<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;


    protected $fillable = [
        'category',
        'description',
        'depreciacion',
        'status',
    ];

    public function categoria(){

        return $this->belongsTo(Group::class, 'group_id');
    }
}
