package com.unicred.cooperados.domain.entity;

import com.unicred.cooperados.domain.vo.CpfCnpj;
import com.unicred.cooperados.domain.vo.Email;
import com.unicred.cooperados.domain.vo.Telefone;

import jakarta.persistence.Column;
import jakarta.persistence.Entity;
import jakarta.persistence.Id;
import jakarta.persistence.Table;
import jakarta.persistence.UniqueConstraint;

import org.hibernate.annotations.CreationTimestamp;
import org.hibernate.annotations.SQLDelete;
import org.hibernate.annotations.UpdateTimestamp;
import org.hibernate.annotations.Where;

import java.math.BigDecimal;
import java.time.LocalDate;
import java.time.OffsetDateTime;
import java.util.UUID;


@Entity
@Table(name = "cooperados",
       uniqueConstraints = @UniqueConstraint(name="uk_cooperados_cpf_cnpj", columnNames = "cpf_cnpj"))
@SQLDelete(sql = "UPDATE cooperados SET deleted_at = NOW() WHERE id = ?")
@Where(clause = "deleted_at IS NULL")
public class Cooperado {

    @Id
    @Column(columnDefinition = "char(36)")
    private String id;

    @Column(nullable = false)
    private String nome;

    @Column(name = "cpf_cnpj", nullable = false, length = 14)
    private String cpfCnpj;

    @Column(name = "data_nascimento_constituicao", nullable = false)
    private LocalDate dataNascimentoConstituicao;

    @Column(name = "renda_faturamento", nullable = false, precision = 15, scale = 2)
    private BigDecimal rendaFaturamento;

    @Column(nullable = false, length = 20)
    private String telefone; //digits-only com 55

    @Column
    private String email; // opcional

    @CreationTimestamp
    @Column(name="created_at", updatable = false)
    private OffsetDateTime createdAt;

    @UpdateTimestamp
    @Column(name="updated_at")
    private OffsetDateTime updatedAt;

    @Column(name="deleted_at")
    private OffsetDateTime deletedAt;

    protected Cooperado() { }

    public static Cooperado of(String nome, CpfCnpj doc, LocalDate data, BigDecimal renda,
                               Telefone tel, Email emailOpt) {
        Cooperado c = new Cooperado();
        c.id = UUID.randomUUID().toString();
        c.nome = nome.trim();
        c.cpfCnpj = doc.numero();
        c.dataNascimentoConstituicao = data;
        c.rendaFaturamento = renda;
        c.telefone = tel.numero();
        c.email = emailOpt != null ? emailOpt.email() : null;
        return c;
    }

    public String getId() { return id; }
    public String getNome() { return nome; }
    public void setNome(String nome) { this.nome = nome.trim(); }
    public String getCpfCnpj() { return cpfCnpj; }
    public void setCpfCnpj(CpfCnpj doc) { this.cpfCnpj = doc.numero(); }
    public LocalDate getDataNascimentoConstituicao() { return dataNascimentoConstituicao; }
    public void setDataNascimentoConstituicao(LocalDate d) { this.dataNascimentoConstituicao = d; }
    public BigDecimal getRendaFaturamento() { return rendaFaturamento; }
    public void setRendaFaturamento(BigDecimal r) { this.rendaFaturamento = r; }
    public String getTelefone() { return telefone; }
    public void setTelefone(Telefone t) { this.telefone = t.numero(); }
    public String getEmail() { return email; }
    public void setEmail(Email e) { this.email = e!=null? e.email() : null; }
}
