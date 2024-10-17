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
    $paso1 = PasoCrecimiento::create([
      'nombre' => 'Ingreso a la iglesia',
      'seccion_paso_crecimiento_id' => 1
    ]);

    $paso2 = PasoCrecimiento::create([
      'nombre' => 'Bautismo',
      'seccion_paso_crecimiento_id' => 1
    ]);

    $paso3 = PasoCrecimiento::create([
      'nombre' => 'Encuentro',
      'seccion_paso_crecimiento_id' => 2
    ]);

    $paso4 = PasoCrecimiento::create([
      'nombre' => 'Re-encuentro',
      'seccion_paso_crecimiento_id' => 2
    ]);


    // Le asigno estos pasos al rol con ID 3
    $paso1->roles()->attach(3);
    $paso3->roles()->attach(3);
  }
}
