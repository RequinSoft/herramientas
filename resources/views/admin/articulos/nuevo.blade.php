@extends('layouts.template_admin')

@section('title', 'TG - Nuevo Artículo')

@section('content')
<div class="card mb-3">
    <div class="card-header">
      <h5 class="mb-0">Nuevo Artículo</h5>
    </div>
    <div class="card-body bg-light">
        <form action="articulo_crear" method="POST">
        @csrf
            <div class="row gx-2">  
                <div class="col-sm-6 mb-1">
                    @error('article')
                        <div class="form-group">
                            <button type="text" disabled class="btn btn-danger btn-block" id="error" name="error">
                                {{$message}}
                            </button>
                        </div>
                    @enderror
                    @error('ns')
                        <div class="form-group">
                            <button type="text" disabled class="btn btn-danger btn-block"
                                id="error" name="error">
                                {{$message}}
                            </button>
                        </div>
                    @enderror
                    @error('modelo')
                        <div class="form-group">
                            <button type="text" disabled class="btn btn-danger btn-block"
                                id="error" name="error">
                                {{$message}}
                            </button>
                        </div>
                    @enderror
                    @error('created_at')
                        <div class="form-group">
                            <button type="text" disabled class="btn btn-danger btn-block"
                                id="error" name="error">
                                {{$message}}
                            </button>
                        </div>
                    @enderror
                </div>          
                <div class="col-sm-6 mb-3">
                    <input type="text" class="form-control" id="action_by" name="action_by"value="{{auth()->user()->id}}" hidden>
                </div> 



                <div class="col-sm-4 mb-4">
                    <label class="form-label" for="event-venue">Artículo</label>
                    <input class="form-control" id="article" name="article" type="text" value="{{ old('article') }}" />
                </div>
                <div class="col-sm-4 mb-3">
                    <label class="form-label" for="event-venue">Precio</label>
                    <input class="form-control" id="precio_inicial" name="precio_inicial" type="text" value="{{ old('precio_inicial') }}" />
                </div>
                <div class="col-sm-4 mb-4">
                    <label class="form-label" for="event-venue">N/S</label>
                    <input class="form-control" id="ns" name="ns" type="text" value="{{ old('ns') }}" />
                </div>
                <div class="col-sm-4 mb-3">
                    <label class="form-label" for="event-venue">Marca</label>
                    <input class="form-control" id="marca" name="marca" type="text" value="{{ old('marca') }}" />
                </div>
                <div class="col-sm-5 mb-3">
                    <label class="form-label" for="event-venue">Modelo</label>
                    <input class="form-control" id="modelo" name="modelo" type="text" value="{{ old('modelo') }}" />
                </div>
                <div class="col-sm-3 mb-3">
                    <label class="form-label" for="event-venue">Categoría</label>
                    <select class="form-control" id="category_id" name="category_id">
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}"> {{ $categoria->category }}</option>    
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-4 mb-3">
                    <label class="form-label" for="event-venue">Descripción</label>
                    <input class="form-control" id="description" name="description" type="text" value="{{ old('description') }}" />
                </div>
                <div class="col-sm-4 mb-3">
                    <label class="form-label" for="event-venue">Comentario</label>
                    <input class="form-control" id="comentario1" name="comentario1" type="text" value="{{ old('comentario1') }}" />
                </div>
                <div class="col-sm-4 mb-3">
                    <label class="form-label" for="datepicker">Fecha de Ingreso</label>
                    <input class="form-control datetimepicker" name="created_at" type="text" placeholder="d/m/y H:i" data-options='{"enableTime":true,"dateFormat":"Y-m-d H:i:S","disableMobile":true}' />
                </div>

            
                            
                <button type="submit" class="btn btn-primary btn-user btn-block">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection