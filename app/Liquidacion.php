<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Liquidacion extends Model
{
    protected $fillable = ['mes', 'anio', 'consorcio_id', 'valor'];

    public static function obtenerTotalPorMesAnioConsorcio($mes, $anio, $consorcioId){
        return DB::table('liquidacions')
            ->where('mes', $mes)
            ->where('anio', $anio)
            ->where('consorcio_id', $consorcioId)
            ->sum('valor');
    }
}
