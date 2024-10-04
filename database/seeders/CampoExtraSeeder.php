<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\CampoExtra;
use Illuminate\Support\Facades\DB;

class CampoExtraSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Id: 1
    CampoExtra::create([
      'nombre' => 'Red',
      'tipo_de_campo' => 3,
      'required' => true,
      'class_col' => 'col-lg-3 col-sm-12 col-12 col-md-4',
      'class_id' => 'ministerio_asociado',
      'opciones_select' =>
        '[{"id": "1","nombre":"PRE JUVENIL","visible":"1","value":"1"},{"id": "2","nombre":"JUVENIL","visible":"1","value":"2"}]',
      'visible' => true,
    ]);

    // Id: 2
    CampoExtra::create([
      'nombre' => 'Número de hijos',
      'tipo_de_campo' => 1,
      'required' => false,
      'class_col' => 'col-lg-3 col-sm-12 col-12 col-md-3',
      'class_id' => 'hijos',
      'opciones_select' => '',
      'visible' => true,
    ]);

    // Id: 3
    CampoExtra::create([
      'nombre' => 'Multiple',
      'tipo_de_campo' => 4,
      'required' => false,
      'class_col' => 'col-lg-3 col-sm-12 col-12 col-md-3',
      'class_id' => 'multiple',
      'opciones_select' => '[{"id": "1","nombre":"AAA","visible":"1","value":"1"},{"id": "2","nombre":"BBB","visible":"1","value":"2"}]',
      'visible' => true,
    ]);

    // Id: 4
    CampoExtra::create([
      'nombre' => 'Textarea',
      'tipo_de_campo' => 2,
      'required' => false,
      'class_col' => 'col-lg-3 col-sm-12 col-12 col-md-3',
      'class_id' => 'textarea',
      'opciones_select' => '',
      'visible' => true,
    ]);


    // relación con el formulario 3
    DB::table('campos_extras_formularios')->insert([
      'campo_extra_id' => 1,
      'formulario_id' => 3,
      'visible' => TRUE,
      'required' => TRUE,
    ]);

    DB::table('campos_extras_formularios')->insert([
      'campo_extra_id' => 2,
      'formulario_id' => 3,
      'visible' => TRUE,
      'required' => TRUE,
    ]);

    DB::table('campos_extras_formularios')->insert([
      'campo_extra_id' => 3,
      'formulario_id' => 3,
      'visible' => TRUE,
      'required' => TRUE,
    ]);

    DB::table('campos_extras_formularios')->insert([
      'campo_extra_id' => 4,
      'formulario_id' => 3,
      'visible' => TRUE,
      'required' => TRUE,
    ]);

    // relación con el formulario 4
    DB::table('campos_extras_formularios')->insert([
      'campo_extra_id' => 1,
      'formulario_id' => 4,
      'visible' => TRUE,
      'required' => TRUE,
    ]);

    DB::table('campos_extras_formularios')->insert([
      'campo_extra_id' => 2,
      'formulario_id' => 4,
      'visible' => TRUE,
      'required' => TRUE,
    ]);

    DB::table('campos_extras_formularios')->insert([
      'campo_extra_id' => 3,
      'formulario_id' => 4,
      'visible' => TRUE,
      'required' => TRUE,
    ]);

    DB::table('campos_extras_formularios')->insert([
      'campo_extra_id' => 4,
      'formulario_id' => 4,
      'visible' => TRUE,
      'required' => TRUE,
    ]);

  }
}
