<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubcategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('subcategories')->insert([
            // Gastos Fijos (category_id = 9)
            ['id' => 1,  'category_id' => 9,  'name' => 'Alquiler piso',                 'status' => 1, 'created_at' => '2023-06-03 21:21:59'],
            ['id' => 2,  'category_id' => 9,  'name' => 'Servicios hogar',               'status' => 1, 'created_at' => '2023-06-03 21:21:59'],
            ['id' => 47, 'category_id' => 9,  'name' => 'Transporte',                    'status' => 1, 'created_at' => '2023-06-03 21:21:59'],
            ['id' => 48, 'category_id' => 9,  'name' => 'Documentos',                    'status' => 1, 'created_at' => '2023-06-03 21:21:59'],
            // Gastos Comida (category_id = 15)
            ['id' => 18, 'category_id' => 15, 'name' => 'Supermercados y Badulaques',    'status' => 1, 'created_at' => '2023-06-03 21:21:59'],
            ['id' => 19, 'category_id' => 15, 'name' => 'Por clasificar',                'status' => 1, 'created_at' => '2023-06-03 21:21:59'],
            ['id' => 30, 'category_id' => 15, 'name' => 'Restaurantes',                  'status' => 1, 'created_at' => '2023-06-03 21:21:59'],
            ['id' => 31, 'category_id' => 15, 'name' => 'Cafetería y Pastelería',        'status' => 1, 'created_at' => '2023-06-03 21:21:59'],
            // Gastos Ocio (category_id = 16)
            ['id' => 8,  'category_id' => 16, 'name' => 'Belleza, Salud y Deporte',      'status' => 1, 'created_at' => '2023-06-03 21:21:59'],
            ['id' => 9,  'category_id' => 16, 'name' => 'Streaming',                     'status' => 1, 'created_at' => '2023-06-03 21:21:59'],
            ['id' => 10, 'category_id' => 16, 'name' => 'Cine',                          'status' => 1, 'created_at' => '2023-06-03 21:21:59'],
            ['id' => 28, 'category_id' => 16, 'name' => 'Quedadas',                      'status' => 1, 'created_at' => '2023-06-03 21:21:59'],
            ['id' => 36, 'category_id' => 16, 'name' => 'Ropa',                          'status' => 1, 'created_at' => '2023-06-03 21:21:59'],
            ['id' => 39, 'category_id' => 16, 'name' => 'Compras General',               'status' => 1, 'created_at' => '2023-06-03 21:21:59'],
            ['id' => 44, 'category_id' => 16, 'name' => 'Videojuegos',                   'status' => 1, 'created_at' => '2023-06-03 21:21:59'],
            ['id' => 45, 'category_id' => 16, 'name' => 'Vacaciones',                    'status' => 1, 'created_at' => '2023-06-03 21:21:59'],
            ['id' => 49, 'category_id' => 16, 'name' => 'Relaxing',                      'status' => 1, 'created_at' => '2023-06-03 21:21:59'],
            // Gastos Extras (category_id = 17)
            ['id' => 3,  'category_id' => 17, 'name' => 'Gastos Bancarios',              'status' => 1, 'created_at' => '2023-06-03 21:21:59'],
            ['id' => 7,  'category_id' => 17, 'name' => 'Coche',                         'status' => 1, 'created_at' => '2023-06-03 21:21:59'],
            ['id' => 14, 'category_id' => 17, 'name' => 'Vivari como Informático',       'status' => 1, 'created_at' => '2023-06-03 21:21:59'],
            ['id' => 16, 'category_id' => 17, 'name' => 'Mossen Clapes',                 'status' => 1, 'created_at' => '2023-06-03 21:21:59'],
            ['id' => 35, 'category_id' => 17, 'name' => 'Negocios - Hostelería',         'status' => 1, 'created_at' => '2023-06-03 21:21:59'],
            ['id' => 46, 'category_id' => 17, 'name' => 'Prestamos Negocios',            'status' => 1, 'created_at' => '2023-06-03 21:21:59'],
        ]);
    }
}
