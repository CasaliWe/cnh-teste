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
        Schema::table('simulados', function (Blueprint $table) {
            $table->string('identificador', 8)->unique()->after('user_id');
            $table->enum('dificuldade', ['facil', 'medio', 'dificil'])->after('identificador');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('simulados', function (Blueprint $table) {
            $table->dropColumn(['identificador', 'dificuldade']);
        });
    }
};
