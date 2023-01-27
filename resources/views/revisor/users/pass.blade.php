@extends('layouts.template_revisor')

@section('title', 'Recuperar Contraseña')

@section('content')

<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-xl-10 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-2">{{ $usuarios->name }}</h1>
                                    <p class="mb-4"> </p>
                                </div>
                                <form class="user" action="{{ route('usuarios.resetear_rev') }}" method="POST">
                                    @csrf
                                    
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="id" name="id"
                                        value="{{ $usuarios->id }}" hidden>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="nombre" name="nombre"
                                        value="{{ $usuarios->name }}" hidden>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Contraseña" value="{{ old('password') }}">
                                    </div>
                                    <div class="form-group">
                                    @error('password')
                                        <div class="form-group">
                                            <button type="text" disabled class="btn btn-danger btn-block"
                                                id="error" name="error">
                                                {{$message}}
                                            </button>
                                        </div>
                                    @enderror
                                    </div>
        
                                    <div class="form-group">
                                        <input type="password" class="form-control" id="pass2" name="pass2"
                                        placeholder="Validar  Contraseña"  value="{{ old('pass2') }}">
                                    </div>
                                    <div class="form-group">
                                    @error('pass2')
                                        <div class="form-group">
                                            <button type="text" disabled class="btn btn-danger btn-block"
                                                id="error" name="error">
                                                {{$message}}
                                            </button>
                                        </div>
                                    @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Cambiar Contraseña
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection