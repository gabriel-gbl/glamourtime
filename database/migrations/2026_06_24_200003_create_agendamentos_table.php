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
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('servico_id')->constrained('servicos')->onDelete('restrict');
            $table->dateTime('data_hora');
            $table->enum('status', ['pendente', 'confirmado', 'cancelado', 'concluido'])->default('pendente');
            $table->text('observacoes')->nullable();
            $table->timestamps(); // criado_em / atualizado_em
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendamentos');
    }
};
