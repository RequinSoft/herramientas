@extends('layouts.template_coadmin')

@section('title', 'TG - Editar Artículos')

@section('content')
<div class="card mb-3">
    <div class="card-header">
      <h5 class="mb-0">Editar Artículos</h5>
    </div>
    <div class="card-body bg-light">
        <form  action="{{ route('articulos.actualizar') }}" method="POST">
        @csrf
            <div class="row gx-2">             
                <div class="col-sm-12 mb-3">
                    <input type="text" class="form-control" id="id" name="id" value="{{ $datos->id }}" hidden>
                </div> 

                <div class="col-sm-4 mb-3">
                    <label class="form-label" for="event-venue">Artículo</label>
                    <input class="form-control" id="article" name="article" type="text" value="{{ $datos->article }}"/>
                </div>
                <div class="col-sm-4 mb-3">
                    <label class="form-label" for="event-venue">Precio</label>
                    <input class="text-end form-control" id="precio_inicial" name="precio_inicial" type="text" value="{{ $datos->precio_inicial }}"/>
                </div>
                <div class="col-sm-4 mb-3">
                    <label class="form-label" for="event-venue">N/S</label>
                    <input class="form-control" id="ns" name="ns" type="text" value="{{ $datos->ns }}"/>
                </div>
                <div class="col-sm-4 mb-3">
                    <label class="form-label" for="event-venue">Marca</label>
                    <input class="form-control" id="marca" name="marca" type="text" value="{{ $datos->marca }}" />
                </div>
                <div class="col-sm-4 mb-3">
                    <label class="form-label" for="event-venue">Modelo</label>
                    <input class="form-control" id="modelo" name="modelo" type="text" value="{{ $datos->modelo }}" />
                </div>
                <div class="col-sm-4 mb-3">
                    <label class="form-label" for="event-venue">Categoría</label>
                    <select class="form-control" id="category_id" name="category_id">
                        @foreach ($categorias as $categoria)
                            @if ($categoria->id == $datos->category_id)
                                <option value="{{ $categoria->id }}" selected> {{ $categoria->category }}</option>
                            @else
                                <option value="{{ $categoria->id }}"> {{ $categoria->category }}</option>                                        
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-12 mb-3">
                    <label class="form-label" for="event-venue">Descripción</label>
                    <input class="form-control" id="description" name="description" type="text" value="{{ $datos->description }}" />
                </div>
                            
                <button type="submit" class="btn btn-primary btn-user btn-block">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection