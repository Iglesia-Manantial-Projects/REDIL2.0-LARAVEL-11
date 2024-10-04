<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;


class Grupo extends Model
{
  use HasFactory;
  protected $table = 'grupos';
  protected $guarded = [];

  public function servidores(): BelongsToMany
  {
    return $this->belongsToMany(User::class, 'servidores_grupo', 'grupo_id', 'user_id')->withTimestamps();
  }

  public function asistentes(): BelongsToMany
  {
    return $this->belongsToMany(User::class, 'integrantes_grupo', 'grupo_id', 'user_id')->withTimestamps();
  }

  public function sede(): BelongsTo
  {
    return $this->belongsTo(Sede::class);
  }

  public function tipoGrupo(): BelongsTo
  {
    return $this->belongsTo(TipoGrupo::class);
  }

  // obtiene los encargados de un grupo
  public function encargados(): BelongsToMany
  {
    return $this->belongsToMany(User::class, 'encargados_grupo', 'grupo_id', 'user_id')
      ->withPivot('id')
      ->withTimestamps();
  }

  //funcion para crear relacion entre reportes de grupo y grupos
  public function reportes(): HasMany
  {
    return $this->hasMany(ReporteGrupo::class);
  }

  public function tipoDeVivienda(): BelongsTo
  {
    return $this->belongsTo(TipoVivienda::class, 'tipo_vivienda_id');
  }

  public function reportesBajaAlta(): HasMany
  {
      return $this->hasMany(ReporteGrupoBajaAlta::class);

  }

  public function usuarioCreacion(): BelongsTo
  {
    return $this->belongsTo(User::class, 'usuario_creacion_id');
  }

  public function camposExtras(): BelongsToMany
  {
    return $this->belongsToMany(CampoExtraGrupo::class, 'grupo_opcion_campo_extra', 'grupo_id', 'campo_extra_grupo_id')
      ->withPivot('valor')
      ->withTimestamps();
  }

  public function encargadosDirectos()
  {
    $lideres = $this->encargados()
      ->leftJoin('tipo_usuarios', 'users.tipo_usuario_id', '=', 'tipo_usuarios.id')
      ->selectRaw(
        "users.id, CONCAT(users.primer_nombre, ' ',users.primer_apellido) as nombre, users.primer_nombre, users.primer_apellido, users.segundo_nombre, users.segundo_apellido, foto,
        tipo_usuarios.nombre as tipo_usuario, tipo_usuarios.color, tipo_usuarios.icono"
      )
      ->get()
      ->unique('id');

    return $lideres;
  }

  public function gruposMinisterio($tipo = 'objeto', $listaAsistentes = 'sin-eliminados')
  {
    $array_ids_nuevos_grupos = [];
    $array_ids_grupos = [];

    $configuracion = Configuracion::find(1);

    if ($listaAsistentes == 'sin-eliminados') {
      //$array_ids_asistentes = $this->asistentes()->select('id')->lists('id');
      //$grupos_asistentes = Asistente::with("grupos")->whereIn('id', $array_ids_asistentes)->get()->lists("grupos");

      $grupos_asistentes = $this->asistentes()
        ->leftJoin('encargados_grupo', 'users.id', '=', 'encargados_grupo.user_id')
        ->whereNotNull('encargados_grupo.grupo_id')
        ->select('encargados_grupo.grupo_id')
        ->pluck('encargados_grupo.grupo_id')
        ->toArray();
    } elseif ($listaAsistentes == 'solo-eliminados') {
      // $array_ids_asistentes=$this->asistentes()->onlyTrashed()->select('id')->lists('id');
      // $grupos_asistentes=Asistente::onlyTrashed()->with("grupos")->whereIn('id', $array_ids_asistentes)->get()->lists("grupos");
      $grupos_asistentes = $this->asistentes()
        ->onlyTrashed()
        ->leftJoin('encargados_grupo', 'users.id', '=', 'encargados_grupo.user_id')
        ->whereNotNull('encargados_grupo.grupo_id')
        ->select('encargados_grupo.grupo_id')
        ->pluck('encargados_grupo.grupo_id')
        ->toArray();
    } else {
      /*$array_ids_asistentes = $this->asistentes()
        ->withTrashed()
        ->select('id')
        ->lists('id');
      $grupos_asistentes = Asistente::withTrashed()
        ->with('grupos')
        ->whereIn('id', $array_ids_asistentes)
        ->get()
        ->lists('grupos');*/

      $grupos_asistentes = $this->asistentes()
        ->withTrashed()
        ->leftJoin('encargados_grupo', 'users.id', '=', 'encargados_grupo.user_id')
        ->whereNotNull('encargados_grupo.grupo_id')
        ->select('encargados_grupo.grupo_id')
        ->pluck('encargados_grupo.grupo_id')
        ->toArray();
    }

    $grupos_excluidos = $this->encargados()
      ->leftJoin('grupos_excluidos', 'users.id', '=', 'grupos_excluidos.user_id')
      ->whereNotNull('encargados_grupo.grupo_id')
      ->select('grupos_excluidos.grupo_id')
      ->pluck('grupos_excluidos.grupo_id')
      ->toArray();
    /*
    $ids_lideres_grupo = Helper::obtenerArrayIds($this->encargados()->get());
    $grupos_excluidos = GrupoExcluido::whereIn('grupos_excluidos.asistente_id', $ids_lideres_grupo)
      ->get()
      ->lists('grupo_id');*/

    $array_ids_grupos_no_repetidos = [];
    array_push($array_ids_grupos_no_repetidos, $this->id);

    while (count($array_ids_grupos_no_repetidos) > 0) {
      $array_ids_nuevos_grupos = [];

      $array_ids_nuevos_grupos = array_merge($array_ids_nuevos_grupos, $grupos_asistentes);
      $array_ids_nuevos_grupos = array_values(array_unique($array_ids_nuevos_grupos));
      $array_ids_nuevos_grupos = array_diff($array_ids_nuevos_grupos, $grupos_excluidos);

      $array_ids_asistentes = IntegranteGrupo::whereIn('integrantes_grupo.grupo_id', $array_ids_nuevos_grupos)
        ->select('user_id')
        ->pluck('user_id')
        ->toArray();

      if ($listaAsistentes == 'sin-eliminados') {
        /*
        $grupos_asistentes = Asistente::with('grupos')
          ->whereIn('asistentes.id', $array_ids_asistentes)
          ->get()
          ->lists('grupos');*/

        $grupos_asistentes = User::whereIn('users.id', $array_ids_asistentes)
          ->leftJoin('encargados_grupo', 'users.id', '=', 'encargados_grupo.user_id')
          ->whereNotNull('encargados_grupo.grupo_id')
          ->select('encargados_grupo.grupo_id')
          ->pluck('encargados_grupo.grupo_id')
          ->toArray();
      } elseif ($listaAsistentes == 'solo-eliminados') {
        /*$grupos_asistentes = Asistente::onlyTrashed()
          ->with('grupos')
          ->whereIn('asistentes.id', $array_ids_asistentes)
          ->get()
          ->lists('grupos');*/

        $grupos_asistentes = User::onlyTrashed()
          ->whereIn('users.id', $array_ids_asistentes)
          ->leftJoin('encargados_grupo', 'users.id', '=', 'encargados_grupo.user_id')
          ->whereNotNull('encargados_grupo.grupo_id')
          ->select('encargados_grupo.grupo_id')
          ->pluck('encargados_grupo.grupo_id')
          ->toArray();
      } else {
        /*$grupos_asistentes = Asistente::withTrashed()
          ->with('grupos')
          ->whereIn('asistentes.id', $array_ids_asistentes)
          ->get()
          ->lists('grupos');*/

        $grupos_asistentes = User::withTrashed()
          ->whereIn('users.id', $array_ids_asistentes)
          ->leftJoin('encargados_grupo', 'users.id', '=', 'encargados_grupo.user_id')
          ->whereNotNull('encargados_grupo.grupo_id')
          ->select('encargados_grupo.grupo_id')
          ->pluck('encargados_grupo.grupo_id')
          ->toArray();
      }
      $array_ids_grupos_no_repetidos = array_diff($array_ids_nuevos_grupos, $array_ids_grupos);
      $array_ids_grupos_no_repetidos = array_values(array_unique($array_ids_grupos_no_repetidos));
      $array_ids_grupos = array_merge($array_ids_grupos, $array_ids_nuevos_grupos);
    }

    $array_ids_grupos = array_values(array_unique($array_ids_grupos));

    if ($tipo == 'objeto') {
      $grupos_ministerio = Grupo::whereIn('grupos.id', $array_ids_grupos);
    } else {
      $grupos_ministerio = $array_ids_grupos;
    }

    return $grupos_ministerio;
  }

  public static function gruposNuevos($tipo="objeto")
  {
    $rolActivo= auth()->user()->roles()->wherePivot('activo', true)->first();

    $nuevaFecha = Carbon::now()->subDays(30)->format('Y-m-d');

    if ($rolActivo->hasPermissionTo('grupos.lista_grupos_todos')){
      $grupos = Grupo::where('fecha_apertura', '>', $nuevaFecha )->where('grupos.dado_baja', FALSE);
    }

    if ($rolActivo->hasPermissionTo('grupos.lista_grupos_solo_ministerio')){
      $grupos = auth()->user()->gruposMinisterio()->where('fecha_apertura', '>', $nuevaFecha )->where('grupos.dado_baja', FALSE);
    }

    return $grupos;
  }

  public static function gruposSinLider($tipo="objeto")
  {
      $rolActivo = auth()->user()->roles()->wherePivot('activo', true)->first();

      if ($rolActivo->hasPermissionTo('grupos.lista_grupos_todos') || isset(auth()->user()->iglesiaEncargada()->first()->id)){
          $grupos=Grupo::where('grupos.dado_baja', FALSE);
      }

      if($rolActivo->hasPermissionTo('grupos.lista_grupos_solo_ministerio')){
          $grupos = auth()->user()->gruposMinisterio()->where('grupos.dado_baja', FALSE);
      }

      $grupos= $grupos->leftJoin('encargados_grupo', 'grupos.id', '=', 'encargados_grupo.grupo_id')
              ->select("*", "grupos.id")
              ->where('encargados_grupo.grupo_id', '=', NULL)
              ->where('grupos.dado_baja', FALSE);

      $gruposIds= $grupos->select('grupos.id')
      ->pluck('grupos.id')
      ->toArray();

      $grupos=Grupo::whereIn('grupos.id', $gruposIds);
      return $grupos;

  }

  public function ultimoReporteDelGrupo()
  {
    return $this->reportes()->orderBy('fecha', 'desc')->first();
  }

  public function alDia(){
    $ultimoReporte= $this->ultimoReporteDelGrupo();
    $fechaHoy = Carbon::now()->format('Y-m-d');
    $fechaReporte = Carbon::parse($ultimoReporte->fecha)->addDays(8)->format('Y-m-d');

    if($fechaReporte>$fechaHoy)
    return true;
    else
    return false;
  }

  public function asignarSede($user_id="")
  {
      if($user_id==""){
        //La sede con default TRUE es la sede principal
        $sedeDefault = Sede::where('default', TRUE)->first();

          if (auth()->check()) {
            $rolActivo = auth()->user()->roles()->wherePivot('activo', true)->first();
              if (auth()->user()->sede_id) {
                $this->sede_id = auth()->user()->sede_id;
              }else{
                $sede = Sede::find($rolActivo->lista_asistentes_sede_id);
                $sede
                  ? $this->sede_id = $rolActivo->lista_asistentes_sede_id
                  : $this->sede_id = $sedeDefault->id;
              }
          } else {
            $this->sede_id = $sedeDefault->id;
          }
      }else{
        $user= User::find($user_id);
        $user
          ? $this->sede_id = $user->sede_id
          : '';
      }
      $this->save();

  }

  public function asignarEncargado($userId){
    if(!$this->encargados()->attach($userId))
    {
      $this->asignarSede($userId);
      return "true";
    }
    else{
      return "false";
    }
  }

  public function eliminarEncargado($userId){
    $this->encargados()->detach($userId);
    return "true";
  }

}
