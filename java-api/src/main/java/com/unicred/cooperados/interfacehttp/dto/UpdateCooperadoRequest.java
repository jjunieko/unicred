package com.unicred.cooperados.interfacehttp.dto;

import java.math.BigDecimal;

public record UpdateCooperadoRequest(
        String nome,
        String cpfCnpj,
        String data,                  // YYYY-MM-DD
        BigDecimal rendaFaturamento,
        String telefone,
        String email
) {}

