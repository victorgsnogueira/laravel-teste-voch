<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BandeiraSeeder extends Seeder
{
    public function run()
    {
        DB::table('bandeiras')->insert(
            collect(range(1, 20))->map(fn ($i) => [
                'nome' => 'Bandeira ' . $i,
                'grupo_economico_id' => rand(1, 20),
                'created_at' => now(),
                'updated_at' => now(),
            ])->toArray()
        );
    }
}
