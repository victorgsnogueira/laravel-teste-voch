<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ColaboradoresExport implements FromCollection, WithHeadings
{
    protected Collection $dados;

    public function __construct(Collection $dados)
    {
        $this->dados = $dados;
    }

    public function collection(): Collection
    {
        return $this->dados;
    }

    public function headings(): array
    {
        return ['ID', 'Nome', 'Email', 'CPF', 'Unidade ID'];
    }
}
