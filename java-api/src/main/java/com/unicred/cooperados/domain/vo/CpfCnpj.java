package com.unicred.cooperados.domain.vo;

public record CpfCnpj(String numero) {
    public CpfCnpj {
        String nums = numero == null ? "" : numero.replaceAll("\\D+", "");
        if (!isValid(nums)) throw new IllegalArgumentException("CPF/CNPJ inválido");
        numero = nums;
    }
    public static boolean isValid(String n) {
        if (n == null) return false;
        if (n.length() == 11) return validaCpf(n);
        if (n.length() == 14) return validaCnpj(n);
        return false;
    }
    private static boolean validaCpf(String cpf) {
        if (cpf.matches("(\\d)\\1{10}")) return false;
        for (int t=9; t<11; t++) {
            int d=0;
            for (int c=0; c<t; c++) d += (cpf.charAt(c)-'0') * ((t+1)-c);
            d = ((10*d)%11)%10;
            if ((cpf.charAt(t)-'0') != d) return false;
        }
        return true;
    }
    private static boolean validaCnpj(String cnpj) {
        if (cnpj.matches("(\\d)\\1{13}")) return false;
        int[] w1 = {5,4,3,2,9,8,7,6,5,4,3,2};
        int[] w2 = {6,5,4,3,2,9,8,7,6,5,4,3,2};
        int sum=0;
        for (int i=0;i<12;i++) sum += (cnpj.charAt(i)-'0')*w1[i];
        int d1 = sum % 11; d1 = d1<2?0:11-d1;
        if ((cnpj.charAt(12)-'0')!=d1) return false;
        sum=0;
        for (int i=0;i<13;i++) sum += (cnpj.charAt(i)-'0')*w2[i];
        int d2 = sum % 11; d2 = d2<2?0:11-d2;
        return (cnpj.charAt(13)-'0')==d2;
    }
}
