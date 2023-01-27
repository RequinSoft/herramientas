@extends('layouts.template_admin')

@section('title', 'TG - Asignar Categorías')

@section('content')
<div class="card mb-3">
    <div class="card-header text-center">
      <h5 class="mb-0">Asignar Categorías</h5>
      <br>
      <h4 class="text-black-900">{{ $grupos->group }}</h4>
    </div>
    <div class="card-body bg-light">
        <form action="{{ route('grupos.categoriascrear') }}" method="POST">
        @csrf
            <div class="row gx-2">           
                <div class="col-sm-12 mb-3">
                    <input type="text" class="form-control" id="user_id" name="user_id" value="{{auth()->user()->id}}" hidden>
                    <input type="text" class="form-control" id="action_by" name="action_by" value="{{auth()->user()->id}}" hidden>
                    <input type="text" class="form-control" id="group_id" name="group_id"value="{{ $grupos->id }}" hidden>
                </div> 
                
                <div id="tableExample2" data-list='{"valueNames":["numero", "articulo","descripcion", "categoria"],"page":10,"pagination":true}'>
                    <div class="table-responsive scrollbar">
                    <table class="table table-bordered table-striped fs--2 mb-0">
                        <thead class="bg-500 text-900">
                        <tr>
                            <th class="sort" data-sort="numero">N°</th>
                            <th class="sort" data-sort="articulo">Articulo</th>
                            <th class="sort" data-sort="descripcion">Descripción</th>
                            <th class="sort" data-sort="categoria">Categoría</th>
                            <th>Check</th>
                        </tr>
                        </thead>
                        <tbody class="list">
                            @php
                                $n = 1;
                            @endphp
                            @foreach ($categorias as $perm)
                            <tr>
                                <td class="text-center numero">{{ $n }}</td>
                                <td class="articulo">{{ $perm->category }}</td>
                                <td class="descripcion">{{ $perm->description }}</td>
                                <td class="categoria">{{ $perm->categoria->group }}</td>
                                @if ($grupos->id == $perm->categoria->id)
                                    <td class="text-center"><a href="{{ route('grupos.categoriasdefault', [$perm->id, $grupos->id]) }}" class="btn btn-danger btn-sm" title="Inhabilitar"><i class="fas fa-trash"></i></a></td>
                                @else
                                    <td class="text-center"><input id="perm[]" name="perm[]" type="checkbox" value="{{ $perm->id }}"></td>
                                @endif
                            </tr>
                            @php
                                $n++;
                            @endphp
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                    <button class="btn btn-sm btn-falcon-default me-1" type="button" title="Previous" data-list-pagination="prev"><span class="fas fa-chevron-left"></span></button>
                    <ul class="pagination mb-0"></ul>
                    <button class="btn btn-sm btn-falcon-default ms-1" type="button" title="Next" data-list-pagination="next"><span class="fas fa-chevron-right"> </span></button>
                    </div>
                </div>             
                            
                <button type="submit" class="btn btn-primary btn-user btn-block">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection