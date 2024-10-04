@extends('layouts/layoutMaster')

@section('title', 'User Profile - Profile')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
@endsection

<!-- Page -->
@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-profile.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/pages-profile.js')}}"></script>

<script>
  function darBajaAlta(usuarioId, $tipo)
  {
    Livewire.dispatch('abrirModalBajaAlta', { usuarioId: usuarioId, tipo: $tipo });
  }

  function comprobarSiTieneRegistros(usuarioId)
  {
    Livewire.dispatch('comprobarSiTieneRegistros', { usuarioId: usuarioId });
  }

  function eliminacionForzada(usuarioId)
  {
    Livewire.dispatch('confirmarEliminacion', { usuarioId: usuarioId });
  }
</script>

<script>
  $(".tapControl").click(function() {

    $(".tapControl").removeClass('active');
    $(".divControl").addClass('d-none');
    tap = $(this).data('tap');
    $("#tap-" + tap).addClass('active');
    $("#div-" + tap).removeClass('d-none');
  });
</script>

<script>
  let cardColor, headingColor, labelColor, borderColor, legendColor;

  if (isDarkStyle) {
    cardColor = config.colors_dark.cardColor;
    headingColor = config.colors_dark.headingColor;
    labelColor = config.colors_dark.textMuted;
    legendColor = config.colors_dark.bodyColor;
    borderColor = config.colors_dark.borderColor;
  } else {
    cardColor = config.colors.cardColor;
    headingColor = config.colors.headingColor;
    labelColor = config.colors.textMuted;
    legendColor = config.colors.bodyColor;
    borderColor = config.colors.borderColor;
  }

  const chartColors = {
    column: {
      series1: '#826af9',
      series2: '#d2b0ff',
      bg: '#f8d3ff'
    },
    donut: {
      series1: '#fee802',
      series2: '#3fd0bd',
      series3: '#826bf8',
      series4: '#2b9bf4'
    },
    area: {
      series1: '#29dac7',
      series2: '#60f2ca',
      series3: '#a5f8cd'
    }
  };

  // grafico reporte reunion
  const graficoReportesReunion = document.querySelector('#graficoReportesReunion'),
    dataReportesReunion = JSON.parse(<?php print json_encode(json_encode($dataReportesReunion)); ?>),
    serieReporesReunion = JSON.parse(<?php print json_encode(json_encode($serieReporesReunion)); ?>),
    graficoReportesReunionConfig = {
      chart: {
        height: 200,
        type: 'area',
        parentHeightOffset: 0,
        toolbar: {
          show: false
        }
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        show: false,
        curve: 'straight'
      },
      legend: {
        show: true,
        position: 'top',
        horizontalAlign: 'start',
        labels: {
          colors: legendColor,
          useSeriesColors: false
        }
      },
      grid: {
        borderColor: borderColor,
        xaxis: {
          lines: {
            show: true
          }
        }
      },
      colors: [chartColors.area.series1],
      series: [{
        name: 'Asistencias',
        data: dataReportesReunion
      }, ],
      xaxis: {
        categories: serieReporesReunion,
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        labels: {
          style: {
            colors: labelColor,
            fontSize: '13px'
          }
        }
      },
      yaxis: {
        min: 0,
        labels: {
          formatter: function(val) {
            return val.toFixed(0)
          },
          style: {
            colors: labelColor,
            fontSize: '13px'
          }
        }
      },


      fill: {
        opacity: 1,
        type: 'solid'
      },
      tooltip: {
        shared: false
      }
    };
  if (typeof graficoReportesReunion !== undefined && graficoReportesReunion !== null) {
    areaChartReunion = new ApexCharts(graficoReportesReunion, graficoReportesReunionConfig);
    areaChartReunion.render();
  }
  // grafico reporte reunion

  // grafico reporte grupo
  const graficoReportesGrupo = document.querySelector('#graficoReportesGrupo'),
    dataReportesGrupo = JSON.parse(<?php print json_encode(json_encode($dataReportesGrupo)); ?>),
    serieReporesGrupo = JSON.parse(<?php print json_encode(json_encode($serieReporesGrupo)); ?>),
    graficoReportesGrupoConfig = {
      chart: {
        height: 200,
        type: 'area',
        parentHeightOffset: 0,
        toolbar: {
          show: false
        }
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        show: false,
        curve: 'straight'
      },
      legend: {
        show: true,
        position: 'top',
        horizontalAlign: 'start',
        labels: {
          colors: legendColor,
          useSeriesColors: false
        }
      },
      grid: {
        borderColor: borderColor,
        xaxis: {
          lines: {
            show: true
          }
        }
      },
      colors: [chartColors.area.series1],
      series: [{
        name: 'Asistencias',
        data: dataReportesGrupo
      }, ],
      xaxis: {
        categories: serieReporesGrupo,
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        labels: {
          style: {
            colors: labelColor,
            fontSize: '13px'
          }
        }
      },
      yaxis: {
        min: 0,
        labels: {
          formatter: function(val) {
            return val.toFixed(0)
          },
          style: {
            colors: labelColor,
            fontSize: '13px'
          }
        }
      },


      fill: {
        opacity: 1,
        type: 'solid'
      },
      tooltip: {
        shared: false
      }
    };
  if (typeof graficoReportesGrupo !== undefined && graficoReportesGrupo !== null) {
    areaChartGrupo = new ApexCharts(graficoReportesGrupo, graficoReportesGrupoConfig);
    areaChartGrupo.render();
  }
  // grafico reporte grupo
</script>
@endsection



@section('content')

@include('layouts.status-msn')

@livewire('Usuarios.modal-baja-alta')


<!-- Header -->
<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="user-profile-header-banner">
        <img src="{{ $configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/img/banner-usuario/profile-banner.png') : $configuracion->ruta_almacenamiento.'/img/banner-usuario/profile-banner.png' }}" alt="Banner image" class="rounded-top">
      </div>
      <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
        <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
          <img src="{{ $configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/img/foto-usuario/'.$usuario->foto) : $configuracion->ruta_almacenamiento.'/img/foto-usuario/'.$usuario->foto }}" alt="{{ $usuario->foto }}" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img">
        </div>
        <div class="flex-grow-1 mt-3 mt-sm-5">
          <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
            <div class="user-profile-info">
              <h4 class="mb-0">{{ $usuario->nombre(3) }}</h4>
              <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                <li class="list-inline-item d-flex gap-1">
                  <span class="badge" style="background-color: {{ $usuario->tipoUsuario->color }}">
                    <i class="{{ $usuario->tipoUsuario->icono }} fs-6"></i> {{ $usuario->tipoUsuario->nombre }}
                  </span>
                </li>
              </ul>
            </div>
            <div class="dropdown d-flex border rounded py-2 px-4 ">
              <button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false">Opciones <i class="ti ti-dots-vertical text-muted"></i></button>
              <ul class="dropdown-menu dropdown-menu-end">

                @if($rolActivo->hasPermissionTo('personas.opcion_dar_de_alta_asistente'))
                  @if($usuario->trashed())
                  <li><a class="dropdown-item" href="javascript:void(0);" onclick="darBajaAlta('{{$usuario->id}}', 'alta')">Dar de alta</a></li>
                  @endif
                @endif

                <!-- opcion modificar  -->
                @if($rolActivo->hasPermissionTo('personas.opcion_modificar_asistente'))
                  @if($usuario->esta_aprobado==TRUE)
                    @foreach( auth()->user()->formularios('opcion_modificar_asistente', $usuario->edad()) as $formulario)
                    <li><a class="dropdown-item" href="{{ route('usuario.modificar', [$formulario, $usuario]) }}">{{$formulario->nombre2}}</a></li>
                    @endforeach
                  @elseif ($usuario->esta_aprobado==FALSE)
                    @if($rolActivo->hasPermissionTo('personas.privilegio_modificar_asistentes_desaprobados'))
                      @foreach( auth()->user()->formularios('opcion_modificar_asistente', $usuario->edad()) as $formulario)
                      <li><a class="dropdown-item" href="{{ route('usuario.modificar', [$formulario, $usuario]) }}">{{$formulario->nombre2}}</a></li>
                      @endforeach
                    @endif
                  @endif
                @endif
                <!-- / opcion modificar  -->

                @if($rolActivo->hasPermissionTo('personas.opcion_modificar_informacion_congregacional'))
                <li><a class="dropdown-item" href="{{ route('usuario.informacionCongregacional', ['formulario' => 0 ,'usuario' => $usuario]) }}">Info. congregacional</a></li>
                @endif

                @if($rolActivo->hasPermissionTo('personas.opcion_geoasignar_asistente'))
                <li><a class="dropdown-item" href="{{ route('usuario.geoAsignacion', ['formulario' => 0 ,'usuario' => $usuario]) }}">Geo asignación</a></li>
                @endif

                @if($rolActivo->hasPermissionTo('personas.opcion_cambiar_contrasena_asistente'))
                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalCambioContrasena" onclick="event.preventDefault(); document.getElementById('formCambioContrasena').setAttribute('action', '/usuarios/{{$usuario->id}}/cambiar-contrasena');">Cambiar contraseña</a></li>

                <form method="POST" id="cambiarContraseñaDefault" action="{{ route('usuario.cambiarContrasenaDefault',  ['usuario' => $usuario ]) }}">
                  @csrf
                  <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('cambiarContraseñaDefault').submit();">Cambiar contraseña default</a></li>
                </form>
                @endif

                <li><a class="dropdown-item" href="{{ route('usuario.descargarCodigoQr', $usuario) }}">Código QR</a></li>

                <hr class="dropdown-divider">
                @if($rolActivo->hasPermissionTo('personas.opcion_dar_de_baja_asistente'))
                  @if($usuario->trashed()!=TRUE)
                  <li><a class="dropdown-item text-danger" href="javascript:void(0);" onclick="darBajaAlta('{{$usuario->id}}', 'baja')">Dar de baja</a></li>
                  @endif
                @endif
                @if($rolActivo->hasPermissionTo('personas.opcion_eliminar_asistente'))
                  @if($usuario->trashed()!=TRUE)
                  <li><a class="dropdown-item text-danger" href="javascript:void(0);" onclick="comprobarSiTieneRegistros('{{$usuario->id}}')">Eliminar</a></li>
                  @endif
                @endif
                @if($rolActivo->hasPermissionTo('personas.eliminar_asistentes_forzadamente'))
                  @if($usuario->trashed()==TRUE)
                  <li><a class="dropdown-item text-danger" href="javascript:void(0);" onclick="eliminacionForzada('{{$usuario->id}}')">Eliminación forzada </a></li>
                  @endif
                @endif

              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--/ Header -->

@if($configuracion->vista_perfil_usuario_clasica==false)
<!-- Navbar pills -->
<div class="row">
  <div class="col-md-12">
    <ul class="nav nav-pills flex-column flex-sm-row mb-4 justify-content-center">
      <li class="nav-item"><a id="tap-principal" class="tapControl nav-link active" href="javascript:void(0);" data-tap="principal"><i class='ti-xs ti ti-user-check me-1'></i> Principal</a></li>
      <li class="nav-item"><a id="tap-familia" class="tapControl nav-link" href="javascript:void(0);" data-tap="familia"><i class='ti-xs ti ti-home-heart me-1'></i> Familia</a></li>
      <li class="nav-item"><a id="tap-congregacion" class="tapControl nav-link" href="javascript:void(0);" data-tap="congregacion"><i class='ti-xs ti ti-building-church me-1'></i> Congregación</a></li>
    </ul>
  </div>
</div>
<!--/ Navbar pills -->
@endif


  @if($configuracion->vista_perfil_usuario_clasica==false)
  <!-- Principal-->
  <div id="div-principal" class="row divControl">
  @else
  <div id="div-principal" class="row">
  @endif

    <div class="col-lg-6 col-md-6">

      <!-- QR -->
      <div class="my-4">
        <center>
          <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($usuario->id.'', 'QRCODE') }}" style="width: 150px; height: 150px;" alt="barcode" />
          <p class="card-text text-uppercase fw-bold">
            <i class="ti ti-qrcode ms-n1 m-2"></i>Mi código QR
          </p>
        </center>
      </div>
      <!--/ QR -->

      <!-- Información general -->
      <div class="card mb-4">
        <div class="card-header align-items-center">
          <p class="card-text text-uppercase fw-bold">Información general</p>
        </div>
        <div class="card-body pb-1">
          <ul class="list-unstyled mb-4 mt-2">
            <li class="d-flex align-items-center mb-1"><i class="ti ti-point text-heading"></i><span class="fw-medium mx-2 text-heading">Fecha de nacimiento:</span> <span>{{ $usuario->fecha_nacimiento ? Carbon\Carbon::parse($usuario->fecha_nacimiento)->locale('es')->isoFormat(('DD MMMM Y')) : 'Sin dato' }}</span></li>
            <li class="d-flex align-items-center mb-1"><i class="ti ti-point text-heading"></i><span class="fw-medium mx-2 text-heading">Edad:</span> <span>{{ $usuario->edad() }} Años ({{ $usuario->rangoEdad()->nombre }})</span></li>
            <li class="d-flex align-items-center mb-1"><i class="ti ti-point text-heading"></i><span class="fw-medium mx-2 text-heading">Sexo:</span> <span>{{ $usuario->genero == 0 ? 'Masculino' : 'Femenino' }}</span></li>

            @if($usuario->edad() >= $configuracion->limite_menor_edad)
            <li class="d-flex align-items-center mb-1"><i class="ti ti-point text-heading"></i><span class="fw-medium mx-2 text-heading">Estado civil:</span> <span>{{ $usuario->estadoCivil ? $usuario->estadoCivil->nombre : 'Sin dato' }}</span></li>
            <li class="d-flex align-items-center mb-1"><i class="ti ti-point text-heading"></i><span class="fw-medium mx-2 text-heading">Tipo identificación:</span> <span>{{ $usuario->tipoIdentificacion ? $usuario->tipoIdentificacion->nombre : 'Sin dato' }}</span></li>
            <li class="d-flex align-items-center mb-1"><i class="ti ti-point text-heading"></i><span class="fw-medium mx-2 text-heading">N° Identificación:</span> <span>{{ $usuario->identificacion ? $usuario->identificacion : 'Sin dato' }}</span></li>
            <li class="d-flex align-items-center mb-1"><i class="ti ti-point text-heading"></i><span class="fw-medium mx-2 text-heading">{{ $labelPaisNacimiento ? $labelPaisNacimiento->label_pais_nacimiento : 'Pais de nacimiento' }}:</span> <span>{{ $usuario->pais ? $usuario->pais->nombre : 'Sin dato' }}</span></li>
            <li class="d-flex align-items-center mb-1"><i class="ti ti-point text-heading"></i><span class="fw-medium mx-2 text-heading">Tipo de vivienda:</span> <span>{{ $usuario->tipoDeVivienda ? $usuario->tipoDeVivienda->nombre : 'Sin dato' }}</span></li>
            @endif

          </ul>
        </div>
      </div>
      <!--/ Información general -->

      <!-- Información de contácto-->
      <div class="card mb-4">
        <div class="card-header align-items-center">
          <p class="card-text text-uppercase fw-bold">Información de contácto</p>
        </div>
        <div class="card-body pb-1">
          <ul class="list-unstyled mb-4 mt-2">
            <li class="d-flex align-items-center mb-1"><i class="ti ti-map"></i><span class="fw-medium mx-2 text-heading">Dirección: </span> <span>
                {{ $usuario->direccion ? $usuario->direccion : 'Sin dato'}}
              </span></li>
            <li class="d-flex align-items-center mb-1"><i class="ti ti-phone-call"></i><span class="fw-medium mx-2 text-heading">Teléfono fijo:</span> <span>{{ $usuario->telefono_fijo ? $usuario->telefono_fijo : 'Sin dato'}}</span></li>

            @if($usuario->telefono_movil)
            <li class="d-flex align-items-center mb-1"><i class="ti ti-phone-call"></i><span class="fw-medium mx-2 text-heading">Teléfono móvil:</span> <span> <a target="_blank" href="https://api.whatsapp.com/send?phone={{$usuario->telefonoMovilPrefijo()}}"> {{ $usuario->telefono_movil }}</a></span></li>
            @else
            <li class="d-flex align-items-center mb-1"><i class="ti ti-phone-call"></i><span class="fw-medium mx-2 text-heading">Teléfono móvil:</span> <span> Sin dato </span></li>
            @endif
            <li class="d-flex align-items-center mb-1"><i class="ti ti-phone-call"></i><span class="fw-medium mx-2 text-heading">Otro teléfono:</span> <span>{{ $usuario->telefono_otro ? $usuario->telefono_otro : 'Sin dato'}}</span></li>
            <li class="d-flex align-items-center mb-1"><i class="ti ti-mail"></i><span class="fw-medium mx-2 text-heading">Email: </span> <span>{{ $usuario->email ? $usuario->email : 'Sin dato'}}</span></li>
          </ul>
        </div>
      </div>
      <!--/ Información de contácto-->

      <!-- Información académica y laboral -->
      <div class="card mb-4">
        <div class="card-header align-items-center">
          <p class="card-text text-uppercase fw-bold">Información académica y laboral</p>
        </div>
        <div class="card-body pb-1">
          <ul class="list-unstyled mb-4 mt-2">
            <li class="d-flex align-items-center mb-1"><i class="ti ti-point text-heading"></i><span class="fw-medium mx-2 text-heading">Nivel académico:</span> <span>{{ $usuario->nivelAcademico ? $usuario->nivelAcademico->nombre : 'Sin dato' }}</span></li>
            <li class="d-flex align-items-center mb-1"><i class="ti ti-point text-heading"></i><span class="fw-medium mx-2 text-heading">Estado nivel académico:</span> <span>{{ $usuario->estadoNivelAcademico ? $usuario->estadoNivelAcademico->nombre : 'Sin dato' }}</span></li>
            <li class="d-flex align-items-center mb-1"><i class="ti ti-point text-heading"></i><span class="fw-medium mx-2 text-heading">Profesión:</span> <span>{{ $usuario->profesion ? $usuario->profesion->nombre : 'Sin dato' }}</span></li>
            <li class="d-flex align-items-center mb-1"><i class="ti ti-point text-heading"></i><span class="fw-medium mx-2 text-heading">Ocupación:</span> <span>{{ $usuario->ocupacion ? $usuario->ocupacion->nombre : 'Sin dato' }}</span></li>
            <li class="d-flex align-items-center mb-1"><i class="ti ti-point text-heading"></i><span class="fw-medium mx-2 text-heading">Sector económico:</span> <span>{{ $usuario->sectorEconomico ? $usuario->sectorEconomico->nombre : 'Sin dato' }}</span></li>
          </ul>
        </div>
      </div>
      <!--/ Información académica y laboral -->
    </div>

    <div class="col-lg-6 col-md-6">
      <!-- Información médica -->
      <div class="card mb-4">
        <div class="card-header align-items-center">
          <p class="card-text text-uppercase fw-bold">Información médica</p>
        </div>
        <div class="card-body pb-1">
          <ul class="list-unstyled mb-4 mt-2">
            <li class="d-flex align-items-center mb-1"><i class="ti ti-point text-heading"></i><span class="fw-medium mx-2 text-heading">Tipo de sangre:</span> <span>{{ $usuario->tipoDeSangre ? $usuario->tipoDeSangre->nombre : 'Sin dato' }}</span></li>
            <li class="d-flex align-items-center mb-1"><i class="ti ti-point text-heading"></i><span class="fw-medium mx-2 text-heading">Indicaciones médicas:</span> <span>{{ $usuario->indicaciones_medicas ? $usuario->indicaciones_medicas : 'Sin dato' }}</span></li>
          </ul>
        </div>
      </div>
      <!--/ Información médica -->

      <!-- Información de creación -->
      <div class="card mb-4">
        <div class="card-header align-items-center">
          <p class="card-text text-uppercase fw-bold">Información de creación</p>
        </div>
        <div class="card-body pb-1">
          <ul class="list-unstyled mb-4 mt-2">
            <li class="d-flex align-items-center mb-1"><i class="ti ti-point text-heading"></i><span class="fw-medium mx-2 text-heading">Tipo vinculación:</span> <span>{{ $usuario->tipoVinculacion->withTrashed()->first() ? $usuario->tipoVinculacion->withTrashed()->first()->nombre : 'Sin dato' }}</span></li>
            <li class="d-flex align-items-center mb-1"><i class="ti ti-point text-heading"></i><span class="fw-medium mx-2 text-heading">Fecha de creación:</span> <span>{{ $usuario->created_at ? Carbon\Carbon::parse($usuario->created_at)->locale('es')->isoFormat(('DD MMMM Y')) : 'Sin dato' }}</span></li>
            <li class="d-flex align-items-center mb-1"><i class="ti ti-point text-heading"></i><span class="fw-medium mx-2 text-heading">Hora de creación:</span> <span>{{ $usuario->created_at ? Carbon\Carbon::parse($usuario->created_at)->format('H:i:s') : 'Sin dato' }}</span></li>
            <li class="d-flex align-items-center mb-1"><i class="ti ti-point text-heading"></i><span class="fw-medium mx-2 text-heading">Creado por:</span> <span>{{ !$usuario->usuarioCreacion ? 'Sin dato' : '' }}</span></li>
            @if($usuario->usuarioCreacion)
            <div class="d-flex align-items-start border rounded-3 p-2">
              <div class="avatar me-2">
                <img src="{{ $configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/img/foto-usuario/'.$usuario->usuarioCreacion->foto) : Storage::url($configuracion->ruta_almacenamiento.'/img/foto-usuario/'.$usuario->usuarioCreacion->foto) }}
              " alt="foto {{$usuario->usuarioCreacion->nombre(3)}}" class="rounded-circle">
              </div>
              <div class="me-2 ms-1 ">
                <h6 class="mb-0">{{ $usuario->usuarioCreacion->nombre(3) }}</h6>
                <small class="text-muted">{{ $usuario->usuarioCreacion->tipoUsuario->nombre }} </small>
              </div>
            </div>
            @endif
          </ul>
        </div>
      </div>
      <!--/ Información de creación -->

      <!-- Información opcional -->
      <div class="card mb-4">
        <div class="card-header align-items-center">
          <p class="card-text text-uppercase fw-bold">Más información</p>
        </div>
        <div class="card-body pb-1">
          <ul class="list-unstyled mb-4 mt-2">
            <li class="d-flex align-items-center mb-1"><i class="ti ti-point text-heading"></i><span class="fw-medium mx-2 text-heading">{{ $configuracion->nombre_informacion_opcional ? $configuracion->nombre_informacion_opcional : 'Información adicional' }} :</span> <span>{{ $usuario->informacion_opcional ? $usuario->informacion_opcional : 'Sin dato' }}</span></li>
            @if($rolActivo->hasPermissionTo('personas.ver_campo_reservado_visible'))
            <li class="d-flex align-items-center mb-1"><i class="ti ti-point text-heading"></i><span class="fw-medium mx-2 text-heading">{{ $configuracion->nombre_campo_reservado ? $configuracion->nombre_campo_reservado : 'Campo reservado' }} :</span> <span>{{ $usuario->campo_reservado ? $usuario->campo_reservado : 'Sin dato' }}</span></li>
            @endif
          </ul>
        </div>
      </div>
      <!--/ Información opcional -->

      @if($configuracion->visible_seccion_campos_extra==TRUE)
      <!-- Información campos extra -->
      <div class="card mb-4">
        <div class="card-header align-items-center">
          <p class="card-text text-uppercase fw-bold">{{ $configuracion->label_seccion_campos_extra ? $configuracion->label_seccion_campos_extra : 'Campos extra' }} </p>
        </div>
        <div class="card-body pb-1">
          <ul class="list-unstyled mb-4 mt-2">
            {!! $camposExtrasHtml !!}
          </ul>
        </div>
      </div>
      <!--/ Información campos extra -->
      @endif

      @if($rolActivo->hasPermissionTo('personas.ver_panel_archivos'))
      <!-- Archivos -->
      <div class="card mb-4">
        <div class="card-header align-items-center">
          <p class="card-text text-uppercase fw-bold">Archivos adjuntos</p>
        </div>
        <div class="card-body pb-3">
          <ul class="list-unstyled mb-4 mt-2">
            @if($usuario->archivo_a)
            <li class="d-flex align-items-center mb-1"><i class="ti ti-file"></i><span class="fw-medium mx-2 text-heading"></span> <a target="_blank" href="{{ $configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/archivos/'.$usuario->archivo_a) : $configuracion->ruta_almacenamiento.'/archivos/'.$usuario->archivo_a }}">{{ $usuario->archivo_a }}</a> </li>
            @endif

            @if($usuario->archivo_b)
            <li class="d-flex align-items-center mb-1"><i class="ti ti-file"></i><span class="fw-medium mx-2 text-heading"></span> <a target="_blank" href="{{ $configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/archivos/'.$usuario->archivo_b) : $configuracion->ruta_almacenamiento.'/archivos/'.$usuario->archivo_b }}">{{ $usuario->archivo_b }}</a> </li>
            @endif

            @if($usuario->archivo_c)
            <li class="d-flex align-items-center mb-1"><i class="ti ti-file"></i><span class="fw-medium mx-2 text-heading"></span> <a target="_blank" href="{{ $configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/archivos/'.$usuario->archivo_c) : $configuracion->ruta_almacenamiento.'/archivos/'.$usuario->archivo_c }}">{{ $usuario->archivo_c }}</a> </li>
            @endif

            @if($usuario->archivo_d)
            <li class="d-flex align-items-center mb-1"><i class="ti ti-file"></i><span class="fw-medium mx-2 text-heading"></span> <a target="_blank" href="{{ $configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/archivos/'.$usuario->archivo_d) : $configuracion->ruta_almacenamiento.'/archivos/'.$usuario->archivo_d }}">{{ $usuario->archivo_d }}</a> </li>
            @endif

          </ul>
        </div>
      </div>
      <!-- /Archivos-->
      @endif
    </div>
  </div>
  <!--/ Principal-->

  @if($configuracion->vista_perfil_usuario_clasica==false)
  <!-- Familia-->
  <div id="div-familia" class="row divControl d-none">
  @else
  <div id="div-familia" class="row">
  @endif

    <!-- Grupo familiar -->
    <div class="col-lg-8 col-md-12">
      <div class="card mb-4">
        <div class="card-header align-items-center">
          <p class="card-text text-uppercase fw-bold">Grupo familiar</p>
        </div>
        <div class="card-body">
          <div class="row g-4">
            @if($parientes->count() > 0)
            @foreach($parientes as $pariente)
            <div class="col-lg-4 col-md-6">
              <div class="card border rounded">
                <div class="card-body text-center">
                  <div class="mx-auto my-3">
                    <img src="{{ $configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/img/foto-usuario/'.$pariente->foto) : $configuracion->ruta_almacenamiento.'/img/foto-usuario/'.$pariente->foto }}" alt="foto {{$pariente->primer_nombre}}" class="rounded-circle w-px-100" />
                  </div>

                  <span class="pb-1"><span></span><b>Relación:</b> {{ $usuario->genero == 0 ? $pariente->nombre_masculino : $pariente->nombre_femenino }} de </span>
                  <h4 class="mb-1 card-title">{{ $pariente->nombre(3) }}</h4>

                  <div class="d-flex align-items-center justify-content-center my-3 gap-2">
                    <span>¿Soy el responsable?</span>
                    @if($pariente->es_el_responsable)
                    <a href="javascript:;" class="me-1"><span class="badge bg-label-success">Si</span></a>
                    @else
                    <a href="javascript:;"><span class="badge bg-label-secondary">No</span></a>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            @endforeach
            @else
            <div class="py-4">
              <center>
                <i class="ti ti-home-heart fs-1 pb-1"></i>
                <h6 class="text-center">No hay personas en tu grupo familiar</h6>
              </center>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
    <!-- / Grupo familiar -->

    <div class="col-lg-4 col-md-12">
      <div class="card mb-4">
        <div class="card-header align-items-center">
          <p class="card-text text-uppercase fw-bold">Datos del acudiente</p>
        </div>
        <div class="card-body pb-1">
          <ul class="list-unstyled mb-4 mt-2">
            <li class="d-flex align-items-center mb-1"><i class="ti ti-point text-heading"></i><span class="fw-medium mx-2 text-heading">Nombre:</span> <span>{{ $usuario->nombre_acudiente ? $usuario->nombre_acudiente : 'Sin dato' }}</span></li>
            <li class="d-flex align-items-center mb-1"><i class="ti ti-point text-heading"></i><span class="fw-medium mx-2 text-heading">Teléfono:</span> <span>{{ $usuario->telefono_acudiente ? $usuario->telefono_acudiente : 'Sin dato' }}</span></li>
            <li class="d-flex align-items-center mb-1"><i class="ti ti-point text-heading"></i><span class="fw-medium mx-2 text-heading">Tipo identificación:</span> <span>{{ $usuario->tipo_identificacion_acudiente_id ? $usuario->tipoIdentificacionAcudiente->nombre : 'Sin dato' }}</span></li>
            <li class="d-flex align-items-center mb-1"><i class="ti ti-point text-heading"></i><span class="fw-medium mx-2 text-heading">Tipo identificación:</span> <span>{{ $usuario->identificacion_acudiente ? $usuario->identificacion_acudiente : 'Sin dato' }}</span></li>
          </ul>
        </div>
      </div>
    </div>

  </div>
  <!--/ Familia-->

    @if($configuracion->vista_perfil_usuario_clasica==false)
    <!-- Congregación -->
    <div id="div-congregacion" class="row divControl d-none">
      @else
      <div id="div-congregacion" class="row">
        @endif

        <div class="col-md-6">
          <div class="card mb-4">
            <div class="card-header align-items-center">
              <p class="card-text text-uppercase fw-bold">Información congregacional</p>
            </div>
            <div class="card-body pb-3">
              <ul class="list-unstyled mb-4 mt-2">
                <li class="d-flex align-items-center mb-1"><i class="ti ti-point text-heading"></i><span class="fw-medium mx-2 text-heading">Vinculado por:</span> <span>{{ $usuario->tipo_vinculacion_id ? $usuario->tipoVinculacion->nombre : 'Sin dato' }}</span></li>
                <li class="d-flex align-items-center mb-1"><i class="ti ti-point text-heading"></i><span class="fw-medium mx-2 text-heading">Última asistencia a grupo:</span> <span>{{ $usuario->ultimo_reporte_grupo ? Carbon\Carbon::parse($usuario->ultimo_reporte_grupo)->locale('es')->isoFormat(('DD MMMM Y')) : 'Sin dato' }}</span></li>
                <li class="d-flex align-items-center mb-1"><i class="ti ti-point text-heading"></i><span class="fw-medium mx-2 text-heading">Sede:</span> <span>{{ $usuario->sede_id ? $usuario->sede->nombre : 'Sin dato' }}</span></li>
                <li class="d-flex align-items-center mb-1"><i class="ti ti-point text-heading"></i><span class="fw-medium mx-2 text-heading">Roles:</span> <span>{{ count($roles) > 0 ? implode(',',$roles) : 'Sin dato' }}</span></li>
                <li class="d-flex align-items-center mb-1"><i class="ti ti-point text-heading"></i><span class="fw-medium mx-2 text-heading">Servicios prestados en grupos:</span><span> {{ $serviciosPrestadosEnGrupos->count() < 1 ? 'Sin dato' : '' }}</span></li>

                @if($serviciosPrestadosEnGrupos->count() > 0)
                <ul class="p-0 pt-2 m-0 d-flex flex-column">
                  @foreach($serviciosPrestadosEnGrupos as $servicio)
                  <li class="d-flex gap-3 align-items-center mb-1 mx-2 pb-1">
                    <div class="badge rounded bg-label-info p-1"><i class="ti ti-circle-check ti-sm"></i></div>
                    <div>
                      <h6 class="mb-0 text-nowrap">{{ $servicio->nombre }}</h6>
                      <small class="text-muted"><b>{{ $servicio->nombreTipoGrupo }}</b> | {{ $servicio->nombreGrupo }} </small>
                    </div>
                  </li>
                  @endforeach
                </ul>
                @endif


              </ul>
            </div>
          </div>

          <!-- Ministerio a cargo -->
          @if($gruposEncargados->count() > 0)
          <div class="card card-action mb-4">
            <div class="card-header align-items-center pb-0">
              <p class="card-text text-uppercase fw-bold">Grupos que dirijo</p><br>
            </div>
            <div class="card-body pb-3">

              <ul class="p-0 m-0 d-flex flex-column">
                <li class="d-flex gap-3 align-items-center pt-2 mx-2 mb-1 pb-1">
                  <div class="badge rounded bg-label-primary p-1"><i class="ti ti-users-group ti-sm"></i></div>
                  <div>
                    <h6 class="mb-0 text-nowrap">Grupos directos</h6>
                    <small class="text-muted">{{ $totalGruposDirectos }}</small>
                  </div>
                </li>
                <li class="d-flex gap-3 align-items-center mb-1 mx-2 pb-1">
                  <div class="badge rounded bg-label-warning p-1"><i class="ti ti-users-group ti-sm"></i></div>
                  <div>
                    <h6 class="mb-0 text-nowrap">Grupos indirectos</h6>
                    <small class="text-muted">{{ $totalGruposDirectos }}</small>
                  </div>
                </li>
                <li class="d-flex gap-3 align-items-center mb-1 mx-2 pb-1">
                  <div class="badge rounded bg-label-info p-1"><i class="ti ti-users-group ti-sm"></i></div>
                  <div>
                    <h6 class="mb-0 text-nowrap">Total grupos</h6>
                    <small class="text-muted">{{ $totalGruposDirectos }}</small>
                  </div>
                </li>
                <li class="d-flex gap-3 align-items-center mb-1 mx-2 pb-1">
                  <div class="badge rounded bg-label-info p-1"><i class="ti ti-users ti-sm"></i></div>
                  <div>
                    <h6 class="mb-0 text-nowrap">Total personas</h6>
                    <small class="text-muted">{{ $totalGruposDirectos }}</small>
                  </div>
                </li>
              </ul>

              <ul class="list-unstyled mb-0 mt-2">
                <small class="card-text text-uppercase">Mis grupos directos</small>
                @foreach($gruposEncargados as $grupo)
                <li class="mb-1 mt-1 p-2 border rounded">
                  <div class="d-flex align-items-start">
                    <div class="d-flex align-items-start">
                      <div class="avatar me-2">
                        <i class="ti ti-users-group me-2 fs-1"></i>
                      </div>
                      <div class="me-2 ms-1">
                        <h6 class="mb-0">{{ $grupo->nombre }}</h6>
                        <small class="text-muted"><b>Tipo:</b> {{ $grupo->tipoGrupo->nombre }}</small>
                      </div>
                    </div>
                    <div class="ms-auto pt-1">
                      <div class="d-flex align-items-center">
                        @if($grupo->latitud)
                        <a href="https://www.google.com/maps/@?api=1&map_action=pano&viewpoint={{$grupo->latitud}}%2C{{$grupo->longitud}}" target="_blank"  class="text-body" data-bs-toggle="tooltip" aria-label="Ver mapa" data-bs-original-title="Ver ubicación en el mapa">
                          <i class="ti ti-map-2 me-2 ti-sm"></i></a>
                        @else
                        <a href="{{ route('grupo.georreferencia',$grupo) }}" target="_blank" class="text-body" data-bs-toggle="tooltip" aria-label="Agregar georeferencia" data-bs-original-title="Este grupo no está ubicado en el mapa, por favor agrega la ubicación aquí">
                          <i class="ti ti-map-pin-plus me-2 ti-sm"></i>
                        </a>
                        @endif
                        <a href="{{ route('grupo.graficoMinisterial',$grupo) }}" target="_blank" class="text-body" data-bs-toggle="tooltip" aria-label="Ver gráfico ministerial " data-bs-original-title="Ver gráfico ministerial">
                          <i class="ti ti-sitemap me-2 ti-sm"></i>
                        </a>
                      </div>
                    </div>
                </li>
                @endforeach
              </ul>
            </div>
          </div>
          @endif
          <!--/ Encargados directos -->

          <!-- Encargados  -->
          @if($encargadosDirectos->count() > 0 || $encargadosAscendentes->count() > 0)
          <div class="card card-action mb-4">
            <div class="card-header align-items-center">
              <p class="card-text text-uppercase fw-bold">Encargados</p>
            </div>
            <div class="card-body pb-3">
              <ul class="list-unstyled mb-0">
                @if($encargadosDirectos->count() > 0)
                <small class="card-text text-uppercase">Directos</small>
                @foreach($encargadosDirectos as $encargado)
                <li class="mb-1 mt-1 p-2 border rounded">
                  <div class="d-flex align-items-start">
                    <div class="d-flex align-items-start">
                      <div class="avatar me-2">
                        <img src="{{ $configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/img/foto-usuario/'.$encargado->foto) : $configuracion->ruta_almacenamiento.'/img/foto-usuario/'.$encargado->foto }}" alt="Avatar" class="rounded-circle" />
                      </div>
                      <div class="me-2 ms-1">
                        <h6 class="mb-0">{{ $encargado->nombre }}</h6>
                        <small class="text-muted"><i class="ti {{ $encargado->icono }} text-heading fs-6"></i> {{ $encargado->tipo_usuario}}</small>
                      </div>
                    </div>

                    <div class="ms-auto pt-1">
                      @if($rolActivo->hasPermissionTo('personas.lista_asistentes_todos'))
                      <a href="{{ route('usuario.perfil', $encargado) }}" target="_blank" class="text-body" data-bs-toggle="tooltip" aria-label="Ver perfil" data-bs-original-title="Ver perfil">
                        <i class="ti ti-user-check me-2 ti-sm"></i></a>
                      </a>
                      @endif
                    </div>
                  </div>
                </li>
                @endforeach
                @endif
                @if($encargadosAscendentes->count() > 0 )
                <small class="card-text text-uppercase">Ascendentes</small>
                @foreach($encargadosAscendentes as $encargado)
                <li class="mb-1 mt-1 p-2 border rounded">
                  <div class="d-flex align-items-start">
                    <div class="d-flex align-items-start">
                      <div class="avatar me-2">
                        <img src="{{ $configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/img/foto-usuario/'.$encargado->foto) : $configuracion->ruta_almacenamiento.'/img/foto-usuario/'.$encargado->foto }}" alt="Avatar" class="rounded-circle" />
                      </div>
                      <div class="me-2 ms-1">
                        <h6 class="mb-0">{{ $encargado->nombre(3) }}</h6>
                        <small class="text-muted"><i class="ti {{ $encargado->tipoUsuario->icono }} text-heading fs-6"></i> {{ $encargado->tipoUsuario->nombre }}</small>
                      </div>
                    </div>

                    <div class="ms-auto pt-1">
                      @if($rolActivo->hasPermissionTo('personas.lista_asistentes_todos'))
                      <a href="{{ route('usuario.perfil', $encargado) }}" target="_blank" class="text-body" data-bs-toggle="tooltip" aria-label="Ver perfil" data-bs-original-title="Ver perfil">
                        <i class="ti ti-user-check me-2 ti-sm"></i></a>
                      </a>
                      @endif
                    </div>

                  </div>
                </li>
                @endforeach
                @endif

              </ul>
            </div>
          </div>
          @endif
          <!--/ Encargados  -->

          <!-- Grupos -->
          @if($gruposDondeAsiste->count() > 0 || $gruposAscendentes->count() > 0 || $gruposExcluidos->count() > 0)
          <div class="card card-action mb-4">
            <div class="card-header align-items-center">
              <p class="card-text text-uppercase fw-bold">Grupos</p>
            </div>
            <div class="card-body pb-3">
              <ul class="list-unstyled mb-0">
                @if($gruposDondeAsiste->count() > 0)
                <small class="card-text text-uppercase">Asiste a</small>
                @foreach($gruposDondeAsiste as $grupo)
                <li class="mb-1 mt-1 p-2 border rounded">
                  <div class="d-flex align-items-start">
                    <div class="d-flex align-items-start">
                      <div class="avatar me-2">
                        <i class="ti ti-users-group me-2 fs-1"></i>
                      </div>
                      <div class="me-2 ms-1">
                        <h6 class="mb-0">{{ $grupo->nombre }}</h6>
                        <small class="text-muted"><b>Tipo:</b> {{ $grupo->tipoGrupo->nombre }}</small>
                      </div>
                    </div>

                    <div class="ms-auto pt-1">
                      @if($rolActivo->hasPermissionTo('grupos.lista_grupos_todos'))
                      <a href="{{ route('grupo.perfil', $grupo) }}" target="_blank" class="text-body" data-bs-toggle="tooltip" aria-label="Ver perfil" data-bs-original-title="Ver perfil del grupo">
                        <i class="ti ti-id me-2 ti-sm"></i></a>
                      </a>
                      @endif
                    </div>
                  </div>
                </li>
                @endforeach
                @endif

                @if($gruposAscendentes->count() > 0)
                <small class="card-text text-uppercase">Ascendentes</small>
                @foreach($gruposAscendentes as $grupo)
                <li class="mb-1 mt-1 p-2 border rounded">
                  <div class="d-flex align-items-start">
                    <div class="d-flex align-items-start">
                      <div class="avatar me-2">
                        <i class="ti ti-users-group me-2 fs-1"></i>
                      </div>
                      <div class="me-2 ms-1">
                        <h6 class="mb-0">{{ $grupo->nombre }}</h6>
                        <small class="text-muted"><b>Tipo:</b> {{$grupo->nombreTipo}}</small>
                      </div>
                    </div>
                    <div class="ms-auto pt-1">
                      @if($rolActivo->hasPermissionTo('grupos.lista_grupos_todos'))
                      <a href="{{ route('grupo.perfil', $grupo) }}" target="_blank" class="text-body" data-bs-toggle="tooltip" aria-label="Ver perfil" data-bs-original-title="Ver perfil del grupo">
                        <i class="ti ti-id me-2 ti-sm"></i></a>
                      </a>
                      @endif
                    </div>
                  </div>
                </li>
                @endforeach
                @endif

                @if($gruposExcluidos->count() > 0)
                <small class="card-text text-uppercase">Excluidos</small>
                @foreach($gruposExcluidos as $grupo)
                <li class="mb-3 mt-1">
                  <div class="d-flex align-items-start">
                    <div class="d-flex align-items-start">
                      <div class="avatar me-2">
                        <i class="ti ti-users-group me-2 fs-1"></i>
                      </div>
                      <div class="me-2 ms-1 p-2 border rounded">
                        <h6 class="mb-0">{{ $grupo->nombre }}</h6>
                        <small class="text-muted"><b>Tipo:</b> {{$grupo->tipoGrupo->nombre}}</small>
                      </div>
                    </div>
                    <div class="ms-auto pt-1">
                      @if($rolActivo->hasPermissionTo('grupos.lista_grupos_todos'))
                      <a href="{{ route('grupo.perfil', $grupo) }}" target="_blank" class="text-body" data-bs-toggle="tooltip" aria-label="Ver perfil" data-bs-original-title="Ver perfil del grupo">
                        <i class="ti ti-id me-2 ti-sm"></i></a>
                      </a>
                      @endif
                    </div>
                  </div>
                </li>
                @endforeach
                @endif
              </ul>
            </div>
          </div>
          @endif
          <!--/ Grupos -->

          <!-- Peticiones -->
          <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
              <p class="card-text text-uppercase fw-bold"><i class="ti ti-message ms-n1 me-2"></i>Peticiones</p>
            </div>
            <div class="card-body pb-20">
              <ul class="timeline ms-1 mb-0">
                @foreach ($peticiones as $peticion)
                <li class="timeline-item timeline-item-transparent ps-4">
                  <span class="timeline-point {{ $peticion->estado== 3 ? 'timeline-point-warning' : ($peticion->estado== 2 ? 'timeline-point-success' : 'timeline-point-primary') }}"></span>
                  <div class="timeline-event">
                    <div class="timeline-header">
                      <h6 class="mb-0 ml-1 fw-bold">{{ $peticion->tipoPeticion->nombre }}</h6>
                      <span class="badge rounded-pill {{ $peticion->estado== 2 ? 'bg-label-success' : ($peticion->estado== 1 ? 'bg-label-primary' : 'bg-label-warning') }}">
                        {{ $peticion->estado== 3 ? 'Atendida' : ($peticion->estado== 2 ? 'Finalizada' : 'Iniciada') }}
                      </span>
                    </div>
                    <small class="text-muted"><i class="ti ti-calendar"></i> {{ $peticion->fecha ?  Carbon\Carbon::parse($peticion->fecha)->locale('es')->isoFormat(('DD MMMM Y')) : 'Sin dato' }}</small>

                    @if($peticion->estado==1)
                    <div class="accordion mt-3" id="peticion{{$peticion->id}}">
                      <div class="card accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                          <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionPeticion{{$peticion->id}}" aria-expanded="true" aria-controls="accordionPeticion{{$peticion->id}}">
                            Petición
                          </button>
                        </h2>

                        <div id="accordionPeticion{{$peticion->id}}" class="accordion-collapse collapse" data-bs-parent="#peticion{{$peticion->id}}">
                          <div class="accordion-body">
                            {{ $peticion->descripcion }}
                          </div>
                        </div>
                      </div>
                    </div>
                    @endif

                    @if($peticion->estado==2)
                    <div class="accordion mt-3" id="respuestaPeticion{{$peticion->id}}">
                      <div class="card accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                          <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionPeticionRespuesta{{$peticion->id}}" aria-expanded="true" aria-controls="accordionPeticionRespuesta{{$peticion->id}}">
                            Repuesta
                          </button>
                        </h2>

                        <div id="accordionPeticionRespuesta{{$peticion->id}}" class="accordion-collapse collapse" data-bs-parent="#respuestaPeticion{{$peticion->id}}">
                          <div class="accordion-body">
                            {{ $peticion->respuesta }}
                          </div>
                        </div>
                      </div>
                    </div>
                    @endif
                </li>
                @endforeach
              </ul>
            </div>
          </div>
          <!--/ Peticiones -->

        </div>

        <div class="col-md-6">

          <!-- Grafico de asistencia a reunión -->
          <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
              <div>
                <h6 class="card-title text-uppercase mb-0 fw-bold">Gráfico asistencia a reuniones</h6>
                <small class="text-muted">
                  Asistencias de los últimos 12 meses
                </small>
              </div>
            </div>
            <div class="card-body">
              <div id="graficoReportesReunion"></div>
              <center>
                <small class="text-muted">
                  Última asistencia a la reunión: <b>{{ $usuario->ultimo_reporte_reunion ? Carbon\Carbon::parse($usuario->ultimo_reporte_reunion)->locale('es')->isoFormat(('DD MMMM Y')) : 'Sin dato' }}</b>
                </small>
              </center>

            </div>
          </div>
          <!-- /Grafico de asistencia a reunión -->

          <!-- Grafico de asistencia al grupo -->
          <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
              <div>
                <h6 class="card-title text-uppercase mb-0 fw-bold">Gráfico asistencia al grupo</h6>
                <small class="text-muted">
                  Asistencias de los últimos 12 meses
                </small>
              </div>
            </div>
            <div class="card-body">
              <div id="graficoReportesGrupo"></div>
              <center>
                <small class="text-muted">
                  Última asistencia al grupo: <b>{{ $usuario->ultimo_reporte_grupo ? Carbon\Carbon::parse($usuario->ultimo_reporte_grupo)->locale('es')->isoFormat(('DD MMMM Y')) : 'Sin dato' }}</b>
                </small>
              </center>

            </div>
          </div>
          <!-- /Grafico de asistencia a reunión -->

          @if($rolActivo->hasPermissionTo('personas.ver_panel_pasos_crecimiento_perfil'))
          <!-- Procesos de crecimiento -->
          <div class="card">
            <div class="card-header d-flex justify-content-between">
              <p class="card-text text-uppercase fw-bold"><i class="ti ti-list-details ms-n1 me-2"></i>Procesos de crecimiento</p>
            </div>
            <div class="card-body pb-20">
              <ul class="timeline ms-1 mb-0">
                @foreach ($pasosDeCrecimiento as $paso)
                <li class="timeline-item timeline-item-transparent ps-4">
                  <span class="timeline-point timeline-point-{{ $paso->clase_color }}"></span>
                  <div class="timeline-event">
                    <div class="timeline-header">
                      <h6 class="mb-0 ml-1 fw-bold">{{ $paso->nombre }}</h6>
                      <span class="badge rounded-pill bg-label-{{ $paso->clase_color }}">
                        {{ $paso->estado_nombre }}
                      </span>
                    </div>
                    <small class="text-muted"><i class="ti ti-calendar"></i> {{ $paso->estado_fecha ?  Carbon\Carbon::parse($paso->estado_fecha)->locale('es')->isoFormat(('DD MMMM Y')) : 'Sin dato' }}</small>
                    <p class="mb-2 d-none"><b>Detalle: </b>{{ $paso->detalle_paso ? $paso->detalle_paso : 'No detallado' }}</p>

                    @if($paso->detalle_paso)
                    <div class="accordion mt-3" id="accordion{{$paso->id}}">
                      <div class="card accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                          <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionPaso{{$paso->id}}" aria-expanded="true" aria-controls="#accordionPaso{{$paso->id}}">
                            Detalle
                          </button>
                        </h2>

                        <div id="accordionPaso{{$paso->id}}" class="accordion-collapse collapse" data-bs-parent="accordion{{$paso->id}}">
                          <div class="accordion-body">
                            {{ $paso->detalle_paso }}
                          </div>
                        </div>
                      </div>
                    </div>
                    @endif
                </li>
                @endforeach
              </ul>
            </div>
          </div>
          <!--/ Procesos de crecimiento -->
          @endif
        </div>

      </div>
      <!--/ Congregación -->

      <!-- Modal cambio de contraseña -->
      <div class="modal fade" id="modalCambioContrasena" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
          <form id="formCambioContrasena" class="forms-sample" method="POST" action="">
            @csrf
            <div class="modal-content">
              <div class="modal-header d-flex flex-column">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">

                <div class="text-center mb-4">
                  <h3 class="role-title mb-2"><i class="ti ti-password ti-lg"></i> Cambio de contraseña</h3>
                  <p class="text-muted">La contraseña debe contener como mínimo 5 caracteres, una letra minúscula y un número.</p>
                </div>

                <div class="row">

                  <!-- Nueva Contrasena -->
                  <div class="col-12 mb-3">
                    <label class="form-label" for="nueva_contrasena">Nueva contraseña</label>
                    <input id="nueva_contrasena" name="password" value="" type="password" class="form-control" required pattern="(?=.*\d)(?=.*[A-Za-z]).{5,}" title="La contraseña debe contener como minimo 5 caracteres alfanumericos, es decir, debe contener como minimo letras y numeros.  "/>
                  </div>

                  <!-- Confirmar Contrasena -->
                  <div class="col-12 mb-3">
                    <label class="form-label" for="confirmar_contrasena">Confirmar contraseña</label>
                    <input id="confirmar_contrasena" name="password_confirmation" value="" type="password" class="form-control" required pattern="(?=.*\d)(?=.*[A-Za-z]).{5,}" title="La contraseña debe contener como minimo 5 caracteres alfanumericos, es decir, debe contener como minimo letras y numeros.  "/>
                  </div>

                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary"><i class="ti ti-donwload ml-3"></i> Guardar </button>
              </div>
            </div>
          </form>
        </div>
      </div>


      @endsection
