<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\TipoServicioGrupo;

class TipoServicioGrupoSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    TipoServicioGrupo::create([
      'nombre' => 'Anfitrion',
      'descripcion' => '',
    ]);

    TipoServicioGrupo::create([
      'nombre' => 'Tesorero',
      'descripcion' => '',
    ]);

    TipoServicioGrupo::create([
      'nombre' => 'Timoteo',
      'descripcion' => '',
    ]);
  }
}
