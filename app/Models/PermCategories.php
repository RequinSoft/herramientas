<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermCategories extends Model
{
    use HasFactory;

    protected $table = 'perm-categories';

    protected $fillable = [
        'group_id',
        'categories_id',
    ];



}
