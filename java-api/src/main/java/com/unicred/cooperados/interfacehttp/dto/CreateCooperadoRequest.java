package com.unicred.cooperados.interfacehttp.dto;

import jakarta.validation.constraints.*;
import java.math.BigDecimal;

public record CreateCooperadoRequest(
        @NotBlank String nome,
        @NotBlank String cpfCnpj,
        @NotBlank String data,                 // YYYY-MM-DD
        @NotNull @DecimalMin("0.0") BigDecimal rendaFaturamento,
        @NotBlank String telefone,
        @Email String email                    // opcional
) {}
