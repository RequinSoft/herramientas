@extends('layouts.template_coadmin')

@section('title', 'Dashboards')

@section('content')
  <!-- Contenido -->
  <div class="row g-3 mb-3">

    <!-- Valor del Inventario -->
    <div class="col-md-6 col-xxl-6">
      <div class="card h-md-100 ecommerce-card-min-width">
        <div class="card-header pb-0">
          <h6 class="mb-0 mt-2 d-flex align-items-center">Valor del Inventario<span class="ms-1 text-400" data-bs-toggle="tooltip" data-bs-placement="top" title="Depreciación aplicada"><span class="far fa-question-circle" data-fa-transform="shrink-1"></span></span></h6>
        </div>
        <div class="card-body d-flex flex-column justify-content-end">
          <div class="row">
            <div class="col">
              <p class="font-sans-serif lh-1 mb-1 fs-4">$ {{ number_format($valorInventario, 0, ".", ",") }} </p>
            </div>
            <div class="col-auto ps-0">
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Valor del Inventario -->

    <!-- Pérdidas este Mes -->
    <div class="col-md-6 col-xxl-6">
      <div class="card h-md-100">
        <div class="card-header pb-0">
          <h6 class="mb-0 mt-2">Pérdidas este Mes</h6>
        </div>
        <div class="card-body d-flex flex-column justify-content-end">
          <div class="row justify-content-between">
            <div class="col-auto align-self-end">
              <div class="fs-4 fw-normal font-sans-serif text-700 lh-1 mb-1">$ {{ number_format($articuloRobado, 0, ".", ",") }}</div>
            </div>
            <div class="col-auto ps-0 mt-n4">
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Pérdidas este Mes -->
  </div>

  <!-- Gráficos Artículos -->
  <div class="row g-3 mb-3">

    <!-- Artículos Totales Disponibles x Categoría en Cantidad -->
    <div class="col-md-6 col-xxl-6">
      <div id="containerTotalesxcategoriaCantidad" class="card-body">
      </div>
    </div>
    <!-- Artículos Totales Disponibles x Categoría en Cantidad  -->

    <!-- Artículos Totales Disponibles x Categoría en Dinero -->
    <div class="col-md-6 col-xxl-6">
      <div id="containerTotalesxcategoriaDinero" class="card-body">
      </div>
    </div>
    <!-- Artículos Totales Disponibles x Categoría en Dinero  -->

    <!-- Artículos Disponibles x Categoría -->
    <div class="col-md-12 col-xxl-12">
      <div id="containerArticulosxcategoria" class="card-body">
      </div>
    </div>
    <!-- Artículos Disponibles x Categoría -->

    <!-- Artículos Asignados x Categoría -->
    <div class="col-md-12 col-xxl-12">
      <div id="containerAsignadosxcategoria" class="card-body">
      </div>
    </div>
    <!-- Artículos Asignados x Categoría -->

    <!-- Artículos Disponibles y Asignados en Cantidad -->
    <div class="col-md-12 col-xxl-12">
      <div id="containerArticulos" class="card-body">
      </div>
    </div>
    <!-- Artículos Disponibles y Asignados en Cantidad -->
    
    <!-- Artículos Disponibles y Asignados en Moneda -->
    <div class="col-md-12 col-xxl-12">
      <div id="containerArticulosMonetizados" class="card-body">
      </div>
    </div>
    <!-- Artículos Disponibles y Asignados en Moneda -->

  </div>
  <!-- Gráficos Artículos -->
  

  

  <!-- Contenido -->
@endsection
@section('script')
@if (session('resguardo_add'))
  <script>
      Swal.fire({
          title: "Artículos Asignados a:",
          text: "{{ session('resguardo_add') }}",
          confirmButtonText: "Aceptar",
      });
  </script>
@endif

  <!-- Artículos Disponibles x Categoría -->
  <script>
    var dataDisponiblesxCategoria = @json($articulosDisponiblexCategoria);
    var cat = @json($categorias);
    var categorias = [];
    //console.log(cat);
    //console.log(cat.length);

    for(var i=0; i<cat.length; i++){
      categorias  = cat[i];
      //console.log(cat[i]);
    }
    
    //console.log("Categorías -> " + cat);
    Highcharts.chart('containerArticulosxcategoria', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Disponibilidad'
        },
        xAxis: {
            categories: cat,
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Cantidad'
            }
        },
        plotOptions: {
            column: {              
                dataLabels: {
                  enabled: true,
                  format: '{y}'
                },
                pointPadding: 0.2,
                borderWidth: 1
            }
        },
        series: [{
            name: 'Disponibles',
            data: dataDisponiblesxCategoria

        }]
    });
  </script>
  <!-- Artículos Disponibles x Categoría -->
  
  <!-- Artículos Asignados x Categoría -->
  <script>
    var dataAsignadoxCategoria = @json($articulosAsignadoxCategoria);
    var cat = @json($categorias);
    var categorias = [];
    //console.log(dataAsignadoxCategoria);
    //console.log(cat.length);

    for(var i=0; i<cat.length; i++){
      categorias  = cat[i];
      //console.log(cat[i]);
    }
    
    //console.log("Categorías -> " + cat);
    Highcharts.chart('containerAsignadosxcategoria', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Asignados'
        },
        xAxis: {
            categories: cat,
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Cantidad'
            }
        },
        plotOptions: {
            column: {
                dataLabels: {
                  enabled: true,
                  format: '{y}'
                },
                pointPadding: 0.2,
                borderWidth: 1
            }
        },
        series: [{
            name: 'Asignados',
            data: dataAsignadoxCategoria

        }]
    });
  </script>
  <!-- Artículos Asignados x Categoría -->

  <!-- Test Totales x Categoria en Cantidad -->
  <script>
    var dataPie = [];
    var totalArticulos = @json($totalArticulos);
    //console.log("Total de Artículos " + totalArticulos);
    for(var i=0; i<cat.length; i++){
      dataPie[i] = "{name: " + cat[i] + ", y: " + totalArticulos[i] + "},";
    }
    //console.log(cat);
    //console.log(dataPie);

    Highcharts.chart('containerTotalesxcategoriaCantidad', {
      colors: ['#ff6961', '#77dd77', '#fdfd96', '#84b6f4', '#fdcae1', '#fcb7af', '#b0c2f2', '#fdf9c4', '#ffda9e', '#c5c6c8', '#b2e2f2'],
      chart: {
        type: 'pie'
      },
      accessibility: {
        point: {
            valueSuffix: '%'
        }
      },
      title: {
        text: 'Cantidad'
      },
      tooltip: {
        pointFormat: '{point.percentage:.0f}%</b>'
      },
      plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true
            },
            showInLegend: false
        }
      },
      series: [{
        name: null,
        colorByPoint: true,
        data: [
          {name: cat[0], y: totalArticulos[0]},
          {name: cat[1], y: totalArticulos[1]},
          {name: cat[2], y: totalArticulos[2]},
          {name: cat[3], y: totalArticulos[3]},
          {name: cat[4], y: totalArticulos[4]},
          {name: cat[5], y: totalArticulos[5]},
          {name: cat[6], y: totalArticulos[6]},
          {name: cat[7], y: totalArticulos[7]},
          {name: cat[8], y: totalArticulos[8]},
          {name: cat[9], y: totalArticulos[9]},
          {name: cat[10], y: totalArticulos[10]},
          {name: cat[11], y: totalArticulos[11]},
          {name: cat[12], y: totalArticulos[12]},
          {name: cat[13], y: totalArticulos[13]},
          {name: cat[14], y: totalArticulos[14]},
          {name: cat[15], y: totalArticulos[15]},
          {name: cat[16], y: totalArticulos[16]},
          {name: cat[17], y: totalArticulos[17]},
          {name: cat[18], y: totalArticulos[18]},
          {name: cat[19], y: totalArticulos[19]},
        ]
        
      }]
    });
  </script>
  <!-- Test Totales x Categoria en Cantidad -->

  <!-- Test Totales x Categoria en Dinero -->
  <script>
    var dataPie = [];
    var totalArticulos = @json($totalArticulos);
    var totalArticulosDinero = @json($totalArticulosDinero);
    //console.log("Total de Artículos " + totalArticulos);
    for(var i=0; i<cat.length; i++){
      dataPie[i] = "{name: " + cat[i] + ", y: " + totalArticulos[i] + "},";
    }
    //console.log("Categorías--> " + cat);
    //console.log("Total Artículos Cantidad --> " + totalArticulos);
    //console.log("Total Artículos Dinero --> " + totalArticulosDinero);
    //console.log(dataPie);

    Highcharts.chart('containerTotalesxcategoriaDinero', {
      colors: ['#ff6961', '#77dd77', '#fdfd96', '#84b6f4', '#fdcae1', '#fcb7af', '#b0c2f2', '#fdf9c4', '#ffda9e', '#c5c6c8', '#b2e2f2'],
      chart: {
        type: 'pie'
      },
      accessibility: {
        point: {
            valueSuffix: '%'
        }
      },
      title: {
        text: 'Dinero'
      },
      tooltip: {
        pointFormat: '{point.percentage:.0f}%</n>'
      },
      plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true
            },
            showInLegend: false
        }
      },
      series: [{
        name: null,
        colorByPoint: true,
        data: [
          {name: cat[0], y: totalArticulosDinero[0]},
          {name: cat[1], y: totalArticulosDinero[1]},
          {name: cat[2], y: totalArticulosDinero[2]},
          {name: cat[3], y: totalArticulosDinero[3]},
          {name: cat[4], y: totalArticulosDinero[4]},
          {name: cat[5], y: totalArticulosDinero[5]},
          {name: cat[6], y: totalArticulosDinero[6]},
          {name: cat[7], y: totalArticulosDinero[7]},
          {name: cat[8], y: totalArticulosDinero[8]},
          {name: cat[9], y: totalArticulosDinero[9]},
          {name: cat[10], y: totalArticulosDinero[10]},
          {name: cat[11], y: totalArticulosDinero[11]},
          {name: cat[12], y: totalArticulosDinero[12]},
          {name: cat[13], y: totalArticulosDinero[13]},
          {name: cat[14], y: totalArticulosDinero[14]},
          {name: cat[15], y: totalArticulosDinero[15]},
          {name: cat[16], y: totalArticulosDinero[16]},
          {name: cat[17], y: totalArticulosDinero[17]},
          {name: cat[18], y: totalArticulosDinero[18]},
          {name: cat[19], y: totalArticulosDinero[19]},
        ]
        
      }]
    });
  </script>
  <!-- Test Totales x Categoria en Dinero -->

  <!-- Artículos Asignados, Robados & Extraviados en Cantidad -->
  <script>
    var dataAsignados = @json($articulosAsignadosxMes);
    var dataRobados = @json($articulosRobadosxMes);
    var dataExtraviados = @json($articulosExtraviadosxMes);
    //console.log(dataAsignados);
    Highcharts.chart('containerArticulos', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Artículos'
        },
        xAxis: {
            categories: [
                'Ene',
                'Feb',
                'Mar',
                'Abr',
                'May',
                'Jun',
                'Jul',
                'Ago',
                'Sep',
                'Oct',
                'Nov',
                'Dic'
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Cantidad'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:5px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f} piezas</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 1
            }
        },
        series: [
          /*{
            name: 'Asignados',
            data: dataAsignados

        },*/{
            name: 'Robados',
            data: dataRobados

        }, {
            name: 'Extraviados',
            data: dataExtraviados

        }]
    });
  </script>
  <!-- Artículos Asignados, Robados & Extraviados en Cantidad -->


  <!-- Artículos Robados y Extraviados en Dinero 
  <script>
    var dataRobados_Moneda = @json($articulosRobadosxMes_Moneda);
    var dataExtraviados_Moneda = @json($articulosExtraviadosxMes_Moneda);
    //console.log(dataExtraviados_Moneda);


    Highcharts.chart('containerArticulosMonetizados', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Artículos Monetizados'
        },
        xAxis: {
            categories: [
                'Ene',
                'Feb',
                'Mar',
                'Abr',
                'May',
                'Jun',
                'Jul',
                'Ago',
                'Sep',
                'Oct',
                'Nov',
                'Dic'
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: '$'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:5px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 1
            }
        },
        series: [{
            name: 'Robados',
            data: dataRobados_Moneda

        }, {
            name: 'Extraviados',
            data: dataExtraviados_Moneda

        }]
    });
  </script>
  Artículos Robados y Extraviados en Dinero -->
@endsection

@section('css')
@endsection