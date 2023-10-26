<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Article;
use App\Models\Category;
use App\Models\Ldap;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    //
    public function username(){

        return 'username';
    }

    protected function credentials(Request $request){

        $credentials = [
            'uid' => $request->get('user'),
            'password' => $request->get('password'),
        ];
        return $credentials;
        if (Auth::attempt($credentials)) {
            $user = Auth::user()->name;
        
            // Returns true:
            //$user instanceof \LdapRecord\Models\Model;
            return redirect()->route('admin.index'); 
        }
    }

    public function index(){

        $ruta = '';

        return view('welcome', compact('ruta'));
    }

    public function login(Request $request){
        //Reglas de los campos        
        $this->validate(request(), [
                'user' => 'required',
                'password' => 'required',
            ],
            [
                'user.required' => 'El usuario es obligatorio',
                'password.required' => 'La contraseña es obligatoria',
            ]
        );

        $hoy = Carbon::now();

        $usuario = request()->user;
        $userdb = User::query()->where('user', $usuario)->get();
        //return $userdb;
        
        if($userdb->count() == 0){
            return redirect()->to('/')->with('empty', "El Usuarios no existe");
        }

        $articulo = Article::with('category')->whereIn('status', ['Disponible', 'Asignado', 'En Reparacion'])->get();
        
        foreach($articulo as $row){
            $categoria = Category::find($row->category_id);
            $depreciacion = $categoria->depreciacion;
            $dias_restar = ($row->created_at)->diffInDays($hoy);

            $dias_precio = $depreciacion - $dias_restar;

            $precio_actual = (($row->precio_inicial)/$depreciacion) * $dias_precio;
            $precio_actual = (int)$precio_actual;
            if($precio_actual <= 0){
                $precio_actual = 0;
            }

            $updatedatos = Article::query()->where(['id' => $row->id])->update(['precio_actual' => $precio_actual]);
        }

        $ldap = Ldap::all();
        //return request()->password;

        $ldap_server = $ldap[0]->ldap_server;
        $ldap_dominio = $ldap[0]->ldap_domain;
        $ldap_port = $ldap[0]->ldap_port;
        $ldap_user = request()->user.'@'.$ldap_dominio;
        $ldap_pass =  request()->password;
        $ldap_version = $ldap[0]->ldap_version;

        $ldap_conn = ldap_connect($ldap_server, $ldap_port);
        ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, $ldap_version);
        ldap_set_option($ldap_conn, LDAP_OPT_REFERRALS, 0);

        if($userdb[0]->auten == 2){
            //Conexión con el servidor LDAP
            //return $userdb[0]->auten;

            $ldap_conn = ldap_connect($ldap_server, $ldap_port);
            ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, $ldap_version);
            ldap_set_option($ldap_conn, LDAP_OPT_REFERRALS, 0);

            
            if(@ldap_bind($ldap_conn, $ldap_user, $ldap_pass)){
                $update_pass = User::query()->where(['id' => $userdb[0]->id])->update(['password' => bcrypt(request('password'))]);

                //return request(['user', 'password']);
                if(auth()->attempt(request(['user', 'password'])) == false){
                    return  redirect()->to('/')->with('error', "Falla del sistema");
                }else {
                    if(auth()->user()->role_id == 1){
                        return redirect()->route('admin.index');
                    }else if (auth()->user()->role_id == 2){
                        return redirect()->route('coadmin.index');
                    }else if (auth()->user()->role_id == 3){
                        return redirect()->route('receptor.index');
                    }
                }
            }
            else{
                return back()->withErrors([
                    'message' => '¡El Usuario y/o la Contraseña son incorrectos!',
                ]);
            }

        }else if ($userdb[0]->auten == 1){

            if(auth()->attempt(request(['user', 'password'])) == false){
                return back()->withErrors([
                    'message' => '¡El Usuario y/o la Contraseña son incorrectos!',
                ]);
            }else {
                if(auth()->user()->role_id == 1){
                    return redirect()->route('admin.index');
                }else if (auth()->user()->role_id == 2){
                    return redirect()->route('coadmin.index');
                }else if (auth()->user()->role_id == 3){
                    return redirect()->route('receptor.index');
                }
            }
        }


 
    }

    public function destroy(){

        auth()->logout();
        return redirect()->to('/');
    }

    protected function ldap_login(Request $request){

    }
}