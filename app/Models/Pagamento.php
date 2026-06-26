<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pagamento extends Model
{
    use HasFactory;

    protected $table = 'pagamentos';

    /**
     * O modelo não usa updated_at (apenas criado_em conforme o diagrama).
     */
    public $timestamps = false;

    protected $fillable = [
        'agendamento_id',
        'valor',
        'forma_pagamento',
        'data_pagamento',
        'criado_em',
    ];

    protected $casts = [
        'valor'          => 'decimal:2',
        'data_pagamento' => 'datetime',
        'criado_em'      => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** O agendamento ao qual este pagamento pertence. */
    public function agendamento(): BelongsTo
    {
        return $this->belongsTo(Agendamento::class, 'agendamento_id');
    }
}
