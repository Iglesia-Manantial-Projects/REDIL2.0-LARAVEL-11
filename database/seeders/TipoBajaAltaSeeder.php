<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\TipoBajaAlta;

class TipoBajaAltaSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    TipoBajaAlta::create([
      'nombre' => 'Se fue de la ciudad',
      'dado_baja' => 1,
      'dado_alta' => 0,
    ]);

    TipoBajaAlta::create([
      'nombre' => 'Cambió de iglesia',
      'dado_baja' => 1,
      'dado_alta' => 0,
    ]);

    TipoBajaAlta::create([
      'nombre' => 'Regreso',
      'dado_baja' => 0,
      'dado_alta' => 1,
    ]);
  }
}
