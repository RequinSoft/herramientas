@extends('layouts.template_admin')

@section('title', 'Cambiar Contraseña')

@section('content')
<div class="card mb-3">
    <div class="card-header">
      <h5 class="mb-0">Editar Artículos</h5>
    </div>
    <div class="card-body bg-light">
        <form  action="{{ route('admin.actualizar_pass') }}" method="POST">
        @csrf
            <div class="row gx-2">  
                <div class="col-sm-3 mb-3">
                @error('password')
                    <button type="text" disabled class="btn btn-danger btn-block" id="error" name="error">
                        {{$message}}
                    </button> 
                @enderror
                @error('password1')
                    <button type="text" disabled class="btn btn-danger btn-block" id="error" name="error">
                        {{$message}}
                    </button> 
                @enderror
                </div>           
                <div class="col-sm-5 mb-3 text-end">
                    <input type="text" class="form-control" id="id" name="id" value="{{ $usuarios->id }}" hidden>
                    <label>{{ $usuarios->name }}</label>
                </div> 

                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="event-venue">Contraseña</label>
                    <input class="form-control" id="password" name="password" type="password"/>
                </div>
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="event-venue">Validar Contraseña</label>
                    <input class="form-control" id="password1" name="password1" type="password"/>
                </div>
                            
                <button type="submit" class="btn btn-primary btn-user btn-block">
                    Actualizar Contraseña
                </button>
            </div>
        </form>
    </div>
</div>
@endsection