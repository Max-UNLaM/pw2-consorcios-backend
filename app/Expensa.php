<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Expensa extends Model
{
    protected $fillable = ['unidad_id', 'año', 'mes', 'pago', 'emision', 'vencimiento', 'importe'];

    public static function userGetAllUsersExpensas(string $userId)
    {
        return DB::table('expensas')
            ->join('unidads', 'expensas.unidad_id', '=', 'expensas.id')
            ->join('users', 'users.id', '=', 'unidads.usuario_id')
            ->select('expensas.*', 'unidads.nombre as unidad_nombre')
            ->where('users.id', '=', $userId);
    }

    public static function expensasPorUsuario($usuario_id){
        $unidadesDelUsuario = Unidad::getAllUnidadIdOfUser($usuario_id);

        return DB::table('expensas')
            ->whereIn('unidad_id', $unidadesDelUsuario);
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

    public static function list(){
        return DB::table('expensas')
            ->join('unidads', 'unidads.id', '=', 'expensas.unidad_id')
            ->join('consorcios', 'consorcios.id', '=', 'unidads.consorcio_id')
            ->addSelect([
                'expensas.id as id',
                'expensas.unidad_id as unidad_id',
                'consorcios.nombre as nombre_consorcio',
                'unidads.nombre as unidad_nombre',
                'expensas.mes as mes',
                'expensas.año as anio',
                'expensas.emision as emision',
                'expensas.vencimiento as vencimiento',
                'expensas.importe as importe',
                'expensas.pago as pago'
            ]);
    }

    public static function listByUnidad($unidadId)
    {
        return Expensa::list()->where('unidad_id', $unidadId);
    }

    public static function obtenerExpensaPorUnidadMesAnio(int $unidad_id, string $mes, string $año){
        return DB::table('expensas')
            ->where('unidad_id', $unidad_id)
            ->where('mes', $mes)
            ->where('año', $año)
            ->get();
    }

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

    public static function obtenerExpensasPagas($size){

        return DB::table('expensas')
            ->where('pago', 1)
            ->paginate($size);
    }

    public static function obtenerExpensasImpagas($size){

        return DB::table('expensas')
            ->where('pago', 0)
            ->paginate($size);
    }
}
