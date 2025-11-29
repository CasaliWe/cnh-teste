<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SimuladoQuestao extends Model
{
    /**
     * Nome da tabela
     */
    protected $table = 'simulado_questoes';

    /**
     * Os atributos que são mass assignable.
     */
    protected $fillable = [
        'questao_id',
        'simulado_id'
    ];

    /**
     * Relacionamento com Questão
     */
    public function questao(): BelongsTo
    {
        return $this->belongsTo(Questao::class);
    }

    /**
     * Relacionamento com Simulado
     */
    public function simulado(): BelongsTo
    {
        return $this->belongsTo(Simulado::class);
    }
}
