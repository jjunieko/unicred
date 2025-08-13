package com.unicred.cooperados.app;

import com.unicred.cooperados.domain.entity.Cooperado;
import com.unicred.cooperados.domain.vo.CpfCnpj;
import com.unicred.cooperados.domain.vo.Telefone;
import com.unicred.cooperados.infra.db.CooperadoRepository;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;
import org.junit.jupiter.api.extension.ExtendWith;
import org.mockito.*;
import org.mockito.junit.jupiter.MockitoExtension;

import java.math.BigDecimal;
import java.time.LocalDate;
import java.util.Optional;

import static org.junit.jupiter.api.Assertions.*;
import static org.mockito.Mockito.*;

@ExtendWith(MockitoExtension.class)
class CooperadoServiceTest {

    @Mock
    CooperadoRepository repo;

    CooperadoService service;

    @BeforeEach
    void setup() {
        service = new CooperadoService(repo);
    }

    // helper para setar id fixo (a entidade gera UUID internamente)
    private static void setId(Cooperado c, String id) {
        try {
            var f = Cooperado.class.getDeclaredField("id");
            f.setAccessible(true);
            f.set(c, id);
        } catch (Exception e) {
            throw new RuntimeException(e);
        }
    }

    @Test
    void criar_ok_normaliza_e_persiste() {
        when(repo.findAnyByCpfCnpj("11144477735")).thenReturn(Optional.empty());
        when(repo.save(any(Cooperado.class))).thenAnswer(inv -> inv.getArgument(0));

        var c = service.criar(
                "  Fulano  ",
                "111.444.777-35",
                LocalDate.parse("1990-01-02"),
                new BigDecimal("12000.50"),
                "+55 (41) 98888-8888",
                "a@a.com"
        );

        assertNotNull(c.getId());
        assertEquals("Fulano", c.getNome());
        assertEquals("11144477735", c.getCpfCnpj());
        assertEquals(LocalDate.parse("1990-01-02"), c.getDataNascimentoConstituicao());
        assertEquals(new BigDecimal("12000.50"), c.getRendaFaturamento());
        assertEquals("5541988888888", c.getTelefone());
        assertEquals("a@a.com", c.getEmail());

        verify(repo).findAnyByCpfCnpj("11144477735");
        verify(repo).save(any(Cooperado.class));
    }

    @Test
    void criar_duplica_doc_lanca_conflict() {
        var existente = Cooperado.of(
                "Alguem",
                new CpfCnpj("111.444.777-35"),
                LocalDate.parse("1980-01-01"),
                new BigDecimal("500.00"),
                new Telefone("41 3344-1234"),
                null
        );
        setId(existente, "X");
        when(repo.findAnyByCpfCnpj("11144477735")).thenReturn(Optional.of(existente));

        assertThrows(CooperadoService.ConflictException.class, () ->
                service.criar("Fulano", "111.444.777-35",
                        LocalDate.parse("1990-01-02"),
                        new BigDecimal("1000.00"),
                        "41 3344-1234", null)
        );
        verify(repo, never()).save(any());
    }

    @Test
    void atualizar_troca_doc_para_um_ja_usado_por_outro_lanca_conflict() {
        var atual = Cooperado.of(
                "Fulano",
                new CpfCnpj("111.444.777-35"),
                LocalDate.parse("1990-01-01"),
                new BigDecimal("1000.00"),
                new Telefone("41 3344-1234"),
                null
        );
        setId(atual, "A");
        when(repo.findById("A")).thenReturn(Optional.of(atual));

        var outro = Cooperado.of(
                "Beltrano",
                new CpfCnpj("04.252.011/0001-10"),
                LocalDate.parse("2000-02-02"),
                new BigDecimal("2000.00"),
                new Telefone("41 98888-7777"),
                null
        );
        setId(outro, "B");
        when(repo.findAnyByCpfCnpj("04252011000110")).thenReturn(Optional.of(outro));

        assertThrows(CooperadoService.ConflictException.class, () ->
                service.atualizar("A",
                        null,
                        "04.252.011/0001-10",
                        null, null, null, null)
        );

        verify(repo, never()).save(any());
    }

    @Test
    void excluir_chama_delete() {
        var existente = Cooperado.of(
                "Alguem",
                new CpfCnpj("111.444.777-35"),
                LocalDate.parse("1980-01-01"),
                new BigDecimal("500.00"),
                new Telefone("41 3344-1234"),
                null
        );
        setId(existente, "X");
        when(repo.findById("X")).thenReturn(Optional.of(existente));

        service.excluir("X");

        verify(repo).delete(existente);
    }
}
