<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unidade extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome_fantasia',
        'razao_social',
        'cnpj',
        'bandeira_id'
    ];

    protected $casts = [
        'bandeira_id' => 'integer',
    ];

    /**
     * Relacionamento com Bandeira
     */
    public function bandeira(): BelongsTo
    {
        return $this->belongsTo(Bandeira::class);
    }

    /**
     * Relacionamento com Colaboradores
     */
    public function colaboradores(): HasMany
    {
        return $this->hasMany(Colaborador::class);
    }

    /**
     * Scope para buscar unidades por nome fantasia
     */
    public function scopeByNomeFantasia($query, string $nomeFantasia)
    {
        return $query->where('nome_fantasia', 'like', '%' . $nomeFantasia . '%');
    }

    /**
     * Scope para buscar unidades por razão social
     */
    public function scopeByRazaoSocial($query, string $razaoSocial)
    {
        return $query->where('razao_social', 'like', '%' . $razaoSocial . '%');
    }

    /**
     * Scope para buscar unidades por CNPJ
     */
    public function scopeByCnpj($query, string $cnpj)
    {
        return $query->where('cnpj', 'like', '%' . $cnpj . '%');
    }

    /**
     * Scope para buscar unidades por bandeira
     */
    public function scopeByBandeira($query, int $bandeiraId)
    {
        return $query->where('bandeira_id', $bandeiraId);
    }

    /**
     * Verifica se a unidade possui colaboradores associados
     */
    public function hasColaboradores(): bool
    {
        return $this->colaboradores()->exists();
    }

    /**
     * Retorna o número total de colaboradores da unidade
     */
    public function getTotalColaboradoresAttribute(): int
    {
        return $this->colaboradores()->count();
    }

    /**
     * Formata o CNPJ para exibição
     */
    public function getCnpjFormatadoAttribute(): string
    {
        $cnpj = preg_replace('/\D/', '', $this->cnpj);
        return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cnpj);
    }

    /**
     * Retorna o nome completo da unidade (nome fantasia + razão social)
     */
    public function getNomeCompletoAttribute(): string
    {
        return $this->nome_fantasia . ' (' . $this->razao_social . ')';
    }

    /**
     * Verifica se a unidade está ativa (possui colaboradores)
     */
    public function isAtiva(): bool
    {
        return $this->hasColaboradores();
    }

    /**
     * Busca unidades com filtros
     */
    public static function filtrar(array $filtros)
    {
        $query = self::query();

        if (!empty($filtros['nome_fantasia'])) {
            $query->byNomeFantasia($filtros['nome_fantasia']);
        }

        if (!empty($filtros['razao_social'])) {
            $query->byRazaoSocial($filtros['razao_social']);
        }

        if (!empty($filtros['cnpj'])) {
            $query->byCnpj($filtros['cnpj']);
        }

        if (!empty($filtros['bandeira_id'])) {
            $query->byBandeira($filtros['bandeira_id']);
        }

        return $query;
    }

    /**
     * Validação de CNPJ
     */
    public function isValidCnpj(): bool
    {
        $cnpj = preg_replace('/\D/', '', $this->cnpj);
        
        if (strlen($cnpj) != 14) {
            return false;
        }

        // Verifica se todos os dígitos são iguais
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        // Validação dos dígitos verificadores
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;
        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) {
            return false;
        }

        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;
        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }
}

