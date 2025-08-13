package com.unicred.cooperados.interfacehttp;

import com.unicred.cooperados.app.CooperadoService;
import com.unicred.cooperados.interfacehttp.dto.*;
import org.springframework.data.domain.Page;
import org.springframework.web.bind.annotation.*;

import jakarta.validation.Valid;
import java.math.BigDecimal;
import java.time.LocalDate;

@RestController
@RequestMapping("/api/cooperados")
public class CooperadoController {

    private final CooperadoService service;

    public CooperadoController(CooperadoService service) { this.service = service; }

    @PostMapping
    public CooperadoResponse create(@Valid @RequestBody CreateCooperadoRequest req) {
        var c = service.criar(
                req.nome(),
                req.cpfCnpj(),
                LocalDate.parse(req.data()),
                req.rendaFaturamento(),
                req.telefone(),
                req.email()
        );
        return CooperadoResponse.from(c);
    }

    @GetMapping("/{id}")
    public CooperadoResponse get(@PathVariable("id") String id) {
        return CooperadoResponse.from(service.obter(id));
    }

    @GetMapping
    public Page<CooperadoResponse> list(
        @RequestParam(name = "q", required = false) String q,
        @RequestParam(name = "page", defaultValue = "1") int page,
        @RequestParam(name = "perPage", defaultValue = "20") int perPage
    ) {
        return service.listar(q, page, perPage).map(CooperadoResponse::from);
    }

    @PutMapping("/{id}")
    public CooperadoResponse update(
        @PathVariable("id") String id,
        @Valid @RequestBody UpdateCooperadoRequest req
    ) {
        var c = service.atualizar(
            id,
            req.nome(),
            req.cpfCnpj(),
            req.data() != null ? java.time.LocalDate.parse(req.data()) : null,
            req.rendaFaturamento(),
            req.telefone(),
            req.email()
        );
        return CooperadoResponse.from(c);
    }

    @DeleteMapping("/{id}")
    public void delete(@PathVariable("id") String id) {
        service.excluir(id);
    }
}
