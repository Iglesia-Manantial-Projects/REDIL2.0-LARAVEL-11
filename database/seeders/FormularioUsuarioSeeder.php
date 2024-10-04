<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

use App\Models\FormularioUsuario;

class FormularioUsuarioSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    FormularioUsuario::create([
      'nombre' => 'Formulario prueba',
      'action' => 'usuario.editar',
      'privilegio' => 'opcion_modificar_asistente',
      'edad_minima' => 18,
      'edad_maxima' => 200,
      'class_identificacion' => 'class',
      'class_fecha_nacimiento' => 'class',
      'class_genero' => 'class',
      'class_primer_nombre' => 'class',
      'class_primer_apellido' => 'class',
      'class_segundo_nombre' => 'class',
      'class_segundo_apellido' => 'class',
      'class_estado_civil' => 'class',
      'class_pais_nacimiento' => 'class',
      'class_telefono_fijo' => 'class',
      'class_telefono_movil' => 'class',
      'class_telefono_otro' => 'class',
      'class_email' => 'class',
      'class_direccion' => 'class',
      'class_vivienda_en_calidad_de' => 'class',
      'class_nivel_academico' => 'class',
      'class_estado_nivel_academico' => 'class',
      'class_profesion' => 'class',
      'class_ocupacion' => 'class',
      'class_sector_economico' => 'class',
      'class_tipo_sangre' => 'class',
      'class_indicaciones_medicas' => 'class',
      'class_informacion_opcional' => 'class',
      'class_campo_reservado' => 'class',
      'class_tipo_vinculacion' => 'class',
      'nombre2' => 'Formulario prueba',
    ]);

    FormularioUsuario::create([
      'nombre' => 'Formulario dos',
      'action' => '',
      'privilegio' => 'opcion_modificar_asistente',
      'edad_minima' => 18,
      'edad_maxima' => 200,
      'class_identificacion' => 'class',
      'class_fecha_nacimiento' => 'class',
      'class_genero' => 'class',
      'class_primer_nombre' => 'class',
      'class_primer_apellido' => 'class',
      'class_segundo_nombre' => 'class',
      'class_segundo_apellido' => 'class',
      'class_estado_civil' => 'class',
      'class_pais_nacimiento' => 'class',
      'class_telefono_fijo' => 'class',
      'class_telefono_movil' => 'class',
      'class_telefono_otro' => 'class',
      'class_email' => 'class',
      'class_direccion' => 'class',
      'class_vivienda_en_calidad_de' => 'class',
      'class_nivel_academico' => 'class',
      'class_estado_nivel_academico' => 'class',
      'class_profesion' => 'class',
      'class_ocupacion' => 'class',
      'class_sector_economico' => 'class',
      'class_tipo_sangre' => 'class',
      'class_indicaciones_medicas' => 'class',
      'class_informacion_opcional' => 'class',
      'class_campo_reservado' => 'class',
      'class_tipo_vinculacion' => 'class',
      'nombre2' => 'Formulario dos',
      'label_pais_nacimiento' => 'Pais de nacimiento'
    ]);

    DB::table('formulario_usuario_rol')->insert([
      'formulario_usuario_id' => 1,
      'rol_id' => 1,
    ]);



    // Prueba nuevo
    FormularioUsuario::create([
      'nombre' => 'Formulario de nuevo',
      'descripcion' => "Aquí podras ingresar un nuevo usuario, por favor llena los campos que son requeridos.",

      'visible_seccion_1' => true,
      'label_seccion_1' => 'Titulo seccion 1',

      'visible_fecha_nacimiento' => true,
      'class_fecha_nacimiento' => 'col-12 col-md-3',
      'obligatorio_fecha_nacimiento' => true,
      'label_fecha_nacimiento' => '',
      'validar_edad' => true,
      'edad_minima' => 18,
      'edad_maxima' => 200,
      'edad_mensaje_error' => '	En este formulario solo podrás ingresar personas mayores a 18 años, por favor verifica nuevamente la fecha de nacimiento.
      <br><br>
      Si requieres de ayuda, llámanos al 34534535, si tiene otro rango de edad da clic <a href="/usuario/2/nuevo">aquí</a>',

      'visible_foto' => true,
      'visible_tipo_identificacion' => true,
      'class_tipo_identificacion' => 'col-12 col-md-3',
      'obligatorio_tipo_identificacion' => true,
      'label_tipo_identificacion' => '',

      'visible_identificacion' => true,
      'class_identificacion' => 'col-12 col-md-3',
      'obligatorio_identificacion' => true,
      'label_identificacion' => '',

      'visible_email' => true,
      'class_email' => 'col-12 col-md-3',
      'obligatorio_email' => true,
      'label_email' => '',

      'visible_genero' => true,
      'class_genero' => 'col-12 col-md-3',
      'obligatorio_genero' => true,
      'label_genero' => '',

      'visible_primer_nombre' => true,
      'class_primer_nombre' => 'col-12 col-md-3',
      'obligatorio_primer_nombre' => true,
      'label_primer_nombre' => '',

      'visible_primer_apellido' => true,
      'class_primer_apellido' => 'col-12 col-md-3',
      'obligatorio_primer_apellido' => true,
      'label_primer_apellido' => '',

      'visible_segundo_nombre' => true,
      'class_segundo_nombre' => 'col-12 col-md-3',
      'obligatorio_segundo_nombre' => true,
      'label_segundo_nombre' => '',

      'visible_segundo_apellido' => true,
      'class_segundo_apellido' => 'col-12 col-md-3',
      'obligatorio_segundo_apellido' => true,
      'label_segundo_apellido' => '',

      'visible_estado_civil' => true,
      'class_estado_civil' => 'col-12 col-md-3',
      'obligatorio_estado_civil' => true,
      'label_estado_civil' => '',

      'visible_pais_nacimiento' => true,
      'class_pais_nacimiento' => 'col-12 col-md-3',
      'obligatorio_pais_nacimiento' => true,
      'label_pais_nacimiento' => '',

      'visible_telefono_fijo' => true,
      'class_telefono_fijo' => 'col-12 col-md-3',
      'obligatorio_telefono_fijo' => true,
      'label_telefono_fijo' => '',

      'visible_telefono_movil' => true,
      'class_telefono_movil' => 'col-12 col-md-3',
      'obligatorio_telefono_movil' => true,
      'label_telefono_movil' => '',

      'visible_telefono_otro' => true,
      'class_telefono_otro' => 'col-12 col-md-3',
      'obligatorio_telefono_otro' => true,
      'label_telefono_otro' => '',

      'visible_direccion' => true,
      'class_direccion' => 'col-12 col-md-12',
      'obligatorio_direccion' => true,
      'label_direccion' => '',

      'visible_vivienda_en_calidad_de' => true,
      'class_vivienda_en_calidad_de' => 'col-12 col-md-3',
      'obligatorio_vivienda_en_calidad_de' => true,
      'label_vivienda_en_calidad_de' => '',

      'visible_nivel_academico' => true,
      'class_nivel_academico' => 'col-12 col-md-4',
      'obligatorio_nivel_academico' => true,
      'label_nivel_academico' => '',

      'visible_estado_nivel_academico' => true,
      'class_estado_nivel_academico' => 'col-12 col-md-4',
      'obligatorio_estado_nivel_academico' => true,
      'label_estado_nivel_academico' => '',

      'visible_profesion' => true,
      'class_profesion' => 'col-12 col-md-4',
      'obligatorio_profesion' => true,
      'label_profesion' => '',

      'visible_ocupacion' => true,
      'class_ocupacion' => 'col-12 col-md-4',
      'obligatorio_ocupacion' => true,
      'label_ocupacion' => '',

      'visible_sector_economico' => true,
      'class_sector_economico' => 'col-12 col-md-4',
      'obligatorio_sector_economico' => true,
      'label_sector_economico' => '',

      'visible_tipo_sangre' => true,
      'class_tipo_sangre' => 'col-12 col-md-4',
      'obligatorio_tipo_sangre' => true,
      'label_tipo_sangre' => '',

      'visible_indicaciones_medicas' => true,
      'class_indicaciones_medicas' => 'col-12 col-md-8',
      'obligatorio_indicaciones_medicas' => true,
      'label_indicaciones_medicas' => '',

      'visible_sede' => true,
      'class_sede' => 'col-12 col-md-6',
      'obligatorio_sede' => true,
      'label_sede' => '',

      'visible_tipo_vinculacion' => true,
      'class_tipo_vinculacion' => 'col-12 col-md-6',
      'obligatorio_tipo_vinculacion' => true,
      'label_tipo_vinculacion' => '',

      'visible_informacion_opcional' => true,
      'class_informacion_opcional' => 'col-12 col-md-6',
      'obligatorio_informacion_opcional' => true,
      'label_informacion_opcional' => '',

      'visible_campo_reservado' => true,
      'class_campo_reservado' => 'col-12 col-md-6',
      'obligatorio_campo_reservado' => true,
      'label_campo_reservado' => '',

      'visible_archivo_a' => true,
      'class_archivo_a' => 'col-12 col-md-6',
      'obligatorio_archivo_a' => true,
      'label_archivo_a' => '',
      'descargable_archivo_a' => true,

      'visible_archivo_b' => true,
      'class_archivo_b' => 'col-12 col-md-6',
      'obligatorio_archivo_b' => true,
      'label_archivo_b' => '',
      'descargable_archivo_b' => true,

      'visible_archivo_c' => true,
      'class_archivo_c' => 'col-12 col-md-6',
      'obligatorio_archivo_c' => true,
      'label_archivo_c' => '',
      'descargable_archivo_c' => true,

      'visible_archivo_d' => true,
      'class_archivo_d' => 'col-12 col-md-6',
      'obligatorio_archivo_d' => true,
      'label_archivo_d' => '',
      'descargable_archivo_d' => true,

      'visible_tipo_identificacion_acudiente' => true,
      'class_tipo_identificacion_acudiente' => 'col-12 col-md-3',
      'obligatorio_tipo_identificacion_acudiente' => true,
      'label_tipo_identificacion_acudiente' => '',

      'visible_identificacion_acudiente' => true,
      'class_identificacion_acudiente' => 'col-12 col-md-3',
      'obligatorio_identificacion_acudiente' => true,
      'label_identificacion_acudiente' => '',

      'visible_nombre_acudiente' => true,
      'class_nombre_acudiente' => 'col-12 col-md-3',
      'obligatorio_nombre_acudiente' => true,
      'label_nombre_acudiente' => '',

      'visible_telefono_acudiente' => true,
      'class_telefono_acudiente' => 'col-12 col-md-3',
      'obligatorio_telefono_acudiente' => true,
      'label_telefono_acudiente' => '',

      'visible_seccion_campos_extra' => true,

      'visible_terminos_condiciones' => true,
      'mensaje_terminos_condiciones' => 'Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen.',
      'url_terminos_condiciones' => 'https://redil.co',

      'action' => 'usuario.crear',

      'visible_seccion_2' => true,
      'label_seccion_2' => 'Titulo seccion 2',
      'visible_seccion_3' => true,
      'label_seccion_3' => 'Titulo seccion 3',
      'visible_seccion_4' => true,
      'label_seccion_4' => 'Titulo seccion 4',
      'visible_seccion_5' => true,
      'label_seccion_5' => 'Titulo seccion 5',
      'visible_seccion_6' => true,
      'label_seccion_6' => 'Titulo seccion 6',
      'visible_seccion_7' => true,
      'label_seccion_7' => 'Titulo seccion 7',
      'visible_seccion_8' => true,
      'label_seccion_8' => 'Titulo seccion 8',

      'privilegio' => 'subitem_nuevo_asistente',

      'class_direccion' => 'class',

      'class_informacion_opcional' => 'class',
      'class_campo_reservado' => 'class',
      'nombre2' => 'Formulario de nuevo',
      'label_pais_nacimiento' => 'Pais de nacimiento'
    ]);


    DB::table('formulario_usuario_rol')->insert([
      'formulario_usuario_id' => 3,
      'rol_id' => 1,
    ]);


    // Prueba actualizar
    FormularioUsuario::create([
      'nombre' => 'Modificar Usuario',
      'descripcion' => "Aquí podras modificar los datos del usuario, por favor llena los campos que son requeridos.",

      'visible_seccion_1' => true,
      'label_seccion_1' => 'Titulo seccion 1',

      'visible_fecha_nacimiento' => true,
      'class_fecha_nacimiento' => 'col-12 col-md-3',
      'obligatorio_fecha_nacimiento' => true,
      'label_fecha_nacimiento' => '',
      'validar_edad' => true,
      'edad_minima' => 0,
      'edad_maxima' => 200,
      'edad_mensaje_error' => '	En este formulario solo podrás ingresar personas mayores a 18 años, por favor verifica nuevamente la fecha de nacimiento.
      <br><br>
      Si requieres de ayuda, llámanos al 34534535, si tiene otro rango de edad da clic <a href="/usuario/2/nuevo">aquí</a>',

      'visible_foto' => true,
      'visible_tipo_identificacion' => true,
      'class_tipo_identificacion' => 'col-12 col-md-3',
      'obligatorio_tipo_identificacion' => true,
      'label_tipo_identificacion' => '',

      'visible_identificacion' => true,
      'class_identificacion' => 'col-12 col-md-3',
      'obligatorio_identificacion' => true,
      'label_identificacion' => '',

      'visible_email' => true,
      'class_email' => 'col-12 col-md-3',
      'obligatorio_email' => true,
      'label_email' => '',

      'visible_genero' => true,
      'class_genero' => 'col-12 col-md-3',
      'obligatorio_genero' => true,
      'label_genero' => '',

      'visible_primer_nombre' => true,
      'class_primer_nombre' => 'col-12 col-md-3',
      'obligatorio_primer_nombre' => true,
      'label_primer_nombre' => '',

      'visible_primer_apellido' => true,
      'class_primer_apellido' => 'col-12 col-md-3',
      'obligatorio_primer_apellido' => true,
      'label_primer_apellido' => '',

      'visible_segundo_nombre' => true,
      'class_segundo_nombre' => 'col-12 col-md-3',
      'obligatorio_segundo_nombre' => true,
      'label_segundo_nombre' => '',

      'visible_segundo_apellido' => true,
      'class_segundo_apellido' => 'col-12 col-md-3',
      'obligatorio_segundo_apellido' => true,
      'label_segundo_apellido' => '',

      'visible_estado_civil' => true,
      'class_estado_civil' => 'col-12 col-md-3',
      'obligatorio_estado_civil' => true,
      'label_estado_civil' => '',

      'visible_pais_nacimiento' => true,
      'class_pais_nacimiento' => 'col-12 col-md-3',
      'obligatorio_pais_nacimiento' => true,
      'label_pais_nacimiento' => '',

      'visible_telefono_fijo' => true,
      'class_telefono_fijo' => 'col-12 col-md-3',
      'obligatorio_telefono_fijo' => true,
      'label_telefono_fijo' => '',

      'visible_telefono_movil' => true,
      'class_telefono_movil' => 'col-12 col-md-3',
      'obligatorio_telefono_movil' => true,
      'label_telefono_movil' => '',

      'visible_telefono_otro' => true,
      'class_telefono_otro' => 'col-12 col-md-3',
      'obligatorio_telefono_otro' => true,
      'label_telefono_otro' => '',

      'visible_direccion' => true,
      'class_direccion' => 'col-12 col-md-12',
      'obligatorio_direccion' => true,
      'label_direccion' => '',

      'visible_vivienda_en_calidad_de' => true,
      'class_vivienda_en_calidad_de' => 'col-12 col-md-3',
      'obligatorio_vivienda_en_calidad_de' => true,
      'label_vivienda_en_calidad_de' => '',

      'visible_nivel_academico' => true,
      'class_nivel_academico' => 'col-12 col-md-4',
      'obligatorio_nivel_academico' => true,
      'label_nivel_academico' => '',

      'visible_estado_nivel_academico' => true,
      'class_estado_nivel_academico' => 'col-12 col-md-4',
      'obligatorio_estado_nivel_academico' => true,
      'label_estado_nivel_academico' => '',

      'visible_profesion' => true,
      'class_profesion' => 'col-12 col-md-4',
      'obligatorio_profesion' => true,
      'label_profesion' => '',

      'visible_ocupacion' => true,
      'class_ocupacion' => 'col-12 col-md-4',
      'obligatorio_ocupacion' => true,
      'label_ocupacion' => '',

      'visible_sector_economico' => true,
      'class_sector_economico' => 'col-12 col-md-4',
      'obligatorio_sector_economico' => true,
      'label_sector_economico' => '',

      'visible_tipo_sangre' => true,
      'class_tipo_sangre' => 'col-12 col-md-4',
      'obligatorio_tipo_sangre' => true,
      'label_tipo_sangre' => '',

      'visible_indicaciones_medicas' => true,
      'class_indicaciones_medicas' => 'col-12 col-md-8',
      'obligatorio_indicaciones_medicas' => true,
      'label_indicaciones_medicas' => '',

      'visible_sede' => true,
      'class_sede' => 'col-12 col-md-6',
      'obligatorio_sede' => true,
      'label_sede' => '',

      'visible_tipo_vinculacion' => true,
      'class_tipo_vinculacion' => 'col-12 col-md-6',
      'obligatorio_tipo_vinculacion' => true,
      'label_tipo_vinculacion' => '',

      'visible_informacion_opcional' => true,
      'class_informacion_opcional' => 'col-12 col-md-6',
      'obligatorio_informacion_opcional' => true,
      'label_informacion_opcional' => '',

      'visible_campo_reservado' => true,
      'class_campo_reservado' => 'col-12 col-md-6',
      'obligatorio_campo_reservado' => true,
      'label_campo_reservado' => '',

      'visible_archivo_a' => true,
      'class_archivo_a' => 'col-12 col-md-6',
      'obligatorio_archivo_a' => true,
      'label_archivo_a' => '',
      'descargable_archivo_a' => true,

      'visible_archivo_b' => true,
      'class_archivo_b' => 'col-12 col-md-6',
      'obligatorio_archivo_b' => true,
      'label_archivo_b' => '',
      'descargable_archivo_b' => true,

      'visible_archivo_c' => true,
      'class_archivo_c' => 'col-12 col-md-6',
      'obligatorio_archivo_c' => true,
      'label_archivo_c' => '',
      'descargable_archivo_c' => true,

      'visible_archivo_d' => true,
      'class_archivo_d' => 'col-12 col-md-6',
      'obligatorio_archivo_d' => true,
      'label_archivo_d' => '',
      'descargable_archivo_d' => true,

      'visible_tipo_identificacion_acudiente' => true,
      'class_tipo_identificacion_acudiente' => 'col-12 col-md-3',
      'obligatorio_tipo_identificacion_acudiente' => true,
      'label_tipo_identificacion_acudiente' => '',

      'visible_identificacion_acudiente' => true,
      'class_identificacion_acudiente' => 'col-12 col-md-3',
      'obligatorio_identificacion_acudiente' => true,
      'label_identificacion_acudiente' => '',

      'visible_nombre_acudiente' => true,
      'class_nombre_acudiente' => 'col-12 col-md-3',
      'obligatorio_nombre_acudiente' => true,
      'label_nombre_acudiente' => '',

      'visible_telefono_acudiente' => true,
      'class_telefono_acudiente' => 'col-12 col-md-3',
      'obligatorio_telefono_acudiente' => true,
      'label_telefono_acudiente' => '',

      'visible_seccion_campos_extra' => true,

      'visible_terminos_condiciones' => true,
      'mensaje_terminos_condiciones' => 'Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen.',
      'url_terminos_condiciones' => 'https://redil.co',

      'action' => 'usuario.editar',

      'visible_seccion_2' => true,
      'label_seccion_2' => 'Titulo seccion 2',
      'visible_seccion_3' => true,
      'label_seccion_3' => 'Titulo seccion 3',
      'visible_seccion_4' => true,
      'label_seccion_4' => 'Titulo seccion 4',
      'visible_seccion_5' => true,
      'label_seccion_5' => 'Titulo seccion 5',
      'visible_seccion_6' => true,
      'label_seccion_6' => 'Titulo seccion 6',
      'visible_seccion_7' => true,
      'label_seccion_7' => 'Titulo seccion 7',
      'visible_seccion_8' => true,
      'label_seccion_8' => 'Titulo seccion 8',


      'privilegio' => 'opcion_modificar_asistente',

      'class_direccion' => 'class',

      'class_informacion_opcional' => 'class',
      'class_campo_reservado' => 'class',
      'nombre2' => 'Formulario modificar',
      'label_pais_nacimiento' => 'Pais de nacimiento'
    ]);

    DB::table('formulario_usuario_rol')->insert([
      'formulario_usuario_id' => 4,
      'rol_id' => 1,
    ]);

     // Prueba nuevo externo
     FormularioUsuario::create([
      'nombre' => 'Crear cuenta',
      'descripcion' => "Aquí podras ingresar un nuevo usuario, por favor llena los campos que son requeridos.",
      'es_formulario_exterior' => TRUE,
      'visible_seccion_1' => true,
      'label_seccion_1' => 'Titulo seccion 1',

      'visible_fecha_nacimiento' => true,
      'class_fecha_nacimiento' => 'col-12 col-md-3',
      'obligatorio_fecha_nacimiento' => true,
      'label_fecha_nacimiento' => '',
      'validar_edad' => true,
      'edad_minima' => 18,
      'edad_maxima' => 200,
      'edad_mensaje_error' => '	En este formulario solo podrás ingresar personas mayores a 18 años, por favor verifica nuevamente la fecha de nacimiento.
      <br><br>
      Si requieres de ayuda, llámanos al 34534535, si tiene otro rango de edad da clic <a href="/usuario/2/nuevo">aquí</a>',

      'visible_foto' => true,
      'visible_tipo_identificacion' => true,
      'class_tipo_identificacion' => 'col-12 col-md-3',
      'obligatorio_tipo_identificacion' => true,
      'label_tipo_identificacion' => '',

      'visible_identificacion' => true,
      'class_identificacion' => 'col-12 col-md-3',
      'obligatorio_identificacion' => true,
      'label_identificacion' => '',

      'visible_email' => true,
      'class_email' => 'col-12 col-md-3',
      'obligatorio_email' => true,
      'label_email' => '',

      'visible_genero' => true,
      'class_genero' => 'col-12 col-md-3',
      'obligatorio_genero' => true,
      'label_genero' => '',

      'visible_primer_nombre' => true,
      'class_primer_nombre' => 'col-12 col-md-3',
      'obligatorio_primer_nombre' => true,
      'label_primer_nombre' => '',

      'visible_primer_apellido' => true,
      'class_primer_apellido' => 'col-12 col-md-3',
      'obligatorio_primer_apellido' => true,
      'label_primer_apellido' => '',

      'visible_segundo_nombre' => true,
      'class_segundo_nombre' => 'col-12 col-md-3',
      'obligatorio_segundo_nombre' => true,
      'label_segundo_nombre' => '',

      'visible_segundo_apellido' => true,
      'class_segundo_apellido' => 'col-12 col-md-3',
      'obligatorio_segundo_apellido' => true,
      'label_segundo_apellido' => '',

      'visible_estado_civil' => true,
      'class_estado_civil' => 'col-12 col-md-3',
      'obligatorio_estado_civil' => true,
      'label_estado_civil' => '',

      'visible_pais_nacimiento' => true,
      'class_pais_nacimiento' => 'col-12 col-md-3',
      'obligatorio_pais_nacimiento' => true,
      'label_pais_nacimiento' => '',

      'visible_telefono_fijo' => true,
      'class_telefono_fijo' => 'col-12 col-md-3',
      'obligatorio_telefono_fijo' => true,
      'label_telefono_fijo' => '',

      'visible_telefono_movil' => true,
      'class_telefono_movil' => 'col-12 col-md-3',
      'obligatorio_telefono_movil' => true,
      'label_telefono_movil' => '',

      'visible_telefono_otro' => true,
      'class_telefono_otro' => 'col-12 col-md-3',
      'obligatorio_telefono_otro' => true,
      'label_telefono_otro' => '',

      'visible_direccion' => true,
      'class_direccion' => 'col-12 col-md-12',
      'obligatorio_direccion' => true,
      'label_direccion' => '',

      'visible_vivienda_en_calidad_de' => true,
      'class_vivienda_en_calidad_de' => 'col-12 col-md-3',
      'obligatorio_vivienda_en_calidad_de' => true,
      'label_vivienda_en_calidad_de' => '',

      'visible_nivel_academico' => true,
      'class_nivel_academico' => 'col-12 col-md-4',
      'obligatorio_nivel_academico' => true,
      'label_nivel_academico' => '',

      'visible_estado_nivel_academico' => true,
      'class_estado_nivel_academico' => 'col-12 col-md-4',
      'obligatorio_estado_nivel_academico' => true,
      'label_estado_nivel_academico' => '',

      'visible_profesion' => true,
      'class_profesion' => 'col-12 col-md-4',
      'obligatorio_profesion' => true,
      'label_profesion' => '',

      'visible_ocupacion' => true,
      'class_ocupacion' => 'col-12 col-md-4',
      'obligatorio_ocupacion' => true,
      'label_ocupacion' => '',

      'visible_sector_economico' => true,
      'class_sector_economico' => 'col-12 col-md-4',
      'obligatorio_sector_economico' => true,
      'label_sector_economico' => '',

      'visible_tipo_sangre' => true,
      'class_tipo_sangre' => 'col-12 col-md-4',
      'obligatorio_tipo_sangre' => true,
      'label_tipo_sangre' => '',

      'visible_indicaciones_medicas' => true,
      'class_indicaciones_medicas' => 'col-12 col-md-8',
      'obligatorio_indicaciones_medicas' => true,
      'label_indicaciones_medicas' => '',

      'visible_sede' => true,
      'class_sede' => 'col-12 col-md-6',
      'obligatorio_sede' => true,
      'label_sede' => '',

      'visible_tipo_vinculacion' => true,
      'class_tipo_vinculacion' => 'col-12 col-md-6',
      'obligatorio_tipo_vinculacion' => true,
      'label_tipo_vinculacion' => '',

      'visible_informacion_opcional' => true,
      'class_informacion_opcional' => 'col-12 col-md-6',
      'obligatorio_informacion_opcional' => true,
      'label_informacion_opcional' => '',

      'visible_campo_reservado' => true,
      'class_campo_reservado' => 'col-12 col-md-6',
      'obligatorio_campo_reservado' => true,
      'label_campo_reservado' => '',

      'visible_archivo_a' => true,
      'class_archivo_a' => 'col-12 col-md-6',
      'obligatorio_archivo_a' => false,
      'label_archivo_a' => '',
      'descargable_archivo_a' => true,

      'visible_archivo_b' => true,
      'class_archivo_b' => 'col-12 col-md-6',
      'obligatorio_archivo_b' => false,
      'label_archivo_b' => '',
      'descargable_archivo_b' => true,

      'visible_archivo_c' => true,
      'class_archivo_c' => 'col-12 col-md-6',
      'obligatorio_archivo_c' => false,
      'label_archivo_c' => '',
      'descargable_archivo_c' => true,

      'visible_archivo_d' => true,
      'class_archivo_d' => 'col-12 col-md-6',
      'obligatorio_archivo_d' => false,
      'label_archivo_d' => '',
      'descargable_archivo_d' => true,

      'visible_tipo_identificacion_acudiente' => true,
      'class_tipo_identificacion_acudiente' => 'col-12 col-md-3',
      'obligatorio_tipo_identificacion_acudiente' => true,
      'label_tipo_identificacion_acudiente' => '',

      'visible_identificacion_acudiente' => true,
      'class_identificacion_acudiente' => 'col-12 col-md-3',
      'obligatorio_identificacion_acudiente' => true,
      'label_identificacion_acudiente' => '',

      'visible_nombre_acudiente' => true,
      'class_nombre_acudiente' => 'col-12 col-md-3',
      'obligatorio_nombre_acudiente' => true,
      'label_nombre_acudiente' => '',

      'visible_telefono_acudiente' => true,
      'class_telefono_acudiente' => 'col-12 col-md-3',
      'obligatorio_telefono_acudiente' => true,
      'label_telefono_acudiente' => '',

      'visible_seccion_campos_extra' => true,

      'visible_terminos_condiciones' => true,
      'mensaje_terminos_condiciones' => 'Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen.',
      'url_terminos_condiciones' => 'https://redil.co',

      'action' => 'usuario.crear',

      'visible_seccion_2' => true,
      'label_seccion_2' => 'Titulo seccion 2',
      'visible_seccion_3' => true,
      'label_seccion_3' => 'Titulo seccion 3',
      'visible_seccion_4' => true,
      'label_seccion_4' => 'Titulo seccion 4',
      'visible_seccion_5' => true,
      'label_seccion_5' => 'Titulo seccion 5',
      'visible_seccion_6' => true,
      'label_seccion_6' => 'Titulo seccion 6',
      'visible_seccion_7' => true,
      'label_seccion_7' => 'Titulo seccion 7',
      'visible_seccion_8' => true,
      'label_seccion_8' => 'Titulo seccion 8',

      'privilegio' => 'nuevo_usuario',

      'class_direccion' => 'class',

      'class_informacion_opcional' => 'class',
      'class_campo_reservado' => 'class',
      'nombre2' => 'Formulario de nuevo',
      'label_pais_nacimiento' => 'Pais de nacimiento'
    ]);

  }
}
