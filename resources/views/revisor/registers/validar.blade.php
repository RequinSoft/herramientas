@extends('layouts.template_revisor')

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
                            <h1 class="h4 text-gray-900 mb-4">Validar Remisión {{ $registros->id }}</h1>
                        </div>
                        <form class="user" action="{{ route('salidas.revisar.rev', $registros->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <input type="text" class="form-control" id="register_id" name="register_id"
                                value="{{ $id }}" hidden>
                                <input type="text" class="form-control" id="user_id" name="user_id"
                                value="{{auth()->user()->id}}" hidden>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="group_id" name="group_id"
                                    value="{{auth()->user()->group_id}}" hidden>
                                </div>
                            </div>


                            <div class="form-group row">
                                <div class="form-group col-lg-3">
                                    <input type="text" class="form-control" id="person" name="person"
                                    value="{{ $registros->person }}">
                                </div>
                                <div class="form-group col-lg-3">
                                    <input type="text" class="form-control" id="vehicle" name="vehicle"
                                    value="{{ $registros->vehicle }}">
                                </div>
                                <div class="form-group col-lg-3">
                                    <input type="text" class="form-control" id="placas" name="placas"
                                    value="{{ $registros->placas }}">
                                </div>
                                
                                <div class="form-group col-lg-3">
                                    @foreach ($proveedor as $proveedor)
                                        <input type="text" class="form-control" id="proveedor_id" name="proveedor_id"
                                        value="{{ $proveedor->proveedor }}">
                                    @endforeach
                                </div>
                                
                            </div>
                            <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Articulo</th>
                                                <th>N/S</th>
                                                <th>Marca</th>
                                                <th>Modelo</th>
                                                <th>Comentario</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($lineas as $articulo)
                                                <tr>
                                                    <td>{{ $articulo->articulos->article }}</td>
                                                    <td>{{ $articulo->articulos->ns }}</td>
                                                    <td>{{ $articulo->articulos->marca }}</td>
                                                    <td>{{ $articulo->articulos->modelo }}</td>
                                                    <td>{{ $articulo->articulos->comentario1 }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                    
                                    </table>
                                </div>
                            </div>
                            </div>
                            
                                
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                Enviar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<!-- /.container-fluid -->
@endsection