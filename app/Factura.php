<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Factura extends Model
{
    protected $fillable = ['consorcio_id', 'usuario_id', 'mes', 'anio', 'emision', 'vencimiento', 'total'];

    public static function obtenerDetalleDeFacturas($facturasSinDetalle){
        foreach ($facturasSinDetalle as $factura){
            $factura->periodo = "$factura->mes-$factura->anio";

            $idUnidadesInvolucradas = Unidad::obtenerIdDeUnidadesPorUsuarioYConsorcio($factura->usuario_id, $factura->consorcio_id);

            $expensas = Expensa::obtenerExpensasPorMesAnioUnidades($factura->mes, $factura->anio, $idUnidadesInvolucradas);

            foreach ($expensas as $expensa){
                $coeficiente = Unidad::calcularCoeficiente($factura->consorcio_id);

                $conceptos[] = [
                    'unidad' => Unidad::find($expensa->unidad_id)->nombre,
                    'concepto' => "Expensas $expensa->mes-$expensa->año",
                    'valor_total' => round($expensa->importe/$coeficiente, 2),
                    'participacion' => round(($coeficiente*100), 2).'%',
                    'valor' => $expensa->importe
                ];
            }

            $factura->conceptos = $conceptos;

            $factura->gastos_del_periodo = Gasto::gastosMensualesPorConsorcio($factura->anio, $factura->mes, $factura->consorcio_id);
        }

        return $facturasSinDetalle;
    }

    public static function obtenerFacturasDetalladasPorUsuarioYConsorcio($usuarioId, $consorcioId){
        $facturas = DB::table('facturas')
            ->where('usuario_id', $usuarioId)
            ->where('consorcio_id', $consorcioId)
            ->get();

        return Factura::obtenerDetalleDeFacturas($facturas);
    }
}
