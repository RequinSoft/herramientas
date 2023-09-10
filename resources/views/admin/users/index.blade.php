@extends('layouts.template_admin')

@section('title', 'Usuarios')

@section('content')
<div class="d-flex bg-200 mb-3 flex-row-reverse">
    <a href="{{ route('usuarios.nuevo') }}" class="btn btn-primary btn-sm" title="Añadir Usuario"><i class="text-100 fas fa-plus-circle"></i></a>
</div>
<div id="tableExample2" data-list='{"valueNames":["usuario","nombre","rol", "grupo", "auten", "email", "custom"],"page":25,"pagination":true}'>
    <div class="table-responsive scrollbar">
      <table class="table table-bordered table-striped fs--2 mb-0">
        <thead class="bg-500 text-900">
          <tr>
            <th class="text-center sort" >N°</th>
            <th class="text-center sort" data-sort="usuario">Usuario</th>
            <th class="text-center sort" data-sort="nombre">Nombre</th>
            <th class="text-center sort" data-sort="rol">Rol</th>
            <th class="text-center sort" data-sort="grupo">Grupo</th>
            <th class="text-center sort" data-sort="auten">Autenticación</th>
            <th class="text-center sort" data-sort="email">Email</th>
            <th class="text-center sort" data-sort="custom">Custom</th>
            <th class="text-center sort" data-sort="custom">Estado</th>
            <th class="text-center sort" data-sort="acciones">Acciones</th>
          </tr>
        </thead>
        <tbody class="list">
            @php
                $n = 1;
                $color = 'green';
            @endphp
            @foreach ($usuarios as $row)
            <tr>
                @php
                    if($row->status == 'inactivo'){
                        $color = 'red';
                    }if($row->status == 'activo'){
                        $color = 'green';
                    }
                @endphp
                <td>{{ $n }}</td>
                <td class="usuario">{{ $row->user }}</td>
                <td class="nombre">{{ $row->name }}</td>
                <td class="rol">{{ $row->role->role }}</td>
                <td class="grupo">{{ $row->group->group }}</td>
                @if ($row->auten == 1)
                    <td class="text-center">Local</td>
                @else
                    <td class="text-center">LDAP</td>
                @endif
                <td class="email">{{ $row->email }}</td>
                <td class="custom">{{ $row->comment1 }}</td>
                <td class="text-center estatus">
                    <p style="color:{{$color}}">
                        {{ $row->status }}
                    </p>
                </td>
                <td class="text-center">
                    @if ($row->user == 'admin')
                        <a href="{{ route('admin.pass', $row->id) }}" class="btn btn-sm" title="Contraseña"><i class="text-500 fas fa-key"></i></a>
                        &nbsp; 
                        <a href="{{ route('usuarios.editar', $row->id) }}" class="btn btn-sm" title="Editar"><i class="text-500 fas fa-edit"></i></a>
                    @else
                        @if ($row->auten == 1)
                            <a href="{{ route('admin.pass', $row->id) }}" class="btn btn-sm" title="Contraseña"><i class="text-500 fas fa-key"></i></a>
                            &nbsp; 
                        @else
                            <a href="#" class="btn btn-secondary btn-sm" title="Se debe cambiar en el Servidor LDAP"><i class="text-500 fas fa-key"></i></a>
                            &nbsp; 
                        @endif
                        <a href="{{ route('usuarios.editar', $row->id) }}" class="btn  btn-sm" title="Editar"><i class="text-500 fas fa-edit"></i></a>
                        &nbsp;
                        @if ($row->status == 'activo')
                            <a href="{{ route('usuarios.inactivar', $row->id) }}" class="btn  btn-sm" title="Inhabilitar"><i class="text-500 fas fa-trash-alt"></i></a>
                        @endif
                        
                    @endif
                </td>
            </tr>
            @php
                $n++;
            @endphp
            @endforeach
        </tbody>
      </table>
    </div>
    <div class="d-flex justify-content-center mt-3">
      <button class="btn btn-sm btn-falcon-default me-1" type="button" title="Previous" data-list-pagination="prev"><span class="fas fa-chevron-left"></span></button>
      <ul class="pagination mb-0"></ul>
      <button class="btn btn-sm btn-falcon-default ms-1" type="button" title="Next" data-list-pagination="next"><span class="fas fa-chevron-right"> </span></button>
    </div>
</div>
@endsection

@section('script')
    @if (session('pass'))
        <script>
            Swal.fire({
                title: "Contraseña ",
                text: "Actualizada la Contraseña de  {{ session('pass') }}",
                confirmButtonText: "Aceptar",
            });
        </script>
    @endif
    @if (session('user_add'))
        <script>
            Swal.fire({
                title: "Usuario Creado ",
                text: "{{ session('user_add') }}",
                confirmButtonText: "Aceptar",
            });
        </script>
    @endif
    @if (session('user_update'))
        <script>
            Swal.fire({
                title: "Usuario Actualizado ",
                text: "{{ session('user_update') }}",
                confirmButtonText: "Aceptar",
            });
        </script>
    @endif
@endsection