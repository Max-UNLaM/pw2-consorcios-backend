<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Liquidacion extends Model
{
    protected $fillable = ['mes', 'anio', 'consorcio_id', 'valor', 'valor_sin_coeficiente'];

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
            ->select([
                'liquidacions.id as liquidacion_id',
                'liquidacions.mes as liquidacion_mes',
                'liquidacions.anio as liquidacion_anio',
                'liquidacions.valor_sin_coeficiente as liquidacion_valor_sin_coeficiente',
                'liquidacions.valor as liquidacion_valor',
                'consorcios.id as consorcio_id',
                'consorcios.nombre as consorcio_nombre',
                'consorcios.direccion as consorcio_direccion',
                'consorcios.localidad as consorcio_localidad',
                'consorcios.provincia as consorcio_provincia',
                'consorcios.telefono as consorcio_telefono',
                'consorcios.email as consorcio_email',
                'consorcios.codigo_postal as consorcio_codigo_postal',
                'consorcios.cuit as consorcio_cuit',
                'consorcios.cantidad_de_pisos as consorcio_cantidad_de_pisos',
                'consorcios.departamentos_por_piso as consorcio_departamentos_por_piso'
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

        if($gastosMensuales == 0) return response("No hay gastos en el mes anterior al periodo seleccionado", 400);

        $liquidacion = Liquidacion::create([
            'mes' => $mes,
            'anio' => $anio,
            'consorcio_id' => $consorcioId,
            'valor' => $gastosMensuales * $coeficiente,
            'valor_sin_coeficiente' => $gastosMensuales
        ]);

        Informe::generarInformeMesAnioConsorcio($mes, $anio, $consorcioId, $liquidacion);

        return $liquidacion;
    }
}
