package com.unicred.cooperados.app;

import com.unicred.cooperados.domain.entity.Cooperado;
import com.unicred.cooperados.domain.vo.CpfCnpj;
import com.unicred.cooperados.domain.vo.Email;
import com.unicred.cooperados.domain.vo.Telefone;
import com.unicred.cooperados.infra.db.CooperadoRepository;
import org.springframework.dao.DataIntegrityViolationException;
import org.springframework.data.domain.*;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.math.BigDecimal;
import java.time.LocalDate;
import java.util.Optional;

@Service
public class CooperadoService {

    private final CooperadoRepository repo;

    public CooperadoService(CooperadoRepository repo) {
        this.repo = repo;
    }

    @Transactional
    public Cooperado criar(String nome, String doc, LocalDate data, BigDecimal renda, String tel, String email) {
        String docNorm = new CpfCnpj(doc).numero();
        if (repo.findAnyByCpfCnpj(docNorm).isPresent()) {
            throw new ConflictException("CPF/CNPJ já cadastrado.");
        }
        Cooperado c = Cooperado.of(nome, new CpfCnpj(doc), data, renda, new Telefone(tel), email!=null? new Email(email): null);
        try {
            return repo.save(c);
        } catch (DataIntegrityViolationException dup) {
            throw new ConflictException("CPF/CNPJ já cadastrado.");
        }
    }

    @Transactional(readOnly = true)
    public Cooperado obter(String id) {
        return repo.findById(id).orElseThrow(() -> new NotFoundException("Cooperado não encontrado."));
    }

    @Transactional
    public Cooperado atualizar(String id, String nome, String doc, LocalDate data, BigDecimal renda, String tel, String email) {
        Cooperado c = obter(id);
        if (nome != null) c.setNome(nome);
        if (doc != null) {
            String docNorm = new CpfCnpj(doc).numero();
            Optional<Cooperado> existente = repo.findAnyByCpfCnpj(docNorm);
            if (existente.isPresent() && !existente.get().getId().equals(id)) {
                throw new ConflictException("CPF/CNPJ já cadastrado.");
            }
            c.setCpfCnpj(new CpfCnpj(doc));
        }
        if (data != null) c.setDataNascimentoConstituicao(data);
        if (renda != null) c.setRendaFaturamento(renda);
        if (tel != null) c.setTelefone(new Telefone(tel));
        if (email != null) c.setEmail(email.isBlank()? null : new Email(email));
        return repo.save(c);
    }

    @Transactional
    public void excluir(String id) {
        Cooperado c = obter(id);
        repo.delete(c); // @SQLDelete -> soft delete
    }

    @Transactional(readOnly = true)
    public Page<Cooperado> listar(String q, int page, int perPage) {
        Pageable p = PageRequest.of(Math.max(page-1,0), perPage, Sort.by("nome").ascending());
        if (q == null || q.isBlank()) {
            return repo.findAll(p);
        }
        return repo.findByNomeContainingIgnoreCaseOrCpfCnpjContaining(q, q.replaceAll("\\D+",""), p);
    }

    // Exceções de domínio simples
    public static class NotFoundException extends RuntimeException { public NotFoundException(String m){super(m);} }
    public static class ConflictException extends RuntimeException { public ConflictException(String m){super(m);} }
}
