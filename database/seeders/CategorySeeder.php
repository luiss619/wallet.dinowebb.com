<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            ['id' => 5,  'name' => 'Nómina',                    'status' => 1, 'created_at' => '2023-06-03 21:21:59'],
            ['id' => 9,  'name' => 'Gastos Fijos',              'status' => 1, 'created_at' => '2023-06-03 21:23:58'],
            ['id' => 29, 'name' => 'Informático',               'status' => 1, 'created_at' => '2023-06-03 22:12:02'],
            ['id' => 27, 'name' => 'Ahorro',                    'status' => 1, 'created_at' => '2023-06-03 22:12:02'],
            ['id' => 18, 'name' => 'Desvío de cuenta',          'status' => 1, 'created_at' => '2023-06-03 22:12:02'],
            ['id' => 6,  'name' => 'Ingresos Clientes Fijos',   'status' => 1, 'created_at' => '2023-06-03 21:21:59'],
            ['id' => 30, 'name' => 'Seguridad Social',          'status' => 1, 'created_at' => '2023-06-03 22:12:02'],
            ['id' => 17, 'name' => 'Gastos Extras',             'status' => 1, 'created_at' => '2023-06-03 22:12:02'],
            ['id' => 7,  'name' => 'Ingresos Clientes Variables','status' => 1, 'created_at' => '2023-06-03 21:21:59'],
            ['id' => 31, 'name' => 'Impuestos',                 'status' => 1, 'created_at' => '2023-06-03 22:12:02'],
            ['id' => 16, 'name' => 'Gastos Ocio',               'status' => 1, 'created_at' => '2023-06-03 22:12:02'],
            ['id' => 15, 'name' => 'Gastos Comida',             'status' => 1, 'created_at' => '2023-06-03 22:12:02'],
        ]);
    }
}
