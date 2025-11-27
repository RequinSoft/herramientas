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
                            <h1 class="h4 text-gray-900 mb-4">Escoger Artículos</h1>
                        </div>
                            @csrf
                            <div class="form-group">
                                <input type="text" class="form-control" id="user_id" name="user_id"
                                value="{{auth()->user()->id}}" hidden>
                                <input type="text" class="form-control" id="action_id" name="action_id"
                                value="{{auth()->user()->id}}" hidden>
                            </div>
                            <div class="form-group col-lg-3">
                            @error('articulos')
                                <div class="form-group">
                                    <button type="text" disabled class="btn btn-danger btn-block"
                                        id="error" name="error">
                                        {{$message}}
                                    </button>
                                </div>
                            @enderror
                            </div>

                            @php
                                $n = 1;
                            @endphp
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Folio</th>
                                                <th>Articulo</th>
                                                <th>N/S</th>
                                                <th>Marca</th>
                                                <th>Modelo</th>
                                                <th>Comentario</th>
                                                <th>Reparaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($articulos as $articulo)
                                                <tr>
                                                    <td class="text-center">{{ $n }}</td>
                                                    <td>{{ $articulo->article }}</td>
                                                    <td>{{ $articulo->ns }}</td>
                                                    <td>{{ $articulo->marca }}</td>
                                                    <td>{{ $articulo->modelo }}</td>
                                                    <td>{{ $articulo->comentario }}</td>
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

                                
                            <a href="{{ route('admin.index') }}" class="btn btn-info btn-user btn-block">
                                Regresar
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<!-- /.container-fluid -->
@endsection