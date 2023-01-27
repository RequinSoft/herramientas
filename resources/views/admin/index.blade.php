@extends('layouts.template_admin')

@section('title', 'TG - Dashboards')

@section('content')
  <!-- Contenido -->
  <div class="row g-3 mb-3">

    <!-- Valor del Inventario -->
    <div class="col-md-6 col-xxl-6">
      <div class="card h-md-100 ecommerce-card-min-width">
        <div class="card-header pb-0">
          <h6 class="mb-0 mt-2 d-flex align-items-center">Valor del Inventario<span class="ms-1 text-400" data-bs-toggle="tooltip" data-bs-placement="top" title="Depreciación aplicada"><span class="far fa-question-circle" data-fa-transform="shrink-1"></span></span></h6>
        </div>
        <div class="card-body d-flex flex-column justify-content-end">
          <div class="row">
            <div class="col">
              <p class="font-sans-serif lh-1 mb-1 fs-4">$ {{ number_format($valorInventario, 0, ".", ",") }} </p>
            </div>
            <div class="col-auto ps-0">
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Valor del Inventario -->

    <!-- Pérdidas este Mes -->
    <div class="col-md-6 col-xxl-6">
      <div class="card h-md-100">
        <div class="card-header pb-0">
          <h6 class="mb-0 mt-2">Pérdidas este Mes</h6>
        </div>
        <div class="card-body d-flex flex-column justify-content-end">
          <div class="row justify-content-between">
            <div class="col-auto align-self-end">
              <div class="fs-4 fw-normal font-sans-serif text-700 lh-1 mb-1">$ {{ number_format($articuloRobado, 0, ".", ",") }}</div>
            </div>
            <div class="col-auto ps-0 mt-n4">
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Pérdidas este Mes -->
  </div>

  

  <!-- Contenido -->
@endsection