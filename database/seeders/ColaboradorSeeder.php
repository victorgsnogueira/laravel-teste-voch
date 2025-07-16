<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ColaboradorSeeder extends Seeder
{
    public function run()
    {
        DB::table('colaboradors')->insert(
            collect(range(1, 20))->map(fn ($i) => [
                'nome' => 'Colaborador ' . $i,
                'email' => 'colaborador' . uniqid() . '@example.com',
                'cpf' => $this->generateValidCpf(),
                'unidade_id' => DB::table('unidades')->inRandomOrder()->value('id'),
                'created_at' => now(),
                'updated_at' => now(),
            ])->toArray()
        );
    }

    private function generateValidCpf(): string
    {
        $n = [rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9)];

        $d1 = 0;
        for ($i = 0, $j = 10; $i < 9; $i++, $j--) {
            $d1 += $n[$i] * $j;
        }
        $d1 = 11 - ($d1 % 11);
        if ($d1 >= 10) $d1 = 0;

        $d2 = 0;
        for ($i = 0, $j = 11; $i < 9; $i++, $j--) {
            $d2 += $n[$i] * $j;
        }
        $d2 += $d1 * 2;
        $d2 = 11 - ($d2 % 11);
        if ($d2 >= 10) $d2 = 0;

        return sprintf('%03d.%03d.%03d-%02d',
            $n[0] * 100 + $n[1] * 10 + $n[2],
            $n[3] * 100 + $n[4] * 10 + $n[5],
            $n[6] * 100 + $n[7] * 10 + $n[8],
            $d1 * 10 + $d2
        );
    }
}
