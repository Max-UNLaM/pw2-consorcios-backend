<?php

namespace App;

use Faker\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pago extends Model
{
    protected $fillable = [
        'usuario_id', 'factura_id', 'fecha', 'monto', 'medio_de_pago', 'estado', 'codigo_comprobante'
    ];

    public static function realizarPago($factura_id, $monto, $fecha, $user, $medioDePago, $codigoComprobante){
        $factura = Factura::find($factura_id);

        if($factura->adeuda == 0) return response(['Esta factura ya esta paga'], 400);
        if($monto > $factura->adeuda) return response(['El monto no puede ser mayor lo que se adeuda de la factura'], 400);


        $pago = Pago::create([
            'usuario_id' => $user->id,
            'factura_id' => $factura_id,
            'monto' => $monto,
            'fecha' => $fecha,
            'estado' => 'APROBACION_PENDIENTE',
            'codigo_comprobante' => $codigoComprobante,
            'medio_de_pago' => $medioDePago
        ]);

        if($user->isAdmin() || $user->isOperator()){
            $pago->aprobarPago();
        }

        return $pago;
    }

    public function aprobarPago(){
        $this->estado = 'APROBADO';
        $this->update();

        $factura = Factura::find($this->factura_id);
        $factura->pago_parcial+=$this->monto;
        $factura->adeuda-=$this->monto;
        if($factura->adeuda == 0) $factura->pago = 'PAGO';
        $factura->update();

        if($factura->adeuda == 0){
            $unidades = Unidad::obtenerIdDeUnidadesPorUsuarioYConsorcio($factura->usuario_id, $factura->consorcio_id);
            $expensas = Expensa::obtenerExpensasPorMesAnioUnidades($factura->mes, $factura->anio, $unidades);

            foreach ($expensas as $expensa){
                $exp = Expensa::find($expensa->id);
                $exp->pago = 'PAGO';
                $exp->update();
            }
        }
    }

    public static function list(){
        return DB::table('pagos')
            ->join('users', 'users.id', '=', 'pagos.usuario_id')
            ->join('facturas', 'pagos.factura_id', '=', 'facturas.id')
            ->join('consorcios', 'facturas.consorcio_id', '=', 'consorcios.id')
            ->addSelect([
                'pagos.id as id',
                'users.id as usuario_id',
                'users.name as usuario_nombre',
                'pagos.factura_id as factura_id',
                'pagos.fecha as fecha',
                'pagos.monto as monto',
                'pagos.estado as estado',
                'pagos.medio_de_pago as medio_de_pago',
                'pagos.codigo_comprobante as codigo_comprobante'
            ])
            ->orderByDesc('pagos.fecha');
    }

    public static function filterByConsorcio($consorcioId){
        return Pago::list()
            ->where('consorcios.id', $consorcioId);
    }

    public static function filterByMesAnio($mes, $anio){
        return Pago::list()
            ->where('facturas.mes', $mes)
            ->where('facturas.anio', $anio);
    }

    public static function filterByUsuario($userId){
        return Pago::list()
            ->where('users.id', $userId);
    }

    public static function obtenerPagosPorUsuarioYFactura($usuario_id, $factura_id){
        return Pago::list()
            ->where('usuario_id', $usuario_id)
            ->where('factura_id', $factura_id);
    }

    public static function obtenerPagosPorUsuario($usuario_id){
        return Pago::list()
            ->where('usuario_id', $usuario_id);
    }

    public static function elCodigoYaFueRegistrado($codigo){
        $pagos = Pago::list()
            ->where('pagos.codigo_comprobante', $codigo)
            ->get();

        return sizeof($pagos);
    }
}
