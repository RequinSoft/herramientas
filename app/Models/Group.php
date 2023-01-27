<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'description',
        'group_id',
        'status',
        'action_by',
    ];

    public function user(){

        return $this->hasMany(User::class);
    }

    public function register(){

        return $this->hasMany(Register::class);
    }

    public function categoria(){

        return $this->hasMany(Category::class);
    }
}
