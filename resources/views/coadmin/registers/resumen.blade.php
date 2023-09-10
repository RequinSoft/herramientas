@extends('layouts.template_coadmin')

@section('title', 'Resumen Resguardo Nuevo')

@section('content')

<div class="d-flex bg-200 mb-3 flex-row">
   
</div>

<div class="card mb-3">
    <div class="card-header position-relative min-vh-25 mb-7">
      <div class="bg-holder rounded-3 rounded-bottom-0" style="background-image:url({{ $ruta }}../resources/assets/img/generic/4.jpg);">
      </div>
      <!--/.bg-holder-->
      <div class="avatar avatar-5xl avatar-profile">
        @if (file_exists("../storage/app/avatars/personal-".$person->id.".".$person->ext))
            <img class="rounded-circle img-thumbnail shadow-sm" src="{{ $ruta }}../storage/app/avatars/personal-{{$person->id}}.{{$person->ext}}" width="200" alt="" />
        @else
            <img class="rounded-circle img-thumbnail shadow-sm" src="{{ $ruta }}../storage/app/avatars/avatar.png" width="200" alt="" />
        @endif
      </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-8">
                <h4 class="mb-1">  {{$person->nombre}}</h4>
                <h5 class="fs-0 fw-normal">{{$person->puesto}}</h5>

                <div class="border-bottom border-dashed my-4 d-lg-none">

                </div>
            </div>

            <div class="col ps-2 ps-lg-3">
                <a class="d-flex align-items-center mb-2" href="#"><span class="fas fa-dollar-sign fs-3 me-2 text-700" data-fa-transform="grow-2"></span>
                    <div class="flex-1">
                        <h4 class="mb-0">{{ number_format($suma, 0, ".", ",") }}</h4>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@php
    $n=1;
@endphp
<div id="tableExample2" data-list='{"valueNames":["articulo", "precio_actual", "creado", "estado"]}'>
    
    <form action="resguardo_coadmin_crear" method="POST">
        @csrf  

        
        <input type="text" class="form-control" id="personal_id" name="personal_id" value="{{$person->id}}" hidden>
        <input type="text" class="form-control" id="users_id" name="users_id" value="{{auth()->user()->id}}" hidden>

    <div class="table-responsive scrollbar">
      <table class="table table-bordered table-striped fs--2 mb-0">
        <thead class="bg-500 text-900">
          <tr>
            <th>N°</th>
            <th class="sort" data-sort="articulo">Artículo</th>
            <th class="sort" data-sort="precio_actual">Costo Actual</th>
          </tr>
        </thead>
        <tbody class="list">
            @foreach ($articulo as $articulo)
            <tr>
                <input type="text" class="form-control" id="articulos[]" name="articulos[]" value="{{$articulo->id}}" hidden>
                <td>{{ $n }}</td>
                <td class="articulo">{{ $articulo->article }}</td>
                <td class="text-end precio_actual">$ {{ number_format($articulo->precio_actual, 0, ".", ",") }}</td>
            </tr>
            @php
                $n++;
            @endphp
            @endforeach
        </tbody>
      </table>

      <br>
              <div class="contenedor text-center">
      
                  <div class="row">
                      <div class="col-md-12">
                          <canvas id="draw-canvas" width="620" height="360">
                              No tienes un buen navegador.
                          </canvas>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-12">
      
                                  <input hidden type="text" id="color" value="#0000ff">
                                  <input hidden type="range" id="puntero" min="1" default="3" max="5" width="10%">
      
      
                      </div>
      
                  </div>
      
                  <br/>
                  <div class="row">
                      <div class="col-md-12">
                          <textarea hidden id="draw-dataUrl" name="signed" class="form-control" rows="5">Para los que saben que es esto:</textarea>
                      </div>
                  </div>
              </div>
      
            <div class="form-control text-center">
              
              <center>
                  <button type="button" id="draw-clearBtn" class="btn btn-danger btn-user">
                      Borrar Firma
                  </button>
                  <button type="submit" id="draw-submitBtn" class="btn btn-primary btn-user btn-block">
                      Guardar
                  </button>   
              </center>
            </div>
        
      </form>
    </div>
</div>

<!-- /.container-fluid -->
@endsection

@section('script')  
<script>
    /*
		El siguiente codigo en JS Contiene mucho codigo
		de las siguietes 3 fuentes:
		https://stipaltamar.github.io/dibujoCanvas/
		https://developer.mozilla.org/samples/domref/touchevents.html - https://developer.mozilla.org/es/docs/DOM/Touch_events
		http://bencentra.com/canvas/signature/signature.html - https://bencentra.com/code/2014/12/05/html5-canvas-touch-events.html
    */

    (function() { // Comenzamos una funcion auto-ejecutable

        // Obtenenemos un intervalo regular(Tiempo) en la pamtalla
        window.requestAnimFrame = (function (callback) {
            return window.requestAnimationFrame ||
                        window.webkitRequestAnimationFrame ||
                        window.mozRequestAnimationFrame ||
                        window.oRequestAnimationFrame ||
                        window.msRequestAnimaitonFrame ||
                        function (callback) {
                            window.setTimeout(callback, 1000/60);
                // Retrasa la ejecucion de la funcion para mejorar la experiencia
                        };
        })();

        // Traemos el canvas mediante el id del elemento html
        var canvas = document.getElementById("draw-canvas");
        var ctx = canvas.getContext("2d");


        // Mandamos llamar a los Elemetos interactivos de la Interfaz HTML
        var drawText = document.getElementById("draw-dataUrl");
        var drawImage = document.getElementById("draw-image");
        var clearBtn = document.getElementById("draw-clearBtn");
        var submitBtn = document.getElementById("draw-submitBtn");
        clearBtn.addEventListener("click", function (e) {
            // Definimos que pasa cuando el boton draw-clearBtn es pulsado
            clearCanvas();
            drawImage.setAttribute("src", "");
        }, false);
            // Definimos que pasa cuando el boton draw-submitBtn es pulsado
        submitBtn.addEventListener("click", function (e) {
        var dataUrl = canvas.toDataURL();
        drawText.innerHTML = dataUrl;
        drawImage.setAttribute("src", dataUrl);
        }, false);

        // Activamos MouseEvent para nuestra pagina
        var drawing = false;
        var mousePos = { x:0, y:0 };
        var lastPos = mousePos;
        canvas.addEventListener("mousedown", function (e)
        {
        /*
        Mas alla de solo llamar a una funcion, usamos function (e){...}
        para mas versatilidad cuando ocurre un evento
        */
            var tint = document.getElementById("color");
            var punta = document.getElementById("puntero");
            console.log(e);
            drawing = true;
            lastPos = getMousePos(canvas, e);
        }, false);
        canvas.addEventListener("mouseup", function (e)
        {
            drawing = false;
        }, false);
        canvas.addEventListener("mousemove", function (e)
        {
            mousePos = getMousePos(canvas, e);
        }, false);

        // Activamos touchEvent para nuestra pagina
        canvas.addEventListener("touchstart", function (e) {
            mousePos = getTouchPos(canvas, e);
        console.log(mousePos);
        e.preventDefault(); // Prevent scrolling when touching the canvas
            var touch = e.touches[0];
            var mouseEvent = new MouseEvent("mousedown", {
                clientX: touch.clientX,
                clientY: touch.clientY
            });
            canvas.dispatchEvent(mouseEvent);
        }, false);
        canvas.addEventListener("touchend", function (e) {
        e.preventDefault(); // Prevent scrolling when touching the canvas
            var mouseEvent = new MouseEvent("mouseup", {});
            canvas.dispatchEvent(mouseEvent);
        }, false);
        canvas.addEventListener("touchleave", function (e) {
        // Realiza el mismo proceso que touchend en caso de que el dedo se deslice fuera del canvas
        e.preventDefault(); // Prevent scrolling when touching the canvas
        var mouseEvent = new MouseEvent("mouseup", {});
        canvas.dispatchEvent(mouseEvent);
        }, false);
        canvas.addEventListener("touchmove", function (e) {
        e.preventDefault(); // Prevent scrolling when touching the canvas
            var touch = e.touches[0];
            var mouseEvent = new MouseEvent("mousemove", {
                clientX: touch.clientX,
                clientY: touch.clientY
            });
            canvas.dispatchEvent(mouseEvent);
        }, false);

        // Get the position of the mouse relative to the canvas
        function getMousePos(canvasDom, mouseEvent) {
            var rect = canvasDom.getBoundingClientRect();
        /*
        Devuelve el tamaño de un elemento y su posición relativa respecto
        a la ventana de visualización (viewport).
        */
            return {
                x: mouseEvent.clientX - rect.left,
                y: mouseEvent.clientY - rect.top
            };
        }

        // Get the position of a touch relative to the canvas
        function getTouchPos(canvasDom, touchEvent) {
            var rect = canvasDom.getBoundingClientRect();
        console.log(touchEvent);
        /*
        Devuelve el tamaño de un elemento y su posición relativa respecto
        a la ventana de visualización (viewport).
        */
            return {
                x: touchEvent.touches[0].clientX - rect.left, // Popiedad de todo evento Touch
                y: touchEvent.touches[0].clientY - rect.top
            };
        }

        // Draw to the canvas
        function renderCanvas() {
            if (drawing) {
        var tint = document.getElementById("color");
        var punta = document.getElementById("puntero");
        ctx.strokeStyle = tint.value;
        ctx.beginPath();
                ctx.moveTo(lastPos.x, lastPos.y);
                ctx.lineTo(mousePos.x, mousePos.y);
        console.log(punta.value);
            ctx.lineWidth = punta.value;
                ctx.stroke();
        ctx.closePath();
                lastPos = mousePos;
            }
        }

        function clearCanvas() {
            canvas.width = canvas.width;
        }

        // Allow for animation
        (function drawLoop () {
            requestAnimFrame(drawLoop);
            renderCanvas();
        })();

    })();
</script>
@endsection