@extends('layouts.template_coadmin')

@section('title', 'TG - Resumen Resguardo Nuevo')

@section('content')

<div class="d-flex bg-200 mb-3 flex-row">
   
</div>

<div class="card mb-3">
    <div class="card-header position-relative min-vh-25 mb-7">
      <div class="bg-holder rounded-3 rounded-bottom-0" style="background-image:url({{ $ruta }}../resources/assets/img/generic/4.jpg);">
      </div>
      <!--/.bg-holder-->

      <div class="avatar avatar-5xl avatar-profile">
        @if (file_exists("../storage/app/avatars/personal-".$person->id.".".$person->ext))
            <img class="rounded-circle img-thumbnail shadow-sm" src="{{ $ruta }}../storage/app/avatars/personal-{{$person->id}}.{{$person->ext}}" width="200" alt="" />
        @else
            <img class="rounded-circle img-thumbnail shadow-sm" src="{{ $ruta }}../storage/app/avatars/avatar.png" width="200" alt="" />
        @endif
      </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-8">
                <h4 class="mb-1">  {{$person->nombre}}</h4>
                <h5 class="fs-0 fw-normal">{{$person->puesto}}</h5>

                <div class="border-bottom border-dashed my-4 d-lg-none">

                </div>
            </div>

            <div class="col ps-2 ps-lg-3">
                <a class="d-flex align-items-center mb-2" href="#"><span class="fas fa-dollar-sign fs-3 me-2 text-700" data-fa-transform="grow-2"></span>
                    <div class="flex-1">
                        <h4 class="mb-0">{{ number_format($suma, 0, ".", ",") }}</h4>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@php
    $n=1;
@endphp
<div id="tableExample2" data-list='{"valueNames":["articulo", "precio_actual", "creado", "estado"]}'>
    
    <div class="table-responsive scrollbar">
      <table class="table table-bordered table-striped fs--2 mb-0">
        <thead class="bg-500 text-900">
          <tr>
            <th class="text-center">N°</th>
            <th class="text-center sort" data-sort="articulo">Artículo</th>
            <th class="text-center sort" data-sort="ns">Número de Serie</th>
            <th class="text-center sort" data-sort="precio_actual">Costo Actual</th>
            <th class="text-center sort" data-sort="asignatario">Asignatario</th>
            <th class="text-center sort" data-sort="acciones">Fecha</th>
            <th class="text-center sort" data-sort="acciones">Acciones</th>
          </tr>
        </thead>
        <tbody class="list">
            @foreach ($articulos as $articulo)
            <tr>
                <td class="text-center">{{ $n }}</td>
                <td class="articulo">{{ $articulo->articulos->article }}</td>
                <td class="text-end ns">{{ $articulo->articulos->ns }}</td>
                <td class="text-end precio_actual">$ {{ number_format($articulo->articulos->precio_actual, 0, ".", ",") }}</td>
                <td class="text-end asignatario">{{ $articulo->usuario->name }}</td>
                <td class="text-end">{{ $articulo->created_at }}</td>
                <td class="text-center"><a href="{{ route('resguardo.coadmin_editarlinea', $articulo->id) }}" class="btn  btn-sm" title="Reporte"><i class="text-500 fas fa-edit"></i></a></td>
            </tr>
            @php
                $n++;
            @endphp
            @endforeach
        </tbody>
      </table>
      <br>
      <div class="col-sm-12 mb-3">
        <center>
        <a href="{{route('resguardo.coadmin_buscar_persona')}}" class="btn btn-primary btn-user btn-block">
            Regresar
        </a> 
        </center>                 
      </div>
    </div>
</div>

<!-- /.container-fluid -->
@endsection

@section('script')
    @if (session('info'))
        <script>
            Swal.fire({
                title: "Remisión Creada",
                text: "Folio {{ session('info') }}",
                confirmButtonText: "Aceptar",
            });
        </script>
    @endif

    @if (session('info_actualizado'))
        <script>
            Swal.fire({
                title: "Remisión Actualizada",
                text: "Folio {{ session('info_actualizado') }}",
                confirmButtonText: "Aceptar",
            });
        </script>
    @endif

    @if (session('info_cancelado'))
        <script>
            Swal.fire({
                title: "Remisión Cancelada",
                text: "Folio {{ session('info_cancelado') }}",
                confirmButtonText: "Aceptar",
            });
        </script>
    @endif
@endsection