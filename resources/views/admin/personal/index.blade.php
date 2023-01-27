@extends('layouts.template_admin')

@section('title', 'TG - Personal')

@section('content')
<div class="d-flex bg-200 mb-3 flex-row-reverse">
    <a href="{{ route('personal.nuevo') }}" class="btn btn-primary btn-sm" title="Añadir Personal"><i class="text-100 fas fa-plus-circle"></i></a>
</div>
<div id="tableExample2" data-list='{"valueNames":["nombre","puesto", "grupo"],"page":10,"pagination":true}'>
    <div class="table-responsive scrollbar">
      <table class="table table-bordered table-striped fs--2 mb-0">
        <thead class="bg-500 text-900">
          <tr>
            <th>N°</th>
            <th class="sort" data-sort="nombre">Nombre</th>
            <th class="sort" data-sort="auten">Puesto</th>
            <th class="sort" data-sort="grupo">Grupo</th>
            <th class="sort" data-sort="acciones">Acciones</th>
          </tr>
        </thead>
        <tbody class="list">
            @php
                $n = 1;
            @endphp
            @foreach ($personal as $row)
            <tr>
                <td>{{ $n }}</td>
                <td class="nombre">{{ $row->nombre }}</td>
                <td class="rol">{{ $row->puesto }}</td>
                <td class="grupo">{{ $row->group->group }}</td>
                <td class="justify-content-center">
                    <a href="{{ route('personal.editar', $row->id) }}" class="btn  btn-sm" title="Editar"><i class="text-500 fas fa-edit"></i></a>
                    &nbsp;
                    <a href="{{ route('personal.inactivar', $row->id) }}" class="btn  btn-sm" title="Inhabilitar"><i class="text-500 fas fa-trash-alt"></i></a>
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