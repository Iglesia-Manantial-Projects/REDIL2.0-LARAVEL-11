<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ReporteReunion extends Model
{
  use HasFactory;
  protected $table = 'reporte_reuniones';
  protected $guarded = [];

  public function usuarios(): BelongsToMany
  {
      return $this->belongsToMany(User::class, "asistencia_reuniones")
      ->withPivot('asistio','reservacion','invitados','created_at','updated_at','observacion','autor_creacion_reserva_id','autor_creacion_asistencia_id');
  }
}
