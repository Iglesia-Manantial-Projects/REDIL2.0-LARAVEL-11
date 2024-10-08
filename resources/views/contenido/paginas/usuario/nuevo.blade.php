@php
$configData = Helper::appClasses();
@endphp

@extends($layout)

@section('title', 'Nuevo usuario')

@section('vendor-style')
@vite([
'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
'resources/assets/vendor/libs/select2/select2.scss',
'resources/assets/vendor/libs/cropperjs/cropper.css',
])
@endsection

@section('vendor-script')
@vite([
'resources/assets/vendor/libs/flatpickr/flatpickr.js',
'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
'resources/assets/vendor/libs/select2/select2.js',
'resources/assets/vendor/libs/cropperjs/cropper.js',
])
@endsection

@section('page-script')
@vite([
'resources/assets/js/form-basic-inputs.js',
'resources/assets/vendor/libs/cropperjs/cropper.js',
])

@vite(['resources/assets/js/contenido/usuarios/nuevo.js'])


@endsection

@section('content')
<div class="row {{$formulario->es_formulario_exterior ? 'p-4 m-0' : '' }} ">
  @if($formulario->es_formulario_exterior)
  <!-- banner -->
  <div class="row m-0 p-0 pb-2">
    <div class="col-12">
      <div class="card">
        <div class="d-flex justify-content-center">
          <center>
            <img src="{{ $configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/img/banner-formulario/banner-formulario-nuevo.jpg') : $configuracion->ruta_almacenamiento.'/img/banner-formulario/banner-formulario-nuevo.jpg' }}" alt="Banner image" class="rounded-top img-fluid">
          </center>
        </div>
      </div>
    </div>
  </div>
  <!--/ banner -->
  @endif

  <h4 class="mb-1 mayusculas {{$formulario->es_formulario_exterior ? 'd-none' : '' }}">{{$formulario->nombre}}</h4>
  <p class="mb-4 {{$formulario->es_formulario_exterior ? 'd-none' : '' }}">{{$formulario->descripcion}}</p>

  @include('layouts.status-msn')

  <form id="formulario" role="form" class="forms-sample" method="POST" action="{{ route($formulario->action, $formulario->id) }}"  enctype="multipart/form-data" >
    @csrf
    <!-- botonera -->
    <div class="d-flex mb-1 mt-5">
      <div class="me-auto">
        <button type="submit" class="btn btn-primary me-1 btnGuardar">Guardar</button>
        <a type="reset" href="{{ url()->previous() }}" class="btn btn-label-secondary">Cancelar</a>
      </div>
      <div class="p-2 bd-highlight">
        <p class="text-muted"><span class="badge badge-dot bg-info me-1"></span> Campos obligatorios</p>
      </div>
    </div>
    <!-- /botonera -->

    <!--  Información basica -->
    @if($formulario->visible_seccion_1)
      <div class="col-md-12">
        <div class="card mb-4">
          <h5 class="card-header">{{$formulario->label_seccion_1}}</h5>
          <div class="card-body">

            <!-- foto -->
            @if($formulario->visible_foto)
            <div class="col-10 offset-1 col-sm-4 offset-sm-4 col-lg-2 offset-lg-5 mb-3">
              <center>
                <img id="preview-foto" class="w-100 cropped-img mt-2 mb-2 rounded-circle" src="{{ Storage::url($configuracion->ruta_almacenamiento.'/img/foto-usuario/default-m.png') }}" alt="foto perfil">
                <button type="button" class="btn btn-icon-text btn-primary" data-bs-toggle="modal" data-bs-target="#modalFoto">
                  <i class="ti ti-camera px-1"></i>Subir foto
                </button>
              </center>
              <input class="form-control d-none" type="text" value="{{ old('foto') }}" id="imagen-recortada" name="foto">
            </div>
            @endif
            <!-- foto -->

            <div class="row">
              <!-- fecha nacimiento -->
              @livewire('Usuarios.formularios.fecha-nacimiento', ['formulario' => $formulario, 'fechaDefault' => $fechaDefault])
              <!-- fecha nacimiento -->

              <!--  Tipo de id  -->
              @if($formulario->visible_tipo_identificacion)
                <div class="mb-3 {{$formulario->class_tipo_identificacion}}">
                  <label class="form-label" for="tipo_identificacion">
                    @if($formulario->obligatorio_tipo_identificacion)<span class="badge badge-dot bg-info me-1"></span>@endif
                    @if($formulario->label_tipo_identificacion!="")
                    {{$formulario->label_tipo_identificacion}}
                    @else
                    Tipo identificación
                    @endif
                  </label>
                  <select id="tipo_identificacion" name="tipo_identificación" class="select2 form-select" data-allow-clear="true">
                      <option value="" selected>Ninguno</option>
                      @foreach ($tiposIdentificaciones as $tipoIdentificacion)
                      <option  value="{{$tipoIdentificacion->id}}" {{ old('tipo_identificación')==$tipoIdentificacion->id ? 'selected' : '' }}>{{$tipoIdentificacion->nombre}}</option>
                      @endforeach
                  </select>
                  @if($errors->has('tipo_identificación')) <div class="text-danger form-label">{{ $errors->first('tipo_identificación') }}</div> @endif
                </div>
              @endif
              <!--  Tipo de id  -->

              <!-- identificacion -->
              @if($formulario->visible_identificacion)
                <div class="mb-2 {{$formulario->class_identificacion}}">
                  <label class="form-label" for="identificacion">
                    @if($formulario->obligatorio_identificacion)<span class="badge badge-dot bg-info me-1"></span>@endif
                    @if($formulario->label_identificacion!="")
                    {{$formulario->label_identificacion}}
                    @else
                    Identificación
                    @endif
                  </label>
                  <input id="identificacion" name="identificación" value="{{ old('identificación') }}" onkeyup="javascript:this.value=this.value.replace('.', '').replace(' ', '')" type="text" class="form-control" autocomplete="off"/>
                  @if($errors->has('identificación')) <div class="text-danger form-label">{{ $errors->first('identificación') }}</div> @endif
                </div>
              @endif
              <!-- /identificacion -->

              <!-- /Email -->
              @if($formulario->visible_email)
                <div class="mb-2 form-group {{$formulario->class_email}}">
                  <label class="form-label" for="email">
                    @if($formulario->obligatorio_email)<span class="badge badge-dot bg-info me-1"></span>@endif
                    @if($formulario->label_email != "")
                    {{$formulario->label_email}}
                    @else
                    E-mail
                    @endif
                  </label>
                  <div class="input-group input-group-merge">
                    <input type="email" id="email" name="email" value="{{ old('email') }}" onkeyup="javascript:this.value=this.value.toLowerCase()"  class="form-control"/>
                  </div>
                  @if($errors->has('email')) <div class="text-danger form-label">{{ $errors->first('email') }}</div> @endif

                </div>
              @endif
              <!-- /Email -->

              <!-- Primer Nombre -->
              @if($formulario->visible_primer_nombre)
                <div class="mb-2 {{$formulario->class_primer_nombre}}">
                  <label class="form-label" for="primer_nombre">
                    @if($formulario->obligatorio_primer_nombre)<span class="badge badge-dot bg-info me-1"></span>@endif
                    @if($formulario->label_primer_nombre!="")
                    {{$formulario->label_primer_nombre}}
                    @else
                    Primer nombre
                    @endif
                  </label>
                  <input id="primer_nombre" name="primer_nombre" value="{{ old('primer_nombre') }}" type="text" class="form-control"/>
                  @if($errors->has('primer_nombre')) <div class="text-danger form-label">{{ $errors->first('primer_nombre') }}</div> @endif
                </div>
              @endif
              <!-- /Primer Nombre  -->

              <!-- Segundo Nombre  -->
              @if($formulario->visible_segundo_nombre)
                <div class="mb-2 {{$formulario->class_segundo_nombre}}">
                    <label class="form-label" for="segundo_nombre">
                      @if($formulario->obligatorio_segundo_nombre)<span class="badge badge-dot bg-info me-1"></span>@endif
                      @if($formulario->label_segundo_nombre!="")
                      {{$formulario->label_segundo_nombre}}
                      @else
                      Segundo nombre
                      @endif
                    </label>
                    <input id="segundo_nombre" name="segundo_nombre" value="{{ old('segundo_nombre') }}" type="text" class="form-control" />
                    @if($errors->has('segundo_nombre')) <div class="text-danger form-label">{{ $errors->first('segundo_nombre') }}</div> @endif
                </div>
              @endif
              <!-- /Segundo Nombre -->

              <!-- Primer apellido -->
              @if($formulario->visible_primer_apellido)
                <div class="mb-2 {{$formulario->class_primer_apellido}}">
                  <label class="form-label" for="primer_apellido">
                    @if($formulario->obligatorio_primer_apellido)<span class="badge badge-dot bg-info me-1"></span>@endif
                    @if($formulario->label_primer_apellido!="")
                    {{$formulario->label_primer_apellido}}
                    @else
                    Primer apellido
                    @endif
                  </label>
                  <input id="primer_apellido" name="primer_apellido" value="{{ old('primer_apellido') }}" type="text" class="form-control"/>
                  @if($errors->has('primer_apellido')) <div class="text-danger form-label">{{ $errors->first('primer_apellido') }}</div> @endif
                </div>
              @endif
              <!-- /Primer apellido  -->

              <!-- Segundo apellido  -->
              @if($formulario->visible_segundo_apellido)
                <div class="mb-2 {{$formulario->class_segundo_apellido}}">
                  <label class="form-label" for="segundo_apellido">
                    @if($formulario->obligatorio_segundo_apellido)<span class="badge badge-dot bg-info me-1"></span>@endif
                    @if($formulario->label_segundo_apellido!="")
                    {{$formulario->label_segundo_apellido}}
                    @else
                    Segundo apellido
                    @endif
                  </label>
                  <input id="segundo_apellido" name="segundo_apellido" value="{{ old('segundo_apellido') }}" type="text" class="form-control" />
                  @if($errors->has('segundo_apellido')) <div class="text-danger form-label">{{ $errors->first('segundo_apellido') }}</div> @endif
                </div>
              @endif
              <!-- /Segundo apellido -->

              <!-- Genero sexual -->
              @if($formulario->visible_genero)
                <div class="mb-2 {{$formulario->class_genero}}">
                  <label class="form-label" for="genero">
                    @if($formulario->obligatorio_genero)<span class="badge badge-dot bg-info me-1"></span>@endif
                    @if($formulario->label_genero!="")
                    {{$formulario->label_genero}}
                    @else
                    Sexo
                    @endif
                  </label>
                  <select id="genero" name="genero" class="grupoSelect select2 selectorGenero form-select" data-allow-clear="true">
                    <option id="genero-m" value="0" {{ old('genero')==0 ? 'selected' : '' }}>Masculino</option>
                    <option id="genero-f" value="1" {{ old('genero')==1 ? 'selected' : '' }}>Femenino</option>
                  </select>
                </div>
              @endif
              <!-- /Genero sexual -->

              <!-- Estado Civil -->
              @if($formulario->visible_estado_civil)
                <div  class="mb-2 {{$formulario->class_estado_civil}}">
                  <label class="form-label" for="estado_civil">
                    @if($formulario->obligatorio_estado_civil) <span class="badge badge-dot bg-info me-1"></span> @endif
                    @if($formulario->label_estado_civil!="")
                    {{$formulario->label_estado_civil}}
                    @else
                    Estado civil
                    @endif
                  </label>
                  <select id="estado_civil" name="estado_civil" class="select2 form-select" data-allow-clear="true">
                    <option value="" selected >Ninguno</option>
                    @foreach ($tiposDeEstadosCiviles as $tiposDeEstadoCivil)
                    <option  value="{{$tiposDeEstadoCivil->id}}" {{ old('estado_civil')==$tiposDeEstadoCivil->id ? 'selected' : '' }} >{{$tiposDeEstadoCivil->nombre}}</option>
                    @endforeach
                  </select>
                  @if($errors->has('estado_civil')) <div class="text-danger form-label">{{ $errors->first('estado_civil') }}</div> @endif
                </div>
              @endif
              <!-- /Estado Civil -->

              <!-- Nacionalidad -->
              @if($formulario->visible_pais_nacimiento)
                <div class="mb-2 {{$formulario->class_pais_nacimiento}}">
                  <label class="form-label" for="pais_nacimiento">
                    @if($formulario->obligatorio_pais_nacimiento) <span class="badge badge-dot bg-info me-1"></span>@endif
                    @if($formulario->label_pais_nacimiento!="")
                    {{$formulario->label_pais_nacimiento}}
                    @else
                    País de nacimiento
                    @endif
                  </label>
                  <select id="pais_nacimiento" name="país" class="select2 form-select" data-allow-clear="true">
                    <option  value="">Ninguno</option>
                    @foreach ($paises as $pais)
                    <option  value="{{$pais->id}}" {{ old('país')==$pais->id ? 'selected' : '' }} >{{ucwords ($pais->nombre)}}</option>
                    @endforeach
                  </select>
                  @if($errors->has('país')) <div class="text-danger form-label">{{ $errors->first('país') }}</div> @endif
                </div>
              @endif
              <!-- /Nacionalidad -->

            </div>

          </div>
        </div>
      </div>
    @endif
    <!-- / Información basica -->

    <!--  Información de contacto -->
    @if($formulario->visible_seccion_2)
      <div class="col-md-12">
        <div class="card mb-4">
          <h5 class="card-header">{{$formulario->label_seccion_2}}</h5>
          <div class="card-body">
            <div class="row">

              <!-- Telefono fijo -->
              @if($formulario->visible_telefono_fijo)
                <div class="mb-2 {{$formulario->class_telefono_fijo}}">
                  <label class="form-label" for="telefono_fijo">
                    @if($formulario->obligatorio_telefono_fijo) <span class="badge badge-dot bg-info me-1"></span>@endif
                    @if($formulario->label_telefono_fijo!="")
                    {{$formulario->label_telefono_fijo}}
                    @else
                    Teléfono fijo
                    @endif
                  </label>
                  <div class="input-group input-group-merge">
                    <span id="basic-icon-default-phone2" class="input-group-text"><i class="ti ti-phone"></i></span>
                    <input id="telefono_fijo" name="teléfono_fijo" value="{{ old('teléfono_fijo') }}" type="text" class="form-control" spellcheck="false" data-ms-editor="true">
                  </div>
                  @if($errors->has('teléfono_fijo')) <div class="text-danger form-label">{{ $errors->first('teléfono_fijo') }}</div> @endif
                </div>
              @endif
              <!-- /Telefono fijo -->

              <!-- Telefono Movil #1 -->
              @if($formulario->visible_telefono_movil)
                <div class="mb-2 {{$formulario->class_telefono_movil}}">
                  <label class="form-label" for="telefono_movil">
                    @if($formulario->obligatorio_telefono_movil)<span class="badge badge-dot bg-info me-1"></span>@endif
                    @if($formulario->label_telefono_movil !="")
                    {{$formulario->label_telefono_movil}}
                    @else
                    Teléfono movil
                    @endif
                  </label>
                  <div class="input-group input-group-merge">
                    <span id="basic-icon-default-phone2" class="input-group-text"><i class="ti ti-device-mobile"></i></span>
                    <input id="telefono_movil" name="teléfono_móvil" value="{{ old('teléfono_móvil') }}" type="text" class="form-control" spellcheck="false" data-ms-editor="true">
                  </div>
                  @if($errors->has('teléfono_móvil')) <div class="text-danger form-label">{{ $errors->first('teléfono_móvil') }}</div> @endif
                </div>
              @endif
              <!-- /Telefono Movil #1 -->

              <!-- Telefono  otro Telefono -->
              @if($formulario->visible_telefono_otro)
                <div class="mb-2 {{$formulario->class_telefono_otro}}">
                  <label class="form-label" for="telefono_otro">
                    @if($formulario->obligatorio_telefono_otro)<span class="badge badge-dot bg-info me-1"></span>@endif
                    @if($formulario->label_telefono_otro != "")
                    {{$formulario->label_telefono_otro}}
                    @else
                    Otro teléfono
                    @endif
                  </label>
                  <div class="input-group input-group-merge">
                    <span id="basic-icon-default-phone2" class="input-group-text"><i class="ti ti-phone"></i></span>
                    <input id="telefono_otro" name="teléfono_otro" value="{{ old('teléfono_otro') }}" type="text" class="form-control" spellcheck="false" data-ms-editor="true">
                  </div>
                  @if($errors->has('teléfono_otro')) <div class="text-danger form-label">{{ $errors->first('teléfono_otro') }}</div> @endif
                </div>
              @endif
              <!-- /Telefono otro Telefono -->

              <!-- vivienda_en_calidad_de -->
              @if($formulario->visible_vivienda_en_calidad_de)
                <div class="mb-2 {{$formulario->class_vivienda_en_calidad_de}}">
                  <label class="form-label" for="vivienda_en_calidad_de">
                    @if($formulario->obligatorio_vivienda_en_calidad_de) <span class="badge badge-dot bg-info me-1"></span>@endif
                    @if($formulario->label_vivienda_en_calidad_de!="")
                    {{$formulario->label_vivienda_en_calidad_de}}
                    @else
                    Vivienda en calidad de
                    @endif
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
              <!-- /vivienda_en_calidad_de -->

              <!-- Direccion -->
              @if($formulario->visible_direccion == TRUE)
                @if($configuracion->usa_listas_geograficas==TRUE)
                  @livewire('Generales.direccion-con-lista-geografica', ['formulario' => $formulario])
                @else
                  <div class="mb-2 {{$formulario->class_direccion}}">
                    <label class="form-label" for="direccion">
                      @if($formulario->obligatorio_direccion) <span class="badge badge-dot bg-info me-1"></span>@endif
                      @if($formulario->label_direccion!="")
                      {{$formulario->label_direccion}}
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
              <!-- /Direccion -->

            </div>
          </div>
        </div>
      </div>
    @endif
    <!-- / Información de contacto -->

    <!--  Información académica y laboral  -->
    @if($formulario->visible_seccion_3)
      <div class="col-md-12">
        <div class="card mb-4">
          <h5 class="card-header">{{$formulario->label_seccion_3}}</h5>
          <div class="card-body">
            <div class="row">
              <!-- Nivel academico -->
              @if($formulario->visible_nivel_academico)
                <div class="mb-2 {{$formulario->class_nivel_academico}}">
                  <label class="form-label" for="nivel_academico">
                    @if($formulario->obligatorio_nivel_academico) <span class="badge badge-dot bg-info me-1"></span>@endif
                    @if($formulario->label_nivel_academico!="")
                    {{$formulario->label_nivel_academico}}
                    @else
                    Nivel académico
                    @endif
                  </label>
                  <select id="nivel_academico" name="nivel_académico" class="select2 form-select" data-allow-clear="true">
                    <option  value="">Ninguno</option>
                    @foreach ($nivelesAcademicos as $nivelAcademico)
                    <option  value="{{$nivelAcademico->id}}" {{ old('nivel_académico')==$nivelAcademico->id ? 'selected' : '' }}>{{ucwords ($nivelAcademico->nombre)}}</option>
                    @endforeach
                  </select>
                  @if($errors->has('nivel_académico')) <div class="text-danger form-label">{{ $errors->first('nivel_académico') }}</div> @endif
                </div>
              @endif
              <!-- /Nivel academico -->

              <!-- Estado Nivel Academico -->
              @if($formulario->visible_estado_nivel_academico)
                <div class="mb-2 {{$formulario->class_estado_nivel_academico}}">
                  <label class="form-label" for="estado_nivel_academico">
                    @if($formulario->obligatorio_estado_nivel_academico) <span class="badge badge-dot bg-info me-1"></span>@endif
                    @if($formulario->label_estado_nivel_academico!="")
                    {{$formulario->label_estado_nivel_academico}}
                    @else
                    Estado nivel académico
                    @endif
                  </label>
                  <select id="estado_nivel_academico" name="estado_nivel_académico" class="select2 form-select" data-allow-clear="true">
                    <option  value="">Ninguno</option>
                    @foreach ($estadosNivelesAcademicos as $estadoNivelAcademico)
                    <option  value="{{$estadoNivelAcademico->id}}" {{ old('estado_nivel_académico')==$estadoNivelAcademico->id ? 'selected' : '' }}>{{ucwords ($estadoNivelAcademico->nombre)}}</option>
                    @endforeach
                  </select>
                  @if($errors->has('estado_nivel_académico')) <div class="text-danger form-label">{{ $errors->first('estado_nivel_académico') }}</div> @endif
                </div>
              @endif
              <!-- /Estado Nivel Academico -->

              <!-- Profesión -->
              @if($formulario->visible_profesion)
                <div class="mb-2 {{$formulario->class_profesion}}">
                  <label class="form-label" for="profesion">
                    @if($formulario->obligatorio_profesion) <span class="badge badge-dot bg-info me-1"></span>@endif
                    @if($formulario->label_profesion!="")
                    {{$formulario->label_profesion}}
                    @else
                    Profesión
                    @endif
                  </label>
                  <select id="profesion" name="profesión" class="select2 form-select" data-allow-clear="true">
                    <option  value="">Ninguno</option>
                    @foreach ($profesiones as $profesion)
                    <option  value="{{$profesion->id}}" {{ old('profesión')==$profesion->id ? 'selected' : '' }}>{{ucwords ($profesion->nombre)}}</option>
                    @endforeach
                  </select>
                  @if($errors->has('profesión')) <div class="text-danger form-label">{{ $errors->first('profesión') }}</div> @endif
                </div>
              @endif
              <!-- /Profesión -->

              <!-- Ocupación -->
              @if($formulario->visible_ocupacion)
              <div class="mb-2 {{$formulario->class_ocupacion}}">
                <label class="form-label" for="ocupacion">
                  @if($formulario->obligatorio_ocupacion) <span class="badge badge-dot bg-info me-1"></span>@endif
                  @if($formulario->label_ocupacion!="")
                  {{$formulario->label_ocupacion}}
                  @else
                  Ocupación
                  @endif
                </label>
                <select id="ocupacion" name="ocupación" class="select2 form-select" data-allow-clear="true">
                  <option  value="">Ninguno</option>
                  @foreach ($ocupaciones as $ocupacion)
                  <option  value="{{$ocupacion->id}}" {{ old('ocupación')==$ocupacion->id ? 'selected' : '' }} >{{ucwords ($ocupacion->nombre)}}</option>
                  @endforeach
                </select>
                @if($errors->has('ocupación')) <div class="text-danger form-label">{{ $errors->first('ocupación') }}</div> @endif
              </div>
              @endif
              <!-- /Ocupación -->

              <!-- Sector económico -->
              @if($formulario->visible_sector_economico)
              <div class="mb-2 {{$formulario->class_sector_economico}}">
                <label class="form-label" for="sector_economico">
                  @if($formulario->obligatorio_sector_economico) <span class="badge badge-dot bg-info me-1"></span>@endif
                  @if($formulario->label_sector_economico!="")
                  {{$formulario->label_sector_economico}}
                  @else
                  Sector económico
                  @endif
                </label>
                <select id="sector_economico" name="sector_económico" class="select2 form-select" data-allow-clear="true">
                  <option  value="">Ninguno</option>
                  @foreach ($sectoresEconomicos as $sectorEconomico)
                  <option  value="{{$sectorEconomico->id}}" {{ old('sector_económico')==$sectorEconomico->id ? 'selected' : '' }}>{{ucwords ($sectorEconomico->nombre)}}</option>
                  @endforeach
                </select>
                @if($errors->has('sector_económico')) <div class="text-danger form-label">{{ $errors->first('sector_económico') }}</div> @endif
              </div>
              @endif
              <!-- /Sector económico -->
            </div>
          </div>
        </div>
      </div>
    @endif
    <!-- / Información académica y laboral  -->

    <!--  Información Medica -->
    @if($formulario->visible_seccion_4)
      <div class="col-md-12">
        <div class="card mb-4">
          <h5 class="card-header">{{$formulario->label_seccion_4}}</h5>
          <div class="card-body">
            <div class="row">
              <!-- Tipo de sangre -->
              @if($formulario->visible_tipo_sangre)
              <div class="mb-2 {{$formulario->class_tipo_sangre}}">
                <label class="form-label" for="tipo_sangre">
                  @if($formulario->obligatorio_tipo_sangre) <span class="badge badge-dot bg-info me-1"></span>@endif
                  @if($formulario->label_tipo_sangre!="")
                  {{$formulario->label_tipo_sangre}}
                  @else
                  Tipo de sangre
                  @endif
                </label>
                <select id="tipo_sangre" name="tipo_de_sangre" class="select2 form-select" data-allow-clear="true">
                  <option  value="">Ninguno</option>
                  @foreach ($tiposDeSangres as $tipoSangre)
                  <option  value="{{$tipoSangre->id}}" {{ old('tipo_de_sangre')==$tipoSangre->id ? 'selected' : '' }}>{{ucwords ($tipoSangre->nombre)}}</option>
                  @endforeach
                </select>
                @if($errors->has('tipo_de_sangre')) <div class="text-danger form-label">{{ $errors->first('tipo_de_sangre') }}</div> @endif
              </div>
              @endif
              <!-- /Tipo de sangre -->

              <!-- Indicaciones medicas -->
              @if($formulario->visible_indicaciones_medicas)
                <div class="mb-2 {{$formulario->class_indicaciones_medicas}}">
                  <label class="form-label" for="indicaciones_medicas">
                    @if($formulario->obligatorio_indicaciones_medicas)<span class="badge badge-dot bg-info me-1"></span>@endif
                    @if($formulario->label_indicaciones_medicas !="")
                    {{$formulario->label_indicaciones_medicas}}
                    @else
                    Indicaciones médicas
                    @endif
                  </label>
                  <textarea onkeypress="return sinComillas(event)"  id="indicaciones_medicas" name="indicaciones_médicas" class="form-control" rows="2"  maxlength="500" spellcheck="false" data-ms-editor="true" placeholder="Escribe aquí si sufre de alguna enfermedad, molestia, si es alérgico a algún medicamento o cosas semejantes.">{{ old('indicaciones_médicas') }}</textarea>
                  @if($errors->has('indicaciones_médicas')) <div class="text-danger form-label">{{ $errors->first('indicaciones_médicas') }}</div> @endif
                </div>
              @endif
              <!-- /Indicaciones medicas -->
            </div>
          </div>
        </div>
      </div>
    @endif
    <!-- / Información Medica -->

    <!--  Petición -->
    @if($formulario->visible_seccion_5)
      <div class="col-md-12">
        <div class="card mb-4">
          <h5 class="card-header">{{$formulario->label_seccion_5}}</h5>
          <div class="card-body">
            <div class="row">

              <!-- Tienes una petición -->
              <div class="mb-2 col-12 col-md-2">
                <div class=" small fw-medium mb-1">¿Tienes una petición?</div>
                <label class="switch switch-lg">
                  <input id="tienesUnaPeticion" name="tienesUnaPeticion" type="checkbox" @checked(old("tienesUnaPeticion")) class="switch-input tienesUnaPeticion" />
                  <span class="switch-toggle-slider">
                    <span class="switch-on">SI</span>
                    <span class="switch-off">NO</span>
                  </span>
                  <span class="switch-label"></span>
                </label>
              </div>
              <!-- / Tienes una petición -->

              <!-- Tipo de Petición -->
              <div id="divSelectTipoPeticion" class="mb-2 {{ old('tienesUnaPeticion') ? '' : 'd-none' }} col-12 col-md-3">
                <label class="form-label" for="tipo_peticion">
                  <span class="badge badge-dot bg-info me-1"></span>
                  Tipo de Petición
                </label>
                <select id="tipo_peticion" name="tipo_peticion_id" class=" form-select" data-allow-clear="true">
                  <option  value="">Ninguno</option>
                  @foreach ($tipoPeticiones as $tipoPeticion)
                  <option  value="{{$tipoPeticion->id}}" {{ old('tipo_peticion_id')==$tipoPeticion->id ? 'selected' : '' }}>{{ucwords ($tipoPeticion->nombre)}}</option>
                  @endforeach
                </select>
              </div>
              <!-- /Tipo de Petición -->

              <!-- Descripción de la petición -->
              <div id="divDescripcionPeticion" class="mb-2 {{ old('tienesUnaPeticion') ? '' : 'd-none' }} col-12 col-md-7">
                <label class="form-label" for="descripcion_peticion">
                  <span class="badge badge-dot bg-info me-1"></span>
                  Escribe aqui tu petición
                </label>
                <textarea onkeypress="return sinComillas(event)"  id="descripcion_peticion" name="descripcion_peticion" class="form-control" rows="2"  maxlength="500" spellcheck="false" data-ms-editor="true" placeholder="Escribe aquí si sufre de alguna enfermedad, molestia, si es alérgico a algún medicamento o cosas semejantes.">{{ old('descripcion_peticion') }}</textarea>
              </div>
              <!-- /Descripción de la petición -->
            </div>
          </div>
        </div>
      </div>
    @endif
    <!-- / Petición -->

    <!-- Vinculación -->
    @if($formulario->visible_seccion_6)
      <div class="col-md-12">
        <div class="card mb-4">
          <h5 class="card-header">{{$formulario->label_seccion_6}}</h5>
          <div class="card-body">
            <div class="row">

              <!-- Sede -->
              @if($formulario->visible_sede)
                <div class="mb-2 {{$formulario->class_sede}}">
                  <label class="form-label" for="sede">
                    @if($formulario->obligatorio_sede) <span class="badge badge-dot bg-info me-1"></span>@endif
                    @if($formulario->label_sede!="")
                    {{$formulario->label_sede}}
                    @else
                    Sede
                    @endif
                  </label>
                  <select id="sede" name="sede" class="select2 form-select" data-allow-clear="true">
                    <option  value="">Ninguno</option>
                    @foreach ($sedes as $sede)
                    <option  value="{{$sede->id}}" {{ old('sede')==$sede->id ? 'selected' : '' }}>{{ucwords ($sede->nombre)}}</option>
                    @endforeach
                  </select>
                  @if($errors->has('sede')) <div class="text-danger form-label">{{ $errors->first('sede') }}</div> @endif
                </div>
              @endif
              <!-- /Sede -->

              <!-- Tipo de vinculación-->
              @if($formulario->visible_tipo_vinculacion)
                <div class="mb-2 {{$formulario->class_tipo_vinculacion}}">
                  <label class="form-label" for="tipo_vinculacion">
                    @if($formulario->obligatorio_tipo_vinculacion) <span class="badge badge-dot bg-info me-1"></span>@endif
                    @if($formulario->label_tipo_vinculacion!="")
                    {{$formulario->label_tipo_vinculacion}}
                    @else
                    Tipo de vinculación
                    @endif
                  </label>
                  <select id="tipo_vinculacion" name="tipo_vinculación" class="select2 form-select" data-allow-clear="true">
                    <option  value="">Ninguno</option>
                    @foreach ($tiposDeVinculacion as $tipoDeVinculacion)
                    <option  value="{{$tipoDeVinculacion->id}}" {{ old('tipo_vinculación')==$tipoDeVinculacion->id ? 'selected' : '' }}>{{ucwords ($tipoDeVinculacion->nombre)}}</option>
                    @endforeach
                  </select>
                  @if($errors->has('tipo_vinculación')) <div class="text-danger form-label">{{ $errors->first('tipo_vinculación') }}</div> @endif
                </div>
              @endif
              <!-- /Tipo de vinculación -->

              @if($formulario->es_formulario_exterior == FALSE)

                <!-- información opcional -->
                @if($rolActivo->hasPermissionTo('personas.ver_campo_informacion_opcional'))
                  @if($formulario->visible_informacion_opcional)
                    <div class="mb-2 {{$formulario->class_informacion_opcional}}">
                      <label class="form-label" for="informacion_opcional">
                        @if($formulario->obligatorio_informacion_opcional) <span class="badge badge-dot bg-info me-1"></span> @endif
                        @if($formulario->label_informacion_opcional != "")
                        {{$formulario->label_informacion_opcional}}
                        @else
                        {{$configuracion->nombre_informacion_opcional}}
                        @endif
                      </label>
                      <textarea onkeypress="return sinComillas(event)"  id="informacion_opcional" name="información_opcional" class="form-control" rows="5"  maxlength="10000" placeholder="">{{ old('información_opcional') }}</textarea>
                      @if($errors->has('información_opcional')) <div class="text-danger form-label">{{ $errors->first('información_opcional') }}</div> @endif
                    </div>
                  @endif
                @endif
                <!-- información opcional-->

                <!-- campo extra reservado -->
                @if($rolActivo->hasPermissionTo('personas.ver_campo_reservado_visible'))
                  @if($formulario->visible_campo_reservado)
                  <div class="mb-2 {{$formulario->class_campo_reservado}}">
                    <label class="form-label" for="campo_reservado">
                      @if($formulario->obligatorio_campo_reservado) <span class="badge badge-dot bg-info me-1"></span> @endif
                      @if($formulario->label_campo_reservado != "")
                      {{$formulario->label_campo_reservado}}
                      @else
                      {{$configuracion->nombre_campo_reservado}}
                      @endif
                    </label>
                    <textarea onkeypress="return sinComillas(event)"  id="campo_reservado" name="campo_reservado" class="form-control" rows="5"  maxlength="50000" placeholder="">{{ old('campo_reservado') }}</textarea>
                    @if($errors->has('campo_reservado')) <div class="text-danger form-label">{{ $errors->first('campo_reservado') }}</div> @endif
                  </div>
                  @endif
                @endif
                <!-- campo extra reservado-->
              @endif

              @if($formulario->es_formulario_exterior)
                <!-- información opcional -->
                @if($formulario->visible_informacion_opcional)
                  <div class="mb-2 {{$formulario->class_informacion_opcional}}">
                    <label class="form-label" for="informacion_opcional">
                      @if($formulario->obligatorio_informacion_opcional) <span class="badge badge-dot bg-info me-1"></span> @endif
                      @if($formulario->label_informacion_opcional != "")
                      {{$formulario->label_informacion_opcional}}
                      @else
                      {{$configuracion->nombre_informacion_opcional}}
                      @endif
                    </label>
                    <textarea onkeypress="return sinComillas(event)"  id="informacion_opcional" name="información_opcional" class="form-control" rows="5"  maxlength="10000" placeholder="">{{ old('información_opcional') }}</textarea>
                    @if($errors->has('información_opcional')) <div class="text-danger form-label">{{ $errors->first('información_opcional') }}</div> @endif
                  </div>
                @endif
                <!-- información opcional-->

                <!-- campo extra reservado -->
                @if($formulario->visible_campo_reservado)
                <div class="mb-2 {{$formulario->class_campo_reservado}}">
                  <label class="form-label" for="campo_reservado">
                    @if($formulario->obligatorio_campo_reservado) <span class="badge badge-dot bg-info me-1"></span> @endif
                    @if($formulario->label_campo_reservado != "")
                    {{$formulario->label_campo_reservado}}
                    @else
                    {{$configuracion->nombre_campo_reservado}}
                    @endif
                  </label>
                  <textarea onkeypress="return sinComillas(event)"  id="campo_reservado" name="campo_reservado" class="form-control" rows="5"  maxlength="50000" placeholder="">{{ old('campo_reservado') }}</textarea>
                  @if($errors->has('campo_reservado')) <div class="text-danger form-label">{{ $errors->first('campo_reservado') }}</div> @endif
                </div>
                @endif
                <!-- campo extra reservado-->
              @endif

            </div>
          </div>
        </div>
      </div>
    @endif
    <!-- Vinculación -->

    <!-- Archivos -->
    @if($formulario->visible_seccion_7)
      <div class="col-md-12">
        <div class="card mb-4">
          <h5 class="card-header">{{$formulario->label_seccion_7}}</h5>
          <div class="card-body">
            <div class="row">

              <!-- Archivo a -->
              @if($formulario->visible_archivo_a)
                <div class="mb-2 {{$formulario->class_archivo_a}}">
                  <label id="label_archivo_a" class="form-label" for="archivo_a">
                    @if($formulario->obligatorio_archivo_a) <span class="badge badge-dot bg-info me-1"></span>@endif
                    @if($formulario->label_archivo_a!="")
                    {{$formulario->label_archivo_a}}
                    @else
                    Archivo A
                    @endif

                    @if($formulario->descargable_archivo_a)
                    (<a href="{{$configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/archivos/descargable_archivo_a.pdf') : $configuracion->ruta_almacenamiento.'/archivos/descargable_archivo_a.pdf' }} "  target="_blank" >Descargar formato</a>)
                    @endif
                  </label>

                  <input type="file" id="archivo_a" name="archivo_a"  class="form-control" accept=".gif, .jpg, .png, .jpeg, .pdf">
                  @if($errors->has('archivo_a')) <div class="text-danger form-label">{{ $errors->first('archivo_a') }}</div> @endif
                </div>
              @endif
              <!--/ Archivo a -->

              <!-- Archivo b -->
              @if($formulario->visible_archivo_b)
                <div class="mb-2 {{$formulario->class_archivo_b}}">
                  <label id="label_archivo_b" class="form-label" for="archivo_b">
                    @if($formulario->obligatorio_archivo_b) <span class="badge badge-dot bg-info me-1"></span>@endif
                    @if($formulario->label_archivo_b!="")
                    {{$formulario->label_archivo_b}}
                    @else
                    Archivo B
                    @endif

                    @if($formulario->descargable_archivo_b)
                    (<a href="{{$configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/archivos/descargable_archivo_b.pdf') : $configuracion->ruta_almacenamiento.'/archivos/descargable_archivo_b.pdf' }} "  target="_blank" >Descargar formato</a>)
                    @endif
                  </label>

                  <input type="file" id="archivo_b" name="archivo_b"  class="form-control" accept=".gif, .jpg, .png, .jpeg, .pdf">
                  @if($errors->has('archivo_b')) <div class="text-danger form-label">{{ $errors->first('archivo_b') }}</div> @endif
                </div>
              @endif
              <!--/ Archivo b -->

              <!-- Archivo c -->
              @if($formulario->visible_archivo_c)
                <div class="mb-2 {{$formulario->class_archivo_c}}">
                  <label id="label_archivo_c" class="form-label" for="archivo_c">
                    @if($formulario->obligatorio_archivo_c) <span class="badge badge-dot bg-info me-1"></span>@endif
                    @if($formulario->label_archivo_c!="")
                    {{$formulario->label_archivo_c}}
                    @else
                    Archivo C
                    @endif

                    @if($formulario->descargable_archivo_c)
                    (<a href="{{$configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/archivos/descargable_archivo_c.pdf') : $configuracion->ruta_almacenamiento.'/archivos/descargable_archivo_c.pdf' }} "  target="_blank" >Descargar formato</a>)
                    @endif
                  </label>

                  <input type="file" id="archivo_c" name="archivo_c"  class="form-control" accept=".gif, .jpg, .png, .jpeg, .pdf">
                  @if($errors->has('archivo_c')) <div class="text-danger form-label">{{ $errors->first('archivo_c') }}</div> @endif
                </div>
              @endif
              <!--/ Archivo c -->

              <!-- Archivo D -->
              @if($formulario->visible_archivo_d)
                <div class="mb-2 {{$formulario->class_archivo_d}}">
                  <label id="label_archivo_d" class="form-label" for="archivo_d">
                    @if($formulario->obligatorio_archivo_d) <span class="badge badge-dot bg-info me-1"></span>@endif
                    @if($formulario->label_archivo_d!="")
                    {{$formulario->label_archivo_d}}
                    @else
                    Archivo D
                    @endif

                    @if($formulario->descargable_archivo_d)
                    (<a href="{{$configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/archivos/descargable_archivo_d.pdf') : $configuracion->ruta_almacenamiento.'/archivos/descargable_archivo_d.pdf' }} "  target="_blank" >Descargar formato</a>)
                    @endif
                  </label>

                  <input type="file" id="archivo_d" name="archivo_d"    class="form-control" accept=".gif, .jpg, .png, .jpeg, .pdf">
                  @if($errors->has('archivo_d')) <div class="text-danger form-label">{{ $errors->first('archivo_d') }}</div> @endif
                </div>
              @endif
              <!--/ Archivo D -->
            </div>
          </div>
        </div>
      </div>
    @endif
    <!--/ Archivos -->

    <!-- Datos de acudiente -->
    @if($formulario->visible_seccion_8)
      <div class="col-md-12">
        <div class="card mb-4">
          <h5 class="card-header">{{$formulario->label_seccion_8}}</h5>
          <div class="card-body">
            <div class="row">
              <!--  Tipo de id acudiente -->
              @if($formulario->visible_tipo_identificacion_acudiente)
                <div class="mb-2 {{$formulario->class_tipo_identificacion_acudiente}}">
                  <label class="form-label" for="tipo_identificacion_acudiente">
                    @if($formulario->obligatorio_tipo_identificacion_acudiente)<span class="badge badge-dot bg-info me-1"></span>@endif
                    @if($formulario->label_tipo_identificacion_acudiente!="")
                    {{$formulario->label_tipo_identificacion_acudiente}}
                    @else
                    Tipo de identidad del acudiente
                    @endif
                  </label>
                  <select id="tipo_identificacion_acudiente" name="tipo_de_identificación_del_acudiente" class="select2 form-select" data-allow-clear="true">
                    <option value="" selected>Ninguno</option>
                    @foreach ($tiposIdentificaciones as $tipoIdentificacion)
                    <option  value="{{$tipoIdentificacion->id}}" {{ old('tipo_de_identificación_del_acudiente')==$tipoIdentificacion->id ? 'selected' : '' }} >{{$tipoIdentificacion->nombre}}</option>
                    @endforeach
                  </select>
                  @if($errors->has('tipo_de_identificación_del_acudiente')) <div class="text-danger form-label">{{ $errors->first('tipo_de_identificación_del_acudiente') }}</div> @endif
                </div>
              @endif
              <!--  Tipo de id acudiente -->

              <!-- identificacion acudiente -->
              @if($formulario->visible_identificacion_acudiente)
                <div class="mb-2 {{$formulario->class_identificacion_acudiente}}">
                  <label class="form-label" for="identificacion_acudiente">
                    @if($formulario->obligatorio_identificacion_acudiente)<span class="badge badge-dot bg-info me-1"></span>@endif
                    @if($formulario->label_identificacion_acudiente!="")
                    {{$formulario->label_identificacion_acudiente}}
                    @else
                    Identificación del acudiente
                    @endif
                  </label>
                  <input id="identificacion_acudiente" name="identificación_del_acudiente" value="{{ old('identificación_del_acudiente') }}"  type="text" class="form-control"/>
                  @if($errors->has('identificación_del_acudiente')) <div class="text-danger form-label">{{ $errors->first('identificación_del_acudiente') }}</div> @endif
                </div>
              @endif
              <!-- /identificacion acudiente -->

              <!-- Nombre acudiente -->
              @if($formulario->visible_nombre_acudiente)
                <div class="mb-2 {{$formulario->class_nombre_acudiente}}">
                  <label class="form-label" for="nombre_acudiente">
                    @if($formulario->obligatorio_nombre_acudiente)<span class="badge badge-dot bg-info me-1"></span>@endif
                    @if($formulario->label_nombre_acudiente!="")
                    {{$formulario->label_nombre_acudiente}}
                    @else
                    Nombre completo del acudiente
                    @endif
                  </label>
                  <input id="nombre_acudiente" name="nombre_del_acudiente" value="{{ old('nombre_del_acudiente') }}" type="text" class="form-control"/>
                  @if($errors->has('nombre_del_acudiente')) <div class="text-danger form-label">{{ $errors->first('nombre_del_acudiente') }}</div> @endif
                </div>
              @endif
              <!-- /Nombre acudiente  -->

              <!-- Telefono acudiente -->
              @if($formulario->visible_telefono_acudiente == TRUE)
                <div class="mb-2 {{$formulario->class_telefono_acudiente}}">
                  <label class="form-label" for="telefono_acudiente">
                    @if($formulario->obligatorio_telefono_acudiente) <span class="badge badge-dot bg-info me-1"></span>@endif
                    @if($formulario->label_telefono_acudiente!="")
                    {{$formulario->label_telefono_acudiente}}
                    @else
                    Teléfono/Móvil
                    @endif
                  </label>
                  <div class="input-group input-group-merge">
                    <span id="basic-icon-default-phone2" class="input-group-text"><i class="ti ti-phone"></i></span>
                    <input id="telefono_acudiente" name="teléfono_del_acudiente" value="{{ old('teléfono_del_acudiente') }}" type="text" class="form-control" spellcheck="false" data-ms-editor="true">
                  </div>
                  @if($errors->has('teléfono_del_acudiente')) <div class="text-danger form-label">{{ $errors->first('teléfono_del_acudiente') }}</div> @endif
                </div>
              @endif
              <!-- /Telefono acudiente -->

            </div>
          </div>
        </div>
      </div>
    @endif
    <!--/ Datos de acudiente -->

    <!-- Campos extras-->
    @if($formulario->visible_seccion_campos_extra)
      <div class="col-md-12">
        <div class="card mb-4">
          <h5 class="card-header">{{$configuracion->label_seccion_campos_extra}}</h5>
          <div class="card-body">
            <div class="row">
              @foreach($camposExtrasFormulario as $campo)
                @if($campo->pivot->visible != FALSE)
                  <div class="mb-2 {{$campo->class_col}}">
                    <label class="form-label" for="{{$campo->class_id}}">
                      @if($campo->pivot->required) <span class="badge badge-dot bg-info me-1"></span> @endif {{$campo->nombre}}
                    </label>

                    <!-- campo tipo 1 -->
                    @if($campo->tipo_de_campo == 1 && $campo->pivot->visible)
                      <input id="{{$campo->class_id}}" name="{{$campo->class_id}}" value="{{ old($campo->class_id) }}" class="form-control">
                    @endif
                    <!-- /campo tipo 1 -->

                    <!-- campo tipo 2 -->
                    @if($campo->tipo_de_campo == 2 && $campo->pivot->visible)
                      <textarea id="{{$campo->class_id}}" name="{{$campo->class_id}}" class="form-control">{{ old($campo->class_id) }}</textarea>
                    @endif
                    <!-- /campo tipo 2 -->

                    <!-- campo tipo 3 -->
                    @if($campo->tipo_de_campo == 3 && $campo->pivot->visible)
                      <select id="{{$campo->class_id}}" name="{{$campo->class_id}}" class="form-control">
                        <option value="">Ninguno</option>
                        @foreach (json_decode($campo->opciones_select) as $opcion)
                          <option value="{{$opcion->value}}" {{ old($campo->class_id)==$opcion->value ? 'selected' : '' }} > {{ ucwords($opcion->nombre) }} </option>
                        @endforeach
                      </select>
                    @endif
                    <!-- /campo tipo 3 -->

                    <!-- campo tipo 4 -->
                    @if($campo->tipo_de_campo == 4 && $campo->pivot->visible)
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
      </div>
    @endif
    <!-- /Campos extras-->

    <!-- Terminos y condiciones-->
    @if($formulario->visible_terminos_condiciones)
      <div class="col-12">
        <div class="form-check mt-3">
          <input class="form-check-input" type="checkbox" name="habeas" @checked(old("habeas")) id="habeas" required>
          <label class="form-check-label" for="habeas">
            <b>Términos y condiciones</b>
          </label>
          <br>
          {{$formulario->mensaje_terminos_condiciones}}
          @if($formulario->url_terminos_condiciones!="")
          <a href="{{$formulario->url_terminos_condiciones}}" target="_blank">Más información...</a>
          @endif
        </div>
      </div>
    @endif
    <!--/ Terminos y condiciones-->

    <!-- botonera -->
    <div class="d-flex mb-1 mt-5">
      <div class="me-auto">
        <button type="submit" class="btn btn-primary me-1 btnGuardar">Guardar</button>
        <a type="reset" href="{{ url()->previous() }}" class="btn btn-label-secondary">Cancelar</a>
      </div>
      <div class="p-2 bd-highlight">
        <p class="text-muted"><span class="badge badge-dot bg-info me-1"></span> Campos obligatorios</p>
      </div>
    </div>
    <!-- /botonera -->
  </form>

  <!-- modal foto-->
  <div class="modal fade modal-img" id="modalFoto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-simple modal-edit-user">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-body">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3 class="mb-2"><i class="ti ti-camera  ti-lg"></i> Subir foto</h3>
            <p class="text-muted">Selecciona y recorta la foto</p>
          </div>

          <div class="row">
            <div class="col-12">
              <div class="mb-2">
                <label class="mb-2"><span class="fw-bold">Paso #1</span> Selecciona la foto</label><br>
                <input class="form-control" type="file" id="cropperImageUpload">
              </div>
              <div class="mb-2">
                <label class="mb-2"><span class="fw-bold">Paso #2</span> Recorta la foto</label><br>
                <center>
                <img src="{{ Storage::url('generales/img/otros/placeholder.jpg') }}" class="w-100" id="croppingImage" alt="cropper">
                </center>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer text-center">
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary crop me-sm-3 me-1" data-bs-dismiss="modal">Guardar</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ modal foto -->
</div>

<script>
   $('#identificacion').keyup(function () {
    alert('entre');
    /*
    clearTimeout($.data(this, 'timer'));
    if($("#identificacion").val()!='')
    {
      @if($configuracion->correo_por_defecto==TRUE && $formulario->visible_email==TRUE)
      if ($("#email").val() == '')
      {
        $("#email").val($("#identificacion").val()+"@cambiaestecorreo.com");
      }else if($("#email").val().indexOf('cambiaestecorreo.com') != -1)
      {
        $("#email").val($("#identificacion").val()+"@cambiaestecorreo.com");
      }
      @endif
    }
    */

  });

</script>

@endsection
