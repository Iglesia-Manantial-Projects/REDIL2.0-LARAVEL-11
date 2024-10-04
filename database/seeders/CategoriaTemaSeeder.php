<?php

namespace Database\Seeders;

use App\Models\CategoriaTema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriaTemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        CategoriaTema::create([
            'nombre'=> 'Devocional',
            
        ]);
        CategoriaTema::create([
            'nombre'=> 'Jesús',
            
        ]);

        CategoriaTema::create([
            'nombre'=> 'Espiritu Santo',
            
        ]);

        CategoriaTema::create([
            'nombre'=> 'Alabanza',
            
        ]);

        CategoriaTema::create([
            'nombre'=> 'Parejas',
            
        ]);
    }
}
