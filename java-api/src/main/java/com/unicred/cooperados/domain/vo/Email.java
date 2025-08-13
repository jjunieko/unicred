package com.unicred.cooperados.domain.vo;

import java.util.regex.Pattern;

public record Email(String email) {
    private static final Pattern SIMPLE = Pattern.compile("^[^@\\s]+@[^@\\s]+\\.[^@\\s]+$");
    public Email {
        if (email == null || !SIMPLE.matcher(email.trim()).matches()) {
            throw new IllegalArgumentException("Email inválido");
        }
        email = email.trim();
    }
}
