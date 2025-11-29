<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Questao extends Model
{
    /**
     * Nome da tabela
     */
    protected $table = 'questoes';

    /**
     * Os atributos que são mass assignable.
     */
    protected $fillable = [
        'imagem',
        'pergunta',
        'opcoes',
        'resposta_certa',
        'dica'
    ];

    /**
     * Os atributos que devem ser cast.
     */
    protected $casts = [
        'opcoes' => 'array',
    ];

    /**
     * Relacionamento many-to-many com Simulados através da tabela pivot
     */
    public function simulados(): BelongsToMany
    {
        return $this->belongsToMany(Simulado::class, 'simulado_questoes', 'questao_id', 'simulado_id');
    }
}
