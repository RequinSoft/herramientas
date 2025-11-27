@extends('layouts.template_admin')

@section('title', 'Nuevo Personal')

@section('content') 

<div class="card mb-3">
    <div class="card-header">
      <h5 class="mb-0">Nuevo Personal</h5>
    </div>
    <div class="card-body bg-light">
        <form action="{{route('personal.crear')}}" method="POST" enctype="multipart/form-data">
        @csrf
            <div class="row gx-2">          
                <div class="col-sm-3 mb-3">
                    <input type="text" class="form-control" id="action_by" name="action_by"
                    value="{{auth()->user()->id}}" hidden>
                </div>   
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
                    <label class="form-label" for="event-venue">Foto de Perfil</label>
                    <input class="form-control"  type="file" name="image" id="image" />
                </div> 
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="event-venue">ID</label>
                    <input class="form-control" id="id" name="id" type="text" value="{{ old('id') }}" />
                </div>
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="event-venue">Nombre Completo</label>
                    <input class="form-control" id="nombre" name="nombre" type="text" value="{{ old('nombre') }}" />
                </div>
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="event-venue">Puesto</label>
                    <input class="form-control" id="puesto" name="puesto" type="text" value="{{ old('puesto') }}" />
                </div>
                <div class="col-sm-6 mb-3">
                    <label class="form-label" for="time-zone">Grupo</label>
                    <select class="form-select" id="group_id" name="group_id">
                        @foreach ($grupos as $grupos)
                        @if ($grupos->id == 1)
                            <option value="{{ $grupos->id }}" selected> {{ $grupos->group }}</option>
                        @else
                            <option value="{{ $grupos->id }}"> {{ $grupos->group }}</option>                                        
                        @endif
                        @endforeach
                    </select>
                </div>
                            
                <button type="submit" class="btn btn-primary btn-user btn-block">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
    @error ('user')
        @if ($message == 'duplicado')
            <script>
                Swal.fire({
                    title: 'El usuario ya existe, ¿Desea activarlo?',
                    showDenyButton: true,
                    confirmButtonText: 'Ir',
                    denyButtonText: `No`,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        window.location.href = "usuarios_inactvos";
                    }
                })
            </script>        
        @endif
    @enderror
@endsection