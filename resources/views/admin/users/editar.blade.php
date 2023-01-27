@extends('layouts.template_admin')

@section('title', 'TG - Editar Usuario')

@section('content')
<div class="card mb-3">
    <div class="card-header">
      <h5 class="mb-0">Editar Usuario</h5>
    </div>
    <div class="card-body bg-light">
        <form  action="{{ route('usuarios.actualizar') }}" method="POST" enctype="multipart/form-data">
        @csrf
            <div class="row gx-2">             
                <div class="col-sm-6 mb-3">
                    <input type="text" class="form-control" id="id" name="id"
                    value="{{ $usuarios->id }}" hidden>
                </div> 
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="event-venue">Foto de Perfil</label>
                    <input class="form-control"  type="file" name="image" id="image" />
                </div> 

                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="event-venue">Usuario</label>
                    <input class="form-control" id="user" name="user" type="text" value="{{ $usuarios->user }}"
                    @if($usuarios->user == 'admin')
                        hidden
                    @endif
                    />
                </div>
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="event-venue">Nombre Completo</label>
                    <input class="form-control" id="name" name="name" type="text" value="{{ $usuarios->name }}" />
                </div>
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="event-venue">E-mail</label>
                    <input class="form-control" id="email" name="email" type="email" value="{{ $usuarios->email }}" />
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="time-zone">Tipo de Autenticación</label>
                    <select class="form-select" id="auten" name="auten">
                        @if (($usuarios->auten == 1))
                            <option value="1" selected>Local</option>
                            <option value="2">LDAP</option>                                             
                        @else
                            <option value="1">Local</option>
                            <option value="2" selected>LDAP</option>   
                            
                        @endif   
                    </select>
                </div>
                @if ($usuarios->user != 'admin')
                <div class="col-sm-6">
                    <label class="form-label" for="time-zone">Rol</label>
                    <select class="form-select" id="role_id" name="role_id">
                        @foreach ($roles as $roles)
                        @if ($roles->id == $usuarios->role_id)
                            <option value="{{ $roles->id }}" selected> {{ $roles->role }}</option>                              
                        @endif
                        @endforeach
                    </select>
                </div>
                @endif
                @if ($usuarios->user != 'admin')
                <div class="col-sm-6">
                    <label class="form-label" for="time-zone">Grupo</label>
                    <select class="form-select" id="group_id" name="group_id">
                        @foreach ($grupos as $grupos)
                            @if ($grupos->id == $usuarios->group_id)
                                <option value="{{ $grupos->id }}" selected> {{ $grupos->group }}</option>    
                            @else
                                <option value="{{ $grupos->id }}"> {{ $grupos->group }}</option>                          
                            @endif
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="event-venue">Custom 1</label>
                    <input class="form-control" id="comment1" name="comment1" type="text" value="{{ $usuarios->comment1 }}" />
                </div>
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="event-venue">Custom 2</label>
                    <input class="form-control" id="comment2" name="comment2" type="text" value="{{ $usuarios->comment2 }}" />
                </div>
                            
                <button type="submit" class="btn btn-primary btn-user btn-block">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection