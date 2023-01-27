@extends('layouts.template_auth')


@section('content')

 <!-- Begin Page Content -->
 <div class="container-fluid">
    <!-- Content Row -->
    <div class="row">
        
        <!-- Creados Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Remisiones x Aprobar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 text-center">{{$creados}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aprobados  Card  -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Remisiones Aprobadas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 text-center">{{$aprobados}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-green-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Articulos  Card  -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Categorías</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 text-center">{{$categorias}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-green-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Articulos  Card  -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Artículos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 text-center">{{$articulos}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-green-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-xl-12 col-lg-10">

            <!-- Bar Chart -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Artículos Dsiponibles Vs En Reparación</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="myBarChart"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
<!-- /.container-fluid -->

@endsection

@section('script')
    <!-- Script para la gráfica de barras Articulos Disponibles/En Reparación -->
    <script>
        
        var cantidad = {!! $cantidad !!};

        var maximo = Math.max.apply(null, cantidad);
        maximo = maximo + 1;
        
        console.log('Máximo ' + maximo);
        var categorias = {!! $labels !!};
        var disponibles = {!! $disponibles !!};
        var reparacion = {!! $reparacion !!};
        var ctx = document.getElementById("myBarChart");

        var myBarChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: categorias,
            display: 'true',
            datasets: [
            {
                label: "Disponibles",
                backgroundColor: "#4e73df",
                hoverBackgroundColor: "#2e59d9",
                borderColor: "#4e73df",
                data: disponibles,
            },
            {
                label: "En Reparación",
                backgroundColor: "#FF0000",
                hoverBackgroundColor: "#F80000",
                borderColor: "#4e73df",
                data: reparacion,
            }],
            
        },
        options: {
            maintainAspectRatio: false,
            layout: {
            padding: {
                left: 10,
                right: 25,
                top: 25,
                bottom: 0
            }
            },
            scales: {
            xAxes: [{
                stacked: true,
                time: {
                unit: 'month'
                },
                gridLines: {
                display: true,
                drawBorder: false
                },
                ticks: {
                maxTicksLimit: 6
                },
                maxBarThickness: 25,
            }],
            yAxes: [{
                stacked: true,
                ticks: {
                min: 0,
                max: maximo,
                maxTicksLimit: 5,
                padding: 10,
                // Include a dollar sign in the ticks
                callback: function(value, index, values) {
                    return  number_format(value);
                }
                },
                gridLines: {
                color: "rgb(234, 236, 244)",
                zeroLineColor: "rgb(234, 236, 244)",
                drawBorder: false,
                borderDash: [2],
                zeroLineBorderDash: [2]
                }
            }],
            },
            legend: {
                display: true
            },
            tooltips: {
            titleMarginBottom: 10,
            titleFontColor: '#6e707e',
            titleFontSize: 14,
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
            callbacks: {
                label: function(tooltipItem, chart) {
                var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
                }
            }
            },
        }
        });
    </script>
@endsection
