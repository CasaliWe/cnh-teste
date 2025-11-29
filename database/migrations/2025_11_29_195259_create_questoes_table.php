<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('questoes', function (Blueprint $table) {
            $table->id();
            $table->string('imagem')->nullable()->comment('Nome da imagem da questão');
            $table->text('pergunta')->comment('Pergunta da questão');
            $table->json('opcoes')->comment('4 opções da questão (a, b, c, d)');
            $table->char('resposta_certa', 1)->comment('Resposta correta (a, b, c ou d)');
            $table->text('dica')->nullable()->comment('Dica para ajudar no nível fácil');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questoes');
    }
};
