<div>       <!-- Editar rol-->
  <div wire:ignore.self class="modal fade" id="modalActualizarPariente"  tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-body">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3 class="mb-2"><i class="ti ti-edit "></i> Editar Relación</h3>
            <p class="text-muted">Desde aquí podrás corregir tu relación con el pariente seleccionado. </p>
          </div>
          <form wire:submit="updateParentesco" class="row g-3">
            @csrf
            <!--/ Familiar principal -->
            <div class="col-lg-12 col-md-12  col-sm-12  mt-3">
                <div class="mb-3">
                  <label class="form-label">¿Qué relación tiene {{$actualizarTipoParentesco}} <b>{{$nombreUsuario}}</b> con el pariente?</label>
                  <select wire:model="actualizarTipoParentesco" id="tipoParentesco" name="tipoParentesco" class="form-select" tabindex="0" id="roleEx7">
                    <option value="0">Selecciona una opción </option>
                        @foreach($tiposParentesco as $tipo)
                              <option @if( $actualizarTipoParentesco == $tipo->id)selected @endif value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                      @endforeach
                  </select>
                </div>
            </div>

            <div class="col-lg-12 col-md-12  col-sm-12  mt-3">
              <div class="mb-3">
                        <label class="form-label">Responsabilidad {{$actualizarResponsabilidad}}</label>
                        <select wire:model="actualizarResponsabilidad" id="responsabilidad" name="responsabilidad" class="form-select" tabindex="0" id="roleEx7">
                          <option @if( $actualizarResponsabilidad == 1)selected @endif value="1">Ninguna</option>
                          <option @if( $actualizarResponsabilidad == 2)selected @endif value="2"><b>{{$nombreUsuario}}</b> es el responsable del pariente</option>
                           <option @if( $actualizarResponsabilidad == 3)selected @endif value="3"> El pariente es el responsable de <b>{{$nombreUsuario}} </b></option>
                          </select>
              </div>
            </div>

            <div class="col-12 text-center">
              <button type="submit" class="btn btn-primary me-sm-3 me-1">Guardar</button>
              <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!--/ Editar rol -->
</div>

@script
<script>
 $wire.on('abrirModal', () => {
    $('#' + event.detail.nombreModal).modal('show');
  });
</script>
@endscript
