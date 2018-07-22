<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Gasto extends Model
{
    protected $fillable = ['nombre', 'valor', 'mes', 'anio', 'fecha', 'proveedor_id', 'consorcio_id'];

    protected function gastosMensual(string $anio, string $mes)
    {
        return DB::table('gastos')
            ->where('fecha', 'like', $anio.'-'.$mes.'%');
    }

    public function importeGastosMensual(string $anio, string $mes)
    {
        return $this->gastosMensual($anio, $mes)
            ->sum('valor');
    }


    public function gastosPorConsorcio(string $anio, string $mes, int $consorcioId)
    {
        return $this->gastosMensual($anio, $mes)
            ->where('consorcio_id', $consorcioId);
    }

    public static function gastosMensualesPorConsorcio(string $anio, string $mes, int $consorcioId)
    {
        $mes = (strlen($mes) == 1) ? '0'.$mes : $mes;

        return Gasto::all()
            ->where('fecha', '>=', "$anio-$mes-01")
            ->where('fecha', '<=', "$anio-$mes-31")
            ->where('consorcio_id', $consorcioId);
    }

    public static function importeGastosMensualConsorcio($anio, $mes, $consorcioId) {
        $mes = (strlen($mes) == 1) ? '0'.$mes : $mes;

        return Gasto::all()
            ->where('mes', '>=', "$anio-$mes-01")
            ->where('anio', '<=', "$anio-$mes-31")
            ->where('consorcio_id', $consorcioId)
            ->sum('valor');

    }

}
