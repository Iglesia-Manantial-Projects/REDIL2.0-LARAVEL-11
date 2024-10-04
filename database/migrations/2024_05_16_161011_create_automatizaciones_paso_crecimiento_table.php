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
    Schema::create('automatizaciones_paso_crecimiento', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->integer('tipo_usuario_a_modificar'); //antes tipo_asistente_a_modificar
      $table->integer('estado_paso_crecimiento');
      $table->integer('paso_crecimiento_id');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('automatizaciones_paso_crecimiento');
  }
};
