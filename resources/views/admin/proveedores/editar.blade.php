@extends('layouts.template_admin')

@section('title', 'Proveedores')

@section('content')

 <!-- Begin Page Content -->
 <div class="container-fluid">

    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <div class="col-lg-5 d-none d-lg-block bg-place-image"></div>
                <div class="col-lg-7">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Editar Proveedor</h1>
                        </div>
                        <form class="user" action="{{ route('proveedores.actualizar')}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <input type="text" class="form-control" id="id" name="id"
                                value="{{ $datos->id }}" hidden>
                            </div>

                            <div class="form-group">
                                    <input type="text" class="form-control" id="proveedor" name="proveedor"
                                    value="{{ $datos->proveedor }}">
                            </div>
                            <div class="form-group">
                            @error('proveedor')
                                <div class="form-group">
                                    <button type="text" disabled class="btn btn-danger btn-block"
                                        id="error" name="error">
                                        {{$message}}
                                    </button>
                                </div>
                            @enderror
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control" id="contacto" name="contacto"
                                value="{{ $datos->contacto }}">
                            </div>
                            <div class="form-group">
                            @error('contacto')
                                <div class="form-group">
                                    <button type="text" disabled class="btn btn-danger btn-block"
                                        id="error" name="error">
                                        {{$message}}
                                    </button>
                                </div>
                            @enderror
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control" id="email" name="email"
                                value="{{ $datos->email }}">
                            </div>
                            <div class="form-group">
                            @error('email')
                                <div class="form-group">
                                    <button type="text" disabled class="btn btn-danger btn-block"
                                        id="error" name="error">
                                        {{$message}}
                                    </button>
                                </div>
                            @enderror
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control" id="direccion" name="direccion"
                                value="{{ $datos->direccion }}">
                            </div>
                            <div class="form-group">
                            @error('direccion')
                                <div class="form-group">
                                    <button type="text" disabled class="btn btn-danger btn-block"
                                        id="error" name="error">
                                        {{$message}}
                                    </button>
                                </div>
                            @enderror
                            </div>

                            <div class="form-group">
                                <input type="number"  class="form-control" id="telefono" name="telefono"
                                value="{{ $datos->telefono }}">
                            </div>
                            <div class="form-group">
                            @error('telefono')
                                <div class="form-group">
                                    <button type="text" disabled class="btn btn-danger btn-block"
                                        id="error" name="error">
                                        {{$message}}
                                    </button>
                                </div>
                            @enderror
                            </div>

                            <button type="submit" class="btn btn-info btn-user btn-block">
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