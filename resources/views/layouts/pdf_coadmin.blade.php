<html>
<head>
    
  <link rel="shortcut icon" type="image/x-icon" href="{{ $ruta }}../resources/assets/img/favicons/toolguardian.ico">

  <style>
    body{
      font-family: sans-serif;
    }
    @page {
      margin: 160px 50px;
    }
    header { 
      position: fixed;
      left: 0px;
      top: -160px;
      right: 0px;
      height: 140px;
      background-color: #ddd;
      text-align: center;
    }    
    
    header .logo {
        float: left;
        margin-left: 20px;
    }
    header h1{
      margin: 10px 0;
    }
    header h2{
      margin: 0 0 10px 0;
    }
    footer {
      position: fixed;
      left: 0px;
      bottom: -50px;
      right: 0px;
      height: 40px;
      border-bottom: 2px solid #ddd;
    }
    footer .page:after {
      content: counter(page);
    }
    footer table {
      width: 100%;
    }
    footer p {
      text-align: left;
    }
    footer .der {
      text-align: right;
    }
    
    /* Curso CSS estilos aprenderaprogramar.com*/
    body {
        font-family: Arial, Helvetica, sans-serif;
    }

    table {   
        font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
        font-size: 12px; 
        margin-top: 50px;   
        margin: 10px;     
        width: 680px;
        text-align: left;    
        border-collapse: collapse; 
    }

    th {     
        font-size: 13px;     
        font-weight: normal;     
        padding: 8px;     
        background: #0633c7;
        border-top: 4px solid #aabcfe;    
        border-bottom: 1px solid #fff; 
        color: white; 
    }

    td {    
        padding: 8px;         
        border-bottom: 1px solid #fff;
        color: #669;    
        border-top: 1px solid transparent; 
    }

    tr:hover td { 
        background: #fdfcd0; 
        color: #339; 
    }

    tbody tr:nth-child(odd) {
        background: #d4d4d2;

    }
    tbody tr:nth-child(even) {
        background: #aabcfe;

    }
    .imgRedonda-logo{
        width: 120px;
        height: 120px;
    }

    .tarjeta {
        text-align: right;
        display:flex;
        flex-direction:column;
        justify-content:space-between;
        width:700px;
        border: 1px solid lightgray;
        box-shadow: 2px 2px 8px 4px #d3d3d3d1;
        border-radius:15px;
        font-family: sans-serif;
    }

    .persona {
        font-size: 24px;
        padding: 10px 10px 0 10px;
    }
    .cuerpo{
        padding: 10px;
    }
    .nota{
        margin-top: 30px;
        color: rgb(81, 70, 243);
        text-align: justify;
        display:flex;
        flex-direction:column;
        justify-content:space-between;
        width:700px;
        box-shadow: 2px 2px 8px 4px #d3d3d3d1;
        border-radius:15px;
        font-family: sans-serif;
    }
  </style>
  <title>Resguardo de {{$person->nombre}}</title>

  
<body>
  <header>
    
    <div class="logo">
        @if (file_exists("../storage/app/public/logo.jpg"))
            <img class="imgRedonda-logo" src="{{ $ruta }}../storage/app/public/logo.jpg"/>
        @else
            <img class="imgRedonda" src="{{ $ruta }}../storage/app/public/empresa.jpg"  />
        @endif
    </div>
    <h1>{{$empresa}}</h1>
    <h2>Resguardo</h2>
    <h3 style="color: blue">Departamento de {{ $departamento[0]->group }}</h3>
  </header>

  <footer>
    <table>
      <tr>
        <td>
            <p class="page">
                Página
            </p>
        </td>
        <td>
          <p class="der">
            {{$hoy}}
            
          </p>
        </td>
      </tr>
    </table>
  </footer>  
    @php
        $n=1;
    @endphp
    <div class="tarjeta">
        <div class="persona">
            {{$person->nombre}}
        </div>
        <div class="cuerpo">
            {{$person->puesto}}
        </div>
    </div>
    
  <div>
    <table>
        <tr>
            <th scope="row">N°</th>
            <th>Artículo</th>
            <th>N/S</th>
            <th>Fecha</th>
            <th>Costo</th>
        </tr>
        
        @foreach ($articulos as $articulo)
            <tr>
                <td scope="row" style="text-align: center">{{ $n }}</td>
                <td>{{ $articulo->articulos->article }}</td>
                <td>{{ $articulo->articulos->ns }}</td>
                <td style="text-align: right">{{ $articulo->articulos->updated_at }}</td>
                <td style="text-align: right">$ {{ number_format($articulo->articulos->precio_actual, 0, ".", ",") }}</td>
            </tr>
            @php
                $n++;
            @endphp
        @endforeach

        
        <tr>
            <td scope="row" style="text-align: center"></td>
            <td></td>
            <td></td>
            <td class="total" style="text-align: right;">Total</td>
            <td style="text-align: right">$ {{ number_format($suma, 0, ".", ",") }}</td>
        </tr>

    </table>    
  </div>

  <div class="nota">
    ** Documento de referencia, este enlista los artículos asignados a la persona mencionada hasta la fecha en la que el documento fue emitido. 
    Este listado puede variar si se sufren cambios en los artículos asignados ya sea por reparación, daño o adición. 
    Consta que para cada cambio que surge en el sistema se registra la firma del personal mencionado y este queda guardado en sistema para cualquier duda y/o aclaración.
  </div>
</body>
</html>