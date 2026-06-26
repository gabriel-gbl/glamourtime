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
        Schema::create('horarios_disponiveis', function (Blueprint $table) {
            $table->id();
            $table->date('data');
            $table->time('hora_inicio');
            $table->time('hora_fim');
            $table->boolean('disponivel')->default(true);
            $table->timestamps(); // criado_em / atualizado_em
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios_disponiveis');
    }
};
