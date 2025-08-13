package com.unicred.cooperados.interfacehttp.dto;

import com.unicred.cooperados.domain.entity.Cooperado;
import java.math.BigDecimal;

public record CooperadoResponse(
        String id,
        String nome,
        String cpfCnpj,
        String data,
        BigDecimal rendaFaturamento,
        String telefone,
        String email
) {
    public static CooperadoResponse from(Cooperado c) {
        return new CooperadoResponse(
                c.getId(),
                c.getNome(),
                c.getCpfCnpj(),
                c.getDataNascimentoConstituicao().toString(),
                c.getRendaFaturamento(),
                c.getTelefone(),
                c.getEmail()
        );
    }
}
