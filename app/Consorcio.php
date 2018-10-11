<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Consorcio extends Model
{
    protected $fillable = ['nombre', 'direccion', 'cuit', 'codigo_postal', 'localidad', 'provincia', 'telefono', 'email', 'cantidad_de_pisos', 'departamentos_por_piso'];

    public static function obtenerPropietarios($consorcio_id)
    {
        $propietarios = Unidad::obtenerPropietariosPorIdConsorcio($consorcio_id);

        return $propietarios;
    }

    public static function getAllConsorciosOfUser($userId)
    {
        return DB::table('consorcios')
            ->join('unidads', 'unidads.consorcio_id', '=', 'consorcios.id')
            ->join('users', 'users.id', '=', 'unidads.usuario_id')
            ->addSelect([
                'consorcios.id as id',
                'consorcios.nombre as nombre',
                'consorcios.direccion as direccion',
                'consorcios.localidad as localidad',
                'consorcios.provincia as provincia',
                'consorcios.telefono as telefono',
                'consorcios.cuit as cuit'
            ])
            ->groupBy('consorcios.id')
            ->where('usuario_id', $userId);
    }

    public static function cantidadTotalDeUnidades(){
        $cantidadDeUnidades = 0;
        $consorcios = Consorcio::all();

        foreach ($consorcios as $consorcio){
            $cantidadDeUnidades += $consorcio->cantidad_de_pisos * $consorcio->departamentos_por_piso;
        }

        return $cantidadDeUnidades;
    }

    public static function obtenerConsorcioIdDesdePagoId($pagoId){
        $pago = Pago::find($pagoId);
        $factura = Factura::find($pago->factura_id);
        return $factura->consorcio_id;
    }
}
