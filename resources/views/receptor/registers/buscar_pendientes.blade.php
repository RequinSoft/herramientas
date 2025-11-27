@extends('layouts.template_receptor')

@section('title', 'Resguardos')

@section('content')
<div class="d-flex bg-200 mb-3 flex-row-reverse">
    <a href="{{ route('resguardo.nuevo') }}" class="btn btn-primary btn-sm" title="Añadir Resguardo"><i class="text-100 fas fa-plus-circle"></i></a>
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
            <th class="text-center ">N°</th>
            <th class="text-center sort" data-sort="nombre">Nombre</th>
            <th class="text-center sort" data-sort="puesto">Puesto</th>
            <th class="text-center sort" data-sort="grupo">Grupo</th>
            <th class="text-center sort" data-sort="acciones">Acciones</th>
          </tr>
        </thead>
        <tbody class="list">
            @php
                $n = 0;
            @endphp
            @foreach ($personal as $row)
            <tr>
                <td>{{ $n+1 }}</td>
                <td class="nombre">{{ $row[0]->nombre }}</td>
                <td class="puesto">{{ $row[0]->puesto }}</td>
                <td class="grupo">{{ $row[0]->group->group }}</td>
                <td class="text-center ">
                    <a href="{{ route('resguardo.receptor_finalizar', $row[0]->id) }}" class="btn  btn-sm" title="Finalizar"><i class="text-500 fas fa-edit"></i></a>
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