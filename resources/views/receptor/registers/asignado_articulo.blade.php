@extends('layouts.template_receptor')

@section('title', 'TG - Artículo Asignado')

@section('content')

<div class="d-flex bg-200 mb-3 flex-row">
   
</div>

<div class="card mb-3">
    
    <div class="card-body">
        <div class="row">
            <div class="col-lg-8">
                <h4 class="mb-1">  {{$articulo[0]->article}}</h4>
                <h5 class="fs-0 fw-normal">N/S {{$articulo[0]->ns}}</h5>

                <div class="border-bottom border-dashed my-4 d-lg-none">
                </div>
            </div>

            <div class="col ps-2 ps-lg-3">
                <a class="d-flex align-items-center mb-2" href="#"><span class="fas fa-dollar-sign fs-3 me-2 text-700" data-fa-transform="grow-2"></span>
                    <div class="flex-1">
                        <h4 class="mb-0">{{ number_format($articulo[0]->precio_actual, 0, ".", ",") }}</h4>
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
    
    <form action="actualizar_receptor_asignado_articulo" method="POST" enctype="multipart/form-data">
        @csrf
    <div class="table-responsive scrollbar">
      <table class="table table-bordered table-striped fs--2 mb-0">
        <thead class="bg-500 text-900">
          <tr>
            <th>N°</th>
            <th class="sort" data-sort="personal">Personal</th>
            <th class="sort" data-sort="fecha_asignacion">Fecha de Asignación</th>
            <th class="sort" data-sort="fecha_entrega">Estatus</th>
            <th class="sort" data-sort="fecha_entrega">Comentario</th>
            <th class="sort" data-sort="fecha_entrega">Reporte</th>
            <th class="sort" data-sort="movimiento">Movimiento x</th>
          </tr>
        </thead>
        <tbody class="list">
            <tr>
                @foreach ($asignado as $asignado)
                <td>
                    {{ $n }}
                    <input class="form-control" id="id" name="id" type="text" value="{{$asignado->id}}" hidden/>
                    <input class="form-control" id="article_id" name="article_id" type="text" value="{{$asignado->article_id}}" hidden />
                </td>
                <td class="personal">{{ $asignado->personal->nombre }}</td>
                <td class="text-end fecha_asignacion">{{ $asignado->created_at }}</td>
                <td class="text-end fecha_entrega">                    
                    <select class="form-select" id="status" size="1" name="status" data-options='{"removeItemButton":true,"placeholder":true}'>
                        @php
                        $asig_selected = '';
                        $dis_selected = '';
                        $rep_selected = '';
                        $rob_selected = '';
                        $ext_selected = '';
                        $baj_selected = '';
                            switch ($articulo[0]->status) {
                                case 'Entregado':
                                    $asig_selected = "selected";
                                    # code...
                                    break;
                                case 'Cobrado':
                                    $dis_selected = "selected";
                                    # code...
                                    break;
                                case 'Baja':
                                    $baj_selected = "selected";
                                    # code...
                                    break;
                                
                                default:
                                    # code...
                                    break;
                            }

                        @endphp
                        <option class="text-end" value="Entregado" {{$dis_selected}}> Entregado</option>
                        <option class="text-end" value="Cobrado" {{$asig_selected}}> Cobrado</option>
                        <option class="text-end" value="Baja"{{$baj_selected}}> Baja</option>
                    </select>
                </td> 
                <td><input class="form-control" id="comentario" name="comentario" type="text" /></td>
                <td><input class="form-control"  type="file" name="image" id="image" /></td>
                <td class="text-end movimiento">{{ $asignado->usuario->name }}</td>
                    
                @endforeach
            </tr>
        </tbody>
      </table>
      <br>
      <div class="col-sm-12 mb-3">
        <center>
            <a href="resguardo_receptor_buscar_articulo"  class="btn btn-warning btn-user btn-block">
                Regresar
            </a>
            <button type="submit" class="btn btn-primary btn-user btn-block">
                Actualizar
            </button>
        </center>                 
      </div>
        
      </form>
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