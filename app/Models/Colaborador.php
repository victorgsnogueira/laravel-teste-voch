<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Colaborador extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'email',
        'cpf',
        'unidade_id'
    ];

    protected $casts = [
        'unidade_id' => 'integer',
    ];

    /**
     * Relacionamento com Unidade
     */
    public function unidade(): BelongsTo
    {
        return $this->belongsTo(Unidade::class);
    }

    /**
     * Scope para buscar colaboradores por nome
     */
    public function scopeByNome($query, string $nome)
    {
        return $query->where('nome', 'like', '%' . $nome . '%');
    }

    /**
     * Scope para buscar colaboradores por email
     */
    public function scopeByEmail($query, string $email)
    {
        return $query->where('email', 'like', '%' . $email . '%');
    }

    /**
     * Scope para buscar colaboradores por CPF
     */
    public function scopeByCpf($query, string $cpf)
    {
        return $query->where('cpf', 'like', '%' . $cpf . '%');
    }

    /**
     * Scope para buscar colaboradores por unidade
     */
    public function scopeByUnidade($query, int $unidadeId)
    {
        return $query->where('unidade_id', $unidadeId);
    }

    /**
     * Formata o CPF para exibição
     */
    public function getCpfFormatadoAttribute(): string
    {
        $cpf = preg_replace('/\D/', '', $this->cpf);
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
    }

    /**
     * Retorna as iniciais do nome
     */
    public function getIniciaisAttribute(): string
    {
        $nomes = explode(' ', $this->nome);
        $iniciais = '';
        
        foreach ($nomes as $nome) {
            if (!empty($nome)) {
                $iniciais .= strtoupper(substr($nome, 0, 1));
            }
        }
        
        return $iniciais;
    }

    /**
     * Retorna o primeiro nome
     */
    public function getPrimeiroNomeAttribute(): string
    {
        $nomes = explode(' ', $this->nome);
        return $nomes[0] ?? '';
    }

    /**
     * Retorna informações completas do colaborador
     */
    public function getInfoCompletaAttribute(): string
    {
        return $this->nome . ' - ' . $this->email . ' (' . $this->unidade->nome_fantasia . ')';
    }

    /**
     * Busca colaboradores com filtros
     */
    public static function filtrar(array $filtros)
    {
        $query = self::query();

        if (!empty($filtros['nome'])) {
            $query->byNome($filtros['nome']);
        }

        if (!empty($filtros['email'])) {
            $query->byEmail($filtros['email']);
        }

        if (!empty($filtros['cpf'])) {
            $query->byCpf($filtros['cpf']);
        }

        if (!empty($filtros['unidade_id'])) {
            $query->byUnidade($filtros['unidade_id']);
        }

        return $query;
    }

    /**
     * Validação de CPF
     */
    public function isValidCpf(): bool
    {
        $cpf = preg_replace('/\D/', '', $this->cpf);
        
        if (strlen($cpf) != 11) {
            return false;
        }

        // Verifica se todos os dígitos são iguais
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Validação do primeiro dígito verificador
        for ($i = 0, $j = 10, $soma = 0; $i < 9; $i++, $j--) {
            $soma += $cpf[$i] * $j;
        }

        $resto = $soma % 11;
        if ($cpf[9] != ($resto < 2 ? 0 : 11 - $resto)) {
            return false;
        }

        // Validação do segundo dígito verificador
        for ($i = 0, $j = 11, $soma = 0; $i < 10; $i++, $j--) {
            $soma += $cpf[$i] * $j;
        }

        $resto = $soma % 11;
        return $cpf[10] == ($resto < 2 ? 0 : 11 - $resto);
    }

    /**
     * Validação de email
     */
    public function isValidEmail(): bool
    {
        return filter_var($this->email, FILTER_VALIDATE_EMAIL) !== false;
    }
}

