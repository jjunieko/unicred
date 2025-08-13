<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cooperados', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->string('nome');
            $t->string('cpf_cnpj', 14)->unique();
            $t->date('data_nascimento_constituicao');
            $t->decimal('renda_faturamento', 15, 2);
            $t->string('telefone', 20);
            $t->string('email')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });
    }
    public function down(): void {
        Schema::dropIfExists('cooperados');
    }
};

