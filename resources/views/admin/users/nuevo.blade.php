@extends('layouts.template_admin')

@section('title', 'TG - Nuevo Usuario')

@section('content') 

<div class="card mb-3">
    <div class="card-header">
      <h5 class="mb-0">Nuevo Usuario</h5>
    </div>
    <div class="card-body bg-light">
        <form action="usuario_crear" method="POST" enctype="multipart/form-data">
        @csrf
            <div class="row gx-2">          
                <div class="col-sm-1 mb-3">
                    <input type="text" class="form-control" id="action_by" name="action_by"
                    value="{{auth()->user()->id}}" hidden>
                </div> 
                <div class="col-sm-5 mb-3">
                    @error('user')
                        <button type="text" disabled class="btn btn-danger btn-block" id="error" name="error">
                            {{$message}}
                        </button> 
                    @enderror
                    @error('name')
                        <button type="text" disabled class="btn btn-danger btn-block" id="error" name="error">
                            {{$message}}
                        </button> 
                    @enderror
                    @error('password')
                        <button type="text" disabled class="btn btn-danger btn-block" id="error" name="error">
                            {{$message}}
                        </button> 
                    @enderror
                    @error('pass2')
                        <button type="text" disabled class="btn btn-danger btn-block" id="error" name="error">
                            {{$message}}
                        </button> 
                    @enderror
                    @error('email')
                        <button type="text" disabled class="btn btn-danger btn-block" id="error" name="error">
                            {{$message}}
                        </button> 
                    @enderror
                </div> 


                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="event-venue">Foto de Perfil</label>
                    <input class="form-control"  type="file" name="image" id="image" />
                </div> 
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="event-venue">Usuario</label>
                    <input class="form-control" id="user" name="user" type="text" value="{{ old('user') }}" />
                </div>
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="event-venue">Nombre Completo</label>
                    <input class="form-control" id="name" name="name" type="text" value="{{ old('name') }}" />
                </div>
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="event-venue">Contraseña</label>
                    <input class="form-control" id="password" name="password" type="password" value="{{ old('password') }}" />
                </div>
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="event-venue">Verificar Contraseña</label>
                    <input class="form-control" id="pass2" name="pass2" type="password" value="{{ old('pass2') }}" />
                </div>
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="event-venue">E-mail</label>
                    <input class="form-control" id="email" name="email" type="email" value="{{ old('email') }}" />
                </div>
                @if ($ldap[0]->ldap_status == 0)
                    <div class="col-sm-6 mb-1">
                        <label class="form-label" for="time-zone">Tipo de Autenticación</label>
                        <input class="form-control" id="auten_label" name="auten_label"  value="Local" disabled />
                        <input class="form-control" id="auten" name="auten"  value="1" hidden/>
                    </div>
                @else
                    <div class="col-sm-6">
                        <label class="form-label" for="time-zone">Tipo de Autenticación</label>
                        <select class="form-select" id="auten" name="auten">
                            <option value="1" selected> Local</option>
                            <option value="2"> LDAP</option>     
                        </select>
                    </div>                    
                @endif
                <div class="col-sm-6">
                    <label class="form-label" for="time-zone">Rol</label>
                    <select class="form-select" id="role_id" name="role_id">
                        @foreach ($roles as $roles)
                        @if ($roles->id == 3)
                            <option value="{{ $roles->id }}" selected> {{ $roles->role }}</option>
                        @else
                            <option value="{{ $roles->id }}"> {{ $roles->role }}</option>                                        
                        @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="time-zone">Grupo</label>
                    <select class="form-select" id="group_id" name="group_id">
                        @foreach ($grupos as $grupos)
                        @if ($grupos->id == 1)
                            <option value="{{ $grupos->id }}" selected> {{ $grupos->group }}</option>
                        @else
                            <option value="{{ $grupos->id }}"> {{ $grupos->group }}</option>                                        
                        @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="event-venue">Custom 1</label>
                    <input class="form-control" id="comment1" name="comment1" type="text" value="{{ old('comment1') }}" />
                    <input class="form-control" id="status" name="status" type="text" value="activo" hidden/>
                </div>
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="event-venue">Custom 2</label>
                    <input class="form-control" id="comment2" name="comment2" type="text" value="{{ old('comment2') }}" />
                </div>
                            
                <button type="submit" class="btn btn-primary btn-user btn-block">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
    @error ('user')
        @if ($message == 'inactivo')
            <script>
                Swal.fire({
                    title: 'El usuario ya existe, ¿Desea activarlo?',
                    showDenyButton: true,
                    confirmButtonText: 'Ir',
                    denyButtonText: `No`,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        window.location.href = "usuarios_inactvos";
                    }
                })
            </script>        
        @endif
    @enderror
@endsection