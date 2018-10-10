<?php

namespace App;

use Faker\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Gasto extends Model
{
    protected $fillable = ['nombre', 'valor', 'mes', 'anio', 'fecha', 'es_gasto_fijo', 'proveedor_id', 'consorcio_id'];

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
                'gastos.consorcio_id as consorcio_id',
                'consorcios.nombre as consorcio_nombre',
                'gastos.proveedor_id as proveedor_id',
                'proveedors.nombre as proveedor_nombre',
                'gastos.valor as valor',
                'gastos.mes as mes',
                'gastos.anio as anio',
                'gastos.fecha as fecha'
            ])
            ->orderByDesc('gastos.fecha');

    }

    public static function filterByConsorcio($consorcioId){
        return Gasto::list()->where('gastos.consorcio_id', $consorcioId);
    }

    public static function filterByMesAnioConsorcio($mes, $anio, $consorcioId){
        return Gasto::list()
            ->where('gastos.mes', $mes)
            ->where('gastos.anio', $anio)
            ->where('gastos.consorcio_id', $consorcioId);
    }

    public static function gastosMesAnioConsorcio($mes, $anio, $consorcioId){
        return DB::table('gastos')
            ->where('mes', $mes)
            ->where('anio', $anio)
            ->where('consorcio_id', $consorcioId)
            ->sum('valor');
    }

    public static function generarGastosFijosMensuales($mes, $anio, $consorcioId){
        $proveedor = Proveedor::find(1);
        $faker = Factory::create();
        $mesString = $mes < 10 ? '0'.$mes : $mes;
        $dia = 10;

        Gasto::create([
            'nombre' => $proveedor->rubro,
            'valor' => 1200,
            'mes' => $mes,
            'anio' => $anio,
            'fecha' => "$anio-$mesString-$dia",
            'es_gasto_fijo' => 1,
            'proveedor_id' => $proveedor->id,
            'consorcio_id' => $consorcioId
        ]);
    }

    public static function agregarInformacion($gasto){
        $gasto->consorcio = Consorcio::find($gasto->consorcio_id);
        $gasto->proveedor = Proveedor::find($gasto->proveedor_id);
        return $gasto;
    }
   
}