<div class="row g-2">
  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between">
      <p class="card-text text-uppercase fw-bold"><i class="ti ti-alert-square-rounded ms-n1 me-2"></i>Exclusiones</p>
    </div>
    <div class="card-body pb-20 row g-2">
      <div class="col-12 offset-md-3 col-md-6 mb-4">
        <div class="input-group input-group-merge ">
          <span class="input-group-text" id="basic-addon-search31"><i class="ti ti-search"></i></span>
          <input wire:model.live.debounce.30ms="busqueda" type="text" class="form-control" placeholder="Buscar integrante por nombre" aria-label="Buscar grupo" aria-describedby="basic-addon-search31" spellcheck="false" data-ms-editor="true">
        </div>
      </div>
      @if($exclusiones->count() > 0)
        @foreach($exclusiones as $exclusion)
          <div class="col-12 col-md-4">
            <div class="card p-3 border rounded">
              <div class="d-flex align-items-start">
                <div class="d-flex align-items-start">
                  <div class="avatar avatar-md me-2 my-auto">
                    <img src="{{ $configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/img/foto-usuario/'.$exclusion->foto) : $configuracion->ruta_almacenamiento.'/img/foto-usuario/'.$exclusion->foto }}" alt="Avatar" class="rounded-circle" />
                  </div>
                  <div class="me-2 ms-1">
                    <h6 class="mb-0">{{ $exclusion->nombre(3) }}</h6>
                    <small class="text-muted"><i class="ti {{ $exclusion->tipoUsuario->icono }} text-heading fs-6"></i> {{ $exclusion->tipoUsuario->nombre}}</small>

                    <div class="mt-2">
                      <h6 class="mb-0">Excluido del grupo: </h6>
                      <span class="badge bg-label-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Último reporte {{$exclusion->nombreGrupo}}">{{ $exclusion->nombreGrupo }}</span>

                    </div>

                  </div>
                </div>

                <div class="ms-auto pt-1 my-auto">
                  @if($rolActivo->hasPermissionTo('personas.lista_asistentes_todos'))
                  <a class="dropdown-item" href="#"  wire:click="eliminar({{$exclusion->id}})">
                    <i class="ti ti-trash m-1 ti-sm"></i></a>
                  @endif
                </div>
              </div>
            </div>
          </div>
        @endforeach
      @elseif($busqueda == '')
        <div class="py-4 border rounded mt-2">
          <center>
            <i class="ti ti-alert-square-rounded ti-xl pb-1"></i>
            <h6 class="text-center">¡Ups! No hay exclusiones creadas.</h6>
          </center>
        </div>
      @else
        <div class="py-4 border rounded mt-2">
          <center>
            <i class="ti ti-search ti-xl pb-1"></i>
            <h6 class="text-center">¡Ups! la busqueda no arrojo ningun resultado.</h6>
          </center>
        </div>
      @endif
    </div>
  </div>
</div>

