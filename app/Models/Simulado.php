<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Simulado extends Model
{
    /**
     * Nome da tabela
     */
    protected $table = 'simulados';

    /**
     * Os atributos que são mass assignable.
     */
    protected $fillable = [
        'user_id',
        'identificador',
        'dificuldade'
    ];

    /**
     * Relacionamento com o usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento many-to-many com Questões através da tabela pivot
     */
    public function questoes(): BelongsToMany
    {
        return $this->belongsToMany(Questao::class, 'simulado_questoes', 'simulado_id', 'questao_id');
    }
}
