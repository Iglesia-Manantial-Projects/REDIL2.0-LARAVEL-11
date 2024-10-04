<?php

namespace Database\Seeders;

use App\Models\ReporteGrupo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReporteGrupoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

      // Enero 2024
      $reporte = ReporteGrupo::create([
        'grupo_id' => 4,
        'fecha' => '2024-01-01',
        'cantidad_asistencias' => rand(5, 15),
        'tema' => 'El evangelio 1'
      ]);
      $reporte->usuarios()->attach(9, ['asistio' => true]);

      $reporte = ReporteGrupo::create([
        'grupo_id' => 4,
        'fecha' => '2024-01-07',
        'cantidad_asistencias' => rand(5, 15),
        'tema' => 'El evangelio 1'
      ]);
      $reporte->usuarios()->attach(9, ['asistio' => true]);

      $reporte = ReporteGrupo::create([
        'grupo_id' => 4,
        'fecha' => '2024-01-14',
        'cantidad_asistencias' => rand(5, 15),
        'tema' => 'El evangelio 2'
      ]);
      $reporte->usuarios()->attach(9, ['asistio' => true]);

      $reporte = ReporteGrupo::create([
        'grupo_id' => 4,
        'fecha' => '2024-01-21',
        'cantidad_asistencias' => rand(5, 15),
        'tema' => 'El evangelio 3'
      ]);
      $reporte->usuarios()->attach(9, ['asistio' => true]);

      $reporte = ReporteGrupo::create([
        'grupo_id' => 4,
        'fecha' => '2024-01-28',
        'cantidad_asistencias' => rand(5, 15),
        'tema' => 'El evangelio 4'
      ]);
      $reporte->usuarios()->attach(9, ['asistio' => true]);

        // Febrero 2024
        $reporte = ReporteGrupo::create([
          'grupo_id' => 4,
          'fecha' => '2024-02-01',
          'cantidad_asistencias' => rand(5, 15),
          'tema' => 'El evangelio 1'
        ]);
        $reporte->usuarios()->attach(9, ['asistio' => true]);

        $reporte = ReporteGrupo::create([
          'grupo_id' => 4,
          'fecha' => '2024-02-07',
          'cantidad_asistencias' => rand(5, 15),
          'tema' => 'El evangelio 1'
        ]);
        $reporte->usuarios()->attach(9, ['asistio' => true]);

        $reporte = ReporteGrupo::create([
          'grupo_id' => 4,
          'fecha' => '2024-02-14',
          'cantidad_asistencias' => rand(5, 15),
          'tema' => 'El evangelio 2'
        ]);
        $reporte->usuarios()->attach(9, ['asistio' => true]);

        $reporte = ReporteGrupo::create([
          'grupo_id' => 4,
          'fecha' => '2024-02-21',
          'cantidad_asistencias' => rand(5, 15),
          'tema' => 'El evangelio 3'
        ]);
        $reporte->usuarios()->attach(9, ['asistio' => true]);

        $reporte = ReporteGrupo::create([
          'grupo_id' => 4,
          'fecha' => '2024-02-28',
          'cantidad_asistencias' => rand(5, 15),
          'tema' => 'El evangelio 4'
        ]);
        $reporte->usuarios()->attach(9, ['asistio' => true]);

        // Marzo 2024
        $reporte = ReporteGrupo::create([
          'grupo_id' => 4,
          'fecha' => '2024-02-14',
          'cantidad_asistencias' => rand(5, 15),
          'tema' => 'El evangelio 2'
        ]);
        $reporte->usuarios()->attach(9, ['asistio' => false]);

        $reporte = ReporteGrupo::create([
          'grupo_id' => 4,
          'fecha' => '2024-02-21',
          'cantidad_asistencias' => rand(5, 15),
          'tema' => 'El evangelio 3'
        ]);
        $reporte->usuarios()->attach(9, ['asistio' => true]);

        // Abril 2024
        $reporte = ReporteGrupo::create([
          'grupo_id' => 4,
          'fecha' => '2024-04-01',
          'cantidad_asistencias' => rand(5, 15),
          'tema' => 'El retorno del Rey'
        ]);
        $reporte->usuarios()->attach(9, ['asistio' => true]);

        // Junio 2024
        $reporte = ReporteGrupo::create([
          'grupo_id' => 4,
          'fecha' => '2024-06-13',
          'cantidad_asistencias' => rand(5, 15),
          'tema' => 'El retorno del Rey'
        ]);
        $reporte->usuarios()->attach(9, ['asistio' => true]);

    }
}
