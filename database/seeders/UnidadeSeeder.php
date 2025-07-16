<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UnidadeSeeder extends Seeder
{
    public function run()
    {
        DB::table('unidades')->insert(
            collect(range(1, 20))->map(fn($i) => [
                'nome_fantasia' => 'Unidade Fantasia ' . $i,
                'razao_social' => 'Razão Social ' . $i,
                'cnpj' => $this->generateValidCnpj(),
                'bandeira_id' => rand(1, 20),
                'created_at' => now(),
                'updated_at' => now(),
            ])->toArray()
        );
    }

    private function generateValidCnpj(): string
    {
        $n = [];
        for ($i = 0; $i < 8; $i++) {
            $n[$i] = rand(0, 9);
        }

        $n[8] = 0;
        $n[9] = 0;
        $n[10] = 0;
        $n[11] = 1;

        $pesos1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $soma = 0;
        for ($i = 0; $i < 12; $i++) {
            $soma += $n[$i] * $pesos1[$i];
        }
        $d1 = $soma % 11;
        $d1 = ($d1 < 2) ? 0 : 11 - $d1;

        $pesos2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $soma = 0;
        for ($i = 0; $i < 12; $i++) {
            $soma += $n[$i] * $pesos2[$i];
        }
        $soma += $d1 * $pesos2[12];
        $d2 = $soma % 11;
        $d2 = ($d2 < 2) ? 0 : 11 - $d2;

        return sprintf(
            '%d%d.%d%d%d.%d%d%d/%d%d%d%d-%d%d',
            $n[0],
            $n[1],
            $n[2],
            $n[3],
            $n[4],
            $n[5],
            $n[6],
            $n[7],
            $n[8],
            $n[9],
            $n[10],
            $n[11],
            $d1,
            $d2
        );
    }
}
