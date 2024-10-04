<?php

namespace Database\Seeders;

use App\Models\ReporteReunion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReporteReunionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

      // Marzo 2024
      $reporte = ReporteReunion::create([
        'reunion_id' => 1,
        'fecha' => '2024-03-02',
      ]);

      $reporte->usuarios()->attach(6, ['asistio' => true]);
      $reporte->usuarios()->attach(11, ['asistio' => false]);


      // Febrero 2024
      $reporte = ReporteReunion::create([
        'reunion_id' => 1,
        'fecha' => '2024-02-10',
      ]);
      $reporte->usuarios()->attach(6, ['asistio' => true]);
      $reporte->usuarios()->attach(11, ['asistio' => false]);

      $reporte = ReporteReunion::create([
        'reunion_id' => 1,
        'fecha' => '2024-02-20',
      ]);
      $reporte->usuarios()->attach(6, ['asistio' => true]);


       // Noviembre 2023
       $reporte = ReporteReunion::create([
        'reunion_id' => 1,
        'fecha' => '2023-11-10',
      ]);
      $reporte->usuarios()->attach(6, ['asistio' => true]);

      $reporte = ReporteReunion::create([
        'reunion_id' => 1,
        'fecha' => '2023-11-20',
      ]);
      $reporte->usuarios()->attach(6, ['asistio' => true]);


    }


}
