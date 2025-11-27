@extends('layouts.template_admin')

@section('title', 'Proveedores')

@section('content')

 <!-- Begin Page Content -->
 <div class="container-fluid">

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Proveedores
                &nbsp;
                &nbsp;
                <a class="btn btn-primary btn-circle" href="{{ route('proveedores.nuevo') }}" >
                    <i class="fas fa-plus"></i>
                </a>
            </h6>
            
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Proveedor</th>
                            <th>Contacto</th>
                            <th>E-mail</th>
                            <th>Dirección</th>
                            <th>Telefono</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datos as $row)
                            <tr>
                                <td>{{ $row->proveedor }}</td>
                                <td>{{ $row->contacto }}</td>
                                <td>{{ $row->email }}</td>
                                <td>{{ $row->direccion }}</td>
                                <td>{{ $row->telefono }}</td>
                                <td style="display: flex">
                                    <a href="{{ route('proveedores.editar', $row->id) }}" class="btn btn-success btn-sm" title="Editar"><i class="fas fa-pen"></i></a>
                                    &nbsp;
                                    <a href="{{ route('proveedores.inactivar', $row->id) }}" class="btn btn-danger btn-sm" title="Inhabilitar"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
@endsection