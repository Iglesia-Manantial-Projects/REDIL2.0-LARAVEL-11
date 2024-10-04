@php
$configData = Helper::appClasses();
$isFooter = ($isFooter ?? false);
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Grupos')

<!-- Page -->
@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
@endsection

@section('page-style')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
@endsection

@section('page-script')
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>

<script>
  var zoom = 10;
  var map = L.map('map').setView(['{{$latitudInicial}}', '{{$longitudInicial}}'], zoom);
  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '<a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
  }).addTo(map);

  var marcador;


  // función que crea los pines en el mapa
  function crearMarcadores(){

    @foreach($grupos as $grupo)
      lat = "{{$grupo->latitud}}";
      lng = "{{$grupo->longitud}}";
        IconoGrupo = L.icon({
        iconUrl: "{{$configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/img/pines-mapa/'.$grupo->tipoGrupo->geo_icono) : $configuracion->ruta_almacenamiento.'/img/foto-usuario/default-m.png' }}",
        iconSize: [35, 45],
        iconAnchor: [27, 27],
        popupAnchor: [0, -14]
      });

      var verPerfil = '';
      @if($rolActivo->hasPermissionTo('grupos.opcion_ver_perfil_grupo'))
      var verPerfil = '<br><a href="/grupo/{{$grupo->id}}/perfil" target="_blank"> <b> <i class="ti ti-users-group"></i> Ver perfil  </b> </a>';
      @endif

      marcador = L.marker([lat, lng], {
        icon: IconoGrupo,
        title: '{{$grupo->nombre}} ( LAT: '+lat+' LNG: '+lng+' )',
        alt: '{{$grupo->id}}'
      })
      .bindPopup( "<b>Nombre:</b> {{$grupo->nombre}} <br> <b>Latitud:</b> "+lat+"<br> <b>Longitud:</b> "+lng + ' <br><br><a href="https://www.google.com/maps/@?api=1&map_action=pano&viewpoint='+lat+'%2C'+lng+'" target="_blank"> <b> <i class="ti ti-brand-google-maps"></i> Ver en google  </b> </a> '+verPerfil )

      map.addLayer(marcador);
      map.flyTo([lat, lng], zoom);
    @endforeach
  }

  function crearCoverturas(){

    @foreach($grupos as $grupo)

      lat = "{{$grupo->latitud}}";
      lng = "{{$grupo->longitud}}";

      L.circle([lat, lng], {
        color: '{{$grupo->tipoGrupo->color}}',
        fillColor: '{{$grupo->tipoGrupo->color}}',
        fillOpacity: 0.5,
        radius: '{{$grupo->tipoGrupo->metros_cobertura}}'
      }).addTo(map);
    @endforeach
  }

  function reiniciarMapa()
  {
    map.remove();
    map = L.map('map').setView(['{{$latitudInicial}}', '{{$longitudInicial}}'], 10);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '<a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);
  }


  $(document).ready(function() {
    crearMarcadores();

    $('#verCovertura').click(function() {
      if(this.checked){
        reiniciarMapa();
        crearCoverturas();
      }else{
        reiniciarMapa();
        crearMarcadores();
      }
    });
  });

</script>

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

</script>

@endsection

@section('content')

  <h4 class="mb-1">Mapa de grupos</h4>
  <p class="mb-4">Aquí podras visualizar los grupos en el mapa.</p>

  @include('layouts.status-msn')

  <div class="row">
    <form class="forms-sample" method="GET" action="{{ route('grupo.mapaDeGrupos') }}">
      <div class="col-12 offset-md-2 col-md-8 d-flex">
        <div class="input-group">
          <input id="buscar" name="buscar" type="text" value="{{$parametrosBusqueda->buscar}}" class="form-control" placeholder="Busqueda..." aria-label="Recipient's username" aria-describedby="button-addon2">
          <button class="btn btn-outline-primary px-2 px-md-3" type="submit" id="button-addon2"><i class="ti ti-search"></i></button>
          @if($parametrosBusqueda->bandera == 1)
          <a href="{{ route('grupo.mapaDeGrupos') }}" class="btn btn-outline-danger" type="submit"><i class="ti ti-x"></i></a>
          @endif
          <button type="button" class="btn btn-primary btn-sm px-2 px-md-3" data-bs-toggle="modal" data-bs-target="#modalBusquedaAvanzada"><i class="ti ti-input-search"></i> <span class="d-none d-md-block">Búsqueda avanzada</span></button>
        </div>
        <!-- Button trigger modal -->
      </div>
    </form>
    @if($grupos)
    <span class="text-center py-3">{{ $grupos->count() > 1 ? $grupos->count().' Grupos' : $grupos->count().' Grupo' }} {!! $parametrosBusqueda->textoBusqueda ? '('.$parametrosBusqueda->textoBusqueda.')' : '' !!}</span>
    @endif
  </div>


  <div class="col-12 col-md-2">
    <div class=" small fw-medium mb-2">Ver covertura
    <label class="switch switch-lg">
      <input id="verCovertura" name="verCovertura" type="checkbox" class="switch-input" />
      <span class="switch-toggle-slider">
        <span class="switch-on">SI</span>
        <span class="switch-off">NO</span>
      </span>
      <span class="switch-label"></span>
    </label>
    </div>
  </div>

  <div id="map" class="border-0 shadow-sm w-100 h-75 m-10"></div>
  <br><br>

  <!-- Modal busqueda avanzada -->
  <div class="modal fade modalSelect2" id="modalBusquedaAvanzada" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <form class="forms-sample" method="GET" action="{{ route('grupo.mapaDeGrupos') }}">
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

@endsection
