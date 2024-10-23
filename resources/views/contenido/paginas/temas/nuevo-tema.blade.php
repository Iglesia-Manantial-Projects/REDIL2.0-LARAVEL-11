@extends('layouts/layoutMaster')

@section('title', 'Tema - Nuevo')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
  @vite([
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
    'resources/assets/vendor/libs/select2/select2.scss',
    'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
    'resources/assets/vendor/libs/quill/typography.scss',
    'resources/assets/vendor/libs/quill/editor.scss'
  ])
@endsection

@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
    'resources/assets/vendor/libs/select2/select2.js',
    'resources/assets/vendor/libs/flatpickr/flatpickr.js',
    'resources/assets/vendor/libs/quill/quill.js'
  ])
@endsection

@section('page-script')
<script type="module">
  const editor = new Quill('#editor', {
    bounds: '#editor',
    placeholder: 'Escribe aquí la respuesta de la persona',
    modules: {
      toolbar: [
        ['bold', 'italic', 'underline', 'strike'],
        [{ 'header': 1 }, { 'header': 2 }],
        [{ 'color': [] }, { 'background': [] }],
        [{ 'align': [] }],
        [{ 'size': ['small', false, 'large', 'huge'] }],
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
        [{ 'font': [] }],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
        [{ 'indent': '-1'}, { 'indent': '+1' }],
        ['link', 'image', 'video'],
        ['clean']
      ]
    },
    theme: 'snow'
  });

  editor.on('text-change', (delta, oldDelta, source) => {
    $('#contenidoEditor').val(editor.root.innerHTML);
  });
</script>

<script>
  $(document).ready(function()
  {
    $('.select2').select2({
      dropdownParent: $('#formulario')
    });
  });

  $(".clearAllItems").click(function() {
    value = $(this).data('select');
    $('#' + value).val(null).trigger('change');
  });

  $(".selectAllItems").click(function() {
    value = $(this).data('select');
    $("#" + value + " > option").prop("selected", true);
    $("#" + value).trigger("change");
  });
</script>
@endsection

@section('content')
<h4 class="mb-1">Nuevo tema</h4>
<p class="mb-4">Descripción...</p>

@include('layouts.status-msn')
  <form id="formulario" role="form" class="forms-sample" method="POST" action="{{ route('tema.crear') }}"  enctype="multipart/form-data" >
    @csrf

    <!-- botonera -->
    <div class="d-flex mb-1 mt-5">
      <div class="me-auto">
        <button type="submit" class="btn btn-primary me-1 btnGuardar">Guardar</button>
        <a type="reset" href="{{ url()->previous() }}" class="btn btn-label-secondary">Cancelar</a>
      </div>
      <div class="p-2 bd-highlight">
        <p class="text-muted"><span class="badge badge-dot bg-info me-1"></span> Campos obligatorios</p>
      </div>
    </div>
    <!-- /botonera -->

    <div class="p-4 m-0 card">
      <div class="row d-flex card-body">

        <!-- Nombre -->
        <div class="col-md-12 mb-2 px-2 ">
          <label class="form-label" for="nombre_tema">
          <span class="badge badge-dot bg-info me-1"></span>
            Nombre del tema
          </label>
          <input id="nombre_tema" name="nombre_del_tema" value="{{ old('nombre_del_tema') }}"  type="text" class="form-control"/>
          @if($errors->has('nombre_del_tema')) <div class="text-danger form-label">{{ $errors->first('nombre_del_tema') }}</div> @endif
          <div class="text-danger form-label"></div>
        </div>
        <!-- /Nombre -->

        <!-- Portada -->
        <div class="col-md-6 mb-2 px-2 ">
            <div class="mb-2">
              <label id="label_portada" class="form-label" for="portada">
                Portada <span style="font-size:10px">(Tamaño recomendado 1600x700)</span>
              </label>

              <input type="file" id="portada" name="portada"  class="form-control" accept=".jpg, .png, .jpeg">
              @if($errors->has('archivo_a')) <div class="text-danger form-label">{{ $errors->first('portada') }}</div> @endif
            </div>
        </div>
        <!--/ Portada -->

        <!-- URL ENLACE -->
        <div class="col-md-6 mb-2 px-2 ">
          <label class="form-label" for="url_externo">
            Url de enlace externo
          </label>
          <input id="url_externo" name="url_externo"  type="text" class="form-control"/>
        </div>
        <!-- /URL ENLACE  -->

        <!--  Categoria -->
        <div class="col-md-12 mb-2 px-2">
          <label for="filtroPorCategoria" class="form-label">¿A que categorías pertenece?</label>
          <select id="categorias" name="categorias[]" class="select2 form-select" multiple>
            @foreach($categorias as $categoria)
            <option value="{{ $categoria->id }}" >{{ $categoria->nombre }}</option>
            @endforeach
          </select>
        </div>
        <!-- / Categoria  -->

        <!-- Editor -->
        <div class="col-12 mb-2 px-2">
          <label for="filtroPorCategoria" class="form-label">Contenido del tema</label>

          <div id="editor"></div>
          <input id="contenidoEditor" name="contenidoEditor" class='d-none'>
        </div>
        <!-- /Editor -->



        <!--  Tipo Usuario -->
        <div class="col-md-12 mb-2 px-2">
          <label for="filtroPorTipoUsuarios" class="form-label">¿Qué tipos de usuarios pueden ver el tema?</label>
          <select id="tipoUsuarios" name="tipoUsuarios[]" class="select2 form-select" multiple>
            @foreach($tiposUsuarios as $tipoUsuario)
            <option value="{{ $tipoUsuario->id }}" >{{ $tipoUsuario->nombre }}</option>
            @endforeach
          </select>
        </div>
        <!-- / Tipo Usuario -->

        <!--  Sede -->
        <div class="col-md-12 mb-2 px-2">
          <label for="filtroPorSede" class="form-label">¿Qué sedes pueden ver el tema?</label>
          <select id="sedes" name="sedes[]" class="select2 form-select" multiple>
            @foreach($sedes as $sede)
            <option value="{{ $sede->id }}" >{{ $sede->nombre }}</option>
            @endforeach
          </select>
        </div>
        <!-- / Sede  -->

        <!--  Tipo Grupo -->
        <div class="col-md-12 mb-2 px-2">
          <label for="filtroPorTipoGrupo" class="form-label">¿Qué tipos de grupo pueden ver?</label>
          <select id="tipoGrupo" name="tipoGrupo[]" class="select2 form-select" multiple>
            @foreach($tiposGrupo as $tipo)
            <option value="{{ $tipo->id }}" >{{ $tipo->nombre }}</option>
            @endforeach
          </select>
        </div>
        <!-- / Tipo Grupo  -->

        <!--  Grupos -->
        <div class="col-md-12 mb-2 px-2">
          @livewire('Grupos.grupos-para-busqueda', [
          'id' => 'inputGruposIds',
          'class' => 'col-12 col-md-12 mb-3',
          'label' => '¿Qué grupos pueden ver el tema?',
          'conDadosDeBaja' => 'no',
          'multiple' => TRUE,
          ])
        </div>
        <!-- / Grupos  -->
      </div>
    </div>

    <!-- botonera -->
    <div class="d-flex mb-1 mt-5">
      <div class="me-auto">
        <button type="submit" class="btn btn-primary me-1 btnGuardar">Guardar</button>
        <a type="reset" href="{{ url()->previous() }}" class="btn btn-label-secondary">Cancelar</a>
      </div>
      <div class="p-2 bd-highlight">
        <p class="text-muted"><span class="badge badge-dot bg-info me-1"></span> Campos obligatorios</p>
      </div>
    </div>
    <!-- /botonera -->

</form>
@endsection
