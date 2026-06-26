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
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agendamento_id')->unique()->constrained('agendamentos')->onDelete('cascade');
            $table->decimal('valor', 10, 2);
            $table->string('forma_pagamento', 50); // Ex: Pix, Cartão, Dinheiro
            $table->dateTime('data_pagamento')->nullable();
            $table->timestamp('criado_em')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagamentos');
    }
};
