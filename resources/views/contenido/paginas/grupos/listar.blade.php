@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Grupos')

<!-- Page -->
@section('page-style')
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

  $(".clearAllItems").click(function() {
    value = $(this).data('select');
    $('#' + value).val(null).trigger('change');
  });

  $(".selectAllItems").click(function() {
    value = $(this).data('select');
    $("#" + value + " > option").prop("selected", true);
    $("#" + value).trigger("change");
  });
</script>


<script>
  function darBajaAlta(grupoId, tipo)
  {
    Livewire.dispatch('abrirModalBajaAlta', { grupoId: grupoId, tipo: tipo });
  }

  function eliminacion(grupoId)
  {
    Livewire.dispatch('confirmarEliminacion', { grupoId: grupoId });
  }
</script>


@endsection

@section('content')
<h4 class="mb-1">Grupos</h4>
<p class="mb-4">Aquí encontraras el listado de grupos.</p>

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
              <a href="{{ route('grupo.lista', $indicador->url) }}">
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
            @foreach( $indicadoresPortipoGrupo as $indicador )
            <div class="col-lg-4 col-sm-6 mb-2">
              <a href="{{ route('grupo.lista', $indicador->url) }}">
                <div class="card h-100">
                  <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="card-title mb-0">
                      <h5 class="mb-0 me-2">{{ $indicador->cantidad }}</h5>
                      <small class="text-black">{{ $indicador->nombre }}</small>
                    </div>
                    <div class="card-icon">
                      <span class="badge {{ $indicador->color}} rounded-pill p-2">
                        <i class='{{ $indicador->icono}} ti-sm'></i>
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
    <form class="forms-sample" method="GET" action="{{ route('grupo.lista', $tipo) }}">
      <div class="col-12 offset-md-2 col-md-8 d-flex">
        <div class="input-group">
          <input id="buscar" name="buscar" type="text" value="{{$parametrosBusqueda->buscar}}" class="form-control" placeholder="Busqueda..." aria-label="Recipient's username" aria-describedby="button-addon2">
          <button class="btn btn-outline-primary px-2 px-md-3" type="submit" id="button-addon2"><i class="ti ti-search"></i></button>
          @if($parametrosBusqueda->bandera == 1)
          <a href="{{ route('grupo.lista', $tipo) }}" class="btn btn-outline-danger" type="submit"><i class="ti ti-x"></i></a>
          @endif
          <button type="button" class="btn btn-primary btn-sm px-2 px-md-3" data-bs-toggle="modal" data-bs-target="#modalBusquedaAvanzada"><i class="ti ti-input-search"></i> <span class="d-none d-md-block">Búsqueda avanzada</span></button>
          <button type="button" class="btn btn-success btn-sm px-2 px-md-3" data-bs-toggle="modal" data-bs-target="#modalGeneradorExcel"><i class="ti ti-file-download"></i> <span class="d-none d-md-block">.xls</span></button>
        </div>
        <!-- Button trigger modal -->
      </div>
    </form>
    @if($grupos)
    <span class="text-center py-3">{{ $grupos->total() > 1 ? $grupos->total().' Grupos' : $grupos->total().' Grupo' }} {!! $parametrosBusqueda->textoBusqueda ? '('.$parametrosBusqueda->textoBusqueda.')' : '' !!}</span>
    @endif
  </div>

  <!-- lista de grupos -->
  <div class="row g-4 mt-1">
    @foreach($grupos as $grupo)
    <div class="col-12 col-xl-4 col-lg-6 col-md-6">
      <div class="card border rounded p-2">
        @if( $grupo->latitud )
        <iframe width="100%" height="200" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q={{$grupo->latitud}},{{$grupo->longitud}}&hl=es&z=14&amp;output=embed">
        </iframe>
        @else
          <div class="p-3 border rounded " style="height: 200px;">
            <center>
              <i class="ti ti-brand-google-maps ti-xl pb-1"></i>
              <h6 class="text-center">¡Ups! no se puede mostrar el mapa debido a que no se ha asignado la georrefencia.</h6>
              @if($rolActivo->hasPermissionTo('grupos.pestana_georreferencia_grupo'))
              <a href="{{ route('grupo.georreferencia',$grupo) }}" class="btn btn-sm btn-primary">
                <i class="ti ti-map-pin-plus me-2 ti-sm"></i> Agregar georreferencia
              </a>
              @endif
            </center>
          </div>
        @endif
        <div class="card-header">
          <div class="d-flex align-items-start">
            <div class="d-flex align-items-start">
              <div class="px-1">
                <button class="btn rounded-pill btn-icon btn-primary waves-effect waves-light btn-xl"><i class="ti ti-users-group ti-xl mx-2"></i></button>
              </div>
              <div class="me-2 ms-1 mt-1">
                <h5 class="mb-0"><a href="javascript:;" class="text-body">{{ $grupo->tipoGrupo ? $grupo->tipoGrupo->nombre : 'No definido'}}</a></h5>
                <div class="client-info"><span class="fw-medium">{{ $grupo->nombre }}</span></div>
              </div>
            </div>
            <div class="ms-auto">
              <div class="dropdown zindex-2 border rounded p-1">
                <button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical text-muted"></i></button>
                <ul class="dropdown-menu dropdown-menu-end">
                  @if($grupo->dado_baja == 0)
                    @if($rolActivo->hasPermissionTo('grupos.opcion_ver_perfil_grupo'))
                      <li><a class="dropdown-item" href="{{ route('grupo.perfil', $grupo)}}">Perfil</a></li>
                    @endif

                    @if($rolActivo->hasPermissionTo('grupos.opcion_modificar_grupo'))
                      <li><a class="dropdown-item" href="{{ route('grupo.modificar', $grupo)}}">Modificar</a></li>
                    @endif

                    @if($rolActivo->hasPermissionTo('grupos.opcion_excluir_grupo'))
                      <form id="excluirGrupo" method="POST" action="{{ route('grupo.excluir', ['grupo' => $grupo]) }}">
                        @csrf
                        <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('excluirGrupo').submit();" >Excluir grupo</a></li>
                      </form>
                    @endif

                    @if($rolActivo->hasPermissionTo('grupos.opcion_dar_de_baja_alta_grupo'))
                      <li><a class="dropdown-item" href="javascript:void(0);" onclick="darBajaAlta('{{$grupo->id}}', 'baja')">Dar de baja</a></li>
                    @endif

                    @if($rolActivo->hasPermissionTo('grupos.opcion_eliminar_grupo'))
                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="eliminacion('{{$grupo->id}}')">Eliminar</a></li>
                    @endif
                  @else
                    @if($rolActivo->hasPermissionTo('grupos.opcion_dar_de_baja_alta_grupo'))
                      <li><a class="dropdown-item" href="javascript:void(0);" onclick="darBajaAlta('{{$grupo->id}}', 'alta')">Dar de alta</a></li>
                    @endif
                  @endif
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body">
            <div class="d-flex align-items-center">
              <ul class="list-unstyled d-flex align-items-center avatar-group mb-0 zindex-2">
                @if($grupo->asistentes()->select('users.id')->take(1)->count() > 0)
                  @foreach($grupo->asistentes()->select('users.id','primer_nombre','segundo_nombre','primer_apellido','foto')->take(3)->get()  as $persona)
                  <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="{{$persona->nombre(3)}}" class="avatar pull-up">
                    <img class="rounded-circle" src="{{ $configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/img/foto-usuario/'.$persona->foto) : $configuracion->ruta_almacenamiento.'/img/foto-usuario/'.$persona->foto }}" alt="foto {{$persona->primer_nombre}}">
                  </li>
                  @endforeach
                  <li></i><small class="text-muted mx-1">{{ $grupo->asistentes()->select('users.id')->count() > 1 ? $grupo->asistentes()->select('users.id')->count().' Integrantes' : '1 Integrante'}}</small></li>
                @else
                <li class="text-muted"><i class="ti ti-user-circle ti-xl text-heading"></i><small class="text-muted mx-1">Sin integrantes</small></li>
                @endif
              </ul>
            </div>

            <ul class="list-unstyled mb-4 mt-3">
              <li class="d-flex align-items-center mb-1"><i class="ti ti-brand-days-counter text-heading"></i><span class="fw-medium mx-2 text-heading">Día de reunión:</span> <span>{{ Helper::obtenerDiaDeLaSemana($grupo->dia) ? Helper::obtenerDiaDeLaSemana($grupo->dia) : 'Día no indicado' }}, {{ Carbon\Carbon::parse($grupo->hora)->format(('g:i a')) }}</span></li>
              <li class="d-flex align-items-center mb-1"><i class="ti ti-confetti text-heading"></i><span class="fw-medium mx-2 text-heading">{{ $configuracion->label_fecha_creacion_grupo ? $configuracion->label_fecha_creacion_grupo : 'Fecha de apertura'}}:</span> <span>{{ $grupo->fecha_apertura ? $grupo->fecha_apertura : 'No indicado' }}</span></li>
            </ul>
            <div class="d-flex align-items-center justify-content-center my-2 gap-2">
                @if( isset($grupo->ultimoReporteDelGrupo()->id) )
                  <span class="badge bg-label-primary"><i class="ti ti-calendar"> </i>  <b>Último reporte:</b> {{ $grupo->ultimoReporteDelGrupo()->fecha }}</span>
                  @if($grupo->alDia())
                  <span class="badge bg-label-success"><i class="ti ti-checks"> </i> Al día </span>
                  @endif
                @else
                <span class="badge bg-label-danger"><i class="ti ti-calendar-question"> </i> Nunca reportado</span>
                @endif
            </div>


            <div class="my-1 py-1 ">
              <span class="fw-bold">Encargados</span><br>
              <div style="height: 60px; overflow-y: scroll;">
                @foreach($grupo->encargadosDirectos() as $encargado)
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
  <!--/ lista de grupos -->

  <div class="row my-3">
    @if($grupos)
    <p> {{$grupos->lastItem()}} <b>de</b> {{$grupos->total()}} <b>grupos - Página</b> {{ $grupos->currentPage() }} </p>
    {!! $grupos->appends(request()->input())->links() !!}
    @endif
    </div>

  @livewire('Grupos.modal-baja-alta-grupo')

  <!-- Modal busqueda avanzada -->
  <div class="modal fade modalSelect2" id="modalBusquedaAvanzada" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <form class="forms-sample" method="GET" action="{{ route('grupo.lista', $tipo) }}">
        <div class="modal-content">
          <div class="modal-header d-flex flex-column">
            <h4 class="modal-title">Búsqueda avanzada</h4>
            <p class="modal-subtitle text-center">Trae un listado de grupos más específico a través de este formulario. </p>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">

              <div class="col-12 mb-3">
                <label for="nameBasic" class="form-label">Por palabra</label>
                <input id="buscar" name="buscar" type="text" value="{{$parametrosBusqueda->buscar}}" class="form-control" placeholder="Buscar por nombre, email, identificación">
              </div>

              <!-- Por tipo de grupo -->
              <div class="col-12 col-md-6 mb-3">
                <label for="filtroPorTipoDeGrupo" class="form-label">Fitrar por tipo de grupo </label>
                <select id="filtroPorTipoDeGrupo" name="filtroPorTipoDeGrupo[]" class="select2BusquedaAvanzada form-select" multiple>
                  @foreach($tiposDeGrupo as $tipoGrupo)
                  <option value="{{ $tipoGrupo->id }}" {{ $parametrosBusqueda->filtroPorTipoDeGrupo && in_array($tipoGrupo->id,$parametrosBusqueda->filtroPorTipoDeGrupo) ? 'selected' : '' }}>{{ $tipoGrupo->nombre }}</option>
                  @endforeach
                </select>
              </div>
              <!-- Por tipo de grupo -->

              @livewire('Grupos.grupos-para-busqueda',[
              'id' => 'filtroGrupo',
              'class' => 'col-12 col-md-6 mb-3',
              'label' => 'Filtrar a partir del grupo',
              'conDadosDeBaja' => 'no',
              'grupoSeleccionadoId' => $parametrosBusqueda->filtroGrupo
              ])

              <!-- Por sede -->
              <div class="col-12 col-md-6 mb-3">
                <label for="filtroPorSedes" class="form-label">Fitrar por sedes </label>
                <select id="filtroPorSedes" name="filtroPorSedes[]" class="select2BusquedaAvanzada form-select" multiple>
                  @foreach($sedes  as $sede)
                  <option value="{{ $sede->id }}" {{ $parametrosBusqueda->filtroPorSedes && in_array($sede->id,$parametrosBusqueda->filtroPorSedes) ? 'selected' : '' }}>{{ $sede->nombre }}</option>
                  @endforeach
                </select>
              </div>
              <!-- Por sede -->

              <!-- Por tipos de vivienda -->
              <div class="col-12 col-md-6 mb-3">
                <label for="filtroPorTiposDeViviendas" class="form-label">Fitrar por tipos de vivienda </label>
                <select id="filtroPorTiposDeViviendas" name="filtroPorTiposDeViviendas[]" class="select2BusquedaAvanzada form-select" multiple>
                  @foreach($tiposDeViviendas  as $tipoDeVivienda)
                  <option value="{{ $tipoDeVivienda->id }}" {{ $parametrosBusqueda->filtroPorTiposDeViviendas && in_array($tipoDeVivienda->id,$parametrosBusqueda->filtroPorTiposDeViviendas) ? 'selected' : '' }}>{{ $tipoDeVivienda->nombre }}</option>
                  @endforeach
                </select>
              </div>
              <!-- Por tipos de vivienda -->

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
      <form class="forms-sample" method="POST" action="{{ route('grupo.listadoFinalCsv') }}">
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

              <!-- Informacion principal -->
              <div class="col-12 mb-3">
                <label for="informacionPrincipal" class="form-label">Información principal
                  (<a href="javascript:;" data-select="informacionPrincipal" class="selectAllItems"><span class="fw-medium">Seleccionar todos</span></a> | <a href="javascript:;" data-select="informacionPrincipal" class="clearAllItems"><span class="fw-medium">Quitar todos</span></a>)
                </label>
                <select id="informacionPrincipal" name="informacionPrincipal[]" class="select2GeneradorExcel form-select" multiple>
                  @foreach($camposInformeExcel->where('selector_id',5) as $campo)
                    @if($campo->nombre_campo_informe == "1")
                    <option value="{{$campo->id}}">{{$configuracion->label_campo_opcional1}}</option>
                    @elseif($campo->nombre_campo_bd == "dia_planeacion")
                    <option value="{{$campo->id}}">{{$configuracion->label_campo_dia_planeacion_grupo}}</option>
                    @elseif($campo->nombre_campo_bd == "hora_planeacion")
                    <option value="{{$campo->id}}">{{$configuracion->label_campo_hora_planeacion_grupo}}</option>
                    @elseif($campo->nombre_campo_bd == "dia")
                    <option value="{{$campo->id}}">{{$configuracion->label_campo_dia_reunion_grupo}}</option>
                    @elseif($campo->nombre_campo_bd == "hora")
                    <option value="{{$campo->id}}">{{$configuracion->label_campo_hora_reunion_grupo}}</option>
                    @else
                    <option value="{{ $campo->id }}">{{ $campo->nombre_campo_informe }}</option>
                    @endif
                  @endforeach
                </select>
              </div>

              @if($configuracion->visible_seccion_campos_extra_grupo)
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

@endsection
