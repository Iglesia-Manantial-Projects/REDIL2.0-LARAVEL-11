@extends('layouts/layoutMaster')

@section('title', 'Tema - Nuevo')

@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/quill/typography.scss',
    'resources/assets/vendor/libs/quill/editor.scss'
  ])
@endsection

@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/quill/quill.js'
  ])
@endsection

@section('page-script')
<script type="module">
  const editor = new Quill('#editor', {
    bounds: '#editor',
    placeholder: 'Escribe aquí la respuesta de la persona',
    modules: {
    },
    theme: 'bubble'
  });

  editor.root.innerHTML = '{!! $tema->contenido !!}';
  editor.editor.enable(false);
</script>
@endsection

@section('content')

  @include('layouts.status-msn')

  <div class="card h-100">
    <img class="card-img-top" src="{{ $configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/temas/archivos/'.$tema->portada) : ''}}" alt="Card image cap" />
    <div class="card-body">
      <div class="d-flex align-items-start">
        <div class="d-flex align-items-start">
          <h3 class="card-header fw-bold text-uppercase p-0 pb-1">{{ $tema->titulo}}</h3>
        </div>
        <div class="ms-auto">
          @if($rolActivo->hasPermissionTo('temas.editar_tema'))
            <div class="dropdown zindex-2 border rounded p-1">
            <button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical text-muted"></i></button>
              <ul class="dropdown-menu dropdown-menu-end">
                @if($rolActivo->hasPermissionTo('temas.editar_tema'))
                <li>
                  <a class="dropdown-item" href="{{route('tema.actualizar', $tema)}}">
                    <span class="me-2">Editar tema</span>
                  </a>
                </li>
                @endif
                @if($rolActivo->hasPermissionTo('temas.eliminar_tema'))
                <hr class="dropdown-divider">
                  <li>
                  <a data-id="{{$tema->id}}"  data-nombre="{{$tema->titulo}}" class="dropdown-item text-danger waves-effect confirmacionEliminar" >
                    <span class="me-2">Eliminar tema</span>
                  </a>
                </li>
                @endif
              </ul>
            </div>
          @endif
        </div>
      </div>

      <div class="mb-5">
        @if($tema->categorias->count()> 0)
          @foreach($tema->categorias as $categoria)
            <span class="badge rounded-pill bg-label-primary mb-1">{{$categoria->nombre}}</span>
          @endforeach
        @else
            <span class="badge rounded-pill bg-label-secondary mb-1">Sin categoria </span>
        @endif
      </div>


      <div id="editor">
      </div>

    </div>
  </div>



@endsection
