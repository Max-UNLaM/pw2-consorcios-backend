<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Deuda;

class Informe extends Model
{
    protected $fillable = ['mes', 'anio', 'consorcio_id', 'liquidacion_id'];

    public static function generarInformeMesAnioConsorcio($mes, $anio, $consorcioId, $liquidacion){

        $informe = Informe::create([
            'mes' => $mes,
            'anio' => $anio,
            'consorcio_id' => $consorcioId,
            'liquidacion_id' => $liquidacion->id
        ]);

        $facturasConDeuda = Factura::filterByMesAnioConsorcioConDeuda($mes, $anio, $consorcioId)->get();

        foreach ($facturasConDeuda as $factura){
            Deuda::create([
                'informe_id' => $informe->id,
                'mes' => $factura->mes,
                'anio' => $factura->anio,
                'usuario_id' => $factura->usuario_id,
                'total_factura' => $factura->total,
                'pago_parcial' => $factura->pago_parcial,
                'adeuda' => $factura->adeuda,
                'vencimiento' => $factura->vencimiento
            ]);
        }
    }

    public static function agregarInformacion($informe){
        $mes = $informe->mes;
        $anio = $informe->anio;
        $consorcioId = $informe->consorcio_id;

        $informe->liquidacion = Liquidacion::find($informe->liquidacion_id);
        $informe->gastos_del_periodo = Gasto::filterByMesAnioConsorcio($mes, $anio, $consorcioId)->get();
        $informe->pagos_del_periodo = Pago::filterByMesAnioConsorcio($mes, $anio, $consorcioId)->get();
        $informe->deudas = Deuda::filterByInforme($informe->id)->get();

    }

    public static function list(){
        return DB::table('informes');
    }

    public static function filterByMesAnioConsorcio($mes, $anio, $consorcioId){
        return Informe::list()
            ->where('mes', $mes)
            ->where('anio', $anio)
            ->where('consorcio_id', $consorcioId);
    }
}
