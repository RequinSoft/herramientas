<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PrecioActual extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'precio:actual';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza el precio actual del articulo de acuerdo a la depreciación';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $hoy = Carbon::now();

        $articulo = Article::with('category')->whereNotIn('status', ['Danado', 'Baja'])->get();
        
        foreach($articulo as $row){
            $categoria = Category::find($row->category_id);
            $depreciacion = $categoria->depreciacion;
            $dias_restar = ($row->created_at)->diffInDays($hoy);

            $dias_precio = $depreciacion - $dias_restar;

            $precio_actual = (($row->precio_inicial)/$depreciacion) * $dias_precio;
            $precio_actual = (int)$precio_actual;

            $updatedatos = Article::query()->where(['id' => $row->id])->update(['precio_actual' => $precio_actual]);
        }
    }
}
