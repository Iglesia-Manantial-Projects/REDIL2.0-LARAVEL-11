<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use App\Helpers\Helpers;
use App\Mail\DefaultMail;
use App\Models\TipoUsuario;
use App\Models\Iglesia;
use App\Models\RangoEdad;
use App\Models\Sede;
use App\Models\Grupo;
use App\Models\IntegranteGrupo;
use App\Models\EstadoCivil;
use App\Models\TipoIdentificacion;
use App\Models\TipoVinculacion;
use App\Models\PasoCrecimiento;
use App\Models\CrecimientoUsuario;
use App\Models\Ocupacion;
use App\Models\SectorEconomico;
use App\Models\TipoVivienda;
use App\Models\NivelAcademico;
use App\Models\EstadoNivelAcademico;
use App\Models\TipoSangre;
use App\Models\Profesion;
use App\Models\CampoInformeExcel;
use App\Models\CampoExtra;
use App\Models\Configuracion;
use App\Models\Continente;
use App\Models\Departamento;
use App\Models\EstadoPasoCrecimientoUsuario;
use App\Models\FormularioUsuario;
use App\Models\InformeGrupo;
use App\Models\Localidad;
use App\Models\Municipio;
use App\Models\Pais;
use App\Models\Peticion;
use App\Models\Region;
use App\Models\ReporteGrupo;
use App\Models\Role;
use App\Models\ServidorGrupo;
use App\Models\TipoGrupo;
use App\Models\TipoPeticion;
use App\Models\SeccionPasoCrecimiento;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use \stdClass;
use Symfony\Component\Console\Input\Input;

class UserController extends Controller
{
  public function listar(Request $request, $tipo = 'todos')//: View
  {
    $rolActivo = auth()->user()->roles()->wherePivot('activo', true)->first();

    $personas = [];
    $indicadoresGenerales = [];
    $indicadoresPorTipoUsuario = [];

    $configuracion = Configuracion::find(1);
    $camposInformeExcel = CampoInformeExcel::orderBy('orden', 'asc')->get();
    $camposExtras = CampoExtra::where('visible', '=', true)->get();

    $tiposUsuarios = TipoUsuario::orderBy('orden', 'asc')
      ->where('visible', true)
      ->where('tipo_pastor_principal', '!=', true)
      ->get();

    $rangosEdad = RangoEdad::all();
    $estadosCiviles = EstadoCivil::all();
    $tiposVinculaciones = TipoVinculacion::withTrashed()->get();
    $pasosCrecimiento = PasoCrecimiento::orderBy('updated_at', 'asc')->get();
    $ocupaciones = Ocupacion::orderBy('nombre', 'asc')->get();
    $nivelesAcademicos = NivelAcademico::orderBy('nombre', 'asc')->get();
    $estadosNivelAcademico = EstadoNivelAcademico::orderBy('id', 'asc')->get();
    $profesiones = Profesion::orderBy('nombre', 'asc')->get();

    //CALCULAR FECHA PARA VER LA FECHA DE CORTE PARA INACTIVAR UNA PERSONA EN GRUPO
    $fechaMaximaActividadGrupo = Carbon::now()
      ->subDays($configuracion->tiempo_para_definir_inactivo_grupo)
      ->format('Y-m-d');

    //CALCULAR FECHA PARA VER LA FECHA DE CORTE PARA INACTIVAR UNA PERSONA EN CULTO
    $fechaMaximaActividadReunion = Carbon::now()
      ->subDays($configuracion->tiempo_para_definir_inactivo_reunion)
      ->format('Y-m-d');

    // Tipos de asistentes con seguimiento de reuniones y grupos
    $tipoUsuariosSeguimientoReunion = TipoUsuario::where('seguimiento_actividad_reunion', '=', true)
      ->select('id')
      ->pluck('id')
      ->toArray();

    $tipoUsuariosSeguimientoGrupo = TipoUsuario::where('seguimiento_actividad_grupo', '=', true)
      ->select('id')
      ->pluck('id')
      ->toArray();

    $tipoUsuariosSeguimientoTodos = [];
    $tipoUsuariosSeguimientoTodos = array_intersect($tipoUsuariosSeguimientoReunion, $tipoUsuariosSeguimientoGrupo);

    // Array pastores principales
    $iglesia = Iglesia::find(1);
    $arrayPastoresPrincipal = $iglesia
      ->pastoresEncargados()
      ->select('users.id')
      ->pluck('users.id')
      ->toArray();

    $parametrosBusqueda['buscar'] = $request->buscar;
    $parametrosBusqueda['filtroPorSexo'] = $request->filtroPorSexo;
    $parametrosBusqueda['filtroPorTipoDeUsuario'] = $request->filtroPorTipoDeUsuario;
    $parametrosBusqueda['filtroPorRangoEdad'] = $request->filtroPorRangoEdad;
    $parametrosBusqueda['filtroPorEstadosCiviles'] = $request->filtroPorEstadosCiviles;
    $parametrosBusqueda['filtroPorTiposVinculaciones'] = $request->filtroPorTiposVinculaciones;
    $parametrosBusqueda['filtroPorOcupacion'] = $request->filtroPorOcupacion;
    $parametrosBusqueda['filtroPorProfesion'] = $request->filtroPorProfesion;
    $parametrosBusqueda['filtroPorNivelAcademico'] = $request->filtroPorNivelAcademico;
    $parametrosBusqueda['filtroPorEstadoNivelAcademico'] = $request->filtroPorEstadoNivelAcademico;
    $parametrosBusqueda['filtroPorPasosCrecimiento1'] = $request->filtroPorPasosCrecimiento1;
    $parametrosBusqueda['filtroEstadoPasos1'] = $request->filtroEstadoPasos1;
    $parametrosBusqueda['filtroFechasPasosCrecimiento1'] = $request->filtroFechasPasosCrecimiento1;
    $parametrosBusqueda['filtroFechaIniPaso1'] = $request->filtroFechaIniPaso1;
    $parametrosBusqueda['filtroFechaFinPaso1'] = $request->filtroFechaFinPaso1;
    $parametrosBusqueda['filtroPorPasosCrecimiento2'] = $request->filtroPorPasosCrecimiento2;
    $parametrosBusqueda['filtroEstadoPasos2'] = $request->filtroEstadoPasos2;
    $parametrosBusqueda['filtroFechasPasosCrecimiento2'] = $request->filtroFechasPasosCrecimiento2;
    $parametrosBusqueda['filtroFechaIniPaso2'] = $request->filtroFechaIniPaso2;
    $parametrosBusqueda['filtroFechaFinPaso2'] = $request->filtroFechaFinPaso2;
    $parametrosBusqueda['filtroGrupo'] = $request->filtroGrupo;
    $parametrosBusqueda['filtroTipoMinisterio'] = $request->filtroTipoMinisterio;
    $parametrosBusqueda['filtroCantidadDiasInactividadGrupos'] = $request->filtroCantidadDiasInactividadGrupos;
    $parametrosBusqueda['filtroCantidadDiasInactividadReuniones'] = $request->filtroCantidadDiasInactividadReuniones;

    $parametrosBusqueda['fechaMaximaActividadGrupo'] = $fechaMaximaActividadGrupo;
    $parametrosBusqueda['fechaMaximaActividadReunion'] = $fechaMaximaActividadReunion;
    $parametrosBusqueda['tipoUsuariosSeguimientoGrupo'] = $tipoUsuariosSeguimientoGrupo;
    $parametrosBusqueda['tipoUsuariosSeguimientoReunion'] = $tipoUsuariosSeguimientoReunion;
    $parametrosBusqueda['tipoUsuariosSeguimientoTodos'] = $tipoUsuariosSeguimientoTodos;
    $parametrosBusqueda['arrayPastoresPrincipal'] = $arrayPastoresPrincipal;
    $parametrosBusqueda['textoBusqueda'] = '';
    $parametrosBusqueda['bandera'] = '';
    $parametrosBusqueda['tipo'] = $tipo;

    $parametrosBusqueda = (object) $parametrosBusqueda;

    if (
      $rolActivo->hasPermissionTo('personas.lista_asistentes_todos') ||
      $rolActivo->hasPermissionTo('personas.lista_asistentes_solo_ministerio')
    ) {
      // si es un usuario diferente al super administrador
      if ($rolActivo->hasPermissionTo('personas.lista_asistentes_solo_ministerio')) {
        $personas = auth()
          ->user()
          ->discipulos('todos');

        $personasIndicadores = clone $personas;
      }

      /// si es el super administrador los trae todos
      if ($rolActivo->hasPermissionTo('personas.lista_asistentes_todos')) {
        $personas = User::withTrashed()
        ->leftJoin('integrantes_grupo', 'users.id', '=', 'integrantes_grupo.user_id')
        ->whereIn('tipo_usuario_id', $tiposUsuarios->pluck('id')->toArray())
        ->select('users.*', 'integrantes_grupo.grupo_id as grupo_id')
        ->get()
        ->unique('id');

        $personasIndicadores = clone $personas;
      }

      $item = new stdClass();
      $item->nombre = 'Todas (Dadas de alta)';
      $item->url = 'todos';
      $item->cantidad = $personasIndicadores->whereNull('deleted_at')->count();
      $item->color = 'bg-label-success';
      $item->icono = 'ti ti-asterisk';
      $indicadoresGenerales[] = $item;

      $item = new stdClass();
      $item->nombre = 'Sin grupo';
      $item->url = 'sin-grupo';
      $item->cantidad = $personasIndicadores
        ->whereNull('deleted_at')
        ->whereNull('grupo_id')
        ->whereNotIn('users.id', $arrayPastoresPrincipal)
        ->count();
      $item->color = 'bg-label-primary';
      $item->icono = 'ti ti-users';
      $indicadoresGenerales[] = $item;

      $item = new stdClass();
      $item->nombre = 'Dadas de baja';
      $item->url = 'dados-de-baja';
      $item->cantidad = $personasIndicadores->whereNotNull('deleted_at')->count();
      $item->color = 'bg-label-secondary';
      $item->icono = 'ti ti-user-off';
      $indicadoresGenerales[] = $item;

      $item = new stdClass();
      $item->nombre = 'Inactivas en reunión';
      $item->url = 'inactivas-reunion';
      $item->cantidad = $personasIndicadores
        ->whereNull('deleted_at')
        ->filter(function ($usuario) use ($fechaMaximaActividadReunion) {
          return $usuario->ultimo_reporte_reunion < $fechaMaximaActividadReunion ||
            $usuario->ultimo_reporte_reunion == null;
        })
        ->whereIn('tipo_usuario_id', $tipoUsuariosSeguimientoReunion)
        ->count();
      $item->color = 'bg-label-danger';
      $item->icono = 'ti ti-building-church';
      $indicadoresGenerales[] = $item;

      $item = new stdClass();
      $item->nombre = 'Inactivas en grupo';
      $item->url = 'inactivas-grupo';
      $item->cantidad = $personasIndicadores
        ->whereNull('deleted_at')
        ->filter(function ($usuario) use ($fechaMaximaActividadGrupo) {
          return $usuario->ultimo_reporte_grupo < $fechaMaximaActividadGrupo || $usuario->ultimo_reporte_grupo == null;
        })
        ->whereIn('tipo_usuario_id', $tipoUsuariosSeguimientoGrupo)
        ->count();
      $item->color = 'bg-label-danger';
      $item->icono = 'ti ti-user-x';
      $indicadoresGenerales[] = $item;

      $item = new stdClass();
      $item->nombre = 'Inactivas en todo';
      $item->url = 'inactivas-todo';
      $item->cantidad = $personasIndicadores
        ->whereNull('deleted_at')
        ->whereIn('tipo_usuario_id', $tipoUsuariosSeguimientoTodos)
        ->filter(function ($usuario) use ($fechaMaximaActividadGrupo, $fechaMaximaActividadReunion) {
          return ($usuario->ultimo_reporte_grupo < $fechaMaximaActividadGrupo ||
            $usuario->ultimo_reporte_grupo == null) &&
            ($usuario->ultimo_reporte_reunion < $fechaMaximaActividadReunion ||
              $usuario->ultimo_reporte_reunion == null);
        })
        ->count();
      $item->color = 'bg-label-danger';
      $item->icono = 'ti ti-x';
      $indicadoresGenerales[] = $item;

      foreach ($tiposUsuarios as $tipoUsuario) {

        $item = new stdClass();
        $item->nombre = $tipoUsuario->nombre;
        $item->url = $tipoUsuario->id;
        $item->cantidad = $personasIndicadores
          ->whereNull('deleted_at')
          ->where('tipo_usuario_id', $tipoUsuario->id)
          ->count();
        $item->color = $tipoUsuario->color;
        $item->icono = $tipoUsuario->icono;
        $indicadoresPorTipoUsuario[] = $item;
      }

      $indicadoresPorTipoUsuario = collect($indicadoresPorTipoUsuario);

      // filtrado por tipo ejemplo: "Todos o inactivo reunion o por alguno de los tipos de usuario Pastor, lider, oveja etc..."
      $personas = $this->filtroPorTipo($personas, $parametrosBusqueda);

      // filtro por busqueda
      $personas = $this->filtrosBusqueda($personas, $tipo, $parametrosBusqueda);

      if ($personas->count() > 0) {
        $personas = $personas->toQuery()->orderBy('id','desc')->paginate(12);
      } else {
        $personas = User::whereRaw('1=2')->paginate(1);
      }
    }

    return view('contenido.paginas.usuario.listar', [
      'personas' => $personas,
      'tipo' => $tipo,
      'tiposUsuarios' => $tiposUsuarios,
      'rangosEdad' => $rangosEdad,
      'estadosCiviles' => $estadosCiviles,
      'parametrosBusqueda' => $parametrosBusqueda,
      'indicadoresGenerales' => $indicadoresGenerales,
      'indicadoresPorTipoUsuario' => $indicadoresPorTipoUsuario,
      'tiposVinculaciones' => $tiposVinculaciones,
      'pasosCrecimiento' => $pasosCrecimiento,
      'ocupaciones' => $ocupaciones,
      'nivelesAcademicos' => $nivelesAcademicos,
      'estadosNivelAcademico' => $estadosNivelAcademico,
      'profesiones' => $profesiones,
      'camposInformeExcel' => $camposInformeExcel,
      'rolActivo' => $rolActivo,
      'camposExtras' => $camposExtras,
      'configuracion' => $configuracion,
    ]);
  }

  public function filtrosBusqueda($personas, $tipo, $parametrosBusqueda)
  {
    if ($parametrosBusqueda->filtroCantidadDiasInactividadGrupos != '') {
      $nuevafecha_grupo = Carbon::now()
        ->subDays($parametrosBusqueda->filtroCantidadDiasInactividadGrupos)
        ->format('Y-m-d');
      $personas = $personas
        ->filter(function ($usuario) use ($nuevafecha_grupo) {
          return $usuario->ultimo_reporte_grupo < $nuevafecha_grupo || $usuario->ultimo_reporte_grupo == null;
        })
        ->whereIn('tipo_usuario_id', $parametrosBusqueda->tipoUsuariosSeguimientoGrupo);

      $parametrosBusqueda->textoBusqueda .=
        '<b>, Días inactiviad en grupos: </b>' . $parametrosBusqueda->filtroCantidadDiasInactividadGrupos . ' ';
      $parametrosBusqueda->bandera = 1;
    }

    if ($parametrosBusqueda->filtroCantidadDiasInactividadReuniones != '') {
      $nuevafecha_reunion = Carbon::now()
        ->subDays($parametrosBusqueda->filtroCantidadDiasInactividadReuniones)
        ->format('Y-m-d');

      $personas = $personas
        ->filter(function ($usuario) use ($nuevafecha_reunion) {
          return $usuario->ultimo_reporte_reunion < $nuevafecha_reunion || $usuario->ultimo_reporte_reunion == null;
        })
        ->whereIn('tipo_usuario_id', $parametrosBusqueda->tipoUsuariosSeguimientoReunion);

      $parametrosBusqueda->textoBusqueda .=
        '<b>, Días inactiviad en grupos: </b>' . $parametrosBusqueda->filtroCantidadDiasInactividadReuniones . ' ';
      $parametrosBusqueda->bandera = 1;
    }
    //Ejecuto las consultas de los filtros de la búsqueda avanzada

    ///si el usuario ejecutó una busqueda se añaden las consultas necesarias
    if ($parametrosBusqueda->buscar != '') {
      $buscar = htmlspecialchars($parametrosBusqueda->buscar);
      $buscar = Helpers::sanearStringConEspacios($buscar);
      $buscar = str_replace(["'"], '', $buscar);
      $buscar_array = explode(' ', $buscar);

      foreach ($buscar_array as $palabra) {
        $personas = $personas->filter(function ($persona) use ($palabra) {
            $respuesta  = false !== stristr(Helpers::sanearStringConEspacios($persona->primer_nombre), $palabra) ||
            false !== stristr(Helpers::sanearStringConEspacios($persona->segundo_nombre), $palabra) ||
            false !== stristr(Helpers::sanearStringConEspacios($persona->primer_apellido), $palabra) ||
            false !== stristr(Helpers::sanearStringConEspacios($persona->segundo_apellido), $palabra) ||
            false !== stristr(Helpers::sanearStringConEspacios($persona->identificacion), $palabra) ||
            false !== stristr(Helpers::sanearStringConEspacios($persona->direccion), $palabra) ||
            false !== stristr(Helpers::sanearStringConEspacios($persona->telefono_movil), $palabra) ||
            false !== stristr(Helpers::sanearStringConEspacios($persona->email), $palabra);

            return $respuesta;
        });
      }

      $parametrosBusqueda->textoBusqueda .= '<b>, Con busqueda: </b>"' . $buscar . '" ';
      $parametrosBusqueda->bandera = 1;
    }

    //Filtro por rango de edad
    $personas = $this->filtrarEdad($personas, $parametrosBusqueda);

    //Filtro por sexo
    $personas = $this->filtrarSexo($personas, $parametrosBusqueda);

    //Filtro por esatdos civiles
    $personas = $this->filtrarEstadoCivil($personas, $parametrosBusqueda);

    //Filtro por tipo vinculacion
    $personas = $this->filtrarTipoVinculacion($personas, $parametrosBusqueda);

    //Filtro por paso de crecimiento 1
    $personas = $this->filtrarPasoCrecimiento(
      $personas,
      $parametrosBusqueda->filtroPorPasosCrecimiento1,
      $parametrosBusqueda->filtroEstadoPasos1,
      $parametrosBusqueda->filtroFechaIniPaso1,
      $parametrosBusqueda->filtroFechaFinPaso1,
      $parametrosBusqueda
    );

    //Filtro por paso de crecimiento 2
    $personas = $this->filtrarPasoCrecimiento(
      $personas,
      $parametrosBusqueda->filtroPorPasosCrecimiento2,
      $parametrosBusqueda->filtroEstadoPasos2,
      $parametrosBusqueda->filtroFechaIniPaso2,
      $parametrosBusqueda->filtroFechaFinPaso2,
      $parametrosBusqueda
    );

    //Filtro por ocupacion
    $personas = $this->filtrarOcupacion($personas, $parametrosBusqueda);

    //Filtro por nivel academico
    $personas = $this->filtrarNivelAcademico($personas, $parametrosBusqueda);

    //Filtro por estado nivel academico
    $personas = $this->filtrarEstadoNivelAcademico($personas, $parametrosBusqueda);

    //Filtro por profesion
    $personas = $this->filtrarProfesion($personas, $parametrosBusqueda);

    //Filtro a partir de un grupo
    $personas = $this->filtrarApartirGrupoSeleccionado($personas, $parametrosBusqueda);

    return $personas;
  }

  public function filtroPorTipo($personas, $parametrosBusqueda)
  {
    $parametrosBusqueda->textoBusqueda = '';
    if ($parametrosBusqueda->tipo != 'dados-de-baja') {
      if ($parametrosBusqueda->tipo == 'todos' || $parametrosBusqueda->tipo == '') {
        $parametrosBusqueda->textoBusqueda = 'Todas';
      }

      $personas = $personas->whereNull('deleted_at');

      if ($parametrosBusqueda->tipo == 'sin-grupo') {
        $parametrosBusqueda->textoBusqueda = 'Sin grupo';
        $personas = $personas
          ->whereNull('grupo_id')
          ->whereNotIn('users.id', $parametrosBusqueda->arrayPastoresPrincipal);
      }

      if ($parametrosBusqueda->tipo == 'inactivas-reunion') {
        $parametrosBusqueda->textoBusqueda = 'Inactivas en reunión';
        $personas = $personas
          ->filter(function ($usuario) use ($parametrosBusqueda) {
            return $usuario->ultimo_reporte_reunion < $parametrosBusqueda->fechaMaximaActividadReunion ||
              $usuario->ultimo_reporte_reunion == null;
          })
          ->whereIn('tipo_usuario_id', $parametrosBusqueda->tipoUsuariosSeguimientoReunion);
      }

      if ($parametrosBusqueda->tipo == 'inactivas-grupo') {
        $parametrosBusqueda->textoBusqueda = 'Inactivas en grupo';
        $personas = $personas
          ->filter(function ($usuario) use ($parametrosBusqueda) {
            return $usuario->ultimo_reporte_grupo < $parametrosBusqueda->fechaMaximaActividadGrupo ||
              $usuario->ultimo_reporte_grupo == null;
          })
          ->whereIn('tipo_usuario_id', $parametrosBusqueda->tipoUsuariosSeguimientoGrupo);
      }

      if ($parametrosBusqueda->tipo == 'inactivas-todo') {
        $parametrosBusqueda->textoBusqueda = 'Inactivas en todo';
        $personas = $personas
          ->whereIn('tipo_usuario_id', $parametrosBusqueda->tipoUsuariosSeguimientoTodos)
          ->filter(function ($usuario) use ($parametrosBusqueda) {
            return ($usuario->ultimo_reporte_grupo < $parametrosBusqueda->fechaMaximaActividadGrupo ||
              $usuario->ultimo_reporte_grupo == null) &&
              ($usuario->ultimo_reporte_reunion < $parametrosBusqueda->fechaMaximaActividadReunion ||
                $usuario->ultimo_reporte_reunion == null);
          });
      }

      if (is_numeric($parametrosBusqueda->tipo) && !isset($parametrosBusqueda->filtroPorTipoDeUsuario)) {
        $parametrosBusqueda->textoBusqueda = TipoUsuario::select('nombre_plural')
          ->where('id', $parametrosBusqueda->tipo)
          ->first()->nombre_plural;
        $personas = $personas->where('tipo_usuario_id', '=', $parametrosBusqueda->tipo);
      }
    } else {
      $parametrosBusqueda->textoBusqueda = 'Dadas de baja';
      $personas = $personas->whereNotNull('deleted_at');
    }

    if (isset($parametrosBusqueda->filtroPorTipoDeUsuario)) {
      $tiposUsuarios = TipoUsuario::select('nombre_plural')
        ->whereIn('id', $parametrosBusqueda->filtroPorTipoDeUsuario)
        ->get();
      $cantidad = $tiposUsuarios->count();
      $contador = 1;
      $parametrosBusqueda->textoBusqueda .= ' "';
      foreach ($tiposUsuarios as $tipo) {
        if ($contador == $cantidad) {
          $parametrosBusqueda->textoBusqueda .= $tipo->nombre_plural;
        } else {
          $parametrosBusqueda->textoBusqueda .= $tipo->nombre_plural . ', ';
        }
        $contador++;
      }
      $parametrosBusqueda->textoBusqueda .= '"';

      $personas = $personas->whereIn('tipo_usuario_id', $parametrosBusqueda->filtroPorTipoDeUsuario);
      $parametrosBusqueda->bandera = 1;
    }

    return $personas;
  }

  public function filtrarEdad($personas, $parametrosBusqueda)
  {
    if ($parametrosBusqueda->filtroPorRangoEdad) {
      $rangos = RangoEdad::whereIn('id', $parametrosBusqueda->filtroPorRangoEdad)->get();
      $edadesPermitidas = [];

      $parametrosBusqueda->textoBusqueda .=
        '<b>, Edades: </b>"' . implode(', ', $rangos->pluck('nombre')->toArray()) . '"';
      $parametrosBusqueda->bandera = 1;

      foreach ($rangos as $rango) {
        for ($x = $rango->edad_minima; $x <= $rango->edad_maxima; $x++) {
          $edadesPermitidas[] = $x;
        }
      }

      $personas = $personas->filter(function ($persona) use ($edadesPermitidas) {
        $edadPersona = Carbon::parse($persona->fecha_nacimiento)->age;
        return in_array($edadPersona, $edadesPermitidas);
      });
    }

    return $personas;
  }

  public function filtrarSexo($personas, $parametrosBusqueda)
  {
    if (is_numeric($parametrosBusqueda->filtroPorSexo)) {
      $personas = $personas->where('genero', '=', $parametrosBusqueda->filtroPorSexo);

      $parametrosBusqueda->textoBusqueda .=
        $parametrosBusqueda->filtroPorSexo == 0 ? '<b>, Sexo: </b> Hombres' : '<b>, Sexo:</b> Mujeres';
      $parametrosBusqueda->bandera = 1;
    }
    return $personas;
  }

  public function filtrarEstadoCivil($personas, $parametrosBusqueda)
  {
    if ($parametrosBusqueda->filtroPorEstadosCiviles) {
      $personas = $personas->whereIn('estado_civil_id', $parametrosBusqueda->filtroPorEstadosCiviles);

      $estadosCiviles = EstadoCivil::whereIn('id', $parametrosBusqueda->filtroPorEstadosCiviles)
        ->select('nombre')
        ->pluck('nombre')
        ->toArray();

      $parametrosBusqueda->textoBusqueda .= '<b>, Estados civiles: </b>"' . implode(', ', $estadosCiviles) . '"';
      $parametrosBusqueda->bandera = 1;
    }
    return $personas;
  }

  public function filtrarTipoVinculacion($personas, $parametrosBusqueda)
  {
    if ($parametrosBusqueda->filtroPorTiposVinculaciones) {
      $personas = $personas->whereIn('tipo_vinculacion_id', $parametrosBusqueda->filtroPorTiposVinculaciones);

      $tiposVinculacion = TipoVinculacion::whereIn('id', $parametrosBusqueda->filtroPorTiposVinculaciones)
        ->select('nombre')
        ->pluck('nombre')
        ->toArray();

      $parametrosBusqueda->textoBusqueda .= '<b>, Tipos de vinculación:</b> "' . implode(', ', $tiposVinculacion) . '"';
      $parametrosBusqueda->bandera = 1;
    }
    return $personas;
  }

  public function filtrarPasoCrecimiento(
    $personas,
    $pasosCrecimiento,
    $estado,
    $fechaInicio,
    $fechaFin,
    $parametrosBusqueda
  ) {
    if ($pasosCrecimiento) {
      $pasosDeCrecimiento = PasoCrecimiento::whereIn('id', $pasosCrecimiento)
        ->select('nombre')
        ->pluck('nombre')
        ->toArray();

      $parametrosBusqueda->textoBusqueda .= ', <b>Pasos de crecimiento';

      $personasPasoCrecimiento = CrecimientoUsuario::whereIn('paso_crecimiento_id', $pasosCrecimiento);
      $parametrosBusqueda->textoBusqueda .= '[ ';
      if ($fechaInicio && $fechaFin) {
        $personasPasoCrecimiento = $personasPasoCrecimiento->whereBetween('fecha', [$fechaInicio, $fechaFin]);
        $parametrosBusqueda->textoBusqueda .= ' Del ' . $fechaInicio . ' al ' . $fechaFin . ' | ';
      }

      if ($estado == 1) {
        $parametrosBusqueda->textoBusqueda .= 'Estado no realizado ]: </b>';
      } elseif ($estado == 2) {
        $parametrosBusqueda->textoBusqueda .= 'Estado en curso ]: </b>';
      } elseif ($estado == 3) {
        $parametrosBusqueda->textoBusqueda .= 'Estado finalizado ]: </b>';
      }

      if ($estado == 1) {
        $personasPasoCrecimiento = $personasPasoCrecimiento->whereNotIn('estado', [2, 3]);
      } else {
        $personasPasoCrecimiento = $personasPasoCrecimiento->where('estado', $estado);
      }

      $parametrosBusqueda->textoBusqueda .= '"' . implode(', ', $pasosDeCrecimiento) . '"';
      $parametrosBusqueda->bandera = 1;

      $idUserPasoCrecimiento = $personasPasoCrecimiento
        ->select('user_id')
        ->pluck('user_id')
        ->toArray();

      $personas = $personas->whereIn('id', $idUserPasoCrecimiento);
    }

    return $personas;
  }

  public function filtrarOcupacion($personas, $parametrosBusqueda)
  {
    if ($parametrosBusqueda->filtroPorOcupacion) {
      $personas = $personas->whereIn('ocupacion_id', $parametrosBusqueda->filtroPorOcupacion);

      $ocupaciones = Ocupacion::whereIn('id', $parametrosBusqueda->filtroPorOcupacion)
        ->select('nombre')
        ->pluck('nombre')
        ->toArray();

      $parametrosBusqueda->textoBusqueda .= '<b>, Ocupaciones: </b>"' . implode(', ', $ocupaciones) . '"';
      $parametrosBusqueda->bandera = 1;
    }
    return $personas;
  }

  public function filtrarNivelAcademico($personas, $parametrosBusqueda)
  {
    if ($parametrosBusqueda->filtroPorNivelAcademico) {
      $personas = $personas->whereIn('nivel_academico_id', $parametrosBusqueda->filtroPorNivelAcademico);

      $nivelesAcademicos = NivelAcademico::whereIn('id', $parametrosBusqueda->filtroPorNivelAcademico)
        ->select('nombre')
        ->pluck('nombre')
        ->toArray();

      $parametrosBusqueda->textoBusqueda .= ', <b>Niveles académicos: </b>"' . implode(', ', $nivelesAcademicos) . '"';
      $parametrosBusqueda->bandera = 1;
    }
    return $personas;
  }

  public function filtrarEstadoNivelAcademico($personas, $parametrosBusqueda)
  {
    if ($parametrosBusqueda->filtroPorEstadoNivelAcademico) {
      $personas = $personas->where(
        'estado_nivel_academico_id',
        '=',
        $parametrosBusqueda->filtroPorEstadoNivelAcademico
      );

      $estadoNivelAcademico = EstadoNivelAcademico::where(
        'id',
        $parametrosBusqueda->filtroPorEstadoNivelAcademico
      )->first();

      $parametrosBusqueda->textoBusqueda .=
        '<b>, Estados niveles académicos: </b>"' . $estadoNivelAcademico->nombre . '"';
      $parametrosBusqueda->bandera = 1;
    }
    return $personas;
  }

  public function filtrarProfesion($personas, $parametrosBusqueda)
  {
    if ($parametrosBusqueda->filtroPorProfesion) {
      $personas = $personas->where('profesion_id', '=', $parametrosBusqueda->filtroPorProfesion);

      $profesiones = Profesion::whereIn('id', $parametrosBusqueda->filtroPorProfesion)
        ->select('nombre')
        ->pluck('nombre')
        ->toArray();

      $parametrosBusqueda->textoBusqueda .= '<b>, Profesiones: </b>"' . implode(', ', $profesiones) . '"';
      $parametrosBusqueda->bandera = 1;
    }
    return $personas;
  }

  public function filtrarApartirGrupoSeleccionado($personas, $parametrosBusqueda)
  {
    if ($parametrosBusqueda->filtroGrupo != '') {
      $configuracion = Configuracion::find(1);
      $grupo = Grupo::find($parametrosBusqueda->filtroGrupo);

      $parametrosBusqueda->textoBusqueda .= '<b>, Grupo: </b>' . $grupo->nombre;

      if ($parametrosBusqueda->filtroTipoMinisterio == 0) {
        $gruposIds = $grupo->gruposMinisterio('array');

        //Agrego el id del grupo que estoy consultado
        array_push($gruposIds, $grupo->id);

        $idsUsers = IntegranteGrupo::whereIn('grupo_id', $gruposIds)
          ->select('user_id')
          ->pluck('user_id')
          ->toArray();

        $personas = $personas->whereIn('id', $idsUsers);
        $parametrosBusqueda->textoBusqueda .= '"Ministerio completo"';
      } else {
        $idsUsers = IntegranteGrupo::where('grupo_id', '=', $grupo->id)
          ->select('user_id')
          ->pluck('user_id')
          ->toArray();

        $personas = $personas->whereIn('id', $idsUsers);
        $parametrosBusqueda->textoBusqueda .= '"Ministerio directo"';
        $parametrosBusqueda->bandera = 1;
      }
    }
    return $personas;
  }

  public function listadoFinalCsv(Request $request)
  {
    $rolActivo = auth()->user()->roles()->wherePivot('activo', true)->first();

    $parametrosBusqueda = json_decode($request->parametrosBusqueda);

    /* ESTA PARTE ES PARA OBTENER TODOS LOS CAMPOS SOLICITADOS DENTRO DE LOS SELECTORES QUE SOLO PERTENECEN AL ASISTENTE  */
    $html_pasos_crecimiento = '';
    $contador = 0;

    $arrayCamposInfoPersonal = $request->informacionPersonal ? $request->informacionPersonal : []; //$arrayCamposInfoPersonal
    $arrayPasosCrecimiento = $request->informacionMinisterial ? $request->informacionMinisterial : []; // $arrayPasosCrecimiento
    $arrayDatosCongregacionales = $request->informacionCongregacional ? $request->informacionCongregacional : []; // $arrayDatosCongregacionales
    $arrayCamposExtra = $request->informacionCamposExtras ? $request->informacionCamposExtras : []; // $arrayCamposExtra

    $configuracion = Configuracion::find(1);
    /*$tiposEstadosCiviles = EstadoCiviles();
    $tiposDeIdentificaciones = TipoIdentificacion();
    $tiposDeSangres = TipoSangre();
    $listadoNivelesAcademicos = NivelAcademico();
    $listadoEstadosNivelesAcademicos = EstadoNivelAcademico();
    $listadoProfesiones = Profesion();
    $listadoOcupaciones = Ocupacion();
    $listadoSectoresEconomicos = SectorEconomico();
    $tiposVivienda = TipoVivienda();*/

    /// aqui se cmezcla el array de todos los campos seleccionados, tanto de los congregacionales como de la información personal
    $arrayTotalCamposSeleccionados = array_merge($arrayCamposInfoPersonal, $arrayDatosCongregacionales);

    $camposInforme = CampoInformeExcel::whereIn('campos_informe_excel.id', $arrayTotalCamposSeleccionados)
      ->orderBy('orden', 'asc')
      ->get();

    $nombreArchivo = 'informe_personas' . Carbon::now()->format('Y-m-d-H-i-s');
    $rutaArchivo = "/$configuracion->ruta_almacenamiento/informes/personas/$nombreArchivo.csv";

    $archivo = fopen(storage_path('app/public').$rutaArchivo, 'w');
    fputs($archivo, $bom = chr(0xef) . chr(0xbb) . chr(0xbf));

    /* Aquí se crean los encabezados */
    $arrayEncabezadoFila1 = [];
    $arrayEncabezadoFila2 = [];

    foreach ($camposInforme->pluck('nombre_campo_informe')->toArray() as $campo) {
      array_push($arrayEncabezadoFila1, $campo);
      array_push($arrayEncabezadoFila2, ' ');
    }

    // agrego los pasos de crecimiento al encabezado
    $pasosCrecimientoSeleccionados = PasoCrecimiento::whereIn('id', $arrayPasosCrecimiento)->get();
    foreach ($pasosCrecimientoSeleccionados as $paso) {
      $arrayEncabezadoFila1 = array_merge($arrayEncabezadoFila1, [$paso->nombre, '', '']);
      $arrayEncabezadoFila2 = array_merge($arrayEncabezadoFila2, ['Fecha', 'Estado', 'Detalle']);
    }

    // agrego los campos extra al encabezado
    $camposExtraSeleccionados = CampoExtra::whereIn('id', $arrayCamposExtra)
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

    // si es un usuario diferente al super administrador
    if ($rolActivo->hasPermissionTo('personas.lista_asistentes_solo_ministerio')) {
      $personas = auth()
        ->user()
        ->discipulos('todos');
    } elseif ($rolActivo->hasPermissionTo('personas.lista_asistentes_todos')) {
      $personas = User::withTrashed()
        ->leftJoin('integrantes_grupo', 'users.id', '=', 'integrantes_grupo.user_id')
        ->select('users.*', 'integrantes_grupo.grupo_id as grupo_id')
        ->get()
        ->unique('id');
    }

    // filtrado por tipo ejemplo: "Todos o inactivo reunion o por alguno de los tipos de usuario Pastor, lider, oveja etc..."
    $personas = $this->filtroPorTipo($personas, $parametrosBusqueda);

    // filtro por busqueda
    $personas = $this->filtrosBusqueda($personas, $parametrosBusqueda->tipo, $parametrosBusqueda);

    foreach ($personas as $persona) {
      $fila = [];

      //tipo identificación
      if ($camposInforme->where('nombre_campo_bd', 'tipo_identificacion')->count() > 0) {
        array_push($fila, $persona->tipoIdentificacion ? $persona->tipoIdentificacion->nombre : 'Sin información');
      }

      //identificación
      if ($camposInforme->where('nombre_campo_bd', 'identificacion')->count() > 0) {
        array_push($fila, $persona->identificacion ? $persona->identificacion : 'Sin información');
      }

      //edad
      if ($camposInforme->where('nombre_campo_bd', 'edad')->count() > 0) {
        array_push($fila, $persona->fecha_nacimiento ? $persona->edad() : 'Sin información');
      }

      //primer nombre
      if ($camposInforme->where('nombre_campo_bd', 'primer_nombre')->count() > 0) {
        array_push($fila, $persona->primer_nombre ? $persona->primer_nombre : 'Sin información');
      }

      //segundo nombre
      if ($camposInforme->where('nombre_campo_bd', 'segundo_nombre')->count() > 0) {
        array_push($fila, $persona->segundo_nombre ? $persona->segundo_nombre : 'Sin información');
      }

      //primer apellido
      if ($camposInforme->where('nombre_campo_bd', 'primer_apellido')->count() > 0) {
        array_push($fila, $persona->primer_apellido ? $persona->primer_apellido : 'Sin información');
      }

      //segundo apellido
      if ($camposInforme->where('nombre_campo_bd', 'segundo_apellido')->count() > 0) {
        array_push($fila, $persona->segundo_apellido ? $persona->segundo_apellido : 'Sin información');
      }

      //estado civil
      if ($camposInforme->where('nombre_campo_bd', 'estado_civil')->count() > 0) {
        array_push($fila, $persona->estadoCivil ? $persona->estadoCivil->nombre : 'Sin información');
      }

      //pais
      if ($camposInforme->where('nombre_campo_bd', 'pais_id')->count() > 0) {
        array_push($fila, $persona->pais ? $persona->pais->nombre : 'Sin información');
      }

      //telefono fijo
      if ($camposInforme->where('nombre_campo_bd', 'telefono_fijo')->count() > 0) {
        array_push($fila, $persona->telefono_fijo ? $persona->telefono_fijo : 'Sin información');
      }

      //telefono otro
      if ($camposInforme->where('nombre_campo_bd', 'telefono_otro')->count() > 0) {
        array_push($fila, $persona->telefono_otro ? $persona->telefono_otro : 'Sin información');
      }

      //telefono fijo
      if ($camposInforme->where('nombre_campo_bd', 'telefono_movil')->count() > 0) {
        array_push($fila, $persona->telefono_movil ? $persona->telefono_movil : 'Sin información');
      }

      //email - correo electronico
      if ($camposInforme->where('nombre_campo_bd', 'email')->count() > 0) {
        array_push($fila, $persona->email ? $persona->email : 'Sin información');
      }

      //direccion
      if ($camposInforme->where('nombre_campo_bd', 'direccion')->count() > 0) {
        array_push($fila, $persona->direccion ? $persona->direccion : 'Sin información');
      }

      //tipo vivienda
      if ($camposInforme->where('nombre_campo_bd', 'tipo_vivienda')->count() > 0) {
        array_push($fila, $persona->tipoDeVivienda ? $persona->tipoDeVivienda->nombre : 'Sin información');
      }

      //nivel educativo
      if ($camposInforme->where('nombre_campo_bd', 'nivel_academico')->count() > 0) {
        array_push($fila, $persona->nivelAcademico ? $persona->nivelAcademico->nombre : 'Sin información');
      }

      //estado nivel academico
      if ($camposInforme->where('nombre_campo_bd', 'estado_nivel_academico')->count() > 0) {
        array_push($fila, $persona->estadoNivelAcademico ? $persona->estadoNivelAcademico->nombre : 'Sin información');
      }

      //profesion
      if ($camposInforme->where('nombre_campo_bd', 'profesion')->count() > 0) {
        array_push($fila, $persona->profesion ? $persona->profesion->nombre : 'Sin información');
      }

      //sector economico
      if ($camposInforme->where('nombre_campo_bd', 'sector_economico')->count() > 0) {
        array_push($fila, $persona->sectorEconomico ? $persona->sectorEconomico->nombre : 'Sin información');
      }

      //tipo de sangre
      if ($camposInforme->where('nombre_campo_bd', 'tipo_sangre')->count() > 0) {
        array_push($fila, $persona->tipoDeSangre ? $persona->tipoDeSangre->nombre : 'Sin información');
      }

      //indicaciones medicas
      if ($camposInforme->where('nombre_campo_bd', 'indicaciones_medicas')->count() > 0) {
        array_push($fila, $persona->indicaciones_medicas ? $persona->indicaciones_medicas : 'Sin información');
      }

      ///informacion opcional
      if ($camposInforme->where('nombre_campo_bd', 'informacion_opcional')->count() > 0) {
        array_push($fila, $persona->informacion_opcional ? $persona->informacion_opcional : 'Sin información');
      }

      // dados baja
      if (
        $camposInforme->where('nombre_campo_bd', 'dado_baja')->count() > 0 ||
        $camposInforme->where('nombre_campo_bd', 'dado_alta')->count() > 0 ||
        $camposInforme->where('nombre_campo_bd', 'fecha_dado_baja')->count() > 0 ||
        $camposInforme->where('nombre_campo_bd', 'fecha_dado_alta')->count() > 0
      ) {
        $dadoBaja = $persona
          ->reportesBajaAlta()
          ->orderBy('created_at', 'DESC')
          ->first();

        if ($camposInforme->where('nombre_campo_bd', 'dado_alta')->count() > 0) {
          array_push($fila, $dadoBaja && $dadoBaja->dado_baja == false ? $dadoBaja->tipo->nombre : 'Sin información');
        }

        if ($camposInforme->where('nombre_campo_bd', 'dado_baja')->count() > 0) {
          array_push($fila, $dadoBaja && $dadoBaja->dado_baja == true ? $dadoBaja->tipo->nombre : 'Sin información');
        }

        if ($camposInforme->where('nombre_campo_bd', 'fecha_dado_alta')->count() > 0) {
          array_push($fila, $dadoBaja && $dadoBaja->dado_baja == false ? $dadoBaja->fecha : 'Sin fecha de alta');
        }

        if ($camposInforme->where('nombre_campo_bd', 'fecha_dado_baja')->count() > 0) {
          array_push($fila, $dadoBaja && $dadoBaja->dado_baja == true ? $dadoBaja->fecha : 'Sin fecha de baja');
        }
      }

      // contactos acudientes menores
      $edad = $persona->edad();
      if (
        $camposInforme->where('nombre_campo_bd', 'nombre_adulto_responsable')->count() > 0 ||
        $camposInforme->where('nombre_campo_bd', 'contacto_adulto_responsable')->count() > 0
      ) {
        if ($edad < $configuracion->limite_menor_edad) {
          $pariente = DB::table('parientes_usuarios')
            ->where('pariente_user_id', '=', $persona->id)
            ->where('es_el_responsable', '=', true)
            ->first();

          if ($pariente) {
            $pariente = User::select(
              'id',
              'primer_nombre',
              'segundo_nombre',
              'primer_apellido',
              'segundo_apellido',
              'telefono_fijo',
              'telefono_movil'
            )->find($pariente->user_id);

            if ($camposInforme->where('nombre_campo_bd', 'nombre_adulto_responsable')->count() > 0) {
              array_push($fila, $pariente->nombre(3));
            }

            if ($camposInforme->where('nombre_campo_bd', 'contacto_adulto_responsable')->count() > 0) {
              if ($pariente->telefono_fijo) {
                array_push($fila, $pariente->telefono_fijo);
              } elseif ($pariente->telefono_movil) {
                array_push($fila, $pariente->telefono_movil);
              } else {
                array_push($fila, 'Sin información');
              }
            }
          } else {
            array_push($fila, 'No Aplica');
            array_push($fila, 'No Aplica');
          }
        } else {
          array_push($fila, 'No Aplica');
          array_push($fila, 'No Aplica');
        }
      }

      if ($camposInforme->where('nombre_campo_bd', 'nombre_acudiente')->count() > 0) {
        if ($edad < $configuracion->limite_menor_edad) {
          array_push($fila, $persona->nombre_acudiente ? $persona->nombre_acudiente : 'Sin información');
        } else {
          array_push($fila, 'No Aplica');
        }
      }

      if ($camposInforme->where('nombre_campo_bd', 'telefono_acudiente')->count() > 0) {
        if ($edad < $configuracion->limite_menor_edad) {
          array_push($fila, $persona->telefono_acudiente ? $persona->telefono_acudiente : 'Sin información');
        } else {
          array_push($fila, 'No Aplica');
        }
      }

      //fecha nacimiento
      if ($camposInforme->where('nombre_campo_bd', 'fecha_nacimiento')->count() > 0) {
        array_push($fila, $persona->fecha_nacimiento ? $persona->fecha_nacimiento : 'Sin información');
      }

      //sexo
      if ($camposInforme->where('nombre_campo_bd', 'genero')->count() > 0) {
        array_push($fila, $persona->genero == 1 ? 'Femenino' : 'Masculino');
      }

      // Ultimo reporte grupo
      if ($camposInforme->where('nombre_campo_bd', 'ultimo_reporte_grupo')->count() > 0) {
        array_push(
          $fila,
          $persona->ultimo_reporte_grupo
            ? Carbon::parse($persona->ultimo_reporte_grupo)->format('Y-m-d')
            : 'Sin información'
        );
      }

      // Ultimo reporte reunion
      if ($camposInforme->where('nombre_campo_bd', 'ultimo_reporte_reunion')->count() > 0) {
        array_push(
          $fila,
          $persona->ultimo_reporte_reunion
            ? Carbon::parse($persona->ultimo_reporte_reunion)->format('Y-m-d')
            : 'Sin información'
        );
      }

      // tipo vinculacion
      if ($camposInforme->where('nombre_campo_bd', 'tipo_vinculacion_id')->count() > 0) {
        array_push($fila, $persona->tipoVinculacion ? $persona->tipoVinculacion->nombre : 'Sin información');
      }

      //tipo asistente
      if ($camposInforme->where('nombre_campo_bd', 'tipo_asistente_id')->count() > 0) {
        array_push($fila, $persona->tipoUsuario ? $persona->tipoUsuario->nombre : 'Sin información');
      }

      //grupo al que pertenece
      if ($camposInforme->where('nombre_campo_bd', 'grupo_id')->count() > 0) {
        $grupo = $persona
          ->gruposDondeAsiste()
          ->orderBy('grupo_id', 'desc')
          ->first();
        array_push($fila, $grupo ? $grupo->nombre : 'Sin información');
      }

      //sede
      if ($camposInforme->where('nombre_campo_bd', 'sede_id')->count() > 0) {
        array_push($fila, $persona->sede ? $persona->sede->nombre : 'Sin información');
      }

      //Fecha Creación
      if ($camposInforme->where('nombre_campo_bd', 'created_at')->count() > 0) {
        array_push($fila, $persona->created_at ? $persona->created_at : 'Sin información');
      }

      //Usuario Creación
      // Antes tambien tenia asistente_de_creacion_id pero ya quedo obsoleto porque se uniero la tabla user y la tabla asistentes
      if ($camposInforme->where('nombre_campo_bd', 'usuario_creacion_id')->count() > 0) {
        array_push($fila, $persona->usuarioCreacion ? $persona->usuarioCreacion->nombre(3) : 'Formulario nuevos');
      }

      //Recepcion Conectate
      if ($camposInforme->where('nombre_campo_bd', 'formulario_conectados')->count() > 0) {
        array_push($fila, $persona->formulario_conectados ? 'SI' : 'NO');
      }

      //AQUI EMPIEZA EL CONSTRUCTOR DE PASOS EXTRA
      foreach ($camposExtraSeleccionados as $campo) {
        $campoExtraUsuario = $persona
          ->camposExtras()
          ->where('campo_extra_id', $campo->id)
          ->first();
        if ($campo->tipo_de_campo == 1) {
          array_push($fila, $campoExtraUsuario ? $campoExtraUsuario->pivot->valor : 'Sin información');
        }

        if ($campo->tipo_de_campo == 2) {
          array_push($fila, $campoExtraUsuario ? $campoExtraUsuario->pivot->valor : 'Sin información');
        }

        if ($campo->tipo_de_campo == 3) {
          if ($campoExtraUsuario) {
            $json_opciones_campo = json_decode($campo->opciones_select);

            foreach ($json_opciones_campo as $opcion) {
              if ($opcion->value == $campoExtraUsuario->pivot->valor) {
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

          if (isset($campoExtraUsuario)) {
            $campo_usuario_opciones_seleccionadas = json_decode($campoExtraUsuario->pivot->valor);
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

      // AQUI EMPIEZA EL CONSTRUCTOR DE LOS PASOS DE CRECIMIENTO
      foreach ($pasosCrecimientoSeleccionados as $paso) {
        $pasoActual = $persona
          ->pasosCrecimiento()
          ->where('paso_crecimiento_id', '=', $paso->id)
          ->first();

        if ($pasoActual) {
          array_push($fila, $pasoActual->pivot->fecha ? $pasoActual->pivot->fecha : 'Sin Fecha');
          array_push(
            $fila,
            $pasoActual->pivot->estado == 1
              ? 'No Finalizado'
              : ($pasoActual->pivot->estado == 2
                ? 'En Curso'
                : ($pasoActual->pivot->estado == 3
                  ? 'Finalizado'
                  : 'Sin estado'))
          );
          array_push(
            $fila,
            $pasoActual->pivot->detalle
              ? preg_replace("[\n|\r|\n\r]", '', ucwords(mb_strtolower($pasoActual->pivot->detalle)))
              : 'Sin detalle'
          );
        } else {
          array_push($fila, 'Sin fecha');
          array_push($fila, 'Sin estado');
          array_push($fila, 'Sin detalle');
        }
      }
      /// AQUI IMPRIME EN EL DOCUMENTO LA LINEA DE CADA ASISTENTE

      fputcsv($archivo, $fila, ';');
    }

    // Genera el archivo
    fclose($archivo);

    return Redirect::back()->with(
      'success',
      'El informe fue generado con éxito, <a href="'.Storage::url($rutaArchivo).'" class=" link-success fw-bold" download="'.$nombreArchivo.'.csv"> descargalo aquí</a>'
    );
  }

  public function perfil($usuarioId): View
  {
    $usuario = User::withTrashed()->find($usuarioId);
    $configuracion = Configuracion::find(1);
    $labelPaisNacimiento = FormularioUsuario::select('label_pais_nacimiento')
      ->whereNotNull('label_pais_nacimiento')
      ->first();
    $camposExtras = CampoExtra::get();

    $camposExtrasHtml = '';
    if ($configuracion->visible_seccion_campos_extra == true) {
      foreach ($camposExtras as $campo) {
        $camposExtrasHtml .=
          '<li class="d-flex align-items-center mb-1">
        <i class="ti ti-point text-heading"></i><span class="fw-medium mx-2 text-heading">
          ' .
          $campo->nombre .
          ': </span><span class="fw-medium mx-2 text-heading">';

        if ($campo->tipo_de_campo == 1 || $campo->tipo_de_campo == 2) {
          $camposExtrasHtml .= $usuario
            ->camposExtras()
            ->where('campos_extra.id', $campo->id)
            ->first()
            ? $usuario
            ->camposExtras()
            ->where('campos_extra.id', $campo->id)
            ->first()->pivot->valor
            : 'Sin dato';
        } elseif ($campo->tipo_de_campo == 3) {
          $bandera = 0;
          foreach (json_decode($campo->opciones_select) as $opcion) {
            $campoUsuario = $usuario
              ->camposExtras()
              ->where('campos_extra.id', $campo->id)
              ->wherePivot('valor', $opcion->value)
              ->first();
            if ($campoUsuario && $campoUsuario->pivot->valor) {
              $camposExtrasHtml .= $opcion->nombre;
              $bandera = 1;
            }
          }

          $camposExtrasHtml .= $bandera == 0 ? 'Sin dato' : '';
        } elseif ($campo->tipo_de_campo == 4) {
          $campoUsuario = $usuario
            ->camposExtras()
            ->where('campos_extra.id', $campo->id)
            ->first();

          if ($campoUsuario && $campoUsuario->pivot->valor) {
            $valoresUsuario = explode(',', $campoUsuario->pivot->valor);
            $arrayValoresUsuario = [];

            foreach (json_decode($campo->opciones_select) as $opcion) {
              if (in_array($opcion->value, $valoresUsuario)) {
                $arrayValoresUsuario[] = $opcion->nombre;
              }
            }
            $camposExtrasHtml .= implode(', ', $arrayValoresUsuario);
          } else {
            $camposExtrasHtml .= 'Sin dato';
          }
        }

        $camposExtrasHtml .= '</span> </i>';
      }
    }

    $parientes = $usuario
      ->parientesDelUsuario()
      ->leftJoin('tipos_parentesco', 'parientes_usuarios.tipo_pariente_id', '=', 'tipos_parentesco.id')
      ->select(
        'users.id',
        'users.foto',
        'users.identificacion',
        'users.primer_nombre',
        'users.segundo_nombre',
        'users.primer_apellido',
        'users.segundo_apellido',
        'users.tipo_identificacion_id',
        'tipos_parentesco.nombre as nombre_parentesco',
        'tipos_parentesco.nombre_masculino',
        'tipos_parentesco.nombre_femenino',
        'parientes_usuarios.es_el_responsable'
      )
      ->get();

    $roles = $usuario
      ->roles()
      ->wherePivot('dependiente', false)
      ->get()
      ->pluck('name')
      ->toArray();
    $encargadosAscendentes = $usuario
      ->lideres()
      ->select(
        'id',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'tipo_usuario_id',
        'foto'
      )
      ->orderby('tipo_usuario_id', 'asc')
      ->get();

    $gruposAscendentes = $usuario
      ->lideres()
      ->leftJoin('integrantes_grupo', 'users.id', '=', 'integrantes_grupo.user_id')
      ->leftJoin('grupos', 'integrantes_grupo.grupo_id', '=', 'grupos.id')
      ->leftJoin('tipo_grupos', 'grupos.tipo_grupo_id', '=', 'tipo_grupos.id')
      ->whereNotNull('integrantes_grupo.grupo_id')
      ->select('grupos.id', 'grupos.nombre', 'tipo_grupos.nombre as nombreTipo')
      ->get();

    $gruposEncargados = $usuario->gruposEncargados;

    $totalGrupos = $usuario
      ->gruposMinisterio()
      ->where('dado_baja', 0)
      ->count();
    $totalGruposDirectos = $gruposEncargados->where('dado_baja', 0)->count();
    $totalGruposIndirectos = $totalGrupos - $totalGruposDirectos;

    $gruposExcluidos = $usuario->gruposExcluidos;

    $rolActivo = auth()->user()->roles()->wherePivot('activo', true)->first();

    $serviciosPrestadosEnGrupos = $usuario->serviciosPrestadosEnGrupos();

    $gruposDondeAsiste = $usuario->gruposDondeAsiste;
    $encargadosDirectos = $usuario->encargadosDirectos();

    $pasosDeCrecimiento = PasoCrecimiento::orderBy('updated_at', 'asc')
      ->select('id', 'nombre')
      ->get();

    $pasosDeCrecimiento->map(function ($paso) use ($usuario) {
      $pasoUsuario = CrecimientoUsuario::where('user_id', $usuario->id)
        ->where('paso_crecimiento_id', $paso->id)
        ->first();
      $paso->clase_color = 'danger';
      $paso->estado_fecha = null;
      $paso->estado_paso = 1;
      $paso->estado_nombre = 'No realizado';
      $paso->detalle_paso = '';
      $paso->bandera = 'default';

      if ($pasoUsuario) {
        $paso->clase_color = $pasoUsuario->estado->color;
        $paso->estado_fecha = $pasoUsuario->fecha;
        $paso->estado_paso = $pasoUsuario->estado_id;
        $paso->estado_nombre = $pasoUsuario->estado->nombre;
        $paso->detalle_paso = $pasoUsuario->detalle;
        $paso->bandera = 'si existe';
      }
    });

    $año = Carbon::now()->year;
    $cantidadMeses = 11;
    $fechaBase = Carbon::now()->format('Y-m-d');
    $meses = Helpers::meses('corto');
    $dataReportesReunion = [];
    $serieReporesReunion = [];

    $dataReportesGrupo = [];
    $serieReporesGrupo = [];

    //$grupoLast =  $usuario->gruposDondeAsiste()->get()->last();

    for ($i = $cantidadMeses; $i >= 0; $i--) {
      $fechaInicio = Carbon::parse($fechaBase)
        ->subMonths($i)
        ->startOfMonth()
        ->format('Y-m-d');
      $fechaFin = Carbon::parse($fechaBase)
        ->subMonths($i)
        ->endOfMonth()
        ->format('Y-m-d');
      $mesNumero = Carbon::parse($fechaBase)->subMonths($i)->month;

      $asistenciasReuniones = $usuario
        ->reportesReunion()
        ->where('asistencia_reuniones.asistio', true)
        ->where('reporte_reuniones.fecha', '>=', $fechaInicio)
        ->where('reporte_reuniones.fecha', '<=', $fechaFin)
        ->select('reporte_reuniones.id')
        ->get();

      $asistenciasGrupos = $usuario
        ->reportesGrupo()
        ->where('asistencia_grupos.asistio', true)
        ->where('reporte_grupos.fecha', '>=', $fechaInicio)
        ->where('reporte_grupos.fecha', '<=', $fechaFin)
        ->select('reporte_grupos.id')
        ->get();

      $dataReportesReunion[] = $asistenciasReuniones->count();
      $serieReporesReunion[] = $meses[$mesNumero - 1];

      $dataReportesGrupo[] = $asistenciasGrupos->count();
      $serieReporesGrupo[] = $meses[$mesNumero - 1];
    }

    $peticiones = $usuario->peticiones;

    return view('contenido.paginas.usuario.perfil', [
      'rolActivo' => $rolActivo,
      'gruposExcluidos' => $gruposExcluidos,
      'usuario' => $usuario,
      'configuracion' => $configuracion,
      'labelPaisNacimiento' => $labelPaisNacimiento,
      'camposExtrasHtml' => $camposExtrasHtml,
      'parientes' => $parientes,
      'roles' => $roles,
      'encargadosAscendentes' => $encargadosAscendentes,
      'gruposAscendentes' => $gruposAscendentes,
      'gruposEncargados' => $gruposEncargados,
      'totalGrupos' => $totalGrupos,
      'totalGruposDirectos' => $totalGruposDirectos,
      'totalGruposIndirectos' => $totalGruposIndirectos,
      'serviciosPrestadosEnGrupos' => $serviciosPrestadosEnGrupos,
      'gruposDondeAsiste' => $gruposDondeAsiste,
      'encargadosDirectos' => $encargadosDirectos,
      'pasosDeCrecimiento' => $pasosDeCrecimiento,
      'dataReportesReunion' => $dataReportesReunion,
      'serieReporesReunion' => $serieReporesReunion,
      'dataReportesGrupo' => $dataReportesGrupo,
      'serieReporesGrupo' => $serieReporesGrupo,
      'peticiones' => $peticiones,
    ]);
  }

  public function descargarCodigoQr(User $usuario)
  {
    $configuracion = Configuracion::find(1);

    $foto = url('') . Storage::url($configuracion->ruta_almacenamiento . '/img/foto-usuario' . '/' . $usuario->foto);

    if ($configuracion->version == 2) {
      $foto = $configuracion->ruta_almacenamiento . '/img/foto-usuario' . '/' . $usuario->foto;
    }

    $data = [
      'title' => 'domPDF in Laravel 10',
      'usuario' => $usuario,
      'configuracion' => $configuracion,
      'foto' => $foto,
    ];

    $pdf = PDF::loadView('contenido.paginas.usuario.codigoQr', $data);
    //return $pdf->stream();
    return $pdf->download('QR-' . $usuario->nombre(2) . '.pdf');
  }

  public function nuevo(FormularioUsuario $formulario)
  {
    $configuracion = Configuracion::find(1);
    if (!isset($formulario) || $formulario->es_formulario_exterior == false) {
      if (!auth()->check()) {
        return Redirect::to('pagina-no-encontrada');
      }
    }

    $layout = $formulario->es_formulario_exterior ? 'layouts/blankLayout' : 'layouts/layoutMaster';

    $fechaHoy = Carbon::now();
    $fechaDefault = Carbon::now()
      ->subYears($formulario->edad_minima)
      ->format('Y-m-d');

    $rolActivo =
      $formulario->es_formulario_exterior == false
      ? auth()->user()->roles()->wherePivot('activo', true)->first()
      : null;

    $pasos = PasoCrecimiento::orderBy('id', 'asc')->get();
    $continentes = Continente::orderBy('nombre', 'asc')->get();
    $paises = Pais::orderBy('nombre', 'asc')->get();
    $tiposDeVinculacion = TipoVinculacion::orderBy('nombre', 'asc')->get();
    $tiposDeVivienda = TipoVivienda::orderBy('nombre', 'asc')->get();
    $nivelesAcademicos = NivelAcademico::orderBy('nombre', 'asc')->get();
    $estadosNivelesAcademicos = EstadoNivelAcademico::orderBy('nombre', 'asc')->get();
    $tiposDeSangres = TipoSangre::orderBy('nombre', 'asc')->get();
    $profesiones = Profesion::orderBy('nombre', 'asc')->get();
    $ocupaciones = Ocupacion::orderBy('nombre', 'asc')->get();
    $sectoresEconomicos = SectorEconomico::orderBy('nombre', 'asc')->get();
    $sedes = Sede::orderBy('nombre', 'asc')->get();
    $tipoPeticiones = TipoPeticion::orderBy('orden', 'asc')->get();
    $tiposDeVinculacion = TipoVinculacion::orderBy('nombre', 'asc')->get();
    $tiposIdentificaciones = TipoIdentificacion::orderBy('nombre', 'asc')->get();
    $tiposDeEstadosCiviles = EstadoCivil::orderBy('nombre', 'asc')->get();

    $camposExtrasFormulario = $formulario
      ->camposExtras()
      ->orderBy('id')
      ->get();

    //$aux=Input::get('aux');
    $aux = null;

    return view('contenido.paginas.usuario.nuevo', [
      'configuracion' => $configuracion,
      'layout' => $layout,
      'formulario' => $formulario,
      'rolActivo' => $rolActivo,
      'continentes' => $continentes,
      'paises' => $paises,
      'tiposDeVivienda' => $tiposDeVivienda,
      'tiposDeVinculacion' => $tiposDeVinculacion,
      'nivelesAcademicos' => $nivelesAcademicos,
      'estadosNivelesAcademicos' => $estadosNivelesAcademicos,
      'tiposIdentificaciones' => $tiposIdentificaciones,
      'camposExtrasFormulario' => $camposExtrasFormulario,
      'profesiones' => $profesiones,
      'ocupaciones' => $ocupaciones,
      'sectoresEconomicos' => $sectoresEconomicos,
      'tiposDeSangres' => $tiposDeSangres,
      'tipoPeticiones' => $tipoPeticiones,
      'tiposDeEstadosCiviles' => $tiposDeEstadosCiviles,
      'sedes' => $sedes,
      'fechaDefault' => $fechaDefault,
      'fechaHoy' => $fechaHoy,
      'aux' => $aux,
    ]);
  }

  public function crear(Request $request, FormularioUsuario $formulario)
  {
    $configuracion = Configuracion::find(1);
    $rolActivo = null;

    if ($formulario->es_formulario_exterior == false && auth()->user()) {
      $rolActivo = auth()->user()->roles()->wherePivot('activo', true)->first();
    }
    $validacion = [];

    //fecha_nacimiento
    if ($formulario->visible_fecha_nacimiento == true) {
      $validarFechaNacimiento = $formulario->obligatorio_fecha_nacimiento ? ['date', 'required'] : ['date', 'nullable'] ;
      $validacion = array_merge($validacion, ['fecha_nacimiento' => $validarFechaNacimiento]);
    }

    // Tipo Identificacion
    if ($formulario->visible_tipo_identificacion) {
      $validarTipoIdentificacion = $formulario->obligatorio_tipo_identificacion ? ['numeric', 'required'] : ['numeric', 'nullable'];
      $validacion = array_merge($validacion, ['tipo_identificación' => $validarTipoIdentificacion]);
    }

    // Identificacion
    if ($formulario->visible_identificacion) {
      $validarIdentificacion = $formulario->obligatorio_identificacion ? ['string', 'required', 'max:255', 'unique:' . User::class . ',identificacion'] : ['string', 'nullable', 'max:255', 'unique:' . User::class . ',identificacion'];
      $validacion = array_merge($validacion, ['identificación' => $validarIdentificacion]);
    }

    // Email
    if ($formulario->visible_email) {
      $validarEmail = $formulario->obligatorio_email ? ['string','required','email', 'max:255', 'unique:' . User::class] : ['string', 'nullable' ,'email', 'max:255', 'unique:' . User::class];
      $validacion = array_merge($validacion, ['email' => $validarEmail]);
    }

    // primer_nombre
    if ($formulario->visible_primer_nombre) {
      $validarPrimerNombre =  $formulario->obligatorio_primer_nombre ? ['string', 'required', 'max:255'] : ['string', 'nullable', 'max:255'];
      $validacion = array_merge($validacion, ['primer_nombre' => $validarPrimerNombre]);
    }

    // segundo_nombre
    if ($formulario->visible_segundo_nombre) {
      $validarSegundoNombre = $formulario->obligatorio_segundo_nombre ? ['string', 'required', 'max:255'] : ['string', 'nullable', 'max:255'] ;
      $validacion = array_merge($validacion, ['segundo_nombre' => $validarSegundoNombre]);
    }

    // primer_apellido
    if ($formulario->visible_primer_apellido) {
      $validarPrimerApellido = $formulario->obligatorio_primer_apellido ? ['string', 'required', 'max:255'] : ['string', 'nullable', 'max:255'];
      $validacion = array_merge($validacion, ['primer_apellido' => $validarPrimerApellido]);
    }

    // segundo_apellido
    if ($formulario->visible_segundo_apellido) {
      $validarSegundoApellido = $formulario->obligatorio_segundo_apellido ? ['string', 'required','max:255'] : ['string', 'nullable','max:255'];
      $validacion = array_merge($validacion, ['segundo_apellido' => $validarSegundoApellido]);
    }

    // genero
    if ($formulario->visible_genero) {
      $validarGenero = $formulario->obligatorio_genero ? ['numeric', 'required'] : ['numeric','nullable'];
      $validacion = array_merge($validacion, ['genero' => $validarGenero]);
    }

    // estado_civil
    if ($formulario->visible_estado_civil) {
      $validarEstadoCivil = $formulario->obligatorio_estado_civil ? ['numeric', 'required'] : ['numeric', 'nullable'];
      $validacion = array_merge($validacion, ['estado_civil' => $validarEstadoCivil]);
    }

    // pais_nacimiento
    if ($formulario->visible_pais_nacimiento) {
      $validarPaisNacimiento = $formulario->obligatorio_pais_nacimiento ? ['numeric', 'required'] : ['numeric', 'nullable'];
      $validacion = array_merge($validacion, ['país' => $validarPaisNacimiento]);
    }

    // telefono_fijo
    if ($formulario->visible_telefono_fijo) {
      $validarTelefonoFijo = $formulario->obligatorio_telefono_fijo ? ['string', 'required'] : ['string', 'nullable'];
      $validacion = array_merge($validacion, ['teléfono_fijo' => $validarTelefonoFijo]);
    }

    // telefono_movil
    if ($formulario->visible_telefono_movil) {
      $validarTelefonoMovil = $formulario->obligatorio_telefono_movil ? ['string', 'required'] : ['string', 'nullable'];
      $validacion = array_merge($validacion, ['teléfono_móvil' => $validarTelefonoMovil]);
    }

    // telefono_otro
    if ($formulario->visible_telefono_otro) {
      $validarTelefonoOtro = $formulario->obligatorio_telefono_otro ? ['string', 'required'] : ['string', 'nullable'];
      $validacion = array_merge($validacion, ['teléfono_otro' => $validarTelefonoOtro]);
    }

    // vivienda_en_calidad_de
    if ($formulario->visible_vivienda_en_calidad_de) {
      $validarViviendaEnCalidadDe = $formulario->obligatorio_vivienda_en_calidad_de ? ['numeric', 'required'] : ['numeric', 'nullable'];
      $validacion = array_merge($validacion, ['tipo_de_vivienda' => $validarViviendaEnCalidadDe]);
    }

    // direccion
    if ($formulario->visible_direccion) {
      $validarDireccion = $formulario->obligatorio_direccion ? ['string', 'required'] : ['string', 'nullable'];
      $validacion = array_merge($validacion, ['dirección' => $validarDireccion]);
    }

    // nivel_academico
    if ($formulario->visible_nivel_academico) {
      $validarNivelAcademico = $formulario->obligatorio_nivel_academico ? ['numeric', 'required'] : ['numeric', 'nullable'];
      $validacion = array_merge($validacion, ['nivel_académico' => $validarNivelAcademico]);
    }

    // estado_nivel_academico
    if ($formulario->visible_estado_nivel_academico) {
      $validarEstadoNivelAcademico = $formulario->obligatorio_estado_nivel_academico ? ['numeric', 'required'] : ['numeric', 'nullable'];
      $validacion = array_merge($validacion, ['estado_nivel_académico' => $validarEstadoNivelAcademico]);
    }

    // profesion
    if ($formulario->visible_profesion) {
      $validarProfesion = $formulario->obligatorio_profesion ? ['numeric', 'required'] : ['numeric', 'nullable'];
      $validacion = array_merge($validacion, ['profesión' => $validarProfesion]);
    }

    // ocupacion
    if ($formulario->visible_ocupacion) {
      $validarOcupacion =   $formulario->obligatorio_ocupacion ? ['numeric', 'required'] :  ['numeric', 'nullable'];
      $validacion = array_merge($validacion, ['ocupación' => $validarOcupacion]);
    }

    //sector_economico
    if ($formulario->visible_sector_economico == true) {
      $validarSectorEconomico =   $formulario->obligatorio_sector_economico ? ['numeric', 'required'] :  ['numeric', 'nullable'];
      $validacion = array_merge($validacion, ['sector_económico' => $validarSectorEconomico]);
    }

    //tipo_sangre
    if ($formulario->visible_tipo_sangre == true) {
      $validarTipoSangre =   $formulario->obligatorio_tipo_sangre ? ['numeric', 'required'] :  ['numeric', 'nullable'];
      $validacion = array_merge($validacion, ['tipo_de_sangre' => $validarTipoSangre]);
    }

    //indicaciones_medicas
    if ($formulario->visible_indicaciones_medicas == true) {
      $validarIndicacionesMedicas = $formulario->obligatorio_indicaciones_medicas ? ['string', 'required'] : ['string', 'nullable'];
      $validacion = array_merge($validacion, ['indicaciones_médicas' => $validarIndicacionesMedicas]);
    }

    //sede
    if ($formulario->visible_sede == true) {
      $validarSede =   $formulario->obligatorio_sede ? ['numeric', 'required'] :  ['numeric', 'nullable'];
      $validacion = array_merge($validacion, ['sede' => $validarSede]);
    }

    //tipo_vinculacion
    if ($formulario->visible_tipo_vinculacion == true) {
      $validarTipoVinculacion =   $formulario->obligatorio_tipo_vinculacion ? ['numeric', 'required'] :  ['numeric', 'nullable'];
      $validacion = array_merge($validacion, ['tipo_vinculación' => $validarTipoVinculacion]);
    }

    //informacion_opcional
    if ($formulario->es_formulario_exterior == false) {
      if ($rolActivo->hasPermissionTo('personas.ver_campo_informacion_opcional')) {
        if ($formulario->visible_informacion_opcional == true) {
          $validarInformacionOpcional = $formulario->obligatorio_informacion_opcional ? ['string', 'required'] : ['string', 'nullable'];
          $validacion = array_merge($validacion, ['información_opcional' => $validarInformacionOpcional]);
        }
      }
    } else {
      if ($formulario->visible_informacion_opcional == true) {
        $validarInformacionOpcional = $formulario->obligatorio_informacion_opcional ? ['string', 'required'] : ['string', 'nullable'];
        $validacion = array_merge($validacion, ['información_opcional' => $validarInformacionOpcional]);
      }
    }

    //campo_reservado
    if ($formulario->es_formulario_exterior == false) {
      if ($rolActivo->hasPermissionTo('personas.ver_campo_reservado_visible')) {
        if ($formulario->visible_campo_reservado == true) {
          $validarCampoReservado = $formulario->obligatorio_campo_reservado ? ['string', 'required'] : ['string', 'nullable'];
          $formulario->obligatorio_campo_reservado ? array_push($validarCampoReservado, 'required') : '';
          $validacion = array_merge($validacion, ['campo_reservado' => $validarCampoReservado]);
        }
      }
    } else {
      if ($formulario->visible_campo_reservado == true) {
        $validarCampoReservado = $formulario->obligatorio_campo_reservado ? ['string', 'required'] : ['string', 'nullable'];
        $validacion = array_merge($validacion, ['campo_reservado' => $validarCampoReservado]);
      }
    }

    //archivo_a
    if ($formulario->visible_archivo_a == true) {
      $validarArchivoA =  $formulario->obligatorio_archivo_a ? ['file', 'required', File::types(['png', 'jpg', 'jpeg', 'gif', 'pdf'])->max('5mb')] : ['file', 'nullable', File::types(['png', 'jpg', 'jpeg', 'gif', 'pdf'])->max('5mb')];
      $validacion = array_merge($validacion, ['archivo_a' => $validarArchivoA]);
    }

    //archivo_b
    if ($formulario->visible_archivo_b == true) {
      $validarArchivoB =  $formulario->obligatorio_archivo_b ? ['file', 'required', File::types(['png', 'jpg', 'jpeg', 'gif', 'pdf'])->max('5mb')] : ['file', 'nullable', File::types(['png', 'jpg', 'jpeg', 'gif', 'pdf'])->max('5mb')];
      $formulario->obligatorio_archivo_b ? array_push($validarArchivoB, 'required') : '';
      $validacion = array_merge($validacion, ['archivo_b' => $validarArchivoB]);
    }

    //archivo_c
    if ($formulario->visible_archivo_c == true) {
      $validarArchivoC =  $formulario->obligatorio_archivo_c ? ['file', 'required', File::types(['png', 'jpg', 'jpeg', 'gif', 'pdf'])->max('5mb')] : ['file', 'nullable', File::types(['png', 'jpg', 'jpeg', 'gif', 'pdf'])->max('5mb')];
      $validacion = array_merge($validacion, ['archivo_c' => $validarArchivoC]);
    }

    //archivo_d
    if ($formulario->visible_archivo_d == true) {
      $validarArchivoD =  $formulario->obligatorio_archivo_d ? ['file', 'required', File::types(['png', 'jpg', 'jpeg', 'gif', 'pdf'])->max('5mb')] : ['file', 'nullable', File::types(['png', 'jpg', 'jpeg', 'gif', 'pdf'])->max('5mb')];
      $validacion = array_merge($validacion, ['archivo_d' => $validarArchivoD]);
    }

    //tipo_identificacion_acudiente
    if ($formulario->visible_tipo_identificacion_acudiente == true) {
      $validarTipoIdentificacionAcudiente = $formulario->obligatorio_tipo_identificacion_acudiente ? ['numeric', 'required'] : ['numeric', 'nullable'];
      $validacion = array_merge($validacion, [
        'tipo_de_identificación_del_acudiente' => $validarTipoIdentificacionAcudiente,
      ]);
    }

    //identificacion_acudiente
    if ($formulario->visible_identificacion_acudiente == true) {
      $validarIdentificacionAcudiente = $formulario->obligatorio_identificacion_acudiente ? ['string', 'required'] : ['string', 'nullable'];
      $validacion = array_merge($validacion, ['identificación_del_acudiente' => $validarIdentificacionAcudiente]);
    }

    //nombre_acudiente
    if ($formulario->visible_nombre_acudiente == true) {
      $validarNombreAcudiente = $formulario->obligatorio_nombre_acudiente ? ['string', 'required'] : ['string', 'nullable'];
      $validacion = array_merge($validacion, ['nombre_del_acudiente' => $validarNombreAcudiente]);
    }

    //telefono_acudiente
    if ($formulario->visible_telefono_acudiente == true) {
      $validarTelefonoAcudiente = $formulario->obligatorio_telefono_acudiente ? ['string', 'required'] : ['string', 'nullable'];
      $validacion = array_merge($validacion, ['teléfono_del_acudiente' => $validarTelefonoAcudiente]);
    }

    /// seccion comprobacion campos extras
    if ($formulario->visible_seccion_campos_extra == true) {
      $camposExtraFormulario = $formulario->camposExtras;

      foreach ($camposExtraFormulario as $campoExtra) {
        $validarCampoExtra = [];
        $campoExtra->pivot->required ? array_push($validarCampoExtra, 'required') : '';
        $validacion = array_merge($validacion, [$campoExtra->class_id => $validarCampoExtra]);
      }
    }

    // Validacion de datos
    $request->validate($validacion);

    $usuario = new User();

    // Foto default
    $usuario->foto = $request->genero == 0 ? 'default-m.png' : 'default-f.png';
    $usuario->password = $configuracion->identificacion_obligatoria
      ? Hash::make($request->identificación)
      : Hash::make('123456');
    $usuario->activo = 1;

    $tipoUsuarioDefault = TipoUsuario::where('default', true)
      ->select('id')
      ->first();
    $usuario->tipo_usuario_id = $tipoUsuarioDefault->id;
    $usuario->email = rand(1000000, 9999999) . '@correopordefecto.com';

    $usuario->fecha_nacimiento = $request->fecha_nacimiento;
    $usuario->tipo_identificacion_id = $request->tipo_identificación;
    $usuario->identificacion = $request->identificación;
    $usuario->primer_nombre = $request->primer_nombre;
    $usuario->segundo_nombre = $request->segundo_nombre;
    $usuario->primer_apellido = $request->primer_apellido;
    $usuario->segundo_apellido = $request->segundo_apellido;
    $usuario->genero = $request->genero;
    $usuario->estado_civil_id = $request->estado_civil;
    $usuario->pais_id = $request->país;
    $usuario->telefono_fijo = $request->teléfono_fijo;
    $usuario->telefono_movil = $request->teléfono_móvil;
    $usuario->telefono_otro = $request->teléfono_otro;
    $usuario->tipo_vivienda_id = $request->tipo_de_vivienda;
    $usuario->direccion = $request->dirección;
    $usuario->barrio_id = $request->barrio_id;
    $usuario->barrio_auxiliar = $request->barrio_auxiliar;
    $usuario->nivel_academico_id = $request->nivel_académico;
    $usuario->estado_nivel_academico_id = $request->estado_nivel_académico;
    $usuario->profesion_id = $request->profesión;
    $usuario->ocupacion_id = $request->ocupación;
    $usuario->sector_economico_id = $request->sector_económico;
    $usuario->tipo_sangre_id = $request->tipo_de_sangre;
    $usuario->indicaciones_medicas = $request->indicaciones_médicas;
    $usuario->sede_id = $request->sede;
    $usuario->tipo_vinculacion_id = $request->tipo_vinculación;
    $usuario->informacion_opcional = $request->información_opcional;
    $usuario->campo_reservado = $request->campo_reservado;
    $usuario->tipo_identificacion_acudiente_id = $request->tipo_de_identificación_del_acudiente;
    $usuario->identificacion_acudiente = $request->identificación_del_acudiente;
    $usuario->nombre_acudiente = $request->nombre_del_acudiente;
    $usuario->telefono_acudiente = $request->teléfono_del_acudiente;

    if ($usuario->save()) {
      // Email
      $email = empty($request->email) ? $usuario->id . '@' . 'correopordefecto.com' : mb_strtolower($request->email);

      $usuario->email = $email;

      // Foto
      if ($formulario->visible_foto == true) {
        if ($request->foto) {
          if ($configuracion->version == 1) {
            $path = public_path('storage/' . $configuracion->ruta_almacenamiento . '/img/foto-usuario/');
            !is_dir($path) && mkdir($path, 0777, true);

            $imagenPartes = explode(';base64,', $request->foto);
            $imagenBase64 = base64_decode($imagenPartes[1]);
            $nombreFoto = 'asistente-' . $usuario->id . '.jpg';
            $imagenPath = $path . $nombreFoto;
            file_put_contents($imagenPath, $imagenBase64);
            $usuario->foto = $nombreFoto;
          } else {
            /*
            $s3 = AWS::get('s3');
            $s3->putObject(array(
              'Bucket'     => $_ENV['aws_bucket'],
              'Key'        => $_ENV['aws_carpeta']."/fotos/asistente-".$asistente->id.".jpg",
              'SourceFile' => "img/temp/".Input::get('foto-hide'),
            ));*/
          }
        }
      }
      // fin Foto

      //documentos adjuntos
      $path = public_path('storage/' . $configuracion->ruta_almacenamiento . '/archivos' . '/');
      !is_dir($path) && mkdir($path, 0777, true);

      // archivo_a
      if ($formulario->visible_archivo_a == true && $request->hasFile('archivo_a')) {
        $extension = $request->archivo_a->extension();
        $nombreArchivoA = $formulario->label_archivo_a
          ? $formulario->label_archivo_a . $usuario->id . '.' . $extension
          : 'archivo-a' . $usuario->id . '.' . $extension;
        if ($configuracion->version == 1) {
          $request->archivo_a->storeAs(
            $configuracion->ruta_almacenamiento . '/archivos' . '/',
            $nombreArchivoA,
            'public'
          );
        } elseif ($configuracion->version == 2) {
          /*
            $s3 = AWS::get('s3');
            $s3->putObject(array(
            'Bucket'     => $_ENV['aws_bucket'],
            'Key'        => $_ENV['aws_carpeta']."/archivos"."/".$nombreArchivoA,
            'SourceFile' => "img/temp/archivo-a-temp-".$asistente->id.".".$extension,
            ));*/
        }
        $usuario->archivo_a = $nombreArchivoA;
        $usuario->save();
      }

      // archivo_b
      if ($formulario->visible_archivo_b == true && $request->hasFile('archivo_b')) {
        $extension = $request->archivo_b->extension();
        $nombreArchivoB = $formulario->label_archivo_b
          ? $formulario->label_archivo_b . $usuario->id . '.' . $extension
          : 'archivo-b' . $usuario->id . '.' . $extension;
        if ($configuracion->version == 1) {
          $request->archivo_b->storeAs(
            $configuracion->ruta_almacenamiento . '/archivos' . '/',
            $nombreArchivoB,
            'public'
          );
        } elseif ($configuracion->version == 2) {
          /*
            $s3 = AWS::get('s3');
            $s3->putObject(array(
            'Bucket'     => $_ENV['aws_bucket'],
            'Key'        => $_ENV['aws_carpeta']."/archivos"."/".$nombreArchivoB,
            'SourceFile' => "img/temp/archivo-a-temp-".$asistente->id.".".$extension,
            ));*/
        }
        $usuario->archivo_b = $nombreArchivoB;
        $usuario->save();
      }

      // archivo_c
      if ($formulario->visible_archivo_c == true && $request->hasFile('archivo_c')) {
        $extension = $request->archivo_c->extension();
        $nombreArchivoC = 'archivo-c' . $usuario->id . '.' . $extension;
        $nombreArchivoC = $formulario->label_archivo_c
          ? $formulario->label_archivo_c . $usuario->id . '.' . $extension
          : 'archivo-c' . $usuario->id . '.' . $extension;
        if ($configuracion->version == 1) {
          $request->archivo_c->storeAs(
            $configuracion->ruta_almacenamiento . '/archivos' . '/',
            $nombreArchivoC,
            'public'
          );
        } elseif ($configuracion->version == 2) {
          /*
            $s3 = AWS::get('s3');
            $s3->putObject(array(
            'Bucket'     => $_ENV['aws_bucket'],
            'Key'        => $_ENV['aws_carpeta']."/archivos"."/".$nombreArchivoC,
            'SourceFile' => "img/temp/archivo-a-temp-".$asistente->id.".".$extension,
            ));*/
        }
        $usuario->archivo_c = $nombreArchivoC;
        $usuario->save();
      }

      // archivo_d
      if ($formulario->visible_archivo_d == true && $request->hasFile('archivo_d')) {
        $extension = $request->archivo_d->extension();
        $nombreArchivoD = $formulario->label_archivo_d
          ? $formulario->label_archivo_d . $usuario->id . '.' . $extension
          : 'archivo-d' . $usuario->id . '.' . $extension;
        if ($configuracion->version == 1) {
          $request->archivo_d->storeAs(
            $configuracion->ruta_almacenamiento . '/archivos' . '/',
            $nombreArchivoD,
            'public'
          );
        } elseif ($configuracion->version == 2) {
          /*
            $s3 = AWS::get('s3');
            $s3->putObject(array(
            'Bucket'     => $_ENV['aws_bucket'],
            'Key'        => $_ENV['aws_carpeta']."/archivos"."/".$nombreArchivoD,
            'SourceFile' => "img/temp/archivo-a-temp-".$asistente->id.".".$extension,
            ));*/
        }
        $usuario->archivo_d = $nombreArchivoD;
        $usuario->save();
      }
      //fin documentos adjuntos

      // Creo todos los Pasos de Crecimiento por defecto
      $pasos_crecimiento = PasoCrecimiento::all();
      foreach ($pasos_crecimiento as $paso) {
        $usuario->pasosCrecimiento()->attach($paso->id, ['estado_id' => '1']);
      }

      if ($formulario->es_formulario_exterior == true) {
        $formulario->pendiente_por_aprobacion == true
          ? ($usuario->esta_aprobado = false)
          : ($usuario->esta_aprobado = true);
      } else {
        if (isset($rolActivo) && $rolActivo->hasPermissionTo('personas.privilegio_crear_asistentes_aprobados')) {
          $usuario->esta_aprobado = true;
        } else {
          $formulario->pendiente_por_aprobacion == true
            ? ($usuario->esta_aprobado = false)
            : ($usuario->esta_aprobado = true);
        }
      }

      // asignacion al grupo automatica
      $grupo = Grupo::find($request->grupo);
      if ($grupo) {
        $usuario->cambiarGrupo($grupo->id);
      }

      // Pasos crecimiento
      if ($formulario->visible_pasos_crecimiento == true) {
        $reporteGrupo = ReporteGrupo::find($request->reporte_grupo);
        $tipoGrupo = TipoGrupo::where('id', '=', $grupo->tipo_grupo_id)->first();
        $pasosCrecimientoReporte = $tipoGrupo->pasosCrecimiento()->get();

        foreach ($pasosCrecimientoReporte as $paso) {
          if ($request['paso_crecimiento_' . $paso->id]) {
            $pasoDeCrecimientoEncontrado = $usuario
              ->pasosCrecimiento()
              ->where('pasos_crecimiento.id', $paso->id)
              ->first();
            $pasoDeCrecimientoEncontrado->pivot->estado = $paso->pivot->estado_por_defecto;
            $pasoDeCrecimientoEncontrado->pivot->fecha = $reporteGrupo->fecha;
            $pasoDeCrecimientoEncontrado->pivot->save();
          }
        }
      }
      // Fin Pasos crecimiento

      /// esta sección es para el guardado de los campos extra ($('#ministerio_asociado_principal option:selected').val());
      if ($configuracion->visible_seccion_campos_extra == true) {
        $camposExtraFormulario = $formulario->camposExtras;
        foreach ($camposExtraFormulario as $campoExtra) {
          if ($campoExtra->visible == true) {
            if ($campoExtra->tipo_de_campo != 4) {
              $usuario
                ->camposExtras()
                ->attach($campoExtra->id, ['valor' => ucwords(mb_strtolower($request[$campoExtra->class_id]))]);
            } else {
              $usuario
                ->camposExtras()
                ->attach($campoExtra->id, ['valor' => json_encode($request[$campoExtra->class_id])]);
            }
          }
        }
      }

      // Peticiones
      if ($request->tipo_peticion_id && $request->descripcion_peticion) {
        $fechaPeticion = Carbon::now()->format('Y-m-d');

        $peticion = new Peticion();
        $peticion->autor_creacion_id = auth()->user() ? auth()->user()->id : $usuario->id;
        $peticion->user_id = $usuario->id;
        $peticion->estado = 1;
        $peticion->descripcion = $request->descripcion_peticion;
        $peticion->tipo_peticion_id = $request->tipo_peticion_id;
        $peticion->fecha = $fechaPeticion;
        $peticion->pais_id = $usuario->pais_id;
        $peticion->save();

        // Enviar el correo
        $mensaje = $peticion->tipoPeticion->mensaje_parte_1;
        if ($usuario->email != '' && $mensaje != '') {
          $key = config('variables.biblia_key');
          $arrContextOptions = [
            'ssl' => [
              'verify_peer' => false,
              'verify_peer_name' => false,
            ],
          ];

          try {
            $jsonVersiculos = $peticion->tipoPeticion->json_versiculos;
            if ($jsonVersiculos != '') {
              $jsonVersiculos = json_decode($jsonVersiculos);
              $cantidadItems = count($jsonVersiculos);
              $random = rand(1, $cantidadItems);
              $respuestaText = file_get_contents(
                'https://api.biblia.com/v1/bible/content/RVR60.txt?passage=' .
                  $jsonVersiculos[$random - 1]->cita .
                  '&key=' .
                  $key .
                  '&style=neVersePerLineFullReference&culture=es',
                false,
                stream_context_create($arrContextOptions)
              );
              $mensaje .=
                '<I>' . $respuestaText . '</I> <B>(' . $jsonVersiculos[$random - 1]->titulo . ', RVR60)</B></p>';
            }
          } catch (Exception $e) {
          }

          $mensaje .= $peticion->tipoPeticion->mensaje_parte_2;

          $mailData = new stdClass();
          $mailData->subject = 'Petición';
          $mailData->nombre = $usuario->nombre(3);
          $mailData->mensaje = $mensaje;

          if ($peticion->tipoPeticion->banner_email != '') {
            $mailData->banner =
              $configuracion->version == 1
              ? Storage::url(
                $configuracion->ruta_almacenamiento . '/img/email/' . $peticion->tipoPeticion->banner_email
              )
              : Storage::url(
                $configuracion->ruta_almacenamiento . '/img/email/' . $peticion->tipoPeticion->banner_email
              );
          }

          //Mail::to($usuario->email)->send(new DefaultMail($mailData));
          Mail::to('softjuancarlos@gmail.com')->send(new DefaultMail($mailData));

        }
      }

      //Asignamos el tipo ROL al usuario
      $usuario->roles()->attach($usuario->tipoUsuario->id_rol_dependiente, [
        'activo' => true,
        'dependiente' => true,
        'model_type' => 'App\Models\User',
      ]);

      //nuevo codigo para hacer seguimiento o bitacora de quien creó a la persona
      if (auth()->user() && $rolActivo) {
        // si existe un usuario logueado, realizo la vitacora con el
        $usuario->usuario_creacion_id = auth()->user()->id;
        $usuario->rol_de_creacion_id = $rolActivo->id;
      } else {
        // De lo contrario coloco los valores por defecto
        $usuario->usuario_creacion_id = null;
        $usuario->rol_de_creacion_id = null;
      }

      // Asigna la sede de quién lo crea en caso de no asignarle sede
      if (!isset($usuario->sede_id)) {
        $usuario->asignarSede();
      }

      $usuario->save();

      // Enviar correo de bienvenida
      if($configuracion->enviar_correo_bienvenida_nuevo_asistente==TRUE)
      {
        $mailData = new stdClass();
        $mailData->subject = $configuracion->titulo_mensaje_bienvenida;
        $mailData->nombre = $usuario->nombre(3);
        $mailData->mensaje = $configuracion->mensaje_bienvenida;

          if ($configuracion->banner_mensaje_bienvenida) {
            $mailData->banner =
              $configuracion->version == 1
              ? Storage::url(
                $configuracion->ruta_almacenamiento . '/img/email/bienvenida_usuario.png'
              )
              : Storage::url(
                $configuracion->ruta_almacenamiento . '/img/email/bienvenida_usuario.png'
              );
          }

          // Mail::to($usuario->email)->send(new DefaultMail($mailData));
          Mail::to('softjuancarlos@gmail.com')->send(new DefaultMail($mailData));
      }

      //seccion guadado ajax
      if ($formulario->guardado_ajax == true) {
        $nombreCompleto = $usuario->nombre(4);
        $respuesta = [
          'id' => $usuario->id,
          'nombre' => $nombreCompleto,
          'msnPersonaResponsable' =>
          'La persona <b>' .
            $nombreCompleto .
            '</b> con identificación <b>' .
            $usuario->identificacion .
            '</b> se vinculará como responsable del menor.',
          'identificacion' => $usuario->identificacion,
          'tipoPersona' => 'asistente',
          'mensaje' => 'La persona <b>' . $nombreCompleto . '</b> fue creada con éxito.',
        ];

        return json_encode($respuesta);
      } else {
        $nombre_completo = $usuario->nombre(4);

        if ($formulario->redirect != '') {
          if ($formulario->redirect == '/') {
            return Redirect::to($formulario->redirect)->with([
              'status' => 'ok_new_asistente',
            ]);
          } elseif ($formulario->redirect == 'peticiones-online') {
            return Redirect::to('/peticiones/formulario-peticiones/' . $asistente->id . '/asistente')->with([
              'status' => 'ok_new_asistente',
              'mensaje' => 'La persona <b>' . $nombre_completo . '</b> fue creada con éxito.',
            ]);
          } elseif ($formulario->redirect == 'donaciones-online') {
            return Redirect::to('/ofrendas/formulario-donaciones/' . $asistente->id . '/asistente')->with([
              'status' => 'ok_new_asistente',
              'mensaje' => 'La persona <b>' . $nombre_completo . '</b> fue creada con éxito.',
            ]);
          } elseif ($formulario->redirect == 'actividades') {
            $actividad_id = Input::get('aux');
            return Redirect::to(
              '/actividades/perfil/' . $actividad_id . '/' . 'website/' . $asistente->id . '/asistente'
            )->with([
              'status' => 'ok_new_asistente',
              'mensaje' => 'La persona <b>' . $nombre_completo . '</b> fue creada con éxito.',
            ]);
          } else {
            return Redirect::to($formulario->redirect . '' . $asistente->id)->with([
              'status' => 'ok_new_asistente',
              'mensaje' => "La persona <b>$nombre_completo</b> fue creada con éxito.",
            ]);
          }
        } else {
          return back()->with('success', "La persona <b>$nombre_completo</b> fue creada con éxito.");
        }
      }
    }

    return 'error, no se guardo';
  }

  public function modificar(FormularioUsuario $formulario, User $usuario)
  {
    $configuracion = Configuracion::find(1);

    if (!isset($formulario) || $formulario->es_formulario_exterior == false) {
      if (!auth()->check()) {
        return Redirect::to('pagina-no-encontrada');
      }
    }

    $layout = $formulario->es_formulario_exterior ? 'layouts/blankLayout' : 'layouts/layoutMaster';

    $fechaHoy = Carbon::now();
    $fechaDefault = Carbon::now()
      ->subYears($formulario->edad_minima)
      ->format('Y-m-d');

    $rolActivo =
      $formulario->es_formulario_exterior == false
      ? auth()->user()->roles()->wherePivot('activo', true)->first()
      : null;

    $pasos = PasoCrecimiento::orderBy('id', 'asc')->get();
    $continentes = Continente::orderBy('nombre', 'asc')->get();
    $paises = Pais::orderBy('nombre', 'asc')->get();
    $tiposDeVinculacion = TipoVinculacion::orderBy('nombre', 'asc')->get();
    $tiposDeVivienda = TipoVivienda::orderBy('nombre', 'asc')->get();
    $nivelesAcademicos = NivelAcademico::orderBy('nombre', 'asc')->get();
    $estadosNivelesAcademicos = EstadoNivelAcademico::orderBy('nombre', 'asc')->get();
    $tiposDeSangres = TipoSangre::orderBy('nombre', 'asc')->get();
    $profesiones = Profesion::orderBy('nombre', 'asc')->get();
    $ocupaciones = Ocupacion::orderBy('nombre', 'asc')->get();
    $sectoresEconomicos = SectorEconomico::orderBy('nombre', 'asc')->get();
    $sedes = Sede::orderBy('nombre', 'asc')->get();
    $tipoPeticiones = TipoPeticion::orderBy('orden', 'asc')->get();
    $tiposDeVinculacion = TipoVinculacion::orderBy('nombre', 'asc')->get();
    $tiposIdentificaciones = TipoIdentificacion::orderBy('nombre', 'asc')->get();
    $tiposDeEstadosCiviles = EstadoCivil::orderBy('nombre', 'asc')->get();
    $camposExtrasFormulario = $formulario
      ->camposExtras()
      ->orderBy('id')
      ->get();

    //$aux=Input::get('aux');
    $aux = null;

    return view('contenido.paginas.usuario.modificar', [
      'configuracion' => $configuracion,
      'layout' => $layout,
      'formulario' => $formulario,
      'rolActivo' => $rolActivo,
      'continentes' => $continentes,
      'paises' => $paises,
      'tiposDeVivienda' => $tiposDeVivienda,
      'tiposDeVinculacion' => $tiposDeVinculacion,
      'nivelesAcademicos' => $nivelesAcademicos,
      'estadosNivelesAcademicos' => $estadosNivelesAcademicos,
      'tiposIdentificaciones' => $tiposIdentificaciones,
      'camposExtrasFormulario' => $camposExtrasFormulario,
      'profesiones' => $profesiones,
      'ocupaciones' => $ocupaciones,
      'sectoresEconomicos' => $sectoresEconomicos,
      'tiposDeSangres' => $tiposDeSangres,
      'tipoPeticiones' => $tipoPeticiones,
      'tiposDeEstadosCiviles' => $tiposDeEstadosCiviles,
      'sedes' => $sedes,
      'fechaDefault' => $fechaDefault,
      'fechaHoy' => $fechaHoy,
      'aux' => $aux,
      'usuario' => $usuario,
    ]);
  }

  public function editar(Request $request, FormularioUsuario $formulario, User $usuario)
  {
    $configuracion = Configuracion::find(1);
    $rolActivo = null;

    if ($formulario->es_formulario_exterior == false && auth()->user()) {
      $rolActivo = auth()->user()->roles()->wherePivot('activo', true)->first();
    }
    $validacion = [];

    //fecha_nacimiento
    if ($formulario->visible_fecha_nacimiento == true) {
      $validarFechaNacimiento = $formulario->obligatorio_fecha_nacimiento ? ['date', 'required'] : ['date', 'nullable'] ;
      $validacion = array_merge($validacion, ['fecha_nacimiento' => $validarFechaNacimiento]);
    }

    // Tipo Identificacion
    if ($formulario->visible_tipo_identificacion) {
      $validarTipoIdentificacion = $formulario->obligatorio_tipo_identificacion ? ['numeric', 'required'] : ['numeric', 'nullable'];
      $validacion = array_merge($validacion, ['tipo_identificación' => $validarTipoIdentificacion]);
    }

    // Identificacion
    if ($formulario->visible_identificacion) {
      $validarIdentificacion = $formulario->obligatorio_identificacion ? ['string', 'required', 'max:255', Rule::unique('users', 'identificacion')->ignore($usuario->id)] : ['string', 'nullable', 'max:255', Rule::unique('users', 'identificacion')->ignore($usuario->id)];
      $validacion = array_merge($validacion, ['identificación' => $validarIdentificacion]);
    }

    // Email
    if ($formulario->visible_email) {
      $validarEmail = $formulario->obligatorio_email ? ['string', 'required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($usuario->id)] : ['string', 'nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($usuario->id)];
      $validacion = array_merge($validacion, ['email' => $validarEmail]);
    }

    // primer_nombre
    if ($formulario->visible_primer_nombre) {
      $validarPrimerNombre = $formulario->obligatorio_primer_nombre ?  ['string', 'required', 'max:255'] : ['string', 'nullable', 'max:255'];
      $validacion = array_merge($validacion, ['primer_nombre' => $validarPrimerNombre]);
    }

    // segundo_nombre
    if ($formulario->visible_segundo_nombre) {
      $validarSegundoNombre = $formulario->obligatorio_segundo_nombre ?  ['string', 'required', 'max:255'] : ['string', 'nullable', 'max:255'];
      $validacion = array_merge($validacion, ['segundo_nombre' => $validarSegundoNombre]);
    }

    // primer_apellido
    if ($formulario->visible_primer_apellido) {
      $validarPrimerApellido = $formulario->obligatorio_primer_apellido ?  ['string', 'required', 'max:255'] : ['string', 'nullable', 'max:255'];
      $validacion = array_merge($validacion, ['primer_apellido' => $validarPrimerApellido]);
    }

    // segundo_apellido
    if ($formulario->visible_segundo_apellido) {
      $validarSegundoApellido = $formulario->obligatorio_segundo_apellido ?  ['string', 'required', 'max:255'] : ['string', 'nullable', 'max:255'];
      $validacion = array_merge($validacion, ['segundo_apellido' => $validarSegundoApellido]);
    }

    // genero
    if ($formulario->visible_genero) {
      $validarGenero = $formulario->obligatorio_genero ? ['numeric', 'required'] : ['numeric', 'nullable'];
      $validacion = array_merge($validacion, ['genero' => $validarGenero]);
    }

    // estado_civil
    if ($formulario->visible_estado_civil) {
      $validarEstadoCivil = $formulario->obligatorio_estado_civil ? ['numeric', 'required'] : ['numeric', 'nullable'];
      $validacion = array_merge($validacion, ['estado_civil' => $validarEstadoCivil]);
    }

    // pais_nacimiento
    if ($formulario->visible_pais_nacimiento) {
      $validarPaisNacimiento = $formulario->obligatorio_pais_nacimiento ? ['numeric', 'required'] : ['numeric', 'nullable'];
      $validacion = array_merge($validacion, ['país' => $validarPaisNacimiento]);
    }

    // telefono_fijo
    if ($formulario->visible_telefono_fijo) {
      $validarTelefonoFijo = $formulario->obligatorio_telefono_fijo ? ['string', 'required'] : ['string', 'nullable'];
      $validacion = array_merge($validacion, ['teléfono_fijo' => $validarTelefonoFijo]);
    }

    // telefono_movil
    if ($formulario->visible_telefono_movil) {
      $validarTelefonoMovil = $formulario->obligatorio_telefono_movil ? ['string', 'required'] : ['string', 'nullable'];
      $validacion = array_merge($validacion, ['teléfono_móvil' => $validarTelefonoMovil]);
    }

    // telefono_otro
    if ($formulario->visible_telefono_otro) {
      $validarTelefonoOtro = $formulario->obligatorio_telefono_otro ? ['string', 'required'] : ['string', 'nullable'];
      $validacion = array_merge($validacion, ['teléfono_otro' => $validarTelefonoOtro]);
    }

    // vivienda_en_calidad_de
    if ($formulario->visible_vivienda_en_calidad_de) {
      $validarViviendaEnCalidadDe = $formulario->obligatorio_vivienda_en_calidad_de ? ['numeric', 'required'] : ['numeric', 'nullable'];
      $validacion = array_merge($validacion, ['tipo_de_vivienda' => $validarViviendaEnCalidadDe]);
    }

    // direccion
    if ($formulario->visible_direccion) {
      $validarDireccion = $formulario->obligatorio_direccion ? ['string', 'required'] : ['string', 'nullable'];
      $validacion = array_merge($validacion, ['dirección' => $validarDireccion]);
    }

    // nivel_academico
    if ($formulario->visible_nivel_academico) {
      $validarNivelAcademico = $formulario->obligatorio_nivel_academico ? ['numeric', 'required'] : ['numeric', 'nullable'];
      $validacion = array_merge($validacion, ['nivel_académico' => $validarNivelAcademico]);
    }

    // estado_nivel_academico
    if ($formulario->visible_estado_nivel_academico) {
      $validarEstadoNivelAcademico = $formulario->obligatorio_estado_nivel_academico ? ['numeric', 'required'] : ['numeric', 'nullable'];
      $validacion = array_merge($validacion, ['estado_nivel_académico' => $validarEstadoNivelAcademico]);
    }

    // profesion
    if ($formulario->visible_profesion) {
      $validarProfesion = $formulario->obligatorio_profesion ? ['numeric', 'required'] : ['numeric', 'nullable'];
      $validacion = array_merge($validacion, ['profesión' => $validarProfesion]);
    }

    // ocupacion
    if ($formulario->visible_ocupacion) {
      $validarOcupacion = $formulario->obligatorio_ocupacion ? ['numeric', 'required'] : ['numeric', 'nullable'];
      $validacion = array_merge($validacion, ['ocupación' => $validarOcupacion]);
    }

    //sector_economico
    if ($formulario->visible_sector_economico == true) {
      $validarSectorEconomico = $formulario->obligatorio_sector_economico ? ['numeric', 'required'] : ['numeric', 'nullable'];
      $validacion = array_merge($validacion, ['sector_económico' => $validarSectorEconomico]);
    }

    //tipo_sangre
    if ($formulario->visible_tipo_sangre == true) {
      $validarTipoSangre = $formulario->obligatorio_tipo_sangre ? ['numeric', 'required'] : ['numeric', 'nullable'];
      $validacion = array_merge($validacion, ['tipo_de_sangre' => $validarTipoSangre]);
    }

    //indicaciones_medicas
    if ($formulario->visible_indicaciones_medicas == true) {
      $validarIndicacionesMedicas = $formulario->obligatorio_indicaciones_medicas ? ['string', 'required'] : ['string', 'nullable'];
      $validacion = array_merge($validacion, ['indicaciones_médicas' => $validarIndicacionesMedicas]);
    }

    //sede
    if ($formulario->visible_sede == true) {
      $validarSede = $formulario->obligatorio_sede ? ['numeric', 'required'] : ['numeric', 'nullable'];
      $validacion = array_merge($validacion, ['sede' => $validarSede]);
    }

    //tipo_vinculacion
    if ($formulario->visible_tipo_vinculacion == true) {
      $validarTipoVinculacion = $formulario->obligatorio_tipo_vinculacion ? ['numeric', 'required'] : ['numeric', 'nullable'];
      $validacion = array_merge($validacion, ['tipo_vinculación' => $validarTipoVinculacion]);
    }

    //informacion_opcional
    if ($formulario->es_formulario_exterior == false) {
      if ($rolActivo->hasPermissionTo('personas.ver_campo_informacion_opcional')) {
        if ($formulario->visible_informacion_opcional == true) {
          $validarInformacionOpcional = $formulario->obligatorio_informacion_opcional ? ['string', 'required'] : ['string', 'nullable'];
          $validacion = array_merge($validacion, ['información_opcional' => $validarInformacionOpcional]);
        }
      }
    } else {
      if ($formulario->visible_informacion_opcional == true) {
        $validarInformacionOpcional = $formulario->obligatorio_informacion_opcional ? ['string', 'required'] : ['string', 'nullable'];
        $validacion = array_merge($validacion, ['información_opcional' => $validarInformacionOpcional]);
      }
    }

    //campo_reservado
    if ($formulario->es_formulario_exterior == false) {
      if ($rolActivo->hasPermissionTo('personas.ver_campo_reservado_visible')) {
        if ($formulario->visible_campo_reservado == true) {
          $validarCampoReservado = $formulario->obligatorio_campo_reservado ? ['string', 'required'] : ['string', 'nullable'];
          $validacion = array_merge($validacion, ['campo_reservado' => $validarCampoReservado]);
        }
      }
    } else {
      if ($formulario->visible_campo_reservado == true) {
        $validarCampoReservado = $formulario->obligatorio_campo_reservado ? ['string', 'required'] : ['string', 'nullable'];
        $validacion = array_merge($validacion, ['campo_reservado' => $validarCampoReservado]);
      }
    }

    //archivo_a
    if ($formulario->visible_archivo_a == true) {
      if ($request->hasFile('archivo_a') || $usuario->archivo_a == '') {
        $validarArchivoA =  $formulario->obligatorio_archivo_a ? ['file', 'required', File::types(['png', 'jpg', 'jpeg', 'gif', 'pdf'])->max('5mb')] : ['file', 'nullable', File::types(['png', 'jpg', 'jpeg', 'gif', 'pdf'])->max('5mb')];
        $validacion = array_merge($validacion, ['archivo_a' => $validarArchivoA]);
      }
    }

    //archivo_b
    if ($formulario->visible_archivo_b == true) {
      if ($request->hasFile('archivo_b') || $usuario->archivo_b == '') {
        $validarArchivoB =  $formulario->obligatorio_archivo_b ? ['file', 'required', File::types(['png', 'jpg', 'jpeg', 'gif', 'pdf'])->max('5mb')] : ['file', 'nullable', File::types(['png', 'jpg', 'jpeg', 'gif', 'pdf'])->max('5mb')];
        $validacion = array_merge($validacion, ['archivo_b' => $validarArchivoB]);
      }
    }

    //archivo_c
    if ($formulario->visible_archivo_c == true) {
      if ($request->hasFile('archivo_c') || $usuario->archivo_c == '') {
        $validarArchivoC =  $formulario->obligatorio_archivo_c ? ['file', 'required', File::types(['png', 'jpg', 'jpeg', 'gif', 'pdf'])->max('5mb')] : ['file', 'nullable', File::types(['png', 'jpg', 'jpeg', 'gif', 'pdf'])->max('5mb')];
        $validacion = array_merge($validacion, ['archivo_c' => $validarArchivoC]);
      }
    }

    //archivo_d
    if ($formulario->visible_archivo_d == true) {
      if ($request->hasFile('archivo_d') || $usuario->archivo_d == '') {
        $validarArchivoD =  $formulario->obligatorio_archivo_d ? ['file', 'required', File::types(['png', 'jpg', 'jpeg', 'gif', 'pdf'])->max('5mb')] : ['file', 'nullable', File::types(['png', 'jpg', 'jpeg', 'gif', 'pdf'])->max('5mb')];
        $validacion = array_merge($validacion, ['archivo_d' => $validarArchivoD]);
      }
    }

    //tipo_identificacion_acudiente
    if ($formulario->visible_tipo_identificacion_acudiente == true) {
      $validarTipoIdentificacionAcudiente = $formulario->obligatorio_tipo_identificacion_acudiente ? ['numeric', 'required'] : ['numeric', 'nullable'];
      $validacion = array_merge($validacion, [
        'tipo_de_identificación_del_acudiente' => $validarTipoIdentificacionAcudiente,
      ]);
    }

    //identificacion_acudiente
    if ($formulario->visible_identificacion_acudiente == true) {
      $validarIdentificacionAcudiente = $formulario->obligatorio_identificacion_acudiente ? ['string', 'required'] : ['string', 'nullable'];
      $validacion = array_merge($validacion, ['identificación_del_acudiente' => $validarIdentificacionAcudiente]);
    }

    //nombre_acudiente
    if ($formulario->visible_nombre_acudiente == true) {
      $validarNombreAcudiente = $formulario->obligatorio_nombre_acudiente ? ['string', 'required'] : ['string', 'nullable'];
      $validacion = array_merge($validacion, ['nombre_del_acudiente' => $validarNombreAcudiente]);
    }

    //telefono_acudiente
    if ($formulario->visible_telefono_acudiente == true) {
      $validarTelefonoAcudiente = $formulario->obligatorio_telefono_acudiente ? ['string', 'required'] : ['string', 'nullable'];
      $validacion = array_merge($validacion, ['teléfono_del_acudiente' => $validarTelefonoAcudiente]);
    }

    /// seccion comprobacion campos extras
    if ($formulario->visible_seccion_campos_extra == true) {
      $camposExtraFormulario = $formulario->camposExtras;

      foreach ($camposExtraFormulario as $campoExtra) {
        $validarCampoExtra = [];
        $campoExtra->pivot->required ? array_push($validarCampoExtra, 'required') : '';
        $validacion = array_merge($validacion, [$campoExtra->class_id => $validarCampoExtra]);
      }
    }

    // Validacion de datos
    $request->validate($validacion);

    if ($formulario->reprocesar_desactualizados == true) {
      if ($usuario->identificador_menor_desactualizado == true) {
        $usuario->esta_aprobado = false;
      }
    } else {
      // Solo el usuario que tiene este privilegio una vez actualizado los datos, el estado de ese usuario para aprobado
      if ($formulario->es_formulario_exterior == false) {
        if (isset($rolActivo) && $rolActivo->hasPermissionTo('personas.lista_asistentes_todos')) {
          $usuario->esta_aprobado = true;
          $usuario->identificador_menor_desactualizado = true;
        }
      }
    }

    if ($usuario->save()) {
      //$usuario->foto= $request->genero == 0 ? "default-m.png" : "default-f.png";
      $usuario->fecha_actualizacion = Carbon::now()->format('Y-m-d');
      $usuario->email = strtolower($request->email);
      $usuario->fecha_nacimiento = $request->fecha_nacimiento;
      $usuario->tipo_identificacion_id = $request->tipo_identificación;
      $usuario->identificacion = $request->identificación;
      $usuario->primer_nombre = $request->primer_nombre;
      $usuario->segundo_nombre = $request->segundo_nombre;
      $usuario->primer_apellido = $request->primer_apellido;
      $usuario->segundo_apellido = $request->segundo_apellido;
      $usuario->genero = $request->genero;
      $usuario->estado_civil_id = $request->estado_civil;
      $usuario->pais_id = $request->país;
      $usuario->telefono_fijo = $request->teléfono_fijo;
      $usuario->telefono_movil = $request->teléfono_móvil;
      $usuario->telefono_otro = $request->teléfono_otro;
      $usuario->tipo_vivienda_id = $request->tipo_de_vivienda;
      $usuario->direccion = $request->dirección;
      $usuario->barrio_id = $request->barrio_id;
      $usuario->barrio_auxiliar = $request->barrio_auxiliar;
      $usuario->nivel_academico_id = $request->nivel_académico;
      $usuario->estado_nivel_academico_id = $request->estado_nivel_académico;
      $usuario->profesion_id = $request->profesión;
      $usuario->ocupacion_id = $request->ocupación;
      $usuario->sector_economico_id = $request->sector_económico;
      $usuario->tipo_sangre_id = $request->tipo_de_sangre;
      $usuario->indicaciones_medicas = $request->indicaciones_médicas;
      $usuario->sede_id = $request->sede;
      $usuario->tipo_vinculacion_id = $request->tipo_vinculación;
      $usuario->informacion_opcional = $request->información_opcional;
      $usuario->campo_reservado = $request->campo_reservado;
      $usuario->tipo_identificacion_acudiente_id = $request->tipo_de_identificación_del_acudiente;
      $usuario->identificacion_acudiente = $request->identificación_del_acudiente;
      $usuario->nombre_acudiente = $request->nombre_del_acudiente;
      $usuario->telefono_acudiente = $request->teléfono_del_acudiente;

      // Foto
      if ($formulario->visible_foto == true) {
        if ($request->foto) {
          if ($configuracion->version == 1) {
            $path = public_path('storage/' . $configuracion->ruta_almacenamiento . '/img/foto-usuario/');
            !is_dir($path) && mkdir($path, 0777, true);

            $imagenPartes = explode(';base64,', $request->foto);
            $imagenBase64 = base64_decode($imagenPartes[1]);
            $nombreFoto = 'asistente-' . $usuario->id . '.jpg';
            $imagenPath = $path . $nombreFoto;
            file_put_contents($imagenPath, $imagenBase64);
            $usuario->foto = $nombreFoto;
          } else {
            /*
            $s3 = AWS::get('s3');
            $s3->putObject(array(
              'Bucket'     => $_ENV['aws_bucket'],
              'Key'        => $_ENV['aws_carpeta']."/fotos/asistente-".$asistente->id.".jpg",
              'SourceFile' => "img/temp/".Input::get('foto-hide'),
            ));*/
          }
        }
      }
      // fin Foto

      /// esta sección es para el guardado de los campos extra ($('#ministerio_asociado_principal option:selected').val());
      if ($configuracion->visible_seccion_campos_extra == true) {
        $camposExtraFormulario = $formulario->camposExtras;
        foreach ($camposExtraFormulario as $campoExtra) {
          if ($campoExtra->visible == true) {
            if ($campoExtra->tipo_de_campo != 4) {
              $usuarioCampoExtra = $usuario
                ->camposExtras()
                ->where('campo_extra_id', '=', $campoExtra->id)
                ->first();
              if ($usuarioCampoExtra) {
                $usuarioCampoExtra->pivot->valor = ucwords(mb_strtolower($request[$campoExtra->class_id]));
                $usuarioCampoExtra->pivot->save();
              } else {
                $usuario
                  ->camposExtras()
                  ->attach($campoExtra->id, ['valor' => ucwords(mb_strtolower($request[$campoExtra->class_id]))]);
              }
            } else {
              $usuarioCampoExtra = $usuario
                ->camposExtras()
                ->where('campo_extra_id', '=', $campoExtra->id)
                ->first();
              if ($usuarioCampoExtra) {
                $usuarioCampoExtra->pivot->valor = json_encode($request[$campoExtra->class_id]);
                $usuarioCampoExtra->pivot->save();
              } else {
                $usuario
                  ->camposExtras()
                  ->attach($campoExtra->id, ['valor' => json_encode($request[$campoExtra->class_id])]);
              }
            }
          }
        }
      }

      //documentos adjuntos
      $path = public_path('storage/' . $configuracion->ruta_almacenamiento . '/archivos' . '/');
      !is_dir($path) && mkdir($path, 0777, true);

      // archivo_a
      if ($formulario->visible_archivo_a == true && $request->hasFile('archivo_a')) {
        $extension = $request->archivo_a->extension();
        $nombreArchivoA = $formulario->label_archivo_a
          ? $formulario->label_archivo_a . $usuario->id . '.' . $extension
          : 'archivo-a' . $usuario->id . '.' . $extension;
        if ($configuracion->version == 1) {
          // elimino el archivo actual
          Storage::delete('public/' . $configuracion->ruta_almacenamiento . '/archivos' . '/' . $usuario->archivo_a);

          $request->archivo_a->storeAs(
            $configuracion->ruta_almacenamiento . '/archivos' . '/',
            $nombreArchivoA,
            'public'
          );
        } elseif ($configuracion->version == 2) {
          /*
            $s3 = AWS::get('s3');
            $s3->putObject(array(
            'Bucket'     => $_ENV['aws_bucket'],
            'Key'        => $_ENV['aws_carpeta']."/archivos"."/".$nombreArchivoA,
            'SourceFile' => "img/temp/archivo-a-temp-".$asistente->id.".".$extension,
            ));*/
        }
        $usuario->archivo_a = $nombreArchivoA;
        $usuario->save();
      }

      // archivo_b
      if ($formulario->visible_archivo_b == true && $request->hasFile('archivo_b')) {
        $extension = $request->archivo_b->extension();
        $nombreArchivoB = $formulario->label_archivo_b
          ? $formulario->label_archivo_b . $usuario->id . '.' . $extension
          : 'archivo-b' . $usuario->id . '.' . $extension;
        if ($configuracion->version == 1) {
          // elimino el archivo actual
          Storage::delete('public/' . $configuracion->ruta_almacenamiento . '/archivos' . '/' . $usuario->archivo_b);

          $request->archivo_b->storeAs(
            $configuracion->ruta_almacenamiento . '/archivos' . '/',
            $nombreArchivoB,
            'public'
          );
        } elseif ($configuracion->version == 2) {
          /*
            $s3 = AWS::get('s3');
            $s3->putObject(array(
            'Bucket'     => $_ENV['aws_bucket'],
            'Key'        => $_ENV['aws_carpeta']."/archivos"."/".$nombreArchivoB,
            'SourceFile' => "img/temp/archivo-a-temp-".$asistente->id.".".$extension,
            ));*/
        }
        $usuario->archivo_b = $nombreArchivoB;
        $usuario->save();
      }

      // archivo_c
      if ($formulario->visible_archivo_c == true && $request->hasFile('archivo_c')) {
        $extension = $request->archivo_c->extension();
        $nombreArchivoC = 'archivo-c' . $usuario->id . '.' . $extension;
        $nombreArchivoC = $formulario->label_archivo_c
          ? $formulario->label_archivo_c . $usuario->id . '.' . $extension
          : 'archivo-c' . $usuario->id . '.' . $extension;
        if ($configuracion->version == 1) {
          // elimino el archivo actual
          Storage::delete('public/' . $configuracion->ruta_almacenamiento . '/archivos' . '/' . $usuario->archivo_c);

          $request->archivo_c->storeAs(
            $configuracion->ruta_almacenamiento . '/archivos' . '/',
            $nombreArchivoC,
            'public'
          );
        } elseif ($configuracion->version == 2) {
          /*
            $s3 = AWS::get('s3');
            $s3->putObject(array(
            'Bucket'     => $_ENV['aws_bucket'],
            'Key'        => $_ENV['aws_carpeta']."/archivos"."/".$nombreArchivoC,
            'SourceFile' => "img/temp/archivo-a-temp-".$asistente->id.".".$extension,
            ));*/
        }
        $usuario->archivo_c = $nombreArchivoC;
        $usuario->save();
      }

      // archivo_d
      if ($formulario->visible_archivo_d == true && $request->hasFile('archivo_d')) {
        $extension = $request->archivo_d->extension();
        $nombreArchivoD = $formulario->label_archivo_d
          ? $formulario->label_archivo_d . $usuario->id . '.' . $extension
          : 'archivo-d' . $usuario->id . '.' . $extension;
        if ($configuracion->version == 1) {
          // elimino el archivo actual
          Storage::delete('public/' . $configuracion->ruta_almacenamiento . '/archivos' . '/' . $usuario->archivo_d);

          $request->archivo_d->storeAs(
            $configuracion->ruta_almacenamiento . '/archivos' . '/',
            $nombreArchivoD,
            'public'
          );
        } elseif ($configuracion->version == 2) {
          /*
            $s3 = AWS::get('s3');
            $s3->putObject(array(
            'Bucket'     => $_ENV['aws_bucket'],
            'Key'        => $_ENV['aws_carpeta']."/archivos"."/".$nombreArchivoD,
            'SourceFile' => "img/temp/archivo-a-temp-".$asistente->id.".".$extension,
            ));*/
        }
        $usuario->archivo_d = $nombreArchivoD;
        $usuario->save();
      }
      //fin documentos adjuntos

      $usuario->save();

      if ($formulario->guardado_ajax == true) {
        $nombre_completo = $usuario->nombre(3);
        $respuesta = [
          'id' => $usuario->id,
          'nombre' => $nombre_completo,
          'msnPersonaResponsable' =>
          'La persona <b>' .
            $nombre_completo .
            '</b> con identificación <b>' .
            $usuario->identificacion .
            '</b> se vinculará como responsable del menor.',
          'identificacion' => $usuario->identificacion,
          'tipoPersona' => 'asistente',
          'mensaje' => 'La persona <b>' . $nombre_completo . '</b> fue creada con éxito.',
        ];

        return json_encode($respuesta);
      } else {
        $nombre_completo = $usuario->nombre(3);
        if ($formulario->redirect != '') {
          if ($formulario->redirect == '/') {
            return Redirect::to('/inicio')->with([
              'status' => 'ok_update_asistente',
              'mensaje' => 'La persona <b>' . $nombre_completo . '</b> fue actualizada con éxito.',
            ]);
          } elseif ($formulario->redirect == 'peticiones-online') {
            return Redirect::to('/peticiones/formulario-peticiones/' . $usuario->id . '/asistente')->with([
              'status' => 'ok_update_asistente',
              'mensaje' => 'La persona <b>' . $nombre_completo . '</b> fue actualizada con éxito.',
            ]);
          } elseif ($formulario->redirect == 'donaciones-online') {
            return Redirect::to('/ofrendas/formulario-donaciones/' . $usuario->id . '/asistente')->with([
              'status' => 'ok_update_asistente',
              'mensaje' => 'La persona <b>' . $nombre_completo . '</b> fue actualizada con éxito.',
            ]);
          } elseif ($formulario->redirect == 'actividades') {
            $actividad_id = $request->aux;
            return Redirect::to(
              '/actividades/perfil/' . $actividad_id . '/' . 'website/' . $usuario->id . '/asistente'
            )->with([
              'status' => 'ok_update_asistente',
              'mensaje' => 'La persona <b>' . $nombre_completo . '</b> fue actualizada con éxito.',
            ]);
          } elseif ($formulario->redirect == 'listado') {
            return Redirect::to('/asistentes/lista')->with([
              'status' => 'ok_update_asistente',
              'mensaje' => 'La persona <b>' . $nombre_completo . '</b> fue actualizada con éxito.',
            ]);
          } else {
            return Redirect::to($formulario->redirect . '' . $usuario->id)->with([
              'status' => 'ok_update_asistente',
              'mensaje' => 'La persona <b>' . $nombre_completo . '</b> fue actualizada con éxito.',
            ]);
          }
        } else {
          return back()->with('success', "La persona <b>$nombre_completo</b> fue actualizada con éxito.");
        }
      }
    }
  }

  public function informacionCongregacional(?int $formulario = 0, User $usuario, int $tipoUsuarioSugeridoId=0)
  {
    $tipoUsuarioSugerido = null;
    if($tipoUsuarioSugeridoId)
    {
      $tipoUsuarioSugerido = TipoUsuario::find($tipoUsuarioSugeridoId);
    }

    $configuracion = Configuracion::find(1);
    if (!isset($usuario)) {
      return Redirect::to('pagina-no-encontrada');
    }

    $rolActivo = auth()->user()->roles()->wherePivot('activo', true)->first();

    $edad = $usuario->edad();

    if ($formulario == 0) {
      $formulario = $rolActivo
        ->formularios()
        ->where('privilegio', '=', 'opcion_modificar_asistente')
        ->where('edad_minima', '<=', $edad)
        ->where('edad_maxima', '>=', $edad)
        ->first();
    }

    $idsGruposPadres = $usuario->gruposDondeAsiste->pluck('id')->toArray();

    $cantidadTiposTipoUsuariosBloqueados = $rolActivo->tipoUsuariosBloqueados()->count();
    if ($cantidadTiposTipoUsuariosBloqueados > 0) {
      $arrayTiposUsuariosBloqueados = $rolActivo
        ->tipoUsuariosBloqueados()
        ->select('tipo_usuarios.id')
        ->pluck('id')
        ->toArray();

      if (in_array($usuario->tipoUsuario->id, $arrayTiposUsuariosBloqueados)) {
        $tiposUsuarios = null;
      } else {
        $tiposUsuarios = TipoUsuario::whereNotIn('id', $arrayTiposUsuariosBloqueados)
          ->orderBy('orden', 'asc')
          ->where('visible', true)
          ->get();
      }
    } else {
      $tiposUsuarios = TipoUsuario::orderBy('orden', 'asc')
        ->where('visible', true)
        ->get();
    }

    if ($rolActivo->hasPermissionTo('personas.privilegio_gestionar_todos_los_pasos_de_crecimiento'))
    {
      $pasosDeCrecimiento = PasoCrecimiento::orderBy('updated_at', 'asc')
      ->select('id', 'nombre','seccion_paso_crecimiento_id')
      ->get();
    }else{
      $pasosDeCrecimiento =  $rolActivo->pasosCrecimiento()->orderBy('updated_at', 'asc')->get();
    }


    $seccionesIds = $pasosDeCrecimiento->pluck('seccion_paso_crecimiento_id')->toArray();
    $seccionesPasoDeCrecimiento = SeccionPasoCrecimiento::whereIn('id',$seccionesIds)->orderBy('orden', 'asc')->get();

    $seccionesPasoDeCrecimiento->map(function ($seccion) use ($usuario, $pasosDeCrecimiento) {

      $pasosDeLaSeccion = $pasosDeCrecimiento->where('seccion_paso_crecimiento_id', $seccion->id);
      $pasosDeLaSeccion->map(function ($paso) use ($usuario) {
        $pasoUsuario = CrecimientoUsuario::where('user_id', $usuario->id)
          ->where('paso_crecimiento_id', $paso->id)
          ->first();
        $paso->clase_color = 'danger';
        $paso->estado_fecha = null;
        $paso->estado_paso = 1;
        $paso->estado_nombre = 'No realizado';
        $paso->detalle_paso = '';
        $paso->bandera = 'default';
        if ($pasoUsuario) {
          $paso->clase_color = $pasoUsuario->estado->color;
          $paso->estado_fecha = $pasoUsuario->fecha;
          $paso->estado_paso = $pasoUsuario->estado_id;
          $paso->estado_nombre = $pasoUsuario->estado->nombre;
          $paso->detalle_paso = $pasoUsuario->detalle;
          $paso->bandera = 'si existe';
        }
      });

      $seccion->pasos = $pasosDeLaSeccion;

    });

    $rolesNoDependientes = Role::where('dependiente', 'FALSE')
      ->orderBy('name', 'asc')
      ->select('id', 'name')
      ->get();
    $rolesNoDependientes->map(function ($rol) use ($usuario) {
      $rolUsuario = $usuario
        ->roles()
        ->where('roles.id', $rol->id)
        ->first();
      $rol->tiene = $rolUsuario ? 'si' : 'no';
    });

    $estados = EstadoPasoCrecimientoUsuario::get();
    $gruposDondeAsisteIds = $usuario->gruposDondeAsiste->pluck('id')->toArray();

    //return $tipoUsuarioSugerido;

    return view('contenido.paginas.usuario.informacion-congregacional', [
      'formulario' => $formulario,
      'usuario' => $usuario,
      'idsGruposPadres' => $idsGruposPadres,
      'tiposUsuarios' => $tiposUsuarios,
      'configuracion' => $configuracion,
      'rolesNoDependientes' => $rolesNoDependientes,
      'rolActivo' => $rolActivo,
    //  'pasosDeCrecimiento' => $pasosDeCrecimiento,
      'estados' => $estados,
      'gruposDondeAsisteIds' => $gruposDondeAsisteIds,
      'tipoUsuarioSugerido' => $tipoUsuarioSugerido,
      'seccionesPasoDeCrecimiento' => $seccionesPasoDeCrecimiento
    ]);
  }

  public function actualizarInformacionCongregacional(Request $request, User $usuario)
  {

    /*return redirect()
    ->route('usuario.informacionCongregacional', ['formulario' => 0 ,'usuario' => $usuario, 'tipoUsuarioSugerido' => 5])
    ->with('success', 'La información congregacional <b>' . $usuario->nombre(3) . '</b> se actualizo con éxito.');
*/
    $rolActivo = auth()->user()->roles()->wherePivot('activo', true)->first();
    $automatizacionTipoUsuarios = [];
    $sugerencia = false;

    if (!isset($usuario)) {
      return Redirect::to('pagina-no-encontrada');
    }

    // Asigno los grupos
    if ($rolActivo->hasPermissionTo('personas.panel_asignar_grupo_al_asistente'))
    {

      $idsGrupos = json_decode($request->inputGrupos);
      $grupos = Grupo::leftJoin('tipo_grupos', 'grupos.tipo_grupo_id', '=', 'tipo_grupos.id')
      ->whereIn('grupos.id', $idsGrupos)
      ->select('grupos.id','grupos.nombre','grupos.tipo_grupo_id', 'tipo_grupos.automatizacion_tipo_usuario_id', 'tipo_grupos.nombre as nameTipo')
      ->get();

      //Validar si privilegio_asignar_asistente_todo_tipo_asistente_a_un_grupo
      if(!$rolActivo->hasPermissionTo('grupos.privilegio_asignar_asistente_todo_tipo_asistente_a_un_grupo'))
      {
        foreach($grupos as $grupo)
        {
          $tipoGrupo=$grupo->tipoGrupo;
          $usuarioPermintidos= $tipoGrupo->tipoUsuariosPermitidos()
          ->wherePivot('para_asistentes', '=', TRUE)
          ->where('tipo_usuario_id','=',$request->tipo_usuario)
          ->count();

          if($usuarioPermintidos<=0)
          {
            return back()->with('danger', 'No es posible asignar un <b>'.$usuario->tipoUsuario->nombre.'</b> a un grupo tipo <b>'.$grupo->tipoGrupo->nombre.'</b>. Por favor, consulte a su administrador.');
          }
        }
      }

      $gruposActualesIds = $usuario->gruposDondeAsiste()->select('grupos.id')->pluck('grupos.id')->toArray();
      $gruposNuevos = array_diff($idsGrupos, $gruposActualesIds);
      $automatizacionTipoUsuarios+= $grupos->whereIn('id',$gruposNuevos)->whereNotNull('automatizacion_tipo_usuario_id')->pluck('automatizacion_tipo_usuario_id')->toArray();
      $usuario->gruposDondeAsiste()->sync($idsGrupos);

      //asigno la sede al usuario del ultimo grupo agregado
      if($idsGrupos && count($idsGrupos) > 0)
        $usuario->asignarSede(end($idsGrupos));

      if($request->bitacora)
      {
        foreach (json_decode($request->bitacora) as $informe)
        {

          // (1) "Asignación de líder" (2) "Asignación de asistente" (3) "Desvinculacion de líder" (4) "Desvinculacion del asistente"
          $tipoInforme = $informe->bitacora == 'desvinculacion' ? 4 : 2;

          InformeGrupo::create([
            'user_id' => $usuario->id,
            'grupo_id' => $informe->grupoId,
            'observaciones' => $informe->observacion,
            'tipo_asignacion_id' => $informe->motivoId,
            'tipo_informe' => $tipoInforme,
            'user_autor_asignacion' => auth()->user()->id
          ]);

          if($informe->bitacora == 'desvinculacion' && $informe->desvincularServicios == 'si')
          {
            //Desvinculo los servicios
            $servidores = ServidorGrupo::where('user_id',$usuario->id)->where('grupo_id',$informe->grupoId)->get();
            foreach ($servidores as $servidor)
            {
              $servidor->tipoServicioGrupo()->detach();
              $servidor->delete();
            }
          }
        }
      }
    }

    // asigna los procesos de crecimiento
    if ($rolActivo->hasPermissionTo('personas.panel_procesos_asistente') && $rolActivo->hasPermissionTo('personas.editar_procesos_asistente')) {
      $pasosCrecimiento = PasoCrecimiento::all();
      $puntaje = 0;
      $idTipoUsuario = null;

      foreach ($pasosCrecimiento as $paso) {
        // Busco si tiene pasos de crecimiento
        $pasoActual = $usuario
          ->pasosCrecimiento()
          ->where('pasos_crecimiento.id', $paso->id)
          ->first();

        if ($pasoActual) {
          if ($pasoActual->pivot->estado_id != $request['estado_paso_' . $paso->id] && $request['estado_paso_' . $paso->id]) {
            $pasoActual->pivot->estado_id = $request['estado_paso_' . $paso->id];

            /* Proceso de automatización */
            $autorizacionPasoCrecimiento = $pasoActual
              ->automatizaciones()
              ->where('estado_paso_crecimiento', $request['estado_paso_' . $paso->id])
              ->first();

            if ($autorizacionPasoCrecimiento) {
              $automatizacionTipoUsuarios[] = $autorizacionPasoCrecimiento->tipo_usuario_a_modificar;
              /*
              $tipoUsuarioTemporal = TipoUsuario::find($autorizacionPasoCrecimiento->tipo_usuario_a_modificar);

              if ($tipoUsuarioTemporal->puntaje > $puntaje) {
                $puntaje = $tipoUsuarioTemporal->puntaje;
                $idTipoUsuario = $tipoUsuarioTemporal->id;
              }*/
            }
            /* Fin proceso de automatización */
          }

          if ($request['estado_paso_' . $paso->id] != 1) {
            $pasoActual->pivot->fecha = $request['fecha_paso_' . $paso->id];
          } else {
            $pasoActual->pivot->fecha = null;
          }
          $pasoActual->pivot->detalle = $request['detalle_paso_' . $paso->id] ? $request['detalle_paso_' . $paso->id] : '';
          $pasoActual->pivot->save();
        } else {
          // Si el usuario no tiene el paso, lo creo
          if ($request['estado_paso_' . $paso->id] == 1 || !$request['estado_paso_' . $paso->id]) {

            $usuario->pasosCrecimiento()->attach($paso->id, [
              'estado_id' => $request['estado_paso_' . $paso->id] ? $request['estado_paso_' . $paso->id] : 1,
              'detalle' => $request['detalle_paso_' . $paso->id] ? $request['detalle_paso_' . $paso->id] : '',
            ]);
          } else {
            $usuario->pasosCrecimiento()->attach($paso->id, [
              'estado_id' => $request['estado_paso_' . $paso->id],
              'fecha' => $request['fecha_paso_' . $paso->id],
              'detalle' => $request['detalle_paso_' . $paso->id],
            ]);
          }
          $pasoActual = $usuario
            ->pasosCrecimiento()
            ->where('pasos_crecimiento.id', $paso->id)
            ->first();

          /* Proceso de automatización */
          $autorizacionPasoCrecimiento = $pasoActual
            ->automatizaciones()
            ->where('estado_paso_crecimiento', $request['estado_paso_' . $paso->id])
            ->first();

          if ($autorizacionPasoCrecimiento) {
            $automatizacionTipoUsuarios[] = $autorizacionPasoCrecimiento->tipo_usuario_a_modificar;
            /*$tipoUsuarioTemporal = TipoUsuario::find($autorizacionPasoCrecimiento->tipo_usuario_a_modificar);

            if ($tipoUsuarioTemporal->puntaje > $puntaje) {
              $puntaje = $tipoUsuarioTemporal->puntaje;
              $idTipoUsuario = $tipoUsuarioTemporal->id;
            }*/
          }
          /* Fin proceso de automatización */
        }
      }

      /*
      // Actualizo de manera automatica
      if (isset($idTipoUsuario) && $puntaje > $usuario->tipoUsuario->puntaje) {
        $usuario->tipo_usuario_id = $idTipoUsuario;
        $usuario->save();

        //Además, le cambio a la usuario el rol dependiente
        $tipoUsuarioNuevo = TipoUsuario::find($idTipoUsuario);
        $rolDependiente = $usuario
          ->roles()
          ->wherePivot('dependiente', '=', true)
          ->first();

        if ($rolDependiente && $tipoUsuarioNuevo->id_rol_dependiente != $rolDependiente->id) {
          $usuario
            ->roles()
            ->wherePivot('dependiente', '=', true)
            ->detach();
          $usuario->roles()->attach($tipoUsuarioNuevo->id_rol_dependiente, [
            'activo' => $rolDependiente->pivot->activo,
            'dependiente' => true,
            'model_type' => 'App\Models\User',
          ]);
        }
      }*/
    }

    // asignar los roles dependientes == false, es decir los independientes
    if ($rolActivo->hasPermissionTo('personas.ver_panel_asignar_tipo_usuario')) {
      $rolesNoDependientes = Role::orderBy('id', 'asc')
        ->where('dependiente', '=', 'FALSE')
        ->get();

      foreach ($rolesNoDependientes as $rol) {
        $registros = $usuario
          ->roles()
          ->where('id', $rol->id)
          ->get();

        if ($registros->count()>0) {
          if (!$request->get('rolIndependiente'.$rol->id)) {
            $usuario->removeRole($rol);
          }
        } else {
          if ($request->get('rolIndependiente'.$rol->id)) {
            $usuario->roles()->attach($rol->id, ['dependiente' => 'false', 'activo' => 'false', 'model_type' => 'App\Models\User']);
          }
        }
      }

      $cantidadActivos = $usuario
        ->roles()
        ->wherePivot('activo', '=', true)
        ->count();
      if ($cantidadActivos < 1) {
        // Con esto nos aseguramosd que tenga minimo un rol dependiente activo
        $rolDependiente = $usuario
          ->roles()
          ->wherePivot('dependiente', '=', true)
          ->first();

        if ($rolDependiente) {
          $rolDependiente->pivot->activo = true;
          $rolDependiente->pivot->dependiente = true;
          $rolDependiente->pivot->save();
        } else {
          $rol = $usuario->roles()->first();
          if ($rol) {
            $rol->pivot->activo = true;
            $rol->pivot->save();
          }
        }
      }
    }

    // Asigno tipo usuario
    if ($rolActivo->hasPermissionTo('personas.panel_tipos_asistente') && $rolActivo->hasPermissionTo('personas.editar_tipos_asistente'))
    {
      if ($request->tipo_usuario) {
        $usuario->tipo_usuario_id = $request->tipo_usuario;
        $usuario->save();
      }
    }

    $tipoUsuarioAutomatico = TipoUsuario::whereIn('id', $automatizacionTipoUsuarios)->orderBy('puntaje', 'DESC')->first();
    //return $tipoUsuarioAutomatico;
    $tipoUsuarioActual = TipoUsuario::find($usuario->tipo_usuario_id);

    if($tipoUsuarioAutomatico && $tipoUsuarioAutomatico->puntaje > $tipoUsuarioActual->puntaje)
    {
      $sugerencia = true;
    }

    //Además, le cambio a la usuario el rol dependiente
    $rolDependiente = $usuario
      ->roles()
      ->wherePivot('dependiente', '=', true)
      ->first();

    if ($rolDependiente && $tipoUsuarioActual->id_rol_dependiente != $rolDependiente->id) {
      $usuario->roles()->attach($tipoUsuarioActual->id_rol_dependiente, ['activo' => $rolDependiente->pivot->activo,'dependiente' => true,'model_type' => 'App\Models\User']);
      $usuario->removeRole($rolDependiente);
    }

    $usuario->save();

    if ($sugerencia) {
      return redirect()
      ->route('usuario.informacionCongregacional', ['formulario' => 0 ,'usuario' => $usuario, 'tipoUsuarioSugerido' => $tipoUsuarioAutomatico->id])
      ->with('success', 'La información congregacional <b>' . $usuario->nombre(3) . '</b> se actualizo con éxito.');
    } else {
      return redirect()
      ->route('usuario.informacionCongregacional', ['formulario' => 0 ,'usuario' => $usuario])
      ->with('success', 'La información congregacional <b>' . $usuario->nombre(3) . '</b> se actualizo con éxito.');
    }
  }

  public function geoAsignacion(?int $formulario = 0, User $usuario)
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
    if (!isset($usuario)) {
      return Redirect::to('pagina-no-encontrada');
    }

    $rolActivo = auth()->user()->roles()->wherePivot('activo', true)->first();
    $edad = $usuario->edad();

    if ($formulario == 0) {
      $formulario = $rolActivo
        ->formularios()
        ->where('privilegio', '=', 'opcion_modificar_asistente')
        ->where('edad_minima', '<=', $edad)
        ->where('edad_maxima', '>=', $edad)
        ->first();
    }

    if ($rolActivo->hasPermissionTo('personas.mostrar_todos_los_grupos_en_geoasignacion')) {
      $grupos = Grupo::with("tipoGrupo")->select("id", "nombre", "latitud", "longitud", "direccion", "tipo_grupo_id")->get();
    } else {
      $grupos = $usuario->gruposMinisterio()->select("id", "nombre", "latitud", "longitud", "direccion", "tipo_grupo_id")->get();
    }

    return view('contenido.paginas.usuario.geo-asignacion', [
      'rolActivo' => $rolActivo,
      'usuario' => $usuario,
      'formulario' => $formulario,
      'configuracion' => $configuracion,
      //'ubicacionInicial' => $ubicacionInicial,
      'longitudInicial' => $longitudInicial,
      'latitudInicial' => $latitudInicial,
      'grupos' => $grupos
    ]);
  }

  public function cambiarContrasenaDefault( User $usuario )
  {
    $configuracion=Configuracion::find(1); //dentificacion_obligatoria
    $nuevaContrasena = $configuracion->identificacion_obligatoria ? $usuario->identificacion : "123456";

    $usuario->password = Hash::make($nuevaContrasena);
    $usuario->save();

    $mailData = new stdClass();
    $mailData->subject = 'Cambio de contraseña';
    $mailData->nombre = $usuario->nombre(3);
    $mailData->mensaje = 'Su contraseña ha sido cambiada satisfactoriamente por parte del administrador, su nueva contraseña es:
    <br> <center><p class="centrar-text" style="font:18px/1.25em '.'Century Gothic'.',Arial,Helvetica;color:#939393"><b>Nueva clave: '.$nuevaContrasena.'</b></p></center>    ';

    try{
      Mail::to($usuario->email)->send(new DefaultMail($mailData));
    }catch (Exception $e) {

    }

    return back()->with('success', "La contraseña de <b>".$usuario->nombre(3)."</b> fue cambiada con éxito a <b>".$nuevaContrasena."</b>.");
  }

  public function cambiarContrasena( Request $request, User $usuario )
  {
    //Este cambio de contraseña es que usa el admin para cambiar a los demas usuarios
    $request->validate([
      'password' => 'required|confirmed|min:5',
    ]);

    $usuario->password = Hash::make($request->password);
    $usuario->save();

    $mailData = new stdClass();
    $mailData->subject = 'Cambio de contraseña';
    $mailData->nombre = $usuario->nombre(3);
    $mailData->mensaje = 'Su contraseña ha sido cambiada satisfactoriamente por parte del administrador, su nueva contraseña es:
    <br> <center><p class="centrar-text" style="font:18px/1.25em '.'Century Gothic'.',Arial,Helvetica;color:#939393"><b>Nueva clave: '.$request->password.'</b></p></center>    ';

    try{
      Mail::to($usuario->email)->send(new DefaultMail($mailData));
    }catch (Exception $e) {

    }

    return back()->with('success', "La contraseña de <b>".$usuario->nombre(3)."</b> fue cambiada con éxito.");
  }

}
