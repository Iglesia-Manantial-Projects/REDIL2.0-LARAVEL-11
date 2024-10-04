@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Temas')

<!-- Page -->
@section('vendor-style')

<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<style>
   .text {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
</style>


@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
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
        <div class="col-12 col-md-4">
          <select id="categorias" name="categorias[]" class="select2 form-select" multiple>
            @foreach($categorias as $categoria)
            <option value="{{ $categoria->id }}" {{ $categoriasSeleccionadas && in_array($categoria->id,$categoriasSeleccionadas) ? 'selected' : '' }}>{{ $categoria->nombre }}</option>
            @endforeach
          </select>
        </div>
  
        <div class="col-12 col-md-8">
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

    <div class="row g-4 mt-1">
      @foreach($temas as $tema)
      <div style="min-height: 400px;"   class="col-12 col-xl-4 col-lg-6 col-md-6">
        <div style="min-height: 400px;"  class="card border rounded p-2">
          @if($rolActivo->hasPermissionTo('temas.editar_tema'))
          <div class="dropdown btn-pinned border rounded p-1">
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
                <li>
                <a data-id="{{$tema->id}}"  data-nombre="{{$tema->titulo}}" class=" confirmacionEliminar dropdown-item" ">
                  <span class="me-2">Eliminar tema</span>
                </a>
              </li>
              @endif
            </ul>  
          </div> 
          @endif   
        <div style=" max-height: 235px;margin-top:55px" class="rounded-2 text-center mb-3">
          <a href="{{route('tema.ver', $tema)}}">
            <img class="img-fluid" src="{{ $configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/temas/archivos/'.$tema->portada) : ''}}" alt="tutor image 1" /></a>
        </div>
        <div class="card-body p-3 pt-2">
          <div class="d-flex justify-content-between align-items-center mb-3">

            @foreach($tema->categorias as $categoria)
            <span class="float-start badge bg-label-primary">{{$categoria->nombre}}</span>
            @endforeach
          </div>
          <a href="" style="line-height:32px; margin-bottom:20px;" class="h2">{{$tema->titulo}}</a>

          <div  class="d-flex flex-column mt-2 flex-md-row gap-2 text-nowrap">

            <a class="app-academy-md-50 btn btn-label-primary d-flex align-items-center" href="{{route('tema.ver', $tema)}}">
              <span class="me-2">Ver más</span><i class="ti ti-chevron-right scaleX-n1-rtl ti-sm"></i>
            </a>
            
          </div>
        </div>
        </div>
      </div>  

      @endforeach
    </div>
    
    <br>

    @if($temas)
          {!! $temas->appends(request()->input())->links() !!}
    @endif

    <form id="eliminarTema" method="POST" action="">
    @csrf
    </form>

@endsection
