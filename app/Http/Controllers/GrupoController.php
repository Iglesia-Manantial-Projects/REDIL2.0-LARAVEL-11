<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use App\Models\Grupo;
use App\Models\TipoGrupo;
use App\Helpers\Helpers;
use App\Models\CampoInformeExcel;
use App\Models\CampoExtraGrupo;
use App\Models\GrupoExcluido;
use App\Models\Iglesia;
use App\Models\IntegranteGrupo;
use App\Models\Sede;
use App\Models\ServidorGrupo;
use App\Models\TipoVivienda;
use App\Models\User;
use Illuminate\Http\Request;
use \stdClass;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;



class GrupoController extends Controller
{
  public function listar(Request $request, $tipo = 'todos')
  {
    $configuracion = Configuracion::find(1);
    $rolActivo = auth()->user()->roles()->wherePivot('activo', true)->first();
    $tiposGruposIds = TipoGrupo::where("seguimiento_actividad","=",TRUE)->select('id')->pluck('id')->toArray();
    $tiposDeViviendas =  TipoVivienda::orderBy('nombre', 'asc')->get();
    $tiposDeGrupo = TipoGrupo::orderBy('nombre', 'asc')->get();
    $sedes = Sede::get();
    $grupos = [];
    $indicadoresGenerales = [];
    $indicadoresPortipoGrupo = [];
    $camposInformeExcel = CampoInformeExcel::where('selector_id','=',5)->orderBy('orden','asc')->get();

    $parametrosBusqueda = [];
    $parametrosBusqueda['buscar'] = $request->buscar;
    $parametrosBusqueda['filtroGrupo'] = $request->filtroGrupo;
    $parametrosBusqueda['filtroPorTipoDeGrupo'] = $request->filtroPorTipoDeGrupo;
    $parametrosBusqueda['filtroPorSedes'] = $request->filtroPorSedes;
    $parametrosBusqueda['filtroPorTiposDeViviendas'] = $request->filtroPorTiposDeViviendas;
    $parametrosBusqueda['bandera'] = '';
    $parametrosBusqueda['textoBusqueda'] = '';
    $parametrosBusqueda['tipo'] = $tipo;
    $parametrosBusqueda = (object) $parametrosBusqueda;

    if($rolActivo->hasPermissionTo('grupos.lista_grupos_todos') || $rolActivo->hasPermissionTo('grupos.lista_grupos_solo_ministerio') || $rolActivo->lista_grupos_sede_id!=NULL )
    {
      if($rolActivo->hasPermissionTo('grupos.lista_grupos_todos') || isset(auth()->user()->iglesiaEncargada()->first()->id)){
        $grupos = Grupo::leftJoin('encargados_grupo', 'grupos.id', '=', 'encargados_grupo.grupo_id')
        ->leftJoin('users', 'users.id', '=', 'encargados_grupo.user_id')
        ->select('grupos.*', 'users.primer_nombre', 'users.segundo_nombre', 'users.primer_apellido', 'users.segundo_apellido')
        ->get()
        ->unique('id');

        $gruposParaIndicadores = clone $grupos;
      }

      if($rolActivo->hasPermissionTo('grupos.lista_grupos_solo_ministerio')){
        $grupos = auth()->user()->gruposMinisterio()->leftJoin('encargados_grupo', 'grupos.id', '=', 'encargados_grupo.grupo_id')
        ->leftJoin('users', 'users.id', '=', 'encargados_grupo.user_id')
        ->select('grupos.*', 'users.primer_nombre', 'users.segundo_nombre', 'users.primer_apellido', 'users.segundo_apellido')
        ->get()
        ->unique('id');

        $gruposParaIndicadores = clone $grupos;
      }

    }

    // Contadores
      $item = new stdClass();
      $item->nombre = 'Todos';
      $item->url = 'todos';
      $item->cantidad = $gruposParaIndicadores->where('dado_baja', FALSE)->pluck('id')->count();
      $item->color = 'bg-label-success';
      $item->icono = 'ti ti-asterisk';
      $indicadoresGenerales[] = $item;

      $item = new stdClass();
      $item->nombre = 'Nuevos';
      $item->url = 'nuevos';
      $item->cantidad = Grupo::gruposNuevos()->select('grupos.id')->count();
      $item->color = 'bg-label-info';
      $item->icono = 'ti ti-heart';
      $indicadoresGenerales[] = $item;

      $item = new stdClass();
      $item->nombre = 'Sin geo referencia';
      $item->url = 'sin-georreferencia';
      $item->cantidad = $gruposParaIndicadores->whereNull("latitud")->whereNull("longitud")->where('dado_baja', FALSE)->pluck('id')->count();
      $item->color = 'bg-label-danger';
      $item->icono = 'ti ti-world-question';
      $indicadoresGenerales[] = $item;

      $item = new stdClass();
      $item->nombre = 'Grupos sin líderes';
      $item->url = 'grupos-sin-lideres';
      $item->cantidad = Grupo::gruposSinLider()->select('grupos.id')->count();
      $item->color = 'bg-label-danger';
      $item->icono = 'ti ti-user-question';
      $indicadoresGenerales[] = $item;

      $item = new stdClass();
      $item->nombre = 'Sin actividad';
      $item->url = 'sin-actividad';
      $item->cantidad = $gruposParaIndicadores->where('dado_baja', FALSE)->whereIn('tipo_grupo_id', $tiposGruposIds)->filter(function ($grupo) {
          $fechaMaximaActividad = Carbon::now()
          ->subDays($grupo->tipoGrupo->tiempo_para_definir_inactivo_grupo)
          ->format('Y-m-d');

          return $grupo->ultimo_reporte_grupo < $fechaMaximaActividad ||
          $grupo->ultimo_reporte_grupo == null;
      })->pluck('id')->count();
      $item->color = 'bg-label-danger';
      $item->icono = 'ti ti-exclamation-circle';
      $indicadoresGenerales[] = $item;

      $item = new stdClass();
      $item->nombre = 'Dados de baja';
      $item->url = 'dados-de-baja';
      $item->cantidad = $gruposParaIndicadores->where('dado_baja', TRUE)->pluck('id')->count();
      $item->color = 'bg-label-secondary';
      $item->icono = 'ti ti-circle-off';
      $indicadoresGenerales[] = $item;

      foreach ($tiposDeGrupo as $tipoGrupo) {
        $item = new stdClass();
        $item->nombre = $tipoGrupo->nombre;
        $item->url = $tipoGrupo->id;
        $item->cantidad = $gruposParaIndicadores
          ->where('dado_baja', FALSE)
          ->where('tipo_grupo_id', $tipoGrupo->id)
          ->count();
        $item->color = 'bg-label-success';
        $item->icono = 'ti ti-users-group';
        $indicadoresPortipoGrupo[] = $item;
      }
    // Fin contadores

     // filtrado por tipo ejemplo: "Todos o nuevos, o sin georeferencia o por los tipos de grupo como abiertos o cerrados, grupo familiar etc..."
     $grupos = $this->filtroPorTipo($grupos, $parametrosBusqueda);

     // filtro por busqueda
     $grupos = $this->filtrosBusqueda($grupos, $parametrosBusqueda);

    if ($grupos->count() > 0) {
      $grupos = $grupos->toQuery()->orderBy('id','desc')->paginate(12);
    } else {
      $grupos = Grupo::whereRaw('1=2')->paginate(1);
    }
    //return $camposInformeExcel;
    $camposExtras = CampoExtraGrupo::where('visible','=', true)->get();

    return view('contenido.paginas.grupos.listar', [
      'configuracion' => $configuracion,
      'rolActivo' => $rolActivo,
      'grupos' => $grupos,
      'tipo' => $tipo,
      'parametrosBusqueda' => $parametrosBusqueda,
      'indicadoresGenerales' => $indicadoresGenerales,
      'indicadoresPortipoGrupo' => $indicadoresPortipoGrupo,
      'tiposDeGrupo' => $tiposDeGrupo,
      'tiposDeViviendas' => $tiposDeViviendas,
      'sedes' => $sedes,
      'camposInformeExcel' => $camposInformeExcel,
      'camposExtras' => $camposExtras
    ]);

  }

  public function filtroPorTipo($grupos, $parametrosBusqueda)
  {
    $tiposGruposIds = TipoGrupo::where("seguimiento_actividad","=",TRUE)->select('id')->pluck('id')->toArray();

    // Filtro por tipo
    if($parametrosBusqueda->tipo=="nuevos")
    {
      // la funcion gruposNuevos carga por defecto los dado_baja FALSE
      $grupos = Grupo::gruposNuevos()->leftJoin('encargados_grupo', 'grupos.id', '=', 'encargados_grupo.grupo_id')
      ->leftJoin('users', 'users.id', '=', 'encargados_grupo.user_id')
      ->select('grupos.*', 'users.primer_nombre', 'users.segundo_nombre', 'users.primer_apellido', 'users.segundo_apellido')
      ->get()
      ->unique('id');

      $parametrosBusqueda->textoBusqueda .= 'Nuevos';
    }
    elseif($parametrosBusqueda->tipo=="sin-georreferencia")
    {
      $grupos = $grupos->whereNull("latitud")->whereNull("longitud")->where('dado_baja', FALSE);

      $parametrosBusqueda->textoBusqueda .= 'Sin geo referencia';
    }
    elseif($parametrosBusqueda->tipo=="sin-actividad")
    {
      $grupos = $grupos->where('dado_baja', FALSE)->whereIn('tipo_grupo_id', $tiposGruposIds)->filter(function ($grupo) {
          $fechaMaximaActividad = Carbon::now()
          ->subDays($grupo->tipoGrupo->tiempo_para_definir_inactivo_grupo)
          ->format('Y-m-d');

          return $grupo->ultimo_reporte_grupo < $fechaMaximaActividad ||
          $grupo->ultimo_reporte_grupo == null;
      });

      $parametrosBusqueda->textoBusqueda .= 'Sin actividad';

    }
    elseif($parametrosBusqueda->tipo=="dados-de-baja")
    {
      $grupos = $grupos->where('dado_baja', TRUE);
      $parametrosBusqueda->textoBusqueda .= 'Dados de baja';
    }
    elseif($parametrosBusqueda->tipo=="grupos-sin-lideres")
    {
      // la funcion gruposSinLider carga por defecto los dado_baja FALSE
      $grupos=Grupo::gruposSinLider()->leftJoin('encargados_grupo', 'grupos.id', '=', 'encargados_grupo.grupo_id')
      ->leftJoin('users', 'users.id', '=', 'encargados_grupo.user_id')
      ->select('grupos.*', 'users.primer_nombre', 'users.segundo_nombre', 'users.primer_apellido', 'users.segundo_apellido')
      ->get()
      ->unique('id');

      $parametrosBusqueda->textoBusqueda .= 'Sin lideres';
    }
    elseif($parametrosBusqueda->tipo=="todos")
    {
      $grupos = $grupos->where('dado_baja', FALSE);

      $parametrosBusqueda->textoBusqueda .= 'Todos';
    }else
    {
      $grupos = $grupos->where('dado_baja', FALSE)->where('tipo_grupo_id', '=', $parametrosBusqueda->tipo);
      $tipoGrupoSeleccionado = TipoGrupo::select('id', 'nombre')->first();
      $parametrosBusqueda->textoBusqueda .= $tipoGrupoSeleccionado->nombre;
    }

    return $grupos;
  }

  public function filtrosBusqueda($grupos, $parametrosBusqueda)
  {
    // Busqueda a partir del filtro de tipos de grupo
    if (isset($parametrosBusqueda->filtroPorTipoDeGrupo)) {
      $tipoGruposFiltro = TipoGrupo::select('nombre_plural')
        ->whereIn('id', $parametrosBusqueda->filtroPorTipoDeGrupo)
        ->get();

      $cantidad = $tipoGruposFiltro->count();
      $contador = 1;
      $parametrosBusqueda->textoBusqueda .= ' "';
      foreach ($tipoGruposFiltro as $tipo) {
        if ($contador == $cantidad) {
          $parametrosBusqueda->textoBusqueda .= $tipo->nombre_plural;
        } else {
          $parametrosBusqueda->textoBusqueda .= $tipo->nombre_plural . ', ';
        }
        $contador++;
      }
      $parametrosBusqueda->textoBusqueda .= '"';

      $grupos = $grupos->whereIn('tipo_grupo_id', $parametrosBusqueda->filtroPorTipoDeGrupo);
      $parametrosBusqueda->bandera = 1;
    }

    // Busqueda a partir del filtro de sedes
    if (isset($parametrosBusqueda->filtroPorSedes)) {
      $sedesFiltro = Sede::select('nombre')
        ->whereIn('id', $parametrosBusqueda->filtroPorSedes)
        ->get();

      $cantidad = $sedesFiltro->count();
      $contador = 1;
      $parametrosBusqueda->textoBusqueda .= ' "';
      foreach ($sedesFiltro as $sede) {
        if ($contador == $cantidad) {
          $parametrosBusqueda->textoBusqueda .= $sede->nombre;
        } else {
          $parametrosBusqueda->textoBusqueda .= $sede->nombre . ', ';
        }
        $contador++;
      }
      $parametrosBusqueda->textoBusqueda .= '"';

      $grupos = $grupos->whereIn('sede_id', $parametrosBusqueda->filtroPorSedes);
      $parametrosBusqueda->bandera = 1;
    }

    // Busqueda a partir del filtro de tipos de vivienda
    if (isset($parametrosBusqueda->filtroPorTiposDeViviendas)) {
      $tiposDeViviendaFiltro = TipoVivienda::select('nombre')
        ->whereIn('id', $parametrosBusqueda->filtroPorTiposDeViviendas)
        ->get();

      $cantidad = $tiposDeViviendaFiltro->count();
      $contador = 1;
      $parametrosBusqueda->textoBusqueda .= ' "';
      foreach ($tiposDeViviendaFiltro as $tipoDeVivienda) {
        if ($contador == $cantidad) {
          $parametrosBusqueda->textoBusqueda .= $tipoDeVivienda->nombre;
        } else {
          $parametrosBusqueda->textoBusqueda .= $tipoDeVivienda->nombre . ', ';
        }
        $contador++;
      }
      $parametrosBusqueda->textoBusqueda .= '"';

      $grupos = $grupos->whereIn('tipo_vivienda_id', $parametrosBusqueda->filtroPorTiposDeViviendas);
      $parametrosBusqueda->bandera = 1;
    }

    // Busqueda por palabra clave
    if ($parametrosBusqueda->buscar != '') {
      $buscar = htmlspecialchars($parametrosBusqueda->buscar);
      $buscar = Helpers::sanearStringConEspacios($buscar);
      $buscar = str_replace(["'"], '', $buscar);
      $buscar_array = explode(' ', $buscar);

      foreach ($buscar_array as $palabra) {
        $grupos = $grupos->filter(function ($grupo) use ($palabra) {
            return false !== stristr(Helpers::sanearStringConEspacios($grupo->nombre), $palabra) ||
            false !== stristr(Helpers::sanearStringConEspacios($grupo->direccion), $palabra) ||
            false !== stristr(Helpers::sanearStringConEspacios($grupo->primer_nombre), $palabra) ||
            false !== stristr(Helpers::sanearStringConEspacios($grupo->segundo_nombre), $palabra) ||
            false !== stristr(Helpers::sanearStringConEspacios($grupo->primer_apellido), $palabra) ||
            false !== stristr(Helpers::sanearStringConEspacios($grupo->segundo_apellido), $palabra) ||
            $grupo->id === $palabra;
        });
      }
      $parametrosBusqueda->textoBusqueda .= '<b>, con busqueda: </b>"' . $buscar . '" ';
      $parametrosBusqueda->bandera = 1;

    }

    // Busqueda a partir de un grupo
    if ($parametrosBusqueda->filtroGrupo != '') {
        $grupoRaiz=Grupo::find($parametrosBusqueda->filtroGrupo);
				$gruposMinisterio=array_merge($grupoRaiz->gruposMinisterio("array"), [$parametrosBusqueda->filtroGrupo]);
				$grupos=$grupos->whereIn('id', $gruposMinisterio);

        $parametrosBusqueda->textoBusqueda .= '<b>, bajo la cobertura del grupo: </b>' . $grupoRaiz->nombre;
        $parametrosBusqueda->bandera = 1;
    }


    return $grupos;

  }

  public function listadoFinalCsv(Request $request)
  {
    $rolActivo = auth()->user()->roles()->wherePivot('activo', true)->first();
    $parametrosBusqueda = json_decode($request->parametrosBusqueda);

    $configuracion = Configuracion::find(1);

    $arrayCamposInfoGrupo = $request->informacionPrincipal ? $request->informacionPrincipal : []; //$arrayCamposInfoGrupo

    $arrayCamposExtra = [];
    if($configuracion->visible_seccion_campos_extra_grupo)
      $arrayCamposExtra = $request->informacionCamposExtras ? $request->informacionCamposExtras : []; // $arrayCamposExtra

    $camposInforme = CampoInformeExcel::whereIn('campos_informe_excel.id', $arrayCamposInfoGrupo)
      ->orderBy('orden', 'asc')
      ->get();

    $nombreArchivo = 'informe_grupos' . Carbon::now()->format('Y-m-d-H-i-s');
    $rutaArchivo = "/$configuracion->ruta_almacenamiento/informes/grupos/$nombreArchivo.csv";

    $archivo = fopen(storage_path('app/public').$rutaArchivo, 'w');
    fputs($archivo, $bom = chr(0xef) . chr(0xbb) . chr(0xbf));

    /* Aquí se crean los encabezados */
    $arrayEncabezadoFila1 = [];
    $arrayEncabezadoFila2 = [];

    foreach ($camposInforme->pluck('nombre_campo_informe')->toArray() as $campo) {

      switch ($campo) {
        case '1':
          array_push($arrayEncabezadoFila1, $configuracion->label_campo_opcional1);
          break;
        case 'dia_planeacion':
          array_push($arrayEncabezadoFila1, $configuracion->label_campo_dia_planeacion_grupo);
          break;
        case 'hora_planeacion':
          array_push($arrayEncabezadoFila1, $configuracion->label_campo_hora_planeacion_grupo);
          break;
        case 'dia':
          array_push($arrayEncabezadoFila1, $configuracion->label_campo_dia_reunion_grupo);
          break;
        case 'hora':
          array_push($arrayEncabezadoFila1, $configuracion->label_campo_hora_reunion_grupo);
          break;
        default:
          array_push($arrayEncabezadoFila1, $campo);
        }

        array_push($arrayEncabezadoFila2, ' ');
    }

    // agrego los campos extra al encabezado
    $camposExtraSeleccionados = CampoExtraGrupo::whereIn('id', $arrayCamposExtra)
      ->orderBy('id', 'asc')
      ->get();

    foreach ($camposExtraSeleccionados as $campo) {
      array_push($arrayEncabezadoFila1, $campo->nombre);

      if ($campo->tipo_de_campo == 4) {
        $cantidad_opciones = $campo->opciones_select;
        $cantidad_opciones = json_decode($cantidad_opciones);

        foreach ($cantidad_opciones as $cantidad) {
          array_push($arrayEncabezadoFila1, '');
          array_push($arrayEncabezadoFila2, $cantidad->nombre);
        }
      }

      array_push($arrayEncabezadoFila2, '');
    }

    //return $arrayEncabezadoFila1;
    fputcsv($archivo, $arrayEncabezadoFila1, ';');
    fputcsv($archivo, $arrayEncabezadoFila2, ';');

    $grupos = [];
    if($rolActivo->hasPermissionTo('grupos.lista_grupos_todos') || $rolActivo->hasPermissionTo('grupos.lista_grupos_solo_ministerio') || $rolActivo->lista_grupos_sede_id!=NULL )
    {
      if($rolActivo->hasPermissionTo('grupos.lista_grupos_todos') || isset(auth()->user()->iglesiaEncargada()->first()->id)){
        $grupos = Grupo::leftJoin('encargados_grupo', 'grupos.id', '=', 'encargados_grupo.grupo_id')
        ->leftJoin('users', 'users.id', '=', 'encargados_grupo.user_id')
        ->select('grupos.*', 'users.primer_nombre', 'users.segundo_nombre', 'users.primer_apellido', 'users.segundo_apellido')
        ->get()
        ->unique('id');
      }

      if($rolActivo->hasPermissionTo('grupos.lista_grupos_solo_ministerio')){
        $grupos = auth()->user()->gruposMinisterio()->leftJoin('encargados_grupo', 'grupos.id', '=', 'encargados_grupo.grupo_id')
        ->leftJoin('users', 'users.id', '=', 'encargados_grupo.user_id')
        ->select('grupos.*', 'users.primer_nombre', 'users.segundo_nombre', 'users.primer_apellido', 'users.segundo_apellido')
        ->get()
        ->unique('id');
      }

    }

    // filtrado por tipo ejemplo: "Todos o nuevos, o sin georeferencia o por los tipos de grupo como abiertos o cerrados, grupo familiar etc..."
    $grupos = $this->filtroPorTipo($grupos, $parametrosBusqueda);

    // filtro por busqueda
    $grupos = $this->filtrosBusqueda($grupos, $parametrosBusqueda);

    foreach ($grupos as $grupo) {
      $fila = [];

      // Nombre
      if ($camposInforme->where('nombre_campo_bd', 'nombre')->count() > 0) {
        array_push($fila, $grupo->nombre ? $grupo->nombre : 'Sin información');
      }

      //fecha_apertura
      if ($camposInforme->where('nombre_campo_bd', 'fecha_apertura')->count() > 0) {
        array_push($fila, $grupo->fecha_apertura ? $grupo->fecha_apertura : 'Sin información');
      }

      //tipo_vivienda
      if ($camposInforme->where('nombre_campo_bd', 'tipo_vivienda')->count() > 0) {
        array_push($fila, $grupo->tipoDeVivienda ? $grupo->tipoDeVivienda->nombre : 'Sin información');
      }

      //direccion
      if ($camposInforme->where('nombre_campo_bd', 'direccion')->count() > 0) {
        array_push($fila, $grupo->direccion ? $grupo->direccion : 'Sin información');
      }

      //telefono
      if ($camposInforme->where('nombre_campo_bd', 'telefono')->count() > 0) {
        array_push($fila, $grupo->telefono ? $grupo->telefono : 'Sin información');
      }

      //dia
      if ($camposInforme->where('nombre_campo_bd', 'dia')->count() > 0) {
        array_push($fila, Helpers::obtenerDiaDeLaSemana($grupo->dia) ? Helpers::obtenerDiaDeLaSemana($grupo->dia) : 'Sin información');
      }

      //hora
      if ($camposInforme->where('nombre_campo_bd', 'hora')->count() > 0) {
        array_push($fila, $grupo->hora ? $grupo->hora : 'Sin información');
      }

      //dia_planeacion
      if ($camposInforme->where('nombre_campo_bd', 'dia_planeacion')->count() > 0) {
        array_push($fila, Helpers::obtenerDiaDeLaSemana($grupo->dia_planeacion) ? Helpers::obtenerDiaDeLaSemana($grupo->dia_planeacion) : 'Sin información');
      }

      //hora_planeación
      if ($camposInforme->where('nombre_campo_bd', 'hora_planeacion')->count() > 0) {
        array_push($fila, $grupo->hora_planeacion ? $grupo->hora_planeacion : 'Sin información');
      }

      //encargados
      if ($camposInforme->where('nombre_campo_bd', 'encargados')->count() > 0) {
        //array_push($fila, $grupo->hora_planeación ? $grupo->hora_planeación : 'Sin información');
        $encargados = $grupo->encargados()->get();
        $texto = '';
        foreach ($encargados as $encargado)
        {
          $texto.= ($encargados->first()->id == $encargado->id) ? $encargado->nombre(3) : ", ".$encargado->nombre(3);
        }

        array_push($fila, $texto ? $texto : 'Sin información');
      }

      //fecha
      if ($camposInforme->where('nombre_campo_bd', 'fecha')->count() > 0) {
        array_push($fila, $grupo->ultimo_reporte_grupo ? Carbon::parse($grupo->ultimo_reporte_grupo)->format('Y-m-d') : 'Sin información');
      }

      //latitud
      if ($camposInforme->where('nombre_campo_bd', 'latitud')->count() > 0) {
        array_push($fila, $grupo->latitud ? 'Está georreferenciado' : 'Sin información');
      }

      //sede_id
      if ($camposInforme->where('nombre_campo_bd', 'sede_id')->count() > 0) {
        array_push($fila, $grupo->sede ? $grupo->sede->nombre : 'Sin información');
      }

      //cantidad_asistentes
      if ($camposInforme->where('nombre_campo_bd', 'cantidad_asistentes')->count() > 0) {
        array_push($fila, $grupo->asistentes()->select('grupos.id')->count());
      }

      //label_campo_opcional1
      if ($camposInforme->where('nombre_campo_bd', 'label_campo_opcional1')->count() > 0) {
        array_push($fila, $grupo->rhema ? $grupo->rhema : 'Sin información');
      }

      //tipo_grupo_id
      if ($camposInforme->where('nombre_campo_bd', 'tipo_grupo_id')->count() > 0) {
        array_push($fila, $grupo->tipoGrupo ? $grupo->tipoGrupo->nombre : 'Sin información');
      }

      //grupo_id
      if ($camposInforme->where('nombre_campo_bd', 'grupo_id')->count() > 0) {

        $encargado = $grupo->encargados()->first();
        $texto = '';
        if($encargado)
        {
          $gruposDelEncargado = $encargado->gruposEncargados()->select('grupos.id','grupos.nombre')->get();

          foreach ($gruposDelEncargado as $grupoDelEncargado)
          {
            $texto.= ($gruposDelEncargado->first()->id == $grupoDelEncargado->id) ? $grupoDelEncargado->nombre : ", ".$grupoDelEncargado->nombre;
          }
        }
        array_push($fila, $texto ? $texto : 'Sin información');
      }

      //fecha_baja
      if ($camposInforme->where('nombre_campo_bd', 'fecha_baja')->count() > 0) {
        $reporte = $grupo->reportesBajaAlta()->where('dado_baja',true)->orderBy('created_at','desc')->first();
        array_push($fila, $reporte ? $reporte->fecha : 'Sin información');
      }

      //motivo_baja
      if ($camposInforme->where('nombre_campo_bd', 'motivo_baja')->count() > 0) {
        $reporte = $grupo->reportesBajaAlta()->where('dado_baja',true)->orderBy('created_at','desc')->first();
        array_push($fila, $reporte ? $reporte->motivo : 'Sin información');
      }

      //fecha_alta
      if ($camposInforme->where('nombre_campo_bd', 'fecha_alta')->count() > 0) {
        $reporte = $grupo->reportesBajaAlta()->where('dado_baja',false)->orderBy('created_at','desc')->first();
        array_push($fila, $reporte ? $reporte->fecha : 'Sin información');
      }

      //motivo_alta
      if ($camposInforme->where('nombre_campo_bd', 'motivo_alta')->count() > 0) {
        $reporte = $grupo->reportesBajaAlta()->where('dado_baja',false)->orderBy('created_at','desc')->first();
        array_push($fila, $reporte ? $reporte->motivo : 'Sin información');
      }


      //AQUI EMPIEZA EL CONSTRUCTOR DE PASOS EXTRA
      foreach ($camposExtraSeleccionados as $campo) {
        $campoExtraGrupo = $grupo
          ->camposExtras()
          ->where('campo_extra_grupo_id', $campo->id)
          ->first();

        if ($campo->tipo_de_campo == 1) {
          array_push($fila, $campoExtraGrupo ? $campoExtraGrupo->pivot->valor : 'Sin información');
        }

        if ($campo->tipo_de_campo == 2) {
          array_push($fila, $campoExtraGrupo ? $campoExtraGrupo->pivot->valor : 'Sin información');
        }

        if ($campo->tipo_de_campo == 3) {
          if ($campoExtraGrupo) {
            $json_opciones_campo = json_decode($campo->opciones_select);

            foreach ($json_opciones_campo as $opcion) {
              if ($opcion->value == $campoExtraGrupo->pivot->valor) {
                array_push($fila, $opcion->nombre);
                break;
              }
            }
          } else {
            array_push($fila, 'Sin información');
          }
        }

        if ($campo->tipo_de_campo == 4) {
          $campo_usuario_opciones_seleccionadas = null;

          if (isset($campoExtraGrupo)) {
            $campo_usuario_opciones_seleccionadas = json_decode($campoExtraGrupo->pivot->valor);
          }

          $campo_opciones_select = json_decode($campo->opciones_select);

          foreach ($campo_opciones_select as $opcion) {
            if (
              isset($campo_usuario_opciones_seleccionadas) &&
              in_array($opcion->value, $campo_usuario_opciones_seleccionadas)
            ) {
              array_push($fila, $opcion->nombre);
            } else {
              array_push($fila, 'Sin información');
            }
          }
          array_push($fila, '');
        }
      }

      fputcsv($archivo, $fila, ';');
    }

    // Genera el archivo
    fclose($archivo);

    return Redirect::back()->with(
      'success',
      'El informe fue generado con éxito, <a href="'.Storage::url($rutaArchivo).'" class=" link-success fw-bold" download="'.$nombreArchivo.'.csv"> descargalo aquí</a>'
    );
  }

  public function nuevo()
  {
    $configuracion = Configuracion::find(1);
    $rolActivo = auth()->user()->roles()->wherePivot('activo', true)->first();
    $tipoGrupos = TipoGrupo::orderBy('orden', 'asc')->get();
    $tiposDeVivienda = TipoVivienda::orderBy('nombre', 'asc')->get();
    $camposExtras = CampoExtraGrupo::where('visible', '=', true)->get();

    return view('contenido.paginas.grupos.nuevo', [
      'tipoGrupos' => $tipoGrupos,
      'rolActivo' => $rolActivo,
      'configuracion' => $configuracion,
      'tiposDeVivienda' => $tiposDeVivienda,
      'camposExtras' => $camposExtras
    ]);
  }

  public function crear(Request $request)
  {
    $rolActivo = auth()->user()->roles()->wherePivot('activo', true)->first();
    $configuracion=Configuracion::find(1);

    // Validación
    $validacion = [];

    //nombre
    if($configuracion->habilitar_nombre_grupo)
    {
      $validarNombre = ['string', 'max:100'];
      $configuracion->nombre_grupo_obligatorio ? array_push($validarNombre, 'required') : '';
      $validacion = array_merge($validacion, ['nombre' => $validarNombre]);
    }

    //  tipo_de_grupo
    if($configuracion->habilitar_tipo_grupo)
    {
      $validarTipoGrupo = [];
      $configuracion->tipo_grupo_obligatorio ? array_push($validarTipoGrupo, 'required') : '';
      $validacion = array_merge($validacion, ['tipo_de_grupo' => $validarTipoGrupo]);
    }

    // fecha
    if($configuracion->habilitar_fecha_creacion_grupo)
    {
      $validarFecha = [];
      $configuracion->fecha_creacion_grupo_obligatorio ? array_push($validarFecha, 'required') : '';
      $validacion = array_merge($validacion, ['fecha' => $validarFecha]);
    }

    // Tiene AMO
    if($configuracion->version == 2)
    $validacion = array_merge($validacion, ['contiene_amo' => []]);


    // telefono
    if($configuracion->habilitar_telefono_grupo)
    {
      $validarTelefono = [];
      $configuracion->telefono_grupo_obligatorio ? array_push($validarTelefono, 'required') : '';
      $validacion = array_merge($validacion, ['teléfono' => $validarTelefono]);
    }

    // tipo de vivienda
    if($configuracion->habilitar_tipo_vivienda_grupo)
    {
      $validarTipoVivienda = [];
      $configuracion->tipo_vivienda_grupo_obligatorio ? array_push($validarTipoVivienda, 'required') : '';
      $validacion = array_merge($validacion, ['tipo_de_vivienda' => $validarTipoVivienda]);
    }

    // direccion
    if ($configuracion->habilitar_direccion_grupo) {
      $validarDireccion = [];
      $configuracion->direccion_grupo_obligatorio ? array_push($validarDireccion, 'required') : '';
      $validacion = array_merge($validacion, ['dirección' => $validarDireccion]);
    }

    // campo_opcional
    if ($configuracion->habilitar_campo_opcional1_grupo) {
      $validarCampoOpcional = [];
      $configuracion->campo_opcional1_obligatorio ? array_push($validarCampoOpcional, 'required') : '';
      $validacion = array_merge($validacion, ['adiccional' => $validarCampoOpcional]);
    }

    // dia de reunion
    if ($configuracion->habilitar_dia_reunion_grupo) {
      $validardiaReunion = [];
      $configuracion->dia_reunion_grupo_obligatorio ? array_push($validardiaReunion, 'required') : '';
      $validacion = array_merge($validacion, ['día_de_reunión' => $validardiaReunion]);
    }
    // hora de reunion
    if ($configuracion->habilitar_hora_reunion_grupo) {
      $validarHoraReunion = [];
      $configuracion->habilitar_hora_reunion_grupo ? array_push($validarHoraReunion, 'required') : '';
      $validacion = array_merge($validacion, ['hora_de_reunión' => $validarHoraReunion]);
    }

    /// seccion comprobacion campos extras
    if ($configuracion->visible_seccion_campos_extra_grupo == TRUE && $rolActivo->hasPermissionTo('grupos.visible_seccion_campos_extra_grupo'))
    {
      $camposExtras = CampoExtraGrupo::where('visible','=', true)->get();

      foreach ($camposExtras as $campoExtra) {
        $validarCampoExtra = [];
        $campoExtra->required ? array_push($validarCampoExtra, 'required') : '';
        $validacion = array_merge($validacion, [$campoExtra->class_id => $validarCampoExtra]);
      }
    }

    // Validacion de datos
    $request->validate($validacion);

		$grupo = new Grupo;
    $grupo->nombre =  $request->nombre;
    $grupo->telefono = $request->teléfono;
    $grupo->direccion = $request->dirección;
    $grupo->barrio_id = $request->barrio_id ? $request->barrio_id : null ;
    $grupo->barrio_auxiliar = $request->barrio_auxiliar;
    $grupo->tipo_vivienda_id = $request->tipo_de_vivienda;
    $grupo->tipo_grupo_id = $request->tipo_de_grupo;
    $grupo->rhema = $request->adiccional;
    $grupo->dia = $request->día_de_reunión;
    $grupo->hora = $request->hora_de_reunión;
    $grupo->contiene_amo = $request->amo ? TRUE : FALSE;
    $grupo->fecha_apertura = $request->fecha;
		$grupo->inactivo = 0;
		$grupo->dado_baja = 0;
    $grupo->usuario_creacion_id = auth()->user()->id;
    $grupo->rol_de_creacion_id = $rolActivo->id;
		$grupo->save();

		$grupo->indice_grafico_ministerial=$grupo->id;
		$grupo->save();
    $grupo->asignarSede();

		/// esta sección es para el guardado de los campos extra
		if($configuracion->visible_seccion_campos_extra_grupo == TRUE)
		{
				$camposExtras= CampoExtraGrupo::where('visible','=', true)->get();

				foreach($camposExtras as $campo)
				{
          if($campo->tipo_de_campo != 4)
            $grupo->camposExtras()->attach($campo->id, array('valor'=> ucwords(mb_strtolower($request[$campo->class_id]))));
          else
            $grupo->camposExtras()->attach($campo->id, array('valor'=>(json_encode($request[$campo->class_id]))));
				}
		}

		return back()->with('success', "El grupo <b>".$grupo->nombre."</b> fue creado con éxito.");
		/*return Redirect::to('/grupos/anadir-lideres/'.$grupo->id)->with(
			array(
				'status' => 'ok_new_grupo',
				'id_nuevo' => $grupo->id,
				'nombre_nuevo' => $grupo->nombre,
				)
		);*/
  }

  public function perfil(Grupo $grupo)
  {
    $configuracion = Configuracion::find(1);
    $rolActivo = auth()->user()->roles()->wherePivot('activo', true)->first();

    $encargados = $grupo->encargados()
    ->select('users.id','primer_nombre','segundo_nombre','primer_apellido','segundo_apellido','foto','tipo_usuario_id')
    ->with('tipoUsuario')
    ->get();

    $servidores = ServidorGrupo::where("grupo_id", "=", $grupo->id)
    ->leftJoin('users','user_id', '=', 'users.id')
    ->select('servidores_grupo.*','users.id as idUser','primer_nombre','segundo_nombre','primer_apellido','segundo_apellido','foto','tipo_usuario_id')
    ->get();

    $servidores = User::where('servidores_grupo.grupo_id',$grupo->id)
    ->leftJoin('servidores_grupo','users.id', '=', 'user_id')
    ->select('servidores_grupo.id as servidorId','users.id','primer_nombre','segundo_nombre','primer_apellido','segundo_apellido','foto','tipo_usuario_id')
    ->get();

    $servidores->map(function ($servidor) use ($grupo) {
      $servicios = $servidor->serviciosPrestadosEnGrupos($grupo->id)->pluck('nombre')->toArray();
      $servidor->servicios  = $servicios;
    });

    $dataUltimosReportes = [];
    $serieUltimosReportes= [];
    $ultimos10Reportes = $grupo->reportes()->orderBy('fecha','desc')->take(10)->select('cantidad_asistencias', 'fecha', 'id')->get();

    $meses = Helpers::meses('cortos');

    foreach($ultimos10Reportes as $reporte)
    {
      $dataUltimosReportes[] = $reporte->cantidad_asistencias;
      $serieUltimosReportes[] = Carbon::parse($reporte->fecha)->day.'-'.$meses[Carbon::parse($reporte->fecha)->month-1];
    }

    $dataUltimosMeses = [];
    $serieUltimosMeses= [];
    $mes = Carbon::now()->firstOfMonth()->month;
    $mesIni = Carbon::now()->firstOfMonth()->subMonth(5)->month;

    for ($i=5; $i >= 0; $i--) {
      $fechaIni =  Carbon::now()->firstOfMonth()->subMonth($i)->format('Y-m-d');
      $fechaFin =  Carbon::now()->lastOfMonth()->subMonth($i)->format('Y-m-d');
      $serieUltimosMeses[] = $meses[Carbon::now()->firstOfMonth()->subMonth($i)->month-1];
      $promedioMes =  $grupo->reportes()->where('fecha','>=',$fechaIni)->where('fecha','<=',$fechaFin)->avg('cantidad_asistencias');
      $dataUltimosMeses[] = $promedioMes;
    }


    $camposExtras = CampoExtraGrupo::where('visible', '=', true)->get();

    $camposExtras->map(function ($campoExtra) use ($grupo) {
      $grupoCampoExtra = $grupo->camposExtras()->where('campos_extra_grupo.id',$campoExtra->id)->first();

      if ($campoExtra->tipo_de_campo == 4){
        $valor = [];

        if($grupoCampoExtra)
        {
          foreach (json_decode($campoExtra->opciones_select) as $opcion)
          {
            if(in_array($opcion->value, json_decode($grupoCampoExtra->pivot->valor)))
            $valor[] = $opcion->nombre;
          }
        }

        $campoExtra->valor = count($valor)>0 ? implode(",",$valor) : '' ;
      }elseif($campoExtra->tipo_de_campo == 3)
      {
        $valor = '';
        if($grupoCampoExtra){
          foreach (json_decode($campoExtra->opciones_select) as $opcion)
          {
            if($opcion->value == $grupoCampoExtra->pivot->valor)
            $valor = $opcion->nombre;
          }
        }
        $campoExtra->valor = $valor;

      }else{
        $campoExtra->valor = $grupoCampoExtra ? $grupoCampoExtra->pivot->valor : '' ;
      }
    });


    return view('contenido.paginas.grupos.perfil', [
      'configuracion' => $configuracion,
      'rolActivo' => $rolActivo,
      'grupo' => $grupo,
      'encargados' => $encargados,
      'servidores' => $servidores,
      'dataUltimosReportes' => $dataUltimosReportes,
      'serieUltimosReportes' => $serieUltimosReportes,
      'serieUltimosMeses' => $serieUltimosMeses,
      'dataUltimosMeses' => $dataUltimosMeses,
      'camposExtras' => $camposExtras
    ]);
  }

  public function modificar(Grupo $grupo)
  {
		if(!isset($grupo)) return Redirect::to('pagina-no-encontrada');;

    $configuracion = Configuracion::find(1);
    $rolActivo = auth()->user()->roles()->wherePivot('activo', true)->first();
    $tipoGrupos = TipoGrupo::orderBy('orden', 'asc')->get();
    $tiposDeVivienda = TipoVivienda::orderBy('nombre', 'asc')->get();
    $camposExtras = CampoExtraGrupo::where('visible', '=', true)->get();

    $camposExtras->map(function ($campoExtra) use ($grupo) {
      $grupoCampoExtra = $grupo->camposExtras()->where('campos_extra_grupo.id',$campoExtra->id)->first();
      $campoExtra->valor = $grupoCampoExtra ? $grupoCampoExtra->pivot->valor : '' ;
    });

    return view('contenido.paginas.grupos.modificar', [
      'tipoGrupos' => $tipoGrupos,
      'rolActivo' => $rolActivo,
      'configuracion' => $configuracion,
      'tiposDeVivienda' => $tiposDeVivienda,
      'camposExtras' => $camposExtras,
      'grupo' => $grupo,
    ]);
  }

  public function editar(Request $request, Grupo $grupo)
  {
    $rolActivo = auth()->user()->roles()->wherePivot('activo', true)->first();
    $configuracion=Configuracion::find(1);

    // Validación
    $validacion = [];

    //nombre
    if($configuracion->habilitar_nombre_grupo)
    {
      $validarNombre = ['string', 'max:100'];
      $configuracion->nombre_grupo_obligatorio ? array_push($validarNombre, 'required') : '';
      $validacion = array_merge($validacion, ['nombre' => $validarNombre]);
    }

    //  tipo_de_grupo
    if($configuracion->habilitar_tipo_grupo)
    {
      $validarTipoGrupo = [];
      $configuracion->tipo_grupo_obligatorio ? array_push($validarTipoGrupo, 'required') : '';
      $validacion = array_merge($validacion, ['tipo_de_grupo' => $validarTipoGrupo]);
    }

    // fecha
    if($configuracion->habilitar_fecha_creacion_grupo)
    {
      $validarFecha = [];
      $configuracion->fecha_creacion_grupo_obligatorio ? array_push($validarFecha, 'required') : '';
      $validacion = array_merge($validacion, ['fecha' => $validarFecha]);
    }

    // Tiene AMO
    if($configuracion->version == 2)
    $validacion = array_merge($validacion, ['contiene_amo' => []]);


    // telefono
    if($configuracion->habilitar_telefono_grupo)
    {
      $validarTelefono = [];
      $configuracion->telefono_grupo_obligatorio ? array_push($validarTelefono, 'required') : '';
      $validacion = array_merge($validacion, ['teléfono' => $validarTelefono]);
    }

    // tipo de vivienda
    if($configuracion->habilitar_tipo_vivienda_grupo)
    {
      $validarTipoVivienda = [];
      $configuracion->tipo_vivienda_grupo_obligatorio ? array_push($validarTipoVivienda, 'required') : '';
      $validacion = array_merge($validacion, ['tipo_de_vivienda' => $validarTipoVivienda]);
    }

    // direccion
    if ($configuracion->habilitar_direccion_grupo) {
      $validarDireccion = [];
      $configuracion->direccion_grupo_obligatorio ? array_push($validarDireccion, 'required') : '';
      $validacion = array_merge($validacion, ['dirección' => $validarDireccion]);
    }

    // campo_opcional
    if ($configuracion->habilitar_campo_opcional1_grupo) {
      $validarCampoOpcional = [];
      $configuracion->campo_opcional1_obligatorio ? array_push($validarCampoOpcional, 'required') : '';
      $validacion = array_merge($validacion, ['adiccional' => $validarCampoOpcional]);
    }

    // dia de reunion
    if ($configuracion->habilitar_dia_reunion_grupo) {
      $validardiaReunion = [];
      $configuracion->dia_reunion_grupo_obligatorio ? array_push($validardiaReunion, 'required') : '';
      $validacion = array_merge($validacion, ['día_de_reunión' => $validardiaReunion]);
    }
    // hora de reunion
    if ($configuracion->habilitar_hora_reunion_grupo) {
      $validarHoraReunion = [];
      $configuracion->habilitar_hora_reunion_grupo ? array_push($validarHoraReunion, 'required') : '';
      $validacion = array_merge($validacion, ['hora_de_reunión' => $validarHoraReunion]);
    }

    /// seccion comprobacion campos extras
    if ($configuracion->visible_seccion_campos_extra_grupo == TRUE && $rolActivo->hasPermissionTo('grupos.visible_seccion_campos_extra_grupo'))
    {
      $camposExtras = CampoExtraGrupo::where('visible','=', true)->get();

      foreach ($camposExtras as $campoExtra) {
        $validarCampoExtra = [];
        $campoExtra->required ? array_push($validarCampoExtra, 'required') : '';
        $validacion = array_merge($validacion, [$campoExtra->class_id => $validarCampoExtra]);
      }
    }

    // Validacion de datos
    $request->validate($validacion);

    $grupo->nombre =  $request->nombre;
    $grupo->telefono = $request->teléfono;
    $grupo->direccion = $request->dirección;
    $grupo->barrio_id = $request->barrio_id ? $request->barrio_id : null ;
    $grupo->barrio_auxiliar = $request->barrio_auxiliar;
    $grupo->tipo_vivienda_id = $request->tipo_de_vivienda;
    $grupo->tipo_grupo_id = $request->tipo_de_grupo;
    $grupo->rhema = $request->adiccional;
    $grupo->dia = $request->día_de_reunión;
    $grupo->hora = $request->hora_de_reunión;
    $grupo->contiene_amo = $request->amo ? TRUE : FALSE;
    $grupo->fecha_apertura = $request->fecha;
		$grupo->inactivo = 0;
		$grupo->dado_baja = 0;
    $grupo->usuario_creacion_id = auth()->user()->id;
    $grupo->rol_de_creacion_id = $rolActivo->id;
		$grupo->save();

		$grupo->indice_grafico_ministerial=$grupo->id;
		$grupo->save();
    $grupo->asignarSede();

		/// esta sección es para el guardado de los campos extra
		if($configuracion->visible_seccion_campos_extra_grupo == TRUE)
		{
				$camposExtras= CampoExtraGrupo::where('visible','=', true)->get();

				foreach($camposExtras as $campo)
				{
          $grupoCampoExtra = $grupo
          ->camposExtras()
          ->where('campo_extra_grupo_id', '=', $campo->id)
          ->first();

          if($grupoCampoExtra)
          {
            if($campo->tipo_de_campo != 4)
              $grupoCampoExtra->pivot->valor = ucwords(mb_strtolower($request[$campo->class_id]));
            else
              $grupoCampoExtra->pivot->valor = ucwords(mb_strtolower(json_encode($request[$campo->class_id])));

            $grupoCampoExtra->pivot->save();

          }else{
            if($campo->tipo_de_campo != 4)
              $grupo->camposExtras()->attach($campo->id, array('valor'=> ucwords(mb_strtolower($request[$campo->class_id]))));
            else
              $grupo->camposExtras()->attach($campo->id, array('valor'=>(json_encode($request[$campo->class_id]))));
          }
				}
		}

		return back()->with('success', "El grupo <b>".$grupo->nombre."</b> se actualizó con éxito.");
		/*return Redirect::to('/grupos/anadir-lideres/'.$grupo->id)->with(
			array(
				'status' => 'ok_new_grupo',
				'id_nuevo' => $grupo->id,
				'nombre_nuevo' => $grupo->nombre,
				)
		);*/
  }

  public function gestionarEncargados(Grupo $grupo)
  {
    $configuracion= Configuracion::find(1);
    $rolActivo = auth()->user()->roles()->wherePivot('activo', true)->first();
   /* return $rolActivo->privilegiosTiposGrupo()->wherePivot("tipo_grupo_id", "=", $grupo->tipoGrupo->id)
    ->first();*/


    $idsEncargadosSeleccionados = $grupo->encargados()->select('users.id')
        ->pluck('users.id')
        ->toArray();


    $queUsuariosCargarEncargados = $rolActivo->hasPermissionTo('personas.ajax_obtiene_asistentes_solo_ministerio')
    ? 'discipulos'
    : 'todos';

    // Si es TRUE carga son los asistentes al grupo
    $queUsuariosCargarServidores = $grupo->tipoGrupo->servidores_solo_discipulos
    ? 'grupo'
    : 'todos';

    $idsServidoresSeleccionados = ServidorGrupo::where('grupo_id', '=', $grupo->id)
    ->pluck('user_id')
    ->toArray();

    /*return $rolActivo->privilegiosTiposGrupo()
    ->get();*/

    return view('contenido.paginas.grupos.gestionar-encargados', [
      'grupo' => $grupo,
      'configuracion' => $configuracion,
      'rolActivo' => $rolActivo,
      'queUsuariosCargarEncargados' => $queUsuariosCargarEncargados,
      'queUsuariosCargarServidores' => $queUsuariosCargarServidores,
      'idsServidoresSeleccionados' => $idsServidoresSeleccionados,
      'idsEncargadosSeleccionados' => $idsEncargadosSeleccionados,
    ]);
  }

  public function gestionarIntegrantes(Grupo $grupo)
  {
    $configuracion= Configuracion::find(1);
    $rolActivo = auth()->user()->roles()->wherePivot('activo', true)->first();

    $queUsuariosCargar = $rolActivo->hasPermissionTo('personas.ajax_obtiene_asistentes_solo_ministerio')
    ? 'discipulos'
    : 'todos';

    $idsIntegrantesSeleccionados = $grupo->asistentes()->select('users.id')
    ->pluck('users.id')
    ->toArray();

     return view('contenido.paginas.grupos.gestionar-integrantes', [
      'grupo' => $grupo,
      'queUsuariosCargar' => $queUsuariosCargar,
      'idsIntegrantesSeleccionados' => $idsIntegrantesSeleccionados,
      'configuracion' => $configuracion,
      'rolActivo' => $rolActivo
    ]);
  }

  public function georreferencia(Grupo $grupo)
  {
    $iglesia = Iglesia::find(1);
    if($iglesia->latitud && $iglesia->longitud)
    {
      $longitudInicial = $iglesia->longitud;
      $latitudInicial = $iglesia->latitud;
    }else{
      $busqueda = '';
      $busqueda .= $iglesia->municipio ? $iglesia->municipio->nombre . ' ' : '';
      $busqueda .= $iglesia->pais ? $iglesia->pais->nombre . ' ' : '';

      if ($busqueda == '') {
        $busqueda .= $usuario->pais ? $usuario->pais->nombre . '' : '';
      }
      $ubicacionInicial = Http::get("https://nominatim.openstreetmap.org/search?q=$busqueda$&format=json");
      $ubicacionInicial  = collect(json_decode($ubicacionInicial))->first();
      $longitudInicial = ($ubicacionInicial && $ubicacionInicial->lon) ? $ubicacionInicial->lon : -72.9088133;
      $latitudInicial = ($ubicacionInicial && $ubicacionInicial->lat) ? $ubicacionInicial->lat : 4.099917;

      if($iglesia->latitud && $iglesia->longitud)
      {
        $iglesia->latitud = $longitudInicial;
        $iglesia->longitud = $longitudInicial;
        $iglesia->save();
      }
    }

    $configuracion = Configuracion::find(1);
    if (!isset($grupo)) {
      return Redirect::to('pagina-no-encontrada');
    }

    $rolActivo = auth()->user()->roles()->wherePivot('activo', true)->first();

    return view('contenido.paginas.grupos.georreferencia', [
      'rolActivo' => $rolActivo,
      'configuracion' => $configuracion,
      //'ubicacionInicial' => $ubicacionInicial,
      'longitudInicial' => $longitudInicial,
      'latitudInicial' => $latitudInicial,
      'grupo' => $grupo
    ]);
  }

  public function graficoMinisterial(Grupo $grupo)
  {
    return "Esta en contrucción";
  }

  public function excluir(Grupo $grupo)
  {
    $usuarioId = auth()->user()->id;
    $cantidadGrupoExcluido = GrupoExcluido::where("user_id",$usuarioId)->where("grupo_id",$grupo->id)->count();

    if( $cantidadGrupoExcluido > 0 ){
      return Redirect::back()->with(
        'danger',
        'Esta exclusión de este grupo ya se había sido creada anteriormente.'
      );
    }
    else{

      $exclusion = new GrupoExcluido;
      $exclusion->grupo_id = $grupo->id;
      $exclusion->user_id = $usuarioId;
      $exclusion->save();

      return Redirect::back()->with(
        'success',
        'La exclusión del grupo "'.$grupo->nombre.'" se creo con éxito.'
      );
    }
  }

  public function verExclusiones()
  {

    $rolActivo = auth()->user()->roles()->wherePivot('activo', true)->first();

    $queUsuariosCargar = $rolActivo->hasPermissionTo('personas.ajax_obtiene_asistentes_solo_ministerio')
    ? 'discipulos'
    : 'todos';

    return view('contenido.paginas.grupos.exclusiones', [
      'queUsuariosCargar' => $queUsuariosCargar
    ]);
  }

  public function crearExclusion ( Request $request )
  {

    $request->validate([
      'grupo' => 'required',
      'usuario' => 'required'
    ], [
      'required' => '¡ups! no fue posible crear la exclusión debido a que no seleccionaste un :attribute.'
    ]);

    $cantidadGrupoExcluido = GrupoExcluido::where("user_id",$request->usuario)->where("grupo_id",$request->grupo)->count();

    if( $cantidadGrupoExcluido > 0 ){
      return Redirect::back()->with(
        'danger',
        '¡ups! no fue posible esta acción, esta exclusión ya había sido creada anteriormente.'
      );
    }else{

      $grupo = Grupo::find($request->grupo);
      $exclusion = new GrupoExcluido;
      $exclusion->grupo_id = $request->grupo;
      $exclusion->user_id = $request->usuario;
      $exclusion->save();

      return Redirect::back()->with(
        'success',
        'La exclusión del grupo "'.$grupo->nombre.'" se creo con éxito.'
      );
    }

    return $request;
  }

  public function mapaDeGrupos( Request $request )
  {

    $configuracion = Configuracion::find(1);
    $iglesia = Iglesia::find(1);
    $rolActivo = auth()->user()->roles()->wherePivot('activo', true)->first();
    $tiposDeViviendas =  TipoVivienda::orderBy('nombre', 'asc')->get();
    $tiposDeGrupo = TipoGrupo::orderBy('nombre', 'asc')->get();
    $sedes = Sede::get();
    $grupos = [];

    $parametrosBusqueda = [];
    $parametrosBusqueda['buscar'] = $request->buscar;
    $parametrosBusqueda['filtroGrupo'] = $request->filtroGrupo;
    $parametrosBusqueda['filtroPorTipoDeGrupo'] = $request->filtroPorTipoDeGrupo;
    $parametrosBusqueda['filtroPorSedes'] = $request->filtroPorSedes;
    $parametrosBusqueda['filtroPorTiposDeViviendas'] = $request->filtroPorTiposDeViviendas;
    $parametrosBusqueda['bandera'] = '';
    $parametrosBusqueda['textoBusqueda'] = '';
    $parametrosBusqueda = (object) $parametrosBusqueda;

    if($rolActivo->hasPermissionTo('grupos.lista_grupos_todos') || $rolActivo->hasPermissionTo('grupos.lista_grupos_solo_ministerio') || $rolActivo->lista_grupos_sede_id!=NULL )
    {
      if($rolActivo->hasPermissionTo('grupos.lista_grupos_todos') || isset(auth()->user()->iglesiaEncargada()->first()->id)){
        $grupos = Grupo::whereNotNull('latitud')
        ->whereNotNull('longitud')
        ->leftJoin('encargados_grupo', 'grupos.id', '=', 'encargados_grupo.grupo_id')
        ->leftJoin('users', 'users.id', '=', 'encargados_grupo.user_id')
        ->select('grupos.*', 'users.primer_nombre', 'users.segundo_nombre', 'users.primer_apellido', 'users.segundo_apellido')
        ->get()
        ->unique('id');
      }

      if($rolActivo->hasPermissionTo('grupos.lista_grupos_solo_ministerio')){
        $grupos = auth()->user()->gruposMinisterio()->whereNotNull('grupos.latitud')
        ->whereNotNull('grupos.longitud')->leftJoin('encargados_grupo', 'grupos.id', '=', 'encargados_grupo.grupo_id')
        ->leftJoin('users', 'users.id', '=', 'encargados_grupo.user_id')
        ->select('grupos.*', 'users.primer_nombre', 'users.segundo_nombre', 'users.primer_apellido', 'users.segundo_apellido')
        ->get()
        ->unique('id');
      }

    }

    // filtro por busqueda
    $grupos = $this->filtrosBusqueda($grupos, $parametrosBusqueda);

    if ($grupos->count() > 0) {
      $grupos = $grupos->toQuery()->orderBy('id','desc')->get();

      $grupoLast = $grupos->last();

      $longitudInicial = $grupoLast->longitud ? $grupoLast->longitud : -72.9088133;
      $latitudInicial =  $grupoLast->latitud ?  $grupoLast->latitud : 4.099917;
    } else {
      $grupos = Grupo::whereRaw('1=2')->get();
      $longitudInicial = $iglesia->longitud ? $iglesia->longitud : -72.9088133;
      $latitudInicial = $iglesia->latitud ? $iglesia->latitud : 4.099917;
    }

    return view('contenido.paginas.grupos.mapa-de-grupos', [
      'configuracion' => $configuracion,
      'rolActivo' => $rolActivo,
      'parametrosBusqueda' => $parametrosBusqueda,
      'grupos' => $grupos,
      'tiposDeGrupo' => $tiposDeGrupo,
      'sedes' => $sedes,
      'tiposDeViviendas' => $tiposDeViviendas,
      'longitudInicial' => $longitudInicial,
      'latitudInicial' => $latitudInicial
    ]);
  }




  public function graficoDelMinisterio($id_nodo="U-logueado", $maximos_niveles=2)
	{
		$configuracion=Configuracion::find(1);
		if($maximos_niveles!=20){
		    $maximos_niveles=$configuracion->maximos_niveles_grafico_ministerio;
		}
		$rolActivo = auth()->user()->roles()->wherePivot('activo', true)->first();

		$identificadores = explode("-", $id_nodo);
		$tipo_nodo = $identificadores[0];
    $tipoDeNodo = $identificadores[0];
		$id= $identificadores[1];
		$usuario_seleccionado="";
    $grupo_seleccionado="";

    $array_ids_usuarios_no_dibujados= array();

	  $mensaje="";
    $contador=0;
    $tamano_nodo_grupo=0.7;
    $tamano_nodo_general=0.7;
    $factor_vision=5;
    $inicio_fila=$factor_vision*-1;
    $distancia_nodos_usuario=0;
    $distancia_nodos_grupo=0;
    $nodos=[];
    $aristas=[];
    $x_usuario=0;
    $x_grupo=0;
    $x=0;
    $y=0;
    $y_grupo=750000;
    $array_ids_usuarios= array();
    $array_ids_grupos_dibujados= array();
    $array_ids_usuarios_dibujados= array();
    $array_aristas_usuario_grupo_dibujadas= array();
    $array_aristas_grupo_usuario_dibujadas= array();

    $id_nulo=1;
    $cantidad_usuarios_grupo=0;

    $nombre_grupo="";
    $tipo_dibujo_grupo="circle";

    if($tipo_nodo=="U")
    {

      if($rolActivo->hasPermissionTo('grupos.grafico_ministerio_todos') || isset(auth()->user()->iglesiaEncargada()->first()->id))
      {
        $iglesia= Iglesia::find(1);
        $array_ids_usuarios=$iglesia->pastoresEncargados()->select('users.id')->pluck('users.id')->toArray();
        $mensaje="Ministerio General";
        $tipoDeNodo = $tipoDeNodo."-principal";
      }

      if($rolActivo->hasPermissionTo('grupos.grafico_ministerio_solo_ministerio'))
      {
        $tipoDeNodo = $tipoDeNodo."-encargado";
        $usuario = auth()->user();
        $usuario_seleccionado=$usuario;
        array_push($array_ids_usuarios, $usuario->id);
        $mensaje="Ministerio del ".$usuario->tipoUsuario->nombre." <a href='/usuario/".$usuario->id."/perfil' target='_blank' >".$usuario->nombre(3)."</a>";
      }

    }else if($tipo_nodo=="A"){
      $tipoDeNodo = $tipoDeNodo."-encargado";
      array_push($array_ids_usuarios, $id);
      $usuario= User::find($id);
      $mensaje="Ministerio del ".$usuario->tipoUsuario->nombre." <a href='/usuario/".$usuario->id."/perfil' target='_blank' >".$usuario->nombre(3)."</a>";

    }else if($tipo_nodo=="G"){
      $tipoDeNodo = $tipoDeNodo."-grupo";
      $grupo=Grupo::find($id);
      $array_ids_usuarios=$grupo->encargados()->select('users.id')->pluck('users.id')->toArray();
      $mensaje="Ministerio a partir del grupo <a href='/grupo/".$grupo->id."/perfil' target='_blank' >".$grupo->codigo." ".$grupo->nombre."</a>";
    }

    if($maximos_niveles!=20)
    {
      $mensaje=$mensaje."<br>
      Actualmente se están visualizando solamente algunos niveles. Si deseas ver el árbol con el ministerio completo, ingresa <a style='color:#3c8dbc' href='/grupo/grafico-del-ministerio/U-logueado/20' >aquí</a>";
    }

    if($tipo_nodo=="A"){
      $usuario_seleccionado=$usuario;
      $mensaje=$mensaje."<br> El índice actual de <b>".$usuario->nombre(3)."</b> que permite establecer su ubicación en el gráfico es: </a> <b>".$usuario->indice_grafico_ministerial."</b>. Si deseas modificarlo, da click <a class='mostrar-div-indice-asistente' > aquí </a>";
    }else if($tipo_nodo=="G"){
      $grupo_seleccionado=$grupo;
      $mensaje=$mensaje."<br> El índice actual del grupo <b>".$grupo->nombre."</b> que permite establecer su ubicación en el gráfico es: </a> <b>".$grupo->indice_grafico_ministerial."</b>. Si deseas modificarlo, da click <a class='mostrar-div-indice-grupo' > aquí </a>";
    }

    $contador_maximos_niveles=0;

    while(count($array_ids_usuarios)>0 && $contador_maximos_niveles<$maximos_niveles)
    {
      $contador_maximos_niveles=$contador_maximos_niveles+1;

      $contador=$contador+1;
      $x_grupo=$inicio_fila;
      $usuarios=User::orderBy('users.indice_grafico_ministerial', 'asc')->whereIn('users.id', $array_ids_usuarios)->get();
      $array_ids_usuarios= array();

      if($contador==1)
      {
        $distancia_nodos_grupo=1175000;
        $distancia_nodos_usuario=235000;
        $inicio_fila=$inicio_fila-470000;
        $x_usuario=$inicio_fila;
      }else if($contador==2){
        $distancia_nodos_grupo=235000;
        $distancia_nodos_usuario=46900;
        $inicio_fila=$inicio_fila-93800;
        $x_usuario=$inicio_fila;
        $tamano_nodo_grupo=$tamano_nodo_grupo*0.5;
      }else if($contador==3){
        $distancia_nodos_grupo=46900;
        $distancia_nodos_usuario=9400;
        $inicio_fila=$inicio_fila-18900;
        $x_usuario=$inicio_fila;
        $tamano_nodo_grupo=$tamano_nodo_grupo*0.5;
      }else if($contador==4){
        $distancia_nodos_grupo=9400;
        $distancia_nodos_usuario=1900;
        $inicio_fila=$inicio_fila-3800;
        $x_usuario=$inicio_fila;
        $tamano_nodo_grupo=$tamano_nodo_grupo*0.5;
      }else if($contador==5){
        $distancia_nodos_grupo=1900;
        $distancia_nodos_usuario=380; //
        $inicio_fila=$inicio_fila-770;
        $x_usuario=$inicio_fila;
        $tamano_nodo_grupo=$tamano_nodo_grupo*0.5;
      }else if($contador==6){
        $distancia_nodos_grupo=380;
        $distancia_nodos_usuario=75;
        $inicio_fila=$inicio_fila-150;
        $x_usuario=$inicio_fila;
        $tamano_nodo_grupo=$tamano_nodo_grupo*0.5;
      }else if($contador==7){
        $distancia_nodos_grupo=75;
        $distancia_nodos_usuario=15;
        $inicio_fila=$inicio_fila-30;
        $x_usuario=$inicio_fila;
        $tamano_nodo_grupo=$tamano_nodo_grupo*0.5;
      }

      foreach($usuarios as $usuario)
      {
        if(IntegranteGrupo::where("user_id",$usuario->id)->count()==0 || $tipo_nodo!="modificado")
        {
          if(!in_array($usuario->id, $array_ids_usuarios_dibujados))
          {
            /*$nodos.='{
              id: "A-'.$usuario->id.'",
              label: '.$usuario->tipoUsuario->nombre.': '.$usuario->nombre(3).',
              type: "circle",
              x: '.$x.',
              y: '.$y.',
              size: '.$tamano_nodo_general.',
              color: "'.$usuario->tipoUsuario->color.'",
              borderColor: "'.$usuario->tipoUsuario->color.'",
              image: {
                url: "'.$configuracion->url_img.'/fotos'.'/'.$usuario->foto.'",
                scale: 1.3,
                clip: 0.85
              }
            },';*/

            $urlFoto = $configuracion->version == 1
            ? Storage::url($configuracion->ruta_almacenamiento.'/img/foto-usuario/'.$usuario->foto)
            : Storage::url($configuracion->ruta_almacenamiento.'/img/foto-usuario/'.$usuario->foto);

            $item = new stdClass();
            $item->id = 'A-'.$usuario->id;
            $item->label = $usuario->tipoUsuario->nombre.': '.$usuario->nombre(3);
            $item->type = "circle";
            $item->x = $x;
            $item->y = $y;
            $item->size = $tamano_nodo_general;
            $item->color = $usuario->tipoUsuario->color;
            $item->boderColor = $usuario->tipoUsuario->color;

            $image = new stdClass();
            $image->url = $urlFoto;
            $image->scale = 1.3;
            $image->clip = 0.85;

            $item->image = $image;
            $nodos[] = $item;
          }else{
              array_push($array_ids_usuarios_no_dibujados, $usuario->id);
          }

          array_push($array_ids_usuarios_dibujados, $usuario->id);
          $x_grupo=$x;
        }

        $x=$x+500000;

        $grupos_excluidos=array();
        if(isset(auth()->user()->id))
        {
          $usuario_logueado=auth()->user();
          $grupos_excluidos=GrupoExcluido::where("user_id",$usuario_logueado->id)->select('grupo_id')->pluck('grupo_id')->toArray();
        }

        $grupos=$usuario->gruposEncargados()->where('grupos.dado_baja', '=', 0)->whereNotIn('grupos.id', $grupos_excluidos)->orderBy('grupos.indice_grafico_ministerial', 'asc')->get();

        foreach($grupos as $grupo)
        {
          if(!in_array($grupo->id, $array_ids_grupos_dibujados))
          {
            if(Sede::where('grupo_id', '=', $grupo->id)->count()>0){
              $nombre_grupo='Sede: '.Sede::where('grupo_id', '=', $grupo->id)->first()->nombre.' - '.$grupo->tipoGrupo->nombre.': '.$grupo->nombre;
              $tipo_dibujo_grupo="equilateral";
            }else{
              $nombre_grupo=$grupo->tipoGrupo->nombre.': '.$grupo->nombre;
              $tipo_dibujo_grupo="circle";
            }

           /* $nodos.='{
              id: "G-'.$grupo->id.'",
              label: "'.$nombre_grupo.'",
              type: "'.$tipo_dibujo_grupo.'",
              x: '.$x_grupo.',
              y: '.$y_grupo.',
              size: '.$tamano_nodo_grupo.',
              color: "'.$grupo->tipoGrupo->color.'",
              borderColor: "'.$grupo->tipoGrupo->color.'"
            },';*/

            $item = new stdClass();
            $item->id = 'G-'.$grupo->id;
            $item->label = $nombre_grupo;
            $item->type = $tipo_dibujo_grupo;
            $item->x = $x_grupo;
            $item->y = $y_grupo;
            $item->size = $tamano_nodo_grupo;
            $item->color = $grupo->tipoGrupo->color;
            $item->boderColor = $grupo->tipoGrupo->color;
            $nodos[] = $item;

            array_push($array_ids_grupos_dibujados, $grupo->id);
            $x_grupo=$x_grupo+$distancia_nodos_grupo;
            $cantidad_usuarios_grupo=$grupo->asistentes()->count();
            $usuarios_grupo=$grupo->asistentes()->orderBy('users.indice_grafico_ministerial', 'asc')->get();
            $y_usuario=$y_grupo+750000;

            $tamano_nodo_asistente=$tamano_nodo_grupo*0.5;
            foreach($usuarios_grupo as $usuario_grupo)
            {
              if(!in_array($usuario_grupo->id, $array_ids_usuarios_dibujados))
              {
                /*$nodos.='{
                id: "A-'.$usuario_grupo->id.'",
                label: "'.$usuario_grupo->tipoUsuario->nombre.': '.$usuario_grupo->nombre(3).' '.$usuario_grupo->segundo_nombre.' '.$usuario_grupo->primer_apellido.' '.$usuario_grupo->segundo_apellido.'",
                type: "circle",
                x: '.$x_usuario.',
                y: '.$y_usuario.',
                url: "www.g.com",
                size: '.$tamano_nodo_asistente.',
                color: "'.$usuario_grupo->tipoUsuario->color.'",
                borderColor: "'.$usuario_grupo->tipoUsuario->color.'",
                image: {
                  url: "'.$configuracion->url_img.'/fotos'.'/'.$usuario_grupo->foto.'",
                  scale: 1.3,
                  clip: 0.85
                }
                },';*/

                $urlFoto = $configuracion->version == 1
                ? Storage::url($configuracion->ruta_almacenamiento.'/img/foto-usuario/'.$usuario_grupo->foto)
                : Storage::url($configuracion->ruta_almacenamiento.'/img/foto-usuario/'.$usuario_grupo->foto);

                $item = new stdClass();
                $item->id = 'A-'.$usuario_grupo->id;
                $item->label = $usuario_grupo->tipoUsuario->nombre.': '.$usuario_grupo->nombre(3);
                $item->type = "circle";
                $item->x = $x_usuario;
                $item->y = $y_usuario;
                $item->size = $tamano_nodo_asistente;
                $item->color = $usuario_grupo->tipoUsuario->color;
                $item->boderColor = $usuario_grupo->tipoUsuario->color;

                $image = new stdClass();
                $image->url = $urlFoto;
                $image->scale = 1.3;
                $image->clip = 0.85;

                $item->image = $image;
                $nodos[] = $item;

                array_push($array_ids_usuarios_dibujados, $usuario_grupo->id);
                if(!in_array("Ar-ga-'.$grupo->id.'_'.$usuario_grupo->id.'", $array_aristas_grupo_usuario_dibujadas))
                {
                  /*$aristas.='{
                    id: "Ar-ga-'.$grupo->id.'_'.$usuario_grupo->id.'",
                    source: "G-'.$grupo->id.'",
                    target: "A-'.$usuario_grupo->id.'",
                    color: "#999",
                    size: 0.1
                  },';*/

                  $item = new stdClass();
                  $item->id = 'Ar-ga-'.$grupo->id.'_'.$usuario_grupo->id;
                  $item->source = 'G-'.$grupo->id;
                  $item->target = 'A-'.$usuario_grupo->id;
                  $item->color = "#999";
                  $item->size = 0.1;
                  $aristas[] = $item;

                  array_push($array_aristas_grupo_usuario_dibujadas, "Ar-ga-'.$grupo->id.'_'.$usuario_grupo->id.'");
                  $x_usuario=$x_usuario+$distancia_nodos_usuario;
                }
              }else{
                array_push($array_ids_usuarios_no_dibujados, $usuario_grupo->id);
              }

            }



            $array_ids_usuarios=array_merge($array_ids_usuarios, $grupo->asistentes()->select('users.id')->pluck('users.id')->toArray());
          }

          if(!in_array("Ar-ag-'.$usuario->id.'_'.$grupo->id.'", $array_aristas_usuario_grupo_dibujadas))
          {
            /*$aristas.='{
            id: "Ar-ag-'.$usuario->id.'_'.$grupo->id.'",
            source: "A-'.$usuario->id.'",
            target: "G-'.$grupo->id.'",
            color: "#999",
            size: 0.1
            },';*/

            $item = new stdClass();
            $item->id = 'Ar-ag-'.$usuario->id.'_'.$grupo->id;
            $item->source = 'A-'.$usuario->id;
            $item->target = 'G-'.$grupo->id;
            $item->color = "#999";
            $item->size = 0.1;
            $aristas[] = $item;

            array_push($array_aristas_usuario_grupo_dibujadas, "Ar-ag-'.$usuario->id.'_'.$grupo->id.'");
          }
        }
      }

      $tipo_nodo="modificado";
      $y_grupo=$y_grupo+1500000;
    }

    $usuarios_no_dibujados=User::whereIn("id",$array_ids_usuarios_no_dibujados)->get();

    return view('contenido.paginas.grupos.grafico-del-ministerio', [
      'nodos'=> $nodos,
      'aristas'=> $aristas,
      'mensaje'=> $mensaje,
      'usuario_seleccionado'=> $usuario_seleccionado,
      'grupo_seleccionado'=> $grupo_seleccionado,
      'array_ids_usuarios_no_dibujados'=> $array_ids_usuarios_no_dibujados,
      'usuarios_no_dibujados'=> $usuarios_no_dibujados,
      'tipoDeNodo' => $tipoDeNodo,
      'maximos_niveles' => $maximos_niveles,
      'configuracion' => $configuracion,
      'rolActivo' => $rolActivo
    ]);
	}

  public function cambiarIndice (Request $request, $tipo, $id)
  {
    if($tipo=="grupo"){
      $grupo = Grupo::find($id);
      $grupo->indice_grafico_ministerial=$request->cambioIndice;
      $grupo->save();

      return back()->with('success', "El índice del grupo <b>".$grupo->nombre."</b> se actualizó a  <b>". $grupo->indice_grafico_ministerial ."</b> con éxito.");
    }elseif($tipo=="usuario"){
      $usuario = User::find($id);
      $usuario->indice_grafico_ministerial=$request->cambioIndice;
      $usuario->save();

      return back()->with('success', "El índice de <b>".$usuario->nombre(3)."</b> se actualizó a <b>". $usuario->indice_grafico_ministerial."</b> con éxito.");
    }


  }

}
