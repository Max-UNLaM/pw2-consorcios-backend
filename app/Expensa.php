<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Expensa extends Model
{
    protected $fillable = ['unidad_id', 'anio', 'mes', 'pago', 'emision', 'vencimiento', 'importe'];

    public static function userGetAllUsersExpensas(string $userId)
    {
        return DB::table('expensas')
            ->join('unidads', 'expensas.unidad_id', '=', 'expensas.id')
            ->join('users', 'users.id', '=', 'unidads.usuario_id')
            ->select('expensas.*', 'unidads.nombre as unidad_nombre')
            ->where('users.id', '=', $userId);
    }

    public static function expensasPorUsuario($usuario_id){
        return DB::table('expensas')
            ->join('unidads', 'unidads.id', '=', 'expensas.unidad_id')
            ->join('consorcios', 'consorcios.id', '=', 'unidads.consorcio_id')
            ->addSelect([
                'expensas.id as id',
                'consorcios.nombre as consorcio_nombre',
                'unidads.nombre as unidad_nombre',
                'unidads.id as unidad_id',
                'expensas.anio as anio',
                'expensas.mes as mes',
                'expensas.pago as estado',
                'expensas.vencimiento as vencimiento',
                'expensas.emision as emision',
                'expensas.importe as importe'
            ])
            ->where('unidads.usuario_id', '=', $usuario_id);
        $unidadesDelUsuario = Unidad::getAllUnidadIdOfUser($usuario_id);

        return DB::table('expensas')
            ->whereIn('unidad_id', $unidadesDelUsuario);
    }

    public static function crearExpensaConImporte(Expensa $expensaNueva)
    {
        $gasto = new Gasto();
        $expensaNueva['importe'] = ($gasto->importeGastosMensualConsorcio($expensaNueva['anio'], $expensaNueva['mes'], $expensaNueva['unidad_id']) * 1.2) * Unidad::calcularCoeficiente($expensaNueva['unidad_id']);

        if($expensaNueva['importe']){
            return $expensa = Expensa::create([
                    'unidad_id' => $expensaNueva->unidad_id,
                    'anio' => $expensaNueva->anio,
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
                'expensas.anio as anio',
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

    public static function obtenerExpensaPorUnidadMesAnio(int $unidad_id, string $mes, string $anio){
        return DB::table('expensas')
            ->where('unidad_id', $unidad_id)
            ->where('mes', $mes)
            ->where('anio', $anio)
            ->get();
    }

    public static function obtenerExpensasPorMesAnioUnidades($mes, $anio, $idUnidades){
        $mes = (strlen($mes) == 1) ? '0'.$mes : $mes;

        return DB::table('expensas')
            ->where('mes', $mes)
            ->where('anio', $anio)
            ->whereIn('unidad_id', $idUnidades)
            ->get();
    }

    public static function obtenerImporteMensualPorMesAnioUnidades($mes, $anio, $idUnidades){
        $mes = (strlen($mes) == 1) ? '0'.$mes : $mes;

        return DB::table('expensas')
            ->where('mes', $mes)
            ->where('anio', $anio)
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

    public static function generarExpensasDelMes($anio, $mes, $consorcioId){
        $gastosMensualesConsorcio = Gasto::importeGastosMensualConsorcio($anio, $mes, $consorcioId);

        $unidadesDelConsorcio = Unidad::obtenerIdUnidadesPorIdConsorcio($consorcioId);
        foreach($unidadesDelConsorcio as $unidad){
            $coeficienteDeLaUnidad = Unidad::calcularCoeficiente($consorcioId);
            $coeficienteGanancia = 1.2;
            $importe = $gastosMensualesConsorcio * $coeficienteGanancia * $coeficienteDeLaUnidad;

            if($importe != 0){
                Expensa::create([
                    'unidad_id' => $unidad->id,
                    'anio' => $anio,
                    'mes' => (strlen($mes) < 10) ? '0'.$mes : $mes,
                    'pago' => 'IMPAGO',
                    'emision' => "$anio-$mes-10",
                    'vencimiento' => "$anio-$mes-20",
                    'importe' => $importe
                ]);
            }
        }
    }

    public static function cantiadadDeExpensasEnElPeriodo($consorcioId, $mes, $anio){
        $mes = (strlen($mes) == 1) ? '0'.$mes : $mes;

        $expensas = DB::table('expensas')
            ->join('unidads', 'unidads.id', '=', 'expensas.unidad_id')
            ->join('consorcios', 'consorcios.id', '=', 'unidads.consorcio_id')
            ->where('mes', $mes)
            ->where('anio', $anio)
            ->where('consorcios.id', $consorcioId)
            ->get();

        return sizeof($expensas);
    }
}
