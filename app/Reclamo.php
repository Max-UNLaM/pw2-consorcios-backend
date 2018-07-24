<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Reclamo extends Model
{
    protected $fillable = ['usuario_id', 'unidad_id', 'estado_de_reclamo_id', 'motivo', 'fecha_reclamo'];

    public static function getIdAllReclamosOfUser(int $userId) {
        return DB::table('reclamos')
            ->where('usuario_id', $userId)
            ->get(['id']);
    }

    public static function getAllReclamosOfUser(int $userId) {
        return DB::table('reclamos')
            ->where('usuario_id', $userId);
    }

    public static function obtenerReclamosPorConsorcio($consorcioId){
        $unidadesDelConsorcio = Unidad::getUnidadsIdByConsorcioId($consorcioId);

        return DB::table('reclamos')
            ->whereIn('unidad_id', $unidadesDelConsorcio)
            ->get();
    }

    public static function obtenerReclamosPorConsorcioMesAnio($consorcioId, $mes, $anio){
        $mes = ($mes < 10) ? '0'.$mes : $mes;


        return Reclamo::obtenerReclamosPorConsorcio($consorcioId)
            ->where('fecha_reclamo', ">", "$anio-$mes-01")
            ->where('fecha_reclamo', "<", "$anio-$mes-31");
    }
}
