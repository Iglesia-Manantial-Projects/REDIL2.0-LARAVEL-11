@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Sedes')

<!-- Page -->
@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
@endsection

@section('page-script')
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
      bg: '#f8d3f9'
    },
    donut: {
      series1: '#fee802',
      series2: '#3fd0bd',
      series3: '#826bf8',
      series4: '#2b9bf4',
      series5: '#f56954',
      series6: '#d2b0ff',
      series7: '#00a65a"',
      series9: '#f56900',
      series10: '#d2b050',

    },
    area: {
      series1: '#29dac7',
      series2: '#60f2ca',
      series3: '#a5f8cd'
    },
    sexo: {
      series1: '#2b9bf4',
      series2: '#826bf8'
    }
  };

  // grafico crecimiento de grupos
  const graficoCrecimientoGrupos = document.querySelector('#graficoCrecimientoGrupos'),
  dataCrecimientoGrupos = JSON.parse(<?php print json_encode(json_encode($dataCrecimientoGrupos)); ?>),
  serieCrecimientoGrupos = JSON.parse(<?php print json_encode(json_encode($serieCrecimientoGrupos)); ?>),
  graficoCrecimientoGruposConfig =
  {
    series: [{
      name: "Grupos",
      data: dataCrecimientoGrupos
    }],
    chart: {
      height: 300,
      type: 'line',
      zoom: {
        enabled: true
      },
      parentHeightOffset: 0,
      toolbar: {
        show: false
      }
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'straight'
    },
    markers: {
      size: 5
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
    xaxis: {
      categories: serieCrecimientoGrupos,
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
  };
  if (typeof graficoCrecimientoGrupos !== undefined && graficoCrecimientoGrupos !== null) {
    areaChartCrecimientoGrupos = new ApexCharts(graficoCrecimientoGrupos, graficoCrecimientoGruposConfig);
    areaChartCrecimientoGrupos.render();
  }
  // grafico crecimiento de grupos

  // grafico crecimiento de personas
  const graficoCrecimientoPersonas = document.querySelector('#graficoCrecimientoPersonas'),
  dataCrecimientoPersonas = JSON.parse(<?php print json_encode(json_encode($dataCrecimientoPersonas)); ?>),
  serieCrecimientoPersonas = JSON.parse(<?php print json_encode(json_encode($serieCrecimientoPersonas)); ?>),
  graficoCrecimientoPersonasConfig =
  {
    series: [{
      name: "Personas",
      data: dataCrecimientoPersonas
    }],
    chart: {
      height: 300,
      type: 'line',
      zoom: {
        enabled: true
      },
      parentHeightOffset: 0,
      toolbar: {
        show: false
      }
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'straight'
    },
    markers: {
      size: 5
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
    xaxis: {
      categories: serieCrecimientoPersonas,
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
  };
  if (typeof graficoCrecimientoPersonas !== undefined && graficoCrecimientoPersonas !== null) {
    areaChartCrecimientoPersonas = new ApexCharts(graficoCrecimientoPersonas, graficoCrecimientoPersonasConfig);
    areaChartCrecimientoPersonas.render();
  }
  // grafico crecimiento de personas

  // Grafico por edades
  const rangoEdadesGrafico = document.querySelector('#rangoEdades'),
  seriesRangoEdades = JSON.parse(<?php print json_encode(json_encode($seriesRangoEdades)); ?>),
  labelsRangoEdades = JSON.parse(<?php print json_encode(json_encode($labelsRangoEdades)); ?>),
    rangoEdadesConfig = {
      chart: {
        height: 390,
        type: 'donut'
      },
      labels: labelsRangoEdades,
      series: seriesRangoEdades,
      colors: [
        chartColors.donut.series1,
        chartColors.donut.series2,
        chartColors.donut.series3,
        chartColors.donut.series4,
        chartColors.donut.series5,
        chartColors.donut.series6,
        chartColors.donut.series7,
        chartColors.donut.series8,
        chartColors.donut.series9,
        chartColors.donut.series10,
      ],
      stroke: {
        show: false,
        curve: 'straight'
      },
      dataLabels: {
        enabled: true,
        formatter: function (val, opt) {
          return parseInt(val, 10) + '%';
        }
      },
      legend: {
        show: true,
        position: 'bottom',
        markers: { offsetX: -3 },
        itemMargin: {
          vertical: 3,
          horizontal: 10
        },
        labels: {
          colors: legendColor,
          useSeriesColors: false
        }
      },
      plotOptions: {
        pie: {
          donut: {
            labels: {
              show: true,
              name: {
                fontSize: '2rem',
                fontFamily: 'Public Sans'
              },
              value: {
                fontSize: '1.2rem',
                color: legendColor,
                fontFamily: 'Public Sans',
                formatter: function (val) {
                  return parseInt(val, 10) + '%';
                }
              }
            }
          }
        }
      },
      responsive: [
        {
          breakpoint: 992,
          options: {
            chart: {
              height: 380
            },
            legend: {
              position: 'bottom',
              labels: {
                colors: legendColor,
                useSeriesColors: false
              }
            }
          }
        },
        {
          breakpoint: 576,
          options: {
            chart: {
              height: 320
            },
            plotOptions: {
              pie: {
                donut: {
                  labels: {
                    show: true,
                    name: {
                      fontSize: '1.5rem'
                    },
                    value: {
                      fontSize: '1rem'
                    },
                    total: {
                      fontSize: '1.5rem'
                    }
                  }
                }
              }
            },
            legend: {
              position: 'bottom',
              labels: {
                colors: legendColor,
                useSeriesColors: false
              }
            }
          }
        },
        {
          breakpoint: 420,
          options: {
            chart: {
              height: 280
            },
            legend: {
              show: false
            }
          }
        },
        {
          breakpoint: 360,
          options: {
            chart: {
              height: 250
            },
            legend: {
              show: false
            }
          }
        }
      ]
    };
  if (typeof rangoEdadesGrafico !== undefined && rangoEdadesGrafico !== null) {
    const rangoEdades = new ApexCharts(rangoEdadesGrafico, rangoEdadesConfig);
    rangoEdades.render();
  }
  // Grafico por edades

  // Grafico por tipos de usuario
  const tiposUsuariosGrafico = document.querySelector('#tiposDeUsuarios'),
  seriesTiposUsuarios = JSON.parse(<?php print json_encode(json_encode($seriesTiposUsuarios)); ?>),
  labelsTiposUsuarios = JSON.parse(<?php print json_encode(json_encode($labelsTiposUsuarios)); ?>),
    tiposUsuariosConfig = {
      chart: {
        height: 390,
        type: 'donut'
      },
      labels: labelsTiposUsuarios,
      series: seriesTiposUsuarios,
      colors: [
        chartColors.donut.series1,
        chartColors.donut.series2,
        chartColors.donut.series3,
        chartColors.donut.series4,
        chartColors.donut.series5,
        chartColors.donut.series6,
        chartColors.donut.series7,
        chartColors.donut.series8,
        chartColors.donut.series9,
        chartColors.donut.series10,
      ],
      stroke: {
        show: false,
        curve: 'straight'
      },
      dataLabels: {
        enabled: true,
        formatter: function (val, opt) {
          return parseInt(val, 10) + '%';
        }
      },
      legend: {
        show: true,
        position: 'bottom',
        markers: { offsetX: -3 },
        itemMargin: {
          vertical: 3,
          horizontal: 10
        },
        labels: {
          colors: legendColor,
          useSeriesColors: false
        }
      },
      plotOptions: {
        pie: {
          donut: {
            labels: {
              show: true,
              name: {
                fontSize: '1.5rem',
                fontFamily: 'Public Sans'
              },
              value: {
                fontSize: '1.2rem',
                color: legendColor,
                fontFamily: 'Public Sans',
                formatter: function (val) {
                  return parseInt(val, 10) + '%';
                }
              }
            }
          }
        }
      },
      responsive: [
        {
          breakpoint: 992,
          options: {
            chart: {
              height: 380
            },
            legend: {
              position: 'bottom',
              labels: {
                colors: legendColor,
                useSeriesColors: false
              }
            }
          }
        },
        {
          breakpoint: 576,
          options: {
            chart: {
              height: 320
            },
            plotOptions: {
              pie: {
                donut: {
                  labels: {
                    show: true,
                    name: {
                      fontSize: '1.5rem'
                    },
                    value: {
                      fontSize: '1rem'
                    },
                    total: {
                      fontSize: '1.5rem'
                    }
                  }
                }
              }
            },
            legend: {
              position: 'bottom',
              labels: {
                colors: legendColor,
                useSeriesColors: false
              }
            }
          }
        },
        {
          breakpoint: 420,
          options: {
            chart: {
              height: 280
            },
            legend: {
              show: false
            }
          }
        },
        {
          breakpoint: 360,
          options: {
            chart: {
              height: 250
            },
            legend: {
              show: false
            }
          }
        }
      ]
    };
  if (typeof tiposUsuariosGrafico !== undefined && tiposUsuariosGrafico !== null) {
    const tiposUsuarios = new ApexCharts(tiposUsuariosGrafico, tiposUsuariosConfig);
    tiposUsuarios.render();
  }
  // Grafico por tipos de usuario

  // Grafico por sexos
  const tiposSexosGrafico = document.querySelector('#tiposDeSexos'),
  seriesTiposSexos = JSON.parse(<?php print json_encode(json_encode($seriesTiposSexos)); ?>),
  labelsTiposSexos = JSON.parse(<?php print json_encode(json_encode($labelsTiposSexos)); ?>),
    tiposSexosConfig = {
      chart: {
        height: 390,
        type: 'donut'
      },
      labels: labelsTiposSexos,
      series: seriesTiposSexos,
      colors: [
        chartColors.sexo.series1,
        chartColors.sexo.series2,
      ],
      stroke: {
        show: false,
        curve: 'straight'
      },
      dataLabels: {
        enabled: true,
        formatter: function (val, opt) {
          return parseInt(val, 10) + '%';
        }
      },
      legend: {
        show: true,
        position: 'bottom',
        markers: { offsetX: -3 },
        itemMargin: {
          vertical: 3,
          horizontal: 10
        },
        labels: {
          colors: legendColor,
          useSeriesColors: false
        }
      },
      plotOptions: {
        pie: {
          donut: {
            labels: {
              show: true,
              name: {
                fontSize: '1.5rem',
                fontFamily: 'Public Sans'
              },
              value: {
                fontSize: '1.2rem',
                color: legendColor,
                fontFamily: 'Public Sans',
                formatter: function (val) {
                  return parseInt(val, 10) + '%';
                }
              }
            }
          }
        }
      },
      responsive: [
        {
          breakpoint: 992,
          options: {
            chart: {
              height: 380
            },
            legend: {
              position: 'bottom',
              labels: {
                colors: legendColor,
                useSeriesColors: false
              }
            }
          }
        },
        {
          breakpoint: 576,
          options: {
            chart: {
              height: 320
            },
            plotOptions: {
              pie: {
                donut: {
                  labels: {
                    show: true,
                    name: {
                      fontSize: '1.5rem'
                    },
                    value: {
                      fontSize: '1rem'
                    },
                    total: {
                      fontSize: '1.5rem'
                    }
                  }
                }
              }
            },
            legend: {
              position: 'bottom',
              labels: {
                colors: legendColor,
                useSeriesColors: false
              }
            }
          }
        },
        {
          breakpoint: 420,
          options: {
            chart: {
              height: 280
            },
            legend: {
              show: false
            }
          }
        },
        {
          breakpoint: 360,
          options: {
            chart: {
              height: 250
            },
            legend: {
              show: false
            }
          }
        }
      ]
    };
  if (typeof tiposSexosGrafico !== undefined && tiposSexosGrafico !== null) {
    const tiposSexos = new ApexCharts(tiposSexosGrafico, tiposSexosConfig);
    tiposSexos.render();
  }
  // Grafico por sexos


</script>

<script>
  $('.confirmacionEliminar').on('click', function () {
    let nombre = $(this).data('nombre');
    let id = $(this).data('id');

    Swal.fire({
      title: "¿Estás seguro que deseas eliminar a <b>"+nombre+"</b>?",
      html: "Esta acción no es reversible.",
      icon: "warning",
      showCancelButton: false,
      confirmButtonText: 'Si, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        $('#eliminarSede').attr('action',"/sede/"+id+"/eliminar");
        $('#eliminarSede').submit();
      }
    })
  });
</script>
@endsection

@section('content')
<div class="d-flex mb-4">
  <div class="p-2 flex-grow-1 bd-highlight">
  </div>
  <div class="flex-shrink-1 ">
    <div class="dropdown d-flex border rounded py-2 px-4 ">
      <button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false">Opciones <i class="ti ti-dots-vertical text-muted"></i></button>
      <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="">Modificar</a></li>

        @if($rolActivo->hasPermissionTo('sedes.opcion_eliminar_sede'))
          <li><a class="dropdown-item confirmacionEliminar" data-nombre="{{ $sede->nombre }}" data-id="{{ $sede->id }}" href="javascript:;">Eliminar</a></li>
        @endif
      </ul>
    </div>
  </div>
</div>

<div class="d-flex flex-column  text-center">
  <div class="mx-auto my-3">
    <img src="{{ $configuracion->version == 1 ? Storage::url($configuracion->ruta_almacenamiento.'/img/foto-sede/'.$sede->foto) : $configuracion->ruta_almacenamiento.'/img/foto-sede/'.$sede->foto }}" alt="foto {{$sede->nombre}}" class="rounded-circle w-px-100" />
  </div>
  <h4 class="mb-1">{{ $sede->nombre }}</h4>
  <p class="mb-4">{{ $sede->tipo ? $sede->tipo->nombre : 'Tipo de sede no indicado' }}</p>
</div>
@include('layouts.status-msn')

  <div id="div-principal" class="row">

    <div class="col-lg-6 col-md-6">

      <!-- Información principal -->
      <div class="card mb-4">
        <div class="card-header align-items-center">
          <p class="card-text text-uppercase fw-bold"> INFORMACIÓN PRINCIPAL </p>
        </div>
        <div class="card-body pb-3">
          <ul class="list-unstyled mb-4 mt-2">

              <li class="d-flex align-items-center mb-1">
                <i class="ti ti-map"></i>
                <span class="fw-medium mx-2 text-heading">Dirección: </span>
                <span>{{ $sede->direccion ? $sede->direccion : 'Sin dato'}}</span>
              </li>

              <li class="d-flex align-items-center mb-1">
                <i class="ti ti-phone"></i>
                <span class="fw-medium mx-2 text-heading">Teléfono: </span>
                <span>{{ $sede->telefono ? $sede->telefono : 'Sin dato'}}</span>
              </li>

              <li class="d-flex align-items-center mb-1">
                <i class="ti ti-confetti"></i>
                <span class="fw-medium mx-2 text-heading">Fecha de creación: </span>
                <span>{{ $sede->fecha_creacion ? $sede->fecha_creacion : 'Sin dato'}}</span>
              </li>

              <li class="d-flex align-items-center mb-1">
                <i class="ti ti-armchair"></i>
                <span class="fw-medium mx-2 text-heading">Capacidad de sillas: </span>
                <span>{{ $sede->capacidad ? $sede->capacidad : 'Sin dato'}}</span>
              </li>

              <li class="d-flex align-items-center mb-1">
                <i class="ti ti-users-group"></i>
                <span class="fw-medium mx-2 text-heading">Cantidad de grupos: </span>
                <span>{{ $sede->grupos()->select('grupos.id')->count() }}</span>
              </li>

              <li class="d-flex align-items-center mb-1">
                <i class="ti ti-users"></i>
                <span class="fw-medium mx-2 text-heading">Cantidad de personas: </span>
                <span>{{ $sede->usuarios()->select('users.id')->count() }}</span>
              </li>

              <li class="d-flex align-items-center mb-1">
                <i class="ti ti-point"></i>
                <span class="fw-medium mx-2 text-heading">Descripción: </span>
                <span>{{ $sede->descripcion ? $sede->descripcion : 'Sin información' }}</span>
              </li>

            </ul>
        </div>
      </div>
      <!--/ Información principal -->

      <!-- Grupo y encargados -->
      <div class="card mb-4">
        <div class="card-header align-items-center">
          <p class="card-text text-uppercase fw-bold"> GRUPO PRINCIPAL Y ENCARGADOS </p>
        </div>
        <div class="card-body pb-3">
          <ul class="list-unstyled mb-0">
            @if($grupoPrincipal)
              <small class="card-text text-uppercase">Grupo principal</small>

              <li class="mb-1 mt-1 p-2 border rounded">
                <div class="d-flex align-items-start">
                  <div class="d-flex align-items-start">
                    <div class="avatar me-2">
                      <i class="ti ti-users-group me-2 fs-1"></i>
                    </div>
                    <div class="me-2 ms-1">
                      <h6 class="mb-0">{{ $grupoPrincipal->nombre }}</h6>
                      <small class="text-muted"><b>Tipo:</b> {{ $grupoPrincipal->tipoGrupo ? $grupoPrincipal->tipoGrupo->nombre : 'Sin información' }}</small>
                    </div>
                  </div>
                  <div class="ms-auto pt-1">
                    @if($rolActivo->hasPermissionTo('grupos.lista_grupos_todos'))
                    <a href="{{ route('grupo.perfil', $grupoPrincipal) }}" target="_blank" class="text-body" data-bs-toggle="tooltip" aria-label="Ver perfil" data-bs-original-title="Ver perfil del grupo">
                      <i class="ti ti-id me-2 ti-sm"></i></a>
                    </a>
                    @endif
                  </div>
                </div>
              </li>
            @endif
          </ul>
          <ul class="list-unstyled mb-0 mt-3">
            @if(count($sede->encargados()))
              <small class="card-text text-uppercase">Encargados</small>
              @foreach($sede->encargados() as $encargado)
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
          </ul>
        </div>
      </div>
      <!--/ Grupo y encargados -->

      <!-- Crecimiento de grupos en el último año -->
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between">
          <div>
            <h6 class="card-title text-uppercase mb-0 fw-bold">Crecimiento de grupos en el último año</h6>
            <small class="text-muted">
             Esta gráfica muestra el crecimiento mensual de los grupos de la sede, durante los últimos 12 meses.
            </small>
          </div>
        </div>
        <div class="card-body">
          <div id="graficoCrecimientoGrupos"></div>

        </div>
      </div>
      <!-- /Crecimiento de grupos en el último año -->

      <!-- Crecimiento de personas en el último año -->
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between">
          <div>
            <h6 class="card-title text-uppercase mb-0 fw-bold">Crecimiento de personas en el último año</h6>
            <small class="text-muted">
              Esta gráfica muestra el crecimiento mensual de las personas de la sede, durante los últimos 12 meses.
            </small>
          </div>
        </div>
        <div class="card-body">
          <div id="graficoCrecimientoPersonas"></div>

        </div>
      </div>
      <!-- /Crecimiento de personas en el último año -->

    </div>

    <div class="col-lg-6 col-md-6">


      <!-- Grafico por edades -->
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between">
          <div>
            <h6 class="card-title text-uppercase mb-0 fw-bold">Personas de la sede por rango de edad</h6>
            <small class="text-muted">
              Esta gráfica muestra la cantidad de personas clasificándola según su edad.
            </small>
          </div>
        </div>
        <div class="card-body">
          <div id="rangoEdades"></div>

          <div class="table-responsive text-nowrap mt-3">
            <table class="table">
              <thead>
                <tr>
                  <th class="fw-bold text-center">Nombre</th>
                  <th class="fw-bold text-center">Rango</th>
                  <th class="fw-bold text-center">Cantidad</th>
                </tr>
              </thead>
              <tbody class="table-border-bottom-0">
                @foreach ($rangoEdades as $rangoEdad)
                <tr>
                  <td class="text-center">{{ $rangoEdad->nombre }}</td>
                  <td class="text-center">{{ $rangoEdad->edad_minima }} a {{ $rangoEdad->edad_maxima }}</td>
                  <td class="text-center">{{ $rangoEdad->cantidad }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>

        </div>
      </div>
      <!-- /Grafico por edades -->

      <!-- Grafico por tipo de usuario -->
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between">
          <div>
            <h6 class="card-title text-uppercase mb-0 fw-bold">Personas de la sede por tipo usuario</h6>
            <small class="text-muted">
               Esta gráfica muestra la cantidad de personas clasificándola según su tipo de usuario.
            </small>
          </div>
        </div>
        <div class="card-body">
          <div id="tiposDeUsuarios"></div>

          <div class="table-responsive text-nowrap mt-3">
            <table class="table">
              <thead>
                <tr>
                <th class="fw-bold text-center">Nombre</th>
                  <th class="fw-bold text-center">Cantidad</th>
                </tr>
              </thead>
              <tbody class="table-border-bottom-0">
                @foreach ($tiposUsuarios as $tipoUsuario)
                <tr>
                  <td class="text-center">{{ $tipoUsuario->nombre }}</td>
                  <td class="text-center">{{ $tipoUsuario->cantidad }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>

        </div>
      </div>
      <!-- /Grafico por tipo de usuario -->

      <!-- Grafico por sexo -->
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between">
          <div>
            <h6 class="card-title text-uppercase mb-0 fw-bold">Personas de la sede por sexo</h6>
            <small class="text-muted">
              Esta gráfica muestra la cantidad de personas clasificándola según su sexo.
            </small>
          </div>
        </div>
        <div class="card-body">
          <div id="tiposDeSexos"></div>

          <div class="table-responsive text-nowrap mt-3">
            <table class="table">
              <thead>
                <tr>
                <th class="fw-bold text-center">Nombre</th>
                  <th class="fw-bold text-center">Cantidad</th>
                </tr>
              </thead>
              <tbody class="table-border-bottom-0">
                @foreach ($tiposDeSexo as $tipoSexo)
                <tr>
                  <td class="text-center">{{ $tipoSexo->nombre }}</td>
                  <td class="text-center">{{ $tipoSexo->cantidad }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>

        </div>
      </div>
      <!-- /Grafico por sexo -->
    </div>

  </div>

  <form id="eliminarSede" method="POST" action="">
    @csrf
  </form>


@endsection
