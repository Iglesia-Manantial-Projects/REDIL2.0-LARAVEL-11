<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\TipoSede;

class TipoSedeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    TipoSede::create([
      'nombre' => 'Sede Principal',
      'descripcion' => '',
    ]);

    TipoSede::create([
      'nombre' => 'Sede',
      'descripcion' => '',
    ]);

    TipoSede::create([
      'nombre' => 'Subsede',
      'descripcion' => '',
    ]);

    TipoSede::create([
      'nombre' => 'Macro Grupo',
      'descripcion' => '',
    ]);
  }
}
