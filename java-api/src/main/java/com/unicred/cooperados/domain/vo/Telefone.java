package com.unicred.cooperados.domain.vo;

public record Telefone(String numero) {
    public Telefone {
        String n = numero==null? "": numero.replaceAll("\\D+","");
        if (n.isEmpty()) throw new IllegalArgumentException("Telefone inválido");
        if (n.startsWith("55")) n = n.substring(2);
        if (!n.matches("\\d{10,11}")) throw new IllegalArgumentException("Telefone inválido");
        String ddd = n.substring(0,2);
        String local = n.substring(2);
        if (ddd.charAt(0)=='0') throw new IllegalArgumentException("Telefone inválido");
        if (local.matches("(\\d)\\1+")) throw new IllegalArgumentException("Telefone inválido");
        if (local.length()==9) {
            if (!local.matches("^9\\d{8}$")) throw new IllegalArgumentException("Telefone inválido");
        } else if (local.length()==8) {
            if (!local.matches("^[1-9]\\d{7}$")) throw new IllegalArgumentException("Telefone inválido");
        } else throw new IllegalArgumentException("Telefone inválido");
        numero = "55"+ddd+local; //com DDI 55
    }
}
