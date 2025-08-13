package com.unicred.cooperados.infra.db;

import com.unicred.cooperados.domain.entity.Cooperado;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.*;
import org.springframework.data.repository.query.Param;

import java.util.Optional;

public interface CooperadoRepository extends JpaRepository<Cooperado, String> {

    // Busca
    @Query(value = "SELECT * FROM cooperados WHERE cpf_cnpj = :doc LIMIT 1", nativeQuery = true)
    Optional<Cooperado> findAnyByCpfCnpj(@Param("doc") String doc);

    // Lista (filtra soft-deleted automaticamente via @Where)
    Page<Cooperado> findByNomeContainingIgnoreCaseOrCpfCnpjContaining(String nome, String cpf, Pageable pageable);
}
