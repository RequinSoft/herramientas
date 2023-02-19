@extends('layouts.template_receptor')

@section('title', 'TG - Historial Personas')

@section('content')

<div class="card mb-3">
    <div class="card-header">
      <h5 class="mb-0">Historial Personas</h5>
    </div>
    <div class="card-body bg-light">
        <form action="receptor_historial_persona" method="POST">
        @csrf    
                <div class="col-sm-6 mb-3">
                    <input type="text" class="form-control" id="action_by" name="action_by"value="{{auth()->user()->id}}" hidden>
                </div> 

                <div class="col-sm-12 mb-12">
                    <label for="organizerMultiple">Personas</label>
                    <select class="form-select js-choice" id="persona" size="3" name="persona" data-options='{"removeItemButton":true,"placeholder":true}'>
                        <option value="">Selecciona de 1 Persona...</option>
                        @foreach ($personas as $personas)
                            <option value="{{ $personas->id }}"> {{ $personas->nombre }}</option>    
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 mb-3">
                    
                </div>

            
                            
                <button type="submit" class="btn btn-primary btn-user btn-block">
                    Enviar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- /.container-fluid -->
@endsection