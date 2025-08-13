package com.unicred.cooperados.domain.vo;

import org.junit.jupiter.api.Test;
import static org.junit.jupiter.api.Assertions.*;

public class ValueObjectsTest {


    @Test
    void cnpj_valido_e_invalido() {
        // válido
        CpfCnpj ok = new CpfCnpj("04.252.011/0001-10");
        assertEquals("04252011000110", ok.numero());

        // inválido
        assertThrows(IllegalArgumentException.class, () -> new CpfCnpj("04.252.011/0001-11")); // DV errado
        assertThrows(IllegalArgumentException.class, () -> new CpfCnpj("00.000.000/0000-00")); // repetidos
    }

    @Test
    void telefone_valido_e_invalido() {
        // válidos (normaliza para 55 + DDD + número)
        Telefone movel = new Telefone("+55 (41) 98888-8888");
        assertEquals("5541988888888", movel.numero());

        Telefone fixo = new Telefone("41 3344-1234");
        assertEquals("554133441234", fixo.numero());

        // inválidos
        assertThrows(IllegalArgumentException.class, () -> new Telefone("00000000000"));
        assertThrows(IllegalArgumentException.class, () -> new Telefone("41 1234-567")); // poucos dígitos
    }

}
