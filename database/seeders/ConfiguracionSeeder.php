<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Configuracion;

class ConfiguracionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Configuracion::create([
      'dias_plazo_reporte_grupo' => null,
      'reportar_grupo_cualquier_dia' => 0,
      'version' => 1,
      'sumar_encargado_asistencia_grupo' => 1,
      'dia_corte_reportes_grupos' => 1,
      'nombre_informacion_opcional' => 'Información opcional',
      'identificacion_obligatoria' => 1,
      'usa_listas_geograficas' => TRUE,
      'usa_modal_asistente_liviano' => 1,
      'nombre_campo_reservado' => 'Campo reservado',
      'correo_por_defecto' => 1,
      //'nombre_resaltador_informe_mensual_reportes_grupo' => '',
      'valor_minimo_resaltador_informe_mensual_reportes_grupo' => 0,
      'valor_maximo_resaltador_informe_mensual_reportes_grupo' => 0,
      'tiempo_para_definir_inactivo_grupo' => 5,
      'tiempo_para_definir_inactivo_reunion' => 5,
      //'url_img' => '/img_dinamicas',
      'identificacion_solo_numerica' => 1,
      'reestructuracion_asistentes_grupos' => 0,
      'edad_minima_logueo' => 14,
      'direccion_obligatoria' => 1,
      'limite_menor_edad' => 18,
      'enviar_correo_bienvenida_nuevo_asistente' => 1,
      'logo_personalizado' => 0,
      //'label_invitados_reporte_grupo' => '',
      //'label_campo_opcional1'         => '',
      'campo_opcional1_obligatorio' => 1,
      'habilitar_campo_opcional1_grupo' => 1,
      'nombre_grupo_obligatorio' => 1,
      'habilitar_nombre_grupo' => 1,
      'tipo_grupo_obligatorio' => 1,
      'habilitar_tipo_grupo' => 1,
      'fecha_creacion_grupo_obligatorio' => 1,
      'habilitar_fecha_creacion_grupo' => 1,
      'telefono_grupo_obligatorio' => 1,
      'habilitar_telefono_grupo' => 1,
      'habilitar_tipo_vivienda_grupo' => 1,
      'tipo_vivienda_grupo_obligatorio' => 1,
      //'titulo_seccion_planeacion_grupo' => '',
      'dia_planeacion_grupo_obligatorio' => 1,
      //'label_campo_dia_planeacion_grupo' => '',
      'hora_planeacion_grupo_obligatorio' => 1,
      //'label_campo_hora_planeacion_grupo' => '',
      //'titulo_seccion_reunion_grupo' => '',
      'habilitar_dia_reunion_grupo' => 1,
      'dia_reunion_grupo_obligatorio' => 1,
      //'label_campo_dia_reunion_grupo' => '',
      'habilitar_hora_reunion_grupo' => 1,
      //'label_campo_hora_reunion_grupo' => '',
      'hora_reunion_grupo_obligatorio' => 1,
      'habilitar_direccion_grupo' => 1,
      'direccion_grupo_obligatorio' => 1,
      //'label_direccion_grupo' => '',
      //'zona_horaria' => '',
      //'label_invitado_reuniones' => '',
      //'label_observacion_invitados_modal' => '',
      'habilitar_contador_anadir_invitados_modal' => 1,
      'habilitar_observacion_anadir_invitados_modal' => 0,
      'text_default_observacion_invitados_modal' => null,
      'label_seccion_campos_extra' => 'Campos extras',
      'visible_seccion_campos_extra' => 1,
      'asistente_pertenece_a_varios_grupos' => 1,
      'visible_seccion_campos_extra_grupo' => 1,
      'maximos_niveles_grafico_ministerio' => 2,
      'mensaje_bienvenida' => '<p> Hemos registrado tus datos personales en nuestra plataforma y ahora eres de los nuestros
          <br>
          También hemos creado para ti, una cuenta con la cual podrás enterarte y disfrutar de los servicios que te ofrecemos en la iglesia.
         </p>',
      'titulo_mensaje_bienvenida' => 'Bienvenido a nuestra iglesia',
      'banner_mensaje_bienvenida' => TRUE,
      //'mensaje_exito_auto_matricula' => '',
      //'mensaje_error_auto_matricula' => '',
      //'mensaje_existe_auto_matricula' => '',
      //'titulo_formulario_externo' => '',
      'espacio_academico_habilitado' => 1,
      'cantidad_intentos_auto_matricula' => 5,
      'dias_plazo_maximo_actualizacion_automatricula' => 5,
      'moneda_predeterminada_punto_pago' => 1,
      'mensaje_correo_punto_pago' => null,
      'label_fecha_creacion_grupo' => null,
      'fecha_inicio_ejecucion_llenar_grupos_de_grupo' => '2023-12-14 01:00:02',
      'fecha_fin_ejecucion_llenar_grupos_de_grupo' => '2023-12-14 01:00:02',
      'habilitar_salones_con_estaciones' => 1,
      'items_mixtos_escuelas_deshabilitados' => 1,
      'cierre_cortes_habilitado' => 1,
      'habilitar_traslados' => 1,
      'cantidad_dias_alerta_notas_maestro' => 12,
      'ruta_almacenamiento' => 'iglesia1'
    ]);
  }
}
