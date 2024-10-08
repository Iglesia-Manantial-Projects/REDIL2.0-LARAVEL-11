$('.fecha-picker').flatpickr({
  dateFormat: 'Y-m-d'
});

$(document).ready(function () {
  $('.select2').select2({
    width: '100px',
    allowClear: true,
    placeholder: 'Ninguno'
  });
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

window.addEventListener('bloquedoBtnGuardar', event => {
  $('.btnGuardar').attr('disabled', 'disabled');
});

window.addEventListener('desbloquedoBtnGuardar', event => {
  $('.btnGuardar').removeAttr('disabled');
});

$('.selectorGenero').on('change', function (event) {
  if ($('#imagen-recortada').val() == '') {
    if ($(this).val() == 1)
      $('#preview-foto').attr(
        'src',
        "{{$configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/img/foto-usuario/default-f.png') : $configuracion->ruta_almacenamiento.'/img/foto-usuario/default-f.png' }}"
      );
    else
      $('#preview-foto').attr(
        'src',
        "{{$configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/img/foto-usuario/default-m.png') : $configuracion->ruta_almacenamiento.'/img/foto-usuario/default-m.png' }}"
      );
  }
});

$('#tienesUnaPeticion').change(function () {
  if (this.checked) {
    $('#divSelectTipoPeticion').removeClass('d-none');
    $('#divDescripcionPeticion').removeClass('d-none');
    $('#descripcion_peticion').prop('required', true);
    $('#tipo_peticion').prop('required', true);
  } else {
    $('#divSelectTipoPeticion').addClass('d-none');
    $('#divDescripcionPeticion').addClass('d-none');

    $('#descripcion_peticion').val('');
    $('#descripcion_peticion').removeAttr('required');

    $('#tipo_peticion').val('');
    $('#tipo_peticion').removeAttr('required');
  }
});

function sinComillas(e) {
  tecla = document.all ? e.keyCode : e.which;
  patron = /[\x5C'"]/;
  te = String.fromCharCode(tecla);
  return !patron.test(te);
}

$('#formulario').submit(function () {
  $('.btnGuardar').attr('disabled', 'disabled');

  Swal.fire({
    title: 'Espera un momento',
    text: 'Ya estamos guardando...',
    icon: 'info',
    showCancelButton: false,
    showConfirmButton: false,
    showDenyButton: false
  });
});

$('#identificacion').keyup(function () {


   clearTimeout($.data(this, 'timer'));
   if($("#identificacion").val()!='')
   {
     @if($configuracion->correo_por_defecto==TRUE && $formulario->visible_email==TRUE)
     if ($("#email").val() == '')
     {
       $("#email").val($("#identificacion").val()+"@cambiaestecorreo.com");
     }else if($("#email").val().indexOf('cambiaestecorreo.com') != -1)
     {
       $("#email").val($("#identificacion").val()+"@cambiaestecorreo.com");
     }
     @endif
   }
});
