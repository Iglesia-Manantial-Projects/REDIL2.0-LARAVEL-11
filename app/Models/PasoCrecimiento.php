<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PasoCrecimiento extends Model
{
  use HasFactory;
  protected $table = 'pasos_crecimiento';
  protected $guarded = [];

  public function usuarios(): BelongsToMany
  {
    return $this->belongsToMany(User::class, 'crecimiento_usuario', 'paso_crecimiento_id', 'user_id')->withPivot(
      'estado_id',
      'fecha',
      'detalle',
      'created_at',
      'updated_at'
    );
  }

  public function automatizaciones(): HasMany
  {
    return $this->hasMany(AutomatizacionPasoCrecimiento::class);
  }

  public function seccion(): BelongsTo
  {
    return $this->belongsTo(SeccionPasoCrecimiento::class);
  }

  public function roles(): BelongsToMany
  {
    return $this->belongsToMany(Role::class, 'privilegios_pasos_crecimiento_roles', 'paso_crecimiento_id', 'rol_id')->withPivot(
      'created_at',
      'updated_at'
    );
  }

}
