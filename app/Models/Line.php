<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'personal_id',
        'users_id',
        'comentario',
        'estatus',
    ];

    public function pase_salida(){

        return $this->belongsTo(Register::class, 'id');
    }

    public function articulos(){

        //return $this->belongsTo(Register::class, 'id');
        return $this->belongsTo(Article::class, 'article_id');
    }

    public function usuario(){

        //return $this->belongsTo(Register::class, 'id');
        return $this->belongsTo(User::class, 'users_id');
    }

    public function personal(){

        //return $this->belongsTo(Register::class, 'id');
        return $this->belongsTo(Personal::class, 'personal_id');
    }


}
