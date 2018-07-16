<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Expensa extends Model
{
    protected $fillable = ['unidad_id', 'año', 'mes', 'estado', 'emision', 'vencimiento', 'importe'];

    public static function listByUnidad($unidadId) {
        return DB::table('expensas')
            ->where('unidad_id', $unidadId)
            ->get();
    }
}
