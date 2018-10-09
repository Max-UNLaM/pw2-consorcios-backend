<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Informe extends Model
{
    protected $fillable = ['mes', 'anio', 'consorcio_id', 'liquidacion_id', 'liquidacion', 'gastos_del_periodo', 'pagos_del_periodo'];

    public static function generarInformeMesAnioConsorcio($mes, $anio, $consorcioId, $liquidacion){

        Informe::create([
            'mes' => $mes,
            'anio' => $anio,
            'consorcio_id' => $consorcioId,
            'liquidacion_id' => $liquidacion->id
        ]);

    }

    public static function agregarInformacion($informe){
        $mes = $informe->mes;
        $anio = $informe->anio;
        $consorcioId = $informe->consorcio_id;

        $informe->liquidacion = Liquidacion::find($informe->liquidacion_id);
        $informe->gastos_del_periodo = Gasto::filterByMesAnioConsorcio($mes, $anio, $consorcioId)->get();
        $informe->pagos_del_periodo = Pago::filterByMesAnioConsorcio($mes, $anio, $consorcioId)->get();
    }

    public static function list(){
        return DB::table('informes');
    }
}
