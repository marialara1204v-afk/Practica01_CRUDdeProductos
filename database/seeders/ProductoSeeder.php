<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Producto::create([
            'nombre' => 'Producto de prueba 1',
            'descripcion' => 'Descripción del primer producto.',
            'precio' => 19.99,
            'stock' => 10,
        ]);

        Producto::create([
            'nombre' => 'Producto de prueba 2',
            'descripcion' => 'Descripción del segundo producto.',
            'precio' => 29.99,
            'stock' => 5,
        ]);
    }
}
