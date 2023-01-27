<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'article',
        'description',
        'ns',
        'marca',
        'modelo',
        'precio_inicial',
        'precio_actual',
        'comentario1',
        'category_id',
        'status',
    ];

    public function category(){

        return $this->belongsTo(Category::class);
    }    
}
