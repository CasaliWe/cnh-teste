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
        Schema::create('simulado_questoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questao_id')->constrained('questoes')->onDelete('cascade');
            $table->foreignId('simulado_id')->constrained('simulados')->onDelete('cascade');
            $table->timestamps();
            
            // Índice único para evitar questões duplicadas no mesmo simulado
            $table->unique(['questao_id', 'simulado_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simulado_questoes');
    }
};
