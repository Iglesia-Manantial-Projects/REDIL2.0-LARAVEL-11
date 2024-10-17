<?php

namespace Database\Seeders;

use App\Models\SeccionPasoCrecimiento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeccionPasoCrecimientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      SeccionPasoCrecimiento::create([
        'nombre' => 'Seccion A',
        'orden' => 1
      ]);

      SeccionPasoCrecimiento::create([
        'nombre' => 'Seccion B',
        'orden' => 2
      ]);
    }
}
