<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\TipoVinculacion;

class TipoVinculacionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    TipoVinculacion::create([
      'nombre' => 'Grupo familiar',
    ]);

    TipoVinculacion::create([
      'nombre' => 'Culto',
    ]);

    TipoVinculacion::create([
      'nombre' => 'Internet',
    ]);

    TipoVinculacion::create([
      'nombre' => 'Emisora u otro',
    ]);

    TipoVinculacion::create([
      'nombre' => 'Campaña conéctate',
    ]);
  }
}
