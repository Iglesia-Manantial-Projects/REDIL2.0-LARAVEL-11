<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('configuraciones', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->integer('dias_plazo_reporte_grupo')->nullable(); //Por ejemplo, si un grupo se reune todos los jueves y la variable dias_plazo_reporte_grupo=2 entonces va tener hasta el sabado para reportar.
      $table->boolean('reportar_grupo_cualquier_dia')->default(0);
      $table->smallInteger('version')->default(1);
      $table->boolean('sumar_encargado_asistencia_grupo')->default(1);
      $table->smallInteger('dia_corte_reportes_grupos')->nullable(); // Esta variable indica el dia de corte:   1= Domingo  7= Sabado  por ejemplo si ingresa 7 va a tener hasta el dia sabado  para reportar en esa misma semana.  NOTA: SI SE UTILIZA ESTE CAMPO, EL CAMPO DIAS_PLAZO_REPORTE_GRUPO DEBE SER NULL
      $table->string('nombre_informacion_opcional', 50)->nullable();
      $table->boolean('identificacion_obligatoria')->default(1);
      $table
        ->boolean('usa_listas_geograficas')
        ->nullable()
        ->default(1);
      $table
        ->boolean('usa_modal_asistente_liviano')
        ->nullable()
        ->default(1);
      $table->string('nombre_campo_reservado', 100)->nullable();
      $table->boolean('correo_por_defecto')->default(1);
      $table->string('nombre_resaltador_informe_mensual_reportes_grupo', 50)->default('Grupo sin Asistentes');
      $table->smallInteger('valor_minimo_resaltador_informe_mensual_reportes_grupo')->default(0);
      $table->smallInteger('valor_maximo_resaltador_informe_mensual_reportes_grupo')->default(0);
      $table
        ->smallInteger('tiempo_para_definir_inactivo_grupo')
        ->nullable()
        ->default(30);
      $table
        ->smallInteger('tiempo_para_definir_inactivo_reunion')
        ->nullable()
        ->default(30);
      $table->text('url_img')->nullable();
      $table
        ->boolean('identificacion_solo_numerica')
        ->nullable()
        ->default(1);
      $table->boolean('reestructuracion_asistentes_grupos')->default(1);
      $table->smallInteger('edad_minima_logueo')->default(0);
      $table->boolean('direccion_obligatoria')->default(1);
      $table->integer('limite_menor_edad')->default(18);
      $table
        ->boolean('enviar_correo_bienvenida_nuevo_asistente')
        ->nullable()
        ->default(1);
      $table
        ->boolean('logo_personalizado')
        ->nullable()
        ->default(1);
      $table
        ->string('label_invitados_reporte_grupo', 20)
        ->nullable()
        ->default('Invitados');
      $table
        ->string('label_campo_opcional1', 50)
        ->nullable()
        ->default('Invitados');
      $table
        ->boolean('campo_opcional1_obligatorio')
        ->nullable()
        ->default(1);
      $table
        ->boolean('habilitar_campo_opcional1_grupo')
        ->nullable()
        ->default(1);
      $table
        ->boolean('nombre_grupo_obligatorio')
        ->nullable()
        ->default(1);
      $table
        ->boolean('habilitar_nombre_grupo')
        ->nullable()
        ->default(1);
      $table
        ->boolean('tipo_grupo_obligatorio')
        ->nullable()
        ->default(1);
      $table
        ->boolean('habilitar_tipo_grupo')
        ->nullable()
        ->default(1);
      $table
        ->boolean('fecha_creacion_grupo_obligatorio')
        ->nullable()
        ->default(1);
      $table
        ->boolean('habilitar_fecha_creacion_grupo')
        ->nullable()
        ->default(1);
      $table
        ->boolean('telefono_grupo_obligatorio')
        ->nullable()
        ->default(1);
      $table
        ->boolean('habilitar_telefono_grupo')
        ->nullable()
        ->default(1);
      $table
        ->boolean('habilitar_tipo_vivienda_grupo')
        ->nullable()
        ->default(1);
      $table
        ->boolean('tipo_vivienda_grupo_obligatorio')
        ->nullable()
        ->default(1);
      $table
        ->string('titulo_seccion_planeacion_grupo', 50)
        ->nullable()
        ->default('Horario de planeación');
      $table
        ->boolean('dia_planeacion_grupo_obligatorio')
        ->nullable()
        ->default(1);
      $table
        ->string('label_campo_dia_planeacion_grupo', 50)
        ->nullable()
        ->default('Día planeación');
      $table
        ->boolean('hora_planeacion_grupo_obligatorio')
        ->nullable()
        ->default(1);
      $table
        ->string('label_campo_hora_planeacion_grupo', 50)
        ->nullable()
        ->default('Hora planeación');
      $table
        ->string('titulo_seccion_reunion_grupo', 50)
        ->nullable()
        ->default('Horario de reunión grupo');
      $table
        ->boolean('habilitar_dia_reunion_grupo')
        ->nullable()
        ->default(1);
      $table
        ->boolean('dia_reunion_grupo_obligatorio')
        ->nullable()
        ->default(1);
      $table
        ->string('label_campo_dia_reunion_grupo', 50)
        ->nullable()
        ->default('Día reunión');
      $table
        ->boolean('habilitar_hora_reunion_grupo')
        ->nullable()
        ->default(1);
      $table
        ->string('label_campo_hora_reunion_grupo', 50)
        ->nullable()
        ->default('Hora reunión');
      $table
        ->boolean('hora_reunion_grupo_obligatorio')
        ->nullable()
        ->default(1);
      $table
        ->boolean('habilitar_direccion_grupo')
        ->nullable()
        ->default(1);
      $table
        ->boolean('direccion_grupo_obligatorio')
        ->nullable()
        ->default(1);
      $table
        ->string('label_direccion_grupo', 100)
        ->nullable()
        ->default('Dirección');
      $table
        ->string('zona_horaria')
        ->nullable()
        ->default('America/Bogota');
      $table
        ->string('label_invitado_reuniones', 50)
        ->nullable()
        ->default('Invitados');
      $table
        ->text('label_observacion_invitados_modal')
        ->nullable()
        ->default('Observación');
      $table
        ->boolean('habilitar_contador_anadir_invitados_modal')
        ->nullable()
        ->default(1);
      $table
        ->boolean('habilitar_observacion_anadir_invitados_modal')
        ->nullable()
        ->default(1);
      $table->text('text_default_observacion_invitados_modal')->nullable();
      $table->string('label_seccion_campos_extra', 100)->nullable();
      $table
        ->boolean('visible_seccion_campos_extra')
        ->nullable()
        ->default(1);
      $table->boolean('asistente_pertenece_a_varios_grupos')->default(1);
      $table
        ->boolean('visible_seccion_campos_extra_grupo')
        ->nullable()
        ->default(1);
      $table->integer('maximos_niveles_grafico_ministerio')->default(2);
      $table->text('mensaje_bienvenida')->nullable()->default('<p> Tus datos se han registrado con éxito <br><br> También hemos creado para ti, una cuenta con la cual podrás enterarte y disfrutar de los servicios que te ofrecemos en la iglesia. <br><br>Tu primer ingreso será de la siguiente manera: <br><br> Entra a la plataforma y coloca tanto en su usuario como en contraseña.</B><p><br><br>');
      $table->string('titulo_mensaje_bienvenida', 100)->nullable()->default('Creación de cuenta - Software Redil');
      $table->boolean('banner_mensaje_bienvenida')->default(0);
      $table->text('mensaje_exito_auto_matricula')->nullable();
      $table->text('mensaje_error_auto_matricula')->nullable();
      $table->text('mensaje_existe_auto_matricula')->nullable();
      $table->string('titulo_formulario_externo', 100)->nullable();
      $table->boolean('espacio_academico_habilitado')->nullable()->default(1);
      $table->integer('cantidad_intentos_auto_matricula')->nullable()->default(1);
      $table->integer('dias_plazo_maximo_actualizacion_automatricula')->nullable();
      $table->integer('moneda_predeterminada_punto_pago')->nullable()->default(1);
      $table->text('mensaje_correo_punto_pago')->nullable();
      $table->string('label_fecha_creacion_grupo', 100)->nullable();
      $table->timestamp('fecha_inicio_ejecucion_llenar_grupos_de_grupo')->nullable();
      $table->timestamp('fecha_fin_ejecucion_llenar_grupos_de_grupo')->nullable();
      $table->boolean('habilitar_salones_con_estaciones')->nullable()->default(1);
      $table->boolean('items_mixtos_escuelas_deshabilitados')->nullable()->default(1);
      $table
        ->boolean('cierre_cortes_habilitado')
        ->nullable()
        ->default(1);
      $table
        ->boolean('habilitar_traslados')
        ->nullable()
        ->default(1);
      $table
        ->integer('cantidad_dias_alerta_notas_maestro')
        ->nullable()
        ->default(8);
      $table->boolean('vista_perfil_usuario_clasica')
      ->default(0);
      $table->text('ruta_almacenamiento')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('configuraciones');
  }
};
