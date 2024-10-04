<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\TipoParentesco;

class TipoParentescoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          TipoParentesco::create([
          'nombre' => '	Padre/Madre',
          'relacionado_con' => '2',
          'nombre_masculino'=>'Padre',
          'nombre_femenino'=>'Madre'
        ]);

        TipoParentesco::create([
          'nombre' => '	Hijo/Hija',
          'relacionado_con' => '1',
          'nombre_masculino'=>'Hijo',
          'nombre_femenino'=>'Hija'
        ]);

        TipoParentesco::create([
          'nombre' => '	Abuelo/Abuela',
          'relacionado_con' => '4',
          'nombre_masculino'=>'Abuelo',
          'nombre_femenino'=>'Abuela'
        ]);

        TipoParentesco::create([
          'nombre' => '	Nieto/Nieta',
          'relacionado_con' => '3',
          'nombre_masculino'=>'Nieto',
          'nombre_femenino'=>'Nieta'
        ]);

        TipoParentesco::create([
          'nombre' => 'Esposo/Esposa',
          'relacionado_con' => null,
          'nombre_masculino'=>'Esposo',
          'nombre_femenino'=>'Esposa'
        ]);

        TipoParentesco::create([
          'nombre' => 'Hermanos',
          'relacionado_con' => null,
          'nombre_masculino'=>'Hermano',
          'nombre_femenino'=>'Hermana'
        ]);

        TipoParentesco::create([
          'nombre' => 'Primos',
          'relacionado_con' => null,
          'nombre_masculino'=>'Primo',
          'nombre_femenino'=>'Prima'
        ]);


        TipoParentesco::create([
          'nombre' => 'Tío/Tía',
          'relacionado_con' => '9',
          'nombre_masculino'=>'Tío',
          'nombre_femenino'=>'Tía'
        ]);

        TipoParentesco::create([
          'nombre' => 'Sobrino/Sobrina',
          'relacionado_con' => '8',
          'nombre_masculino'=>'Sobrino',
          'nombre_femenino'=>'Sobrina'
        ]);


        TipoParentesco::create([
          'nombre' => 'Cuñados',
          'relacionado_con' => null,
          'nombre_masculino'=>'Cuñado',
          'nombre_femenino'=>'Cuñada'
        ]);

        TipoParentesco::create([
          'nombre' => '	Padrastro/Madrastra',
          'relacionado_con' => '12',
          'nombre_masculino'=>'Padrastro',
          'nombre_femenino'=>'Madrastra'
        ]);

        TipoParentesco::create([
          'nombre' => '	Hermanastro/Hermanastra',
          'relacionado_con' => '11',
          'nombre_masculino'=>'Hermanastro',
          'nombre_femenino'=>'Hermanastra'
        ]);


  

      /*
      $path = Storage::path('archivos_desarrollador/tipos_parentesco.sql');
      DB::unprepared(file_get_contents($path));*/
    }
}
