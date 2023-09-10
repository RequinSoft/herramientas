@extends('layouts.template_admin')

@section('title', 'Nuevos Departamentos')

@section('content')
<div class="card mb-3">
    <div class="card-header">
      <h5 class="mb-0">Nuevo Departamento</h5>
    </div>
    <div class="card-body bg-light">
        <form action="grupo_crear" method="POST">
        @csrf
            <div class="row gx-2">  

                <div class="col-sm-6 mb-3">
                </div>          
                <div class="col-sm-6 mb-3">
                    <input type="text" class="form-control" id="action_by" name="action_by"
                    value="{{auth()->user()->id}}" hidden>
                </div> 

                <div class="col-sm-3 mb-3">
                    <label class="form-label" for="event-venue">Departamento</label>
                    <input class="form-control" id="group" name="group" type="text" value="{{ old('group') }}" />
                </div>
                <div class="col-sm-9 mb-3">
                    <label class="form-label" for="event-venue">Descripción</label>
                    <input class="form-control" id="description" name="description" type="text" value="{{ old('description') }}" />
                </div>
                <div class="col-sm-3 mb-3">
                </div>    
                <div class="col-sm-6 mb-3 text-center">
                    @error('group')
                        <br>
                        <button type="text" disabled class="btn btn-danger btn-block"id="error" name="error">
                            {{$message}}
                        </button>                 
                    @enderror
                </div>              
                            
                <button type="submit" class="btn btn-primary btn-user btn-block">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection