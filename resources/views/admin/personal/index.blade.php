@extends('layouts.template_admin')

@section('title', 'Personal')

@section('content')
<div class="d-flex bg-200 mb-3 flex-row-reverse">
    <a href="{{ route('personal.nuevo') }}" class="btn btn-primary btn-sm" title="Añadir Personal"><i class="text-100 fas fa-plus-circle"></i></a>
</div>
<div id="tableExample2" data-list='{"valueNames":["nombre"],"page":25,"pagination":true}'>
    <div class="table-responsive scrollbar">
      <table class="table table-bordered table-striped fs--2 mb-0">
        <div class="search-box" data-list='{"valueNames":["ns"]}'>
            <input class="form-control search-input fuzzy-search" type="search" placeholder="Buscar Nombre..." aria-label="Search" data-column="7"/>
            <span class="fas fa-search search-box-icon"></span>
        </div> 
    </br>
        <thead class="bg-500 text-900">
          <tr>
            <th class="sort text-center" data-sort="id">N°</th>
            <th class="sort text-center" data-sort="nombre">Nombre</th>
            <th class="sort text-center" data-sort="auten">Puesto</th>
            <th class="sort text-center" data-sort="grupo">Grupo</th>
            <th class="sort text-center" data-sort="grupo">Estado</th>
            <th class="sort text-center" data-sort="acciones">Acciones</th>
          </tr>
        </thead>
        <tbody class="list">
            @php
                $n = 1;
                $color = 'green';
            @endphp
            @foreach ($personal as $row)
            <tr>
                @php
                    if($row->status == 'inactivo'){
                        $color = 'red';
                    }if($row->status == 'activo'){
                        $color = 'green';
                    }
                @endphp
                <td class="id">{{ $row->id }}</td>
                <td class="nombre">{{ $row->nombre }}</td>
                <td class="rol">{{ $row->puesto }}</td>
                <td class="grupo">{{ $row->group->group }}</td>
                <td class="text-center estatus">
                    <p style="color:{{$color}}">
                        {{ $row->status }}
                    </p>
                </td>
                <td class="justify-content-center">
                    <a href="{{ route('personal.editar', $row->id) }}" class="btn  btn-sm" title="Editar"><i class="text-500 fas fa-edit"></i></a>
                    &nbsp;
                    @if ($row->status == 'activo')
                        <a href="{{ route('personal.inactivar', $row->id) }}" class="btn  btn-sm" title="Inhabilitar"><i class="text-500 fas fa-trash-alt"></i></a>
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
    @if (session('user_add'))
        <script>
            Swal.fire({
                title: "Personal Creado ",
                text: "{{ session('user_add') }}",
                confirmButtonText: "Aceptar",
            });
        </script>
    @endif
    @if (session('user_update'))
        <script>
            Swal.fire({
                title: "Personal Actualizado ",
                text: "{{ session('user_update') }}",
                confirmButtonText: "Aceptar",
            });
        </script>
    @endif
@endsection