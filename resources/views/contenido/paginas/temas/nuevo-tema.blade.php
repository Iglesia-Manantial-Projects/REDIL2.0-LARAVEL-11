@extends('layouts/layoutMaster')

@section('title', 'Tema - Nuevo')

@section('vendor-style')

<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />


@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
@endsection

@section('page-script')
<!-- este meta es obligatorio para el tinymce que es el editor de texto -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<script src="{{asset('assets/js/form-basic-inputs.js')}}"></script>
<!-- este script es obligatorio para el tinymce que es el editor de texto -->
<script src="https://cdn.tiny.cloud/1/u0v8um3sg88k8eizaz5729j679xnspqr43nyokktksguekzn/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>


<!-- este script es obligatorio para subir las imagenes al servidor, las que estan dentro del editor-->
<script>


      $.ajaxSetup({
              beforeSend: function(xhr, type) {
                  if (!type.crossDomain) {
                      xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
                  }
              },
          });

      //esta funcion es un js para que se cree una petición por js al server para que suba la imagen y para que al concluir imprima dentro del editor el enlace img src de la imagen cargada
      const image_upload_handler = (blobInfo, progress) => new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        xhr.open('POST', 'cargar');
        var token = '{{ csrf_token() }}';
        xhr.setRequestHeader("X-CSRF-Token", token);
        xhr.upload.onprogress = (e) => {
          progress(e.loaded / e.total * 100);
        };
        /// esto es para comprobar si hay errores
        xhr.onload = () => {
          if (xhr.status === 403) {
            reject({ message: 'HTTP Error: ' + xhr.status, remove: true });
            return;
          }

          if (xhr.status < 200 || xhr.status >= 300) {
            reject('HTTP Error: ' + xhr.status);
            return;
          }
          // aqui despues de comprobar que no hay errores lo que hace es obtener el resultado de la consulta
          const json = JSON.parse(xhr.responseText);

          if (!json || typeof json.location != 'string') {
            reject('Invalid JSON: ' + xhr.responseText);
            return;
          }

          resolve(json.location); // aqui agrega el pedazo de codigo al editor
        };

        xhr.onerror = () => {
          reject('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
        };

        //esto es la funcion de la promise, que se envia al server para subir la imagen, esto funciona al reves porque primero se hace una carga de archivo temporal y luego si el del archivo final
        const formData = new FormData();
        formData.append('file', blobInfo.blob(), blobInfo.filename());

        xhr.send(formData);
      });

        /// aqui esta la configuración del editor inicialmente
          tinymce.init({
          selector: 'textarea#myeditorinstance', // Replace this CSS selector to match the placeholder element for TinyMCE
          plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount  ',
          toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table  | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight |',
          tinycomments_mode: 'embedded',
          tinycomments_author: 'Editor del tema',
          language: 'es',
          relative_urls: false,
          images_upload_handler:image_upload_handler,
          remove_script_host: false
              });

</script>

<script>
    // este script es para que el hacer unos arreglos al texto del editor
    $('.btnGuardar').on('click', function()
    {
            ///obtener el contenido primero
            const contenido = tinymce.activeEditor.getContent();
            //// esta funcion lo que hace es eliminar todos los espacios inncesarios o las inconsitencias en los cierres de etiquetas
            $('#contenidoEditor').val(contenido);
    });


    $(document).ready(function()
    {
        $('.select2BusquedaAvanzada').select2({
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


      <div class="row p-4 m-0 card">

          @csrf
          <div class="row d-flex card-body">


                      <!-- Nombre Categoria -->

                        <div class="col-md-6 mb-2 px-2 ">
                          <label class="form-label" for="nombre_tema">
                          <span class="badge badge-dot bg-info me-1"></span>
                            Nombre del tema
                          </label>
                          <input id="nombre_tema" name="nombre_del_tema"  type="text" class="form-control"/>
                          @if($errors->has('nombre_del_tema')) <div class="text-danger form-label">{{ $errors->first('nombre_del_tema') }}</div> @endif
                          <div class="text-danger form-label"></div>
                        </div>

                      <!-- /Nombre Categoria  -->

                       <!-- Portada -->
                       <div class="col-md-6 mb-2 px-2 ">
                            <div class="mb-2">
                              <label id="label_portada" class="form-label" for="portada">
                                 <span class="badge badge-dot bg-info me-1"></span>
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
                          <div class="text-danger form-label"></div>
                      </div>

                      <!-- /URL ENLACE  -->

                      <!--  Categoria -->
                        <div class="col-md-6 mb-2 px-2">
                                <label for="filtroPorCategoria" class="form-label">Categorías</label>
                                <select id="categorias" name="categorias[]" class="select2BusquedaAvanzada form-select" multiple>
                                  @foreach($categorias as $categoria)
                                  <option value="{{ $categoria->id }}" >{{ $categoria->nombre }}</option>
                                  @endforeach
                                </select>
                        </div>
                      <!-- / Categoria  -->

                       <!--  Tipo Usuario -->
                        <div class="col-md-4 mb-2 px-2">
                            <label for="filtroPorTipoUsuarios" class="form-label">Tipo de Usuarios</label>
                            <select id="tipoUsuarios" name="tipoUsuarios[]" class="select2BusquedaAvanzada form-select" multiple>
                              @foreach($tiposUsuarios as $tipoUsuario)
                              <option value="{{ $tipoUsuario->id }}" >{{ $tipoUsuario->nombre }}</option>
                              @endforeach
                            </select>
                        </div>
                      <!-- / Tipo Usuario  -->


                       <!--  Sede -->
                          <div class="col-md-4 mb-2 px-2">
                              <label for="filtroPorSede" class="form-label">Sede</label>
                              <select id="sedes" name="sedes[]" class="select2BusquedaAvanzada form-select" multiple>
                                @foreach($sedes as $sede)
                                <option value="{{ $sede->id }}" >{{ $sede->nombre }}</option>
                                @endforeach
                              </select>
                          </div>
                      <!-- / Sede  -->


                       <!--  Tipo Grupo -->
                       <div class="col-md-4 mb-2 px-2">
                          <label for="filtroPorTipoGrupo" class="form-label">Tipos Grupo</label>
                          <select id="tipoGrupo" name="tipoGrupo[]" class="select2BusquedaAvanzada form-select" multiple>
                            @foreach($tiposGrupo as $tipo)
                            <option value="{{ $tipo->id }}" >{{ $tipo->nombre }}</option>
                            @endforeach
                          </select>
                       </div>
                      <!-- / Tipo Grupo  -->

                       <!--  Tipo Grupo -->
                       <div class="col-md-6 mb-2 px-2">
                          <label for="filtroPorGrupos class="form-label">Asignar tema a un Grupo</label>
                          <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between">
                              <p class="card-text text-uppercase fw-bold"><i class="ti ti-users-group ms-n1 me-2"></i>Grupos</p>
                            </div>
                            <div class="card-body pb-20">
                              @livewire('Grupos.grupos-para-busqueda', [
                              'id' => 'inputGruposIds',
                              'class' => 'col-12 col-md-12 mb-3',
                              'label' => 'Seleccione el grupo para asignar el tema',
                              'conDadosDeBaja' => 'no',                              
                              'multiple' => TRUE                              
                              ])
                    
                            </div>
                          </div>
                          
                          </select>
                       </div>
                      <!-- / Tipo Grupo  -->


                        <!-- Full Editor -->
                        <div class="col-12">

                        <label for="filtroPorCategoria" class="form-label">Contenido del tema</label>
                        <textarea id="myeditorinstance"></textarea>
                        <input id="contenidoEditor" name="contenidoEditor" class='d-none'>
                        </div>
                        <!-- /Full Editor -->
          </div>

      </div>

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
