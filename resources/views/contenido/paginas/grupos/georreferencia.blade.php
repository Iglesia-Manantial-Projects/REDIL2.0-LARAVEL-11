@php
$configData = Helper::appClasses();
$isFooter = ($isFooter ?? false);
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Grupos')

<!-- Page -->
@section('vendor-style')
@vite([

'resources/assets/vendor/libs/select2/select2.scss',
'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
])
@endsection

@section('page-style')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endsection

@section('vendor-script')
@vite([
'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
'resources/assets/vendor/libs/select2/select2.js',
'resources/assets/vendor/libs/flatpickr/flatpickr.js',
])
@endsection

@section('page-script')
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>

<script type="module">
  // Inicialización del mapa
  var map = L.map('map').setView(['{{$latitudInicial}}', '{{$longitudInicial}}'], 13);
  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '<a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
  }).addTo(map);

  // Hacer la función asignarAsistente globalmente accesible
  window.asignarAsistente = function(grupoId, idUsuario) {
    Livewire.dispatch('asignar-al-grupo', { grupoId: grupoId, idUsuario: idUsuario });
  };

  @foreach($grupos as $grupo)
    @if($grupo->latitud != null && $grupo->longitud != null && $grupo->tipoGrupo->visible_mapa_asignacion == true)
      var IconoGrupo = L.icon({
        iconUrl: "{{$configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/img/pines-mapa/'.$grupo->tipoGrupo->geo_icono) : $configuracion->ruta_almacenamiento.'/img/foto-usuario/default-m.png' }}",
        iconSize: [35, 45],
        iconAnchor: [27, 27],
        popupAnchor: [0, -14]
      });

      var nombreGrupo = "{{$grupo->nombre}}";
      var grupoId = "{{$grupo->id}}";
      var tipoGrupo = "{{$grupo->tipoGrupo->nombre}}";
      var direccionGrupo = "{{$grupo->direccion}}" != "" ? ` ubicado en la dirección '{{$grupo->direccion}}'` : "";
      var nombreUsuario = "{{$usuario->nombre(3)}}";
      var idUsuario = "{{$usuario->id}}";

      var DatosCelula = [{
        "info": `Agregar a <b>'${nombreUsuario}'</b> al Grupo <b>${tipoGrupo} '${nombreGrupo}'${direccionGrupo}</b><br><br> <b><i class='ti ti-circle-plus'></i>Clic aquí para agregar </b>`,
        "url": "#"
      }];

      L.marker(['{{$grupo->latitud}}', '{{$grupo->longitud}}'], {
        icon: IconoGrupo,
        title: nombreGrupo,
        alt: nombreGrupo
      })
      .bindPopup(`<a href="#" onclick="asignarAsistente(${grupoId}, ${idUsuario}); return false;">${DatosCelula[0].info}</a>`)
      .addTo(map);
    @endif
  @endforeach
</script>

<script type="module">
  window.addEventListener('verEnElMapa', event => {
    map.flyTo(new L.LatLng(event.detail.latitud, event.detail.longitud), event.detail.zoom);
  });

  window.addEventListener('msn', event => {
    Swal.fire({
      title: event.detail.msnTitulo,
      html: event.detail.msnTexto,
      icon: event.detail.msnIcono,
      customClass: {
        confirmButton: 'btn btn-primary'
      },
      buttonsStyling: false
    });
  });
</script>
@endsection

@section('content')

<div class="row mb-2">
  <ul class="nav nav-pills mb-3 d-flex justify-content-end" role="tablist">

    <li class="nav-item">
      <a href="{{ route('grupo.modificar',$grupo) }}">
        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-home" aria-controls="navs-pills-justified-home" aria-selected="true">
          <span class="badge rounded-pill badge-center h-px-10 w-px-10 bg-label-primary ms-1 mx-1">1</span>
          Datos principales
        </button>
      </a>
    </li>

    <li class="nav-item">
      <a href="{{ route('grupo.gestionarEncargados',$grupo) }}">
        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-home" aria-controls="navs-pills-justified-home" aria-selected="true">
          <span class="badge rounded-pill badge-center h-px-10 w-px-10 bg-label-primary ms-1 mx-1">2</span>
          Encargados
        </button>
      </a>
    </li>

    <li class="nav-item">
      <a href="{{ route('grupo.gestionarIntegrantes',$grupo) }}">
        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-home" aria-controls="navs-pills-justified-home" aria-selected="true">
          <span class="badge rounded-pill badge-center h-px-10 w-px-10 bg-label-primary ms-1 mx-1">3</span>
          Integrantes
        </button>
      </a>
    </li>

    <li class="nav-item">
      <a href="{{ route('grupo.georreferencia',$grupo) }}">
        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-home" aria-controls="navs-pills-justified-home" aria-selected="true">
          <span class="badge rounded-pill badge-center h-px-10 w-px-10 bg-label-primary ms-1 mx-1">4</span>
          Georeferencia
        </button>
      </a>
    </li>
  </ul>
</div>

<h4 class="mb-1">Gestionar georreferencia</h4>
<p class="mb-4">Para añadir la georefernecia, haga clic en el mapa y se asignará de forma automática al grupo.</p>

@include('layouts.status-msn')

@livewire('Usuarios.mapa-geo-asignacion')
<div id="map" class="border-0 shadow-sm w-100 h-75 m-10"></div>
<br><br>

@endsection
