<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Sede;

class SedeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Sede::create([
      'nombre' => 'Sede principal',
      'grupo_id' => 1,
      'tipo_sede_id' => 1,
      'continente_id' => 2,
      'foto' => 'sede.png',
      'pais_id' => 45,
      'default' => TRUE
    ]);

    Sede::create([
      'nombre' => 'Sede principal 2',
      'grupo_id' => 2,
      'tipo_sede_id' => 1,
      'continente_id' => 2,
      'foto' => 'sede.png',
      'pais_id' => 45,
      'default' => FALSE
    ]);
  }
}
