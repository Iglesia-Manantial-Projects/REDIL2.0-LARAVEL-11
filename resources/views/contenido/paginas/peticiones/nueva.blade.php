@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Peticiones')

<!-- Page -->
@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
@endsection

@section('page-script')
<script>
$(document).ready(function() {
    $('#select2').select2({
      width: '100px',
      allowClear: true,
      placeholder: 'Ninguna'
    });
  });
</script>

<script type="text/javascript">
  function sinComillas(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    patron =/[\x5C'"]/;
    te = String.fromCharCode(tecla);
    return !patron.test(te);
  }
</script>

<script type="text/javascript">
  $('#formulario').submit(function(){
    $('.btnGuardar').attr('disabled','disabled');

    Swal.fire({
      title: "Espera un momento",
      text: "Ya estamos guardando...",
      icon: "info",
      showCancelButton: false,
      showConfirmButton: false,
      showDenyButton: false
    });
  });
</script>
@endsection

@section('content')
<h4 class="mb-1">Nueva petición</h4>
<p class="mb-4">Descripción...</p>

@include('layouts.status-msn')

<form id="formulario" role="form" class="forms-sample" method="POST" action="{{ route('peticion.crear') }}" enctype="multipart/form-data">
  @csrf

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


  <div class=" col-12 col-md-12">

    <!-- Persona -->
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between">
        <p class="card-text text-uppercase fw-bold">Información principal</p>
      </div>
      <div class="card-body pb-20 row">

        <div class="mb-3 col-12 col-md-12">
          @livewire('Usuarios.usuarios-para-busqueda', [
            'id' => 'persona',
            'class' => 'col-12 col-md-12 mb-3',
            'label' => '¿De quién es la petición?',
            'tipoBuscador' => 'unico',
            'queUsuariosCargar' => $queUsuariosCargar,
            'conDadosDeBaja' => 'no',
            'modulo' => 'peticiones',
            'obligatorio' => true,
            'usuarioSeleccionadoId' => old('persona') ?  old('persona') : ''
          ])
        </div>

        <!-- Tipos de petición -->
        <div class="mb-3 col-12 col-md-12">
          <label class="form-label" for="tipo_de_peticion">
            <span class="badge badge-dot bg-info me-1"></span>
            ¿Qué tipo de petición es?
          </label>
          <select id="tipo_de_peticion" name="tipo_de_petición" class="select2 form-select" data-allow-clear="true">
            <option value="" selected>Ninguno</option>
            @foreach ($tiposPeticiones as $tipoPeticion)
            <option value="{{$tipoPeticion->id}}" {{ old('tipo_de_grupo')==$tipoPeticion->id ? 'selected' : '' }}>{{$tipoPeticion->nombre}}</option>
            @endforeach
          </select>
          @if($errors->has('tipo_de_petición')) <div class="text-danger form-label">{{ $errors->first('tipo_de_petición') }}</div> @endif
        </div>
        <!-- Tipos de petición -->

        <!--  Escribe la petición -->
        <div class="mb-3 col-12 col-md-12">
          <label class="form-label" for="descripcion">
            <span class="badge badge-dot bg-info me-1"></span>
            Escribe la petición
          </label>
          <textarea onkeypress="return sinComillas(event)" id="descripcion" name="descripción" class="form-control" rows="2" spellcheck="false" data-ms-editor="true" placeholder="">{{ old('adiccional') }}</textarea>
          @if($errors->has('descripción')) <div class="text-danger form-label">{{ $errors->first('descripción') }}</div> @endif
        </div>
         <!--  Escribe la petición -->


      </div>
    </div>
    <!--/ Persona -->
  </div>
  </div>


  <!-- botonera -->
  <div class="d-flex mb-1 mt-2">
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
