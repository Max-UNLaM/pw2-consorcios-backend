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
            ->select([
                'gastos.id as gasto_id',
                'gastos.nombre as gasto_nombre',
                'gastos.valor as gasto_valor',
                'gastos.mes as gasto-mes',
                'gastos.anio as gasto_anio',
                'gastos.fecha as gasto_fecha',
                'gastos.es_gasto_fijo as gasto.es_gasto_fijo',
                'proveedors.id as proveedor_id',
                'proveedors.nombre as proveedor_nombre',
                'proveedors.email as proveedor_email',
                'proveedors.tel as proveedor_tel',
                'proveedors.rubro as proveedor_rubro',
                'consorcios.id as consorcio_id',
                'consorcios.nombre as consorcio_nombre',
                'consorcios.direccion as consorcio_direccion',
                'consorcios.localidad as consorcio_localidad',
                'consorcios.provincia as consorcio_provincia',
                'consorcios.telefono as consorcio_telefono',
                'consorcios.email as consorcio_email',
                'consorcios.codigo_postal as consorcio_codigo_postal',
                'consorcios.cuit as consorcio_cuit',
                'consorcios.cantidad_de_pisos as consorcio_cantidad_de_pisos',
                'consorcios.departamentos_por_piso as consorcio_departamentos_por_piso'
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
   
}