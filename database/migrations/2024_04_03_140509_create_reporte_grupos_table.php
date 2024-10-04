<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reporte_grupos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->date('fecha');
            $table->string('tema',100);
            $table->text('observacion')->nullable();
            $table->integer('grupo_id');
            $table->integer('invitados')->nullable();
            $table->boolean('reporte_a_tiempo')->default(0);
            $table->text('clasificacion_asistentes')->nullable();
            $table->text('informacion_del_grupo')->nullable();
            $table->text('informacion_encargado_grupo')->nullable();
            $table->text('encargados_ascendentes')->nullable();
            $table->text('sumatoria_adicional_clasificacion')->nullable();//	Este campo guarda un JSON con las sumatorias segun su clasificacion, solo si en la tabla clasificacion_asistente_reporte_grupo el campo tiene_sumatoria_adicional es TRUE.
            $table->integer('cantidad_asistencias')->nullable();
            $table->integer('cantidad_inasistencias')->nullable();
            $table->boolean('aprobado')->nullable();
            $table->boolean('cerrado')->nullable();
            $table->integer('total_ofrendas')->default(0);
            $table->integer('autor_creacion')->default(1);
            $table->integer('autor_aprobacion')->nullable();
            $table->dateTime('fecha_aprobacion')->nullable();
            $table->integer('autor_cierre')->nullable();
            $table->dateTime('fecha_cierre')->nullable();
            $table->boolean('finalizado')->nullable()->default(1);
            $table->boolean('no_reporte')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reporte_grupos');
    }
};
