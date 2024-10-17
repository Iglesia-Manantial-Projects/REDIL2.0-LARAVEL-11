@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Peticiones')

<!-- Page -->
@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />

<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>

@endsection

@section('page-script')
<script src="{{asset('assets/js/cards-actions.js')}}"></script>
<script>
   $("#filtroFechas").flatpickr({
    mode: "range",
    dateFormat: "Y-m-d",
    defaultDate: ["{{ $filtroFechaIni }}", "{{ $filtroFechaFin }}"],
    locale: {
      firstDayOfWeek: 1,
      weekdays: {
        shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
        longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
      },
      months: {
        shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Оct', 'Nov', 'Dic'],
        longhand: ['Enero', 'Febreo', 'Мarzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      },
    },
    onChange: function(dates) {
      if (dates.length == 2) {
        var _this = this;
        var dateArr = dates.map(function(date) {
          return _this.formatDate(date, 'Y-m-d');
        });
        $('#filtroFechaIni').val(dateArr[0]);
        $('#filtroFechaFin').val(dateArr[1]);
        // interact with selected dates here
      }
    },
    onReady: function(dateObj, dateStr, instance) {
      var $cal = $(instance.calendarContainer);
      if ($cal.find('.flatpickr-clear').length < 1) {
        $cal.append('<button type="button" class="btn btn-sm btn-outline-primary flatpickr-clear mb-2">Borrar</button>');
        $cal.find('.flatpickr-clear').on('click', function() {
          instance.clear();
          $('#filtroFechaIni').val('');
          $('#filtroFechaFin').val('');
          instance.close();
        });
      }
    }
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

  // Grafico por tipos de peticiones
  const tiposPeticionesGrafico = document.querySelector('#tiposDePeticiones'),
  seriesTiposPeticiones = JSON.parse(<?php print json_encode(json_encode($seriesTiposPeticiones)); ?>),
  labelsTiposPeticiones = JSON.parse(<?php print json_encode(json_encode($labelsTiposPeticiones)); ?>),
    tiposPeticionesConfig = {
      chart: {
        height: 390,
        type: 'donut'
      },
      labels: labelsTiposPeticiones,
      series: seriesTiposPeticiones,
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
                fontSize: '1.8rem',
                fontFamily: 'Public Sans'
              },
              value: {
                fontSize: '1.2rem',
                color: legendColor,
                fontFamily: 'Public Sans',
                formatter: function (val) {
                  return val;
                }
              },
              total: {
                show: true,
                fontSize: '1.2rem',
                color: headingColor,
                label: 'Total',
                formatter: function (w)  {
                  return '{{ $peticiones->total()}}';
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
  if (typeof tiposPeticionesGrafico !== undefined && tiposPeticionesGrafico !== null) {
    const tiposPeticiones = new ApexCharts(tiposPeticionesGrafico, tiposPeticionesConfig);
    tiposPeticiones.render();
  }
  // Grafico por tipos de peticiones


</script>
@endsection

@section('content')
  <h4 class="mb-1">Panel de peticiones</h4>
  <p class="mb-4">Aquí podrás gestionar tus peticiones.</p>

  @include('layouts.status-msn')

  <div class="row mt-5">
    <form class="forms-sample" method="GET" action="{{ route('peticion.panel') }}">
    <div class="row m-0 p-0">

      <!-- Por rango de fechas  -->
      <div class="col-12 col-md-12 mb-2">
        <div class="input-group input-group-merge">
          <span class="input-group-text"><i class="ti ti-calendar"></i></span>
          <input type="text" id="filtroFechaIni" name="filtroFechaIni" value="{{ $filtroFechaIni }}" class="form-control d-none" placeholder="">
          <input type="text" id="filtroFechaFin" name="filtroFechaFin" value="{{ $filtroFechaFin }}" class="form-control d-none" placeholder="">
          <input id="filtroFechas" name="filtroFechas" type="text" class="form-control" placeholder="YYYY-MM-DD a YYYY-MM-DD" />
        </div>
      </div>

    </div>
    </form>
    @if($peticiones)
      <span class="text-center py-3">{{ $peticiones->total() > 1 ? $peticiones->total().' Peticiones' : $peticiones->total().' Petición' }} {!! $textoBusqueda ? '('.$textoBusqueda.')' : '' !!}</span>
    @endif
  </div>

  <div class="row mb-3">
    <!-- Indicadores -->
    <div class="col-lg-12 col-md-12 mb-3">
      <div class="card h-100">
        <div class="card-header d-flex justify-content-between">
          <h6 class="card-title text-uppercase mb-0 fw-bold">Indicadores</h6>
        </div>
        <div class="card-body">
          <div class="row gy-3">
            @foreach( $indicadores as $indicador )
            <div class="{{$indicador->col}}">
              <div class="d-flex align-items-center">
                <div class="badge rounded {{ $indicador->color}}  me-4 p-2"><i class="{{ $indicador->icono}} ti-lg"></i></div>
                <div class="card-info">
                  <h5 class="mb-0">{{ $indicador->cantidad }}</h5>
                  <small>{{ $indicador->nombre }}</small>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
    <!--/ Indicadores -->


    <!-- Por paises -->
    <div class="col-md-4 mb-3">
      <div class="card h-100">
        <div class="card-header d-flex justify-content-between">
          <h6 class="card-title text-uppercase mb-0 fw-bold">Paises</h6>
        </div>
        <div class="card-body">
          @foreach($paises as $pais)
          <div class="card card-action mb-2">
            <div class="card-header">
              <div class="card-action-title">
              <ul class="p-0 m-0">
                <li class="d-flex align-items-center mb-0">
                  <div class="avatar flex-shrink-0 me-2">
                    <i class="fis fi fi-{{ strtolower($pais->codigo_alpha) }} rounded-circle fs-2"></i>
                  </div>
                  <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                    <div class="me-2">
                      <h5 class="mb-1 fw-bold">{{$pais->nombre}} </h5>
                      <p class="card-subtitle">Total: {{ $pais->cantidad}}</p>
                    </div>
                  </div>
                </li>
              </ul>
              </div>
              <div class="card-action-element">
                <ul class="list-inline mb-0">
                  <li class="list-inline-item">
                    <a href="javascript:void(0);" class="card-collapsible"><i class="tf-icons ti ti-chevron-right scaleX-n1-rtl ti-sm"></i></a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="collapse">
              <div class="card-body pt-0 px-2 pb-2">
                <ul class="list-unstyled mb-0">
                  @foreach($pais->tipos as $tipo)
                    <li class="mb-1 mt-1 p-2 border rounded">
                      <div class="d-flex align-items-start">
                        <div class="d-flex align-items-start">
                          <div class="me-2 ms-1">
                            <h6 class="mb-0">{{ $tipo->cantidad}}</h6>
                            <small class="text-muted">{{ $tipo->nombre }} </small>
                          </div>
                        </div>

                        <div class="ms-auto pt-1">
                          <a href="https://redilbeta.ubicalo.com/usuario/3/perfil" target="_blank" class="text-body" data-bs-toggle="tooltip" aria-label="Ver perfil" data-bs-original-title="Ver perfil">
                          <i class="ti ti-user-check me-2 ti-sm"></i></a>
                        </div>
                      </div>
                    </li>
                  @endforeach
                </ul>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
    <!--/ Por paises -->

    <div class="col-md-4 mb-3" >

      <!-- Grafico por tipo de usuario -->
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between">
          <div>
            <h6 class="card-title text-uppercase mb-0 fw-bold">Gráfico por tipo de peticiones</h6>
          </div>
        </div>
        <div class="card-body">
          <div id="tiposDePeticiones"></div>

          <div class="table-responsive text-nowrap mt-3">
            <table class="table">
              <thead>
                <tr>
                <th class="fw-bold text-center">Nombre</th>
                  <th class="fw-bold text-center">Cantidad</th>
                </tr>
              </thead>
              <tbody class="table-border-bottom-0">
                @foreach ($tiposPeticiones as $tipoPenticion)
                <tr>
                  <td class="text-center">{{ $tipoPenticion->nombre }}</td>
                  <td class="text-center">{{ $tipoPenticion->cantidad }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>

        </div>
      </div>
      <!-- /Grafico por tipo de usuario -->

    </div>


  </div>

@endsection
