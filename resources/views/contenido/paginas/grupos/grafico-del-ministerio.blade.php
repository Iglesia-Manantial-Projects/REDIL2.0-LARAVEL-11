@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Gráfico del ministerio')

<!-- Page -->
@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>

<script src="{{asset('assets/vendor/libs/sigma/src/sigma.core.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sigma/src/alert.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sigma/src/conrad.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sigma/src/utils/sigma.utils.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sigma/src/sigma.settings.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sigma/src/classes/sigma.classes.dispatcher.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sigma/src/classes/sigma.classes.configurable.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sigma/src/classes/sigma.classes.graph.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sigma/src/classes/sigma.classes.camera.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sigma/src/classes/sigma.classes.quad.js')}}"></script>

<script src="{{asset('assets/vendor/libs/sigma/src/captors/sigma.captors.touch.js')}}"></script>

<script src="{{asset('assets/vendor/libs/sigma/src/renderers/canvas/sigma.canvas.hovers.def.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sigma/src/renderers/canvas/sigma.canvas.edges.def.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sigma/src/middlewares/sigma.middlewares.rescale.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sigma/src/misc/sigma.misc.drawHovers.js')}}"></script>

<script src="{{asset('assets/vendor/libs/sigma/src/misc/sigma.misc.animation.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sigma/src/misc/sigma.misc.bindDOMEvents.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sigma/src/shapes/shape-library.js')}}"></script>

<script src="{{asset('assets/vendor/libs/sigma/src/renderers/sigma.renderers.canvas.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sigma/src/renderers/canvas/sigma.canvas.labels.def.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sigma/src/misc/sigma.misc.bindEvents.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sigma/src/shapes/sigma.renderers.customShapes.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sigma/src/captors/sigma.captors.mouse.js')}}"></script>

@endsection

@section('page-script')

<script>
sigma.utils.pkg('sigma.canvas.nodes');

 var nodos = JSON.parse(<?php print json_encode(json_encode($nodos)); ?>);
 var aristas = JSON.parse(<?php print json_encode(json_encode($aristas)); ?>);

  //Esta
  var g = {
   nodes: nodos,
   edges: aristas
  };

  var s = new sigma({
    graph: g,
    settings: {
      minNodeSize: 10,
      maxNodeSize: 20,
    }
  });
  CustomShapes.init(s);

  s.addRenderer({
    id: 'main',
    type: 'canvas',
    container: document.getElementById('graph-container'),
    freeStyle: false
  });

  s.bind('clickNode', function(e) {
    var nodeId = e.data.node.id;
    window.top.location.href = '/grupo/grafico-del-ministerio/'+nodeId;
  });

  s.refresh();
</script>

<script type="text/javascript">
  $('.cargando').click(function(){
    Swal.fire({
      title: "Espera un momento",
      text: "Esto puede tardar un momento...",
      icon: "info",
      showCancelButton: false,
      showConfirmButton: false,
      showDenyButton: false
    });
  });
</script>

@endsection

@section('content')

  <h4 class="mb-1">GRÁFICO DE TU MINISTERIO</h4>
  <p class="mb-4">Este gráfico te permitirá visualizar un árbol con la estructura jerárquica de tu ministerio</p>


  @include('layouts.status-msn')
  <div class="row m-1" style="height: 800px">
    <div class="card h-100 w-100 bg-white">
        <div class="card-header align-items-center">

          @if($tipoDeNodo == 'U-principal')
          <p class="card-text mb-1 mt-3">
            <b>Ministerio general</b>
          </p>
          @endif

          @if($maximos_niveles !=20 )
          <p class="card-text mb-1">Actualmente, solo se están visualizando algunos niveles. Si deseas ver el árbol completo, da clic en el botón.</p>
          @else
          <p class="card-text mb-1">Gráfico del árbol completo.</p>
          @endif
          <div class="">
              @if($maximos_niveles!=20)
              <a  href="{{ route('grupo.graficoDelMinisterio', ['U-logueado', 20] ) }}" type="button" class="cargando my-1 btn btn-xs btn-outline-primary waves-effect">
                <span class="ti-xs ti ti-sitemap me-2"></span>Ver árbol completo
              </a>
              @endif
              @if($tipoDeNodo != 'U-principal')
              <button type="button" class="my-1 btn btn-xs btn-outline-primary waves-effect" data-bs-toggle="modal" data-bs-target="#modalCambiarIndice">
                <span class="ti-xs ti ti-transform me-2"></span>Cambiar índice
              </button>
              @endif

              <button type="button" class="my-1 btn btn-xs btn-outline-primary waves-effect" data-bs-toggle="modal" data-bs-target="#modalPersonasNoGraficadas">
                <span class="ti-xs ti ti-pencil-off me-2"></span>Personas no gráficadas
              </button>
          </div>

          @if($tipoDeNodo != 'U-principal')
          <p class="card-text mb-1 mt-3"> <b>Ministerio del {{ $tipoDeNodo == 'G-grupo' ? 'grupo' : ''}}: </b></p>
          @endif

          @if($tipoDeNodo == 'U-encargado' || $tipoDeNodo == 'A-encargado')
          <ul class="list-unstyled mb-0">
            <li class="mb-1 mt-1 p-2 border rounded">
              <div class="d-flex align-items-start">
                <div class="d-flex align-items-start">
                  <div class="avatar me-2 my-auto">
                    <img src="{{ $configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/img/foto-usuario/'.$usuario_seleccionado->foto) : $configuracion->ruta_almacenamiento.'/img/foto-usuario/'.$usuario_seleccionado->foto }}" alt="Avatar" class="rounded-circle" />
                  </div>
                  <div class="me-2 ms-1">
                    <h6 class="mb-0">{{$usuario_seleccionado->nombre(3)}}</h6>
                    <small class="text-muted"><i class="ti {{ $usuario_seleccionado->tipoUsuario->icono }} text-heading fs-6"></i> {{$usuario_seleccionado->tipoUsuario->nombre}}</small><br>
                    <small class="text-muted"><b>Índice en el gráfico:</b> {{ $usuario_seleccionado->indice_grafico_ministerial }}</small>
                  </div>
                </div>

                <div class="ms-auto pt-1 my-auto ">
                  @if($rolActivo->hasPermissionTo('personas.lista_asistentes_todos'))
                  <a href="{{ route('usuario.perfil', $usuario_seleccionado) }}" target="_blank" class="text-body" data-bs-toggle="tooltip" aria-label="Ver perfil" data-bs-original-title="Ver perfil">
                    <i class="ti ti-user-check me-2 ti-sm"></i></a>
                  </a>
                  @endif
                </div>

              </div>
            </li>
          </ul>
          @endif

          @if($tipoDeNodo == 'G-grupo')
          <ul class="list-unstyled mb-0">
            <li class="mb-1 mt-1 p-2 border rounded">
              <div class="d-flex align-items-start">
                <div class="d-flex align-items-start">
                  <div class="avatar me-2 my-auto">
                    <i class="ti ti-users-group me-2 fs-1"></i>
                  </div>
                  <div class="me-2 ms-1">
                    <h6 class="mb-0">{{ $grupo_seleccionado->nombre }}</h6>
                    <small class="text-muted"><b>Tipo:</b> {{ $grupo_seleccionado->tipoGrupo->nombre }}</small><br>
                    <small class="text-muted"><b>Índice en el gráfico:</b> {{ $grupo_seleccionado->indice_grafico_ministerial }}</small>
                  </div>
                </div>

                <div class="ms-auto pt-1 my-auto">
                  @if($rolActivo->hasPermissionTo('grupos.lista_grupos_todos'))
                  <a href="{{ route('grupo.perfil', $grupo_seleccionado) }}" target="_blank" class="text-body" data-bs-toggle="tooltip" aria-label="Ver perfil" data-bs-original-title="Ver perfil del grupo">
                    <i class="ti ti-id me-2 ti-sm"></i></a>
                  </a>
                  @endif
                </div>
              </div>
            </li>
          </ul>
          @endif


        </div>
        <div id="graph-container" class="card-body bg-gray"></div>
    </div>
  </div>

  <!-- modal Personas No Graficadas -->
  <div class="modal fade" id="modalPersonasNoGraficadas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-role">
      <div class="modal-content p-3 p-md-5">
        <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="modal-body">
          <div class="text-center mb-4">
            <h3 class="role-title mb-2"><i class="ti ti-pencil-off ti-lg"></i> Pesonas no gráficadas</h3>
            <p class="text-muted">A continuación presentamos las personas que se repiten, y por tanto no pueden ser dibujados en esta área del gráfico</p>
          </div>

          <div class="table-responsive text-nowrap">
            <table class="table">
              <thead>
                <tr>
                  <th>Personas</th>
                  <th>Grupos a los que pertenece</th>
                </tr>
              </thead>
              <tbody class="table-border-bottom-0">
              @foreach($usuarios_no_dibujados as $usuariNoDibujado)
                <tr>
                  <td>
                    <div class="d-flex align-items-start">
                      <div class="avatar me-2 my-auto">
                        <img src="{{ $configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/img/foto-usuario/'.$usuariNoDibujado->foto) : $configuracion->ruta_almacenamiento.'/img/foto-usuario/'.$usuariNoDibujado->foto }}" alt="Avatar" class="rounded-circle" />
                      </div>
                      <div class="me-2 ms-1">
                        <h6 class="mb-0">{{$usuariNoDibujado->nombre(3)}}</h6>
                        <small class="text-muted"><i class="ti {{ $usuariNoDibujado->tipoUsuario->icono }} text-heading fs-6"></i> {{$usuariNoDibujado->tipoUsuario->nombre}}</small><br>

                      </div>
                    </div>
                  </td>
                  <td class="p-0">
                    <ul class="list-unstyled mb-0">
                    @foreach($usuariNoDibujado->gruposDondeAsiste as $grupoDondeAsiste)
                      <li class="mt-1 p-1  rounded">
                        <div class="d-flex align-items-start">
                          <div class="d-flex align-items-start">
                            <div class="avatar me-2 my-auto">
                              <i class="ti ti-users-group me-2 fs-1"></i>
                            </div>
                            <div class="me-2 ms-1">
                              <h6 class="mb-0">{{ $grupoDondeAsiste->nombre }}</h6>
                              <small class="text-muted"><b>Tipo:</b> {{ $grupoDondeAsiste->tipoGrupo->nombre }}</small><br>
                              </div>
                          </div>

                          <div class="ms-auto pt-1 my-auto">
                            @if($rolActivo->hasPermissionTo('grupos.lista_grupos_todos'))
                            <a href="{{ route('grupo.perfil', $grupoDondeAsiste) }}" target="_blank" class="text-body" data-bs-toggle="tooltip" aria-label="Ver perfil" data-bs-original-title="Ver perfil del grupo">
                              <i class="ti ti-id me-2 ti-sm"></i></a>
                            </a>
                            @endif
                          </div>
                        </div>
                      </li>
                      @endforeach
                    </ul>
                  </td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ modal Personas No Graficadas -->

  <!-- modal cambiar indice -->
  @if($tipoDeNodo != 'U-principal')
  <div class="modal fade" id="modalCambiarIndice" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-role">
      <div class="modal-content p-3 p-md-5">
        <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="modal-body">
          <div class="text-center mb-4">
            @if($usuario_seleccionado)
            <h3 class="role-title mb-2"><i class="ti ti-transform ti-lg"></i> Cambiar índice de {{ $usuario_seleccionado->nombre(3) }}</h3>
            @elseif($grupo_seleccionado)
            <h3 class="role-title mb-2"><i class="ti ti-transform ti-lg"></i> Cambiar índice de {{ $grupo_seleccionado->nombre }} </h3>
            @endif
            <p class="text-muted">A continuación presentamos las personas que se repiten, y por tanto no pueden ser dibujados en esta área del gráfico</p>
          </div>
          <form method="POST" action="{{ $usuario_seleccionado ? route('grupo.cambiarIndice', ['usuario', $usuario_seleccionado->id] ) : ($grupo_seleccionado ? route('grupo.cambiarIndice', ['grupo', $grupo_seleccionado->id] ) : '' ) }}" class="row g-3">
          @csrf
          @method('PATCH')
            <div class="col-12 col-md-6 offset-md-3">
              <label class="form-label" for="cambioIndice"><span class="badge badge-dot bg-info me-1"></span> Cambiar indice </label>
              <input type="number" id="cambioIndice" name="cambioIndice" class="form-control" value="{{ $usuario_seleccionado ? $usuario_seleccionado->indice_grafico_ministerial : ($grupo_seleccionado ? $grupo_seleccionado->indice_grafico_ministerial : '' ) }}" placeholder="Cambio de indice" />
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
  @endif
  <!--/ modal cambiar indice -->


@endsection
