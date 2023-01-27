<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use App\Models\Role;
use App\Mail\ResetPass;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    //


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
}
