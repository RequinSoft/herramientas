@extends('layouts.template_admin')

@section('title', 'TG - Editar Personal')

@section('content')
<div class="card mb-3">
    <div class="card-header">
      <h5 class="mb-0">Editar Personal</h5>
    </div>
    <div class="card-body bg-light">
        <form  action="{{ route('personal.actualizar') }}" method="POST" enctype="multipart/form-data">
        @csrf
            <div class="row gx-2">             
                <div class="col-sm-6 mb-3">
                    <input type="text" class="form-control" id="id" name="id"
                    value="{{ $personal->id }}" hidden>
                </div> 
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="event-venue">Foto de Perfil</label>
                    <input class="form-control"  type="file" name="image" id="image" />
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
                            
                <button type="submit" class="btn btn-primary btn-user btn-block">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection