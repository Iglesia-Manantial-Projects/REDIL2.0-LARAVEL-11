@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Usuarios')

<!-- Page -->
@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-profile.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
@endsection

@section('page-script')

<script>
  $(document).ready(function() {
    $('.select2BusquedaAvanzada').select2({
      dropdownParent: $('#modalBusquedaAvanzada')
    });
  });

  // Eso arragle un error en los select2 con el scroll cuando esta dentro de un modal
  $('#modalBusquedaAvanzada').on('scroll', function(event) {
    $(this).find(".select2BusquedaAvanzada").each(function() {
      $(this).select2({
        dropdownParent: $(this).parent()
      });
    });
  });

  $(document).ready(function() {
    $('.select2GeneradorExcel').select2({
      dropdownParent: $('#modalGeneradorExcel')
    });
  });

  // Eso arragle un error en los select2 con el scroll cuando esta dentro de un modal
  $('#modalGeneradorExcel').on('scroll', function(event) {
    $(this).find(".select2GeneradorExcel").each(function() {
      $(this).select2({
        dropdownParent: $(this).parent()
      });
    });
  });

  $("#filtroFechasPasosCrecimiento1").flatpickr({
    mode: "range",
    dateFormat: "Y-m-d",
    defaultDate: ["{{ $parametrosBusqueda->filtroFechaIniPaso1 ? $parametrosBusqueda->filtroFechaIniPaso1 : ''}}", "{{ $parametrosBusqueda->filtroFechaFinPaso1 ? $parametrosBusqueda->filtroFechaFinPaso1 : ''}}"],
    locale: {
      firstDayOfWeek: 1,
      weekdays: {
        shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
        longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
      },
      months: {
        shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Оct', 'Nov', 'Dic'],
        longhand: ['Enero', 'Febreo', 'Мarzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      },
    },
    onChange: function(dates) {
      if (dates.length == 2) {
        var _this = this;
        var dateArr = dates.map(function(date) {
          return _this.formatDate(date, 'Y-m-d');
        });
        $('#filtroFechaIniPaso1').val(dateArr[0]);
        $('#filtroFechaFinPaso1').val(dateArr[1]);
        // interact with selected dates here
      }
    },
    onReady: function(dateObj, dateStr, instance) {
      var $cal = $(instance.calendarContainer);
      if ($cal.find('.flatpickr-clear').length < 1) {
        $cal.append('<button type="button" class="btn btn-sm btn-outline-primary flatpickr-clear mb-2">Borrar</button>');
        $cal.find('.flatpickr-clear').on('click', function() {
          instance.clear();
          $('#filtroFechaIniPaso1').val('');
          $('#filtroFechaFinPaso1').val('');
          instance.close();
        });
      }
    }
  });

  $("#filtroFechasPasosCrecimiento2").flatpickr({
    mode: "range",
    dateFormat: "Y-m-d",
    defaultDate: ["{{ $parametrosBusqueda->filtroFechaIniPaso2 ? $parametrosBusqueda->filtroFechaIniPaso2 : ''}}", "{{ $parametrosBusqueda->filtroFechaFinPaso2 ? $parametrosBusqueda->filtroFechaFinPaso2 : ''}}"],
    locale: {
      firstDayOfWeek: 1,
      weekdays: {
        shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
        longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
      },
      months: {
        shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Оct', 'Nov', 'Dic'],
        longhand: ['Enero', 'Febreo', 'Мarzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      },
    },
    onChange: function(dates) {
      if (dates.length == 2) {
        var _this = this;
        var dateArr = dates.map(function(date) {
          return _this.formatDate(date, 'Y-m-d');
        });
        $('#filtroFechaIniPaso2').val(dateArr[0]);
        $('#filtroFechaFinPaso2').val(dateArr[1]);
        // interact with selected dates here
      }
    },
    onReady: function(dateObj, dateStr, instance) {
      var $cal = $(instance.calendarContainer);
      if ($cal.find('.flatpickr-clear').length < 1) {
        $cal.append('<button type="button" class="btn btn-sm btn-outline-primary flatpickr-clear mb-2">Borrar</button>');
        $cal.find('.flatpickr-clear').on('click', function() {
          instance.clear();
          $('#filtroFechaIniPaso2').val('');
          $('#filtroFechaFinPaso2').val('');
          instance.close();
        });
      }
    }
  });

  $(".clearAllItems").click(function() {
    value = $(this).data('select');
    $('#' + value).val(null).trigger('change');
  });

  $(".selectAllItems").click(function() {
    value = $(this).data('select');
    $("#" + value + " > option").prop("selected", true);
    $("#" + value).trigger("change");
  });

  function darBajaAlta(usuarioId, tipo)
  {
    Livewire.dispatch('abrirModalBajaAlta', { usuarioId: usuarioId, tipo: tipo });
  }

  function comprobarSiTieneRegistros(usuarioId)
  {
    Livewire.dispatch('comprobarSiTieneRegistros', { usuarioId: usuarioId });
  }

  function eliminacionForzada(usuarioId)
  {
    Livewire.dispatch('confirmarEliminacion', { usuarioId: usuarioId });
  }


</script>

@endsection

@section('content')
<h4 class="mb-1">Personas</h4>
<p class="mb-4">Aquí encontraras el listado de usuarios registrados en la plataforma.</p>

@include('layouts.status-msn')

<div class="row mb-2">
  <div id="carouselExample-cf" class="carousel carousel-dark slide carousel-fade" data-bs-ride="carousel">
    <a class="float-end" href="#carouselExample-cf" role="button" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </a>
    <a class="float-end" href="#carouselExample-cf" role="button" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </a>

    <div class="carousel-inner">
      <div class="carousel-item active">
        <div class="row">
          <!-- Cards with few info -->
          @foreach( $indicadoresGenerales as $indicador )
          <div class="col-lg-4 col-sm-6 mb-2">
            <a href="{{ route('usuario.lista', $indicador->url) }}">
              <div class="card h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                  <div class="card-title mb-0">
                    <h5 class="mb-0 me-2">{{ $indicador->cantidad }}</h5>
                    <small class="text-black">{{ $indicador->nombre }}</small>
                  </div>
                  <div class="card-icon">
                    <span class="badge {{ $indicador->color}} rounded-pill p-2">
                      <i class='{{ $indicador->icono}} ti-lg'></i>
                    </span>
                  </div>
                </div>
              </div>
            </a>
          </div>
          @endforeach
          <!--/ Cards with few info -->
        </div>
      </div>

      <div class="carousel-item">
        <div class="row">
          <!-- Cards with few info -->
          @foreach( $indicadoresPorTipoUsuario as $indicador )
          <div class="col-lg-4 col-sm-6 mb-2">
            <a href="{{ route('usuario.lista', $indicador->url) }}">
              <div class="card h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                  <div class="card-title mb-0">
                    <h5 class="mb-0 me-2">{{ $indicador->cantidad }}</h5>
                    <small class="text-black">{{ $indicador->nombre }}</small>
                  </div>
                  <div class="card-icon">
                    <span class="badge rounded-pill p-2" style="background-color: {{$indicador->color}}">
                      <i class='{{ $indicador->icono }} ti-sm'></i>
                    </span>
                  </div>
                </div>
              </div>
            </a>
          </div>
          @endforeach
          <!--/ Cards with few info -->
        </div>
      </div>
    </div>
  </div>
</div>

    <div class="row mt-5">
      <form class="forms-sample" method="GET" action="{{ route('usuario.lista', $tipo) }}">
        <div class="col-12 offset-md-2 col-md-8 d-flex">
          <div class="input-group">
            <input id="buscar" name="buscar" type="text" value="{{$parametrosBusqueda->buscar}}" class="form-control" placeholder="Busqueda..." aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-primary px-2 px-md-3" type="submit" id="button-addon2"><i class="ti ti-search"></i></button>
            @if($parametrosBusqueda->bandera == 1)
            <a href="{{ route('usuario.lista', $tipo) }}" class="btn btn-outline-danger" type="submit"><i class="ti ti-x"></i></a>
            @endif
            <button type="button" class="btn btn-primary btn-sm px-2 px-md-3" data-bs-toggle="modal" data-bs-target="#modalBusquedaAvanzada"><i class="ti ti-input-search"></i> <span class="d-none d-md-block">Búsqueda avanzada</span></button>
            <button type="button" class="btn btn-success btn-sm px-2 px-md-3" data-bs-toggle="modal" data-bs-target="#modalGeneradorExcel"><i class="ti ti-file-download"></i> <span class="d-none d-md-block">.xls</span></button>
          </div>
          <!-- Button trigger modal -->
        </div>
      </form>
      @if($personas)
      <span class="text-center py-3">{{ $personas->total() > 1 ? $personas->total().' Personas' : $personas->total().' Persona' }} {!! $parametrosBusqueda->textoBusqueda ? '('.$parametrosBusqueda->textoBusqueda.')' : '' !!}</span>
      @endif
    </div>

    <!-- Listado de persona -->
    <div class="row g-4 mt-1">
      @foreach($personas as $persona)
      <div class="col-xl-4 col-lg-6 col-md-6">
        <div class="card border rounded">
          <div class="card-body text-center">
            <div class="dropdown btn-pinned border rounded p-1">
              <button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical text-muted"></i></button>
              <ul class="dropdown-menu dropdown-menu-end">

                @if($rolActivo->hasPermissionTo('personas.opcion_ver_perfil_asistente'))
                  <li><a class="dropdown-item" href="{{ route('usuario.perfil', $persona) }}">Ver perfil</a></li>
                @endif

                @if($rolActivo->hasPermissionTo('personas.opcion_dar_de_alta_asistente'))
                  @if($persona->trashed())
                  <li><a class="dropdown-item" href="javascript:void(0);" onclick="darBajaAlta('{{$persona->id}}', 'alta')">Dar de alta</a></li>
                  @endif
                @endif

                <!-- opcion modificar  -->
                @if($rolActivo->hasPermissionTo('personas.opcion_modificar_asistente'))
                  @if($persona->esta_aprobado==TRUE)
                    @foreach( auth()->user()->formularios('opcion_modificar_asistente', $persona->edad()) as $formulario)
                    <li><a class="dropdown-item" href="{{ route('usuario.modificar', [$formulario, $persona]) }}">{{$formulario->nombre2}}</a></li>
                    @endforeach
                  @elseif ($persona->esta_aprobado==FALSE)
                    @if($rolActivo->hasPermissionTo('personas.privilegio_modificar_asistentes_desaprobados'))
                      @foreach( auth()->user()->formularios('opcion_modificar_asistente', $persona->edad()) as $formulario)
                      <li><a class="dropdown-item" href="{{ route('usuario.modificar', [$formulario, $persona]) }}">{{$formulario->nombre2}}</a></li>
                      @endforeach
                    @endif
                  @endif
                @endif
                <!-- / opcion modificar  -->

                @if($rolActivo->hasPermissionTo('personas.opcion_modificar_informacion_congregacional'))
                <li><a class="dropdown-item" href="{{ route('usuario.informacionCongregacional', ['formulario' => 0 ,'usuario' => $persona]) }}">Info. congregacional</a></li>
                @endif

                @if($rolActivo->hasPermissionTo('personas.opcion_geoasignar_asistente'))
                <li><a class="dropdown-item" href="{{ route('usuario.geoAsignacion', ['formulario' => 0 ,'usuario' => $persona]) }}">Geo asignación</a></li>
                @endif

                @if($rolActivo->hasPermissionTo('personas.opcion_cambiar_contrasena_asistente'))
                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalCambioContrasena" onclick="event.preventDefault(); document.getElementById('formCambioContrasena').setAttribute('action', 'usuarios/{{$persona->id}}/cambiar-contrasena');">Cambiar contraseña</a></li>

                <form method="POST" id="cambiarContraseñaDefault" action="{{ route('usuario.cambiarContrasenaDefault',  ['usuario' => $persona ]) }}">
                  @csrf
                  <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('cambiarContraseñaDefault').submit();">Cambiar contraseña default</a></li>
                </form>
                @endif

                <li><a class="dropdown-item" href="{{ route('usuario.descargarCodigoQr', $persona) }}">Código QR</a></li>

                <hr class="dropdown-divider">
                @if($rolActivo->hasPermissionTo('personas.opcion_dar_de_baja_asistente'))
                  @if($persona->trashed()!=TRUE)
                  <li><a class="dropdown-item text-danger" href="javascript:void(0);" onclick="darBajaAlta('{{$persona->id}}', 'baja')">Dar de baja</a></li>
                  @endif
                @endif
                @if($rolActivo->hasPermissionTo('personas.opcion_eliminar_asistente'))
                  @if($persona->trashed()!=TRUE)
                  <li><a class="dropdown-item text-danger" href="javascript:void(0);" onclick="comprobarSiTieneRegistros('{{$persona->id}}')">Eliminar</a></li>
                  @endif
                @endif
                @if($rolActivo->hasPermissionTo('personas.eliminar_asistentes_forzadamente'))
                  @if($persona->trashed()==TRUE)
                  <li><a class="dropdown-item text-danger" href="javascript:void(0);" onclick="eliminacionForzada('{{$persona->id}}')">Eliminación forzada </a></li>
                  @endif
                @endif

              </ul>
            </div>
            <div class="mx-auto my-3">
              <img src="{{ $configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/img/foto-usuario/'.$persona->foto) : $configuracion->ruta_almacenamiento.'/img/foto-usuario/'.$persona->foto }}" alt="foto {{$persona->primer_nombre}}" class="rounded-circle w-px-100" />
            </div>
            <h4 class="mb-1 card-title">{{ $persona->primer_nombre}} {{ $persona->segundo_nombre ? $persona->segundo_nombre : ''}} {{ $persona->primer_apellido }}</h4>

            <p class="pb-1">
              <span class="badge" style="background-color: {{$persona->tipoUsuario->color}}">
                <i class="{{ $persona->tipoUsuario->icono }} fs-6 mx-1"></i> {{ $persona->tipoUsuario->nombre }} </span>
              |<i class="ti ti-calendar mx-1"></i> {{ $persona->edad() }} años
              @if($persona->ultimoTipoServicioGrupo())
              <br>
              <span class="pb-1 "><i class="ti ti-circle-check mx-1"></i> {{ $persona->ultimoTipoServicioGrupo()->nombre }}</span>
              @endif
            </p>


            @if($tipo=="dados-de-baja")
            <span class="badge bg-label-danger">Motivo: {{ $persona->ultimoReporteDadoBaja() ? $persona->ultimoReporteDadoBaja()->tipo->nombre : 'No definido' }}</span>
            @else
              @if(isset($persona->tipoUsuario->id))
                <div class="d-flex align-items-center justify-content-center my-2 gap-2">
                  @if($persona->tipoUsuario->seguimiento_actividad_grupo==FALSE)
                    <span class="badge bg-label-secondary">No seguimiento grupos</span>
                  @else
                    @if($persona->estadoActividadGrupos())
                    <span class="badge bg-label-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Último reporte {{$persona->ultimo_reporte_grupo}}">Activo grupo</span>
                    @else
                    <span class="badge bg-label-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Último reporte {{$persona->ultimo_reporte_grupo}}">Inactivo grupo</span>
                    @endif
                  @endif

                  @if($persona->tipoUsuario->seguimiento_actividad_reunion==FALSE)
                    <span class="badge bg-label-secondary">No seguimiento en reuniónes</span>
                  @else
                    @if($persona->estadoActividadReuniones())
                    <span class="badge bg-label-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Último reporte {{$persona->ultimo_reporte_reunion}}">Activo reuniones</span>
                    @else
                    <span class="badge bg-label-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Último reporte {{$persona->ultimo_reporte_reunion}}">Inactivo reuniones</span>
                    @endif
                  @endif
                </div>
              @endif
            @endif

            <div class="my-1 py-1 ">
              <span class="fw-bold">Encargados</span><br>
              <div style="height: 60px; overflow-y: scroll;">
                @foreach($persona->encargadosDirectos() as $encargado)
                <label class="pb-1">
                  <span class="badge px-2" style="background-color: {{ $encargado->color }}">
                    <i class="{{ $encargado->icono }} fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $encargado->tipo_usuario }}"></i>
                  </span> {{ $encargado->nombre }}
                </label><br>
                @endforeach
              </div>
            </div>

          </div>
        </div>
      </div>
      @endforeach
    </div>
    <!--/ Listado de persona -->

    <div class="row my-3">
      @if($personas)
      {!! $personas->appends(request()->input())->links() !!}
      @endif
    </div>

  <!-- Modal busqueda avanzada -->
  <div class="modal fade modalSelect2" id="modalBusquedaAvanzada" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <form class="forms-sample" method="GET" action="{{ route('usuario.lista', $tipo) }}">
        <div class="modal-content">
          <div class="modal-header d-flex flex-column">
            <h4 class="modal-title">Búsqueda avanzada</h4>
            <p class="modal-subtitle text-center">Trae un listado de personas más específico a través de este formulario. </p>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">

              <div class="col-12 mb-3">
                <label for="nameBasic" class="form-label">Por palabra</label>
                <input id="buscar" name="buscar" type="text" value="{{$parametrosBusqueda->buscar}}" class="form-control" placeholder="Buscar por nombre, email, identificación">
              </div>

              <!-- Por sexo -->
              <div class="col-12 col-md-4 mb-3">
                <label for="filtroPorSexo" class="form-label">Fitrar por sexo</label>
                <select id="filtroPorSexo" name="filtroPorSexo" class="select2BusquedaAvanzada form-select">
                  <option value="0" {{ $parametrosBusqueda->filtroPorSexo == 0 ? 'selected' : '' }}>Hombres</option>
                  <option value="1" {{ $parametrosBusqueda->filtroPorSexo == 1 ? 'selected' : '' }}>Mujeres</option>
                  <option value="" {{ !is_numeric($parametrosBusqueda->filtroPorSexo) ? 'selected' : '' }}>Todos</option>
                </select>
              </div>

              <!-- Por tipo de usuario -->
              <div class="col-12 col-md-4 mb-3">
                <label for="filtroPorTipoDeUsuario" class="form-label">Fitrar por tipo de usuario </label>
                <select id="filtroPorTipoDeUsuario" name="filtroPorTipoDeUsuario[]" class="select2BusquedaAvanzada form-select" multiple>
                  @foreach($tiposUsuarios as $tipoUsuario)
                  <option value="{{ $tipoUsuario->id }}" {{ $parametrosBusqueda->filtroPorTipoDeUsuario && in_array($tipoUsuario->id,$parametrosBusqueda->filtroPorTipoDeUsuario) ? 'selected' : '' }}>{{ $tipoUsuario->nombre }}</option>
                  @endforeach
                </select>
              </div>

              <!-- Por edades -->
              <div class="col-12 col-md-4 mb-3">
                <label for="filtroPorRangoEdad" class="form-label">Fitrar por tipo rango de edad</label>
                <select id="filtroPorRangoEdad" name="filtroPorRangoEdad[]" class="select2BusquedaAvanzada form-select" multiple>
                  @foreach($rangosEdad as $rangoEdad)
                  <option value="{{ $rangoEdad->id }}" {{ $parametrosBusqueda->filtroPorRangoEdad && in_array($rangoEdad->id,$parametrosBusqueda->filtroPorRangoEdad) ? 'selected' : '' }}>{{ $rangoEdad->nombre.' ('.$rangoEdad->edad_minima.'-'.$rangoEdad->edad_maxima.')' }}</option>
                  @endforeach
                </select>
              </div>

              <!-- Por estados civiles -->
              <div class="col-12 col-md-4 mb-3">
                <label for="filtroPorEstadosCiviles" class="form-label">Fitrar por estados civiles</label>
                <select id="filtroPorEstadosCiviles" name="filtroPorEstadosCiviles[]" class="select2BusquedaAvanzada form-select" multiple>
                  @foreach($estadosCiviles as $estadoCivil)
                  <option value="{{ $estadoCivil->id }}" {{ $parametrosBusqueda->filtroPorEstadosCiviles && in_array($estadoCivil->id,$parametrosBusqueda->filtroPorEstadosCiviles) ? 'selected' : '' }}>{{ $estadoCivil->nombre }}</option>
                  @endforeach
                </select>
              </div>

              <!-- Por tipo de vinculacion -->
              <div class="col-12 col-md-4 mb-3">
                <label for="filtroPorTiposVinculaciones" class="form-label">Fitrar por tipo de vinculación</label>
                <select id="filtroPorTiposVinculaciones" name="filtroPorTiposVinculaciones[]" class="select2BusquedaAvanzada form-select" multiple>
                  @foreach($tiposVinculaciones as $tipoVinculacion)
                  <option value="{{ $tipoVinculacion->id }}" {{ $parametrosBusqueda->filtroPorTiposVinculaciones && in_array($tipoVinculacion->id,$parametrosBusqueda->filtroPorTiposVinculaciones) ? 'selected' : '' }}>{{ $tipoVinculacion->nombre }}</option>
                  @endforeach
                </select>
              </div>

              <!-- Por ocupacion -->
              <div class="col-12 col-md-4 mb-3">
                <label for="filtroPorOcupacion" class="form-label">Fitrar por ocupación</label>
                <select id="filtroPorOcupacion" name="filtroPorOcupacion[]" class="select2BusquedaAvanzada form-select" multiple>
                  @foreach($ocupaciones as $ocupacion)
                  <option value="{{ $ocupacion->id }}" {{ $parametrosBusqueda->filtroPorOcupacion && in_array($ocupacion->id,$parametrosBusqueda->filtroPorOcupacion) ? 'selected' : '' }}>{{ $ocupacion->nombre }}</option>
                  @endforeach
                </select>
              </div>

              <!-- Por profesion -->
              <div class="col-12 col-md-4 mb-3">
                <label for="filtroPorProfesion" class="form-label">Fitrar por nivel profesión</label>
                <select id="filtroPorProfesion" name="filtroPorProfesion[]" class="select2BusquedaAvanzada form-select" multiple>
                  @foreach($profesiones as $profesion)
                  <option value="{{ $profesion->id }}" {{ $parametrosBusqueda->filtroPorProfesion && in_array($profesion->id,$parametrosBusqueda->filtroPorProfesion) ? 'selected' : '' }}>{{ $profesion->nombre }}</option>
                  @endforeach
                </select>
              </div>

              <!-- Por nivel academico -->
              <div class="col-12 col-md-4 mb-3">
                <label for="filtroPorNivelAcademico" class="form-label">Fitrar por nivel académico</label>
                <select id="filtroPorNivelAcademico" name="filtroPorNivelAcademico[]" class="select2BusquedaAvanzada form-select" multiple>
                  @foreach($nivelesAcademicos as $nivelAcademico)
                  <option value="{{ $nivelAcademico->id }}" {{ $parametrosBusqueda->filtroPorNivelAcademico && in_array($nivelAcademico->id,$parametrosBusqueda->filtroPorNivelAcademico) ? 'selected' : '' }}>{{ $nivelAcademico->nombre }}</option>
                  @endforeach
                </select>
              </div>

              <!-- Por estado nivel académico -->
              <div class="col-12 col-md-4 mb-3">
                <label for="filtroPorEstadoNivelAcademico" class="form-label">Fitrar estado académico</label>
                <select id="filtroPorEstadoNivelAcademico" name="filtroPorEstadoNivelAcademico" class="select2BusquedaAvanzada form-select">
                  @foreach($estadosNivelAcademico as $estadoNivelAcademico)
                  <option value="{{ $estadoNivelAcademico->id }}" {{ $estadoNivelAcademico->id == $parametrosBusqueda->filtroPorEstadoNivelAcademico ? 'selected' : '' }}>{{ $estadoNivelAcademico->nombre }}</option>
                  @endforeach
                  <option value="" {{ !is_numeric($parametrosBusqueda->filtroPorEstadoNivelAcademico) ? 'selected' : '' }}>Todos</option>
                </select>
              </div>

              <div class="divider text-start my-2">
                <div class="divider-text fw-bold">PASOS DE CRECIMIENTO</div>
              </div>

              <!-- Por paso crecimiento 1 -->
              <div class="col-12 col-md-6 mb-3">
                <label for="filtroPorPasosCrecimiento1" class="form-label">Pasos de crecimiento</label>
                <select id="filtroPorPasosCrecimiento1" name="filtroPorPasosCrecimiento1[]" class="select2BusquedaAvanzada form-select" multiple>
                  @foreach($pasosCrecimiento as $pasoCrecimiento)
                  <option value="{{ $pasoCrecimiento->id }}" {{ $parametrosBusqueda->filtroPorPasosCrecimiento1 && in_array($pasoCrecimiento->id,$parametrosBusqueda->filtroPorPasosCrecimiento1) ? 'selected' : '' }}>{{ $pasoCrecimiento->nombre }}</option>
                  @endforeach
                </select>
              </div>

              <!-- Por estado paso 1-->
              <div class="col-12 col-md-3 mb-3">
                <label for="filtroEstadoPasos1" class="form-label">Estado</label>
                <select id="filtroEstadoPasos1" name="filtroEstadoPasos1" class="select2BusquedaAvanzada form-select">
                  <option value="1" {{ $parametrosBusqueda->filtroEstadoPasos1 == 1 ? 'selected' : '' }}>No realizado</option>
                  <option value="2" {{ $parametrosBusqueda->filtroEstadoPasos1 == 2 ? 'selected' : '' }}>En curso</option>
                  <option value="3" {{ !$parametrosBusqueda->filtroEstadoPasos1 || $parametrosBusqueda->filtroEstadoPasos1 == 3 ? 'selected' : '' }}>Realizado</option>
                </select>
              </div>

              <!-- fecha paso 1 -->
              <div class="col-12 col-md-3 mb-3">
                <label for="filtroFechasPasosCrecimiento1" class="form-label">Rango de fechas</label>
                <input id="filtroFechasPasosCrecimiento1" name="filtroFechasPasosCrecimiento1" type="text" class="form-control" placeholder="YYYY-MM-DD a YYYY-MM-DD" />
                <input type="text" id="filtroFechaIniPaso1" name="filtroFechaIniPaso1" value="{{ $parametrosBusqueda->filtroFechaIniPaso1 }}" class="form-control d-none" placeholder="">
                <input type="text" id="filtroFechaFinPaso1" name="filtroFechaFinPaso1" value="{{ $parametrosBusqueda->filtroFechaFinPaso1 }}" class="form-control d-none" placeholder="">
              </div>

              <!-- Por paso crecimiento 1 -->
              <div class="col-12 col-md-6 mb-3">
                <label for="filtroPorPasosCrecimiento2" class="form-label">Pasos de crecimiento</label>
                <select id="filtroPorPasosCrecimiento2" name="filtroPorPasosCrecimiento2[]" class="select2BusquedaAvanzada form-select" multiple>
                  @foreach($pasosCrecimiento as $pasoCrecimiento)
                  <option value="{{ $pasoCrecimiento->id }}" {{ $parametrosBusqueda->filtroPorPasosCrecimiento2 && in_array($pasoCrecimiento->id,$parametrosBusqueda->filtroPorPasosCrecimiento2) ? 'selected' : '' }}>{{ $pasoCrecimiento->nombre }}</option>
                  @endforeach
                </select>
              </div>

              <!-- Por estado paso 1-->
              <div class="col-12 col-md-3 mb-3">
                <label for="filtroEstadoPasos2" class="form-label">Estado</label>
                <select id="filtroEstadoPasos2" name="filtroEstadoPasos2" class="select2BusquedaAvanzada form-select">
                  <option value="1" {{ $parametrosBusqueda->filtroEstadoPasos2 == 1 ? 'selected' : '' }}>No Realizado</option>
                  <option value="2" {{ $parametrosBusqueda->filtroEstadoPasos2 == 2 ? 'selected' : '' }}>En curso</option>
                  <option value="3" {{ !$parametrosBusqueda->filtroEstadoPasos2 || $parametrosBusqueda->filtroEstadoPasos2 == 3 ? 'selected' : '' }}>Realizado</option>
                </select>
              </div>

              <!-- fecha paso 1 -->
              <div class="col-12 col-md-3 mb-3">
                <label for="filtroFechasPasosCrecimiento2" class="form-label">Rango de fechas</label>
                <input id="filtroFechasPasosCrecimiento2" name="filtroFechasPasosCrecimiento2" type="text" class="form-control" placeholder="YYYY-MM-DD a YYYY-MM-DD" />
                <input type="text" id="filtroFechaIniPaso2" name="filtroFechaIniPaso2" value="{{ $parametrosBusqueda->filtroFechaIniPaso2 }}" class="form-control d-none" placeholder="">
                <input type="text" id="filtroFechaFinPaso2" name="filtroFechaFinPaso2" value="{{ $parametrosBusqueda->filtroFechaFinPaso2 }}" class="form-control d-none" placeholder="">
              </div>

              <div class="divider text-start my-2">
                <div class="divider-text fw-bold">GRUPOS Y REUNIONES</div>
              </div>

              @livewire('Grupos.grupos-para-busqueda',[
              'id' => 'filtroGrupo',
              'class' => 'col-12 col-md-4 mb-3',
              'label' => 'Filtrar a partir del grupo',
              'conDadosDeBaja' => 'no',
              'grupoSeleccionadoId' => $parametrosBusqueda->filtroGrupo,
              'estiloSeleccion' => 'pequeno'
              ])

              <!-- Por tipo ministerio -->
              <div class="col-12 col-md-4 mb-3">
                <label for="filtroTipoMinisterio" class="form-label">Fitrar por tipo ministerio</label>
                <select id="filtroTipoMinisterio" name="filtroTipoMinisterio" class="select2BusquedaAvanzada form-select">
                  <option value="0" {{ !$parametrosBusqueda->filtroTipoMinisterio || $parametrosBusqueda->filtroTipoMinisterio == 0 ? 'selected' : '' }}>Ministerio completo</option>
                  <option value="1" {{ $parametrosBusqueda->filtroTipoMinisterio == 1 ? 'selected' : '' }}>Ministerio directo</option>
                </select>
              </div>

              <div class="col-12 col-md-2 mb-3">
                <label for="filtroCantidadDiasInactividadGrupos" class="form-label">Días inactividad grupos</label>
                <input type="number" id="filtroCantidadDiasInactividadGrupos" name="filtroCantidadDiasInactividadGrupos" value="{{ old('filtroCantidadDiasInactividadGrupos', $parametrosBusqueda->filtroCantidadDiasInactividadGrupos) }}" class="form-control">
              </div>

              <div class="col-12 col-md-2 mb-3">
                <label for="filtroCantidadDiasInactividadReuniones" class="form-label">Días inactividad reunión</label>
                <input type="number" id="filtroCantidadDiasInactividadReuniones" name="filtroCantidadDiasInactividadReuniones" value="{{ old('filtroCantidadDiasInactividadReuniones', $parametrosBusqueda->filtroCantidadDiasInactividadReuniones) }}" class="form-control">
              </div>



            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary"><i class="ti ti-search ml-3"></i> Buscar </button>
          </div>
        </div>
      </form>
    </div>
  </div>


  <!-- Modal generador de excel -->
  <div class="modal fade modalSelect2" id="modalGeneradorExcel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <form class="forms-sample" method="POST" action="{{ route('usuario.listadoFinalCsv') }}">
        @csrf
        <div class="modal-content">
          <div class="modal-header d-flex flex-column">
            <h4 class="modal-title">Generador de excel</h4>
            <p class="modal-subtitle text-center">Selecciona los campos que deseas exportar en el archivo Excel.</p>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <textarea id="parametros-busqueda-excel" name="parametrosBusqueda" class="d-none">{{json_encode($parametrosBusqueda)}}</textarea>

              <!-- Informacion personal -->
              <div class="col-12 mb-3">
                <label for="informacionPersonal" class="form-label">Información personal
                  (<a href="javascript:;" data-select="informacionPersonal" class="selectAllItems"><span class="fw-medium">Seleccionar todos</span></a> | <a href="javascript:;" data-select="informacionPersonal" class="clearAllItems"><span class="fw-medium">Quitar todos</span></a>)
                </label>
                <select id="informacionPersonal" name="informacionPersonal[]" class="select2GeneradorExcel form-select" multiple>
                  @foreach($camposInformeExcel->where('selector_id',1) as $campo)
                  <option value="{{ $campo->id }}">{{ $campo->nombre_campo_informe }}</option>
                  @endforeach
                </select>
              </div>

              <!-- Informacion ministerial -->
              <div class="col-12 mb-3">
                <label for="informacionMinisterial" class="form-label">Información ministerial
                  (<a href="javascript:;" data-select="informacionMinisterial" class="selectAllItems"><span class="fw-medium">Seleccionar todos</span></a> | <a href="javascript:;" data-select="informacionMinisterial" class="clearAllItems"><span class="fw-medium">Quitar todos</span></a>)
                </label>
                <select id="informacionMinisterial" name="informacionMinisterial[]" class="select2GeneradorExcel form-select" multiple>
                  @foreach($pasosCrecimiento as $pasoCrecimiento)
                  <option value="{{ $pasoCrecimiento->id }}">{{ $pasoCrecimiento->nombre }}</option>
                  @endforeach
                </select>
              </div>

              <!-- Informacion congregacional -->
              <div class="col-12 mb-3">
                <label for="informacionCongregacional" class="form-label">Información congregacional
                  (<a href="javascript:;" data-select="informacionCongregacional" class="selectAllItems"><span class="fw-medium">Seleccionar todos</span></a> | <a href="javascript:;" data-select="informacionCongregacional" class="clearAllItems"><span class="fw-medium">Quitar todos</span></a>)
                </label>
                <select id="informacionCongregacional" name="informacionCongregacional[]" class="select2GeneradorExcel form-select" multiple>
                  @foreach($camposInformeExcel->where('selector_id',2) as $campo)
                  <option value="{{ $campo->id }}">{{ $campo->nombre_campo_informe }}</option>
                  @endforeach
                </select>
              </div>

              @if($configuracion->visible_seccion_campos_extra)
              <!-- Informacion congregacional -->
              <div class="col-12 mb-3">
                <label for="informacionCamposExtras" class="form-label">Información {{$configuracion->label_seccion_campos_extra}}
                  (<a href="javascript:;" data-select="informacionCamposExtras" class="selectAllItems"><span class="fw-medium">Seleccionar todos</span></a> | <a href="javascript:;" data-select="informacionCamposExtras" class="clearAllItems"><span class="fw-medium">Quitar todos</span></a>)
                </label>
                <select id="informacionCamposExtras" name="informacionCamposExtras[]" class="select2GeneradorExcel form-select" multiple>
                  @foreach($camposExtras as $campo)
                  <option value="{{ $campo->id }}">{{ $campo->nombre }}</option>
                  @endforeach
                </select>
              </div>
              @endif

            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-success"><i class="ti ti-donwload ml-3"></i> Generar </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal cambio de contraseña -->
  <div class="modal fade" id="modalCambioContrasena" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <form id="formCambioContrasena" class="forms-sample" method="POST" action="">
        @csrf
        <div class="modal-content">
          <div class="modal-header d-flex flex-column">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">

            <div class="text-center mb-4">
              <h3 class="role-title mb-2"><i class="ti ti-password ti-lg"></i> Cambio de contraseña</h3>
              <p class="text-muted">La contraseña debe contener como mínimo 5 caracteres, una letra minúscula y un número.</p>
            </div>

            <div class="row">

              <!-- Nueva Contrasena -->
              <div class="col-12 mb-3">
                <label class="form-label" for="nueva_contrasena">Nueva contraseña</label>
                <input id="nueva_contrasena" name="password" value="" type="password" class="form-control" required pattern="(?=.*\d)(?=.*[A-Za-z]).{5,}" title="La contraseña debe contener como minimo 5 caracteres alfanumericos, es decir, debe contener como minimo letras y numeros.  "/>
              </div>

              <!-- Confirmar Contrasena -->
              <div class="col-12 mb-3">
                <label class="form-label" for="confirmar_contrasena">Confirmar contraseña</label>
                <input id="confirmar_contrasena" name="password_confirmation" value="" type="password" class="form-control" required pattern="(?=.*\d)(?=.*[A-Za-z]).{5,}" title="La contraseña debe contener como minimo 5 caracteres alfanumericos, es decir, debe contener como minimo letras y numeros.  "/>
              </div>

            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary"><i class="ti ti-donwload ml-3"></i> Guardar </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  @livewire('Usuarios.modal-baja-alta')

@endsection
