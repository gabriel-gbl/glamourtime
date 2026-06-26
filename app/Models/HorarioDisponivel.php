<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class HorarioDisponivel extends Model
{
    use HasFactory;

    protected $table = 'horarios_disponiveis';

    protected $fillable = [
        'data',
        'hora_inicio',
        'hora_fim',
        'disponivel',
    ];

    protected $casts = [
        'data'       => 'date',
        'disponivel' => 'boolean',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /**
     * Um horário disponível pode ter no máximo um agendamento.
     * Se não houver agendamento, o horário permanece disponível.
     */
    public function agendamento(): HasOne
    {
        return $this->hasOne(Agendamento::class, 'data_hora', 'hora_inicio');
    }
}
