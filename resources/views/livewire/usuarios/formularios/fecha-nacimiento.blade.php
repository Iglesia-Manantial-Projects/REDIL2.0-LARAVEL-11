<!-- fecha nacimiento -->
  <div class="mb-3 {{$formulario->class_fecha_nacimiento}} {{$formulario->visible_fecha_nacimiento ? '':'d-none' }}">
    @if($formulario->visible_fecha_nacimiento)
    <label for="fecha_nacimiento" class="form-label">
      @if($formulario->obligatorio_fecha_nacimiento)<span class="badge badge-dot bg-info me-1"></span>@endif
      @if($formulario->label_fecha_nacimiento!="")
      {{$formulario->label_fecha_nacimiento}}
      @else
      Fecha de nacimiento
      @endif
    </label>
    <input wire:click='bloquearBtnGuardar' wire:model.debounce="fecha" wire:click.outside="validarFecha()" id="fecha_nacimiento" value="{{ old('fecha_nacimiento', $fechaDefault) }}" placeholder="YYYY-MM-DD" name="fecha_nacimiento" class="fecha_nacimiento form-control fecha-picker" type="text" />
    @endif
  </div>
<!-- fecha nacimiento -->
