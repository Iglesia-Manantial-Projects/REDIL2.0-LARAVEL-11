@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Grupos')

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
<script type="text/javascript">

  $(".fecha-picker").flatpickr({
    dateFormat: "Y-m-d"
  });

  $(".hora-picker").flatpickr({
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
  });

  function sinComillas(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    patron = /[\x5C'"]/;
    te = String.fromCharCode(tecla);
    return !patron.test(te);
  }

  $(document).ready(function() {
    $('.select2').select2({
      width: '100px',
      allowClear: true,
      placeholder: 'Ninguno'
    });
  });
</script>
@endsection

@section('content')

<div class="row mb-2">
  <ul class="nav nav-pills mb-3 d-flex justify-content-end" role="tablist">

    @if($rolActivo->hasPermissionTo('grupos.pestana_actualizar_grupo'))
    <li class="nav-item">
      <a href="{{ route('grupo.modificar',$grupo) }}">
        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-home" aria-controls="navs-pills-justified-home" aria-selected="true">
          <span class="badge rounded-pill badge-center h-px-10 w-px-10 bg-label-primary ms-1 mx-1">1</span>
          Datos principales
        </button>
      </a>
    </li>
    @endif

    @if($rolActivo->hasPermissionTo('grupos.pestana_anadir_lideres_grupo'))
    <li class="nav-item">
      <a href="{{ route('grupo.gestionarEncargados',$grupo) }}">
        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-home" aria-controls="navs-pills-justified-home" aria-selected="true">
          <span class="badge rounded-pill badge-center h-px-10 w-px-10 bg-label-primary ms-1 mx-1">2</span>
          Encargados
        </button>
      </a>
    </li>
    @endif

    @if($rolActivo->hasPermissionTo('grupos.pestana_anadir_integrantes_grupo'))
    <li class="nav-item">
      <a href="{{ route('grupo.gestionarIntegrantes',$grupo) }}">
        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-home" aria-controls="navs-pills-justified-home" aria-selected="true">
          <span class="badge rounded-pill badge-center h-px-10 w-px-10 bg-label-primary ms-1 mx-1">3</span>
          Integrantes
        </button>
      </a>
    </li>
    @endif

    @if($rolActivo->hasPermissionTo('grupos.pestana_georreferencia_grupo'))
    <li class="nav-item">
      <a href="{{ route('grupo.georreferencia',$grupo) }}">
        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-home" aria-controls="navs-pills-justified-home" aria-selected="true">
          <span class="badge rounded-pill badge-center h-px-10 w-px-10 bg-label-primary ms-1 mx-1">4</span>
          Georeferencia
        </button>
      </a>
    </li>
    @endif

  </ul>
</div>

<h4 class="mb-1">Gestionar integrantes</h4>
<p class="mb-4">Aquí podras gestionar las personas que asisten al grupo.</p>


<div class="row">
  <div class="col-12">
    <!-- Integrantes -->
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between">
        <p class="card-text text-uppercase fw-bold"><i class="ti ti-user ms-n1 me-2"></i>Integrantes</p>
      </div>
      <div class="card-body pb-20 row">
        @livewire('Usuarios.usuarios-para-busqueda', [
          'id' => 'integrantes',
          'class' => 'col-12 col-md-12 mb-3',
          'label' => 'Seleccione los integrantes que asisten a este grupo',
          'tipoBuscador' => 'multiple',
          'queUsuariosCargar' => $queUsuariosCargar,
          'conDadosDeBaja' => 'no',
          'modulo' => 'integrantes-grupo',
          'grupo' => $grupo,
          'usuariosSeleccionadosIds' => $idsIntegrantesSeleccionados,
          'validarPrivilegiosTipoGrupo' => TRUE,
          'tieneInformeDeVinculacion' => TRUE,
          'tieneInformeDeDesvinculacion' => TRUE
        ])
      </div>
    </div>
    <!--/ Encargados -->
  </div>
</div>


@endsection
