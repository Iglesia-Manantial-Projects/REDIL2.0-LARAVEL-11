<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ReporteGrupo extends Model
{
    use HasFactory;
    protected $table = 'reporte_grupos';
    protected $guarded = [];

    //funcion para crear relacion uno a muchos entre Reporte_Grupos y Grupo
    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class,'grupo_id');
    }


    //funcion para crear relacion muchos a muchos entre Reporte_Grupos y usuarios(Asistentes)
    public function usuarios(): BelongsToMany
    {
      return $this->belongsToMany(User::class, "asistencia_grupos")
      ->withPivot('asistio','observaciones','tipo_inasistencia','created_at','updated_at');
    }


}
