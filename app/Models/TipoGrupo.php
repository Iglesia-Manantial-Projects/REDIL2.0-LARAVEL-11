<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TipoGrupo extends Model
{
  use HasFactory;

  public function pasosCrecimiento(): BelongsToMany
  {
    return $this->belongsToMany(
      PasoCrecimiento::class,
      'tipo_grupo_pasos_crecimientos',
      'tipo_grupo_id',
      'paso_crecimiento_id'
    )->withPivot('created_at', 'updated_at', 'estado_por_defecto', 'pregunta');
  }

  // antes privilegiosUsuarios
  public function privilegiosRoles()
  {
    return $this->belongsToMany(
      Role::class,
      'privilegios_tipo_grupo_rol',
      'tipo_grupo_id',
      'rol_id'
    )->withPivot('asignar_asistente', 'desvincular_asistente', 'asignar_encargado', 'desvincular_encargado', 'created_at', 'updated_at');
  }

  // antes tipoAsistentesPermitidos
  public function tipoUsuariosPermitidos()
  {
    return $this->belongsToMany(
      TipoUsuario::class,
      'asignaciones_permitidas_tipo_usuario_tipo_grupo' ,
      'tipo_grupo_id',
      'tipo_usuario_id'
    )->withPivot('para_encargados','para_asistentes','created_at','updated_at');

  }
}
