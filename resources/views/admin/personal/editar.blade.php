@extends('layouts.template_admin')

@section('title', 'Editar Personal')

@section('content')
<div class="card mb-3">
    <div class="card-header">
      <h5 class="mb-0">Editar Personal</h5>
    </div>
    <div class="card-body bg-light">
        <form  action="{{ route('personal.actualizar') }}" method="POST" enctype="multipart/form-data">
        @csrf
            <div class="row gx-2">  
                <div class="col-sm-3 mb-3 text-center">
                    @error('nombre')
                        <br>
                        <button type="text" disabled class="btn btn-danger btn-block"id="error" name="error">
                            {{$message}}
                        </button>                 
                    @enderror
                    @error('id')
                        <br>
                        <button type="text" disabled class="btn btn-danger btn-block"id="error" name="error">
                            {{$message}}
                        </button>                 
                    @enderror
                </div>            
                <div class="col-sm-6 mb-3">
                    <input type="text" class="form-control" id="id_viejo" name="id_viejo"
                    value="{{ $personal->id }}" hidden>
                </div> 
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="event-venue">Foto de Perfil</label>
                    <input class="form-control"  type="file" name="image" id="image" />
                </div> 
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="event-venue">ID</label>
                    <input class="form-control" id="id" name="id" type="text" value="{{ $personal->id }}" />
                </div>
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="event-venue">Nombre Completo</label>
                    <input class="form-control" id="nombre" name="nombre" type="text" value="{{ $personal->nombre }}" />
                </div>
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="event-venue">Puesto</label>
                    <input class="form-control" id="puesto" name="puesto" type="text" value="{{ $personal->puesto }}" />
                </div>
                
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="time-zone">Grupo</label>
                    <select class="form-select" id="group_id" name="group_id">
                        @foreach ($grupos as $grupos)
                        @if ($grupos->id == $personal->group_id)
                            <option value="{{ $grupos->id }}" selected> {{ $grupos->group }}</option>
                        @else
                            <option value="{{ $grupos->id }}"> {{ $grupos->group }}</option>                                        
                        @endif
                        @endforeach
                    </select>
                </div>
                @php
                    if($personal->status == 'activo'){
                        $selected_activo = 'selected';
                        $selected_inactivo = '';
                    }elseif ($personal->status == 'inactivo') {                        
                        $selected_activo = '';
                        $selected_inactivo = 'selected';
                    }
                @endphp
                
                <div class="col-sm-6">
                    <label class="form-label" for="time-zone">Estatus</label>
                    <select class="form-select" id="status" name="status">
                        <option style="color:green" value="activo" {{$selected_activo}}>activo</option>  
                        <option style="color:red" value="inactivo" {{$selected_inactivo}}>inactivo</option> 
                    </select>
                </div>
                            
                <button type="submit" class="btn btn-primary btn-user btn-block">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
    @if (session('mensaje_update'))
        <script>
            Swal.fire({
                title: "ID Duplicado ",
                text: "{{ session('mensaje_update') }}",
                confirmButtonText: "Aceptar",
            });
        </script>
    @endif
@endsection