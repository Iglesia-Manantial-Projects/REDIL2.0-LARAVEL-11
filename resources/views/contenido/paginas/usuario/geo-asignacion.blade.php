@php
$configData = Helper::appClasses();
$isFooter = ($isFooter ?? false);
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Geo asignación')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
@endsection

@section('page-style')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
@endsection

@section('page-script')
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>

<script>
  var map = L.map('map').setView(['{{$latitudInicial}}', '{{$longitudInicial}}'], 13);
  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '<a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
  }).addTo(map);

  //var osmGeocoder = new L.Control.OSMGeocoder();

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
      var direccionGrupo = "";
      if ("{{$grupo->direccion}}" != "") {
        direccionGrupo = " ubicado en la dirección '{{$grupo->direccion}}'";
      }
      var nombreUsuario = "{{$usuario->nombre(3)}}";
      var idUsuario = "{{$usuario->id}}";

      DatosCelula = [{
        "info": "Agregar a <b>'" + nombreUsuario + "'</b> al Grupo  <b>" + tipoGrupo + " '" + nombreGrupo + "'" + direccionGrupo + "</b><br><br> <b><i class='ti ti-circle-plus'></i>Clic aquí para agregar </b>",
        "url": "javascript:asignarAsistente(" + grupoId + "," + idUsuario + ");"
      }];

      L.marker(['{{$grupo->latitud}}', '{{$grupo->longitud}}'], {
        icon: IconoGrupo,
        title: 'title',
        alt: 'alt'
      })
      .bindPopup('<a href="' + DatosCelula[0].url + '" target="">' + DatosCelula[0].info + '</a>')
      .addTo(map);

    @endif
  @endforeach

  function asignarAsistente(grupoId, idUsuario) {
    Livewire.dispatch('asignar-al-grupo', { grupoId: grupoId, idUsuario: idUsuario });
  }
</script>
<script>
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
          <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-home" aria-controls="navs-pills-justified-home" aria-selected="true">
            <span class="badge rounded-pill badge-center h-px-10 w-px-10 bg-label-primary ms-1 mx-1">2</span>
            Información congregacional
          </button>
        </a>
      </li>
      @endif
      @elseif(auth()->user()->id == $usuario->id && $rolActivo->hasPermissionTo('personas.autogestion_pestana_informacion_congregacional'))
      <li class="nav-item">
        <a href="{{ route('usuario.informacionCongregacional', ['formulario' => $formulario ,'usuario' => $usuario]) }}">
          <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-home" aria-controls="navs-pills-justified-home" aria-selected="true">
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
          <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-home" aria-controls="navs-pills-justified-home" aria-selected="true">
            <span class="badge rounded-pill badge-center h-px-10 w-px-10 bg-label-primary ms-1 mx-1">3</span>
            Geo asignación
          </button>
        </a>
      </li>
      @else
      <li class="nav-item">
        <a href="{{ route('usuario.geoAsignacion', ['formulario' => $formulario ,'usuario' => $usuario]) }}">
          <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-home" aria-controls="navs-pills-justified-home" aria-selected="true">
            <span class="badge rounded-pill badge-center h-px-10 w-px-10 bg-label-primary ms-1 mx-1">3</span>
            Geo asignación
          </button>
        </a>
      </li>
      @endif
      @endif

    </ul>
  </div>

  <h4 class="mb-1 mayusculas">Geo asignación</h4>
  <p class="mb-4">Aquí podrás gestionar a <b>{{$usuario->nombre(3)}}</b> a un grupo.</p>

  @include('layouts.status-msn')

  @livewire('Usuarios.mapa-geo-asignacion')

  <div id="map" class="border-0 shadow-sm w-100 h-75 mb-4"></div>

@endsection
