@extends('layouts.template_admin')

@section('title', 'Configurar LDAP')

@section('content')
<div class="card mb-3">
    <div class="card-header">
      <h5 class="mb-0">Configurar LDAP</h5>
    </div>
    <div class="card-body bg-light">
        <form  action="{{ route('admin.editar_ldap') }}" method="POST">
        @csrf
            @foreach ($ldap as $ldap)
                @php
                    if($ldap->ldap_status == 1){
                        $checked = "checked";
                    }else{
                        $checked = "";
                    }
                @endphp
                <div class="row gx-2">  
                    <div class="col-sm-6 mb-3">
                        <label class="form-label" for="event-venue"></label>
                    </div>
                    <div class="form-switch col-sm-6 mb-3">
                        <input class="form-check-input" role="switch" id="ldap_status" name="ldap_status" type="checkbox" {{ $checked }} />
                        <label class="form-check-label" for="event-venue">Habilitar Servidor</label>
                    </div> 
                    <div class="col-sm-6 mb-3">
                        <label class="form-label" for="event-venue">Server</label>
                        <input class="form-control" id="ldap_server" name="ldap_server" type="text" value="{{ $ldap->ldap_server }}" />
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class="form-label" for="event-venue">Puerto</label>
                        <input class="form-control" id="ldap_port" name="ldap_port" type="text" value="{{ $ldap->ldap_port }}" />
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class="form-label" for="event-venue">Domain</label>
                        <input class="form-control" id="ldap_domain" name="ldap_domain" type="text" value="{{ $ldap->ldap_domain }}" />
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class="form-label" for="event-venue">Versión</label>
                        <input class="form-control" id="ldap_version" name="ldap_version" type="text" value="{{ $ldap->ldap_version }}" />
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class="form-label" for="event-venue">Usuario</label>
                        <input class="form-control" id="ldap_user" name="ldap_user" type="text" value="{{ $ldap->ldap_user }}" />
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class="form-label" for="event-venue">Contraseña</label>
                        <input class="form-control" id="ldap_password" name="ldap_password" type="password" value="{{ $ldap->ldap_password }}" />
                    </div>    
            @endforeach 
                <a href="probar_ldap" class="btn btn-success btn-user btn-block">
                    Probar Conexión
                </a>  
                <div class="col-sm-6 mb-1">
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
    @if (session('ldap_msg'))
        <script>
            Swal.fire({
                title: "Servidor LDAP",
                text: "{{ session('ldap_msg') }}",
                confirmButtonText: "Aceptar",
            });
        </script>
    @endif
@endsection