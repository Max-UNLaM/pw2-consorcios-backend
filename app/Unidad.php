<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Unidad extends Model
{
    protected $fillable = ['nombre', 'consorcio_id', 'usuario_id'];


    public static function calcularCoeficiente(int $id)
    {
        $total = DB::table('unidads')
            ->where('consorcio_id', Unidad::obtenerSucursal($id)->id)
            ->count();

        return ($total != 0) ? (1 / $total) : 1;
    }

    public static function getIdAllUnidadOfUser(int $userId) {
        return DB::table('unidads')
            ->where('usuario_id', $userId)
            ->get(['id']);
    }

    public static function obtenerIdUnidadesPorIdConsorcio(int $consorcio_id) {
        return DB::table('unidads')
            ->where('consorcio_id', $consorcio_id)
            ->get(['id']);
    }

    public static function getAllUnidadOfUser(int $userId) {
        return DB::table('unidads')
            ->where('usuario_id', $userId);
    }


    public static function obtenerSucursal(int $id) {
        return DB::table('unidads')
            ->where('id', $id)
            ->get()[0];
    }
}
