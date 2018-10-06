<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Liquidacion extends Model
{
    protected $fillable = ['mes', 'anio', 'consorcio_id', 'valor'];

    public static function obtenerTotalPorMesAnioConsorcio($mes, $anio, $consorcioId){
        return DB::table('liquidacions')
            ->where('mes', $mes)
            ->where('anio', $anio)
            ->where('consorcio_id', $consorcioId)
            ->sum('valor');
    }

    public static function list(){
        return DB::table('liquidacions')
            ->join('consorcios', 'consorcios.id', '=', 'liquidacions.consorcio_id')
            ->addSelect([
                'liquidacions.id as id',
                'consorcios.id as consorcio_id',
                'consorcios.nombre as consorcio_nombre',
                'liquidacions.mes as mes',
                'liquidacions.anio as anio',
                'liquidacions.valor as valor'
            ]);
    }

    public static function filterByConsorcio($consorcioId){
        return Liquidacion::list()
            ->where('consorcios.id', $consorcioId);
    }
}
