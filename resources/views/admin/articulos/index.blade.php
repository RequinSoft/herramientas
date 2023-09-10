@extends('layouts.template_admin')

@section('title', 'Artículos')

@section('content')
<div class="d-flex bg-200 mb-3 flex-row-reverse">
    <a href="{{ route('articulos.nuevo') }}" class="btn btn-primary btn-sm" title="Añadir Articulos"><i class="text-100 fas fa-plus-circle"></i></a>
</div>

<div id="tableExample2" data-list='{"valueNames":["ns","categoria", "estatus"],"page":25,"pagination":true}'>
    <div class="table-responsive scrollbar">
      <table class="table table-bordered table-striped fs--2 mb-0" id="tabla_articulos">
        <div class="search-box" data-list='{"valueNames":["ns"]}'>
            <input class="form-control search-input fuzzy-search" type="search" placeholder="Search N/S..." aria-label="Search" data-column="7"/>
            <span class="fas fa-search search-box-icon"></span>
        </div> 
    </br>
        <thead class="bg-500 text-900">
          <tr>
            <th class="sort" data-sort="numero">N°</th>
            <th class="sort" data-sort="articulo">Artículo</th>
            <th class="sort" data-sort="precio_incial">Precio inicial</th>
            <th class="sort" data-sort="precio_actual">Precio Actual</th>
            <th class="sort" data-sort="descripcion">Descripción</th>
            <th class="sort" data-sort="marca">Marca</th>
            <th class="sort" data-sort="modelo">Modelo</th>
            <th class="sort" data-sort="ns">N/S</th>
            <th class="sort" data-sort="categoria">Categoría</th>
            <th class="sort" data-sort="estatus">Estatus</th>
            <th class="sort" data-sort="acciones">Acciones</th>
          </tr>
        </thead>
        <tbody class="list">
            @php
                $n = 1;
            @endphp
            @foreach ($datos as $row)
            <tr>
                <td class="text-center numero">{{ $n }}</td>
                <td class="articulo">{{ $row->article }}</td>
                <td class="text-end precio_inicial">$ {{ number_format($row->precio_inicial, 0, ".", ",") }}</td>
                <td class="text-end precio_actual">$ {{ number_format($row->precio_actual, 0, ".", ",") }}</td>
                <td class="descripcion">{{ $row->description }}</td>
                <td class="marca">{{ $row->marca }}</td>
                <td class="modelo">{{ $row->modelo }}</td>
                <td class="ns">{{ $row->ns }}</td>
                <td class="categoria">{{ $row->category->category }}</td>
                <td class="estatus">{{ $row->status }}</td>
                <td class="text-center">
                    <a href="{{ route('articulos.editar', $row->id) }}" class="btn btn-sm" title="Editar"><i class="text-500 fas fa-edit"></i></a>
                    &nbsp;
                    <a href="{{ route('articulos.inactivar', $row->id) }}" class="btn btn-sm" title="Inhabilitar"><i class="text-500 fas fa-trash-alt"></i></a>
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
    @if (session('articulo_add'))
        <script>
            Swal.fire({
                title: "Artículo Creado ",
                text: "{{ session('articulo_add') }}",
                confirmButtonText: "Aceptar",
            });
        </script>
    @endif
    @if (session('articulo_update'))
        <script>
            Swal.fire({
                title: "Artículo Actualizado ",
                text: "{{ session('articulo_update') }}",
                confirmButtonText: "Aceptar",
            });
        </script>
    @endif
    @if (session('info'))
        <script>
            Swal.fire({
                title: "Artículo Dado de Baja",
                text: "{{ session('info') }}",
                confirmButtonText: "Aceptar",
            });
        </script>
    @endif
    @if (Session::has('articulo_desactivado'))
        <script>
            Swal.fire({
                title: 'El N/S <a style="color:green";>{{ Session('articulo_ns')}} </a> ya existe <br> Estatus --> <a style="color:#FF0000";>{{ Session('articulo_desactivado') }}</a> <br>¿Desea activarlo?',
                showDenyButton: true,
                confirmButtonText: 'Sí',
                denyButtonText: `No`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    window.location.href = "activar_articulos/{{Session('id_articulo')}}";
                }
            })
        </script>        
    @endif
    @if (session('articulo_disponible'))
        <script>
            Swal.fire({
                title: '¡El N/S <a style="color:green";>{{ Session('articulo_ns')}}</a> existe! <br> Estatus <a style="color:blue";>{{ Session('articulo_disponible')}}</a>',
                text: '',
                confirmButtonText: "Aceptar",
            });
        </script>
    @endif

@endsection