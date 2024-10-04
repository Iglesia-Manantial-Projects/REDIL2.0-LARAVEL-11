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
    Schema::create('tipo_grupos', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->string('nombre', 50);
      $table->string('descripcion', 200)->nullable();
      $table->boolean('seguimiento_actividad')->nullable();
      $table->boolean('contiene_servidores')->nullable();
      $table->boolean('posible_grupo_sede')->default(0);
      $table
        ->integer('metros_cobertura')
        ->nullable()
        ->default(500);
      $table->boolean('ingresos_individuales_discipulos')->default(1);
      $table->boolean('ingresos_individuales_lideres')->default(1);
      $table->boolean('registra_datos_planeacion')->default(0);
      $table->boolean('servidores_solo_discipulos')->default(1);
      $table->string('color', 10)->nullable();
      $table->boolean('visible_mapa_asignacion')->default(1);
      $table->string('geo_icono', 30)->nullable();
      $table->string('nombre_plural', 35)->nullable();
      $table->boolean('tipo_evangelistico')->default(0); //	Este campo recibe una lista de ids de estado_civiles que se desea totalizar.
      $table->smallinteger('cantidad_maxima_reportes_semana')->default(1);
      $table->boolean('enviar_mensaje_bienvenida')->default(0);
      $table->text('mensaje_bienvenida')->nullable();
      $table->smallinteger('orden')->nullable();
      $table
        ->smallinteger('tiempo_para_definir_inactivo_grupo')
        ->nullable()
        ->default(30);
      $table
        ->boolean('inasistencia_obligatoria')
        ->nullable()
        ->default(1);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('tipo_grupos');
  }
};
