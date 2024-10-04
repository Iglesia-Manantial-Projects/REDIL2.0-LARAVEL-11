@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Información congregacional')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/form-basic-inputs.js')}}"></script>

<script>
  $(document).ready(function() {
    $('.select2').select2({
      width: '100px',
      allowClear: true,
      placeholder: 'Ninguno'
    });

    $(".fecha-picker").flatpickr({
      dateFormat: "Y-m-d"
    });
  });
</script>

<script type="text/javascript">
  function sinComillas(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    patron = /[\x5C'"]/;
    te = String.fromCharCode(tecla);
    return !patron.test(te);
  }
</script>

<script>
  $('.modificarEstadoProceso').click(function() {

    var estados = <?php echo json_encode($estados); ?>;
    let dataEstado = $(this).attr("data-estado");
    let pasoId = $(this).attr("data-id");

    $("#fecha_paso_" + pasoId).attr("disabled", false);
    if (dataEstado == 1) {
      dataEstadoNuevo = 2;
    } else if (dataEstado == 2) {
      dataEstadoNuevo = 3;
    } else if (dataEstado == 3) {
      dataEstadoNuevo = 1;
      $("#fecha_paso_" + pasoId).attr("disabled", true);
    }

    $("#estado_paso_" + pasoId).val(dataEstadoNuevo);

    for (let i in estados) {
      if (estados[i].id == dataEstado) {
        $(this).removeClass("btn-" + estados[i].color);
        $("#icono_paso_" + pasoId).removeClass("timeline-indicator-" + estados[i].color);
      }
    }

    for (let j in estados) {
      if (estados[j].id == dataEstadoNuevo) {
        $(this).attr("data-estado", dataEstadoNuevo);
        $(this).addClass("btn-" + estados[j].color);
        $("#icono_paso_" + pasoId).addClass("timeline-indicator-" + estados[j].color);
        $(this).html(estados[j].nombre);
      }
    }

  });
</script>

@endsection

@section('content')
<div class="row mb-2">
  <ul class="nav nav-pills mb-3 d-flex justify-content-end" role="tablist">

    @if($rolActivo->hasPermissionTo('personas.pestana_actualizar_asistente') && isset($formulario))
    @if($rolActivo->hasPermissionTo('personas.opcion_modificar_asistente'))
    @if($usuario->esta_aprobado==TRUE)
    <li class="nav-item">
      <a href="{{ route('usuario.modificar', [$formulario, $usuario]) }}">
        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-home" aria-controls="navs-pills-justified-home" aria-selected="true">
          <span class="badge rounded-pill badge-center h-px-10 w-px-10 bg-label-primary ms-1 mx-1">1</span>
          Datos principales
        </button>
      </a>
    </li>
    @elseif ($usuario->esta_aprobado==TRUE && $rolActivo->hasPermissionTo('personas.privilegio_modificar_asistentes_desaprobados'))
    <li class="nav-item">
      <a href="{{ route('usuario.modificar', [$formulario, $usuario]) }}">
        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-home" aria-controls="navs-pills-justified-home" aria-selected="true">
          <span class="badge rounded-pill badge-center h-px-10 w-px-10 bg-label-primary ms-1 mx-1">1</span>
          Datos principales
        </button>
      </a>
    </li>
    @endif
    @endif
    @endif

    @if($rolActivo->hasPermissionTo('personas.pestana_informacion_congregacional'))
    @if(auth()->user()->id != $usuario->id)
    <li class="nav-item">
      <a href="{{ route('usuario.informacionCongregacional', ['formulario' => $formulario ,'usuario' => $usuario]) }}">
        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-home" aria-controls="navs-pills-justified-home" aria-selected="true">
          <span class="badge rounded-pill badge-center h-px-10 w-px-10 bg-label-primary ms-1 mx-1">2</span>
          Información congregacional
        </button>
      </a>
    </li>
    @endif
    @elseif(auth()->user()->id == $usuario->id && $rolActivo->hasPermissionTo('personas.autogestion_pestana_informacion_congregacional'))
    <li class="nav-item">
      <a href="{{ route('usuario.informacionCongregacional', ['formulario' => $formulario ,'usuario' => $usuario]) }}">
        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-home" aria-controls="navs-pills-justified-home" aria-selected="true">
          <span class="badge rounded-pill badge-center h-px-10 w-px-10 bg-label-primary ms-1 mx-1">2</span>
          Información congregacional
        </button>
      </a>
    </li>
    @endif

    @if($rolActivo->hasPermissionTo('personas.pestana_geoasignacion'))
    @if( auth()->user()->id == $usuario->id && $rolActivo->hasPermissionTo('personas.auto_gestion_pestana_geoasignacion_grupo'))
    <li class="nav-item">
      <a href="{{ route('usuario.geoAsignacion', ['formulario' => $formulario ,'usuario' => $usuario]) }}">
        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-home" aria-controls="navs-pills-justified-home" aria-selected="true">
          <span class="badge rounded-pill badge-center h-px-10 w-px-10 bg-label-primary ms-1 mx-1">3</span>
          Geo asignación
        </button>
      </a>
    </li>
    @else
    <li class="nav-item">
      <a href="{{ route('usuario.geoAsignacion', ['formulario' => $formulario ,'usuario' => $usuario]) }}">
        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-home" aria-controls="navs-pills-justified-home" aria-selected="true">
          <span class="badge rounded-pill badge-center h-px-10 w-px-10 bg-label-primary ms-1 mx-1">3</span>
          Geo asignación
        </button>
      </a>
    </li>
    @endif
    @endif

  </ul>
</div>

<h4 class="mb-1 mayusculas">Información congregacional</h4>
<p class="mb-4">Aquí podrás gestionar toda la información de <b>{{$usuario->nombre(3)}}</b> relacionada con la congregación.</p>



@include('layouts.status-msn')

<form id="formulario" role="form" class="forms-sample" method="POST" action="{{ route( 'usuario.actualizarInformacionCongregacional', $usuario->id) }}" enctype="multipart/form-data">
  @csrf
  @method('PATCH')

  <!-- botonera -->
  <div class="d-flex mb-1 mt-5">
    <div class="me-auto">
      <button type="submit" class="btn btn-primary me-1 btnGuardar">Guardar</button>
      <button type="reset" class="btn btn-label-secondary">Cancelar</button>
    </div>
    <div class="p-2 bd-highlight">
      <p class="text-muted"><span class="badge badge-dot bg-info me-1"></span> Campos obligatorios</p>
    </div>
  </div>
  <!-- /botonera -->



  <div class="row">
    <div class="col-md-6">
      <!-- tipoUsuario -->
      @if($rolActivo->hasPermissionTo('personas.panel_tipos_asistente'))
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between">
          <p class="card-text text-uppercase fw-bold"><i class="ti ti-building-church  ms-n1 me-2"></i>Información principal</p>
        </div>
        <div class="card-body pb-2">
          <!-- Tipo de asistente -->
          @if($rolActivo->hasPermissionTo('personas.editar_tipos_asistente'))
          <div class="mb-3">
            <label class="form-label" for="tipo_identificacion">
              <span class="badge badge-dot bg-info me-1"></span>
              Tipo usuario
            </label>
            <select id="tipo_usuario" name="tipo_usuario" class="select2 form-select" data-allow-clear="true">
              <option value="" selected>Ninguno</option>
              @foreach ($tiposUsuarios as $tiposUsuarios)
              <option value="{{$tiposUsuarios->id}}" {{ old('tipo_usuario', $usuario->tipo_usuario_id )==$tiposUsuarios->id ? 'selected' : '' }}>{{$tiposUsuarios->nombre}}</option>
              @endforeach
            </select>
            @if($errors->has('tipo_usuario')) <div class="text-danger form-label">{{ $errors->first('tipo_usuario') }}</div> @endif
          </div>
          @else
          <ul class="list-unstyled mb-0">
            <small class="card-text text-uppercase">Tipo de usuario</small>
            <li class="mb-1 mt-1 p-2 border rounded">
              <div class="d-flex align-items-start d-flex">
                <div class="d-flex align-items-center">
                  <div class="badge" style="background-color: {{$usuario->tipoUsuario->color}};">
                    <i class="ti {{$usuario->tipoUsuario->icono}} fs-1"></i>
                  </div>
                  <div class="me-3 ms-1 d-flex ">
                    <h5 class="mb-0 d-flex align-items-center">{{$usuario->tipoUsuario->nombre}}</h5>
                  </div>
                </div>

              </div>
            </li>
          </ul>
          @endif
          <!-- /Tipo de asistente-->
        </div>
      </div>
      @endif
      <!--/ tipoUsuario -->

      @if($rolActivo->hasPermissionTo('personas.panel_procesos_asistente'))
      <!-- Procesos de crecimiento -->
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <p class="card-text text-uppercase fw-bold"><i class="ti ti-list-details ms-n1 me-2"></i>Procesos de crecimiento</p>
        </div>
        <div class="card-body pb-20">
          <ul class="timeline ms-1 mb-0">
            @if($rolActivo->hasPermissionTo('personas.editar_procesos_asistente'))
            @foreach ($pasosDeCrecimiento as $paso)
            <li class="timeline-item timeline-item-transparent ps-4">
              <span id="icono_paso_{{$paso->id}}" class="timeline-indicator-advanced timeline-indicator-{{ $paso->clase_color }}">
                <i class="ti ti-square rounded-circle scaleX-n1-rtl"></i>
              </span>
              <div class="timeline-event">
                <div class="timeline-header">
                  <h6 class="mb-0 ml-1 fw-bold">{{ $paso->nombre }}</h6>
                  <button type="button" data-id="{{ $paso->id }}" data-estado="{{ $paso->estado_paso}}" class="modificarEstadoProceso btn btn-sm rounded-pill btn-{{ $paso->clase_color }} waves-effect waves-light">{{ $paso->estado_nombre }}</button>
                </div>
                <input id="fecha_paso_{{$paso->id}}" name="fecha_paso_{{$paso->id}}" value="{{ $paso->estado_fecha }}" {{ $paso->estado_fecha ? '' : 'disabled'}} placeholder="YYYY-MM-DD" class="mt-2 form-control fecha-picker" type="text" />
                <input id="estado_paso_{{$paso->id}}" name="estado_paso_{{$paso->id}}" value="{{ $paso->estado_paso}}" class="d-none" />
                <div class="accordion mt-1" id="accordion{{$paso->id}}">
                  <div class="card accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                      <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionPaso{{$paso->id}}" aria-expanded="true" aria-controls="accordionPaso{{$paso->id}}">
                        Detalle
                      </button>
                    </h2>
                    <div id="accordionPaso{{$paso->id}}" class="accordion-collapse collapse" data-bs-parent="#accordion{{$paso->id}}">
                      <div class="accordion-body">
                        <textarea onkeypress="return sinComillas(event)" id="detalle_paso_{{$paso->id}}" name="detalle_paso_{{$paso->id}}" class="form-control" rows="2" maxlength="500" spellcheck="false" data-ms-editor="true" placeholder="">{{ $paso->detalle_paso }}</textarea>
                      </div>
                    </div>
                  </div>
                </div>
            </li>
            @endforeach
            @else
            @foreach ($pasosDeCrecimiento as $paso)
            <li class="timeline-item timeline-item-transparent ps-4">
              <span class="timeline-point timeline-point-{{ $paso->clase_color }}"></span>
              <div class="timeline-event">
                <div class="timeline-header">
                  <h6 class="mb-0 ml-1 fw-bold">{{ $paso->nombre }}</h6>
                  <span class="badge rounded-pill bg-label-{{ $paso->clase_color }}">
                    {{ $paso->estado_nombre }}
                  </span>
                </div>
                <small class="text-muted"><i class="ti ti-calendar"></i> {{ $paso->estado_fecha ?  Carbon\Carbon::parse($paso->estado_fecha)->locale('es')->isoFormat(('DD MMMM Y')) : 'Sin dato' }}</small>
                <p class="mb-2 d-none"><b>Detalle: </b>{{ $paso->detalle_paso }}</p>

                @if($paso->detalle_paso)
                <div class="accordion mt-3" id="accordionExample">
                  <div class="card accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                      <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionOne" aria-expanded="true" aria-controls="accordionOne">
                        Detalle
                      </button>
                    </h2>

                    <div id="accordionOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                      <div class="accordion-body">
                        {{ $paso->detalle_paso }}
                      </div>
                    </div>
                  </div>
                </div>
                @endif
            </li>
            @endforeach
            @endif
          </ul>
        </div>
      </div>
      <!--/ Procesos de crecimiento -->
      @endif
    </div>

    <div class="col-md-6">
      <!-- Grupos -->
      @if($rolActivo->hasPermissionTo('personas.panel_asignar_grupo_al_asistente'))
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between">
          <p class="card-text text-uppercase fw-bold"><i class="ti ti-users-group ms-n1 me-2"></i>Grupos</p>
        </div>
        <div class="card-body pb-20">
          @livewire('Grupos.grupos-para-busqueda', [
          'id' => 'inputGrupos',
          'class' => 'col-12 col-md-12 mb-3',
          'label' => 'Seleccione el grupo donde asiste la persona',
          'conDadosDeBaja' => 'no',
          'gruposSeleccionadosIds' => $gruposDondeAsisteIds,
          'multiple' => TRUE,
          'validarPrivilegiosTipoGrupo' => TRUE,
          'tieneInformeDeVinculacion' => TRUE,
          'tieneInformeDeDesvinculacion' => TRUE,
          'usuario' => $usuario
          ])

        </div>
      </div>
      @endif
      <!--/ Grupos -->

      <!-- Tipo usuarios independientes -->
      @if($rolActivo->hasPermissionTo('personas.ver_panel_asignar_tipo_usuario'))
      <div class="card ">
        <div class="card-header d-flex justify-content-between">
          <p class="card-text text-uppercase fw-bold"><i class="ti ti-checkbox ms-n1 me-2"></i>Asignar roles independientes</p>
        </div>
        <div class="card-body pb-20">
          <div class="table-responsive">
            <table class="table table-flush-spacing">
              <tbody>
                <tr>
                  <td class=""></td>
                  <td class="text-nowrap fw-medium fw-bold text-center">
                    ¿Asignar?
                  </td>
                </tr>
                @foreach( $rolesNoDependientes as $rol)
                <tr>
                  <td class="text-nowrap fw-medium">{{ $rol->name }}</td>
                  <td class="text-center">
                    <label class="switch switch-lg">
                      <input id="rolDependiente{{$rol->id}}" name="rolDependiente{{$rol->id}}" type="checkbox" class="switch-input" />
                      <span class="switch-toggle-slider">
                        <span class="switch-on">Si</span>
                        <span class="switch-off">No</span>
                      </span>
                      <span class="switch-label"></span>
                    </label>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      @endif
      <!--/ Tipo usuarios independientes -->
    </div>
  </div>

  <!-- botonera -->
  <div class="d-flex mb-1 mt-5">
    <div class="me-auto">
      <button type="submit" class="btn btn-primary me-1 btnGuardar">Guardar</button>
      <button type="reset" class="btn btn-label-secondary">Cancelar</button>
    </div>
    <div class="p-2 bd-highlight">
      <p class="text-muted"><span class="badge badge-dot bg-info me-1"></span> Campos obligatorios</p>
    </div>
  </div>
  <!-- /botonera -->


</form>

@endsection
