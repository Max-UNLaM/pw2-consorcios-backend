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
        $facturas = array();

        foreach ($expensas as $expensa){
            $unidad = Unidad::find($expensa->unidad_id);
            $usuarioId = $unidad->propietarioId();

            $factura = Factura::create([
                'consorcio_id' => $consorcioId,
                'usuario_id' => $usuarioId,
                'expensa_id' => $expensa->id,
                'mes' => $mes,
                'anio' => $anio,
                'emision' => "$anio-$mes-01",
                'vencimiento' => "$anio-$mes-10",
                'total' => $expensa->importe,
                'pago_parcial' => 0,
                'adeuda' => $expensa->importe,
                'pago' => 'IMPAGO'
            ]);

            $facturas[] = $factura;
        }

        return $facturas;
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
            ->join('expensas', 'expensas.id', '=', 'facturas.expensa_id')
            ->join('unidads', 'unidads.id', '=', 'expensas.unidad_id')
            ->addSelect([
                'facturas.id as id',
                'users.id as usuario_id',
                'users.name as usuario_nombre',
                'consorcios.id as consorcio_id',
                'consorcios.nombre as consorcio_nombre',
                'unidads.id as unidad_id',
                'unidads.nombre as unidad_nombre',
                'expensas.id as expensa_id',
                'facturas.mes as mes',
                'facturas.anio as anio',
                'facturas.emision as emision',
                'facturas.vencimiento as vencimiento',
                'facturas.total as total',
                'facturas.pago_parcial as pago_parcial',
                'facturas.adeuda as adeuda',
                'facturas.pago as pago'
            ])
            ->orderByDesc('facturas.emision')
            ->orderByDesc('facturas.id');
    }

    public static function filterByConsorcio($consorcioId){
        return Factura::list()
            ->where('consorcios.id', $consorcioId);
    }

    public static function filterByMesAnio($mes, $anio){
        return Factura::list()
            ->where('facturas.mes', $mes)
            ->where('facturas.anio', $anio);
    }

    public static function filterByMesAnioConsorcio($mes, $anio, $consorcioId){
        return Factura::list()
            ->where('facturas.mes', $mes)
            ->where('facturas.anio', $anio)
            ->where('consorcios.id', $consorcioId);
    }

    public static function filterByMesAnioConsorcioConDeuda($mes, $anio, $consorcioId){
        return Factura::list()
            ->where('facturas.mes', $mes)
            ->where('facturas.anio', $anio)
            ->where('consorcios.id', $consorcioId)
            ->where('facturas.adeuda', '!=', 0);
    }

    public static function filterByUsuario($userId){
        return Factura::list()
            ->where('users.id', $userId);
    }

    public static function obtenerDeudaPorConsorcio($consorcioId){
        $deuda = DB::table('facturas')
            ->join('consorcios', 'consorcios.id', '=', 'facturas.consorcio_id')
            ->where('consorcios.id', $consorcioId)
            ->sum('facturas.adeuda');

        return round($deuda, 2);
    }
}
