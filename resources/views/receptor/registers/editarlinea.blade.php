@extends('layouts.template_receptor')

@section('title', 'TG - Reportar Artículo')

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
                
            </div>
        </div>
    </div>
</div>

<form action="{{ route('resguardo.receptor_actualizarLinea') }}" method="POST" enctype="multipart/form-data">
    @csrf
<div id="tableExample2" data-list='{"valueNames":["articulo", "precio_actual", "creado", "estado"]}'>    
    
    <input type="text" class="form-control" id="id_linea" name="id_linea" value="{{$line->id}}" hidden>
    <div class="table-responsive scrollbar">
      <table class="table table-bordered table-striped fs--2 mb-0">
        <thead class="bg-500 text-900">
          <tr>
            <th class="text-center">N°</th>
            <th class="text-center sort" data-sort="articulo">Artículo</th>
            <th class="text-center sort" data-sort="ns">Número de Serie</th>
            <th class="text-center sort" data-sort="precio_actual">Costo Actual</th>
            <th class="text-center sort" data-sort="acciones">Fecha</th>
            <th class="text-center sort" data-sort="acciones">Acciones</th>
            <th class="text-center sort" data-sort="comentario">Comentarios</th>
            <th class="text-center sort" data-sort="comentario">Reporte</th>
          </tr>
        </thead>
        <tbody class="list">
            <tr>
                <td class="text-center">1</td>
                <td class="articulo">{{ $articulo->article }}</td>
                <td class="text-end ns">{{ $articulo->ns }}</td>
                <td class="text-end precio_actual">$ {{ number_format($articulo->precio_actual, 0, ".", ",") }}</td>
                <td class="text-end">{{ $articulo->created_at }}</td>
                <td class="text-center">
                    <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="status" id="status">
                        <option value="Disponible"> Entregado</option>
                        <option value="En Reparacion"> En Reparación</option>
                        <option value="Extraviado"> Extraviado</option>  
                        <option value="Robado"> Robado</option>  
                        <option value="Baja"> Baja</option>    
                      </select>
                </td>
                <td>
                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="1" id="comentario1" name="comentario1" >
                    </textarea>
                </td>
                <td><input class="form-control" accept=".pdf"  type="file" name="image" id="image" /></td>
            </tr>
        </tbody>
      </table>
      <div class="col-sm-12 mb-3">
        <center>
        <button type="submit" class="btn btn-primary btn-user btn-block">
            Actualizar
        </button> 
        </center>                 
      </div>
    </div>
</div>
</form>

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