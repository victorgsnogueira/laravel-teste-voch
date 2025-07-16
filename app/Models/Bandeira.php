<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bandeira extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'grupo_economico_id'
    ];

    protected $casts = [
        'grupo_economico_id' => 'integer',
    ];

    /**
     * Relacionamento com GrupoEconomico
     */
    public function grupoEconomico(): BelongsTo
    {
        return $this->belongsTo(GrupoEconomico::class);
    }

    /**
     * Relacionamento com Unidades
     */
    public function unidades(): HasMany
    {
        return $this->hasMany(Unidade::class);
    }

    /**
     * Scope para buscar bandeiras por nome
     */
    public function scopeByNome($query, string $nome)
    {
        return $query->where('nome', 'like', '%' . $nome . '%');
    }

    /**
     * Scope para buscar bandeiras por grupo econômico
     */
    public function scopeByGrupoEconomico($query, int $grupoEconomicoId)
    {
        return $query->where('grupo_economico_id', $grupoEconomicoId);
    }

    /**
     * Verifica se a bandeira possui unidades associadas
     */
    public function hasUnidades(): bool
    {
        return $this->unidades()->exists();
    }

    /**
     * Retorna o número total de unidades da bandeira
     */
    public function getTotalUnidadesAttribute(): int
    {
        return $this->unidades()->count();
    }

    /**
     * Retorna o nome completo da bandeira com grupo econômico
     */
    public function getNomeCompletoAttribute(): string
    {
        return $this->nome . ' (' . $this->grupoEconomico->nome . ')';
    }

    /**
     * Verifica se a bandeira está ativa (possui unidades)
     */
    public function isAtiva(): bool
    {
        return $this->hasUnidades();
    }

    /**
     * Busca bandeiras com filtros
     */
    public static function filtrar(array $filtros)
    {
        $query = self::query();

        if (!empty($filtros['nome'])) {
            $query->byNome($filtros['nome']);
        }

        if (!empty($filtros['grupo_economico_id'])) {
            $query->byGrupoEconomico($filtros['grupo_economico_id']);
        }

        return $query;
    }
}

