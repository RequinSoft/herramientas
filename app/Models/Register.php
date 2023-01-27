<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Group;
use App\Models\User;
use App\Models\Personal;
use Illuminate\Notifications\Action;

class Register extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'group_id',
        'user_id',
        'status',
    ];


    public function group(){

        return $this->belongsTo(Group::class);
    }

    public function user(){

        return $this->belongsTo(User::class);
    }

    public function persona(){

        return $this->belongsTo(Personal::class);
    }
}
