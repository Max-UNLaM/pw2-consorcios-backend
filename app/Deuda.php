<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Deuda extends Model
{
    protected $fillable = ['informe_id', 'mes', 'anio', 'usuario_id', 'total_factura', 'pago_parcial', 'adeuda', 'vencimiento'];

    public static function list(){
        return DB::table('deudas')
            ->join('users', 'deudas.usuario_id', '=', 'users.id')
            ->join('unidads', 'unidads.usuario_id', '=', 'users.id');
    }

    public static function filterByInforme($informeId){
        return Deuda::list()
            ->where('informe_id', $informeId);
    }
}
