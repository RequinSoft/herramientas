<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Register;
use Illuminate\Support\Carbon;

class PrincipalController extends Controller
{
    //

    public function index_user(){

        $category = [];
        $ruta = '';
        $labels = [];
        $cantidad = [];
        $disponibles = [];
        $reparacion = [];

        $creados = Register::where('status', 'creado')->where('user_id', auth()->user()->id)->count();
        $aprobados = Register::where('status', 'aprobado')->where('user_id', auth()->user()->id)->count();
        $categorias = Category::where('status', 'activo')->whereNot('category', 'Default')->where('group_id', auth()->user()->group_id)->count();

        $cat = Category::query()->where('status', 'activo')->where('group_id', auth()->user()->group_id)->get();
        foreach($cat as $cat){
            $category[] = $cat->id;
        }
        $articulos = Article::whereNotIn('status', ['Danado', 'Baja'])->whereIn('category_id', $category)->count();


        $cate = Category::query()->whereNot('category', ['Default'])->where('group_id', auth()->user()->group_id)->get();
        //return $cate;

        foreach($cate as $cate){
            $labels[] = $cate->category;
            
            $articulos = Article::where('category_id', $cate->id)->count();
            $art_disponibles = Article::where('category_id', $cate->id)->where('status', 'Disponible')->count();
            $art_reparacion = Article::where('category_id', $cate->id)->where('status', 'En Reparacion')->count();

            $cantidad[] = $articulos;
            $disponibles[] = $art_disponibles;
            $reparacion[] = $art_reparacion;

        }
        
        $labels = json_encode($labels);
        $cantidad = json_encode($cantidad);
        $disponibles = json_encode($disponibles);
        $reparacion = json_encode($reparacion);
        //return $cantidad;
        return view('user.index', compact('creados', 'aprobados', 'articulos', 'ruta', 'categorias', 'labels', 'cantidad', 'disponibles', 'reparacion'));
    }

    public function index_validator(){

        $ruta = '';
        $pases = Register::where('status', 'aprobado')->count();
        $aprobados = Register::where('status', 'aprobado')->where('user_id', auth()->user()->id)->count();

        return view('validator.index', compact('pases', 'aprobados', 'ruta'));
    }
    
    public function index_auth(){

        $category = [];
        $ruta = '';
        $labels = [];
        $cantidad = [];
        $disponibles = [];
        $reparacion = [];

        $creados = Register::where('status', 'creado')->where('group_id', auth()->user()->group_id)->count();
        $aprobados = Register::where('status', 'aprobado')->where('user_id', auth()->user()->id)->count();
        $categorias = Category::where('status', 'activo')->whereNot('category', 'Default')->where('group_id', auth()->user()->group_id)->count();

        $cat = Category::query()->where('status', 'activo')->where('group_id', auth()->user()->group_id)->get();
        foreach($cat as $cat){
            $category[] = $cat->id;
        }
        $articulos = Article::whereNotIn('status', ['Danado', 'Baja'])->whereIn('category_id', $category)->count();


        $cate = Category::query()->whereNot('category', ['Default'])->where('group_id', auth()->user()->group_id)->get();
        //return $cate;

        foreach($cate as $cate){
            $labels[] = $cate->category;
            
            $articulos = Article::where('category_id', $cate->id)->count();
            $art_disponibles = Article::where('category_id', $cate->id)->where('status', 'Disponible')->count();
            $art_reparacion = Article::where('category_id', $cate->id)->where('status', 'En Reparacion')->count();

            $cantidad[] = $articulos;
            $disponibles[] = $art_disponibles;
            $reparacion[] = $art_reparacion;

        }
        
        $labels = json_encode($labels);
        $cantidad = json_encode($cantidad);
        $disponibles = json_encode($disponibles);
        $reparacion = json_encode($reparacion);
        //return $cantidad;
        return view('auth.index', compact('creados', 'aprobados', 'articulos', 'ruta', 'categorias', 'labels', 'cantidad', 'disponibles', 'reparacion'));
    }
    
    public function index_revisor(){

        $ruta = '';

        $aprobados = Register::where('status', 'validado')->count();

        return view('revisor.index', compact('aprobados', 'ruta'));
    }
    
}
