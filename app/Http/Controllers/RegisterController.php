<?php

namespace App\Http\Controllers;

use App\Models\Register;
use App\Models\Article;
use App\Models\Line;
use App\Models\User;
use App\Mail\SalidasMailable;
use App\Mail\CancelarRemision;
use App\Mail\AprobarRemision;
use App\Mail\EnviarRemision;
use App\Models\Category;
use App\Models\Group;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Livewire\Component;

class RegisterController extends Controller
{
    public $selectedCategoria = null;
    public $selectedArticles = null;
    public $articles = null;
    /****************************************** */
    /************ Rutas de Administrador ****** */
    /****************************************** */

    public function salida_editar($id){

        $ruta = '../';
        $numero=1;
        $registros = Register::find($id);
        $lineas = Line::with('articulos')->where('register_id', $id)->get();
        $long = sizeof($lineas);

                
        //return $long;
        return view('admin.registers.edit', compact('registros', 'lineas', 'numero', 'ruta', 'long')); 
    }

    public function salida_actualizar(){

        $logged_user = auth()->user()->id;
        $id = request('register_id');
        
        $pasesalida = Register::query()->where(['id' => $id])->update(request(['person', 'vehicle', 'placas']));
        
        
        return  redirect()->to('/pase_salida')->with('info_actualizado', $id);
    }

    public function salida_mostrar_autorizados($id){

        $ruta = '../';
        $registros = Register::find($id);
        $registros2 = Register::with('user')->where('id', $registros->id)->get();
        $lineas = Line::with('articulos')->where('register_id', $id)->get();
        //return $registros2;
        return view('admin.registers.mostrar_autorizados', compact('registros', 'registros2', 'lineas', 'ruta')); 
    }

    public function articulo_borrar($article_id, $line_id, $register_id){
        //return $id;
        $ruta = '../../../';
        
        $updateArticle = Article::query()->where(['id' => $article_id])->update(['status' => 'Disponible']);
        $articulo = Line::find($line_id);
        $articulo->delete();

        return  redirect()->route('salidas.editar', [$register_id]);
    }

    public function salida_inactivar($id){
        //return $id;
        $logged_user = auth()->user()->id;

        $remision = Register::query()->where(['id' => $id])->update(['status' => 'cancelado', 'approval_id' => $logged_user]);
        $lineas = Line::where('register_id', $id)->get();
        
        foreach($lineas as $linea){
            
            $updateArticle = Article::query()->where(['id' => $linea->article_id])->update(['status' => 'Disponible']);
            $linea->delete();
        }

        return  redirect()->to('/autorizar_index')->with('info_cancelado', $id);
    }

    public function autorizar_admin(){

        $ruta = '';
        $registros = Register::with('group')->get()->where('status', 'creado');
        //return $registros;
        return view('admin.registers.autorizados', compact('registros', 'ruta'));
    }

    public function salida_autorizar($id){

        $logged_user = auth()->user()->id;

        $pasesalida = Register::query()->where(['id' => $id])->update(['status'=>'aprobado', 'approval_id'=>$logged_user]);
        return  redirect()->to('/autorizar_index')->with('info_actualizado', $id);
    }
    
    public function validar_admin(){

        $ruta = '';
        $registros = Register::with('group')->get()->where('status', 'aprobado');
        //return $registros;
        return view('admin.registers.validados', compact('registros', 'ruta'));
    }

    public function salida_validar($id){

        $ruta = '../';
        $registros = Register::find($id);
        $lineas = Line::with('articulos')->where('register_id', $id)->get();
        $proveedor = Proveedor::query()->orderBy('proveedor', 'asc')->get();
        
        return view('admin.registers.validar', compact('id', 'ruta', 'registros', 'lineas', 'proveedor'));

    }

    public function salida_validar2(){

        //return request();
        $id = request()->register_id;
        $proveedor = request()->proveedor_id;
        //return $proveedor;

        $remision = Register::query()->where(['id' => $id])->update(['status'=>'validado', 'proveedor_id'=>$proveedor, 'validation_id'=>auth()->user()->id]);
        return  redirect()->to('/validar_index');
    }

    public function revisar_admin(){

        $ruta = '';
        $registros = Register::with('group')->get()->where('status', 'validado');
        //return $registros;
        return view('admin.registers.revisados', compact('registros', 'ruta'));
    }

    public function salida_revisar($id){

        $ruta = '../';
        $registros = Register::find($id);
        $lineas = Line::with('articulos')->where('register_id', $id)->get();
        $proveedor = Proveedor::query()->where('id', $registros->proveedor_id)->get();
        //return $proveedor;
        return view('admin.registers.revisar', compact('id', 'ruta', 'registros', 'lineas', 'proveedor'));

    }

    public function salida_revisar2(){

        //return request();
        $id = request()->register_id;
        $proveedor = request()->proveedor_id;
        //return $proveedor;

        $remision = Register::query()->where(['id' => $id])->update(['status'=>'enviado']);
        return  redirect()->to('/revisar_index');
    }

    public function enviados_admin(){

        $ruta = '';
        $registros = Register::with('group')->get()->where('status', 'enviado');
        //return $registros;
        return view('admin.registers.enviados', compact('registros', 'ruta'));
    }
    
    public function salida_recibir($id){

        $ruta = '../';
        $numero=1;
        $registros = Register::find($id);
        $lineas = Line::with('articulos')->where('register_id', $id)->get();
        $proveedor = Proveedor::find($registros->proveedor_id);
        $long = sizeof($lineas);

                
        //return $long;
        return view('admin.registers.recibir', compact('registros', 'lineas', 'numero', 'ruta', 'long', 'proveedor')); 
    }

    public function articulo_recibir($article_id, $line_id, $register_id){
        //return $id;
        $ruta = '../../../';
        
        $updateArticle = Article::query()->where(['id' => $article_id])->update(['status' => 'Disponible']);
        $updateLine = Line::query()->where(['id' => $line_id])->update(['recibido' => 'Si']);

        return  redirect()->route('salidas.recibir', [$register_id]);
    }

    public function salida_recibir_remisión(){
        //return request();
        $logged_user = auth()->user()->id;
        //return request()->register_id;

        $remision = Register::query()->where(['id' => request()->register_id])->update(['status' => 'finalizado', 'approval_id' => $logged_user]);
        

        $lineas = Line::query()->where('register_id', request()->register_id)->where('recibido', 'No')->get();
        //return $lineas;

        foreach($lineas as $linea){
            
            $updateArticle = Article::query()->where(['id' => $linea->article_id])->update(['status' => 'Disponible']);
            $updateLine = Line::query()->where(['id' => $linea->id])->update(['recibido' => 'Si']);
        }

        return  redirect()->to('/enviados_index')->with('info_recibido', request()->register_id);
    }

    public function salida_finalizar($id){

        $logged_user = auth()->user()->id;
        
        $lineas = Line::query()->where((['register_id'=>$id]))->get();
        $pasesalida = Register::query()->where(['id' => $id])->update(['status'=>'finalizado', 'approval_id'=>$logged_user]);
        //return $lineas;

        foreach($lineas as $linea){
            $updateArticulo = Article::query()->where('id', $linea->article_id)->update(['status'=>'Disponible']);
            /*
            $updateLinea = Line::find($linea->id);            
            $updateLinea->delete();
            */
        }

        return  redirect()->to('/enviados_index')->with('info_actualizado', $id);
    }

    public function historial_admin(){

        $ruta = '';
        $registros = Register::with('group')->whereIN('status', ['finalizado', 'cancelado'])->get();
        //return $registros;
        return view('admin.registers.historial', compact('registros', 'ruta'));
    }

    /****************************************** */
    /************ Rutas de Autorizador ******** */
    /****************************************** */
    public function register_auth(){

        $ruta = '';
        $registros = Register::with('user2', 'user', 'group')->whereNotIn('status', ['cancelado', 'finalizado'])->where('group_id', auth()->user()->group_id)->get();
        
        //return $registros;
        return view('auth.registers.index', compact('registros', 'ruta'));
    }

    public function salida_nueva_auth(){
        
        $ruta = '';        
        $group_user = auth()->user()->group_id;
        $categorias = [];

        $grupos = Group::find($group_user);
        $categories = Category::query()->whereIn('group_id', [$grupos->id])->get();


        foreach($categories as $category){
            $categorias[] = $category->id;
        }

        $articulos = Article::with('category')->where('status', 'Disponible')->whereIn('category_id', $categorias)->get();

        return view('auth.registers.nuevo', compact('ruta', 'articulos', 'grupos'));
    }

    public function salida_crear_auth(){

        $this->validate(request(), 
            [
                'person' => 'required',
                'vehicle' => 'required',
                'placas' => 'required',
            ],
            [
                'person.required' => '¡La Persona es Obligatoria!',
                'vehicle.required' => '¡El Vehículo es Obligatorio!',
                'placas.required' => '¡El Vehículo es Obligatorio!',
            ]
        );

        $logged_user = auth()->user()->id;
        $group_user = auth()->user()->group_id;
        
        $register = Register::create(request(['person', 'vehicle', 'placas', 'user_id', 'group_id']));
        $data = Register::latest('id')->first();
        $folio = $data->id;
        
        //return request();
        //$updateRegistro = Register::query()->where('id', $folio)->update(['approval_id' => '0']);

        
        $articulos = request()->articulos;
        foreach($articulos as $articulo){
            
            $line = Line::create(['article_id'=>$articulo, 'register_id'=>$folio]);
            //$lineas = Line::query()->where(['id' => $line_id[$i]])->update(['article'=>$desc[$i], 'quantity'=>$cant[$i]]);
            $updateArticle = Article::query()->where(['id' => $articulo])->update(['status'=>'En Reparacion']);
        }

        
        //return $group_user;
        if(auth()->user()->user=='admin'){
            $aprobadores = User::get()->where('user', auth()->user()->user);
        }else{
            $aprobadores = User::all()->where('group_id', $group_user)->whereIn('role_id', [1,2]);
            //return $aprobadores;
        }
        
        
        foreach($aprobadores as $e){
            $email[] = $e->email;
            
            $correo = new SalidasMailable($folio);
            Mail::to($e->email)->send($correo);
        }
    
        return  redirect()->to('/pase_salida_auth')->with('info', $folio);
    }

    public function salida_editar_auth($id){

        $ruta = '../';
        $numero=1;
        $registros = Register::find($id);
        $lineas = Line::with('articulos')->where('register_id', $id)->get();
        $long = sizeof($lineas);
        
        //return $lineas;
        return view('auth.registers.edit', compact('registros', 'lineas', 'numero', 'long', 'ruta')); 
    }

    public function salida_actualizar_auth(){

        $logged_user = auth()->user()->id;
        $id = request('register_id');
        
        $pasesalida = Register::query()->where(['id' => $id])->update(request(['person', 'vehicle', 'placas']));
        
        
        return  redirect()->to('/pase_salida_auth')->with('info_actualizado', $id);
    }

    public function auth_articulo_borrar($article_id, $line_id, $register_id){
        //return $id;
        $ruta = '../../../';
        
        $updateArticle = Article::query()->where(['id' => $article_id])->update(['status' => 'Disponible']);
        $articulo = Line::find($line_id);
        $articulo->delete();

        return  redirect()->route('salidas.editar.auth', [$register_id]);
    }

    public function salida_inactivar_auth($id){

        $logged_user = auth()->user()->id;

        $remision = Register::query()->where(['id' => $id])->update(['status' => 'cancelado', 'approval_id' => $logged_user]);
        $lineas = Line::where('register_id', $id)->get();
        
        foreach($lineas as $linea){
            
            $updateArticle = Article::query()->where(['id' => $linea->article_id])->update(['status' => 'Disponible']);
            $linea->delete();
        }

        $registro = Register::query()->where('id', $id)->get();
        $user = User::query()->where('id', $registro[0]->user_id)->get();
        //return $user[0]->email;
        
        $correo = new CancelarRemision($id);
        Mail::to($user[0]->email)->send($correo);

        return  redirect()->to('/pase_salida_auth')->with('info_cancelado', $id);
    }

    public function salida_mostrar_autorizados_auth($id){

        $ruta = '../';
        $registros = Register::find($id);
        $registros2 = Register::with('user')->where('id', $registros->id)->get();
        $lineas = Line::with('articulos')->where('register_id', $id)->get();
        //return $registros2;
        return view('auth.registers.mostrar_autorizados', compact('registros', 'registros2', 'lineas', 'ruta')); 
    }

    public function autorizar_auth(){

        $ruta = '';
        $registros = Register::with('group')->where('status', 'creado')->where('group_id', auth()->user()->group_id)->get();
        //return $registros;
        return view('auth.registers.autorizados', compact('registros', 'ruta'));
    }
    
    public function salida_autorizar_auth($id){

        $logged_user = auth()->user()->id;

        $pasesalida = Register::query()->where(['id' => $id])->update(['status'=>'aprobado', 'approval_id'=>$logged_user]);

        $registro = Register::query()->where('id', $id)->get();
        $user = User::query()->where('id', $registro[0]->user_id)->get();
        //return $user[0]->email;
        
        $correo = new AprobarRemision($id);
        Mail::to($user[0]->email)->send($correo);

        return  redirect()->to('/autorizar_auth')->with('info_actualizado', $id);
    }

    public function historial_auth(){

        $ruta = '';
        $registros = Register::with('group')->whereIn('status', ['finalizado', 'cancelado'])->where('group_id', auth()->user()->group_id)->get();
        //return $registros;
        return view('auth.registers.historial', compact('registros', 'ruta'));
    }
    
    /****************************************** */
    /************ Rutas de Usuario ************ */
    /****************************************** */
    public function register_user(){

        $ruta = '';
        $registros = Register::with('user', 'group')->whereNotIn('status', ['cancelado', 'finalizado'])->where('user_id', auth()->user()->id)->get();
        
        //return $registros;
        return view('user.registers.index', compact('registros', 'ruta'));
    }

    public function salida_nueva_user(){

        $ruta = '';        
        $group_user = auth()->user()->group_id;
        $categorias = [];

        $grupos = Group::find($group_user);
        $categories = Category::query()->whereIn('group_id', [$grupos->id])->get();


        foreach($categories as $category){
            $categorias[] = $category->id;
        }

        $articulos = Article::with('category')->where('status', 'Disponible')->whereIn('category_id', $categorias)->get();

        return view('user.registers.nuevo', compact('ruta', 'articulos', 'grupos'));
    }

    public function salida_crear_user(){


        $this->validate(request(), [
                'person' => 'required',
                'vehicle' => 'required',
                'placas' => 'required',
                'articulos' => 'required',
            ],
            [
                'person.required' => '¡La Persona es Obligatoria!',
                'vehicle.required' => '¡El Vehículo es Obligatorio!',
                'placas.required' => '¡El Vehículo es Obligatorio!',
                'articulos.required' => '¡Se debe escoger al menos 1 Artículo!',
            ]
        );

        $logged_user = auth()->user()->id;
        $group_user = auth()->user()->group_id;
        
        $register = Register::create(request(['person', 'vehicle', 'placas', 'user_id', 'group_id']));
        $data = Register::latest('id')->first();
        $folio = $data->id;
        
        //return request();
        //$updateRegistro = Register::query()->where('id', $folio)->update(['approval_id' => '0']);

        
        $articulos = request()->articulos;
        foreach($articulos as $articulo){
            
            $line = Line::create(['article_id'=>$articulo, 'register_id'=>$folio]);
            //$lineas = Line::query()->where(['id' => $line_id[$i]])->update(['article'=>$desc[$i], 'quantity'=>$cant[$i]]);
            $updateArticle = Article::query()->where(['id' => $articulo])->update(['status'=>'En Reparacion']);
        }

        
        //return $group_user;
        if(auth()->user()->user=='admin'){
            $aprobadores = User::get()->where('user', auth()->user()->user);
        }else{
            $aprobadores = User::all()->where('group_id', $group_user)->whereIn('role_id', [1,2]);
            //return $aprobadores;
        }
        
        
        foreach($aprobadores as $e){
            $email[] = $e->email;
            
            $correo = new SalidasMailable($folio);
            Mail::to($e->email)->send($correo);
        }
        
        return  redirect()->to('/pase_salida_user')->with('info', $folio);
    }

    public function salida_editar_user($id){
        
        $ruta = '../';
        $numero=1;
        $registros = Register::find($id);
        $lineas = Line::with('articulos')->where('register_id', $id)->get();
        $long = sizeof($lineas);
        
        //return $lineas;
        return view('user.registers.edit', compact('ruta', 'registros', 'lineas', 'numero', 'long')); 
    }

    public function salida_actualizar_user(){

        $logged_user = auth()->user()->id;
        $id = request('register_id');
        
        $pasesalida = Register::query()->where(['id' => $id])->update(request(['person', 'vehicle', 'placas']));
        
        
        return  redirect()->to('/pase_salida_user')->with('info_actualizado', $id);
    }

    public function user_articulo_borrar($article_id, $line_id, $register_id){
        //return $id;
        $ruta = '../../../';
        
        $updateArticle = Article::query()->where(['id' => $article_id])->update(['status' => 'Disponible']);
        $articulo = Line::find($line_id);
        $articulo->delete();

        return  redirect()->route('salidas.editar.user', [$register_id]);
    }
   
    public function salida_inactivar_user($id){

        $logged_user = auth()->user()->id;

        $grupo = Register::query()->where(['id' => $id])->update(['status' => 'cancelado', 'approval_id' => $logged_user]);
        $lineas = Line::where('register_id', $id)->get();

        foreach($lineas as $linea){
            
            $updateArticle = Article::query()->where(['id' => $linea->article_id])->update(['status' => 'Disponible']);
            $linea->delete();
        }

        return  redirect()->to('/pase_salida_user')->with('info_cancelado', $id);
    }

    public function historial_user(){

        $ruta = '';
        $registros = Register::with('group')->whereIn('status', ['finalizado', 'cancelado'])->where('user_id', auth()->user()->id)->get();
        //return $registros;
        return view('user.registers.historial', compact('registros', 'ruta'));
    }
    
    /****************************************** */
    /********** Rutas de Validador ************ */
    /****************************************** */
    public function register_val(){

        $ruta = '';
        $registros = Register::with('user2', 'user', 'group')->where('status', 'aprobado')->get();
        
        //return $registros;
        return view('validator.registers.index', compact('registros', 'ruta'));
    }

    public function salida_mostrar_autorizados_val($id){

        $ruta = '../';
        $registros = Register::find($id);
        $registros2 = Register::with('user')->where('id', $registros->id)->get();
        $lineas = Line::with('articulos')->where('register_id', $id)->get();
        $proveedor = Proveedor::query()->orderBy('proveedor', 'asc')->get();
        //return $registros2;
        return view('validator.registers.validar', compact('id', 'proveedor', 'registros', 'registros2', 'lineas', 'ruta')); 
    }

    public function salida_revisar_val(){

        $id = request()->register_id;
        $proveedor = request()->proveedor_id;
        $logged_user = auth()->user()->id;
        $referencia = request()->referencia;

        $grupo = Register::query()->where(['id' => $id])->update(['status' => 'validado', 'validation_id' => $logged_user, 'proveedor_id' => $proveedor, 'referencia' => $referencia]);

        return  redirect()->to('/pase_salida_val')->with('info_actualizado', $id);
    }

    public function salida_inactivar_val($id){

        $logged_user = auth()->user()->id;

        $grupo = Register::query()->where(['id' => $id])->update(['status' => 'cancelado', 'approval_id' => $logged_user]);
        $lineas = Line::where('register_id', $id)->get();

        foreach($lineas as $linea){
            
            $updateArticle = Article::query()->where(['id' => $linea->article_id])->update(['status' => 'Disponible']);
            $linea->delete();
        }
        return  redirect()->to('/pase_salida_val')->with('info_cancelado', $id);
    }
    
    /****************************************** */
    /********** Rutas de Revisor ************ */
    /****************************************** */
    public function register_rev(){

        $ruta = '';
        $registros = Register::with('user2', 'user', 'group')->where('status', 'validado')->get();
        
        //return $registros;
        return view('revisor.registers.index', compact('registros', 'ruta'));
    }

    public function salida_mostrar_autorizados_rev($id){

        $ruta = '../';
        $registros = Register::find($id);
        $registros2 = Register::with('user')->where('id', $registros->id)->get();
        $lineas = Line::with('articulos')->where('register_id', $id)->get();
        $proveedor = Proveedor::query()->where('id', $registros->proveedor_id)->get();
        //return $registros2;
        return view('revisor.registers.validar', compact('id', 'proveedor', 'registros', 'registros2', 'lineas', 'ruta')); 
    }

    public function salida_revisar_rev(){

        $id = request()->register_id;
        $proveedor = request()->proveedor_id;
        $logged_user = auth()->user()->id;
        $refencia = '3456';

        $grupo = Register::query()->where(['id' => $id])->update(['status' => 'enviado', 'validation_id' => $logged_user]);
        $findProveedor = Proveedor::query()->where(['proveedor' => $proveedor])->get();
        //return $findProveedor[0]->email;

        $correo = new EnviarRemision($id, $refencia);
        Mail::to($findProveedor[0]->email)->send($correo);


        return  redirect()->to('/pase_salida_rev')->with('info_actualizado', $id);
    }

    public function salida_inactivar_rev($id){

        $logged_user = auth()->user()->id;

        $grupo = Register::query()->where(['id' => $id])->update(['status' => 'cancelado', 'approval_id' => $logged_user]);
        $lineas = Line::where('register_id', $id)->get();

        foreach($lineas as $linea){
            
            $updateArticle = Article::query()->where(['id' => $linea->article_id])->update(['status' => 'Disponible']);
            $linea->delete();
        }
        return  redirect()->to('/pase_salida_val')->with('info_cancelado', $id);
    }
    
}