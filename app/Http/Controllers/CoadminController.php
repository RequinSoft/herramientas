<?php

namespace App\Http\Controllers;

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

class CoadminController extends Controller
{
    //Rutas del perfil de Administrador

    /****************************************/
    /************* Usuarios *****************/
    /****************************************/    
    public function index_admin(){
        $ruta = '';
        $articulosRobadosxMes = [];
        $articulosExtraviadosxMes = [];
        $articulosDisponiblesxMes = [];
        $articulosAsignadosxMes = [];
        $articulosRobadosxMes_Moneda = [];
        $articulosExtraviadosxMes_Moneda = [];
        $articulosDisponiblexCategoria = [];
        $categorias = [];

        $fecha = Carbon::now();
        $esteMes = $fecha->format('m');
        $esteAno = $fecha->format('Y');
        $id = auth()->user()->id;

        //Obtener el grupo del Usuario
        $grupo = Group::query()->where('id', $id)->get();
        
        //Categorías del Grupo al que pertence el usuario
        $cat_grupo = Category::query()->where('group_id', $grupo[0]->id)->get('id');
        
        // Valor Inventario
        $valorInventario = Article::query()->whereIn('status',['Disponible', 'Asignado', 'En Reparacion'])->whereIn('category_id', $cat_grupo)->get()->sum('precio_actual');
        // Artículos Robados
        $articuloRobado = Article::query()->whereIn('status',['Robado', 'Extraviado'])->whereMonth('updated_at', $esteMes)->whereYear('updated_at', $esteAno)->whereIn('category_id', $cat_grupo)->get()->sum('precio_actual');
        // Procentaje de artículos
        $cat = Category::query()->where('status', 'activo')->whereNot('category', 'Default')->where('group_id', $grupo[0]->id)->get();
        foreach($cat as $categoria){
            $articulosDisponiblexCategoria[] = [Article::where('status', 'Disponible')->where('category_id', $categoria->id)->count()];
            $categorias[] = $categoria->category;
        }

        //Artículos por Status por mes en el año
        for($i=1; $i<=12; $i++){
            
            $articulosRobadosxMes [] = [Article::query()->whereIn('status',['Robado'])->whereMonth('updated_at', $i)->whereYear('updated_at', $esteAno)->whereIn('category_id', $cat_grupo)->get()->count()];
            $articulosExtraviadosxMes [] = [Article::query()->whereIn('status',['Extraviado'])->whereMonth('updated_at', $i)->whereYear('updated_at', $esteAno)->whereIn('category_id', $cat_grupo)->get()->count()];
            $articulosDisponiblesxMes [] = [Article::query()->whereIn('status',['Disponible'])->whereMonth('updated_at', $i)->whereYear('updated_at', $esteAno)->whereIn('category_id', $cat_grupo)->get()->count()];
            $articulosAsignadosxMes [] = [Article::query()->whereIn('status',['Asignado'])->whereMonth('updated_at', $i)->whereYear('updated_at', $esteAno)->whereIn('category_id', $cat_grupo)->get()->count()];
            
            $articulosRobadosxMes_Moneda [] = [Article::query()->whereIn('status',['Robado'])->whereMonth('updated_at', $i)->whereYear('updated_at', $esteAno)->whereIn('category_id', $cat_grupo)->get()->sum('precio_actual')];
            $articulosExtraviadosxMes_Moneda [] = [Article::query()->whereIn('status',['Extraviado'])->whereMonth('updated_at', $i)->whereYear('updated_at', $esteAno)->whereIn('category_id', $cat_grupo)->get()->sum('precio_actual')];
        }
                
        return view('coadmin.index', compact('ruta', 'valorInventario', 'articuloRobado', 'esteMes', 'categorias', 'articulosDisponiblexCategoria', 'articulosRobadosxMes', 'articulosExtraviadosxMes', 'articulosDisponiblesxMes', 'articulosAsignadosxMes', 'articulosRobadosxMes_Moneda', 'articulosExtraviadosxMes_Moneda'));
    }

    
    /****************************************/
    /************** Artículos ***************/
    /****************************************/
        
    public function articulo_index(){

        $ruta = '';
        $date = Carbon::now();
        $group_user = auth()->user()->group_id;
                
        $cat_grupo = Category::query()->where('group_id', $group_user)->get('id');

        $datos = Article::with('category')->whereNotIn('status', ['Danado', 'Baja'])->whereIn('category_id', $cat_grupo)->get();
        
        return view('coadmin.articulos.index', compact('datos', 'ruta', 'date'));                
    }

    public function articulo_nuevo(){

        $ruta = '';
        $group_user = auth()->user()->group_id;

        $categorias = Category::query()->where('group_id', $group_user)->get();

        return view('coadmin.articulos.nuevo', compact('categorias', 'ruta'));
    }

    public function articulo_crear(){
        
        $this->validate(request(), [
                'article' => 'required',
                'modelo' => 'required',
                'precio_inicial' => 'required',
                'ns' => 'required|unique:articles',
                'created_at' => 'required',
            ],
            [
                'article.required' => 'El Artículo es Obligatorio',
                'modelo.required' => 'El Modelo es Obligatorio',
                'ns.required' => 'El Número de Serie es Obligatorio',
                'ns.unique' => 'El Número de Serie ya existe',
                'created_at.required' => 'La Fecha es obligatoria',
            ]
        );

        //return request();
        $datos = Article::create(request(['article', 'precio_inicial', 'description', 'ns', 'category_id', 'marca', 'modelo', 'comentario1']));
        $last = Article::all()->last()->id;
        $hoy = Carbon::now();
        $update = Article::query()->where(['id' => $last])->update(['created_at' => request()->created_at]);
        $articulo = Article::find($last);

        $categoria = Category::find(request()->category_id);
        $depreciacion = $categoria->depreciacion;
        $dias_restar = ($articulo->created_at)->diffInDays($hoy);

        $dias_precio = $depreciacion - $dias_restar;

        $precio_actual = ((request()->precio_inicial)/$depreciacion) * $dias_precio;
        $precio_actual = (int)$precio_actual;
        if($precio_actual <= 0){
            $precio_actual = 0;
        }
        $update2 = Article::query()->where(['id' => $last])->update(['precio_actual' => $precio_actual]);

        return  redirect()->to('/coadmin_articulos')->with('articulo_add', $datos->article);
    }

    public function articulos_editar($id){

        $ruta = '../';
        $group_user = auth()->user()->group_id;
        $datos = Article::find($id);

        $categorias = Category::query()->where('group_id', $group_user)->get();

        return view('coadmin.articulos.editar', compact('datos', 'categorias', 'ruta'));
    }

    public function articulos_actualizar(){

        $id = request (['id']);
        $ns = "Artículo ".request()->article." -- N/S ".request()->ns;
        
        $updatedatos = Article::query()->where(['id' => $id])->update(request(['article', 'description', 'ns', 'category_id', 'marca', 'modelo', 'comentario1', 'precio_inicial']));
        
        return  redirect()->to('/coadmin_articulos')->with('articulo_update', $ns);
    }

    public function articulo_inactivar($id){

        $datos = Article::query()->where(['id' => $id])->update(['status' => 'baja']);
        $articulo = Article::find($id);
        $info = $articulo->article." con N/S ".$articulo->ns;

        return  redirect()->to('/coadmin_articulos')->with('info', $info);
    }

    
    /****************************************/
    /************** Resguardos **************/
    /****************************************/
    
    public function resguardo_admin(){

        $ruta = '';
        $registros = Register::with('user2', 'user', 'group')->get()->whereNotIn('status', ['cancelado', 'finalizado']);
        
        //return $registros;
        return view('admin.registers.index', compact('registros', 'ruta'));
    }

    public function resguardo_nuevo(){

        $ruta = '';
        //$grupos = Group::all();
        $personal = Personal::all();
        $id = auth()->user()->id;

        //Obtener el grupo del Usuario
        $grupo = auth()->user()->group_id;
        
        //Categorías del Grupo al que pertence el usuario
        $cat_grupo = Category::query()->where('group_id', $grupo)->get('id');
        //Artículos x Grupo al que pertenece el Usuario
        $articulos = Article::with('category')->where('status', 'Disponible')->whereIn('category_id', $cat_grupo)->get();

        //return $articulos;
        return view('coadmin.registers.nuevo', compact('articulos', 'personal', 'ruta'));
    }

    public function resguardo_resumen(){

        $ruta = '';
        $this->validate(request(), [
                'person' => 'required',
                'articulos' => 'required'
            ],
            [
                'person.required' => '¡La Persona es Obligatoria!',
                'articulos.required' => '¡Se debe escoger al menos 1 Artículo!',
            ]
        );
        //return request();

        $logged_user = auth()->user()->id;
        $group_user = auth()->user()->group_id;
        $articulos = request()->articulos;
                
        //Buscar la persona seleccionada
        $person = Personal::find(request()->person);
        
        $articulo = Article::query()->whereIn('id', request()->articulos)->get();
        $suma = Article::query()->whereIn('id', request()->articulos)->get()->sum('precio_actual');

        //return $suma;
        /*
        foreach($articulos as $articulo){
            
            $line = Line::create(['article_id'=>$articulo, 'register_id'=>$folio]);
            //$lineas = Line::query()->where(['id' => $line_id[$i]])->update(['article'=>$desc[$i], 'quantity'=>$cant[$i]]);
            $updateArticle = Article::query()->where(['id' => $articulo])->update(['status'=>'En Reparacion']);
        }
        */        
        return view('coadmin.registers.resumen', compact('logged_user', 'group_user', 'articulo', 'ruta', 'person', 'suma')); 
    }

    public function resguardo_crear(){
        //return request();
        //return request()->personal_id;
        //return request()->users_id;
        //return request()->articulos;
        $ruta = '';

        foreach(request()->articulos as $articulos){
            
            $lines = new Line();
            $lines->article_id = $articulos;
            $lines->personal_id = request()->personal_id;
            $lines->users_id = auth()->user()->id;
            $lines->comentario = "";
            $lines->status = "Activo";
            $lines->save();

            $update = Article::query()->where(['id' => $articulos])->update(['status' => "Asignado"]);
        }
        return  redirect()->to('/coadmin_index');
    }

    public function resguardo_buscar_persona(){
        
        $n=0;
        $ruta = '';
        $personal=[];

        $id_grupo = auth()->user()->group_id;
        
        $cat_grupo = Category::query()->where('group_id', $id_grupo)->get('id');

        $articulos_grupo = Article::query()->whereIn('category_id', $cat_grupo)->where('status', 'Asignado')->get('id');
        $resguardos = Line::query()->where('status', 'Activo')->whereIn('article_id', $articulos_grupo)->distinct()->get('personal_id');

        //return $resguardos;

        foreach($resguardos as $resguardo){
            $personal[] = Personal::query()->where('id', $resguardo->personal_id)->get();             
        }

        return view('coadmin.registers.buscar_persona', compact('ruta', 'personal'));
    }

    public function resguardo_editar($id){
        //return $id;
        $ruta = '../';
        $logged_user = auth()->user()->id;
        $group_user = auth()->user()->group_id;
                
        $cat_grupo = Category::query()->where('group_id', $group_user)->get('id');
        $articulos_grupo = Article::query()->whereIn('category_id', $cat_grupo)->where('status', 'Asignado')->get('id');

        $person = Personal::find($id);

        $articulos = Line::with('articulos', 'usuario')->where('personal_id', $id)->whereIn('article_id', $articulos_grupo)->where('status', 'Activo')->get();

        //Foreach para obtener los articulos en un arreglo y después obtener la suma
        foreach($articulos as $art){
            $articulo[] = $art->article_id;
        }
        $suma = Article::query()->whereIn('id', $articulo)->get()->sum('precio_actual');
        
        //return $articulos;
        return view('coadmin.registers.resguardo', compact('logged_user', 'group_user', 'articulos', 'ruta', 'person', 'suma'));
    }

    public function resguardo_editar_linea($linea){
        //return "Línea-> ".$linea;

        $ruta = '../';        

        $line = Line::find($linea);
        $person = Personal::find($line->personal_id);
        $articulo = Article::find($line->article_id);

        //return $line;
        //return $person;
        return view('coadmin.registers.editarlinea', compact('line', 'person', 'ruta', 'articulo')); 
    }

    public function resguardo_actualizar_linea(){
        //return request();
        
        $id_grupo = auth()->user()->group_id;
        $cat_grupo = Category::query()->where('group_id', $id_grupo)->get('id');
        $articulos_grupo = Article::query()->whereIn('category_id', $cat_grupo)->where('status', 'Asignado')->get('id');

        //return $articulos_grupo;

        Line::query()->where(['id' => request()->id_linea])->update(['status' => 'Inactivo']);
        $linea = Line::find(request()->id_linea);
        $articulo = Article::query()->where(['id' => $linea->article_id])->update(['status' => request()->status, 'comentario1' => request()->comentario1]);
        
        $fecha = Carbon::parse($linea->updated_at)->format('d-m-Y');

        $ruta = '../storage/app/reports/';
        if(request()->hasFile('image')){

            //return 'Sí hay imagen';
            $imagen = request()->file('image');
            $nombre_imagen = Str::slug($linea->article_id." - ".$fecha).".".$imagen->guessExtension();
            //return $nombre_imagen;

            copy($imagen->getRealPath(), $ruta.$nombre_imagen);
        }
        $existencia = Line::query()->where('personal_id', $linea->personal_id)->whereIn('article_id', $articulos_grupo)->where('status', 'Activo')->get()->count();

        if($existencia != 0){
            return  redirect()->to('/resguardo_coadmin_editar/'.$linea->personal_id);
        }else{
            return  redirect()->to('/resguardo_coadmin_buscar_persona');
        }
    }

    public function resguardo_buscar_articulo(){
        
        $ruta = '';
        $date = Carbon::now();
        $group_user = auth()->user()->group_id;
                
        $cat_grupo = Category::query()->where('group_id', $group_user)->get('id');

        $articulos = Article::where(['status' => 'Asignado'])->whereIn('category_id', $cat_grupo)->get();

        return view('coadmin.registers.buscar_articulo', compact('ruta', 'articulos', 'date'));
    }

    public function asignado_articulo(){
        
        $ruta = '';
        $asignado = [];
        
        $articulos = Article::all();
        $articulo = Article::find(request()->articulos);
        $asignado = Line::with('usuario', 'personal')->where(['article_id'=>$articulo[0]->id, 'status'=> 'Activo'])->get();
        //return $asignado;

        return view('coadmin.registers.asignado_articulo', compact('asignado', 'ruta', 'articulo'));       
    }

    public function actualizar_asignado_articulo(){
        //return request();
        if(request()->status == 'Asignado'){
            return  redirect()->to('/resguardo_coadmin_buscar_articulo');
        }else{
            Line::query()->where(['id' => request()->id])->update(['status' => 'Inactivo']);        
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

            return  redirect()->to('/resguardo_coadmin_buscar_persona');
        }
    }
    
    /****************************************/
    /*************** Historial **************/
    /****************************************/ 
    public function buscar_historial_articulo(){
        $ruta = '';
        $date = Carbon::now();
        $group_user = auth()->user()->group_id;

        $cat_grupo = Category::query()->where('group_id', $group_user)->get('id');

        $articulos = Article::query()->whereIn('category_id', $cat_grupo)->get();
        //return $articulos;

        return view('coadmin.historial.buscar_historial_articulo', compact('articulos', 'ruta', 'date'));
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
        return view('coadmin.historial.historial_articulo', compact('historial', 'ruta', 'articulo', 'fecha'));
    }

    public function buscar_historial_persona(){
        $ruta = '';
        $date = Carbon::now();
        $personas=[];

        $id_grupo = auth()->user()->group_id;
        
        $cat_grupo = Category::query()->where('group_id', $id_grupo)->get('id');

        $articulos_grupo = Article::query()->whereIn('category_id', $cat_grupo)->get('id');
        $resguardos = Line::query()->whereIn('article_id', $articulos_grupo)->distinct()->get('personal_id');

        foreach($resguardos as $resguardo){
            $personas[] = Personal::query()->where('id', $resguardo->personal_id)->get();             
        }
        //return $personas;
        
        return view('coadmin.historial.buscar_historial_persona', compact('personas', 'ruta', 'date'));
    }

    public function historial_persona(){
        $ruta = '';
        $fecha = [];
        
        $id_grupo = auth()->user()->group_id;        
        $cat_grupo = Category::query()->where('group_id', $id_grupo)->get('id');
        $articulos_grupo = Article::query()->whereIn('category_id', $cat_grupo)->get('id');

        $persona = Personal::find(request()->persona);
        $historial = Line::with('usuario', 'articulos')->where(['personal_id' => $persona->id])->whereIn('article_id', $articulos_grupo)->get();

        //return $historial;

        foreach($historial as $hist){            
            $fecha[] = Carbon::parse($hist->updated_at)->format('d-m-Y');
        }
        
        return view('coadmin.historial.historial_persona', compact('historial', 'ruta', 'persona', 'fecha'));
    } 











    








    

    

        

    

    

       
    

/*
    public function reset_password(Request $request){

        $this->validate(request(), [
            'password' => 'required|min:8',
            'pass2' => 'required|min:8',
            'pass2' => 'same:password',
        ],
        [
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe ser de 8 caracteres mínimo',
            'password.same' => 'Las contraseñas no coinciden',
        ]
        );

        $id = request (['id']);
        $datos = User::with('group', 'role')->where(['id' => $id])->get();
        
        foreach($datos as $user){

        }
        $usuario = User::query()->where(['id' => $id])->update(['password' => bcrypt($request->password)]);

        
        $email[] = $user->email;
            
        $correo = new ResetPass($user->name, request('password'));
        
        Mail::to($user->email)->send($correo);

        return  redirect()->to('/admin_usuarios')->with('pass', $user->name);
    }

    public function password_auth($id){

        //return "Este es el id -> $id";
        $ruta = '../';
        $usuarios = User::find($id);

        return view('auth.users.pass', compact('usuarios', 'ruta'));
    }

    public function reset_password_auth(Request $request){

        $this->validate(request(), [
            'password' => 'required|min:8',
            'pass2' => 'required|min:8',
            'pass2' => 'same:password',
        ],
        [
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe ser de 8 caracteres mínimo',
            'password.same' => 'Las contraseñas no coinciden',
        ]
        );

        $id = request (['id']);
        $datos = User::with('group', 'role')->where(['id' => $id])->get();
        
        foreach($datos as $user){

        }
        $usuario = User::query()->where(['id' => $id])->update(['password' => bcrypt($request->password)]);

        
        $email[] = $user->email;
            
        $correo = new ResetPass($user->name, request('password'));
        
        Mail::to($user->email)->send($correo);

        return  redirect()->to('/pase_salida_auth')->with('pass', $user->name);
    }

    public function password_val($id){

        //return "Este es el id -> $id";
        $ruta = '../';
        $usuarios = User::find($id);

        return view('validator.users.pass', compact('usuarios', 'ruta'));
    }

    public function reset_password_val(Request $request){

        $this->validate(request(), [
            'password' => 'required|min:8',
            'pass2' => 'required|min:8',
            'pass2' => 'same:password',
        ],
        [
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe ser de 8 caracteres mínimo',
            'password.same' => 'Las contraseñas no coinciden',
        ]
        );

        $id = request (['id']);
        $datos = User::with('group', 'role')->where(['id' => $id])->get();
        
        foreach($datos as $user){

        }
        $usuario = User::query()->where(['id' => $id])->update(['password' => bcrypt($request->password)]);

        
        $email[] = $user->email;
            
        $correo = new ResetPass($user->name, request('password'));
        
        Mail::to($user->email)->send($correo);

        return  redirect()->to('/pase_salida_val')->with('pass', $user->name);
    }

    public function password_rev($id){

        //return "Este es el id -> $id";
        $ruta = '../';
        $usuarios = User::find($id);

        return view('revisor.users.pass', compact('usuarios', 'ruta'));
    }

    public function reset_password_rev(Request $request){

        $this->validate(request(), [
            'password' => 'required|min:8',
            'pass2' => 'required|min:8',
            'pass2' => 'same:password',
        ],
        [
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe ser de 8 caracteres mínimo',
            'password.same' => 'Las contraseñas no coinciden',
        ]
        );

        $id = request (['id']);
        $datos = User::with('group', 'role')->where(['id' => $id])->get();
        
        foreach($datos as $user){

        }
        $usuario = User::query()->where(['id' => $id])->update(['password' => bcrypt($request->password)]);

        
        $email[] = $user->email;
            
        $correo = new ResetPass($user->name, request('password'));
        
        Mail::to($user->email)->send($correo);

        return  redirect()->to('/pase_salida_rev')->with('pass', $user->name);
    }
    */
}
