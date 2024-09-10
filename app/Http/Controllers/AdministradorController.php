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
use App\Models\Ldap;
use App\Mail\ResetPass;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use App\Mail\SalidasMailable;
use Faker\Provider\ar_EG\Person;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests;
use PDF;
use App\Http\Requests\UserEditRequest;
use App\Http\Requests\PersonalEditRequest;
use App\Http\Requests\CategoryEditRequest;
use App\Http\Requests\GroupsEditRequest;

use Illuminate\Http\Request;

class AdministradorController extends Controller
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
        $totalArticulos = [];
        $totalArticulosDinero = [];

        $fecha = Carbon::now();
        $esteMes = $fecha->format('m');
        $esteAno = $fecha->format('Y');

        // Valor Inventario
        $valorInventario = Article::query()->whereIn('status',['Disponible', 'Asignado', 'En Reparacion', 'Recibido'])->get()->sum('precio_actual');
        // Artículos Robados
        $articuloRobado = Article::query()->whereIn('status',['Robado', 'Extraviado'])->whereMonth('updated_at', $esteMes)->whereYear('updated_at', $esteAno)->get()->sum('precio_actual');
        // Procentaje de artículos
        $cat = Category::where('status', 'activo')->whereNot('category', 'Default')->get();
        foreach($cat as $categoria){
            $totalArticulos[] = Article::query()->get()->whereIn('status', ['Disponible', 'Asignado', 'En Reparacion', 'Recibido'])->where('category_id', $categoria->id)->count();
            $totalArticulosDinero[] = Article::query()->get()->whereIn('status', ['Disponible', 'Asignado', 'En Reparacion', 'Recibido'])->where('category_id', $categoria->id)->sum('precio_actual');
            
            $articulosDisponiblexCategoria[] = [Article::where('status', 'Disponible')->where('category_id', $categoria->id)->count()];
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
                
        return view('admin.index', compact('ruta', 'totalArticulos', 'totalArticulosDinero', 'valorInventario', 'articuloRobado', 'esteMes', 'categorias', 'articulosDisponiblexCategoria', 'articulosRobadosxMes', 'articulosExtraviadosxMes', 'articulosDisponiblesxMes', 'articulosAsignadosxMes', 'articulosRobadosxMes_Moneda', 'articulosExtraviadosxMes_Moneda'));
    }

    public function password($id){

        //return "Este es el id -> $id";
        $ruta = '../';
        $usuarios = User::find($id);

        return view('admin.users.pass', compact('usuarios', 'ruta'));
    }
    
    public function actualizar_password(Request $request){
        
        $this->validate(request(), [
            'password' => 'required|min:8',
            'password1' => 'required|min:8',
            'password1' => 'same:password',
        ],
        [
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe ser de 8 caracteres mínimo',
            'password.same' => 'Las contraseñas no coinciden',
            'password1.same' => 'Las contraseñas no coinciden',
        ]
        );

        $id = request (['id']);
        $datos = User::with('group', 'role')->where(['id' => $id])->get();
        
        foreach($datos as $user){

        }
        $usuario = User::query()->where(['id' => $id])->update(['password' => bcrypt($request->password)]);

        if(empty($user->email)){
            
        }else{
            $email[] = $user->email;            
            $correo = new ResetPass($user->name, request('password'));            
            Mail::to($user->email)->send($correo);
        }        

        return  redirect()->to('/admin_usuarios')->with('pass', $user->name);
    }

    public function users_admin(){

        $ruta = '';
        $usuarios = User::with('group', 'role')->get();
        $user = auth()->user()->user;

        return view('admin.users.index', compact('usuarios', 'user', 'ruta'));
    }
    
    public function nuevo(){

        $ruta = '';
        $roles = Role::all();
        $grupos = Group::all();
        $ldap = Ldap::all();

        return view('admin.users.nuevo', compact('roles', 'grupos', 'ruta', 'ldap'));
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

        
        $usuario = User::create(request(['user', 'name', 'auten', 'email', 'password', 'comment1', 'comment2', 'role_id', 'group_id', 'action_by']));
        $last = User::all()->last()->id;

        $ruta = '../storage/app/evidencias/';
        if(request()->hasFile('image')){

            //return 'Sí hay imagen';
            $imagen = request()->file('image');
            $nombre_imagen = Str::slug(request()->user).".".$imagen->guessExtension();
            //return $nombre_imagen;

            copy($imagen->getRealPath(), $ruta.$nombre_imagen);
            
            $update = User::query()->where(['id' => $last])->update(['ext'=>$imagen->guessExtension()]);
        }
        
        return  redirect()->to('/admin_usuarios')->with('user_add', $usuario->user);
    }

    public function editar($id){

        //return "Este es el id -> $id";
        $ruta = '../';
        $usuarios = User::find($id);
        $grupos = Group::all()->where('status', 'activo');
        $roles = Role::all();
        $ldap = Ldap::all();

        return view('admin.users.editar', compact('usuarios', 'grupos', 'roles', 'ruta', 'ldap'));
    }

    public function actualizar(UserEditRequest $request){
        

        $id = request (['id']);
        $logged_user = auth()->user()->id;

        $updateuser = User::query()->where(['id' => $id])->update(request(['user', 'name', 'email', 'role_id', 'group_id', 'auten', 'comment1', 'comment2', 'status']));
        $usuario = User::query()->where(['id' => $id])->update(['action_by' => $logged_user]);
        $user = User::find($id);

        $ruta = '../storage/app/avatars/';
        if(request()->hasFile('image')){

            //return 'Sí hay imagen';
            $imagen = request()->file('image');
            $nombre_imagen = Str::slug(request()->user).".".$imagen->guessExtension();
            //return $nombre_imagen;

            copy($imagen->getRealPath(), $ruta.$nombre_imagen);
            $updateext = User::query()->where(['id' => $id])->update(['ext' => $imagen->guessExtension()]);
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
        $personal = Personal::with('group')->get();
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
                'id' => 'required|unique:personal',
            ],
            [
                'nombre.required' => 'El nombre es obligatorio',
                'nombre.unique' => 'El nombre ya existe',
                'id.required' => 'El ID es obligatorio',
                'id.unique' => 'El ID ya existe',
            ]
        );
        //return request();


        $personal = Personal::create(request(['nombre', 'puesto', 'group_id']));
        $last = Personal::all()->last()->id;
        $updateuser = Personal::query()->where(['id' => 1])->update(request(['id']));
        
        $nombre = "personal_".$last;
        $ruta = '../storage/app/avatars/';
        if(request()->hasFile('image')){

            //return 'Sí hay imagen';
            $imagen = request()->file('image');
            $nombre_imagen = Str::slug($nombre).".".$imagen->guessExtension();
            //return $nombre_imagen;

            copy($imagen->getRealPath(), $ruta.$nombre_imagen);

            $update = Personal::query()->where(['id' => $last])->update(['ext'=>$imagen->guessExtension()]);
        }        

        return  redirect()->to('/admin_personal')->with('user_add', $personal->nombre);
    }

    public function personal_editar($id){

        //return "Este es el id -> $id";
        $ruta = '../';
        $personal = Personal::find($id);
        $grupos = Group::all()->where('status', 'activo');

        return view('admin.personal.editar', compact('personal', 'grupos', 'ruta'));
    }

    public function personal_actualizar(PersonalEditRequest $request){


        $id_viejo = request (['id_viejo']);
        $id_old = $request->get('id_viejo');
        $nombre = "personal_".request()->id;
        $logged_user = auth()->user()->id;

        //return $id_viejo;
        $existe = Personal::query()->where(['id' => request()->id])->count();
        $existe1= Personal::query()->where(['id' => request()->id])->get();
        //return $existe."--".$id_old."--".$existe1[0]->id;

        if($existe > 0 && $id_old != $existe1[0]->id){
            
            $mensaje = "El ID ".request()->id." ya existe!";
            //return  redirect()->to('/editar_personal/'.$id_viejo);
            return  redirect()->to('/editar_personal/'.$id_old)->with('mensaje_update', $mensaje);

        }else{

            $updateuser = Personal::query()->where(['id' => $id_viejo])->update(request(['id', 'nombre', 'puesto', 'group_id', 'status']));
            $user = Personal::find(request()->id);


            $ruta = '../storage/app/avatars/';
            if(request()->hasFile('image')){

                //return 'Sí hay imagen';
                $imagen = request()->file('image');
                $nombre_imagen = Str::slug($nombre).".".$imagen->guessExtension();
                //return $nombre_imagen;

                copy($imagen->getRealPath(), $ruta.$nombre_imagen);
                $updateext = Personal::query()->where(['id' => request (['id'])])->update(['ext' => $imagen->guessExtension()]);
            }

            //return $user;
            $mensaje = "".$user->nombre;
            //return $mensaje;
            return  redirect()->to('/admin_personal')->with('user_update', $mensaje);
        }
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

    public function categoria_actualizar(CategoryEditRequest $request){

        $id = $request->id;
        $logged_user = auth()->user()->id;

        $depreciacion = $request->depreciacion * 365;
        $category = $request->category;
        $descripcion = $request->descripcion;

        $updatecategory = Category::query()->where(['id' => $id])->update(['category'=>$category, 'description'=>$descripcion, 'depreciacion'=>$depreciacion]);
        //$usuario = Category::query()->where(['id' => $id])->update(['action_by' => $logged_user]);

        return  redirect()->to('/admin_categorias')->with('update_categoria', $category);
    }

    public function intento_categoria_inactivar($id){

        $category = Category::find($id);
        return  redirect()->to('/admin_categorias')->with('inactivar', $category->category)->with('id', $category->id);
    }

    public function categoria_inactivar($id){
        $grupo = Category::find($id);
        $articulos = Article::query()->where('category_id', $grupo->id)->get();
        $cantidad_articulos = Article::query()->where('category_id', $grupo->id)->count();


        if($cantidad_articulos == 0){
            $category = Category::query()->where(['id' => $id])->update(['status' => 'inactivo']);
            return  redirect()->to('admin_categorias')->with('categoria_inactivada', $grupo->category);
        }else{
            foreach($articulos as $articulos){ 
                //return $articulos->id;
                $update_articulos = Article::query()->where('id', $articulos->id)->update(['category_id' => 1]);
            }
            //return $articulos;
            $category = Category::query()->where(['id' => $id])->update(['status' => 'inactivo']);
            return  redirect()->to('/admin_categorias')->with('categoria_inactivada', $grupo->category);
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

    public function actualizar_grupo(GroupsEditRequest $request){
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
                'ns' => 'required'
            ],
            [
                'article.required' => 'El Artículo es Obligatorio',
                'modelo.required' => 'El Modelo es Obligatorio',
                'ns.required' => 'El Número de Serie es Obligatorio',
            ]
        );

        $existe = Article::query()->where(['ns' => request('ns')])->count();
        $articulo = Article::query()->where(['ns' => request('ns')])->get();

        
        if($existe == 1 && $articulo[0]->status == 'Baja'){
            return  redirect()->to('/articulo_nuevo')->with('id_articulo', $articulo[0]->id)->with('existe_baja', $articulo[0]->status)->with('articulo_ns', $articulo[0]->ns);
        }else if($existe == 1 && $articulo[0]->status == 'Disponible'){
            return  redirect()->to('/articulo_nuevo')->with('id_articulo', $articulo[0]->id)->with('existe_disponible', $articulo[0]->status)->with('articulo_ns', $articulo[0]->ns);
        }else{

            $datos = Article::create(request(['article', 'precio_inicial', 'description', 'ns', 'category_id', 'marca', 'modelo', 'comentario1']));
            $last = Article::all()->last()->id;
            $hoy = Carbon::now();

            $fecha = request()->created_at;
            if(empty(request('created_at'))){
                $fecha = $hoy;
            }
            $update = Article::query()->where(['id' => $last])->update(['created_at' => $fecha]);
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
        $existe = Article::query()->where(['ns' => request('ns')])->count();
        $articulo = Article::find($id);
        $articulo_status = Article::query()->where(['ns' => request('ns')])->get();        
        //return $articulo;
        //return $articulo_status[0]->ns;
        //return $mensaje;

        if(empty($articulo_status[0]->status)){
            $updatedatos = Article::query()->where(['id' => $id])->update(request(['article', 'description', 'ns', 'category_id', 'marca', 'modelo', 'comentario1', 'precio_inicial', 'created_at']));
            return  redirect()->to('/admin_articulos')->with('articulo_update', $ns);
        }else{
            $mensaje = $articulo_status[0]->status;
            if($existe == 1 && $articulo_status[0]->status == 'Baja' && request('ns') != $articulo[0]->ns){
                return  redirect()->to('/admin_articulos')->with('articulo_desactivado', $mensaje)->with('id_articulo', $articulo_status[0]->id)->with('articulo_ns', $articulo_status[0]->ns);
            }elseif($existe == 1 && $articulo_status[0]->status == 'Disponible' && request('ns') != $articulo[0]->ns){
                return  redirect()->to('/admin_articulos')->with('articulo_disponible', $mensaje)->with('id_articulo', $articulo[0]->id)->with('articulo_ns', $articulo_status[0]->ns);
            }else{
                $updatedatos = Article::query()->where(['id' => $id])->update(request(['article', 'description', 'ns', 'category_id', 'marca', 'modelo', 'comentario1', 'precio_inicial', 'created_at']));
                return  redirect()->to('/admin_articulos')->with('articulo_update', $ns);
            }
        }


        
        
    }

    public function articulo_inactivar($id){

        $datos = Article::query()->where(['id' => $id])->update(['status' => 'baja']);
        $articulo = Article::find($id);
        $info = $articulo->article." con N/S ".$articulo->ns;

        return  redirect()->to('/admin_articulos')->with('info', $info);
    }

    public function articulo_activar($id){

        //return $id;
        $datos = Article::query()->where(['id' => $id])->update(['status' => 'disponible']);
        $articulo = Article::find($id);
        $info = $articulo->article." con N/S ".$articulo->ns;

        return  redirect()->to('/admin_articulos')->with('info_activado', $info);
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
        $personal = Personal::query()->where('status', 'activo')->get();
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
        $persona = Personal::find(request()->personal_id);
        //return $persona;
        $fecha = Carbon::now();
        $hoy = $fecha->format('d-m-Y');

        $ruta = '';        
        $ruta_firma = '../storage/app/firmas/';
        $nombre_imagen = 'persona_' . request()->personal_id . '_fecha_' . $hoy;

        $image_parts = explode(";base64,", request()->signed);
             
        $image_type_aux = explode("image/", $image_parts[0]);
           
        $image_type = $image_type_aux[1];
           
        $image_base64 = base64_decode($image_parts[1]);
 
        $signature = $nombre_imagen. '.'.$image_type;
           
        $file = $ruta_firma . $signature;


        file_put_contents($file, $image_base64);
        

        foreach(request()->articulos as $articulos){
            
            $lines = new Line();
            $lines->article_id = $articulos;
            $lines->personal_id = request()->personal_id;
            $lines->users_id = auth()->user()->id;
            $lines->comentario = "";
            $lines->status = "Activo";
            $lines->firma = $file;
            $lines->save();

            $update = Article::query()->where(['id' => $articulos])->update(['status' => "Asignado"]);
        }
        return  redirect()->to('/admin_index')->with('resguardo_add', $persona->nombre);
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

    public function admin_firma_modal(){

    }

    public function admin_entregados(){
        
        $ruta = '';

        $entregados = Article::query()->where('status', 'Entregado')->get();
        //return $entregados;
        //$linea = Line::find($entregados[0]->comentario1);
        //return $linea;
        
        return view('admin.registers.buscar_entregados', compact('ruta', 'entregados'));
    }

    public function crear_resguardopdf($id){

        //return $id;
        $ruta = '';
        $articulo = [];
        $depto = [];
        
        $fecha = Carbon::now();
        $dia = $fecha->format('d');
        $mes = $fecha->format('m');
        $anio = $fecha->format('Y');

        switch($mes){
            case('01'):
                $mes = 'Enero';
                break;
            case('02'):
                $mes = 'Febrero';
                break;
            case('03'):
                $mes = 'Marzo';
                break;
            case('04'):
                $mes = 'Abril';
                break;
            case('05'):
                $mes = 'Mayo';
                break;
            case('06'):
                $mes = 'Junio';
                break;
            case('07'):
                $mes = 'Julio';
                break;
            case('08'):
                $mes = 'Agosto';
                break;
            case('09'):
                $mes = 'Septiembre';
                break;
            case('10'):
                $mes = 'Octubre';
                break;
            case('11'):
                $mes = 'Noviembre';
                break;
            case('12'):
                $mes = 'Diciembre';
                break;
        }
        
        $hoy = $dia." de ".$mes." del ".$anio;
        $empresa = 'Minera Juanicipio';
        $logged_user = auth()->user()->id;
        $group_user = auth()->user()->group_id;
                
        $articulos_grupo = Article::query()->where('status', 'Asignado')->get('id');

        $person = Personal::find($id);

        $articulos = Line::with('articulos', 'usuario')->where('personal_id', $id)->whereIn('article_id', $articulos_grupo)->where('status', 'Activo')->get();

        //return $articulos;
        //Foreach para obtener los articulos en un arreglo y después obtener la suma
        foreach($articulos as $art){
            $articulo[] = $art->article_id;
            $grupo = Category::find($art->articulos->category_id);
            $depto[] = Group::query()->where('id', $grupo->group_id)->get('group');
        }
        //return $depto;
        $suma = Article::query()->whereIn('id', $articulo)->get()->sum('precio_actual');


        $pdf = PDF::loadView('layouts.pdf_admin', compact('logged_user', 'group_user', 'articulos', 'ruta', 'person', 'suma', 'empresa', 'hoy', 'depto'));
        return $pdf->stream($person->nombre.'.pdf');

    }

    
    /****************************************/
    /*************** Historial **************/
    /****************************************/    
    public function buscar_historial_articulo(){
        $ruta = '';
        $date = Carbon::now();

        $articulos = Article::all();
        //return $articulos;

        return view('admin.historial.buscar_historial_articulo', compact('articulos', 'ruta', 'date'));
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
        return view('admin.historial.historial_articulo', compact('historial', 'ruta', 'articulo', 'fecha'));
    }  

    public function buscar_historial_persona(){
        $ruta = '';
        $date = Carbon::now();

        $personas = Personal::all();
        //return $articulos;

        return view('admin.historial.buscar_historial_persona', compact('personas', 'ruta', 'date'));
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
        return view('admin.historial.historial_persona', compact('historial', 'ruta', 'persona', 'fecha'));
    } 

    
    /****************************************/
    /***************** LDAP *****************/
    /****************************************/ 
    public function server_ldap(){
        $ruta = '';

        $ldap = Ldap::all();

        //return $ldap->count();
        return view('admin.ldap.editar', compact('ldap', 'ruta'));
    }

    public function editar_ldap(){
        //return request();

        if(request()->ldap_status == 'on'){
            $ldap_status = Ldap::query()->where(['id' => 1])->update(['ldap_status' => 1]);
        }else{
            $ldap_status = Ldap::query()->where(['id' => 1])->update(['ldap_status' => 0]);
        }
        $ldap = Ldap::query()->where(['id' => 1])->update(['ldap_server' => request()->ldap_server, 'ldap_port' => request()->ldap_port, 'ldap_domain' => request()->ldap_domain, 'ldap_version' => request()->ldap_version, 'ldap_user' => request()->ldap_user, 'ldap_password' => request()->ldap_password]);
        
        return  redirect()->to('/server_ldap')->with('ldap_msg', "Actualización Exitosa");
    }

    public function probar_ldap(){
        $ldap = Ldap::all();
        //return $ldap;

        $ldap_server = $ldap[0]->ldap_server;
        $ldap_dominio = $ldap[0]->ldap_domain;
        $ldap_port = $ldap[0]->ldap_port;
        $ldap_user = $ldap[0]->ldap_user.'@'.$ldap_dominio;
        $ldap_pass =  $ldap[0]->ldap_password;
        $ldap_version = $ldap[0]->ldap_version;

        $ldap_conn = ldap_connect($ldap_server, $ldap_port);
        ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, $ldap_version);
        ldap_set_option($ldap_conn, LDAP_OPT_REFERRALS, 0);

        if(@ldap_bind($ldap_conn, $ldap_user, $ldap_pass)){
            return  redirect()->to('/server_ldap')->with('ldap_msg', "Conexión Exitosa");
        }else{
            return  redirect()->to('/server_ldap')->with('ldap_msg', "Conexión Fallida");
        }
    }

    
    /****************************************/
    /*************** Inactivos **************/
    /****************************************/ 
    public function inactivo_articulos(){
        $ruta = '';
        $datos = Article::query()->get()->whereNotIn('status', ['Disponible', 'Asignado']);

        return view('admin.inactivos.articulos', compact('ruta', 'datos'));
    }

    public function inactivo_articulos_actualizar($id){        
        $ruta = '../';

        return $id;
    }
}