@extends('layouts.template_admin')

@section('title', 'TG - Editar Departamentos')

@section('content')
<div class="card mb-3">
    <div class="card-header">
      <h5 class="mb-0">Editar Departamento</h5>
    </div>
    <div class="card-body bg-light">
        <form  action="{{ route('grupos.actualizar') }}" method="POST">
        @csrf
            <div class="row gx-2">             
                <div class="col-sm-12 mb-3">
                    <input type="text" class="form-control" id="id" name="id"
                    value="{{ $grupos->id }}" hidden>
                </div> 
                <div class="col-sm-3 mb-3">
                    <label class="form-label" for="event-venue">Grupo</label>
                    <input class="form-control" id="group" name="group" type="text" value="{{ $grupos->group }}"/>
                </div>
                <div class="col-sm-9 mb-3">
                    <label class="form-label" for="event-venue">Descripción</label>
                    <input class="form-control" id="description" name="description" type="text" value="{{ $grupos->description }}" />
                </div>
                            
                <button type="submit" class="btn btn-primary btn-user btn-block">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection