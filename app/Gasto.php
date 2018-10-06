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

    public static function importeGastosMensualConsorcio(string $anio, string $mes, int $consorcioId)
    {
        Gasto::gastosMensualesPorConsorcio($anio, $mes, $consorcioId)
            ->sum('valor');
    }

    public static function list(){

        return DB::table('gastos')
            ->join('proveedors', 'proveedors.id', '=', 'gastos.proveedor_id')
            ->join('consorcios', 'consorcios.id', '=', 'gastos.consorcio_id')
            ->addSelect([
                'gastos.id as id',
                'gastos.nombre as nombre',
                'consorcios.nombre as consorcio_nombre',
                'proveedors.nombre as proveedor_nombre',
                'gastos.valor as valor',
                'gastos.mes as mes',
                'gastos.anio as anio',
                'gastos.fecha as fecha',
                'gastos.proveedor_id as proveedor_id',
                'gastos.consorcio_id as consorcio_id'
            ]);

    }

    public static function filterByConsorcio($consorcioId){
        return Gasto::list()->where('gastos.consorcio_id', $consorcioId);
    }

    public static function gastosMesAnioConsorcio($mes, $anio, $consorcioId){
        return DB::table('gastos')
            ->where('mes', $mes)
            ->where('anio', $anio)
            ->where('consorcio_id', $consorcioId)
            ->sum('valor');
    }
   
}