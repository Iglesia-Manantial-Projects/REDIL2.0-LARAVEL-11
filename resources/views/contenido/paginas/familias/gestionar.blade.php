@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Relaciones Familiares')

<!-- Page -->
@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-profile.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js')}}"></script>
@endsection

@section('page-script')
<script type="text/javascript">
  ///confirmación para eliminar tema
  $('.confirmacionEliminar').on('click', function () {
    let nombre = $(this).data('nombre');
    let pariente = $(this).data('id');

    Swal.fire({
      title: "¿Estás seguro que deseas eliminar la relación familiar</b>?",
      html: "Esta acción no es reversible.",
      icon: "warning",
      showCancelButton: false,
      confirmButtonText: 'Si, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        $('#eliminarRelacion').attr('action',"/familias/"+pariente+"/eliminar");
        $('#eliminarRelacion').submit();
      }
    })
  });

  function abrirModalActualizarPariente(relacionId,usuarioId)
  {
    Livewire.dispatch('abrirModalActualizarPariente', { relacionId: relacionId , usuarioId: usuarioId} );
  }

</script>
@endsection

@section('content')



<h4 class="mb-1">Gestionar relaciones familiares</h4>
<p class="mb-4">Aquí podrás crear relaciones familiares entre los miembros de tu congregación.</p>

@include('layouts.status-msn')

<div class="row">
  <div class="col-lg-3 col-md-5 col-xs-12">
    <!-- Familiar principal -->
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between">
        <p class="card-text text-uppercase fw-bold"><i class="ti ti-user ms-n1 me-2"></i>Buscar usuario</p>
      </div>
      <div class="card-body pb-20 row">
        @livewire('Usuarios.usuarios-para-busqueda', [
          'id' => 'buscador-usuario',
          'tipoBuscador' => 'unico',
          'conDadosDeBaja' => 'no',
          'class' => 'col-12 col-md-12 mb-3',
          'placeholder' => 'Seleccione un usuario',
          'queUsuariosCargar'=>'todos',
          'modulo' => 'familiar-principal',
          'redirect'=> 'familias.gestionar',
          'usuarioSeleccionadoId'=>$userId ? $userId : ''       
        ])
      </div>
     
    </div>
    <!--/ Familiar principal -->
  </div>

 
  <div class=" col-lg-9 col-md-7 col-xs-12">
  
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between">
        <p class="card-text text-uppercase fw-bold"><i class="ti ti-home-star ms-n1 me-2"></i>MIS RELACIONES FAMILIARES</p>
       @if(isset($userId))
        <button type="button" class="btn btn-sm btn-label-primary waves-effect" data-bs-toggle="modal" data-bs-target="#onboardHorizontalImageModal"> <i class="ti ti-user-plus pb-1"></i> Nueva relación</button>
       @endif
      </div>
      
      <div class="card-body">
        <div class="row g-4">
          @if(count($parientes) > 0)
          @foreach($parientes as $pariente)
          <div class="col-lg-4 col-md-12 col-xs-12">
            <div class="card border rounded">
             
              <div class="dropdown btn-pinned border rounded p-1">
                <button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical text-muted"></i></button>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a href="javascript:void(0);" onclick="abrirModalActualizarPariente('{{$pariente->id}}', '{{$userId}}')" class="dropdown-item" href=" ">
                      <span class="me-2">Editar relación</span>
                    </a>
                  </li>
                  <li>
                    <a data-id="{{$pariente->id}}" data-nombre="" class=" confirmacionEliminar dropdown-item">
                      <span class="me-2">Eliminar relación</span>
                    </a>
                  </li>
                
                </ul>  
              </div> 
          
              <div class="card-body text-center">
                <div class="mx-auto my-3">
                  <img src="{{ $configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/img/foto-usuario/'.$pariente->foto) : $configuracion->ruta_almacenamiento.'/img/foto-usuario/'.$pariente->foto }}" alt="foto {{$pariente->primer_nombre}}" class="rounded-circle w-px-100" />
                </div>

                <span class="pb-1"><span></span><b>Relación:</b> {{ $usuario->genero == 0 ? $pariente->nombre_masculino : $pariente->nombre_femenino }} de </span>
                <h4 class="mb-1 card-title">{{ $pariente->nombre(3) }}</h4>

                <div class="d-flex align-items-center justify-content-center my-3 gap-2">
                  <span>¿Soy el responsable?</span>
                  @if($pariente->es_el_responsable)
                  <a href="javascript:;" class="me-1"><span class="badge bg-label-success">Si</span></a>
                  @else
                  <a href="javascript:;"><span class="badge bg-label-secondary">No</span></a>
                  @endif
                </div>
              </div>
            </div>
          </div>
          @endforeach
          @else
          <div class="py-4">
            <center>
              <i class="ti ti-home-heart fs-1 pb-1"></i>
              <h6 class="text-center">No hay personas en tu grupo familiar</h6>
            </center>
          </div>
          @endif
        </div>
      </div>
   
    </div>
 
  </div>

    <!-- SECCIÓN MODALES -->
    @if($userId)
    <div class="modal-onboarding modal fade animate__animated" id="onboardHorizontalImageModal" tabindex="-1" aria-hidden="true">
      <form id="formulario" role="form" class="forms-sample" method="get" action="{{ route('familias.crear') }}"  enctype="multipart/form-data" >
        
        <div class="modal-dialog  modal-lg" role="document">
          <div class="modal-content text-center">
            <div class="modal-header border-0">
              
              <butto  type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar">
              </button>
            </div>
            <div class="modal-body  p-0">
              
              <div class="onboarding-content mb-0">
                <h4 class="onboarding-title text-body">NUEVA RELACIÓN FAMILIAR</h4>
                <div class="onboarding-info">Elige los campos necesarios para crear tu relación familiar</div>
                 
                <div class="row mt-3">
                    <!-- Familiar principal -->
                   <div class="col-lg-12 col-md-12 col-sm-12  mt-3">
                    <div class="card-header d-flex justify-content-between">
                    
                      @livewire('Usuarios.usuarios-para-busqueda', [
                        'id' => 'buscador_asistente_modal',
                        'tipoBuscador' => 'unico',
                        'conDadosDeBaja' => 'no',
                        'class' => 'col-12 col-md-12 mb-3',
                        'label' => 'Seleccione un usuario',
                        'queUsuariosCargar'=>'todos',
                        'modulo' => 'familiar-secundario'    
                      ])
                    </div>
                  </div>   
                   
                  <!--/ Familiar principal -->
                    <div class="col-lg-12 col-md-12  col-sm-12  mt-3">
                        <div class="mb-3">
                          <label class="form-label">¿Qué relación tiene <b>{{$usuario->nombre(3)}}</b> con el pariente?</label>
                          <select id="tipoParentesco" name="tipoParentesco" class="form-select" tabindex="0" id="roleEx7">
                            <option value="0">Selecciona una opción</option>
                                @foreach($tiposParentesco as $tipo)
                                      <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                              @endforeach
                          </select>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12  col-sm-12  mt-3">
                      <div class="mb-3">
                                <label class="form-label">Responsabilidad</label>
                                <select id="responsabilidad" name="responsabilidad" class="form-select" tabindex="0" id="roleEx7">
                                  <option value="1">Ninguna</option>
                                  <option value="2"><b>{{$usuario->nombre(3)}}</b> es el responsable del pariente</option>
                                   <option value="3"> El pariente es el responsable de <b>{{$usuario->nombre()}} </b></option>
                                  </select>
                      </div>
                    </div>
                  
                    @if(isset($userId))
                    <input id="parientePrincipal" name="parientePrincipal" class="d-none" value="{{$userId}}">
                    @endif
                </div>
              </div>
            </div>
            <div class="modal-footer border-0">
              <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
          </div>
        </div>

       </form>
    </div>
    @endif   
    @livewire('Familias.actualizar-pariente')

    <form id="eliminarRelacion" method="POST" action="">
      @csrf
    </form>
  
</div>


@endsection
