<div class="row g-2">
<div class="col-12 offset-md-3 col-md-6 mb-4">
        <div class="input-group input-group-merge ">
          <span class="input-group-text" id="basic-addon-search31"><i class="ti ti-search"></i></span>
          <input wire:model.live.debounce.30ms="busqueda" type="text" class="form-control" placeholder="Buscar integrante por nombre, email, identificación" aria-label="Buscar grupo" aria-describedby="basic-addon-search31" spellcheck="false" data-ms-editor="true">
        </div>
      </div>
    @if($integrantes->count() > 0)

      @foreach($integrantes as $integrante)
        <div class="col-12 col-md-4">
          <div class="p-2 border rounded">
            <div class="d-flex align-items-start">
              <div class="d-flex align-items-start">
                <div class="avatar avatar-md me-2 my-auto">
                  <img src="{{ $configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/img/foto-usuario/'.$integrante->foto) : $configuracion->ruta_almacenamiento.'/img/foto-usuario/'.$integrante->foto }}" alt="Avatar" class="rounded-circle" />
                </div>
                <div class="me-2 ms-1">
                  <h6 class="mb-0">{{ $integrante->nombre(3) }}</h6>
                  <small class="text-muted"><i class="ti {{ $integrante->tipoUsuario->icono }} text-heading fs-6"></i> {{ $integrante->tipoUsuario->nombre}}</small>

                  <div class="mt-2">
                    @if($integrante->tipoUsuario->seguimiento_actividad_grupo==FALSE)
                      <span class="badge bg-label-secondary">No seguimiento grupos</span>
                    @else
                      @if($integrante->estadoActividadGrupos())
                      <span class="badge bg-label-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Último reporte {{$integrante->ultimo_reporte_grupo}}">Activo grupo</span>
                      @else
                      <span class="badge bg-label-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Último reporte {{$integrante->ultimo_reporte_grupo}}">Inactivo grupo</span>
                      @endif
                    @endif

                    @if($integrante->tipoUsuario->seguimiento_actividad_reunion==FALSE)
                      <span class="badge bg-label-secondary">No seguimiento en reuniónes</span>
                    @else
                      @if($integrante->estadoActividadReuniones())
                      <span class="badge bg-label-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Último reporte {{$integrante->ultimo_reporte_reunion}}">Activo reuniones</span>
                      @else
                      <span class="badge bg-label-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Último reporte {{$integrante->ultimo_reporte_reunion}}">Inactivo reuniones</span>
                      @endif
                    @endif
                  </div>

                </div>
              </div>

              <div class="ms-auto pt-1 my-auto">
                @if($rolActivo->hasPermissionTo('personas.lista_asistentes_todos'))
                <a href="{{ route('usuario.perfil', $integrante) }}" target="_blank" class="text-body" data-bs-toggle="tooltip" aria-label="Ver perfil" data-bs-original-title="Ver perfil">
                  <i class="ti ti-user-check me-2 ti-sm"></i></a>
                </a>
                @endif
              </div>
            </div>
          </div>
        </div>
        @endforeach
      @elseif($busqueda == '')
        <div class="py-4 border rounded mt-2">
          <center>
            <i class="ti ti-users ti-xl pb-1"></i>
            <h6 class="text-center">¡Ups! este grupo no posee integrantes.</h6>
            @if($rolActivo->hasPermissionTo('grupos.pestana_anadir_integrantes_grupo'))
              <a href="{{ route('grupo.gestionarIntegrantes',$grupo) }}" target="_blank" class="btn btn-primary pendiente" data-bs-toggle="tooltip" aria-label="Gestionar integrantes" data-bs-original-title="Este grupo no tiene integrantes, agrégalos aquí">
                <i class="ti ti-user-plus me-2 ti-sm"></i> Gestionar integrantes
              </a>
            @endif
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
