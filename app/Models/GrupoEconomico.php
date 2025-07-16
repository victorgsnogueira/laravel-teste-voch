<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GrupoEconomico extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome'
    ];

    /**
     * Relacionamento com Bandeiras
     */
    public function bandeiras(): HasMany
    {
        return $this->hasMany(Bandeira::class);
    }

    /**
     * Scope para buscar grupos econômicos por nome
     */
    public function scopeByNome($query, string $nome)
    {
        return $query->where('nome', 'like', '%' . $nome . '%');
    }

    /**
     * Verifica se o grupo econômico possui bandeiras associadas
     */
    public function hasBandeiras(): bool
    {
        return $this->bandeiras()->exists();
    }

    /**
     * Retorna o número total de bandeiras do grupo econômico
     */
    public function getTotalBandeirasAttribute(): int
    {
        return $this->bandeiras()->count();
    }

    /**
     * Retorna o número total de unidades através das bandeiras
     */
    public function getTotalUnidadesAttribute(): int
    {
        return $this->bandeiras()
            ->withCount('unidades')
            ->get()
            ->sum('unidades_count');
    }

    /**
     * Verifica se o grupo econômico está ativo (possui bandeiras)
     */
    public function isAtivo(): bool
    {
        return $this->hasBandeiras();
    }

    /**
     * Busca grupos econômicos com filtros
     */
    public static function filtrar(array $filtros)
    {
        $query = self::query();

        if (!empty($filtros['nome'])) {
            $query->byNome($filtros['nome']);
        }

        return $query;
    }
}

