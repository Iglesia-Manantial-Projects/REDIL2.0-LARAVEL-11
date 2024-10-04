<div>

  <!-- Role cards -->
    <div class="row g-4">
      <div class="d-flex flex-row-reverse">
        <button data-bs-target="#addRoleModal" wire:click="abrirFormularioAddRol" class="btn btn-primary mb-2 text-nowrap add-new-role waves-effect waves-light"><i class="ti ti-plus"></i> Nuevo rol </button>
      </div>
      <div class="col-xl-6 offset-xl-3 col-12">
        <div class="input-group">
           <input wire:model.live.debounce.500ms="busqueda" type="text" class="form-control" id="busqueda" name="busqueda" placeholder="Buscar">
        </div>
      </div>
    </div>
    <div class="row g-4 mt-2">

    @if($roles)
      @foreach( $roles as $rol )
      <div class="col-xl-4 col-lg-6 col-md-6">
        <div class="card">
          <div class="card-body">
            <i class="{{ $rol->icono }} ti-lg"></i>
            <h5 class="mb-1"> {{ $rol->name }}</h5>
            <div class="d-flex justify-content-between align-items-end mt-1">
              <div class="role-heading flex-fill">
                <p>Activo</p>
              </div>
              <div>
                <a href="javascript:void(0);" class="text-muted" wire:click="abrirFormularioActualizarPermisos({{ $rol->id }} , 'update')" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Actualizar permisos"><i class="ti ti-checkbox "></i></a>
                <a href="javascript:void(0);" class="text-muted" wire:click="abrirFormularioEditarRol({{ $rol->id }})"  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Editar rol"><i class="ti ti-edit "></i></a>
                <a href="javascript:void(0);" class="text-muted" wire:click="duplicarRol({{ $rol->id }})" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Duplicar rol"><i class="ti ti-copy "></i></a>
                <a href="javascript:void(0);" class="text-muted" wire:click="$dispatch('eliminar', {{ $rol->id }})" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Eliminar rol"><i class="ti ti-trash "></i></a>

              </div>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    @else
      No hay roles
    @endif
    </div>
  <!--/ Role cards -->

  <!-- Modal -->
  <!-- editar permisos -->
  <div  wire:ignore.self class="modal fade" id="editarPermisosAlRol" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-role">
      <div class="modal-content p-3 p-md-5">
        <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="modal-body">
          <div class="text-center mb-4">
            <h3 class="role-title mb-2"><i class="{{ $iconoModalPermisos }} ti-lg"></i> {{$tituloModalPermisos}}</h3>
            <p class="text-muted">Los cambios se guardan de manera automatica</p>
          </div>
          <!-- Add role form -->
          <form id="addForm" class="row g-3" onsubmit="return false">
            <div class="d-flex justify-content-start flex-column">

            <div class="alert alert-success {{ $msnModalPermisos ?  : 'd-none' }}" role="alert">
             {!!  $msnModalPermisos !!}
            </div>
              @foreach( json_decode($checkboxes) as $checkbox)
                <label class="text-nowrap fw-bold mb-2 mt-3"> {{ $checkbox->bloque->nombre }}
                  <i class="ti ti-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Allows a full access to the system"></i>
                </label>
                <div class="row">
                @foreach( $checkbox->permisos as $permiso)
                  <div class="col-6 form-check">
                    <input wire:model="arrayPermisosRol" value="{{ $permiso->name }}" class="form-check-input actualizarPermiso" type="checkbox" id="{{$permiso->titulo}}" data-permiso="{{$permiso->id}}" data-rol="{{$idRolUpdatePermisos}}" />
                    <label class="form-check-label" for="{{ $permiso->titulo }}">{{ str_replace('_', ' ', $permiso->titulo)}}</label>
                  </div>
                @endforeach
                </div>
              @endforeach
            </div>
          </form>
          <!--/ Add role form -->
        </div>
      </div>
    </div>
  </div>
  <!--/ editar permisos -->

  <!-- Add rol-->
  <div wire:ignore.self class="modal fade" id="addRol" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-body">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3 class="mb-2"><i class="ti ti-plus ti-lg"></i> Nuevo rol</h3>
            <p class="text-muted">Los campos con <span class="badge badge-dot bg-info me-1"></span> son obligatorios</p>
          </div>
          <form wire:submit="nuevoRol" class="row g-3">

            <div class="col-12 col-md-6">
              <label class="form-label" for="nombreRol"><span class="badge badge-dot bg-info me-1"></span> Nombre @error('nombreRol') <span class="error">{{ $message }}</span> @enderror </label>
              <input type="text" wire:model="nombreRol" id="nombreRol" name="nombre" class="form-control" placeholder="Nombre de rol" />
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label" for="iconoRol">Icono @error('iconoRol') <span class="error">{{ $message }}</span> @enderror </label>
              <input type="text" wire:model="iconoRol" id="iconoRol" name="icono" class="form-control" placeholder="Icono" />
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label" for="lista_asistentes_sede_id">Lista asistentes sede id  @error('lista_asistentes_sede_id') <span class="error">{{ $message }}</span> @enderror </label>
              <input type="number" wire:model="lista_asistentes_sede_id" id="lista_asistentes_sede_id" name="lista_asistentes_sede_id" class="form-control" placeholder="Lista asistente sede id" />
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label" for="lista_grupos_sede_id">Lista grupos sede id  @error('lista_grupos_sede_id') <span class="error">{{ $message }}</span> @enderror </label>
              <input type="number" wire:model="lista_grupos_sede_id" id="lista_grupos_sede_id" name="lista_grupos_sede_id" class="form-control" placeholder="Lista grupos sede id" />
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label" for="lista_reportes_grupo_sede_id">lista reportes grupo sede id  @error('lista_reportes_grupo_sede_id') <span class="error">{{ $message }}</span> @enderror </label>
              <input type="number" wire:model="lista_reportes_grupo_sede_id" id="lista_reportes_grupo_sede_id" name="lista_reportes_grupo_sede_id" class="form-control" placeholder="lista reportes grupo sede id" />
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label" for="lista_reuniones_sede_id">Lista reuniones sede id  @error('lista_reuniones_sede_id') <span class="error">{{ $message }}</span> @enderror </label>
              <input type="number" wire:model="lista_reuniones_sede_id" id="lista_reuniones_sede_id" name="lista_reuniones_sede_id" class="form-control" placeholder="Lista reuniones sede id" />
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label" for="lista_sedes_sede_id">Lista sedes sede id  @error('lista_sedes_sede_id') <span class="error">{{ $message }}</span> @enderror </label>
              <input type="number" wire:model="lista_sedes_sede_id" id="lista_sedes_sede_id" name="lista_sedes_sede_id" class="form-control" placeholder="Lista sedes sede id" />
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label" for="lista_ingresos_sede_id">Lista ingresos sede id  @error('lista_ingresos_sede_id') <span class="error">{{ $message }}</span> @enderror </label>
              <input type="number" wire:model="lista_ingresos_sede_id" id="lista_ingresos_sede_id" name="lista_ingresos_sede_id" class="form-control" placeholder="Lista ingresos sede id" />
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label" for="lista_peticiones_sede_id">Lista peticiones sede id  @error('lista_peticiones_sede_id') <span class="error">{{ $message }}</span> @enderror </label>
              <input type="number" wire:model="lista_peticiones_sede_id" id="lista_peticiones_sede_id" name="lista_peticiones_sede_id" class="form-control" placeholder="Lista peticiones sede id" />
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label" for="ver_sumatoria_ingresos_reportes_grupo_id">Ver sumatoria ingresos reportes grupo id  @error('ver_sumatoria_ingresos_reportes_grupo_id') <span class="error">{{ $message }}</span> @enderror </label>
              <input type="number" wire:model="ver_sumatoria_ingresos_reportes_grupo_id" id="ver_sumatoria_ingresos_reportes_grupo_id" name="ver_sumatoria_ingresos_reportes_grupo_id" class="form-control" placeholder="Ver sumatoria ingresos reportes grupo id" />
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
  <!--/ Add rol -->

  <!-- Editar rol-->
  <div wire:ignore.self class="modal fade" id="editarRol" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-body">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3 class="mb-2"><i class="ti ti-edit "></i> Editar rol</h3>
            <p class="text-muted">Los campos con <span class="badge badge-dot bg-info me-1"></span> son obligatorios</p>
          </div>
          <form wire:submit="editarRol({{ $idRol }})" class="row g-3">
            @csrf
            <div class="col-12 col-md-6">
              <label class="form-label" for="nombreRol"><span class="badge badge-dot bg-info me-1"></span> Nombre @error('nombreRol') <span class="error">{{ $message }}</span> @enderror </label>
              <input type="text" wire:model="nombreRol" id="nombreRol" name="nombre" class="form-control" placeholder="Nombre de rol" />
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label" for="iconoRol">Icono @error('iconoRol') <span class="error">{{ $message }}</span> @enderror </label>
              <input type="text" wire:model="iconoRol" id="iconoRol" name="icono" class="form-control" placeholder="Icono" />
            </div>


            <div class="col-12 col-md-6">
              <label class="form-label" for="lista_asistentes_sede_id">Lista asistentes sede id  @error('lista_asistentes_sede_id') <span class="error">{{ $message }}</span> @enderror </label>
              <input type="number" wire:model="lista_asistentes_sede_id" id="lista_asistentes_sede_id" name="lista_asistentes_sede_id" class="form-control" placeholder="Lista asistente sede id" />
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label" for="lista_grupos_sede_id">Lista grupos sede id  @error('lista_grupos_sede_id') <span class="error">{{ $message }}</span> @enderror </label>
              <input type="number" wire:model="lista_grupos_sede_id" id="lista_grupos_sede_id" name="lista_grupos_sede_id" class="form-control" placeholder="Lista grupos sede id" />
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label" for="lista_reportes_grupo_sede_id">lista reportes grupo sede id  @error('lista_reportes_grupo_sede_id') <span class="error">{{ $message }}</span> @enderror </label>
              <input type="number" wire:model="lista_reportes_grupo_sede_id" id="lista_reportes_grupo_sede_id" name="lista_reportes_grupo_sede_id" class="form-control" placeholder="lista reportes grupo sede id" />
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label" for="lista_reuniones_sede_id">Lista reuniones sede id  @error('lista_reuniones_sede_id') <span class="error">{{ $message }}</span> @enderror </label>
              <input type="number" wire:model="lista_reuniones_sede_id" id="lista_reuniones_sede_id" name="lista_reuniones_sede_id" class="form-control" placeholder="Lista reuniones sede id" />
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label" for="lista_sedes_sede_id">Lista sedes sede id  @error('lista_sedes_sede_id') <span class="error">{{ $message }}</span> @enderror </label>
              <input type="number" wire:model="lista_sedes_sede_id" id="lista_sedes_sede_id" name="lista_sedes_sede_id" class="form-control" placeholder="Lista sedes sede id" />
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label" for="lista_ingresos_sede_id">Lista ingresos sede id  @error('lista_ingresos_sede_id') <span class="error">{{ $message }}</span> @enderror </label>
              <input type="number" wire:model="lista_ingresos_sede_id" id="lista_ingresos_sede_id" name="lista_ingresos_sede_id" class="form-control" placeholder="Lista ingresos sede id" />
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label" for="lista_peticiones_sede_id">Lista peticiones sede id  @error('lista_peticiones_sede_id') <span class="error">{{ $message }}</span> @enderror </label>
              <input type="number" wire:model="lista_peticiones_sede_id" id="lista_peticiones_sede_id" name="lista_peticiones_sede_id" class="form-control" placeholder="Lista peticiones sede id" />
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label" for="ver_sumatoria_ingresos_reportes_grupo_id">Ver sumatoria ingresos reportes grupo id  @error('ver_sumatoria_ingresos_reportes_grupo_id') <span class="error">{{ $message }}</span> @enderror </label>
              <input type="number" wire:model="ver_sumatoria_ingresos_reportes_grupo_id" id="ver_sumatoria_ingresos_reportes_grupo_id" name="ver_sumatoria_ingresos_reportes_grupo_id" class="form-control" placeholder="Ver sumatoria ingresos reportes grupo id" />
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
