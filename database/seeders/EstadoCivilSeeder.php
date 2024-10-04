<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\EstadoCivil;

class EstadoCivilSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    EstadoCivil::create([
      'nombre' => 'Soltero',
    ]);

    EstadoCivil::create([
      'nombre' => 'Casado por lo civil',
    ]);

    EstadoCivil::create([
      'nombre' => 'Casado por la iglesia',
    ]);

    EstadoCivil::create([
      'nombre' => 'Unión libre',
    ]);

    EstadoCivil::create([
      'nombre' => 'Divorciado',
    ]);

    EstadoCivil::create([
      'nombre' => 'Viudo',
    ]);

    EstadoCivil::create([
      'nombre' => 'Separado',
    ]);

    EstadoCivil::create([
      'nombre' => 'Casado por ambas',
    ]);
  }
}
