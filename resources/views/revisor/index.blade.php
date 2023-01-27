@extends('layouts.template_revisor')


@section('content')

 <!-- Begin Page Content -->
 <div class="container-fluid">
    <!-- Content Row -->
    <div class="row">
        

        <!-- Aprobados  Card  -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Remisiones x Revisar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 text-center">{{$aprobados}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-green-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
<!-- /.container-fluid -->

@endsection