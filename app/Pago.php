<?php

namespace App;

use Faker\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pago extends Model
{
    protected $fillable = [
        'usuario_id', 'factura_id', 'fecha', 'monto'
    ];

    public static function pagoParcial($factura_id, $monto, $fecha){
        $factura = Factura::find($factura_id);

        if($factura->adeuda == 0) return response(['Esta factura ya esta paga'], 400);
        if($monto > $factura->adeuda) return response(['El monto no puede ser mayor lo que se adeuda de la factura'], 400);

        $factura->pago_parcial+=$monto;
        $factura->adeuda-=$monto;
        $factura->update();

        if($factura->adeuda == 0){
            $unidades = Unidad::obtenerIdDeUnidadesPorUsuarioYConsorcio($factura->usuario_id, $factura->consorcio_id);
            $expensas = Expensa::obtenerExpensasPorMesAnioUnidades($factura->mes, $factura->anio, $unidades);

            foreach ($expensas as $expensa){
                $exp = Expensa::find($expensa->id);
                $exp->pago = 1;
                $exp->update();
            }
        }

        $resp = Pago::create([
            'usuario_id' => $factura->usuario_id,
            'factura_id' => $factura_id,
            'monto' => $monto,
            'fecha' => $fecha
        ]);

        return $resp;
    }

    public static function list(){
        return DB::table('pagos')
            ->join('users', 'users.id', '=', 'pagos.usuario_id')
            ->addSelect([
                'pagos.id as id',
                'users.id as usuario_id',
                'users.name as usuario_nombre',
                'pagos.factura_id as factura_id',
                'pagos.fecha as fecha',
                'pagos.monto as monto'
            ]);
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
}
