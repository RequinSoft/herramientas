@extends('layouts.login')

@section('content')


    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
      <div class="container-fluid">
        <div class="row min-vh-100 flex-center g-0">
          <div class="col-lg-8 col-xxl-5 py-3 position-relative"><img class="bg-auth-circle-shape" src="{{ $ruta }}../resources/assets/img/icons/spot-illustrations/bg-shape.png" alt="" width="250"><img class="bg-auth-circle-shape-2" src="{{ $ruta }}../resources/assets/img/icons/spot-illustrations/shape-1.png" alt="" width="150">
            <div class="card overflow-hidden z-index-1">
              <div class="card-body p-0">
                <div class="row g-0 h-100">
                  <div class="col-md-5 text-center bg-card-gradient">
                    <div class="position-relative p-4 pt-md-5 pb-md-7 light">
                      <div class="bg-holder bg-auth-card-shape" style="background-image:url({{ $ruta }}../resources/assets/img/icons/toolguardian-blue_200.png);">
                      </div>
                      <!--/.bg-holder-->

                      <div class="z-index-1 position-relative"><a class="link-light mb-4 font-sans-serif fs-4 d-inline-block fw-bolder" href="#">Herramientas</a>
                        <p class="opacity-75 text-white">&nbsp;</p>
                        <p class="opacity-75 text-white">&nbsp;</p>
                        <p class="opacity-75 text-white">¡Las herramientas en tus manos!</p>
                        <p class="opacity-75 text-white">&nbsp;</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-7 d-flex flex-center">
                    <div class="p-4 p-md-5 flex-grow-1">
                      <div class="row flex-between-center">
                        <div class="col-auto">
                          <h3>Herramientas</h3>
                        </div>
                      </div>
                      <form action="home" method="POST">
                        @csrf
                        <div class="mb-3">
                          <label class="form-label" for="card-email">Usuario</label>
                          <input class="form-control" name="user" type="text" />
                        </div>
                        <div class="mb-3">
                          <div class="d-flex justify-content-between">
                            <label class="form-label" for="card-password">Contraseña</label>
                          </div>
                          <input class="form-control" name="password" type="password" />
                        </div>
                        
                        <div class="mb-3">
                          <button class="btn btn-primary d-block w-100 mt-3" type="submit">Ingresar</button>
                        </div>

                        @error('message')
                        <div class="form-group">
                            <button type="text" disabled class="btn btn-danger btn-block"
                                id="error" name="error">
                                {{$message}}
                            </button>
                        </div>
                        @enderror

                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
    <!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->

@endsection

@section('script')
  @if (session('info'))
      <script>
        colsole.log('Hola');
          Swal.fire({
              title: "Articulo Dado de Baja",
              text: "{{ session('info') }}",
              confirmButtonText: "Aceptar",
          });
      </script>
  @endif
@endsection