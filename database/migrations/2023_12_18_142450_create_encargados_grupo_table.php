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
    Schema::create('encargados_grupo', function (Blueprint $table) {
      $table->id();
      $table->integer('grupo_id');
      $table->integer('user_id'); // antes asistente_id
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('encargados_grupo');
  }
};
