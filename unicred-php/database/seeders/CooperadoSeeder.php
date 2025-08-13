<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Infrastructure\Persistence\Eloquent\Models\CooperadoModel;

class CooperadoSeeder extends Seeder
{
    public function run(): void
    {
        // evite duplicação de dados!!!
        if (CooperadoModel::count() > 0) {
            $this->command?->info('Cooperados já existem — seed ignorado.');
            return;
        }

        $rows = [
            [
                'id'   => (string) Str::uuid(),
                'nome' => 'Fulano de Tal',
                'cpf_cnpj' => '11144477735',            // CPF válido
                'data_nascimento_constituicao' => '1990-01-02',
                'renda_faturamento' => 12000.50,
                'telefone' => '5541988888888',          // +55 41 98888-8888
                'email' => 'fulano@ex.com',
            ],
            [
                'id'   => (string) Str::uuid(),
                'nome' => 'Empresa Exemplo LTDA',
                'cpf_cnpj' => '04252011000110',         // CNPJ válido
                'data_nascimento_constituicao' => '2005-05-20',
                'renda_faturamento' => 250000.00,
                'telefone' => '554133441234',           // +55 41 3344-1234 (fixo)
                'email' => 'contato@empresa.com',
            ],
        ];

        foreach ($rows as $data) {
            CooperadoModel::create($data);
        }

        $this->command?->info('Seed de cooperados concluído.');
    }
}
