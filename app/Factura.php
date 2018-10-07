<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Factura extends Model
{
    protected $fillable = ['consorcio_id', 'usuario_id', 'expensa_id', 'mes', 'anio', 'emision', 'vencimiento', 'total', 'pago_parcial', 'adeuda', 'pago'];

    public static function obtenerDetalleDeFacturas($facturasSinDetalle)
    {
        foreach ($facturasSinDetalle as $factura) {
            $factura->periodo = "$factura->mes-$factura->anio";

            $idUnidadesInvolucradas = Unidad::obtenerIdDeUnidadesPorUsuarioYConsorcio($factura->usuario_id, $factura->consorcio_id);

            $expensas = Expensa::obtenerExpensasPorMesAnioUnidades($factura->mes, $factura->anio, $idUnidadesInvolucradas);

            foreach ($expensas as $expensa) {
                $coeficiente = Unidad::calcularCoeficiente($factura->consorcio_id);

                $conceptos[] = [
                    'unidad' => Unidad::find($expensa->unidad_id)->nombre,
                    'concepto' => "Expensas $expensa->mes-$expensa->anio",
                    'valor_total' => round($expensa->importe / $coeficiente, 2),
                    'participacion' => round(($coeficiente * 100), 2) . '%',
                    'valor' => $expensa->importe
                ];
            }

            $factura->conceptos = $conceptos;

            $factura->gastos_del_periodo = Gasto::gastosMensualesPorConsorcio($factura->anio, $factura->mes, $factura->consorcio_id);
        }

        return $facturasSinDetalle;
    }

    public static function obtenerFacturasDetalladasPorUsuarioYConsorcio($usuarioId, $consorcioId, $size)
    {
        $respuesta = DB::table('facturas')
            ->where('usuario_id', $usuarioId)
            ->where('consorcio_id', $consorcioId)
            ->paginate($size);

        return Factura::obtenerDetalleDeFacturas($respuesta->items());
    }


    public static function obtenerFacturasDetalladasPorUsuario($usuarioId, $size)
    {
        $respuesta = (DB::table('facturas')
            ->where('usuario_id', $usuarioId)
            ->paginate($size));
        Factura::obtenerDetalleDeFacturas($respuesta->items());
        return $respuesta;
    }

    public static function abrirFacturaDetallada($facturaId)
    {
        return Factura::listarFacturaDetallada()->where('facturas.id', $facturaId);
    }

    public static function listarFacturaDetallada()
    {
        return DB::table('facturas')
            ->join('expensas as e', 'facturas.anio', '=',  'e.anio')
            ->join('expensas as ex','facturas.mes','=','ex.mes');
    }

    public static function obtenerFacturasPorConsorcio($consorcioId)
    {
        return DB::table('facturas')
            ->where('consorcio_id', $consorcioId)
            ->get();
    }

    public static function obtenerFacturasPorConsorcioMesAnio($consorcioId, $mes, $anio)
    {
        return Factura::obtenerFacturasPorConsorcio($consorcioId)
            ->where('mes', $mes)
            ->where('anio', $anio);
    }

    public static function facturaById(string $id)
    {
        return Factura::find($id);
    }

    public static function facturarPeriodo($consorcioId, $mes, $anio)
    {
        $expensas = Expensa::expensasEnElPeriodo($consorcioId, $mes, $anio);

        foreach ($expensas as $expensa){
            $unidad = Unidad::find($expensa->unidad_id);
            $usuarioId = $unidad->propietarioId();

            Factura::create([
                'consorcio_id' => $consorcioId,
                'usuario_id' => $usuarioId,
                'expensa_id' => $expensa->id,
                'mes' => $mes,
                'anio' => $anio,
                'emision' => "$anio-$mes-10",
                'vencimiento' => "$anio-$mes-20",
                'total' => $expensa->importe,
                'pago_parcial' => 0,
                'adeuda' => $expensa->importe,
                'pago' => 'IMPAGO'
            ]);
        }
    }

    public static function cantidadDeFacturasEnElPeriodo($consorcioId, $mes, $anio)
    {
        $facturas = Factura::obtenerFacturasPorConsorcioMesAnio($consorcioId, $mes, $anio);

        return sizeof($facturas);
    }

    public static function list(){
        return DB::table('facturas')
            ->join('users', 'users.id', '=', 'facturas.usuario_id')
            ->join('consorcios', 'consorcios.id', '=', 'facturas.consorcio_id')
            ->addSelect([
                'facturas.id as id',
                'facturas.mes as mes',
                'facturas.anio as anio',
                'facturas.emision as emision',
                'facturas.vencimiento as vencimiento',
                'facturas.total as total',
                'facturas.pago_parcial as pago_parcial',
                'facturas.adeuda as adeuda',
                'consorcios.id as consorcio_id',
                'consorcios.nombre as consorcio_nombre',
                'users.id as usuario_id',
                'users.name as usuario_nombre'
            ])
            ->orderByDesc('facturas.id');
    }

    public static function filterByConsorcio($consorcioId){
        return Factura::list()
            ->where('consorcios.id', $consorcioId);
    }
}
