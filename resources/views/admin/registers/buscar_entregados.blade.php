@extends('layouts.template_admin')

@section('title', 'Artículos Entregados')

@section('content')
<div class="d-flex bg-200 mb-3 flex-row-reverse">
    
</div>
<div id="tableExample2" data-list='{"valueNames":["ns"],"page":25,"pagination":true}'>
    <div class="table-responsive scrollbar">
      <table class="table table-bordered table-striped fs--2 mb-0">
        <thead class="bg-500 text-900">
          <tr>
            <th class="text-center ">N°</th>
            <th class="text-center sort" data-sort="nombre">Artículo</th>
            <th class="text-center sort" data-sort="puesto">N/S</th>
            <th class="text-center sort" data-sort="grupo">Estatus</th>
            <th class="text-center sort" data-sort="acciones">Acciones</th>
          </tr>
        </thead>
        <tbody class="list">
            @php
                $n = 0;
            @endphp
            @foreach ($entregados as $row)
            <tr>
                <td>{{ $n+1 }}</td>
                <td class="nombre">{{ $row->article }}</td>
                <td class="puesto">{{ $row->ns }}</td>
                <td class="grupo">{{ $row->status }}</td>
                <td class="acciones">
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('admin.articleDisponible', $row->id) }}" class="btn btn-sm btn-success" title="Activar"><i class="fas fa-check"></i></a>
                    </div>
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
    @if (session('articulo_disponible'))
        <script>
            Swal.fire({
                title: "Artículo Disponible ",
                text: "{{ session('articulo_disponible') }}",
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