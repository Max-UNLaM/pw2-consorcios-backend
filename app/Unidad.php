<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Unidad extends Model
{
    protected $fillable = ['nombre', 'direccion', 'localidad', 'provincia', 'consorcio_id', 'usuario_id'];


    public static function calcularCoeficiente(int $id)
    {
        $total = DB::table('unidads')
            ->where('consorcio_id', Unidad::obtenerSucursal($id)->id)
            ->count();
        return 1 / $total;
    }

    public static function getAllUnidadOfUser(int $userId) {
        return DB::table('unidads')
            ->where('usuario_id', $userId)
            ->get(['id']);
    }


    public static function obtenerSucursal(int $id) {
        return DB::table('unidads')
            ->where('id', $id)
            ->get()[0];
    }
}
