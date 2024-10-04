<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\TipoUsuario;

class TipoUsuarioSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    TipoUsuario::create([
      'nombre' => 'Pastor',
      'nombre_plural' => 'Pastores',
      'color' => '#6b2682',
      'icono' => 'ti ti-book',
      'id_rol_dependiente' => 2,
      'puntaje' => 4
    ]);

    TipoUsuario::create([
      'nombre' => 'Lider',
      'nombre_plural' => 'Lideres',
      'color' => '#a251bd',
      'icono' => 'ti ti-star',
      'id_rol_dependiente' => 3,
      'puntaje' => 3
    ]);

    TipoUsuario::create([
      'nombre' => 'Oveja',
      'nombre_plural' => 'Ovejas',
      'color' => '#dd4b39',
      'icono' => 'ti ti-mood-heart',
      'id_rol_dependiente' => 4,
      'default' => TRUE,
      'puntaje' => 2
    ]);

    TipoUsuario::create([
      'nombre' => 'Nuevo',
      'nombre_plural' => 'Nuevos',
      'color' => '#00c0ef',
      'icono' => 'ti ti-mood-smile',
      'id_rol_dependiente' => 5,
      'puntaje' => 1
    ]);

    TipoUsuario::create([
      'nombre' => 'Empleado',
      'nombre_plural' => 'Empleados',
      'color' => '#055498',
      'icono' => 'ti ti-building-skyscraper',
      'id_rol_dependiente' => 6,
      'puntaje' => 0
    ]);

    TipoUsuario::create([
      'nombre' => 'Desarrollador',
      'nombre_plural' => 'Desarrolladores',
      'color' => '#055498',
      'icono' => 'ti ti-building-skyscraper',
      'id_rol_dependiente' => 7,
      'visible' => 0,
      'puntaje' => 0
    ]);
  }
}
