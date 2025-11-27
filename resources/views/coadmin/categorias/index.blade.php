@extends('layouts.template_coadmin')

@section('title', 'Categorías')

@section('content')
<div class="d-flex bg-200 mb-3 flex-row-reverse">
    <a href="{{ route('coadmin_categorias.nuevo') }}" class="btn btn-primary btn-sm" title="Añadir Categorías"><i class="text-100 fas fa-plus-circle"></i></a>
</div>
<div id="tableExample2" data-list='{"valueNames":["numero", "categoria","descripcion","depreciacion"],"page":25,"pagination":true}'>
    <div class="table-responsive scrollbar">
      <table class="table table-bordered table-striped fs--2 mb-0">
        <thead class="bg-500 text-900">
          <tr>
            <th class="sort" data-sort="numero">N°</th>
            <th class="sort" data-sort="categoria">Categoría</th>
            <th class="sort" data-sort="descripcion">Descripción</th>
            <th class="sort" data-sort="depreciacion">Depreciación</th>
            <th class="sort" data-sort="depreciacion">Estatus</th>
            <th class="sort" data-sort="acciones">Acciones</th>
          </tr>
        </thead>
        <tbody class="list">
            @php
                $n = 1;
            @endphp
            @foreach ($categorias as $row)
            <tr>
                <td class="text-center numero">{{ $n }}</td>
                <td class="categoria">{{ $row->category }}</td>
                <td class="descripcion">{{ $row->description }}</td>
                <td class="text-center depreciacion">{{ $row->depreciacion/365 }}</td>
                <td class="text-center depreciacion">{{ $row->status }}</td>
                <td class="text-center">
                    <a href="{{ route('coadmin_categorias.editar', $row->id) }}" class="btn btn-sm" title="Editar"><i class="text-500 fas fa-edit"></i></a>
                    &nbsp;
                    <a href="{{ route('coadmin_categorias.intento_inactivar', $row->id) }}" class="btn btn-sm" title="Inhabilitar"><i class="text-500 fas fa-trash-alt"></i></a>
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
    @if (session('add_categoria'))
        <script>
            Swal.fire({
                title: "Categoría Creada",
                text: "{{ session('add_categoria') }}",
                confirmButtonText: "Aceptar",
            });
        </script>
    @endif
    @if (session('update_categoria'))
        <script>
            Swal.fire({
                title: 'Categoría Actualizada',
                text: '{{ session('update_categoria') }}',
                confirmButtonText: "Aceptar",
            })
        </script>
    @endif
    @if (session('inactivar'))
    <script>
        Swal.fire({
            title: 'Todos los artículos de esta categría pasarán a Default, ¿Quieres inactivar la categoría {{ session('inactivar') }}?',
            showDenyButton: true,
            confirmButtonText: 'Sí',
            denyButtonText: `No`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                location.href = 'coadmin_inactivar_categorias/{{session("id")}}';
            } 
        })
    </script>
    @endif
    @if (session('categoria_inactivada'))
        <script>
            Swal.fire({
                title: 'Categoría Inactivada',
                text: '{{ session('categoria_inactivada') }}',
                confirmButtonText: "Aceptar",
            })
        </script>
    @endif
@endsection