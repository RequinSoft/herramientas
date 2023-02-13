@extends('layouts.template_admin')

@section('title', 'TG - Dashboards')

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

    <!-- Artículos Disponibles x Categoría -->
    <div class="col-md-12 col-xxl-12">
      <div id="containerArticulosxcategoria" class="card-body">
      </div>
    </div>
    <!-- Artículos Disponibles x Categoría -->

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
  <!-- Artículos Disponibles x Categoría -->
  <script>
    var dataDisponiblesxCategoria = @json($articulosDisponiblexCategoria);
    var cat = @json($categorias);
    var categorias = [];
    console.log(cat);
    console.log(cat.length);

    for(var i=0; i<cat.length; i++){
      categorias  = cat[i];
      console.log(cat[i]);
    }
    
    console.log("Categorías -> " + cat);
    Highcharts.chart('containerArticulosxcategoria', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Artículos'
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
        series: [{
            name: 'Disponibles',
            data: dataDisponiblesxCategoria

        }]
    });
  </script>
  <!-- Artículos Disponibles x Categoría -->

  <!-- Artículos Asignados, Robados & Extraviados en Cantidad -->
  <script>
    var dataAsignados = @json($articulosAsignadosxMes);
    var dataRobados = @json($articulosRobadosxMes);
    var dataExtraviados = @json($articulosExtraviadosxMes);
    console.log(dataAsignados);
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
        series: [{
            name: 'Asignados',
            data: dataAsignados

        },{
            name: 'Robados',
            data: dataRobados

        }, {
            name: 'Extraviados',
            data: dataExtraviados

        }]
    });
  </script>
  <!-- Artículos Asignados, Robados & Extraviados en Cantidad -->


  <!-- Artículos Robados y Extraviados en Dinero -->
  <script>
    var dataRobados_Moneda = @json($articulosRobadosxMes_Moneda);
    var dataExtraviados_Moneda = @json($articulosExtraviadosxMes_Moneda);
    console.log(dataExtraviados_Moneda);


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
  <!-- Artículos Robados y Extraviados en Dinero -->
@endsection

@section('css')
@endsection