<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Agendamento extends Model
{
    use HasFactory;

    protected $table = 'agendamentos';

    protected $fillable = [
        'usuario_id',
        'servico_id',
        'data_hora',
        'status',
        'observacoes',
    ];

    protected $casts = [
        'data_hora' => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** O usuário (cliente) que fez o agendamento. */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /** O serviço agendado. */
    public function servico(): BelongsTo
    {
        return $this->belongsTo(Servico::class, 'servico_id');
    }

    /**
     * O pagamento associado a este agendamento (relação 1:1).
     * Só existe se o agendamento possuir um pagamento registrado.
     */
    public function pagamento(): HasOne
    {
        return $this->hasOne(Pagamento::class, 'agendamento_id');
    }
}
