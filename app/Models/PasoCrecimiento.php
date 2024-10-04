<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
