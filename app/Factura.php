<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Factura extends Model
{
    protected $fillable = ['consorcio_id', 'usuario_id', 'mes', 'anio', 'emision', 'vencimiento', 'total', 'pago_parcial', 'adeuda'];

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

        Factura::obtenerDetalleDeFacturas($respuesta->items());

        return $respuesta;
    }

    public static function obtenerFacturasDetalladasPorUsuario($usuarioId, $size)
    {
        $respuesta = (DB::table('facturas')
            ->where('usuario_id', $usuarioId)
            ->paginate($size));

        Factura::obtenerDetalleDeFacturas($respuesta->items());

        return $respuesta;
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

    public static function facturarPeriodo($consorcioId, $mes, $anio)
    {
        $propietarios = Consorcio::obtenerPropietarios($consorcioId);
        $facturaciones = 0;
        foreach ($propietarios as $propietario) {
            $idUnidades = Unidad::obtenerIdDeUnidadesPorUsuarioYConsorcio($propietario->id, $consorcioId);

            $total = Expensa::obtenerImporteMensualPorMesAnioUnidades($mes, $anio, $idUnidades);

            if ($total != 0) {
                Factura::create([
                    'consorcio_id' => $consorcioId,
                    'usuario_id' => $propietario->id,
                    'mes' => $mes,
                    'anio' => $anio,
                    'emision' => "$anio-$mes-10",
                    'vencimiento' => "$anio-$mes-20",
                    'total' => $total,
                    'pago_parcial' => 0,
                    'adeuda' => $total
                ]);
                $facturaciones++;
            }
        }
        return $facturaciones;
    }

    public static function cantidadDeFacturasEnElPeriodo($consorcioId, $mes, $anio)
    {
        $facturas = Factura::obtenerFacturasPorConsorcioMesAnio($consorcioId, $mes, $anio);

        return sizeof($facturas);
    }
}
