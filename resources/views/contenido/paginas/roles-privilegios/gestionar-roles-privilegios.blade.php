@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Inicio')


@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/app-access-roles.js')}}"></script>
<script src="{{asset('assets/js/modal-add-role.js')}}"></script>

<script>
    window.addEventListener('cerrarModal', event => {
      $('#'+event.detail.nombreModal).modal('hide');
    });

    window.addEventListener('abrirModal', event => {
      $('#'+event.detail.nombreModal).modal('show');
    });

    $(document).on('change','.actualizarPermiso', function(){
      let rolId = $(this).data('rol');
      let permisoId = $(this).data('permiso');
      Livewire.dispatch('updatePermiso', { rolId: rolId, permisoId: permisoId });
    });

    window.addEventListener('msn', event => {
      Swal.fire({
        title: event.detail.msnTitulo,
        text: event.detail.msnTexto,
        icon: event.detail.msnIcono,
        customClass: {
          confirmButton: 'btn btn-primary'
        },
        buttonsStyling: false
      });
    });
</script>

<script>
  Livewire.on('eliminar', rolId => {

    Swal.fire({
      title: '¿Deseas eliminar este rol?',
      text: "Esta acción no es reversible.",
      icon: 'warning',
      showCancelButton: false,
      confirmButtonText: 'Si, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        Livewire.dispatchTo('roles-privilegios.gestionar-roles-privilegios','eliminarRol', { rolId: rolId });

        Swal.fire(
          '¡Eliminado!',
          'El rol fue eliminado correctamente.',
          'success'
        )
      }
    })
  });
</script>
@endsection

@section('content')
<h4 class="mb-1">Gestionar roles</h4>
<p class="mb-4">Administra los roles con sus privilegios.</p>

@livewire('RolesPrivilegios.gestionar-roles-privilegios')

@endsection
