<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends SpatieRole
{
  use HasFactory;
  protected $table = 'roles';
  protected $guarded = [];

  public function formularios(): BelongsToMany
  {
    return $this->belongsToMany(
      FormularioUsuario::class,
      'formulario_usuario_rol',
      'rol_id',
      'formulario_usuario_id'
    )->withTimestamps();
  }

  // antes tipoAsistentesBloqueados
  public function tipoUsuariosBloqueados(): BelongsToMany
  {
    return $this->belongsToMany(
      TipoUsuario::class,
      'tipo_usuario_bloqueado_rol',
      'rol_id',
      'tipo_usuario_id'
    );
  }

  public function privilegiosTiposGrupo(): BelongsToMany
  {
    return $this->belongsToMany(
      TipoGrupo::class,
      'privilegios_tipo_grupo_rol',
      'rol_id',
      'tipo_grupo_id'
    )->withPivot('asignar_asistente', 'desvincular_asistente', 'asignar_encargado', 'desvincular_encargado', 'created_at', 'updated_at');
  }


  public function pasosCrecimiento(): BelongsToMany
  {
    return $this->belongsToMany(PasoCrecimiento::class, 'privilegios_pasos_crecimiento_roles', 'rol_id', 'paso_crecimiento_id')->withPivot(
      'created_at',
      'updated_at'
    );
  }
}
