<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\Register;
use App\Models\Article;
use App\Models\Category;
use App\Models\Role;
use App\Models\Group;
use App\Models\User;
use App\Models\Personal;
use App\Models\Line;
use App\Mail\SalidasMailable;
use Faker\Provider\ar_EG\Person;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;

class AdministradorController extends Controller
{
    //Rutas del perfil de Administrador

    /****************************************/
    /************* Usuarios *****************/
    /****************************************/
    
    public function index_admin(){

        $ruta = '';
        $n=0;

        $fecha = Carbon::now();
        $esteMes = $fecha->format('m');

        // Valor Inventario
        $valorInventario = Article::query()->whereIn('status',['Disponible', 'Asignado', 'En Reparacion'])->get()->sum('precio_actual');
        // Artículos Robados
        $articuloRobado = Article::query()->whereIn('status',['Robado'])->whereMonth('updated_at', $esteMes)->get()->sum('precio_actual');
        // Procentaje de artículos
        $categorias = Category::where('status', 'activo')->whereNot('category', 'Default')->get();
        foreach($categorias as $categoria){
            $articulosTotal[] = Article::whereNotIn('status', ['Robado', 'Extraviado', 'Baja'])->where('category_id', $categoria->id)->count();
            $articulosDisponible[] = Article::where('status', 'Disponible')->where('category_id', $categoria->id)->count();
            
            if($articulosTotal[$n] == 0){
                $promedio[] = 0;
            }
            else{
                $promedio[] = ($articulosDisponible[$n] * 100) / $articulosTotal[$n];
            }
            $n++;
        }
        






        //return $valorInventario;
        return view('admin.index', compact('ruta', 'valorInventario', 'articuloRobado', 'esteMes', 'categorias', 'articulosDisponible', 'promedio'));
    }

    public function password($id){

        //return "Este es el id -> $id";
        $ruta = '../';
        $usuarios = User::find($id);

        return view('admin.users.pass', compact('usuarios', 'ruta'));
    }

    public function users_admin(){

        $ruta = '';
        $usuarios = User::with('group', 'role')->get()->where('status', 'activo');
        $user = auth()->user()->user;

        return view('admin.users.index', compact('usuarios', 'user', 'ruta'));
    }
    
    public function nuevo(){

        $ruta = '';
        $roles = Role::all();
        $grupos = Group::all();
        return view('admin.users.nuevo', compact('roles', 'grupos', 'ruta'));
    }

    public function crear(){
        
        $this->validate(request(), [
                'user' => 'required|unique:users',
                'name' => 'required',
                'email' => 'required|unique:users',
                'password' => 'required|min:8',
                'pass2' => 'required|same:password',
            ],
            [
                'user.required' => 'El usuario es obligatorio',
                'user.unique' => 'Usuario Duplicado',
                'name.required' => 'El nombre es obligatorio',
                'email.required' => 'El email es obligatorio',
                'email.unique' => 'El email ya está en uso',
                'password.required' => 'La contraseña es obligatoria',
                'pass2.required' => 'La validación de la contraseña es obligatoria',
                'password.min' => 'La contraseña debe ser de 8 caracteres mínimo',
            ]
        );

        $ruta = '../storage/app/avatars/';
        if(request()->hasFile('image')){

            //return 'Sí hay imagen';
            $imagen = request()->file('image');
            $nombre_imagen = Str::slug(request()->user).".".$imagen->guessExtension();
            //return $nombre_imagen;

            copy($imagen->getRealPath(), $ruta.$nombre_imagen);
        }


        $usuario = User::create(request(['user', 'name', 'email', 'password', 'comment1', 'comment2', 'role_id', 'group_id', 'action_by']));
        
        return  redirect()->to('/admin_usuarios')->with('user_add', $usuario->user);
    }

    public function editar($id){

        //return "Este es el id -> $id";
        $ruta = '../';
        $usuarios = User::find($id);
        $grupos = Group::all()->where('status', 'activo');
        $roles = Role::all();

        return view('admin.users.editar', compact('usuarios', 'grupos', 'roles', 'ruta'));
    }

    public function actualizar(){

        $id = request (['id']);
        $logged_user = auth()->user()->id;

        $updateuser = User::query()->where(['id' => $id])->update(request(['user', 'name', 'email', 'role_id', 'group_id', 'auten', 'comment1', 'comment2']));
        $usuario = User::query()->where(['id' => $id])->update(['action_by' => $logged_user]);
        $user = User::find($id);

        $ruta = '../storage/app/avatars/';
        if(request()->hasFile('image')){

            //return 'Sí hay imagen';
            $imagen = request()->file('image');
            $nombre_imagen = Str::slug(request()->user).".".$imagen->guessExtension();
            //return $nombre_imagen;

            copy($imagen->getRealPath(), $ruta.$nombre_imagen);
        }

        //return $user;
        $mensaje = "".$user[0]->user;

        return  redirect()->to('/admin_usuarios')->with('user_update', $mensaje);
    }

    public function inactivar($id){

        $logged_user = auth()->user()->id;

        $usuario = User::query()->where(['id' => $id])->update(['status' => 'inactivo', 'action_by' => $logged_user]);

        return  redirect()->to('/admin_usuarios');
    }

    public function activar($usuario){

        $logged_user = auth()->user()->id;

        $usuario = User::query()->where(['user' => $usuario])->update(['status' => 'activo', 'action_by' => $logged_user]);

        return  redirect()->to('/admin_usuarios');
    }
    

    /****************************************/
    /************* Personal *****************/
    /****************************************/
    
    public function personal_admin(){

        $ruta = '';
        $personal = Personal::with('group')->get()->where('status', 'activo');
        $user = auth()->user()->user;
        
        return view('admin.personal.index', compact('personal', 'user', 'ruta'));
    }
    
    public function personal_nuevo(){

        $ruta = '';
        
        $grupos = Group::all();
        return view('admin.personal.nuevo', compact('grupos', 'ruta'));
    }

    public function personal_crear(){
        
        $this->validate(request(), [
                'nombre' => 'required|unique:personal',
                'puesto' => 'required',
            ],
            [
                'nombre.required' => 'El nombre es obligatorio',
                'puesto.required' => 'El puesto es obligatorio',
                'nombre.unique' => 'El nombre ya existe',
            ]
        );

        $ruta = '../storage/app/avatars/';
        if(request()->hasFile('image')){

            //return 'Sí hay imagen';
            $imagen = request()->file('image');
            $nombre_imagen = Str::slug(request()->nombre).".".$imagen->guessExtension();
            //return $nombre_imagen;

            copy($imagen->getRealPath(), $ruta.$nombre_imagen);
        }


        $personal = Personal::create(request(['nombre', 'puesto', 'group_id']));
        return  redirect()->to('/admin_personal')->with('user_add', $personal->nombre);
    }

    public function personal_editar($id){

        //return "Este es el id -> $id";
        $ruta = '../';
        $personal = Personal::find($id);
        $grupos = Group::all()->where('status', 'activo');

        return view('admin.personal.editar', compact('personal', 'grupos', 'ruta'));
    }

    public function personal_actualizar(){

        $id = request (['id']);
        $logged_user = auth()->user()->id;

        $updateuser = Personal::query()->where(['id' => $id])->update(request(['nombre', 'puesto', 'group_id']));
        $user = Personal::find($id);

        $ruta = '../storage/app/avatars/';
        if(request()->hasFile('image')){

            //return 'Sí hay imagen';
            $imagen = request()->file('image');
            $nombre_imagen = Str::slug(request()->user).".".$imagen->guessExtension();
            //return $nombre_imagen;

            copy($imagen->getRealPath(), $ruta.$nombre_imagen);
        }

        //return $user;
        $mensaje = "".$user[0]->nombre;
        return  redirect()->to('/admin_personal')->with('user_update', $mensaje);
    }

    public function personal_inactivar($id){

        $logged_user = auth()->user()->id;

        $personal = Personal::query()->where(['id' => $id])->update(['status' => 'inactivo']);

        return  redirect()->to('/admin_personal');
    }
    

    /****************************************/
    /************* Categorías ***************/
    /****************************************/

    public function categoria_index(){

        $ruta = '';
        $categorias = Category::all()->where('status', 'activo')->whereNotIn('category', 'Default');
        $user = auth()->user()->user;

        return view('admin.categorias.index', compact('categorias', 'user', 'ruta'));
    }

    public function categoria_nuevo(){

        $ruta = '';
        return view('admin.categorias.nuevo', compact('ruta'));
    }

    public function categoria_crear(Request $request){

        $this->validate(request(), [
                'category' => 'required|unique:categories',
                'depreciacion' => 'required',
            ],
            [
                'category.unique' => '¡La Categoría ya existe!',
                'category.required' => 'La Categoría es Obligatoria',
                'depreciacion.required' => 'La depreciación es Obligatoria',
            ]
        );
        
        $depreciacion = $request->depreciacion * 365;
        $category = $request->category;
        $descripcion = $request->descripcion;
        
        //return $category;
        $datos = Category::create(['category'=>$category, 'description'=>$descripcion, 'depreciacion'=>$depreciacion]);
        return  redirect()->to('/admin_categorias')->with('add_categoria', $category);
    }

    public function categoria_editar($id){

        $ruta = '../';
        //return "Este es el id -> $id";
        $datos = Category::find($id);

        return view('admin.categorias.editar', compact('datos', 'ruta'));
    }

    public function categoria_actualizar(Request $request){

        $id = $request->id;
        $logged_user = auth()->user()->id;

        $depreciacion = $request->depreciacion * 365;
        $category = $request->category;
        $descripcion = $request->descripcion;

        $updatecategory = Category::query()->where(['id' => $id])->update(['category'=>$category, 'description'=>$descripcion, 'depreciacion'=>$depreciacion]);
        //$usuario = Category::query()->where(['id' => $id])->update(['action_by' => $logged_user]);

        return  redirect()->to('/admin_categorias')->with('update_categoria', $category);
    }

    public function categoria_inactivar($id){

        $grupo = Category::find($id);
        $grupo_nombre = Group::find($grupo->group_id);

        if($grupo->group_id != 1){
            return  redirect()->to('/admin_categorias')->with('grupo', $grupo_nombre->group);
        } else {

            $category = Category::query()->where(['id' => $id])->update(['status' => 'inactivo']);
            return  redirect()->to('/admin_categorias')->with('info', $grupo->category);
        }
    }

    
    /****************************************/
    /*************** Grupos *****************/
    /****************************************/
    
    public function grupos_admin(){

        $ruta = '';
        $grupos = Group::all()->where('status', 'activo')->whereNotIn('group', 'default')->sortBy('group');
        $user = auth()->user()->user;

        return view('admin.groups.index', compact('grupos', 'user', 'ruta'));
    }

    public function nuevo_grupo(){

        $ruta = '';
        return view('admin.groups.nuevo', compact('ruta'));
    }

    public function crear_grupo(){

        $this->validate(request(), [
                'group' => 'required|unique:groups',
            ],
            [
                'group.required' => 'El Nombre del Grupo es Obligatorio',
                'group.unique' => 'El Grupo ya existe',
            ]
        );
        
        $logged_user = auth()->user()->id;
        //return request();
        $grupo = Group::create(request(['group', 'description', 'action_by']));
        return  redirect()->to('/admin_grupos')->with('group_add', $grupo->group);
    }

    public function editar_grupo($id){

        $ruta = '../';
        //return "Este es el id -> $id";
        $grupos = Group::find($id);

        return view('admin.groups.editar', compact('grupos', 'ruta'));
    }

    public function actualizar_grupo(){
        //return 'Actualizar Grupos';
        $id = request (['id']);
        $logged_user = auth()->user()->id;

        $updateuser = Group::query()->where(['id' => $id])->update(request(['group', 'description']));
        $usuario = Group::query()->where(['id' => $id])->update(['action_by' => $logged_user]);

        return  redirect()->to('/admin_grupos')->with('group_update', request()->group);
    }

    public function inactivar_grupo($id){

        $logged_user = auth()->user()->id;

        $grupo = Group::query()->where(['id' => $id])->update(['status' => 'inactivo', 'action_by' => $logged_user]);

        return  redirect()->to('/admin_grupos');
    }

    public function categorias_grupo($id){
        //return $id;
        $ruta = '../';
        
        //return "Este es el id -> $id";
        $grupos = Group::find($id);
        $categorias = Category::with('categoria')->whereNot('category', 'default')->whereIn('group_id', [$id, 1])->get();
        //$perm = PermCategories::where('group_id', '');
        //return $categorias;

        return view('admin.groups.categorias', compact('grupos', 'categorias', 'ruta'));
    }

    public function categorias_crear_grupo(){

        $perm = request()->perm;
        $grupo = request()->group_id;
        //return request();
        
        if($perm == null){
            return  redirect()->route('grupos.categorias', [$grupo]);
        }else{
            foreach($perm as $cat){
                $updateCategorias = Category::query()->where(['id' => $cat])->update(request(['group_id', $grupo]));
            }   
            return  redirect()->route('grupos.categorias', [$grupo]);         
        }
        //return $long;

        
    }

    public function categorias_default_grupo($id, $grupo){
        
        $updateCategory = Category::query()->where(['id' => $id])->update(['group_id' => 1]);
        
        return  redirect()->route('grupos.categorias', [$grupo]);
    }

    
    /****************************************/
    /************** Artículos ***************/
    /****************************************/
        
    public function articulo_index(){

        $ruta = '';
        $date = Carbon::now();

        $datos = Article::with('category')->whereNotIn('status', ['Danado', 'Baja'])->get();
        return view('admin.articulos.index', compact('datos', 'ruta', 'date'));
                
    }

    public function articulo_nuevo(){

        $ruta = '';
        $categorias = Category::all();
        return view('admin.articulos.nuevo', compact('categorias', 'ruta'));
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

        return  redirect()->to('/admin_articulos')->with('articulo_add', $datos->article);
    }

    public function articulos_editar($id){

        $ruta = '../';
        //return "Este es el id -> $id";
        $datos = Article::find($id);
        $categorias = Category::all();

        return view('admin.articulos.editar', compact('datos', 'categorias', 'ruta'));
    }

    public function articulos_actualizar(){

        $id = request (['id']);
        $ns = "Artículo ".request()->article." -- N/S ".request()->ns;
        
        $updatedatos = Article::query()->where(['id' => $id])->update(request(['article', 'description', 'ns', 'category_id', 'marca', 'modelo', 'comentario1', 'precio_inicial']));
        //$usuario = Category::query()->where(['id' => $id])->update(['action_by' => $logged_user]);

        return  redirect()->to('/admin_articulos')->with('articulo_update', $ns);
    }

    public function articulo_inactivar($id){

        $datos = Article::query()->where(['id' => $id])->update(['status' => 'baja']);
        $articulo = Article::find($id);
        $info = $articulo->article." con N/S ".$articulo->ns;

        return  redirect()->to('/admin_articulos')->with('info', $info);
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
        $articulos = Article::with('category')->where('status', 'Disponible')->get();
        $grupos = Group::all();
        $personal = Personal::all();
        //return $articulos;
        return view('admin.registers.nuevo', compact('articulos', 'grupos', 'personal', 'ruta'));
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
        return view('admin.registers.resumen', compact('logged_user', 'group_user', 'articulo', 'ruta', 'person', 'suma')); 
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
        return  redirect()->to('/admin_index');
    }

    public function resguardo_buscar_persona(){
        
        $n=0;
        $ruta = '';
        $personal=[];

        $resguardos = Line::query()->where('status', 'Activo')->distinct()->get('personal_id');
        
        foreach($resguardos as $resguardo){
            $personal[] = Personal::query()->where('id', $resguardo->personal_id)->get();             
        }
        return view('admin.registers.buscar_persona', compact('ruta', 'personal'));
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
        return view('admin.registers.resguardo', compact('logged_user', 'group_user', 'articulos', 'ruta', 'person', 'suma'));
    }

    public function resguardo_editar_linea($linea){
        //return "Línea-> ".$linea;

        $ruta = '../';        

        $line = Line::find($linea);
        $person = Personal::find($line->personal_id);
        $articulo = Article::find($line->article_id);

        //return $line;
        //return $person;
        return view('admin.registers.editarlinea', compact('line', 'person', 'ruta', 'articulo')); 
    }

    public function resguardo_actualizar_linea(){
        //return request();
        
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
        $existencia = Line::query()->where('personal_id', $linea->personal_id)->where('status', 'Activo')->get()->count();

        if($existencia != 0){
            return  redirect()->to('/resguardo_editar/'.$linea->personal_id);
        }else{
            return  redirect()->to('/resguardo_buscar_persona');
        }
    }    

    public function resguardo_buscar_articulo(){
        
        $ruta = '';
        $date = Carbon::now();

        $articulos = Article::where(['status' => 'Asignado'])->get();
        return view('admin.registers.buscar_articulo', compact('ruta', 'articulos', 'date'));
    }

    public function asignado_articulo(){
        
        $ruta = '';
        $asignado = [];
        
        $articulos = Article::all();
        $articulo = Article::find(request()->articulos);
        $asignado = Line::with('usuario', 'personal')->where(['article_id'=>$articulo[0]->id, 'status'=> 'Activo'])->get();
        //return $asignado;
        return view('admin.registers.asignado_articulo', compact('asignado', 'ruta', 'articulo'));       
    }

    public function actualizar_asignado_articulo(){
        //return request();
        if(request()->status == 'Asignado'){
            return  redirect()->to('/resguardo_buscar_articulo');
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

            return  redirect()->to('/resguardo_buscar_persona');
        }
    }

    
    /****************************************/
    /*************** Historial **************/
    /****************************************/
    
    public function buscar_historial_articulo(){
        $ruta = '';
        $date = Carbon::now();

        $articulos = Article::all();
        //return $articulos;

        return view('admin.registers.buscar_historial_articulo', compact('articulos', 'ruta', 'date'));
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
        return view('admin.registers.historial_articulo', compact('historial', 'ruta', 'articulo', 'fecha'));
    }
}
