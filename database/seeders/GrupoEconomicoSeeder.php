<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GrupoEconomicoSeeder extends Seeder
{
    public function run()
    {
        DB::table('grupo_economicos')->insert(
            collect(range(1, 20))->map(fn ($i) => [
                'nome' => 'Grupo Econômico ' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ])->toArray()
        );
    }
}
