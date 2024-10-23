@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Temas')

<!-- Page -->
@section('vendor-style')

@vite([
  'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
  'resources/assets/vendor/libs/select2/select2.scss',
])

@endsection

@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/flatpickr/flatpickr.js',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
    'resources/assets/vendor/libs/select2/select2.js'
  ])
@endsection


@section('page-script')
<script>
    $(document).ready(function()
    {
        $('.select2').select2({
          dropdownParent: $('#formulario'),
          placeholder: "Buscar por categoría"
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


      ///confirmación para eliminar tema
      $('.confirmacionEliminar').on('click', function () {
    let nombre = $(this).data('nombre');
    let id = $(this).data('id');

    Swal.fire({
      title: "¿Estás seguro que deseas eliminar el tema <b>"+nombre+"</b>?",
      html: "Esta acción no es reversible.",
      icon: "warning",
      showCancelButton: false,
      confirmButtonText: 'Si, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        $('#eliminarTema').attr('action',"/tema/"+id+"/eliminar");
        $('#eliminarTema').submit();
      }
    })
  });

</script>

@endsection

@section('content')
  <h4 class="mb-1">Listado de temas</h4>
  <p class="mb-4">Aquí encontraras el listado de todos los temas a los que puedes acceder.</p>

  @include('layouts.status-msn')

  <div class="row mt-5">
    <form id='formulario' class="forms-sample" method="GET" action="{{ route('tema.lista') }}">
      <div class="row m-0 p-0">
        <!-- Por categoria -->
        <div class="col-12 col-md-6">
          <select id="categorias" name="categorias[]" class="select2 form-select" multiple>
            @foreach($categorias as $categoria)
            <option value="{{ $categoria->id }}" {{ $categoriasSeleccionadas && in_array($categoria->id,$categoriasSeleccionadas) ? 'selected' : '' }}>{{ $categoria->nombre }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-12 col-md-6">
          <div class="input-group">
            <input id="buscar" name="buscar" type="text" value="{{ $buscar }}" class="form-control" placeholder="Busqueda..." aria-label="" aria-describedby="button-addon2">
            <button class="btn btn-outline-primary px-2 px-md-3" type="submit" id="button-addon2"><i class="ti ti-search"></i></button>
            @if($bandera == 1)
              <a href="{{ route('tema.lista') }}" class="btn btn-outline-danger" type="submit"><i class="ti ti-x"></i></a>
            @endif

          </div>
        </div>
      </div>
    </form>
    @if($temas)
      <span class="text-center py-3">{{ $temas->total() > 1 ? $temas->total().' Temas' : $temas->total().' Tema' }} {!! $textoBusqueda ? '('.$textoBusqueda.')' : '' !!}</span>
    @endif
  </div>

  <!-- Lista de temas -->
  <div class="row g-4 mt-1">
  @foreach($temas as $tema)
    <div class="col-md-6 col-lg-4">
      <div class="card h-100">
        <img class="card-img-top" src="{{ $configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/temas/archivos/'.$tema->portada) : ''}}" alt="Card image cap" />
        <div class="card-body">

          <div class="d-flex align-items-start">
            <div class="d-flex align-items-start">
              <h5 class="card-title">{{ $tema->titulo}}</h5>
            </div>
            <div class="ms-auto">
              @if($rolActivo->hasPermissionTo('temas.editar_tema'))
                <div class="dropdown zindex-2 border rounded p-1">
                <button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical text-muted"></i></button>
                  <ul class="dropdown-menu dropdown-menu-end">
                    @if($rolActivo->hasPermissionTo('temas.editar_tema'))
                    <li>
                      <a class="dropdown-item" href="{{route('tema.actualizar', $tema)}}">
                        <span class="me-2">Editar tema</span>
                      </a>
                    </li>
                    @endif
                    @if($rolActivo->hasPermissionTo('temas.eliminar_tema'))
                    <hr class="dropdown-divider">
                      <li>
                      <a data-id="{{$tema->id}}"  data-nombre="{{$tema->titulo}}" class="dropdown-item text-danger waves-effect confirmacionEliminar" >
                        <span class="me-2">Eliminar tema</span>
                      </a>
                    </li>
                    @endif
                  </ul>
                </div>
              @endif
            </div>
          </div>

          <div class="mb-5">
            @if($tema->categorias->count()> 0)
              @foreach($tema->categorias as $categoria)
                <span class="badge rounded-pill bg-label-primary mb-1">{{$categoria->nombre}}</span>
              @endforeach
            @else
                <span class="badge rounded-pill bg-label-secondary mb-1">Sin categoria </span>
            @endif
          </div>

          <a href="{{route('tema.ver', $tema)}}" class="btn btn-primary w-100 waves-effect waves-light">Ver más <i class="ti ti-chevron-right scaleX-n1-rtl ti-sm"></i></a>
        </div>
      </div>
    </div>
  @endforeach
  </div>
  <!-- Lista de temas -->

  @if($temas)
    {!! $temas->appends(request()->input())->links() !!}
  @endif

  <form id="eliminarTema" method="POST" action="">
  @csrf
  </form>

@endsection
