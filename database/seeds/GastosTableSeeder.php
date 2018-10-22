<?php

use App\Gasto;
use App\Proveedor;
use Faker\Factory;
use Illuminate\Database\Seeder;

class GastosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        $consorcios = \App\Consorcio::all();
        $cantidadDeProveedores = sizeof(\App\Proveedor::all());

        $mesDesdeElQueSeCreanGastos = 5;
        $mesHastaElQueSeCreanGastos = 10;
        $cantidadDeGastosMensuales = 10;

        for($j = $mesDesdeElQueSeCreanGastos; $j <= $mesHastaElQueSeCreanGastos; $j++){

            $mes = ($j > 10) ? '0'.$j : $j;

            foreach ($consorcios as $consorcio){

                for ($i = 0; $i < $cantidadDeGastosMensuales; $i++) {
                    $proveedorId = $faker->numberBetween(2, $cantidadDeProveedores);
                    $proveedor = Proveedor::find($proveedorId);
                    $dia = $faker->numberBetween(1, 22);

                    Gasto::create([
                        'nombre' => $proveedor->rubro,
                        'valor' => $faker->numberBetween(500, 3000),
                        'mes' => $mes,
                        'anio' => 2018,
                        'fecha' => "2018-$mes-$dia",
                        'es_gasto_fijo' => 0,
                        'proveedor_id' => $proveedorId,
                        'consorcio_id' => $consorcio->id
                    ]);
                }
            }
        }
    }
}
