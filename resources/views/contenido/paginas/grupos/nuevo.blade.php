@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Grupos')

<!-- Page -->

<!-- Vendor Styles -->
@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
  'resources/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.scss',
  'resources/assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.scss',
  'resources/assets/vendor/libs/jquery-timepicker/jquery-timepicker.scss',
  'resources/assets/vendor/libs/pickr/pickr-themes.scss',
  'resources/assets/vendor/libs/select2/select2.scss',
])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/moment/moment.js',
  'resources/assets/vendor/libs/flatpickr/flatpickr.js',
  'resources/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js',
  'resources/assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js',
  'resources/assets/vendor/libs/jquery-timepicker/jquery-timepicker.js',
  'resources/assets/vendor/libs/pickr/pickr.js',
  'resources/assets/vendor/libs/select2/select2.js',
])
@endsection

<!-- Page Scripts -->
@section('page-script')
@vite(['resources/assets/js/prueba.js'])

@endsection


@section('content')
<h4 class="mb-1">Nuevo grupo</h4>
<p class="mb-4">Descripción...</p>

@include('layouts.status-msn')

<form id="formulario" role="form" class="forms-sample" method="POST" action="{{ route('grupo.crear') }}" enctype="multipart/form-data">
  @csrf

  <!-- botonera -->
  <div class="d-flex mb-1 mt-5">
    <div class="me-auto">
      <button class="btn btn-primary me-1 btnGuardar">Guardar</button>
      <button type="reset" class="btn btn-label-secondary">Cancelar</button>
    </div>
    <div class="p-2 bd-highlight">
      <p class="text-muted"><span class="badge badge-dot bg-info me-1"></span> Campos obligatorios</p>
    </div>
  </div>
  <!-- /botonera -->

  <div class="row">
    <div class="col-md-12">
      <div class="card mb-4">
        <h5 class="card-header">Información principal</h5>
        <div class="card-body row">
          <!-- nombre -->
          @if($configuracion->habilitar_nombre_grupo)
          <div class="mb-2 col-12 col-md-4">
            <label class="form-label" for="nombre">
              @if($configuracion->nombre_grupo_obligatorio)
              <span class="badge badge-dot bg-info me-1"></span>
              @endif
              Nombre
            </label>
            <input id="nombre" name="nombre" value="{{ old('nombre') }}" onkeypress="return sinComillas(event)" type="text" class="form-control" />
            @if($errors->has('nombre')) <div class="text-danger form-label">{{ $errors->first('nombre') }}</div> @endif
          </div>
          @endif
          <!-- nombre -->

          <!--  Tipo de grupo  -->
          @if($configuracion->habilitar_tipo_grupo)
          <div class="mb-3 col-12 col-md-4">
            <label class="form-label" for="tipo_grupo">
              @if($configuracion->tipo_grupo_obligatorio)<span class="badge badge-dot bg-info me-1"></span>@endif
              ¿Qué tipo de grupo es?
            </label>
            <select id="tipo_grupo" name="tipo_de_grupo" class="select2 form-select" data-allow-clear="true">
              <option value="" selected>Ninguno</option>
              @foreach ($tipoGrupos as $tipoGrupo)
              <option value="{{$tipoGrupo->id}}" {{ old('tipo_de_grupo')==$tipoGrupo->id ? 'selected' : '' }}>{{$tipoGrupo->nombre}}</option>
              @endforeach
            </select>
            @if($errors->has('tipo_de_grupo')) <div class="text-danger form-label">{{ $errors->first('tipo_de_grupo') }}</div> @endif
          </div>
          @endif
          <!--  Tipo de grupo  -->

          <!-- fecha -->
          @if($configuracion->habilitar_fecha_creacion_grupo)
          <div class="mb-2 col-12 col-md-4">
            <label class="form-label" for="fecha">
              @if($configuracion->fecha_creacion_grupo_obligatorio)
              <span class="badge badge-dot bg-info me-1"></span>
              @endif
              {{ $configuracion->label_fecha_creacion_grupo ? $configuracion->label_fecha_creacion_grupo : 'Fecha de creación'}}
            </label>
            <input id="fecha" value="{{ old('fecha') }}" placeholder="YYYY-MM-DD" name="fecha" class="fecha form-control fecha-picker" type="text" />
            @if($errors->has('fecha')) <div class="text-danger form-label">{{ $errors->first('fecha') }}</div> @endif
          </div>
          @endif
          <!-- fecha -->

          @if($configuracion->version==2)
          <!-- AMO -->
          <div class="mb-2 col-12 col-md-4">
            <div class=" small fw-medium mb-2">¿Este Grupo tiene AMO?</div>
            <label class="switch switch-lg">
              <input id="amo" name="amo" type="checkbox" @checked(old("amo")) class="switch-input" />
              <span class="switch-toggle-slider">
                <span class="switch-on">SI</span>
                <span class="switch-off">NO</span>
              </span>
              <span class="switch-label"></span>
            </label>
          </div>
          <!-- / AMO -->
          @endif

          <!-- Telefono -->
          @if($configuracion->habilitar_telefono_grupo)
          <div class="mb-2 col-12 col-md-4">
            <label class="form-label" for="telefono">
              @if($configuracion->telefono_grupo_obligatorio) <span class="badge badge-dot bg-info me-1"></span>@endif
              Teléfono
            </label>
            <div class="input-group input-group-merge">
              <span id="basic-icon-default-phone2" class="input-group-text"><i class="ti ti-phone"></i></span>
              <input id="telefono" name="teléfono" value="{{ old('teléfono') }}" type="text" class="form-control" spellcheck="false" data-ms-editor="true">
            </div>
            @if($errors->has('teléfono')) <div class="text-danger form-label">{{ $errors->first('teléfono') }}</div> @endif
          </div>
          @endif
          <!-- /Telefono fijo -->

          <!-- vivienda en calidad de -->
          @if($configuracion->habilitar_tipo_vivienda_grupo)
          <div class="mb-2 col-12 col-md-4">
            <label class="form-label" for="vivienda_en_calidad_de">
              @if($configuracion->tipo_vivienda_grupo_obligatorio) <span class="badge badge-dot bg-info me-1"></span>@endif
              Vivienda en calidad de
            </label>
            <select id="vivienda_en_calidad_de" name="tipo_de_vivienda" class="select2 form-select" data-allow-clear="true">
              <option  value="">Ninguno</option>
              @foreach ($tiposDeVivienda as $tipoDeVivienda)
              <option  value="{{$tipoDeVivienda->id}}" {{ old('tipo_de_vivienda')==$tipoDeVivienda->id ? 'selected' : '' }}>{{ucwords ($tipoDeVivienda->nombre)}}</option>
              @endforeach
            </select>
            @if($errors->has('tipo_de_vivienda')) <div class="text-danger form-label">{{ $errors->first('tipo_de_vivienda') }}</div> @endif
          </div>
          @endif
          <!-- /vivienda en calidad de -->

          <!-- Direccion -->
          @if($configuracion->habilitar_direccion_grupo == true)
            @if($configuracion->usa_listas_geograficas==TRUE)
              @livewire('Generales.direccion-con-lista-geografica', ['modulo' => 'grupos'])
            @else
              <div class="mb-2 col-12 col-md-6">
                <label class="form-label" for="direccion">
                  @if($configuracion->direccion_grupo_obligatorio) <span class="badge badge-dot bg-info me-1"></span>@endif
                  @if($configuracion->label_direccion_grupo!="")
                  {{$configuracion->label_direccion_grupo}}
                  @else
                  Dirección
                  @endif
                </label>
                <div class="input-group input-group-merge">
                  <span class="input-group-text"><i class="ti ti-map"></i></span>
                  <input onkeypress="return sinComillas(event)" id="direccion" name="dirección" value="{{ old('dirección') }}" type="text" class="form-control" spellcheck="false" data-ms-editor="true" placeholder="Digita la dirección, la ciudad y el país, donde vives.">
                </div>
                @if($errors->has('dirección')) <div class="text-danger form-label">{{ $errors->first('dirección') }}</div> @endif
              </div>
            @endif
          @endif
          <!-- Direccion -->

          <!-- Campo opcional -->
          @if($configuracion->habilitar_campo_opcional1_grupo)
          <div class="mb-2 col-12 col-md-12">
            <label class="form-label" for="campo_opcional1">
              @if($configuracion->campo_opcional1_obligatorio)<span class="badge badge-dot bg-info me-1"></span>@endif
              {{ $configuracion->label_campo_opcional1 }}
            </label>
            <textarea onkeypress="return sinComillas(event)" id="campo_opcional1" name="adiccional" class="form-control" rows="2" spellcheck="false" data-ms-editor="true" placeholder="">{{ old('adiccional') }}</textarea>
            @if($errors->has('adiccional')) <div class="text-danger form-label">{{ $errors->first('adiccional') }}</div> @endif
          </div>
          @endif
          <!-- /Campo opcional -->

        </div>
      </div>
    </div>

    @if($configuracion->habilitar_dia_reunion_grupo || $configuracion->habilitar_hora_reunion_grupo)
    <div class="col-md-12">
      <div class="card mb-4">
        <h5 class="card-header"> {{$configuracion->titulo_seccion_reunion_grupo ? $configuracion->titulo_seccion_reunion_grupo : '¿En qué horario se reúne grupo?'}}</h5>
        <div class="card-body row">

          <!-- fecha -->
          @if($configuracion->habilitar_dia_reunion_grupo)
          <div class="mb-3 col-12 col-md-6">
            <label class="form-label" for="dia_reunion">
              @if($configuracion->dia_reunion_grupo_obligatorio) <span class="badge badge-dot bg-info me-1"></span> @endif
              {{ $configuracion->label_campo_dia_reunion_grupo }}
            </label>
            <select id="dia_reunion" name="día_de_reunión" class="select2 form-select" data-allow-clear="true">
              <option value="" selected>Ninguno</option>
              @foreach (Helper::diasDeLaSemana() as $dia)
              <option value="{{$dia->id}}" {{ old('día_de_reunión')==$dia->id ? 'selected' : '' }}>{{$dia->nombre}}</option>
              @endforeach
            </select>
            @if($errors->has('día_de_reunión')) <div class="text-danger form-label">{{ $errors->first('día_de_reunión') }}</div> @endif
          </div>
          @endif
          <!-- /fecha -->

          <!-- hora -->
          @if($configuracion->habilitar_hora_reunion_grupo)
          <div class="mb-2 col-12 col-md-6">
            <label class="form-label" for="hora_reunion">
              @if($configuracion->habilitar_hora_reunion_grupo)<span class="badge badge-dot bg-info me-1"></span> @endif
              {{$configuracion->label_campo_hora_reunion_grupo }}
            </label>
            <input id="hora_reunion" value="{{ old('hora_de_reunión') }}" placeholder="HH-MM" name="hora_de_reunión" class="fecha form-control hora-picker" type="text" />
            @if($errors->has('hora_de_reunión')) <div class="text-danger form-label">{{ $errors->first('hora_de_reunión') }}</div> @endif
          </div>
          @endif
          <!-- /hora -->

        </div>
      </div>
    </div>
    @endif

    @if($configuracion->visible_seccion_campos_extra_grupo == TRUE && $rolActivo->hasPermissionTo('grupos.visible_seccion_campos_extra_grupo') )
    <div class="col-md-12">
      <div class="card mb-4">
        <h5 class="card-header"> {{$configuracion->label_seccion_campos_extra}} </h5>
        <div class="card-body row">

          @foreach($camposExtras as $campo)
            @if($campo->visible != FALSE)
              <div class="mb-2 {{$campo->class_col}}">
                <label class="form-label" for="{{$campo->class_id}}">
                  @if($campo->required) <span class="badge badge-dot bg-info me-1"></span> @endif {{$campo->nombre}}
                </label>

                <!-- campo tipo 1 -->
                @if($campo->tipo_de_campo == 1 && $campo->visible)
                  <input id="{{$campo->class_id}}" name="{{$campo->class_id}}" value="{{ old($campo->class_id) }}" class="form-control">
                @endif
                <!-- /campo tipo 1 -->

                <!-- campo tipo 2 -->
                @if($campo->tipo_de_campo == 2 && $campo->visible)
                  <textarea id="{{$campo->class_id}}" name="{{$campo->class_id}}" class="form-control">{{ old($campo->class_id) }}</textarea>
                @endif
                <!-- /campo tipo 2 -->

                <!-- campo tipo 3 -->
                @if($campo->tipo_de_campo == 3 && $campo->visible)
                  <select id="{{$campo->class_id}}" name="{{$campo->class_id}}" class="form-control">
                    <option value="">Ninguno</option>
                    @foreach (json_decode($campo->opciones_select) as $opcion)
                      <option value="{{$opcion->value}}" {{ old($campo->class_id)==$opcion->value ? 'selected' : '' }} > {{ ucwords($opcion->nombre) }} </option>
                    @endforeach
                  </select>
                @endif
                <!-- /campo tipo 3 -->

                <!-- campo tipo 4 -->
                @if($campo->tipo_de_campo == 4 && $campo->visible)
                  <select id="{{$campo->class_id}}" name="{{$campo->class_id}}[]" multiple class="select2 form-control">
                    @foreach (json_decode($campo->opciones_select) as $opcion)
                      <option value="{{$opcion->value}}" {{ in_array($opcion->value, old($campo->class_id, []))  ? "selected" : "" }}>  {{ ucwords($opcion->nombre) }} </option>
                    @endforeach
                  </select>
                @endif
                <!-- /campo tipo 4 -->

                @if($errors->has($campo->class_id)) <div class="text-danger form-label">{{ $errors->first($campo->class_id) }}</div> @endif
              </div>
            @endif
          @endforeach

        </div>
      </div>
    </div>
    @endif

  </div>


  <!-- botonera -->
  <div class="d-flex mb-1 mt-5">
    <div class="me-auto">
      <button  class="btn btn-primary me-1 btnGuardar">Guardar</button>
      <button type="reset" class="btn btn-label-secondary">Cancelar</button>
    </div>
    <div class="p-2 bd-highlight">
      <p class="text-muted"><span class="badge badge-dot bg-info me-1"></span> Campos obligatorios</p>
    </div>
  </div>
  <!-- /botonera -->

</form>

@endsection
