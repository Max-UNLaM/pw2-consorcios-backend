<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Expensa extends Model
{
    protected $fillable = ['unidad_id', 'año', 'mes', 'estado', 'emision', 'vencimiento', 'importe'];

    public static function userGetAllUsersExpensas(string $userId)
    {
        return DB::table('expensas')
            ->join('unidads', 'expensas.unidad_id', '=', 'expensas.id')
            ->join('users', 'users.id', '=', 'unidads.usuario_id')
            ->select('expensas.*', 'unidads.nombre as unidad_nombre')
            ->where('users.id', '=', $userId);
    }

    public static function crearExpensaConImporte(Expensa $expensaNueva)
    {
        $gasto = new Gasto();
        $expensaNueva['importe'] = ($gasto->importeGastosMensualConsorcio($expensaNueva['año'], $expensaNueva['mes'], $expensaNueva['unidad_id']) * 1.2) * Unidad::calcularCoeficiente($expensaNueva['unidad_id']);

        if($expensaNueva['importe']){
            return $expensa = Expensa::create([
                    'unidad_id' => $expensaNueva->unidad_id,
                    'año' => $expensaNueva->año,
                    'mes' => $expensaNueva->mes,
                    'estado' => $expensaNueva->estado,
                    'emision' => $expensaNueva->emision,
                    'vencimiento' => $expensaNueva->vencimiento,
                    'importe' => $expensaNueva->importe
                ]);
        }
    }

    public static function listByUnidad($unidadId)
    {
        return DB::table('expensas')
            ->where('unidad_id', $unidadId)
            ->get();
    }

    public static function obtenerExpensaPorUnidadMesAnio(int $unidad_id, string $mes, string $año){
        return DB::table('expensas')
            ->where('unidad_id', $unidad_id)
            ->where('mes', $mes)
            ->where('año', $año)
            ->get();
    }

    /*public static function obtenerExpensaPorUsuarioMesAnio($usuario_id, $mes, $anio){
        return DB::table('expensas')
            ->where('unidad_id', $usuario_id)
            ->where('mes', $mes)
            ->where('año', $anio)
            ->get();
    }*/

    public static function obtenerExpensasPorMesAnioUnidades($mes, $anio, $idUnidades){
        $mes = (strlen($mes) == 1) ? '0'.$mes : $mes;

        return DB::table('expensas')
            ->where('mes', $mes)
            ->where('año', $anio)
            ->whereIn('unidad_id', $idUnidades)
            ->get();
    }

    public static function obtenerImporteMensualPorMesAnioUnidades($mes, $anio, $idUnidades){
        $mes = (strlen($mes) == 1) ? '0'.$mes : $mes;

        return DB::table('expensas')
            ->where('mes', $mes)
            ->where('año', $anio)
            ->whereIn('unidad_id', $idUnidades)
            ->get()
            ->sum('importe');
    }
}
