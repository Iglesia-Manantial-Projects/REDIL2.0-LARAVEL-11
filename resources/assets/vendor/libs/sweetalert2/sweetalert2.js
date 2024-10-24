import SwalPlugin from 'sweetalert2/dist/sweetalert2';
alert('entrar');
const Swal = SwalPlugin.mixin({
  buttonsStyling: false,
  customClass: {
    confirmButton: 'btn btn-primary',
    cancelButton: 'btn btn-label-danger',
    denyButton: 'btn btn-label-secondary'
  }
});

try {
  window.Swal = Swal;
} catch (e) {}

export { Swal };
