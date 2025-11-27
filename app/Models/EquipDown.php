<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipDown extends Model
{
    use HasFactory;

    protected $table = 'equipo_bajas';

    protected $fillable = [
        'group_id',
        'name',
        'month',
        'year',
        'bajas',
        'bajas_moneda',
        'robados',
        'robados_moneda',
        'extraviados',
        'extraviados_moneda',
        'asignados',
    ];
}
