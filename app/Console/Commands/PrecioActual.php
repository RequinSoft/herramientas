<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\Line;
use App\Models\EquipDown;
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
        $months = [1,2,3,4,5,6,7,8,9,10,11,12];
        $year = $hoy->format('Y');
        print("Año actual: {$year}\n");

        $articulo = Article::with('category')->whereNotIn('status', ['Robado', 'Baja', 'Extraviado'])->get();
        //print($articulo);

        foreach($articulo as $row){
            $categoria = Category::find($row->category_id);
            $depreciacion = $categoria->depreciacion;
            //print("{$depreciacion}\n");
            $dias_restar = ($row->created_at)->diffInDays($hoy);
            //print("{$dias_restar}\n");

            $dias_precio = $depreciacion - $dias_restar;

            $precio_actual = (($row->precio_inicial)/$depreciacion) * $dias_precio;
            $precio_actual = (int)$precio_actual;

            $updatedatos = Article::query()->where(
                [
                    'id' => $row->id,
                ]
                )->update(
                    [
                        'precio_actual' => $precio_actual
                    ]
                );
        }


        $categorias = Category::with('investment')->get();

        foreach($categorias as $categoria){
            print("Categoria: {$categoria->category} - Precio->{$categoria->investment->sum('precio_actual')} - Cantidad->{$categoria->investment->count()} - Asignados->{$categoria->investment->where('status', 'Asignado')->count()}\n");

            $data = Inventory::updateOrCreate(
                [
                    'category_id' => $categoria->id
                ],
                [
                    'name' => $categoria->category,
                    'quantity' => $categoria->investment->count(),
                    'investment' => $categoria->investment->sum('precio_actual'),
                    'available' => $categoria->investment->where('status', 'Disponible')->count(),
                    'assigned' => $categoria->investment->where('status', 'Asignado')->count(),
                    'group_id' => $categoria->group_id
                ]
            );
        }    
        
        $dataDown = Line::all();

        foreach($months as $month){

            foreach($categorias as $categoria){
                $consulta = Article::whereYear('updated_at', $year)
                            ->whereMonth('updated_at', $month)
                            ->where('category_id', $categoria->id)
                            ->get();

                $bajas = $consulta->where('status', 'Baja')->count();
                $bajas_moneda = $consulta->where('status', 'Baja')->sum('precio_actual');
                $robados = $consulta->where('status', 'Robado')->count();
                $robados_moneda = $consulta->where('status', 'Robado')->sum('precio_actual');
                $extraviados = $consulta->where('status', 'Extraviado')->count();
                $extraviados_moneda = $consulta->where('status', 'Extraviado')->sum('precio_actual');
                $asignados = $consulta->where('status', 'Asignado')->count();

                print("Mes: {$month} - Categoría {$categoria->category} - Cantidad: {$consulta->count()} - Bajas: {$bajas} - Robados: {$robados} - Extraviados: {$extraviados}\n");
                $insercion = EquipDown::updateOrCreate(
                    [
                        'name' => $categoria->category,
                        'year' => $year,
                        'month' => $month
                    ],
                    [
                        'group_id' => $categoria->group_id,
                        'year' => $year,
                        'month' => $month,
                        'bajas' => $bajas,
                        'bajas_moneda' => $bajas_moneda,
                        'robados' => $robados,
                        'robados_moneda' => $robados_moneda,
                        'extraviados' => $extraviados,
                        'extraviados_moneda' => $extraviados_moneda,
                        'asignados' => $asignados,
                    ]
                );
            }



        }
    }
}
