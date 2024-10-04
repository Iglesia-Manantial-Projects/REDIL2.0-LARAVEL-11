<?php

namespace Database\Seeders;

use App\Models\Role as ModelsRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // RolAdmistrador
    $superAdmin = Role::create(['name' => 'Super Administrador', 'icono' => 'ti ti-key', 'dependiente' => false]);

    // creo relacion create_privilegios_tipo_grupo_rol
    ModelsRole::find(1)->privilegiosTiposGrupo()->attach(3, ['asignar_asistente' => false, 'desvincular_asistente' => true, 'asignar_encargado' => false, 'desvincular_encargado' => true]);
    ModelsRole::find(1)->privilegiosTiposGrupo()->attach(4, ['asignar_asistente' => true, 'desvincular_asistente' => false, 'asignar_encargado' => true, 'desvincular_encargado' => false]);

    $usuario1 = \App\Models\User::create([
      'pais_id' => 45,
      'email' => 'admin@redil.com',
      'password' => bcrypt('12345678'),
      'activo' => 0,
      'asistente_id' => 1,
      'primer_nombre' => 'Admin',
      'primer_apellido' => 'Admin',
      'genero' => 0,
      'identificacion' => '111222333',
      'tipo_usuario_id' => 6,
      'foto' => 'default-m.png',
      'fecha_nacimiento' => '2000-08-05',
      'esta_aprobado' => 1,
      'tipo_vinculacion_id' => 4,
    ]);

    $usuario1->roles()->attach($superAdmin->id, ['activo' => 1, 'dependiente' => 0, 'model_type' => 'App\Models\User']);


    // RolPastor
    $pastor = Role::create(['name' => 'Pastor', 'icono' => 'ti ti-user-shield', 'dependiente' => true]);

    $usuario2 = \App\Models\User::create([
      'pais_id' => 45,
      'email' => 'pastorprincipal@redil.com',
      'password' => bcrypt('12345678'),
      'activo' => 0,
      'asistente_id' => 1,
      'primer_nombre' => 'Hector Fabio',
      'primer_apellido' => 'Jaramillo',
      'genero' => 0,
      'identificacion' => '2384283482',
      'tipo_usuario_id' => 1,
      'foto' => 'default-m.png',
      'fecha_nacimiento' => '1977-08-05',
      'tipo_vinculacion_id' => 2,
    ]);

    $usuario2->roles()->attach($pastor->id, ['activo' => 1, 'dependiente' => 1, 'model_type' => 'App\Models\User']);

    // Persona encargada de la iglesia con id 1
    $usuario2->iglesiaEncargada()->attach(1);

    // RolLider
    $lider = Role::create(['name' => 'Lider', 'icono' => 'ti ti-user-star', 'dependiente' => true]);

    // creo relacion create_privilegios_tipo_grupo_rol
    ModelsRole::find(3)->privilegiosTiposGrupo()->attach(2, ['asignar_asistente' => true, 'desvincular_asistente' => true, 'asignar_encargado' => false, 'desvincular_encargado' => false]);

    $usuario3 = \App\Models\User::create([
      'pais_id' => 45,
      'email' => 'lider_a@redil.com',
      'password' => bcrypt('12345678'),
      'activo' => 0,
      'asistente_id' => 1,
      'primer_nombre' => 'Fabian',
      'primer_apellido' => 'Aguirre',
      'genero' => 0,
      'identificacion' => '243599756',
      'tipo_usuario_id' => 2,
      'foto' => 'default-m.png',
      'fecha_nacimiento' => '1985-08-05',
      'ultimo_reporte_grupo' => '2024-08-20',
      'ultimo_reporte_reunion' => '2024-08-20',
      'tipo_vinculacion_id' => 2,
    ]);

    $usuario3->roles()->attach($lider->id, ['activo' => 1, 'dependiente' => 1, 'model_type' => 'App\Models\User']);

    $usuario4 = \App\Models\User::create([
      'pais_id' => 45,
      'email' => 'lider_b@redil.com',
      'password' => bcrypt('12345678'),
      'activo' => 0,
      'asistente_id' => 1,
      'primer_nombre' => 'James',
      'primer_apellido' => 'Cano',
      'genero' => 0,
      'identificacion' => '43545345345',
      'tipo_usuario_id' => 2,
      'foto' => 'default-m.png',
      'fecha_nacimiento' => '1980-08-05',
      'ultimo_reporte_grupo' => '2024-01-13',
      'ultimo_reporte_reunion' => '2024-08-20',
      'tipo_vinculacion_id' => 1,
    ]);

    $usuario4->roles()->attach($lider->id, ['activo' => 1, 'dependiente' => 1, 'model_type' => 'App\Models\User']);

    $usuario5 = \App\Models\User::create([
      'pais_id' => 45,
      'email' => 'lider_c@redil.com',
      'password' => bcrypt('12345678'),
      'activo' => 0,
      'asistente_id' => 1,
      'primer_nombre' => 'Asiste',
      'primer_apellido' => 'a dos grupos',
      'genero' => 0,
      'identificacion' => '735837375',
      'tipo_usuario_id' => 2,
      'foto' => 'default-m.png',
      'fecha_nacimiento' => '2010-08-05',
      'ultimo_reporte_grupo' => '2024-01-25',
      'ultimo_reporte_reunion' => '2024-01-30',
      'tipo_vinculacion_id' => 1,
    ]);

    $usuario5->roles()->attach($lider->id, ['activo' => 1, 'dependiente' => 1, 'model_type' => 'App\Models\User']);

    $usuario6 = \App\Models\User::create([
      'pais_id' => 45,
      'email' => 'lider_d@redil.com',
      'password' => bcrypt('12345678'),
      'activo' => 0,
      'asistente_id' => 1,
      'primer_nombre' => 'Juan',
      'segundo_nombre' => 'Carlos',
      'primer_apellido' => 'Velasquez',
      'genero' => 0,
      'tipo_identificacion_id' => 3,
      'identificacion' => '1112101544',
      'tipo_usuario_id' => 2,
      'foto' => 'asistente-6.png',
      'fecha_nacimiento' => '1989-08-05',
      'ultimo_reporte_grupo' => '2023-12-19',
      'ultimo_reporte_reunion' => '2023-12-31',
      'estado_civil_id' => 3,
      'tipo_vinculacion_id' => 2,
      'profesion_id' => 5,
      'nivel_academico_id' => 11,
      'estado_nivel_academico_id' => 2,
      'ocupacion_id' => 7,
      'pais_id' => 45,
      'estado_civil_id' => 3,
      'telefono_fijo' => '435354',
      'telefono_otro' => '453868',
      'telefono_movil' => '3155552546',
      'tipo_vivienda_id' => 1,
      'direccion' => 'Calle falsa 123',
      'sector_economico_id' => 5,
      'tipo_sangre_id' => 1,
      'indicaciones_medicas' => 'Sanito gracias a DIOS',
      'informacion_opcional' => 'Epa '
    ]);

    $usuario6->roles()->attach($lider->id, ['activo' => 1, 'dependiente' => 1, 'model_type' => 'App\Models\User']);

    // RolOveja
    $oveja = Role::create(['name' => 'Oveja', 'icono' => 'ti ti-mood-heart', 'dependiente' => true]);

    $usuario7 = \App\Models\User::create([
      'pais_id' => 45,
      'email' => 'carlos@redil.com',
      'password' => bcrypt('12345678'),
      'activo' => 0,
      'asistente_id' => 1,
      'primer_nombre' => 'El dado de baja',
      'primer_apellido' => 'Velásquez',
      'genero' => 0,
      'identificacion' => '9652412552',
      'tipo_usuario_id' => 3,
      'foto' => 'default-m.png',
      'fecha_nacimiento' => '2001-08-05',
      'ultimo_reporte_grupo' => '2024-01-30',
      'ultimo_reporte_reunion' => '2024-01-30',
      'telefono_otro' => '3255141245',
      'deleted_at' => '2023-09-21 12:23:28',
      'tipo_vinculacion_id' => 4,
    ]);

    $usuario7->roles()->attach($oveja->id, ['activo' => 1, 'dependiente' => 1, 'model_type' => 'App\Models\User']);

    $usuario8 = \App\Models\User::create([
      'pais_id' => 45,
      'email' => 'usuarionoaprobado@redil.com',
      'password' => bcrypt('12345678'),
      'activo' => 0,
      'asistente_id' => 1,
      'primer_nombre' => 'No esta',
      'primer_apellido' => 'Aprobado',
      'genero' => 0,
      'identificacion' => '346437456',
      'tipo_usuario_id' => 3,
      'foto' => 'default-m.png',
      'fecha_nacimiento' => '2020-08-05',
      'ultimo_reporte_grupo' => '2024-01-30',
      'ultimo_reporte_reunion' => '2024-01-30',
      'esta_aprobado' => 0,
      'tipo_vinculacion_id' => 4,
    ]);

    $usuario8->roles()->attach($oveja->id, ['activo' => 1, 'dependiente' => 1, 'model_type' => 'App\Models\User']);

    $usuario9 = \App\Models\User::create([
      'pais_id' => 45,
      'email' => 'ovejaengrupo@redil.com',
      'password' => bcrypt('12345678'),
      'activo' => 0,
      'asistente_id' => 1,
      'primer_nombre' => 'Oveja',
      'primer_apellido' => 'Uno',
      'genero' => 0,
      'tipo_identificacion_id' => 3,
      'identificacion' => '934930454',
      'tipo_usuario_id' => 3,
      'foto' => 'default-m.png',
      'fecha_nacimiento' => '2015-08-05',
      'tipo_vinculacion_id' => 1,
      'profesion_id' => 4,
      'nivel_academico_id' => 10,
      'estado_nivel_academico_id' => 3,
      'ocupacion_id' => 5,
      'pais_id' => 3,
      'estado_civil_id' => 2,
      'telefono_fijo' => '25434538',
      'telefono_otro' => '737865786',
      'telefono_movil' => '47584538',
      'tipo_vivienda_id' => 2,
      'direccion' => 'Calle falsa 123',
      'sector_economico_id' => 8,
      'tipo_sangre_id' => 1,
      'indicaciones_medicas' => 'Todo bien',
      'informacion_opcional' => 'Que más quieres de mi',
      'sede_id' => 1,
    ]);

    $usuario9->roles()->attach($oveja->id, ['activo' => 1, 'dependiente' => 1, 'model_type' => 'App\Models\User']);

    $usuario10 = \App\Models\User::create([
      'pais_id' => 45,
      'email' => 'ovejasingrupo@redil.com',
      'password' => bcrypt('12345678'),
      'activo' => 0,
      'asistente_id' => 1,
      'primer_nombre' => 'Oveja',
      'primer_apellido' => 'Sin grupo',
      'genero' => 1,
      'identificacion' => '73838287',
      'tipo_usuario_id' => 3,
      'foto' => 'default-m.png',
      'fecha_nacimiento' => '2018-08-05',
      'usuario_creacion_id' => 3,
      'estado_civil_id' => 1,
      'tipo_vinculacion_id' => 3,
      'profesion_id' => 4,
      'nivel_academico_id' => 10,
      'estado_nivel_academico_id' => 3,
      'ocupacion_id' => 5,
    ]);

    $usuario10->roles()->attach($oveja->id, ['activo' => 1, 'dependiente' => 1, 'model_type' => 'App\Models\User']);

    $usuario11 = \App\Models\User::create([
      'pais_id' => 45,
      'email' => 'hijo@redil.com',
      'password' => bcrypt('12345678'),
      'activo' => 0,
      'asistente_id' => 1,
      'primer_nombre' => 'Hija',
      'primer_apellido' => 'De Juan',
      'genero' => 0,
      'tipo_identificacion_id' => 3,
      'identificacion' => '963852741',
      'tipo_usuario_id' => 3,
      'foto' => 'default-f.png',
      'fecha_nacimiento' => '2019-08-05',
      'tipo_vinculacion_id' => 1,
      'profesion_id' => 4,
      'nivel_academico_id' => 10,
      'estado_nivel_academico_id' => 3,
      'ocupacion_id' => 5,
      'pais_id' => 3,
      'estado_civil_id' => 2,
      'telefono_fijo' => '7267676764',
      'telefono_otro' => '7386728766',
      'telefono_movil' => '456435435',
      'tipo_vivienda_id' => 2,
      'direccion' => 'Calle falsa 456',
      'sector_economico_id' => 8,
      'tipo_sangre_id' => 1,
      'indicaciones_medicas' => 'esadf sdf dsf',
      'informacion_opcional' => 'sdfsdaf asdf sadf'
    ]);

    $usuario11->roles()->attach($oveja->id, ['activo' => 1, 'dependiente' => 1, 'model_type' => 'App\Models\User']);

    // RolNuevo
    $oveja = Role::create(['name' => 'Nuevo', 'icono' => 'ti ti-paper-bag', 'dependiente' => true]);

    // RolEmpleado
    $oveja = Role::create(['name' => 'Empleado', 'icono' => 'ti ti-brand-ctemplar', 'dependiente' => true]);

    // RolDesarrollador
    $oveja = Role::create(['name' => 'Desarrollador', 'icono' => 'ti ti-anchor', 'dependiente' => true]);

    // RolPDP
    $oveja = Role::create(['name' => 'PDP', 'icono' => 'ti ti-paperclip', 'dependiente' => false]);

    // Personas
    Permission::create([
      'titulo' => 'lista_asistentes_todos',
      'descripcion' => '',
      'name' => 'personas.lista_asistentes_todos',
    ])->syncRoles([$superAdmin]);



    Permission::create([
      'titulo' => 'lista_asistentes_solo_ministerio',
      'descripcion' => '',
      'name' => 'personas.lista_asistentes_solo_ministerio',
    ])->syncRoles([$lider]);

    Permission::create([
      'titulo' => 'item_asistentes',
      'descripcion' => '',
      'name' => 'personas.item_asistentes',
    ]);

    Permission::create([
      'titulo' => 'subitem_nuevo_asistente',
      'descripcion' => '',
      'name' => 'personas.subitem_nuevo_asistente',
    ]);

    Permission::create([
      'titulo' => 'subitem_lista_asistentes',
      'descripcion' => '',
      'name' => 'personas.subitem_lista_asistentes',
    ]);

    Permission::create([
      'titulo' => 'opcion_ver_perfil_asistente',
      'descripcion' => '',
      'name' => 'personas.opcion_ver_perfil_asistente',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'opcion_modificar_asistente',
      'descripcion' => '',
      'name' => 'personas.opcion_modificar_asistente',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'opcion_cambiar_contrasena_asistente',
      'descripcion' => '',
      'name' => 'personas.opcion_cambiar_contrasena_asistente',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'opcion_eliminar_asistente',
      'descripcion' => '',
      'name' => 'personas.opcion_eliminar_asistente',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'opcion_dar_de_baja_asistente',
      'descripcion' => '',
      'name' => 'personas.opcion_dar_de_baja_asistente',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'opcion_geoasignar_asistente',
      'descripcion' => '',
      'name' => 'personas.opcion_geoasignar_asistente',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'opcion_dar_de_alta_asistente',
      'descripcion' => '',
      'name' => 'personas.opcion_dar_de_alta_asistente',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'pestana_informacion_congregacional',
      'descripcion' => '',
      'name' => 'personas.pestana_informacion_congregacional',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'pestana_geoasignacion',
      'descripcion' => '',
      'name' => 'personas.pestana_geoasignacion',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'panel_tipos_asistente',
      'descripcion' => '',
      'name' => 'personas.panel_tipos_asistente',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'panel_procesos_asistente',
      'descripcion' => '',
      'name' => 'personas.panel_procesos_asistente',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'panel_asignar_grupo_al_asistente',
      'descripcion' => '',
      'name' => 'personas.panel_asignar_grupo_al_asistente',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'pestana_actualizar_asistente',
      'descripcion' => '',
      'name' => 'personas.pestana_actualizar_asistente',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'ajax_obtiene_asistentes_solo_ministerio',
      'descripcion' => '',
      'name' => 'personas.ajax_obtiene_asistentes_solo_ministerio',
    ])->syncRoles([$lider]);

    Permission::create([
      'titulo' => 'mostrar_todos_los_grupos_en_geoasignacion',
      'descripcion' => '',
      'name' => 'personas.mostrar_todos_los_grupos_en_geoasignacion',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'ver_campo_reservado_visible',
      'descripcion' => '',
      'name' => 'personas.ver_campo_reservado_visible',
    ]);

    Permission::create([
      'titulo' => 'opcion_modificar_informacion_congregacional',
      'descripcion' => '',
      'name' => 'personas.opcion_modificar_informacion_congregacional',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'autogestion_pestana_informacion_congregacional',
      'descripcion' => '',
      'name' => 'personas.autogestion_pestana_informacion_congregacional',
    ]);

    Permission::create([
      'titulo' => 'ver_panel_asignar_tipo_usuario',
      'descripcion' => '',
      'name' => 'personas.ver_panel_asignar_tipo_usuario',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'opcion_editar_autocontraseña',
      'descripcion' => '',
      'name' => 'personas.opcion_editar_autocontraseña',
    ]);

    Permission::create([
      'titulo' => 'ver_campo_informacion_opcional',
      'descripcion' => '',
      'name' => 'personas.ver_campo_informacion_opcional',
    ]);

    Permission::create([
      'titulo' => 'privilegio_crear_asistentes_aprobados',
      'descripcion' => '',
      'name' => 'personas.privilegio_crear_asistentes_aprobados',
    ]);

    Permission::create([
      'titulo' => 'privilegio_modificar_asistentes_desaprobados',
      'descripcion' => '',
      'name' => 'personas.privilegio_modificar_asistentes_desaprobados',
    ]);

    Permission::create([
      'titulo' => 'privilegio_actualizar_estado_aprobado_asistentes',
      'descripcion' => '',
      'name' => 'personas.privilegio_actualizar_estado_aprobado_asistentes',
    ]);

    Permission::create([
      'titulo' => 'subitem_lista_sin_aprobar',
      'descripcion' => '',
      'name' => 'personas.subitem_lista_sin_aprobar',
    ]);

    Permission::create([
      'titulo' => 'editar_tipos_asistente',
      'descripcion' => '',
      'name' => 'personas.editar_tipos_asistente',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'editar_procesos_asistente',
      'descripcion' => '',
      'name' => 'personas.editar_procesos_asistente',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'eliminar_asistentes_forzadamente',
      'descripcion' => '',
      'name' => 'personas.eliminar_asistentes_forzadamente',
    ])->syncRoles([$superAdmin]);

    /*Permission::create([
      'titulo' => 'visible_seccion_campos_extra',
      'descripcion' => '',
      'name' => 'personas.visible_seccion_campos_extra',
    ])->syncRoles([$superAdmin]);*/

    Permission::create([
      'titulo' => 'ver_perfil_propio',
      'descripcion' => '',
      'name' => 'personas.ver_perfil_propio',
    ]);

    Permission::create([
      'titulo' => 'ver_panel_pasos_crecimiento_perfil',
      'descripcion' => '',
      'name' => 'personas.ver_panel_pasos_crecimiento_perfil',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'ver_panel_archivos',
      'descripcion' => '',
      'name' => 'personas.ver_panel_archivos',
    ])->syncRoles([$superAdmin]);

    // Grupos
    Permission::create([
      'titulo' => 'lista_grupos_todos',
      'descripcion' => '',
      'name' => 'grupos.lista_grupos_todos',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'lista_grupos_solo_ministerio',
      'descripcion' => '',
      'name' => 'grupos.lista_grupos_solo_ministerio',
    ])->syncRoles([$lider]);

    Permission::create([
      'titulo' => 'item_grupos',
      'descripcion' => '',
      'name' => 'grupos.item_grupos',
    ]);

    Permission::create([
      'titulo' => 'subitem_lista_grupos',
      'descripcion' => '',
      'name' => 'grupos.subitem_lista_grupos',
    ]);

    Permission::create([
      'titulo' => 'subitem_nuevo_grupo',
      'descripcion' => '',
      'name' => 'grupos.subitem_nuevo_grupo',
    ]);

    Permission::create([
      'titulo' => 'subitem_lista_informes_grupo',
      'descripcion' => '',
      'name' => 'grupos.subitem_lista_informes_grupo',
    ]);

    Permission::create([
      'titulo' => 'subitem_mapa_grupos',
      'descripcion' => '',
      'name' => 'grupos.subitem_mapa_grupos',
    ]);

    Permission::create([
      'titulo' => 'subitem_grafico_ministerio',
      'descripcion' => '',
      'name' => 'grupos.subitem_grafico_ministerio',
    ]);

    Permission::create([
      'titulo' => 'opcion_ver_perfil_grupo',
      'descripcion' => '',
      'name' => 'grupos.opcion_ver_perfil_grupo',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'opcion_modificar_grupo',
      'descripcion' => '',
      'name' => 'grupos.opcion_modificar_grupo',
    ])->syncRoles([$superAdmin,$lider]);

    Permission::create([
      'titulo' => 'opcion_dar_de_baja_alta_grupo',
      'descripcion' => '',
      'name' => 'grupos.opcion_dar_de_baja_alta_grupo',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'opcion_eliminar_grupo',
      'descripcion' => '',
      'name' => 'grupos.opcion_eliminar_grupo',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'pestana_actualizar_grupo',
      'descripcion' => '',
      'name' => 'grupos.pestana_actualizar_grupo',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'pestana_anadir_lideres_grupo',
      'descripcion' => '',
      'name' => 'grupos.pestana_anadir_lideres_grupo',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'pestana_anadir_integrantes_grupo',
      'descripcion' => '',
      'name' => 'grupos.pestana_anadir_integrantes_grupo',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'pestana_georreferencia_grupo',
      'descripcion' => '',
      'name' => 'grupos.pestana_georreferencia_grupo',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'ajax_obtiene_grupos_solo_ministerio',
      'descripcion' => '',
      'name' => 'grupos.ajax_obtiene_grupos_solo_ministerio',
    ]);

    Permission::create([
      'titulo' => 'informe_asistencia_semanal_grupos',
      'descripcion' => '',
      'name' => 'grupos.informe_asistencia_semanal_grupos',
    ]);

    Permission::create([
      'titulo' => 'informe_asistencia_mensual_grupos',
      'descripcion' => '',
      'name' => 'grupos.informe_asistencia_mensual_grupos',
    ]);

    Permission::create([
      'titulo' => 'informe_generar_pdf_yumbo',
      'descripcion' => '',
      'name' => 'grupos.informe_generar_pdf_yumbo',
    ]);

    Permission::create([
      'titulo' => 'mapa_grupos_todos',
      'descripcion' => '',
      'name' => 'grupos.mapa_grupos_todos',
    ]);

    Permission::create([
      'titulo' => 'mapa_grupos_solo_ministerio',
      'descripcion' => '',
      'name' => 'grupos.mapa_grupos_solo_ministerio',
    ]);

    Permission::create([
      'titulo' => 'grafico_ministerio_todos',
      'descripcion' => '',
      'name' => 'grupos.grafico_ministerio_todos',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'grafico_ministerio_solo_ministerio',
      'descripcion' => '',
      'name' => 'grupos.grafico_ministerio_solo_ministerio',
    ])->syncRoles([$lider]);

    Permission::create([
      'titulo' => 'mostar_modal_informe_asignacion_de_lideres',
      'descripcion' => '',
      'name' => 'grupos.mostar_modal_informe_asignacion_de_lideres',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'mostar_modal_informe_asignacion_de_asistentes',
      'descripcion' => '',
      'name' => 'grupos.mostar_modal_informe_asignacion_de_asistentes',
    ]) ->syncRoles([$superAdmin, $lider]);

    Permission::create([
      'titulo' => 'mostar_modal_informe_desvinculacion_de_lideres',
      'descripcion' => '',
      'name' => 'grupos.mostar_modal_informe_desvinculacion_de_lideres',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'mostar_modal_informe_desvinculacion_de_asistentes',
      'descripcion' => '',
      'name' => 'grupos.mostar_modal_informe_desvinculacion_de_asistentes',
    ])->syncRoles([$superAdmin, $lider]);

    Permission::create([
      'titulo' => 'privilegio_asignar_asistente_todo_tipo_asistente_a_un_grupo',
      'descripcion' => '',
      'name' => 'grupos.privilegio_asignar_asistente_todo_tipo_asistente_a_un_grupo',
    ])->syncRoles([$superAdmin, $lider]);

    Permission::create([
      'titulo' => 'opcion_desvincular_asistentes_grupos',
      'descripcion' => '',
      'name' => 'grupos.opcion_desvincular_asistentes_grupos',
    ])->syncRoles([$superAdmin, $lider]);

    Permission::create([
      'titulo' => 'item_excluir_asistentes_grupos',
      'descripcion' => '',
      'name' => 'grupos.item_excluir_asistentes_grupos',
    ]);

    Permission::create([
      'titulo' => 'opcion_excluir_grupo',
      'descripcion' => '',
      'name' => 'grupos.opcion_excluir_grupo',
    ])->syncRoles([$superAdmin]);


    Permission::create([
      'titulo' => 'visible_seccion_campos_extra_grupo',
      'descripcion' => '',
      'name' => 'grupos.visible_seccion_campos_extra_grupo',
    ])->syncRoles([$superAdmin]);

    // Reporte Grupos
    Permission::create([
      'titulo' => 'lista_reportes_grupo_todos',
      'descripcion' => '',
      'name' => 'reportes_grupos.lista_reportes_grupo_todos',
    ]);

    Permission::create([
      'titulo' => 'lista_reportes_grupo_solo_ministerio',
      'descripcion' => '',
      'name' => 'reportes_grupos.lista_reportes_grupo_solo_ministerio',
    ]);

    Permission::create([
      'titulo' => 'subitem_lista_reportes_grupo',
      'descripcion' => '',
      'name' => 'reportes_grupos.subitem_lista_reportes_grupo',
    ]);

    Permission::create([
      'titulo' => 'subitem_nuevo_reporte_grupo',
      'descripcion' => '',
      'name' => 'reportes_grupos.subitem_nuevo_reporte_grupo',
    ]);

    Permission::create([
      'titulo' => 'ver_boton_aprobar_desaprobar_reporte_grupo',
      'descripcion' => '',
      'name' => 'reportes_grupos.ver_boton_aprobar_desaprobar_reporte_grupo',
    ]);

    Permission::create([
      'titulo' => 'ver_opciones_reporte_grupo',
      'descripcion' => '',
      'name' => 'reportes_grupos.ver_opciones_reporte_grupo',
    ]);

    Permission::create([
      'titulo' => 'opcion_aprobar_reporte_grupo',
      'descripcion' => '',
      'name' => 'reportes_grupos.opcion_aprobar_reporte_grupo',
    ]);

    Permission::create([
      'titulo' => 'opcion_desaprobar_reporte_grupo',
      'descripcion' => '',
      'name' => 'reportes_grupos.opcion_desaprobar_reporte_grupo',
    ]);

    Permission::create([
      'titulo' => 'opcion_ver_perfil_reporte_grupo',
      'descripcion' => '',
      'name' => 'reportes_grupos.opcion_ver_perfil_reporte_grupo',
    ]);

    Permission::create([
      'titulo' => 'opcion_actualizar_reporte_grupo',
      'descripcion' => '',
      'name' => 'reportes_grupos.opcion_actualizar_reporte_grupo',
    ]);

    Permission::create([
      'titulo' => 'opcion_eliminar_reporte_grupo',
      'descripcion' => '',
      'name' => 'reportes_grupos.opcion_eliminar_reporte_grupo',
    ]);

    Permission::create([
      'titulo' => 'privilegio_reportar_grupo_cualquier_fecha',
      'descripcion' => '',
      'name' => 'reportes_grupos.privilegio_reportar_grupo_cualquier_fecha',
    ]);

    Permission::create([
      'titulo' => 'panel_ingresos_en_lista_reportes_grupo',
      'descripcion' => '',
      'name' => 'reportes_grupos.panel_ingresos_en_lista_reportes_grupo',
    ]);

    Permission::create([
      'titulo' => 'boton_configurar_semanas_informes_reportes_grupo',
      'descripcion' => '',
      'name' => 'reportes_grupos.boton_configurar_semanas_informes_reportes_grupo',
    ]);

    Permission::create([
      'titulo' => 'cierre_caja_ingresos_reportes_grupo',
      'descripcion' => '',
      'name' => 'reportes_grupos.cierre_caja_ingresos_reportes_grupo',
    ]);

    // Reuniones
    Permission::create([
      'titulo' => 'lista_reuniones_todas',
      'descripcion' => '',
      'name' => 'reuniones.lista_reuniones_todas',
    ]);

    Permission::create([
      'titulo' => 'lista_reuniones_solo_ministerio',
      'descripcion' => '',
      'name' => 'reuniones.lista_reuniones_solo_ministerio',
    ]);

    Permission::create([
      'titulo' => 'item_reuniones',
      'descripcion' => '',
      'name' => 'reuniones.item_reuniones',
    ]);

    Permission::create([
      'titulo' => 'subitem_lista_reuniones',
      'descripcion' => '',
      'name' => 'reuniones.subitem_lista_reuniones',
    ]);

    Permission::create([
      'titulo' => 'subitem_nueva_reunion',
      'descripcion' => '',
      'name' => 'reuniones.subitem_nueva_reunion',
    ]);

    Permission::create([
      'titulo' => 'subitem_informes_reunion',
      'descripcion' => '',
      'name' => 'reuniones.subitem_informes_reunion',
    ]);

    Permission::create([
      'titulo' => 'crea_reuniones_para_todas_las_sedes',
      'descripcion' => '',
      'name' => 'reuniones.crea_reuniones_para_todas_las_sedes',
    ]);

    Permission::create([
      'titulo' => 'opcion_ver_perfil_reunion',
      'descripcion' => '',
      'name' => 'reuniones.opcion_ver_perfil_reunion',
    ]);

    Permission::create([
      'titulo' => 'opcion_modificar_reunion',
      'descripcion' => '',
      'name' => 'reuniones.opcion_modificar_reunion',
    ]);

    Permission::create([
      'titulo' => 'opcion_dar_de_baja_alta_reunion',
      'descripcion' => '',
      'name' => 'reuniones.opcion_dar_de_baja_alta_reunion',
    ]);

    Permission::create([
      'titulo' => 'opcion_eliminar_reunion',
      'descripcion' => '',
      'name' => 'reuniones.opcion_eliminar_reunion',
    ]);

    // Reporte Reuniones
    Permission::create([
      'titulo' => 'lista_reportes_reunion_todos',
      'descripcion' => '',
      'name' => 'reporte_reuniones.lista_reportes_reunion_todos',
    ]);

    Permission::create([
      'titulo' => 'lista_reportes_reunion_solo_ministerio',
      'descripcion' => '',
      'name' => 'reporte_reuniones.lista_reportes_reunion_solo_ministerio',
    ]);

    Permission::create([
      'titulo' => 'subitem_nuevo_reporte_reunion',
      'descripcion' => '',
      'name' => 'reporte_reuniones.subitem_nuevo_reporte_reunion',
    ]);

    Permission::create([
      'titulo' => 'subitem_lista_reportes_reunion',
      'descripcion' => '',
      'name' => 'reporte_reuniones.subitem_lista_reportes_reunion',
    ]);

    Permission::create([
      'titulo' => 'ajax_obtiene_todas_las_reuniones_para_reportarlas',
      'descripcion' => '',
      'name' => 'reporte_reuniones.ajax_obtiene_todas_las_reuniones_para_reportarlas',
    ]);

    Permission::create([
      'titulo' => 'pestana_informacion_principal_reporte_reunion',
      'descripcion' => '',
      'name' => 'reporte_reuniones.pestana_informacion_principal_reporte_reunion',
    ]);

    Permission::create([
      'titulo' => 'pestana_anadir_asistentes_reporte_reunion',
      'descripcion' => '',
      'name' => 'reporte_reuniones.pestana_anadir_asistentes_reporte_reunion',
    ]);

    Permission::create([
      'titulo' => 'pestana_anadir_ingresos_reporte_reunion',
      'descripcion' => '',
      'name' => 'reporte_reuniones.pestana_anadir_ingresos_reporte_reunion',
    ]);

    Permission::create([
      'titulo' => 'ajax_obtiene_todos_los_asistentes_para_reportar_reunion',
      'descripcion' => '',
      'name' => 'reporte_reuniones.ajax_obtiene_todos_los_asistentes_para_reportar_reunion',
    ]);

    Permission::create([
      'titulo' => 'opcion_ver_perfil_reporte_reunion',
      'descripcion' => '',
      'name' => 'reporte_reuniones.opcion_ver_perfil_reporte_reunion',
    ]);

    Permission::create([
      'titulo' => 'opcion_modificar_reporte_reunion',
      'descripcion' => '',
      'name' => 'reporte_reuniones.opcion_modificar_reporte_reunion',
    ]);

    Permission::create([
      'titulo' => 'opcion_anadir_asistentes_reporte_reunion',
      'descripcion' => '',
      'name' => 'reporte_reuniones.opcion_anadir_asistentes_reporte_reunion',
    ]);

    Permission::create([
      'titulo' => 'opcion_eliminar_reporte_reunion',
      'descripcion' => '',
      'name' => 'reporte_reuniones.opcion_eliminar_reporte_reunion',
    ]);

    Permission::create([
      'titulo' => 'privilegio_anadir_asistente_reporte_reunion_cualquier_fecha',
      'descripcion' => '',
      'name' => 'reporte_reuniones.privilegio_anadir_asistente_reporte_reunion_cualquier_fecha',
    ]);

    Permission::create([
      'titulo' => 'pestana_anadir_servidores_reporte_reunion',
      'descripcion' => '',
      'name' => 'reporte_reuniones.pestana_anadir_servidores_reporte_reunion',
    ]);

    Permission::create([
      'titulo' => 'opcion_subitem_anadir_servidores_reporte_reunion',
      'descripcion' => '',
      'name' => 'reporte_reuniones.opcion_subitem_anadir_servidores_reporte_reunion',
    ]);

    Permission::create([
      'titulo' => 'subitem_iglesia_infantil',
      'descripcion' => '',
      'name' => 'reporte_reuniones.subitem_iglesia_infantil',
    ]);

    Permission::create([
      'titulo' => 'ver_conteo_preliminar_reuniones',
      'descripcion' => '',
      'name' => 'reporte_reuniones.ver_conteo_preliminar_reuniones',
    ]);

    Permission::create([
      'titulo' => 'subitem_iglesia_virtual',
      'descripcion' => '',
      'name' => 'reporte_reuniones.subitem_iglesia_virtual',
    ]);

    Permission::create([
      'titulo' => 'opcion_anadir_asistentes_reservas_reunion',
      'descripcion' => '',
      'name' => 'reporte_reuniones.opcion_anadir_asistentes_reservas_reunion',
    ]);

    Permission::create([
      'titulo' => 'opcion_descargar_informe_servidores_reporte_reunion',
      'descripcion' => '',
      'name' => 'reporte_reuniones.opcion_descargar_informe_servidores_reporte_reunion',
    ]);

    Permission::create([
      'titulo' => 'opcion_descargar_informe_asistencias_reservas_reporte_reunion',
      'descripcion' => '',
      'name' => 'reporte_reuniones.opcion_descargar_informe_asistencias_reservas_reporte_reunion',
    ]);

    Permission::create([
      'titulo' => 'opcion_descargar_informe_visualizaciones_reporte_reunion',
      'descripcion' => '',
      'name' => 'reporte_reuniones.opcion_descargar_informe_visualizaciones_reporte_reunion',
    ]);

    // Sedes
    Permission::create([
      'titulo' => 'lista_sedes_todas',
      'descripcion' => '',
      'name' => 'sedes.lista_sedes_todas',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'lista_sedes_solo_ministerio',
      'descripcion' => '',
      'name' => 'sedes.lista_sedes_solo_ministerio',
    ]);

    Permission::create([
      'titulo' => 'item_sedes',
      'descripcion' => '',
      'name' => 'sedes.item_sedes',
    ]);

    Permission::create([
      'titulo' => 'subitem_lista_sedes',
      'descripcion' => '',
      'name' => 'sedes.subitem_lista_sedes',
    ]);

    Permission::create([
      'titulo' => 'subitem_nueva_sede',
      'descripcion' => '',
      'name' => 'sedes.subitem_nueva_sede',
    ]);

    Permission::create([
      'titulo' => 'opcion_ver_perfil_sede',
      'descripcion' => '',
      'name' => 'sedes.opcion_ver_perfil_sede',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'opcion_modificar_sede',
      'descripcion' => '',
      'name' => 'sedes.opcion_modificar_sede',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'opcion_dar_de_baja_sede',
      'descripcion' => '',
      'name' => 'sedes.opcion_dar_de_baja_sede',
    ]);

    Permission::create([
      'titulo' => 'opcion_eliminar_sede',
      'descripcion' => '',
      'name' => 'sedes.opcion_eliminar_sede',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'crear_banners_videos_sede',
      'descripcion' => '',
      'name' => 'sedes.crear_banners_videos_sede',
    ]);

    // Ingresos
    Permission::create([
      'titulo' => 'lista_ingresos_todos',
      'descripcion' => '',
      'name' => 'ingresos.lista_ingresos_todos',
    ]);

    Permission::create([
      'titulo' => 'lista_ingresos_solo_ministerio',
      'descripcion' => '',
      'name' => 'ingresos.lista_ingresos_solo_ministerio',
    ]);

    Permission::create([
      'titulo' => 'item_ingresos',
      'descripcion' => '',
      'name' => 'ingresos.item_ingresos',
    ]);

    Permission::create([
      'titulo' => 'subitem_informes_por_persona_ingresos',
      'descripcion' => '',
      'name' => 'ingresos.subitem_informes_por_persona_ingresos',
    ]);

    Permission::create([
      'titulo' => 'subitem_informes_por_grupo_ingresos',
      'descripcion' => '',
      'name' => 'ingresos.subitem_informes_por_grupo_ingresos',
    ]);

    Permission::create([
      'titulo' => 'subitem_informes_por_reunion_ingresos',
      'descripcion' => '',
      'name' => 'ingresos.subitem_informes_por_reunion_ingresos',
    ]);

    Permission::create([
      'titulo' => 'subitem_informe_sumatoria_ingresos_reportes_grupo',
      'descripcion' => '',
      'name' => 'ingresos.subitem_informe_sumatoria_ingresos_reportes_grupo',
    ]);

    Permission::create([
      'titulo' => 'opcion_ver_perfil_ingreso',
      'descripcion' => '',
      'name' => 'ingresos.opcion_ver_perfil_ingreso',
    ]);

    Permission::create([
      'titulo' => 'opcion_modificar_ingreso',
      'descripcion' => '',
      'name' => 'ingresos.opcion_modificar_ingreso',
    ]);

    Permission::create([
      'titulo' => 'opcion_eliminar_ingreso',
      'descripcion' => '',
      'name' => 'ingresos.opcion_eliminar_ingreso',
    ]);

    Permission::create([
      'titulo' => 'subitem_informes_donaciones_online',
      'descripcion' => '',
      'name' => 'ingresos.subitem_informes_donaciones_online',
    ]);

    Permission::create([
      'titulo' => 'subitem_nueva_ofrenda',
      'descripcion' => '',
      'name' => 'ingresos.subitem_nueva_ofrenda',
    ]);

    Permission::create([
      'titulo' => 'privilegio_ver_todos_los_ingresos_informes_donaciones_online',
      'descripcion' => '',
      'name' => 'ingresos.privilegio_ver_todos_los_ingresos_informes_donaciones_online',
    ]);

    // Informes
    Permission::create([
      'titulo' => 'opcion_descargar_informe_excel_informe_ingresos_persona',
      'descripcion' => '',
      'name' => 'informes.opcion_descargar_informe_excel_informe_ingresos_persona',
    ]);

    Permission::create([
      'titulo' => 'opcion_descargar_informe_pdf_informe_ingresos_persona',
      'descripcion' => '',
      'name' => 'informes.opcion_descargar_informe_pdf_informe_ingresos_persona',
    ]);

    // Temas

    Permission::create([
      'titulo' => 'ver_todos_los_temas',
      'descripcion' => '',
      'name' => 'temas.ver_todos_los_temas',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'ver_tema',
      'descripcion' => '',
      'name' => 'temas.ver_tema',
    ])->syncRoles([$superAdmin]);


    Permission::create([
      'titulo' => 'editar_tema',
      'descripcion' => '',
      'name' => 'temas.editar_tema',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'abrir_link_tema',
      'descripcion' => '',
      'name' => 'temas.abrir_link_tema',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'eliminar_tema',
      'descripcion' => '',
      'name' => 'temas.eliminar_tema',
    ])->syncRoles([$superAdmin]);

    // Iglesia
    Permission::create([
      'titulo' => 'ver_configuracion_iglesia',
      'descripcion' => '',
      'name' => 'iglesia.ver_configuracion_iglesia',
    ]);

    Permission::create([
      'titulo' => 'crear_banners_videos_iglesia',
      'descripcion' => '',
      'name' => 'iglesia.crear_banners_videos_iglesia',
    ]);

    Permission::create([
      'titulo' => 'logo_personalizado',
      'descripcion' => '',
      'name' => 'iglesia.logo_personalizado',
    ]);

    // Actividades
    Permission::create([
      'titulo' => 'item_actividades',
      'descripcion' => '',
      'name' => 'actividades.item_actividades',
    ]);

    Permission::create([
      'titulo' => 'subitem_nueva_actividad',
      'descripcion' => '',
      'name' => 'actividades.subitem_nueva_actividad',
    ]);

    Permission::create([
      'titulo' => 'subitem_historial_carga_de_achivo',
      'descripcion' => '',
      'name' => 'actividades.subitem_historial_carga_de_achivo',
    ]);

    Permission::create([
      'titulo' => 'subitem_informe_inscripciones',
      'descripcion' => '',
      'name' => 'actividades.subitem_informe_inscripciones',
    ]);

    Permission::create([
      'titulo' => 'subitem_informe_compras',
      'descripcion' => '',
      'name' => 'actividades.subitem_informe_compras',
    ]);

    Permission::create([
      'titulo' => 'subitem_informe_pagos',
      'descripcion' => '',
      'name' => 'actividades.subitem_informe_pagos',
    ]);

    Permission::create([
      'titulo' => 'pestana_actualizar_actividad',
      'descripcion' => '',
      'name' => 'actividades.pestana_actualizar_actividad',
    ]);

    Permission::create([
      'titulo' => 'pestana_categorias_actividad',
      'descripcion' => '',
      'name' => 'actividades.pestana_categorias_actividad',
    ]);

    Permission::create([
      'titulo' => 'pestana_anadir_encargados_actividad',
      'descripcion' => '',
      'name' => 'actividades.pestana_anadir_encargados_actividad',
    ]);

    Permission::create([
      'titulo' => 'pestana_anadir_asistencias_actividad',
      'descripcion' => '',
      'name' => 'actividades.pestana_anadir_asistencias_actividad',
    ]);

    Permission::create([
      'titulo' => 'pestana_multimedia_actividad',
      'descripcion' => '',
      'name' => 'actividades.pestana_multimedia_actividad',
    ]);

    Permission::create([
      'titulo' => 'ver_opciones_actividad',
      'descripcion' => '',
      'name' => 'actividades.ver_opciones_actividad',
    ]);

    Permission::create([
      'titulo' => 'opcion_actualizar_actividad',
      'descripcion' => '',
      'name' => 'actividades.opcion_actualizar_actividad',
    ]);

    Permission::create([
      'titulo' => 'opcion_categorias_actividad',
      'descripcion' => '',
      'name' => 'actividades.opcion_categorias_actividad',
    ]);

    Permission::create([
      'titulo' => 'opcion_anadir_encargados_actividad',
      'descripcion' => '',
      'name' => 'actividades.opcion_anadir_encargados_actividad',
    ]);

    Permission::create([
      'titulo' => 'opcion_anadir_asistencias_actividad',
      'descripcion' => '',
      'name' => 'actividades.opcion_anadir_asistencias_actividad',
    ]);

    Permission::create([
      'titulo' => 'opcion_multimediar_actividad',
      'descripcion' => '',
      'name' => 'actividades.opcion_multimediar_actividad',
    ]);

    Permission::create([
      'titulo' => 'ver_boton_exportar_excel_informe_compras',
      'descripcion' => '',
      'name' => 'actividades.ver_boton_exportar_excel_informe_compras',
    ]);

    Permission::create([
      'titulo' => 'ver_filtros_informe_compras',
      'descripcion' => '',
      'name' => 'actividades.ver_filtros_informe_compras',
    ]);

    Permission::create([
      'titulo' => 'ver_columna_compra_informe_compra',
      'descripcion' => '',
      'name' => 'actividades.ver_columna_compra_informe_compra',
    ]);

    Permission::create([
      'titulo' => 'ver_boton_exportar_excel_informe_pagos',
      'descripcion' => '',
      'name' => 'actividades.ver_boton_exportar_excel_informe_pagos',
    ]);

    Permission::create([
      'titulo' => 'ver_filtros_informe_pagos',
      'descripcion' => '',
      'name' => 'actividades.ver_filtros_informe_pagos',
    ]);

    Permission::create([
      'titulo' => 'ver_columna_compra_informe_pagos',
      'descripcion' => '',
      'name' => 'actividades.ver_columna_compra_informe_pagos',
    ]);

    Permission::create([
      'titulo' => 'ver_boton_exportar_excel_informe_inscripciones',
      'descripcion' => '',
      'name' => 'actividades.ver_boton_exportar_excel_informe_inscripciones',
    ]);

    Permission::create([
      'titulo' => 'ver_filtros_informe_inscripciones',
      'descripcion' => '',
      'name' => 'actividades.ver_filtros_informe_inscripciones',
    ]);

    Permission::create([
      'titulo' => 'ver_columna_compra_informe_inscripciones',
      'descripcion' => '',
      'name' => 'actividades.ver_columna_compra_informe_inscripciones',
    ]);

    Permission::create([
      'titulo' => 'lista_asistentes_todos_informe_inscripciones',
      'descripcion' => '',
      'name' => 'actividades.lista_asistentes_todos_informe_inscripciones',
    ]);

    Permission::create([
      'titulo' => 'lista_asistentes_todos_informe_compras',
      'descripcion' => '',
      'name' => 'actividades.lista_asistentes_todos_informe_compras',
    ]);

    Permission::create([
      'titulo' => 'lista_asistentes_todos_informe_pagos',
      'descripcion' => '',
      'name' => 'actividades.lista_asistentes_todos_informe_pagos',
    ]);

    Permission::create([
      'titulo' => 'ver_boton_cargar_archivo_historial_carga_de_archivo',
      'descripcion' => '',
      'name' => 'actividades.ver_boton_cargar_archivo_historial_carga_de_archivo',
    ]);

    Permission::create([
      'titulo' => 'pestana_abonos_actividad',
      'descripcion' => '',
      'name' => 'actividades.pestana_abonos_actividad',
    ]);

    Permission::create([
      'titulo' => 'pestana_novedades_actividad',
      'descripcion' => '',
      'name' => 'actividades.pestana_novedades_actividad',
    ]);

    Permission::create([
      'titulo' => 'opcion_novedades_actividad',
      'descripcion' => '',
      'name' => 'actividades.opcion_novedades_actividad',
    ]);

    Permission::create([
      'titulo' => 'opcion_abonos_actividad',
      'descripcion' => '',
      'name' => 'actividades.opcion_abonos_actividad',
    ]);

    Permission::create([
      'titulo' => 'ver_todas_las_actividades',
      'descripcion' => '',
      'name' => 'actividades.ver_todas_las_actividades',
    ]);

    Permission::create([
      'titulo' => 'sub_item_configuracion_general_web_checking',
      'descripcion' => '',
      'name' => 'actividades.sub_item_configuracion_general_web_checking',
    ]);

    Permission::create([
      'titulo' => 'ver_web_checkin',
      'descripcion' => '',
      'name' => 'actividades.ver_web_checkin',
    ]);

    Permission::create([
      'titulo' => 'pestana_anadir_servidores_actividad',
      'descripcion' => '',
      'name' => 'actividades.pestana_anadir_servidores_actividad',
    ]);

    // Puntos de pago
    Permission::create([
      'titulo' => 'item_puntos_de_pago',
      'descripcion' => '',
      'name' => 'puntos_de_pago.item_puntos_de_pago',
    ]);

    Permission::create([
      'titulo' => 'subitem_lista_punto_de_pago',
      'descripcion' => '',
      'name' => 'puntos_de_pago.subitem_lista_punto_de_pago',
    ]);

    Permission::create([
      'titulo' => 'subitem_lista_cajas',
      'descripcion' => '',
      'name' => 'puntos_de_pago.subitem_lista_cajas',
    ]);

    Permission::create([
      'titulo' => 'subitem_nueva_persona_punto_de_pago',
      'descripcion' => '',
      'name' => 'puntos_de_pago.subitem_nueva_persona_punto_de_pago',
    ]);

    Permission::create([
      'titulo' => 'subitem_compras_de_actividades_punto_de_pago',
      'descripcion' => '',
      'name' => 'puntos_de_pago.subitem_compras_de_actividades_punto_de_pago',
    ]);

    Permission::create([
      'titulo' => 'subitem_donaciones_punto_de_pago',
      'descripcion' => '',
      'name' => 'puntos_de_pago.subitem_donaciones_punto_de_pago',
    ]);

    Permission::create([
      'titulo' => 'ver_boton_nuevo_punto_de_pago',
      'descripcion' => '',
      'name' => 'puntos_de_pago.ver_boton_nuevo_punto_de_pago',
    ]);

    Permission::create([
      'titulo' => 'opcion_modificar_punto_de_pago',
      'descripcion' => '',
      'name' => 'puntos_de_pago.opcion_modificar_punto_de_pago',
    ]);

    Permission::create([
      'titulo' => 'opcion_eliminar_punto_de_pago',
      'descripcion' => '',
      'name' => 'puntos_de_pago.opcion_eliminar_punto_de_pago',
    ]);

    Permission::create([
      'titulo' => 'opcion_dar_de_alta_punto_de_pago',
      'descripcion' => '',
      'name' => 'puntos_de_pago.opcion_dar_de_alta_punto_de_pago',
    ]);

    Permission::create([
      'titulo' => 'opcion_dar_de_baja_punto_de_pago',
      'descripcion' => '',
      'name' => 'puntos_de_pago.opcion_dar_de_baja_punto_de_pago',
    ]);

    Permission::create([
      'titulo' => 'ver_boton_nueva_caja',
      'descripcion' => '',
      'name' => 'puntos_de_pago.ver_boton_nueva_caja',
    ]);

    Permission::create([
      'titulo' => 'opcion_historial_de_cierres_caja',
      'descripcion' => '',
      'name' => 'puntos_de_pago.opcion_historial_de_cierres_caja',
    ]);

    Permission::create([
      'titulo' => 'opcion_registros_de_caja',
      'descripcion' => '',
      'name' => 'puntos_de_pago.opcion_registros_de_caja',
    ]);

    Permission::create([
      'titulo' => 'opcion_dar_de_alta_caja',
      'descripcion' => '',
      'name' => 'puntos_de_pago.opcion_dar_de_alta_caja',
    ]);

    Permission::create([
      'titulo' => 'opcion_cierre_de_caja',
      'descripcion' => '',
      'name' => 'puntos_de_pago.opcion_cierre_de_caja',
    ]);

    Permission::create([
      'titulo' => 'opcion_desactivar_caja',
      'descripcion' => '',
      'name' => 'puntos_de_pago.opcion_desactivar_caja',
    ]);

    Permission::create([
      'titulo' => 'opcion_activar_caja',
      'descripcion' => '',
      'name' => 'puntos_de_pago.opcion_activar_caja',
    ]);

    Permission::create([
      'titulo' => 'opcion_dar_de_baja_caja',
      'descripcion' => '',
      'name' => 'puntos_de_pago.opcion_dar_de_baja_caja',
    ]);

    Permission::create([
      'titulo' => 'subitem_abonos_de_actividades_punto_de_pago',
      'descripcion' => '',
      'name' => 'puntos_de_pago.subitem_abonos_de_actividades_punto_de_pago',
    ]);

    Permission::create([
      'titulo' => 'lista_cajas_todas',
      'descripcion' => '',
      'name' => 'puntos_de_pago.lista_cajas_todas',
    ]);

    Permission::create([
      'titulo' => 'opcion_anular_registro_caja',
      'descripcion' => '',
      'name' => 'puntos_de_pago.opcion_anular_registro_caja',
    ]);

    // Informes
    Permission::create([
      'titulo' => 'item_informes',
      'descripcion' => '',
      'name' => 'informes.item_informes',
    ]);

    Permission::create([
      'titulo' => 'subitem_informe_ministerios_generales',
      'descripcion' => '',
      'name' => 'informes.subitem_informe_ministerios_generales',
    ]);

    Permission::create([
      'titulo' => 'subitem_informe_mima',
      'descripcion' => '',
      'name' => 'informes.subitem_informe_mima',
    ]);

    Permission::create([
      'titulo' => 'subitem_informe_no_reportados',
      'descripcion' => '',
      'name' => 'informes.subitem_informe_no_reportados',
    ]);

    Permission::create([
      'titulo' => 'subitem_informe_almah',
      'descripcion' => '',
      'name' => 'informes.subitem_informe_almah',
    ]);

    Permission::create([
      'titulo' => 'subitem_informe_inasistencia_grupos',
      'descripcion' => '',
      'name' => 'informes.subitem_informe_inasistencia_grupos',
    ]);

    Permission::create([
      'titulo' => 'privilegio_administrar_informes',
      'descripcion' => '',
      'name' => 'informes.privilegio_administrar_informes',
    ]);

    Permission::create([
      'titulo' => 'seccion_informes_personalizados',
      'descripcion' => '',
      'name' => 'informes.seccion_informes_personalizados',
    ]);

    // Peticiones
    Permission::create([
      'titulo' => 'ver_boton_nueva_peticion',
      'descripcion' => '',
      'name' => 'peticiones.ver_boton_nueva_peticion',
    ]);

    Permission::create([
      'titulo' => 'item_peticiones',
      'descripcion' => '',
      'name' => 'peticiones.item_peticiones',
    ]);

    Permission::create([
      'titulo' => 'subitem_mis_peticiones',
      'descripcion' => '',
      'name' => 'peticiones.subitem_mis_peticiones',
    ]);

    Permission::create([
      'titulo' => 'subitem_panel_peticiones',
      'descripcion' => '',
      'name' => 'peticiones.subitem_panel_peticiones',
    ]);

    Permission::create([
      'titulo' => 'subitem_gestionar_peticiones',
      'descripcion' => '',
      'name' => 'peticiones.subitem_gestionar_peticiones',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'lista_peticiones_todas',
      'descripcion' => '',
      'name' => 'peticiones.lista_peticiones_todas',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'lista_peticiones_solo_ministerio',
      'descripcion' => '',
      'name' => 'peticiones.lista_peticiones_solo_ministerio',
    ]);

    Permission::create([
      'titulo' => 'opcion_eliminar',
      'descripcion' => '',
      'name' => 'peticiones.opcion_eliminar',
    ])->syncRoles([$superAdmin]);

    Permission::create([
      'titulo' => 'opcion_eliminacion_masiva',
      'descripcion' => '',
      'name' => 'peticiones.opcion_eliminacion_masiva',
    ])->syncRoles([$superAdmin]);

    // Padres
    Permission::create([
      'titulo' => 'item_padres',
      'descripcion' => '',
      'name' => 'padres.item_padres',
    ]);

    Permission::create([
      'titulo' => 'subitem_lista_hijos',
      'descripcion' => '',
      'name' => 'padres.subitem_lista_hijos',
    ]);

    Permission::create([
      'titulo' => 'subitem_nuevo_hijo',
      'descripcion' => '',
      'name' => 'padres.subitem_nuevo_hijo',
    ]);

    Permission::create([
      'titulo' => 'opcion_modificar_hijo',
      'descripcion' => '',
      'name' => 'padres.opcion_modificar_hijo',
    ]);

    // Escuelas
    Permission::create([
      'titulo' => 'item_escuelas',
      'descripcion' => '',
      'name' => 'escuelas.item_escuelas',
    ]);

    // Familiar
    Permission::create([
      'titulo' => 'item_familiar',
      'descripcion' => '',
      'name' => 'familiar.item_familiar',
    ]);

    Permission::create([
      'titulo' => 'subitem_gentionar_relaciones',
      'descripcion' => '',
      'name' => 'familiar.subitem_gentionar_relaciones',
    ]);

    Permission::create([
      'titulo' => 'opcion_modificar_relacion_familiar',
      'descripcion' => '',
      'name' => 'familiar.opcion_modificar_relacion_familiar',
    ]);

    Permission::create([
      'titulo' => 'opcion_eliminar_relacion_familiar',
      'descripcion' => '',
      'name' => 'familiar.opcion_eliminar_relacion_familiar',
    ]);

    Permission::create([
      'titulo' => 'ver_boton_nueva_relacion_familiar',
      'descripcion' => '',
      'name' => 'familiar.ver_boton_nueva_relacion_familiar',
    ]);

    //Dashboard
    Permission::create([
      'titulo' => 'dashboard_mostrar_calendario',
      'descripcion' => '',
      'name' => 'dashboard.dashboard_mostrar_calendario',
    ]);

    Permission::create([
      'titulo' => 'ver_banners_videos_todos',
      'descripcion' => '',
      'name' => 'dashboard.ver_banners_videos_todos',
    ]);

    Permission::create([
      'titulo' => 'ver_video_software_redil_defecto',
      'descripcion' => '',
      'name' => 'dashboard.ver_video_software_redil_defecto',
    ]);

    // Administracion
    Permission::create([
      'titulo' => 'ver_cronograma_desarrollo',
      'descripcion' => '',
      'name' => 'administracion.ver_cronograma_desarrollo',
    ]);

    Permission::create([
      'titulo' => 'editar_item_etapas_crecimiento',
      'descripcion' => '',
      'name' => 'administracion.editar_item_etapas_crecimiento',
    ]);

    Permission::create([
      'titulo' => 'ver_item_etapas_crecimiento',
      'descripcion' => '',
      'name' => 'administracion.ver_item_etapas_crecimiento',
    ]);
  }
}
