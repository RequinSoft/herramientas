<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\Register;
use App\Models\Article;
use App\Models\Category;
use App\Models\Role;
use App\Models\Group;
use App\Models\User;
use App\Models\Personal;
use App\Models\Line;
use App\Models\Ldap;
use App\Mail\ResetPass;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use App\Mail\SalidasMailable;
use Faker\Provider\ar_EG\Person;
use Illuminate\Support\Facades\Mail;

class ReceptorController extends Controller
{
    //

    /****************************************/
    /************** Index *******************/
    /****************************************/    
    
    public function index_admin(){
        
        $ruta = '';
        $articulosRobadosxMes = [];
        $articulosExtraviadosxMes = [];
        $articulosDisponiblesxMes = [];
        $articulosAsignadosxMes = [];
        $articulosRobadosxMes_Moneda = [];
        $articulosExtraviadosxMes_Moneda = [];
        $articulosRecibidoxCategoria = [];
        $categorias = [];

        $fecha = Carbon::now();
        $esteMes = $fecha->format('m');
        $esteAno = $fecha->format('Y');

        // Valor Inventario
        $entregados = Article::query()->whereIn('status',['Entregado'])->get()->count();
        $recibidos = Article::query()->whereIn('status',['Recibido'])->get()->count();
        //return $recibidos;
        // Artículos Robados
        $articuloRobado_Extraviado = Article::query()->whereIn('status',['Robado', 'Extraviado'])->whereMonth('updated_at', $esteMes)->whereYear('updated_at', $esteAno)->get()->sum('precio_actual');
        // Procentaje de artículos
        $cat = Category::where('status', 'activo')->whereNot('category', 'Default')->get();
        foreach($cat as $categoria){
            $articulosRecibidoxCategoria[] = [Article::where('status', 'Recibido')->where('category_id', $categoria->id)->count()];
            $articulosEntregadoxCategoria[] = [Article::where('status', 'Entregado')->where('category_id', $categoria->id)->count()];
            $categorias[] = $categoria->category;
        }

        //Artículos por Status por mes en el año
        for($i=1; $i<=12; $i++){
            
            $articulosRobadosxMes [] = [Article::query()->whereIn('status',['Robado'])->whereMonth('updated_at', $i)->whereYear('updated_at', $esteAno)->get()->count()];
            $articulosExtraviadosxMes [] = [Article::query()->whereIn('status',['Extraviado'])->whereMonth('updated_at', $i)->whereYear('updated_at', $esteAno)->get()->count()];
            $articulosDisponiblesxMes [] = [Article::query()->whereIn('status',['Disponible'])->whereMonth('updated_at', $i)->whereYear('updated_at', $esteAno)->get()->count()];
            $articulosAsignadosxMes [] = [Article::query()->whereIn('status',['Asignado'])->whereMonth('updated_at', $i)->whereYear('updated_at', $esteAno)->get()->count()];
            
            $articulosRobadosxMes_Moneda [] = [Article::query()->whereIn('status',['Robado'])->whereMonth('updated_at', $i)->whereYear('updated_at', $esteAno)->get()->sum('precio_actual')];
            $articulosExtraviadosxMes_Moneda [] = [Article::query()->whereIn('status',['Extraviado'])->whereMonth('updated_at', $i)->whereYear('updated_at', $esteAno)->get()->sum('precio_actual')];
        }
                
        return view('receptor.index', compact('ruta', 'recibidos', 'entregados', 'articuloRobado_Extraviado', 'esteMes', 'categorias', 'articulosRecibidoxCategoria', 'articulosEntregadoxCategoria', 'articulosRobadosxMes', 'articulosExtraviadosxMes', 'articulosDisponiblesxMes', 'articulosAsignadosxMes', 'articulosRobadosxMes_Moneda', 'articulosExtraviadosxMes_Moneda'));
    }

    
    /****************************************/
    /************** Resguardos **************/
    /****************************************/    

    public function resguardo_buscar_persona(){
        
        $n=0;
        $ruta = '';
        $personal=[];

        $resguardos = Line::query()->where('status', 'Activo')->distinct()->get('personal_id');
        
        foreach($resguardos as $resguardo){
            $personal[] = Personal::query()->where('id', $resguardo->personal_id)->get();             
        }
        return view('receptor.registers.buscar_persona', compact('ruta', 'personal'));
    }

    public function resguardo_editar($id){
        //return $id;
        $ruta = '../';
        $logged_user = auth()->user()->id;
        $group_user = auth()->user()->group_id;
        
        $person = Personal::find($id);
        $articulos = Line::with('articulos', 'usuario')->where('personal_id', $id)->where('status', 'Activo')->get();

        //Foreach para obtener los articulos en un arreglo y después obtener la suma
        foreach($articulos as $art){
            $articulo[] = $art->article_id;
        }
        $suma = Article::query()->whereIn('id', $articulo)->get()->sum('precio_actual');
        
        //return $articulos;
        return view('receptor.registers.resguardo', compact('logged_user', 'group_user', 'articulos', 'ruta', 'person', 'suma'));
    }

    public function resguardo_editar_linea($linea){
        //return "Línea-> ".$linea;

        $ruta = '../';        

        $line = Line::find($linea);
        $person = Personal::find($line->personal_id);
        $articulo = Article::find($line->article_id);

        //return $line;
        //return $person;
        return view('receptor.registers.editarlinea', compact('line', 'person', 'ruta', 'articulo')); 
    }

    public function resguardo_actualizar_linea(){
        //return request();
        
        $estado = request()->status;
        if(request()->status == 'Baja'){
            $estado = 'Inactivo';
        }

        Line::query()->where(['id' => request()->id_linea])->update(['status' => $estado, 'receptor_id' => auth()->user()->id]);
        $linea = Line::find(request()->id_linea);
        
        $articulo = Article::query()->where(['id' => $linea->article_id])->update(['status' => request()->status, 'comentario1' => request()->id_linea]);
        
        $fecha = Carbon::parse($linea->updated_at)->format('d-m-Y');

        $ruta = '../storage/app/reports/';
        if(request()->hasFile('image')){

            //return 'Sí hay imagen';
            $imagen = request()->file('image');
            $nombre_imagen = Str::slug($linea->article_id." - ".$fecha).".".$imagen->guessExtension();
            //return $nombre_imagen;

            copy($imagen->getRealPath(), $ruta.$nombre_imagen);
        }
        $existencia = Line::query()->where('personal_id', $linea->personal_id)->where('status', 'Activo')->get()->count();

        if($existencia != 0){
            return  redirect()->to('/resguardo_receptor_editar/'.$linea->personal_id);
        }else{
            return  redirect()->to('/resguardo_receptor_buscar_persona');
        }
    }   

    public function resguardo_buscar_articulo(){
        
        $ruta = '';
        $date = Carbon::now();

        $articulos = Article::where(['status' => 'Asignado'])->get();
        return view('receptor.registers.buscar_articulo', compact('ruta', 'articulos', 'date'));
    }

    public function asignado_articulo(){
        
        $ruta = '';
        $asignado = [];
        
        $articulos = Article::all();
        $articulo = Article::find(request()->articulos);
        $asignado = Line::with('usuario', 'personal')->where(['article_id'=>$articulo[0]->id, 'status'=> 'Activo'])->get();
        //return $asignado;
        return view('receptor.registers.asignado_articulo', compact('asignado', 'ruta', 'articulo'));       
    }

    public function actualizar_asignado_articulo(){
        //return request();
        if(request()->status == 'Asignado'){
            return  redirect()->to('/resguardo_receptor_buscar_articulo');
        }else{
            if(request()->status == 'Pendiente'){
                Line::query()->where(['id' => request()->id])->update(['status' => 'Pendiente']);
            }else{
                Line::query()->where(['id' => request()->id])->update(['status' => 'Inactivo']); 
            }       
            $linea = Line::find(request()->id);
            $articulo = Article::query()->where(['id' => request()->article_id])->update(['status' => request()->status, 'comentario1' => request()->comentario1]);
            $fecha = Carbon::parse($linea->updated_at)->format('d-m-Y');
    
            //return $fecha;
            $ruta = '../storage/app/reports/';
            if(request()->hasFile('image')){
    
                //return 'Sí hay imagen';
                $imagen = request()->file('image');
                $nombre_imagen = Str::slug($linea->article_id." - ".$fecha).".".$imagen->guessExtension();
                //return $nombre_imagen;
    
                copy($imagen->getRealPath(), $ruta.$nombre_imagen);
            }

            return  redirect()->to('/resguardo_receptor_buscar_persona');
        }
    }

    public function resguardo_reportes(){        
        $ruta = '';

        $resguardos = Line::query()->where('status', 'Activo')->distinct()->get('personal_id');

        return $resguardos;
    }

    public function resguardo_entregar(){
        $ruta = '';

        $articulos = Article::with('category')->where('status', 'Recibido')->get();

        return view('receptor.registers.entregas', compact('ruta', 'articulos'));
    }
    
    public function resguardo_pendientes(){
        $n=0;
        $ruta = '';
        $personal=[];

        $resguardos = Line::query()->where('status', 'Pendiente')->distinct()->get('personal_id');
        //return $resguardos;
        foreach($resguardos as $resguardo){
            $personal[] = Personal::query()->where('id', $resguardo->personal_id)->get();             
        }
        return view('receptor.registers.buscar_pendientes', compact('ruta', 'personal'));
    }

    public function resguardo_finalizar($id){
        //return $id;
        $ruta = '../';
        $articulo = [];
        $logged_user = auth()->user()->id;
        $group_user = auth()->user()->group_id;
        //return $id;
        $person = Personal::find($id);
        //return $person;
        $articulos = Line::with('articulos', 'usuario')->where('personal_id', $id)->where('status', 'Pendiente')->get();

        //Foreach para obtener los articulos en un arreglo y después obtener la suma
        foreach($articulos as $art){
            $articulo[] = $art->article_id;
        }
        //return $articulo;
        $suma = Article::query()->whereIn('id', $articulo)->get()->sum('precio_actual');
        
        //return $articulos;
        return view('receptor.registers.resguardo_finalizar', compact('logged_user', 'group_user', 'articulos', 'ruta', 'person', 'suma'));
    }

    public function resguardo_finalizar_linea($linea){
        //return $linea;
        $ruta = '../';

        $line = Line::find($linea);
        $person = Personal::find($line->personal_id);
        $articulo = Article::find($line->article_id);

        return view('receptor.registers.finalizarlinea', compact('line', 'person', 'ruta', 'articulo')); 
    }

    public function resguardo_actualizar_fin_linea(){
        //return request();
        

        Line::query()->where(['id' => request()->id_linea])->update(['status' => 'Inactivo', 'receptor_id' => auth()->user()->id]);
        $linea = Line::find(request()->id_linea);
        
        $articulo = Article::query()->where(['id' => $linea->article_id])->update(['status' => request()->status, 'comentario1' => request()->id_linea]);
        
        $fecha = Carbon::parse($linea->updated_at)->format('d-m-Y');

        $ruta = '../storage/app/reports/';
        if(request()->hasFile('image')){

            //return 'Sí hay imagen';
            $imagen = request()->file('image');
            $nombre_imagen = Str::slug($linea->article_id." - ".$fecha).".".$imagen->guessExtension();
            //return $nombre_imagen;

            copy($imagen->getRealPath(), $ruta.$nombre_imagen);
        }
        $existencia = Line::query()->where('personal_id', $linea->personal_id)->where('status', 'Pendiente')->get()->count();

        if($existencia != 0){
            return  redirect()->to('/resguardo_receptor_finalizar/'.$linea->personal_id);
        }else{
            return  redirect()->to('/resguardo_pendientes');
        }
    } 

    public function resguardo_editar_entrega($id){
        $ruta = '../';
        $hoy = Carbon::now();

        $articulo = Article::find($id);
        //return $articulo;
        $categoria = Category::find($articulo->category_id);
        //return $categoria;
        $usuarios = User::query()->where('group_id', $categoria->group_id)->whereNot('status', 'Inactivo')->get();
        //return $usuarios;
        return view('receptor.registers.editarentrega', compact('ruta', 'articulo', 'hoy', 'usuarios'));
    }

    public function resguardo_cerrar_entrega(){
        $ruta = '';
        $fecha = Carbon::now();
        $hoy = $fecha->format('d-m-Y');

        $this->validate(request(), [
                'entregado' => 'required',
            ],
            [
                'entregado.required' => 'El usuario al que se entrega es obligatorio',
            ]
        );
        
        $ruta_firma = '../storage/app/firmas/entregas/';
        $nombre_imagen = 'user_' . request()->entregado . '_fecha_' . $hoy;

        $image_parts = explode(";base64,", request()->signed);
             
        $image_type_aux = explode("image/", $image_parts[0]);
           
        $image_type = $image_type_aux[1];
           
        $image_base64 = base64_decode($image_parts[1]);
 
        $signature = $nombre_imagen. '.'.$image_type;
           
        $file = $ruta_firma . $signature;


        file_put_contents($file, $image_base64);

        //return request();
        $articulo = Article::query()->where(['id' => request('id')])->update(['status' => 'Disponible']); 
        $linea = Line::query()->where(['id' => request('id_linea')])->update(['status' => 'Entregado', 'comentario' => request('comentario1'), 'entregado' => request('entregado'), 'firma_entrega' => $file]);

        return  redirect()->to('/resguardo_entregar');
    }

    public function index_entregados(){
        $ruta = '';

        $entregados = Line::with('articulos', 'entregado_a')->where('status', 'Entregado')->get();

        //return $entregados;
        return view('receptor.registers.index_entregados', compact('ruta', 'entregados'));
    }

    
    /****************************************/
    /*************** Historial **************/
    /****************************************/    
    public function buscar_historial_articulo(){
        $ruta = '';
        $date = Carbon::now();

        $articulos = Article::all();
        //return $articulos;

        return view('receptor.historial.buscar_historial_articulo', compact('articulos', 'ruta', 'date'));
    }

    public function historial_articulo(){
        $ruta = '';
        $fecha = [];

        $articulo = Article::find(request()->articulos);
        $historial = Line::with('usuario', 'articulos', 'personal')->where(['article_id' => request()->articulos])->get();

        foreach($historial as $hist){            
            $fecha[] = Carbon::parse($hist->updated_at)->format('d-m-Y');
        }
        //return $historial;
        return view('receptor.historial.historial_articulo', compact('historial', 'ruta', 'articulo', 'fecha'));
    }  

    public function buscar_historial_persona(){
        $ruta = '';
        $date = Carbon::now();

        $personas = Personal::all();
        //return $articulos;

        return view('receptor.historial.buscar_historial_persona', compact('personas', 'ruta', 'date'));
    }

    public function historial_persona(){
        $ruta = '';
        $fecha = [];

        $persona = Personal::find(request()->persona);
        $historial = Line::with('usuario', 'articulos')->where(['personal_id' => $persona->id])->get();

        foreach($historial as $hist){            
            $fecha[] = Carbon::parse($hist->updated_at)->format('d-m-Y');
        }
        //return $historial;
        return view('receptor.historial.historial_persona', compact('historial', 'ruta', 'persona', 'fecha'));
    } 
}
