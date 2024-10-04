@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Sedes')

<!-- Page -->
@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
@endsection

@section('page-script')
<script>
  $('.confirmacionEliminar').on('click', function () {
    let nombre = $(this).data('nombre');
    let id = $(this).data('id');

    Swal.fire({
      title: "¿Estás seguro que deseas eliminar a <b>"+nombre+"</b>?",
      html: "Esta acción no es reversible.",
      icon: "warning",
      showCancelButton: false,
      confirmButtonText: 'Si, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        $('#eliminarSede').attr('action',"/sede/"+id+"/eliminar");
        $('#eliminarSede').submit();
      }
    })
  });
</script>
@endsection

@section('content')
<h4 class="mb-1">Sedes</h4>
<p class="mb-4">Aquí encontraras el listado de sedes.</p>

@include('layouts.status-msn')

  <div class="row mt-5">
    <form class="forms-sample" method="GET" action="{{ route('sede.lista') }}">
      <div class="col-12 offset-md-2 col-md-8 d-flex">
        <div class="input-group">
          <input id="buscar" name="buscar" type="text" value="{{$buscar}}" class="form-control" placeholder="Busqueda..." aria-label="Recipient's username" aria-describedby="button-addon2">
          <button class="btn btn-outline-primary px-2 px-md-3" type="submit" id="button-addon2"><i class="ti ti-search"></i></button>
          @if($buscar)
          <a href="{{ route('sede.lista') }}" class="btn btn-outline-danger" type="submit"><i class="ti ti-x"></i></a>
          @endif
        </div>
        <!-- Button trigger modal -->
      </div>
    </form>
    @if($sedes)
    <span class="text-center py-3">{{ $sedes->total() > 1 ? $sedes->total().' Sedes' : $sedes->total().' Sede' }} {!! $buscar ? '(Con busqueda <b>"'.$buscar.'"</b>)' : '' !!} </span>
    @endif
  </div>

  <!-- lista de sedes -->
  <div class="row g-4 mt-1">
  @foreach($sedes as $sede)
    <div class="col-12 col-xl-4 col-lg-6 col-md-6">
      <div class="card border rounded p-2">
        <div class="card-header p-1">
          <div class="d-flex align-items-start">
            <div class="ms-auto">
              <div class="dropdown zindex-2 border rounded p-1">
                <button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical text-muted"></i></button>
                <ul class="dropdown-menu dropdown-menu-end">
                  @if($rolActivo->hasPermissionTo('sedes.opcion_ver_perfil_sede'))
                    <li><a class="dropdown-item" href="{{ route('sede.perfil', $sede)}}">Perfil</a></li>
                  @endif
                  @if($rolActivo->hasPermissionTo('sedes.opcion_modificar_sede'))
                    <li><a class="dropdown-item" href="{{ route('sede.modificar', $sede)}}">Modificar</a></li>
                  @endif
                  @if($rolActivo->hasPermissionTo('sedes.opcion_eliminar_sede'))
                    <li><a class="dropdown-item confirmacionEliminar" data-nombre="{{ $sede->nombre }}" data-id="{{ $sede->id }}" href="javascript:;">Eliminar</a></li>
                  @endif
                </ul>
              </div>
            </div>
          </div>
          <div class="text-center">
            <div class="mx-auto my-3">
              <img src="{{ $configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/img/foto-sede/'.$sede->foto) : $configuracion->ruta_almacenamiento.'/img/foto-sede/'.$sede->foto }}" alt="foto {{$sede->nombre}}" class="rounded-circle w-px-100" />
            </div>
            <h4 class="mb-1 card-title">{{ $sede->nombre}}</h4>
            <p class="pb-1">
              <span class="badge bg-primary">
              <i class="fs-6"></i> {{ $sede->tipo->nombre }} </span>
            </p>
          </div>
        </div>
        <div class="card-body">

          <div class="row gy-1">
            <span class="fw-bold">Personas</span><br>
            <div class="col-12 col-md-5">
              <div class="d-flex align-items-center">
                <div class="badge rounded bg-label-primary me-2 p-2"><i class="ti ti-users "></i></div>
                <div class="card-info">
                  <small>Todas</small>
                  <h5 class="mb-0">{{ $sede->usuarios()->select('id')->count() }} </h5>
                </div>
              </div>
            </div>

            <div class="col-12 col-md-7">
              <div class="d-flex align-items-center">
                <div class="badge rounded bg-label-danger me-2 p-2"><i class="ti ti-user-x "></i></div>
                <div class="card-info">
                  <small>Inactivos en grupos</small>
                  <h5 class="mb-0">
                  {{ $sede->usuariosInactivosGrupos() }} </h5>
                </div>
              </div>
            </div>

            <div class="col-12 col-md-12">
              <div class="d-flex align-items-center">
                <div class="badge rounded bg-label-danger me-2 p-2"><i class="ti ti-building-church"></i></div>
                <div class="card-info">
                  <small>Inactivos en reunión</small>
                  <h5 class="mb-0">
                  {{ $sede->usuariosInactivosReuniones() }} </h5>
                </div>
              </div>
            </div>
          </div>

          <div class="row gy-1 mt-2">
            <span class="fw-bold">Grupos</span><br>

            <div class="col-12 col-md-5">
              <div class="d-flex align-items-center">
                <div class="badge rounded bg-label-primary me-2 p-2"><i class="ti ti-users-group "></i></div>
                <div class="card-info">
                  <small>Todos</small>
                  <h5 class="mb-0">{{ $sede->grupos()->select('id')->count() }} </h5>
                </div>
              </div>
            </div>

            <div class="col-12 col-md-7">
              <div class="d-flex align-items-center">
                <div class="badge rounded bg-label-danger me-2 p-2"><i class="ti ti-exclamation-circle"></i></div>
                <div class="card-info">
                  <small>Sin actividad</small>
                  <h5 class="mb-0">{{ $sede->gruposNoReportados() }} </h5>
                </div>
              </div>
            </div>
          </div>

          <div class="my-2 py-1 ">
            <span class="fw-bold">Encargados</span><br>
            <div style="height: 60px; overflow-y: scroll;">
              @foreach($sede->encargados() as $encargado)
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
  <!--/ lista de sedes -->

  <div class="row my-3">
    @if($sedes)
    {!! $sedes->appends(request()->input())->links() !!}
    @endif
  </div>

  <form id="eliminarSede" method="POST" action="">
    @csrf
  </form>


@endsection
