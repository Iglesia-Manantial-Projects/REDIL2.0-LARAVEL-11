<?php

namespace App\Livewire\Usuarios\Formularios;

use Carbon\Carbon;
use Livewire\Component;

class FechaNacimiento extends Component
{
  public $formulario;
  public $usuario;
  public $fechaDefault;
  public $respuesta = '';
  public $fecha = '';

  public function mount()
  {
    $this->fecha = $this->fechaDefault;

    if(old('fecha_nacimiento')!='')
    $this->fecha = old('fecha_nacimiento');
  }

  public function validarFecha()
  {
    if($this->formulario->validar_edad)
    {
      $edad = Carbon::parse($this->fecha)->age;
      if($this->fecha)
      {
        if($edad < $this->formulario->edad_minima || $edad > $this->formulario->edad_maxima){
          $this->fecha = '';
          $this->dispatch(
            'msn',
            msnIcono: 'info',
            msnTitulo: '¡Ups!',
            msnTexto:  $this->formulario->edad_mensaje_error
          );
        }else{
          $this->dispatch('desbloqueoBtnGuardar');
        }
      }
    }
  }

  public function bloquearBtnGuardar(){
    $this->dispatch('bloqueoBtnGuardar');
  }

  public function render()
  {
      return view('livewire.usuarios.formularios.fecha-nacimiento');
  }
}
