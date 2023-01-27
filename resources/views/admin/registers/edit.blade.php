@extends('layouts.template_admin')

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
                            <h1 class="h4 text-gray-900 mb-4">Editar Remisión</h1>
                        </div>
                        <form class="user" action="{{ route('salidas.actualizar') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <input type="text" class="form-control" id="register_id" name="register_id"
                                value="{{ $registros->id }}" hidden>
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
                                @error('person')
                                    <div class="form-group">
                                        <button type="text" disabled class="btn btn-danger btn-block"
                                            id="error" name="error">
                                            {{$message}}
                                        </button>
                                    </div>
                                @enderror
                                </div>
                                <div class="form-group col-lg-3">
                                    <input type="text" class="form-control" id="vehicle" name="vehicle"
                                    value="{{ $registros->vehicle }}">
                                </div>
                                <div class="form-group col-lg-3">
                                @error('vehicle')
                                    <div class="form-group">
                                        <button type="text" disabled class="btn btn-danger btn-block"
                                            id="error" name="error">
                                            {{$message}}
                                        </button>
                                    </div>
                                @enderror
                                </div>
                                
                                <div class="form-group col-lg-3">
                                    <input type="text" class="form-control" id="placas" name="placas"
                                    value="{{ $registros->placas }}">
                                </div>
                                <div class="form-group col-lg-3">
                                @error('origen_id')
                                    <div class="form-group">
                                        <button type="text" disabled class="btn btn-danger btn-block"
                                            id="error" name="error">
                                            {{$message}}
                                        </button>
                                    </div>
                                @enderror
                                @error('destino_id')
                                    <div class="form-group">
                                        <button type="text" disabled class="btn btn-danger btn-block"
                                            id="error" name="error">
                                            {{$message}}
                                        </button>
                                    </div>
                                @enderror
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
                                                <th>Estatus</th>
                                                <th>Retirar</th>
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
                                                    <td>{{ $articulo->articulos->status }}</td>
                                                    @if ($long == 1)
                                                        <td></td>
                                                    @else
                                                        <td class="text-center"><a href="{{ route('admin.articulo_borrar', [$articulo->article_id, $articulo->id, $articulo->register_id]) }}" class="btn btn-danger btn-sm" title="Inhabilitar"><i class="fas fa-trash"></i></a></td>                                                        
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                    
                                    </table>
                                </div>
                            </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                Actualizar
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