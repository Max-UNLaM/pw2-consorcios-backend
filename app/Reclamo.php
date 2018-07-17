<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Reclamo extends Model
{
    protected $fillable = ['usuario_id', 'unidad_id', 'motivo', 'fecha_reclamo', 'fecha_resolucion', 'conforme'];

    public static function getIdAllReclamosOfUser(int $userId) {
        return DB::table('reclamo')
            ->where('usuario_id', $userId)
            ->get(['id']);
    }

    public static function getAllReclamosOfUser(int $userId) {
        return DB::table('reclamos')
            ->where('usuario_id', $userId);
    }
}
