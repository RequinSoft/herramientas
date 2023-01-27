@extends('layouts.template_val')


@section('content')

 <!-- Begin Page Content -->
 <div class="container-fluid">

    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <div class="col-lg-12 d-none d-lg-block row"></div>
                <div class="col-lg-12">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Remisión F-{{ $registros->id }} </h1>
                        </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="user_id" name="user_id"
                                value="{{auth()->user()->id}}" hidden>
                                <input type="text" class="form-control" id="action_id" name="action_id"
                                value="{{auth()->user()->id}}" hidden>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="group_id" name="group_id"
                                    value="{{auth()->user()->group_id}}" hidden>
                                </div>
                            </div>


                            <div class="form-group row">
                                <div class="form-group col-lg-3 text_right">
                                    <label for=""><b>Persona</b></label>
                                </div>
                                <div class="form-group col-lg-3">
                                    <label for="">{{ $registros->person }} </label>
                                </div>
                                <div class="form-group col-lg-3">
                                    <label for=""><b>Vehículo</b></label>
                                </div>
                                <div class="form-group col-lg-3">
                                    <label for="">{{ $registros->vehicle }} </label>
                                </div>
                            </div> 
                            
                            <div class="form-group row">
                                <div class="form-group col-lg-3">
                                    <label for=""><b>Placas</b></label>
                                </div>
                                <div class="form-group col-lg-3">
                                    <label for="">{{ $registros->placas }} </label>
                                </div>
                                <div class="form-group col-lg-3">
                                    <label for=""><b>Elaboró</b></label>
                                </div>
                                <div class="form-group col-lg-3">
                                    @foreach ($registros2 as $item)
                                        <label for="">{{ $item->user->name }} </label>
                                    @endforeach
                                    
                                </div>
                            </div> 
                            
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Articulo</th>
                                                <th>N/S</th>
                                                <th>Marca</th>
                                                <th>Modelo</th>
                                                <th>Comentario</th>
                                                <th>Estatus</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($lineas as $articulo)
                                                <tr>
                                                    <td class="text-center">{{ $articulo->id }}</td>
                                                    <td>{{ $articulo->articulos->article }}</td>
                                                    <td>{{ $articulo->articulos->ns }}</td>
                                                    <td>{{ $articulo->articulos->marca }}</td>
                                                    <td>{{ $articulo->articulos->modelo }}</td>
                                                    <td>{{ $articulo->articulos->comentario1 }}</td>
                                                    <td>{{ $articulo->articulos->status }}</td>
                                                </tr>
                                                
                                            @endforeach
                                        </tbody>
                    
                                    </table>
                                </div>
                            </div>

                            <a class="btn btn-primary btn-user btn-block" href="{{ route('salidas.revisar.val', $registros->id) }}">
                                Aprobar
                            </a> 
                            <a class="btn btn-danger btn-user btn-block" href="{{ route('salidas.inactivar.val', $registros->id) }}">
                                Eliminar
                            </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<!-- /.container-fluid -->
@endsection