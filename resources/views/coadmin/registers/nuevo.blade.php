@extends('layouts.template_coadmin')

@section('title', 'Nuevos Resguardos')

@section('content')

<div class="card mb-3">
    <div class="card-header">
      <h5 class="mb-0">Nuevo Resguardo</h5>
    </div>
    <div class="card-body bg-light">
        <form action="{{route('resguardo.coadmin_resumen')}}" method="POST">
        @csrf    
                <div class="col-sm-6 mb-3">
                    <input type="text" class="form-control" id="action_by" name="action_by"value="{{auth()->user()->id}}" hidden>
                </div> 
                <div class="col-sm-6 mb-1">
                    @error('person')
                        <div class="form-group">
                            <button type="text" disabled class="btn btn-danger btn-block" id="error" name="error">
                                {{$message}}
                            </button>
                        </div>
                    @enderror
                    @error('articulos')
                        <div class="form-group">
                            <button type="text" disabled class="btn btn-danger btn-block"
                                id="error" name="error">
                                {{$message}}
                            </button>
                        </div>
                    @enderror
                </div> 


                <div class="col-sm-12 mb-4">
                    <label class="form-label" for="person">Personal</label>
                    <select class="form-select js-choice" id="person" size="1" name="person" data-options='{"removeItemButton":true,"placeholder":true}'>
                        <option value="">Selecciona a la Persona...</option>
                        @foreach ($personal as $personal)
                            <option value="{{ $personal->id }}"> {{ $personal->nombre }}</option>    
                        @endforeach
                      </select>
                </div>
                <div class="col-sm-12 mb-12">
                    <label for="organizerMultiple">Artículos</label>
                    <select class="form-select js-choice" id="articulos[]" multiple="multiple" size="3" name="articulos[]" data-options='{"removeItemButton":true,"placeholder":true}'>
                        <option value="">Selecciona de 1 a Varios Artículos...</option>
                        @foreach ($articulos as $articulo)
                            <option value="{{ $articulo->id }}"> {{ $articulo->article }} -- {{ $articulo->ns }} </option>    
                        @endforeach
                    </select>
                </div>
                
                <!--
                <div id="tableExample" data-list='{"valueNames":["articulo","ns", "modelo"],"page":10,"pagination":true}'>
                    <div class="table-responsive scrollbar">
                    <table class="table table-bordered table-striped fs--1 mb-0">
                        <thead class="bg-200 text-900">
                        <tr>
                            <th>Escoger</th>
                            <th>Articulo</th>
                            <th>N/S</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Comentario</th>
                            <th>Precio Actual</th>
                        </tr>
                        </thead>
                        <tbody class="list">
                            @foreach ($articulos as $articulo)
                                <tr>
                                    <td class="text-center"><input id="articulos[]" name="articulos[]" type="checkbox" value="{{ $articulo->id }}"></td>
                                    <td>{{ $articulo->article }}</td>
                                    <td>{{ $articulo->ns }}</td>
                                    <td>{{ $articulo->marca }}</td>
                                    <td>{{ $articulo->modelo }}</td>
                                    <td>{{ $articulo->comentario }}</td>
                                    <td class="text-end precio_actual">$ {{ number_format($articulo->precio_actual, 0, ".", ",") }}</td>
                                </tr>
    
                                
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                    <div class="row align-items-center mt-3">
                      <div class="pagination d-none"></div>
                      <div class="col">
                        <p class="mb-0 fs--1">
                          <span class="d-none d-sm-inline-block" data-list-info="data-list-info"></span>
                          <span class="d-none d-sm-inline-block"> &mdash; </span>
                          <a class="fw-semi-bold" href="#!" data-list-view="*">View all<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a><a class="fw-semi-bold d-none" href="#!" data-list-view="less">View Less<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
                        </p>
                      </div>
                      <div class="col-auto d-flex">
                        <button class="btn btn-sm btn-primary" type="button" data-list-pagination="prev"><span>Previous</span></button>
                        <button class="btn btn-sm btn-primary px-4 ms-2" type="button" data-list-pagination="next"><span>Next</span></button>
                      </div>
                    </div>
                </div>
                -->
                <div class="col-sm-6 mb-3">
                    
                </div>

            
                            
                <button type="submit" class="btn btn-primary btn-user btn-block">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- /.container-fluid -->
@endsection