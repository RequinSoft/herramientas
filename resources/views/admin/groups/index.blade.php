@extends('layouts.template_admin')

@section('title', 'TG - Departamentos')

@section('content')
<div class="d-flex bg-200 mb-3 flex-row-reverse">
    <a href="{{ route('grupos.nuevo') }}" class="btn btn-primary btn-sm" title="Añadir Grupos"><i class="text-100 fas fa-plus-circle"></i></a>
</div>
<div id="tableExample2" data-list='{"valueNames":["numero", "grupo","descripcion"],"page":10,"pagination":true}'>
    <div class="table-responsive scrollbar">
      <table class="table table-bordered table-striped fs--2 mb-0">
        <thead class="bg-500 text-900">
          <tr>
            <th class="sort" data-sort="numero">N°</th>
            <th class="sort" data-sort="grupo">Grupo</th>
            <th class="sort" data-sort="descripcion">Descripción</th>
            <th class="sort" data-sort="acciones">Acciones</th>
          </tr>
        </thead>
        <tbody class="list">
            @php
                $n = 1;
            @endphp
            @foreach ($grupos as $row)
            <tr>
                <td class="text-center numero">{{ $n }}</td>
                <td class="grupo">{{ $row->group }}</td>
                <td class="descripcion">{{ $row->description }}</td>
                <td class="text-center">
                    <a href="{{ route('grupos.categorias', $row->id) }}" class="btn btn-sm" title="Editar"><i class="text-500 fas fa-key"></i></a>
                    &nbsp;
                    <a href="{{ route('grupos.editar', $row->id) }}" class="btn btn-sm" title="Editar"><i class="text-500 fas fa-edit"></i></a>
                    &nbsp;
                    <a href="{{ route('grupos.inactivar', $row->id) }}" class="btn btn-sm" title="Inhabilitar"><i class="text-500 fas fa-trash-alt"></i></a>
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
    @if (session('group_add'))
        <script>
            Swal.fire({
                title: "Grupo Creado ",
                text: "{{ session('group_add') }}",
                confirmButtonText: "Aceptar",
            });
        </script>
    @endif
    @if (session('group_update'))
        <script>
            Swal.fire({
                title: "Grupo Actualizado ",
                text: "{{ session('group_update') }}",
                confirmButtonText: "Aceptar",
            });
        </script>
    @endif
@endsection