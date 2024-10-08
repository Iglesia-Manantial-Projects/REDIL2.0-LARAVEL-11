@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Peticiones')

<!-- Page -->
@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
@endsection

@section('page-script')

<script>
   $("#filtroFechas").flatpickr({
    mode: "range",
    dateFormat: "Y-m-d",
    defaultDate: ["{{ $filtroFechaIni }}", "{{ $filtroFechaFin }}"],
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
        $('#filtroFechaIni').val(dateArr[0]);
        $('#filtroFechaFin').val(dateArr[1]);
        // interact with selected dates here
      }
    },
    onReady: function(dateObj, dateStr, instance) {
      var $cal = $(instance.calendarContainer);
      if ($cal.find('.flatpickr-clear').length < 1) {
        $cal.append('<button type="button" class="btn btn-sm btn-outline-primary flatpickr-clear mb-2">Borrar</button>');
        $cal.find('.flatpickr-clear').on('click', function() {
          instance.clear();
          $('#filtroFechaIni').val('');
          $('#filtroFechaFin').val('');
          instance.close();
        });
      }
    }
  });



  $(document).ready(function() {
    $('#filtroPaises').select2({
        placeholder: 'Filtrar por paises',
      }
    );
  });

  $(document).ready(function() {
    $('#filtroTipoPeticiones').select2({
        placeholder: 'Filtrar por tipo de petición',
      }
    );
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
  function modalRespuesta(peticionId, personaId)
  {
    Livewire.dispatch('modalRespuesta', { peticionId: peticionId, personaId: personaId });
  }

  function modalSeguimiento(peticionId, personaId)
  {
    Livewire.dispatch('modalSeguimiento', { peticionId: peticionId, personaId: personaId });
  }
</script>

<script>
  function confirmarEliminacionMasiva(cantidad, tipo)
  {
    if(cantidad>0)
    {
      let titulo = '¿Estás seguro que deseas elimina esta <b>'+cantidad+'</b> petición?';
      if(cantidad > 1)
      titulo = '¿Estás seguro que deseas eliminar las <b>'+cantidad+'</b> peticiones?';

      Swal.fire({
        title: titulo,
        html: 'Esta acción no es reversible.',
        icon: 'warning',
        showCancelButton: false,
        confirmButtonText: 'Si, eliminar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          $('#eliminacionMasiva').attr('action',"/peticion/"+tipo+"/eliminaciones");
          $('#eliminacionMasiva').submit();
        }
      });
    }else{
      Swal.fire({
        title: 'No hay peticiones por eliminar',
        html: 'Intenta nuevamente.',
        icon: 'info',
        showCloseButton: false,
        showCancelButton: false,
      });
    }

  }

  function confirmarEliminacion($peticionId)
  {
    Swal.fire({
      title: '¿Estás seguro que deseas elimina esta petición?',
      html: 'Esta acción no es reversible.',
      icon: 'warning',
      showCancelButton: false,
      confirmButtonText: 'Si, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        $('#eliminacion').attr('action',"/peticion/"+$peticionId+"/eliminacion");
        $('#eliminacion').submit();
      }
    })
  }


</script>
@endsection


@section('content')

<h4 class="mb-1">Gestionar peticiones</h4>
<p class="mb-4">Aquí encontraras el listado de peticiones para hacerles seguimiento y gestionarlas.</p>

@include('layouts.status-msn')

  <div class="row mb-2">
    <div class="row">
      <!-- Cards with few info -->
      @foreach( $indicadores as $indicador )
      <div class="col-lg-4 col-sm-6 mb-2">
        <a href="{{ route('peticion.gestionar', $indicador->url) }}">
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

  <div class="row mt-5">
    <form class="forms-sample" method="GET" action="{{ route('peticion.gestionar', $tipo) }}">
    <div class="row m-0 p-0">

      <!-- Por rango de fechas  -->
      <div class="col-12 col-md-4 mb-2">
        <div class="input-group input-group-merge">
          <span class="input-group-text"><i class="ti ti-calendar"></i></span>
          <input type="text" id="filtroFechaIni" name="filtroFechaIni" value="{{ $filtroFechaIni }}" class="form-control d-none" placeholder="">
          <input type="text" id="filtroFechaFin" name="filtroFechaFin" value="{{ $filtroFechaFin }}" class="form-control d-none" placeholder="">
          <input id="filtroFechas" name="filtroFechas" type="text" class="form-control" placeholder="YYYY-MM-DD a YYYY-MM-DD" />
        </div>
      </div>

      <!-- Por tipo peticion -->
      <div class="col-12 col-md-4 mb-2">
        <select id="filtroTipoPeticiones" name="filtroTipoPeticiones[]" class="select2 form-select" multiple>
          @foreach($tiposPeticiones as $tipoPeticion)
          <option value="{{ $tipoPeticion->id }}" {{ $filtroTipoPeticiones && in_array($tipoPeticion->id,$filtroTipoPeticiones) ? 'selected' : '' }}>{{ $tipoPeticion->nombre }}</option>
          @endforeach
        </select>
      </div>

       <!-- Por paises -->
       <div class="col-12 col-md-4 mb-2">
        <select id="filtroPaises" name="filtroPaises[]" class="select2 form-select" multiple>
          @foreach($paises as $pais)
          <option value="{{ $pais->id }}" {{ $filtroPaises && in_array($pais->id,$filtroPaises) ? 'selected' : '' }}>{{ $pais->nombre }}</option>
          @endforeach
        </select>
      </div>

      <!-- Por persona -->
      <div class="col-12 col-md-4">
        @livewire('Usuarios.usuarios-para-busqueda', [
          'id' => 'persona_id',
          'class' => 'col-12 col-md-12 mb-2',
          'label' => '',
          'estiloSeleccion' => 'pequeno',
          'placeholder' => 'Filtrar por persona',
          'tipoBuscador' => 'unico',
          'queUsuariosCargar' => $queUsuariosCargar,
          'conDadosDeBaja' => 'no',
          'modulo' => 'peticiones',
          'obligatorio' => true,
          'usuarioSeleccionadoId' => $persona ? $persona->id : ''
        ])
      </div>


      <div class="col-12 col-md-8 mb-2">
        <div class="input-group">
          <input id="buscar" name="buscar" type="text" value="{{ $buscar }}" class="form-control" placeholder="Busqueda..." aria-label="" aria-describedby="button-addon2">
          <button class="btn btn-outline-primary px-2 px-md-3" type="submit" id="button-addon2"><i class="ti ti-search"></i></button>
          @if($bandera == 1)
            <a href="{{ route('peticion.gestionar', $tipo) }}" class="btn btn-outline-danger btn-sm " type="submit"><i class="ti ti-x"></i></a>
          @endif
          <button type="button" class="btn btn-success btn-sm px-2 px-md-3" data-bs-toggle="modal" data-bs-target="#modalGeneradorExcel"><i class="ti ti-file-download"></i> <span class="d-none d-md-block">.xls</span></button>

          <button type="button" class="btn dropdown-toggle hide-arrow  btn-outline-secondary waves-effect px-2 px-md-3 " data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical text-muted"></i></button>
          <ul class="dropdown-menu dropdown-menu-end">
            @if($rolActivo->hasPermissionTo('peticiones.opcion_eliminacion_masiva'))
              <li><a class="dropdown-item" href="javascript:void(0);" onclick="confirmarEliminacionMasiva('{{$peticiones->total()}}', '{{$tipo}}')" >Eliminar</a></li>
            @endif
          </ul>
        </div>
      </div>

    </div>
    </form>
    @if($peticiones)
      <span class="text-center py-3">{{ $peticiones->total() > 1 ? $peticiones->total().' Peticiones' : $peticiones->total().' Petición' }} {!! $textoBusqueda ? '('.$textoBusqueda.')' : '' !!}</span>
    @endif
  </div>

  <!-- lista de peticiones -->
  <div class="row g-4 mt-1">
    @foreach($peticiones as $peticion)
    <div class="col-12 col-xl-4 col-lg-6 col-md-6">
      <div class="card border rounded p-2">

        <div class="card-header">
          <div class="d-flex align-items-start">
            <div class="d-flex align-items-start">
              <div class="px-1">
                <button class="btn rounded-pill btn-icon btn-{{ $peticion->estado == 3 ? 'warning' : ($peticion->estado == 2 ? 'success' : 'primary' )  }} waves-effect waves-light btn-xl"><i class="ti ti-notes ti-xl mx-2"></i></button>
              </div>
              <div class="me-2 ms-1 mt-1">
                <h5 class="mb-0"><a href="javascript:;" class="text-body"><b>Tipo:</b> {{ $peticion->tipoPeticion ? $peticion->tipoPeticion->nombre : 'No definido'}}</a></h5>
                <div class="client-info"><span class="fw-medium"><i class="ti ti-calendar"></i> {{ $peticion->fecha }}</span></div>
              </div>
            </div>
            <div class="ms-auto">
              <div class="dropdown zindex-2 border rounded p-1">
                <button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical text-muted"></i></button>
                <ul class="dropdown-menu dropdown-menu-end">

                  @if($rolActivo->hasPermissionTo('peticiones.opcion_eliminar'))
                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="confirmarEliminacion('{{$peticion->id}}')" >Eliminar</a></li>
                  @endif
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body">

        <div class="d-flex align-items-center">
          <ul class="list-unstyled d-flex align-items-center avatar-group mb-0 zindex-2">
              <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="{{$peticion->nombreUsuario}}" class="avatar pull-up">
                <img class="rounded-circle" src="{{ $configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/img/foto-usuario/'.$peticion->fotoUsuario) : $configuracion->ruta_almacenamiento.'/img/foto-usuario/'.$peticion->fotoUsuario }}" alt="foto {{$peticion->nombreUsuario}}">
              </li>
              <span class="text-muted mx-1">{{ $peticion->nombreUsuario }}</span>
          </ul>
        </div>
        <div class="list-unstyled mb-4 mt-3">
          <ul class="list-unstyled mb-4 mt-3">
            <li class="d-flex align-items-center mb-1"><i class="ti ti-phone-call text-heading"></i> <span class="mx-2">{{ $peticion->telefonosUsuario }}</span> </li>
            <li class="d-flex align-items-center mb-1"><i class="ti ti-mail text-heading"></i> <span class="mx-2">{{ $peticion->emailUsuario }}</span></li>
            @if($peticion->pais)
            <li class="d-flex align-items-center mb-1"><i class="fis fi fi-{{ strtolower($peticion->pais->codigo_alpha) }} rounded-circle fs-5"></i>
            <span class="mx-2">{{ $peticion->pais->nombre ? $peticion->pais->nombre : 'No indicado' }}</span></li>
            @endif
          </ul>
        </div>

        <div class="accordion mt-3" id="accordionDePeticiones{{$peticion->id}}">

          <div class="card accordion-item">
            <h2 class="accordion-header" id="headingPeticion{{$peticion->id}}">
              <button type="button" class="accordion-button collapsed fw-bold" data-bs-toggle="collapse" data-bs-target="#accordionPeticion{{$peticion->id}}" aria-expanded="true" aria-controls="accordionPeticion{{$peticion->id}}">
                Petición
              </button>
            </h2>

            <div id="accordionPeticion{{$peticion->id}}" class="accordion-collapse collapse" data-bs-parent="#accordionDePeticiones{{$peticion->id}}">
              <div class="accordion-body" style="height: 100px; overflow-y: scroll;">
                <p class="text-secondary mt-0 mb-2"><b><i class="ti ti-user-circle"></i> Creada por:</b> {{$peticion->usuarioCreacion }}</p>
                <p class="m-0">{!! $peticion->descripcion !!}</p>
              </div>
            </div>
          </div>

          @if($peticion->estado > 1)
          <div class="card accordion-item">
            <h2 class="accordion-header" id="headingSeguimiengo{{$peticion->id}}">
              <button type="button" class="accordion-button collapsed fw-bold" data-bs-toggle="collapse" data-bs-target="#accordionSeguimiengo{{$peticion->id}}" aria-expanded="false" aria-controls="accordionSeguimiengo{{$peticion->id}}">
                Seguimiento
              </button>
            </h2>
            <div id="accordionSeguimiengo{{$peticion->id}}" class="accordion-collapse collapse" aria-labelledby="headingSeguimiengo{{$peticion->id}}" data-bs-parent="#accordionDePeticiones{{$peticion->id}}">
              <div class="accordion-body" style="height: 100px; overflow-y: scroll;">
                @foreach($peticion->seguimientos as $seguimiento)
                <p class="text-secondary mt-0 mb-2"><b><i class="ti ti-user-circle"></i> Creada por:</b> {{$seguimiento->usuarioCreacion ? $seguimiento->usuarioCreacion->nombre(3) : 'No definido' }}</p>
                <p class="m-0">{!! $seguimiento->descripcion !!}</p>
                <hr>
                @endforeach
              </div>
            </div>
          </div>
          @endif

          @if($peticion->estado==2)
          <div class="card accordion-item">
            <h2 class="accordion-header" id="headingRespuesta{{$peticion->id}}">
              <button type="button" class="accordion-button collapsed fw-bold" data-bs-toggle="collapse" data-bs-target="#accordionRespuesta{{$peticion->id}}" aria-expanded="false" aria-controls="accordionRespuesta{{$peticion->id}}">
                Respuesta
              </button>
            </h2>
            <div id="accordionRespuesta{{$peticion->id}}" class="accordion-collapse collapse" aria-labelledby="headingRespuesta{{$peticion->id}}" data-bs-parent="#accordionDePeticiones{{$peticion->id}}">
              <div class="accordion-body">
                {!! $peticion->respuesta !!}
              </div>
            </div>
          </div>
          @endif

          @if($peticion->estado!=2)
          <div class="mt-3">
            <center>
              <button type="button" onclick="modalRespuesta('{{$peticion->id}}', '{{$peticion->user_id}}')" class="btn btn-sm rounded-pill btn-outline-primary waves-effect"> <i class="ti ti-file-check"></i> Respuesta</button>
              <button type="button" onclick="modalSeguimiento('{{$peticion->id}}', '{{$peticion->user_id}}')" class="btn btn-sm rounded-pill btn-outline-warning waves-effect"> <i class="ti ti-file-like"></i> Seguimiento</button>
            </center>
          </div>
          @endif
        </div>

        </div>
      </div>
    </div>
    @endforeach
  </div>
  <!--/ lista de peticiones -->

  <div class="row my-3">
    @if($peticiones)
    <p> {{$peticiones->lastItem()}} <b>de</b> {{$peticiones->total()}} <b>peticiones - Página</b> {{ $peticiones->currentPage() }} </p>
    {!! $peticiones->appends(request()->input())->links() !!}
    @endif
  </div>

  @livewire('Peticiones.gestionar-peticiones')

  <!-- Modal generador de excel -->
  <div class="modal fade modalSelect2" id="modalGeneradorExcel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <form class="forms-sample" method="POST" action="{{ route('peticion.generarExcel', $tipo) }}">
        @csrf
        <textarea id="parametros-busqueda" name="parametrosBusqueda" class="d-none">{{json_encode(request()->input())}}</textarea>
        <div class="modal-content">
          <div class="modal-header d-flex flex-column">
            <h4 class="modal-title">Generador de excel</h4>
            <p class="modal-subtitle text-center">Selecciona los campos que deseas exportar en el archivo Excel.</p>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">

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

              <!-- Información petición-->
              <div class="col-12 mb-3">
                <label for="informacionCamposPeticiones" class="form-label">Información campos petición
                  (<a href="javascript:;" data-select="informacionCamposPeticiones" class="selectAllItems"><span class="fw-medium">Seleccionar todos</span></a> | <a href="javascript:;" data-select="informacionCamposPeticiones" class="clearAllItems"><span class="fw-medium">Quitar todos</span></a>)
                </label>
                <select id="informacionCamposPeticiones" name="informacionCamposPeticiones[]" class="select2GeneradorExcel form-select" multiple>
                  @foreach($camposPeticiones as $campoPeticion)
                  <option value="{{ $campoPeticion->id }}">{{ $campoPeticion->nombre }}</option>
                  @endforeach
                </select>
              </div>

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

  <form id="eliminacionMasiva" method="POST" action="">
    @csrf
    <textarea id="parametros-busqueda" name="parametrosBusqueda" class="d-none">{{json_encode(request()->input())}}</textarea>
  </form>

  <form id="eliminacion" method="POST" action="">
    @csrf
  </form>

@endsection
