@extends('layouts.template_val')

@section('title', 'Remmas')
@section('company', 'Remmas')

@section('content')

 <!-- Begin Page Content -->
 <div class="container-fluid">

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Remisiones
            </h6>
            
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Persona</th>
                            <th>Vehículo</th>
                            <th>Placas</th>
                            <th>Grupo</th>
                            <th>Creado Por</th>
                            <th>Modificado Por</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($registros as $row)
                            <tr>
                                <td class="text-center">{{ $row->id }}</td>
                                <td>{{ $row->person }}</td>
                                <td>{{ $row->vehicle }}</td>
                                <td>{{ $row->placas }}</td>
                                <td>{{ $row->group->group }}</td>
                                <td>{{ $row->user->name }}</td>
                                <td>{{ $row->user2->name }}</td>
                                <td>{{ $row->status }}</td>
                                <td style="display: flex">
                                    <a href="{{ route('salidas.mostrar_autorizados_val', $row->id) }}" class="btn btn-warning btn-sm" title="Editar"><i class="fas fa-eye"></i></a>
                                    &nbsp;
                                    <a href="{{ route('salidas.inactivar.val', $row->id) }}" class="btn btn-danger btn-sm" title="Inhabilitar"><i class="fas fa-trash-alt"></i></a>
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

@section('script')
    @if (session('info'))
        <script>
            Swal.fire({
                title: "Remisión Creada",
                text: "Folio {{ session('info') }}",
                confirmButtonText: "Aceptar",
            });
        </script>
    @endif

    @if (session('info_actualizado'))
        <script>
            Swal.fire({
                title: "Remisión Revisada",
                text: "Folio {{ session('info_actualizado') }}",
                confirmButtonText: "Aceptar",
            });
        </script>
    @endif

    @if (session('info_cancelado'))
        <script>
            Swal.fire({
                title: "Remisión Cancelada",
                text: "Folio {{ session('info_cancelado') }}",
                confirmButtonText: "Aceptar",
            });
        </script>
    @endif
@endsection