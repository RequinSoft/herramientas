<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    //
    public function proveedor_index(){

        $ruta = '';
        $datos = Proveedor::all()->where('status', 'activo');

        return view('admin.proveedores.index', compact('datos', 'ruta'));
    }

    public function proveedor_nuevo(){

        $ruta = '';
        return view('admin.proveedores.nuevo', compact('ruta'));
    }

    public function proveedor_crear(){

        $this->validate(request(), [
                'proveedor' => 'required',
            ],
            [
                'proveedor.required' => 'El Nombre del Proveedor es Obligatorio',
            ]
        );
        
        //return request();
        $datos = Proveedor::create(request(['proveedor', 'contacto', 'email', 'direccion', 'telefono']));
        return  redirect()->to('/admin_proveedores');
    }

    public function proveedores_editar($id){

        $ruta = '../';
        //return "Este es el id -> $id";
        $datos = Proveedor::find($id);

        return view('admin.proveedores.editar', compact('datos', 'ruta'));
    }

    public function proveedores_actualizar(){

        $id = request (['id']);
        $logged_user = auth()->user()->id;

        $updatedatos = Proveedor::query()->where(['id' => $id])->update(request(['proveedor', 'contacto', 'email', 'direccion', 'telefono']));
        //$usuario = Category::query()->where(['id' => $id])->update(['action_by' => $logged_user]);

        return  redirect()->to('/admin_proveedores');
    }

    public function proveedor_inactivar($id){

        $datos = Proveedor::query()->where(['id' => $id])->update(['status' => 'inactivo']);

        return  redirect()->to('/admin_proveedores');
    }
}
