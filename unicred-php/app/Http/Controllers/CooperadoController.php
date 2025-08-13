<?php

namespace App\Http\Controllers;

use App\Application\Cooperado\DTOs\CreateCooperadoDTO;
use App\Application\Cooperado\UseCases\{CreateCooperado,UpdateCooperado,GetCooperado,ListCooperados,SoftDeleteCooperado};
use App\Http\Requests\{StoreCooperadoRequest,UpdateCooperadoRequest};
use Illuminate\Http\Request;

class CooperadoController
{
    public function store(StoreCooperadoRequest $req, CreateCooperado $usecase)
    {
        $dto = new CreateCooperadoDTO(
            $req->input('nome'),
            $req->input('cpfCnpj'),
            $req->input('data'),
            (float)$req->input('rendaFaturamento'),
            $req->input('telefone'),
            $req->input('email')
        );
        $c = $usecase->handle($dto);
        return response()->json($this->present($c), 201);
    }

    public function update(string $id, UpdateCooperadoRequest $req, UpdateCooperado $usecase)
    {
        $c = $usecase->handle($id, $req->validated());
        return response()->json($this->present($c));
    }

    public function show(string $id, GetCooperado $usecase)
    {
        $c = $usecase->handle($id);
        return $c ? response()->json($this->present($c)) : response()->json(['message'=>'Not found'],404);
    }

    public function index(Request $req, ListCooperados $usecase)
    {
        $data = $usecase->handle(
            (int)$req->get('page',1),
            (int)$req->get('perPage',20),
            $req->get('q')
        );
        return response()->json(array_map([$this,'present'],$data));
    }

    public function destroy(string $id, SoftDeleteCooperado $usecase)
    {
        $usecase->handle($id);
        return response()->json([],204);
    }

    private function present($c): array {
        return [
            'id' => $c->id,
            'nome' => $c->nome,
            'cpfCnpj' => $c->cpfCnpj->numero,
            'data' => $c->dataNascimentoConstituicao->format('Y-m-d'),
            'rendaFaturamento' => $c->rendaFaturamento,
            'telefone' => $c->telefone->numero,
            'email' => $c->email?->email
        ];
    }
}
