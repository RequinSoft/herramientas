@extends('layouts.template_receptor')

@section('title', 'TG - Resumen Resguardo Nuevo')

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
                @php
                    $textColor='';
                    if($articulo[0]->status == 'Disponible'){
                        $textColor = 'text-success';
                    }else if($articulo[0]->status == 'Robado'){
                        $textColor = 'text-danger';
                    }else if($articulo[0]->status == 'Extraviado'){
                        $textColor = 'text-warning';
                    }
                @endphp
                <span class="{{$textColor}} fs-1 me-1 " data-fa-transform="grow-2">{{$articulo[0]->status}}</span>
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
            <th>N°</th>
            <th class="sort" data-sort="personal">Personal</th>
            <th class="sort" data-sort="fecha_asignacion">Fecha de Asignación</th>
            <th class="sort" data-sort="fecha_entrega">Fecha de Entrega</th>
            <th class="sort" data-sort="fecha_entrega">Reporte</th>
            <th class="sort" data-sort="movimiento">Movimiento x</th>
          </tr>
        </thead>
        <tbody class="list">
            @foreach ($historial as $historial)
            <tr>
                <td>{{ $n }}</td>
                <td class="personal">{{ $historial->personal->nombre }}</td>
                <td class="text-end fecha_asignacion">{{ $historial->created_at }}</td>

                @if ($historial->status == 'Activo')
                    <td class="text-end fecha_entrega">N/A</td>
                @else
                    <td class="text-end fecha_entrega">{{ $historial->updated_at }}</td>                    
                @endif

                @if (file_exists("../storage/app/reports/".$historial->article_id."-".$fecha[$n-1].".pdf"))
                    <td class="text-center">
                        <a href="../storage/app/reports/{{$historial->article_id."-".$fecha[$n-1]}}.pdf" class="btn btn-secondary btn-sm" title="Ver" target="_blank"><i class="far fa-file-pdf"></i></a>                   
                    </td>
                @else
                    <td class="text-center">Sin Reporte</td>
                @endif

                <td class="text-end movimiento">{{$historial->usuario->name}}</td>
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
        <a class="btn btn-primary btn-user btn-block" href="{{ route('resguardo.receptor_buscar_historial_articulo') }}">
            Regresar
        </a> 
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