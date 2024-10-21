@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Grupos')

<!-- Page -->
@section('page-style')
@vite([
'resources/assets/vendor/scss/pages/page-profile.scss',
'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
])
@endsection

@section('vendor-script')
@vite([
'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'
])
@endsection

@section('page-script')
<script type="module">
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
<h4 class="mb-1">Excluir grupos</h4>
<p class="mb-4">Crea aquí las relaciones de exclusión entre usuarios y grupos, para que los usuarios no puedan visualizarlos, aunque estén bajo su cobertura.</p>

@include('layouts.status-msn')

<div class="row mt-2 mb-4">
  <div class="d-flex flex-row-reverse">
    <button data-bs-toggle="modal" data-bs-target="#addNuevaExclusion" class="btn btn-primary text-nowrap add-new-role waves-effect waves-light"><i class="ti ti-plus"></i> Nueva exclusión </button>
  </div>
</div>

@livewire('Grupos.listado-exclusiones-grupo')


    <!-- Modal add nueva exclusion -->
    <div id="addNuevaExclusion"  class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-body">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3 class="mb-2"><i class="ti ti-plus ti-lg"></i> Nueva exclusión</h3>
            <p class="text-muted">Por favor, selecciona el grupo y el usuario.</p>
          </div>
          <form method="POST" action="{{ route('grupo.crearExclusion') }}" class="row g-3">
          @csrf

            @livewire('Grupos.grupos-para-busqueda',[
              'id' => 'grupo',
              'class' => 'col-12 col-md-12 mb-3',
              'label' => 'Selecciona el grupo',
              'conDadosDeBaja' => 'no',
              'grupoSeleccionadoId' => ''
            ])

            @livewire('Usuarios.usuarios-para-busqueda', [
              'id' => 'usuario',
              'class' => 'col-12 col-md-12 mb-3',
              'label' => 'Seleccione el usuario',
              'tipoBuscador' => 'unico',
              'queUsuariosCargar' => $queUsuariosCargar,
              'conDadosDeBaja' => 'no',
              'modulo' => 'exclusiones-grupo'
            ])

            <div class="col-12 text-center">
              <button type="submit" class="btn btn-primary me-sm-3 me-1">Guardar</button>
              <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!--/ Modal add nueva exclusion  -->

@endsection
