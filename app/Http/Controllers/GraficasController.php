<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Line;
use Illuminate\Http\Request;

class GraficasController extends Controller
{
    //
    
    public function graficas_articulos(){

        $ruta = '';
        $categorias = Category::query()->whereNot('category', ['Default'])->get();

        $labels = [];
        $cantidad = [];
        $disponibles = [];
        $reparacion = [];

        foreach($categorias as $categoria){
            $labels[] = $categoria->category;
            
            $articulos = Article::where('category_id', $categoria->id)->count();
            $art_disponibles = Article::where('category_id', $categoria->id)->where('status', 'Disponible')->count();
            $art_reparacion = Article::where('category_id', $categoria->id)->where('status', 'En Reparacion')->count();

            $cantidad[] = $articulos;
            $disponibles[] = $art_disponibles;
            $reparacion[] = $art_reparacion;

        }
        
        $labels = json_encode($labels);
        $cantidad = json_encode($cantidad);
        $disponibles = json_encode($disponibles);
        $reparacion = json_encode($reparacion);

        //return $labels;
        return view('admin.graficas.index', compact('labels', 'cantidad', 'disponibles', 'reparacion', 'ruta'));
    }

    public function graficas_categorias(){

        $ruta = '';
        $categorias = Category::query()->whereNot('category', ['Default'])->get();

        $labels = [];
        $cantidad = [];
        $disponibles = [];
        $reparacion = [];
        $articulo = [];

        foreach($categorias as $categoria){
            
            unset($articulo);
            $articulo = [];
            $labels[] = $categoria->category;
            
            $articulos = Article::where('category_id', $categoria->id)->get();
            foreach($articulos as $item0){
                $articulo[] = $item0->id;
            }
            
            $cantidad_articulos = Line::whereIn('article_id', $articulo)->count();
             
            $art_disponibles = Article::where('category_id', $categoria->id)->where('status', 'Disponible')->count();
            $art_reparacion = Article::where('category_id', $categoria->id)->where('status', 'En Reparacion')->count();




            $cantidad[] = $cantidad_articulos;
            $disponibles[] = $art_disponibles;
            $reparacion[] = $art_reparacion;

            //return $cantidad_articulos;
        }
        //return $articulo;
        $labels = json_encode($labels);
        $cantidad = json_encode($cantidad);
        $disponibles = json_encode($disponibles);
        $reparacion = json_encode($reparacion);

        //return $labels;
        return view('admin.graficas.categorias', compact('labels', 'cantidad', 'disponibles', 'reparacion', 'ruta'));
    }

    public function graficas_escogerArticulo(){

        $ruta = '';
        $articulos = Article::all();

        return view('admin.graficas.escoger_articulo', compact('ruta', 'articulos'));
    }

    public function graficas_reparaciones(){

        $this->validate(request(), [
                'articulos' => 'required'
            ],
            [
                'articulos.required' => '¡Se debe escoger al menos 1 Artículo!',
            ]
        );

        $articulos = request()->articulos;
        $ruta = '';

        $labels = [] ;
        $cantidad = [];
        $disponibles = [];
        $reparacion = [];

        foreach($articulos as $articulo){
            
            $article = Article::find($articulo);


            $labels[] = $article->article.'-'.$article->ns;
            $art_disponibles = Line::query()->where('article_id', $articulo)->count();
            $cantidad[] = $art_disponibles;
            /*
            $art_reparacion = Article::where('category_id', $categoria->id)->where('status', 'En Reparacion')->count();

            $disponibles[] = $art_disponibles;
            $reparacion[] = $art_reparacion;
            */

        }
        //return $labels;
        $labels = json_encode($labels);
        $cantidad = json_encode($cantidad);
        $disponibles = json_encode($disponibles);
        $reparacion = json_encode($reparacion);

        //return $labels;
        return view('admin.graficas.reparaciones_articulos', compact('labels', 'cantidad', 'disponibles', 'reparacion', 'ruta'));
    }

    //Tablas    
    public function tablas_articulos(){

        $ruta = '';
        $categorias = Category::query()->whereNot('category', ['Default'])->get();

        $labels = [];
        $cantidad = [];
        $disponibles = [];
        $reparacion = [];

        foreach($categorias as $categoria){
            $labels[] = $categoria->category;
            
            $articulos = Article::where('category_id', $categoria->id)->count();
            $art_disponibles = Article::where('category_id', $categoria->id)->where('status', 'Disponible')->count();
            $art_reparacion = Article::where('category_id', $categoria->id)->where('status', 'En Reparacion')->count();

            $cantidad[] = $articulos;
            $disponibles[] = $art_disponibles;
            $reparacion[] = $art_reparacion;

        }

        //return $labels;
        return view('admin.tablas.index', compact('categorias', 'labels', 'cantidad', 'disponibles', 'reparacion', 'ruta'));
    }

    public function tablas_categorias(){

        $ruta = '';
        $categorias = Category::query()->whereNot('category', ['Default'])->get();

        $labels = [];
        $cantidad = [];
        $disponibles = [];
        $reparacion = [];
        $articulo = [];

        foreach($categorias as $categoria){
            
            unset($articulo);
            $articulo = [];
            $labels[] = $categoria->category;
            
            $articulos = Article::where('category_id', $categoria->id)->get();
            foreach($articulos as $item0){
                $articulo[] = $item0->id;
            }
            
            $cantidad_articulos = Line::whereIn('article_id', $articulo)->count();
             
            $art_disponibles = Article::where('category_id', $categoria->id)->where('status', 'Disponible')->count();
            $art_reparacion = Article::where('category_id', $categoria->id)->where('status', 'En Reparacion')->count();




            $cantidad[] = $cantidad_articulos;
            $disponibles[] = $art_disponibles;
            $reparacion[] = $art_reparacion;

            //return $cantidad_articulos;
        }
        

        //return $labels;
        return view('admin.tablas.categorias', compact('labels', 'categorias', 'cantidad', 'disponibles', 'reparacion', 'ruta'));
    }

    public function tablas_escogerArticulo(){

        $ruta = '';
        $cantidad = [];
        $articulos = Article::all();
        foreach($articulos as $articulo){

            $reparaciones = Line::query()->where('article_id', $articulo->id)->count();
            $cantidad[] = $reparaciones;
        }
        
        return view('admin.tablas.escoger_articulo', compact('ruta', 'articulos', 'cantidad'));
    }

    public function tablas_reparaciones(){

        $this->validate(request(), [
                'articulos' => 'required'
            ],
            [
                'articulos.required' => '¡Se debe escoger al menos 1 Artículo!',
            ]
        );

        $articulos = request()->articulos;
        $ruta = '';

        $labels = [] ;
        $cantidad = [];
        $disponibles = [];
        $reparacion = [];

        foreach($articulos as $articulo){
            
            $article = Article::find($articulo);


            $labels[] = $article->article.'-'.$article->ns;
            $art_disponibles = Line::query()->where('article_id', $articulo)->count();
            $cantidad[] = $art_disponibles;
            /*
            $art_reparacion = Article::where('category_id', $categoria->id)->where('status', 'En Reparacion')->count();

            $disponibles[] = $art_disponibles;
            $reparacion[] = $art_reparacion;
            */

        }
        

        //return $labels;
        return view('admin.tablas.reparaciones_articulos', compact('labels', 'cantidad', 'disponibles', 'reparacion', 'ruta'));
    }
}
