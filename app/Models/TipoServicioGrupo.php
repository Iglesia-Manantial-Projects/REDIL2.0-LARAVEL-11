<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TipoServicioGrupo extends Model
{
  use HasFactory;
  protected $table = 'tipo_servicio_grupos';
  protected $guarded = [];

  public function servidorGrupo(): BelongsToMany
  {
    return $this->belongsToMany(
      ServidorGrupo::class,
      'servicios_servidores_grupo',
      'tipo_servicio_grupos_id',
      'servidores_grupo_id'
    )->withTimestamps();
  }
}
