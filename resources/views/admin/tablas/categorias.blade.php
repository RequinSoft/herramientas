@extends('layouts.template_admin')

@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">
         <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Artículos x Categoría </h6>
                
            </div>
            @php
                $n=1;
            @endphp
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Categoría</th>
                                <th>Reparaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categorias as $label)
                                <tr>
                                    <td class="text-center font-weight-bold">{{ $n }}</td>
                                    <td>{{ $label->category }}</td>
                                    <td class="text-center">{{ $cantidad[$n-1] }}</td>
                                </tr>
                                @php
                                    $n++;
                                @endphp
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

@endsection