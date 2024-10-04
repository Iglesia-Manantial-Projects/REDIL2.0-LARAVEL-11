<?php

namespace Database\Seeders;

use App\Models\AutomatizacionPasoCrecimiento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AutomatizacionPasoCrecimientoSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    AutomatizacionPasoCrecimiento::create([
      'paso_crecimiento_id' => 4, //re-encuentro
      'estado_paso_crecimiento' => 3, // finalizado
      'tipo_usuario_a_modificar' => 2 // lider
    ]);
  }
}
