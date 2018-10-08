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
            ])
            ->orderByDesc('liquidacions.id');
    }

    public static function filterByConsorcio($consorcioId){
        return Liquidacion::list()
            ->where('consorcios.id', $consorcioId);
    }

    public static function filterByMesAnio($mes, $anio){
        return Liquidacion::list()
            ->where('liquidacions.mes', $mes)
            ->where('liquidacions.anio', $anio);
    }

    public static function existeParaMesAnioConsorcio($mes, $anio, $consorcioId){
        $liquidaciones = Liquidacion::list()
            ->where('liquidacions.mes', $mes)
            ->where('liquidacions.anio', $anio)
            ->where('consorcios.id', $consorcioId)
            ->get();

        return sizeof($liquidaciones);
    }

    public static function liquidarMesAnioConsorcio($mes, $anio, $consorcioId){
        $coeficiente = 1.2;

        Gasto::generarGastosFijosMensuales($mes, $anio, $consorcioId);

        $gastosMensuales = Gasto::gastosMesAnioConsorcio($mes, $anio, $consorcioId);

        if($gastosMensuales == 0) return response("No hay gastos en el periodo seleccionado", 400);

        return Liquidacion::create([
            'mes' => $mes,
            'anio' => $anio,
            'consorcio_id' => $consorcioId,
            'valor' => $gastosMensuales * $coeficiente
        ]);
    }
}
