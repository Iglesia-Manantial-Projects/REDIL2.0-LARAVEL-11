<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

use App\Models\Grupo;

class GrupoSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Grupo::create([
      'nombre' => 'Team Principal',
      'dado_baja' => 0,
      'tipo_grupo_id' => 2,
      'sede_id' => 1,
      'latitud' => '4.0903',
      'longitud' => '-76.2052',
      'dia' => 1,
      'hora' => '17:00:00',
      'fecha_apertura' => '2023-09-05'
    ]);

    Grupo::create([
      'nombre' => 'Team A',
      'dado_baja' => 0,
      'tipo_grupo_id' => 2,
      'sede_id' => 1,
      'latitud' => '4.0962',
      'longitud' => '-76.1940',
      'dia' => 2,
      'hora' => '12:00:00',
      'fecha_apertura' => '2023-09-15'
    ]);

    Grupo::create([
      'nombre' => 'Team B',
      'dado_baja' => 0,
      'tipo_grupo_id' => 2,
      'sede_id' => 2,
      'dia' => 2,
      'hora' => '09:00:00',
      'ultimo_reporte_grupo' => '2024-04-13',
      'fecha_apertura' => '2023-10-20'
    ]);

    Grupo::create([
      'nombre' => 'Team A1',
      'dado_baja' => 0,
      'tipo_grupo_id' => 1,
      'sede_id' => 1,
      'usuario_creacion_id' => 6,
      'latitud' => '3.9008',
      'longitud' => '-76.2937',
      'dia' => 5,
      'dia_planeacion' => 4,
      'rhema' => 'este es el super rhema',
      'hora' => '12:00:00',
      'hora_planeacion' => '11:00:00',
      'ultimo_reporte_grupo' => '2024-06-13',
      'fecha_apertura' => '2023-06-14',
      'tipo_vivienda_id' => 1,
      'direccion' => 'calle falsa 123',
      'telefono' => '123456789',
      'fecha_apertura' => '2024-06-20'
    ]);

    Grupo::create([
      'nombre' => 'Sin lideres',
      'dado_baja' => 0,
      'tipo_grupo_id' => 1,
      'sede_id' => 1,
      'usuario_creacion_id' => 6,
      'dia' => 4,
      'hora' => '12:00:00',
      'fecha_apertura' => '2024-06-22'
    ]);

    Grupo::create([
      'nombre' => 'El inagregable',
      'dado_baja' => 0,
      'tipo_grupo_id' => 3,
      'sede_id' => 1,
      'usuario_creacion_id' => 6,
      'dia' => 1,
      'hora' => '18:00:00'
    ]);

    Grupo::create([
      'nombre' => 'El ineliminable',
      'dado_baja' => 0,
      'tipo_grupo_id' => 4,
      'sede_id' => 1,
      'usuario_creacion_id' => 6,
      'dia' => 7,
      'hora' => '07:00:00'
    ]);

    Grupo::create([
      'nombre' => 'El dado de baja',
      'dado_baja' => 1,
      'tipo_grupo_id' => 1,
      'sede_id' => 1,
      'usuario_creacion_id' => 6,
      'dia' => 7,
      'hora' => '07:00:00'
    ]);

    Grupo::create([
      'nombre' => 'El nuevo',
      'dado_baja' => 0,
      'tipo_grupo_id' => 1,
      'sede_id' => 1,
      'usuario_creacion_id' => 6,
      'dia' => 7,
      'hora' => '07:00:00',
      'fecha_apertura' => '2024-06-14'
    ]);


    DB::table('encargados_grupo')->insert([
      'grupo_id' => 1,
      'user_id' => 2,
    ]);

    DB::table('encargados_grupo')->insert([
      'grupo_id' => 2,
      'user_id' => 3,
    ]);

    DB::table('encargados_grupo')->insert([
      'grupo_id' => 3,
      'user_id' => 4,
    ]);

    DB::table('encargados_grupo')->insert([
      'grupo_id' => 4,
      'user_id' => 6,
    ]);

    // integrantes_grupo
    DB::table('integrantes_grupo')->insert([
      'grupo_id' => 1,
      'user_id' => 3,
    ]);

    DB::table('integrantes_grupo')->insert([
      'grupo_id' => 1,
      'user_id' => 4,
    ]);

    DB::table('integrantes_grupo')->insert([
      'grupo_id' => 2,
      'user_id' => 5,
    ]);

    DB::table('integrantes_grupo')->insert([
      'grupo_id' => 3,
      'user_id' => 5,
    ]);

    DB::table('integrantes_grupo')->insert([
      'grupo_id' => 2,
      'user_id' => 6,
    ]);

    DB::table('integrantes_grupo')->insert([
      'grupo_id' => 4,
      'user_id' => 7,
    ]);

    DB::table('integrantes_grupo')->insert([
      'grupo_id' => 4,
      'user_id' => 9,
    ]);
  }
}
