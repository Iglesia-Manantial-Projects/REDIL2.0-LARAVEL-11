<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\PasoCrecimiento;

class PasoCrecimientoSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    PasoCrecimiento::create([
      'nombre' => 'Ingreso a la iglesia',
    ]);

    PasoCrecimiento::create([
      'nombre' => 'Bautismo',
    ]);

    PasoCrecimiento::create([
      'nombre' => 'Encuentro',
    ]);

    PasoCrecimiento::create([
      'nombre' => 'Re-encuentro',
    ]);
  }
}
