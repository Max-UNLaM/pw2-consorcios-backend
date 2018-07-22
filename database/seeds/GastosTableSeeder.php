<?php

use App\Gasto;
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

        $mesHastaElQueSeCreanGastos = 7;
        $cantidadDeGastosMensuales = 5;

        for($j = 1; $j <= $mesHastaElQueSeCreanGastos; $j++){

            $mes = ($j > 10) ? '0'.$j : $j;

            foreach ($consorcios as $consorcio){

                for ($i = 0; $i < $cantidadDeGastosMensuales; $i++) {
                    Gasto::create([
                        'nombre' => $faker->randomElement(array('Gastos generales', 'Gastos generales', 'Gastos generales', 'Pintura', 'Plomería', 'Electricista', 'Gasista')),
                        'valor' => $faker->numberBetween(0,3000),
                        'mes' => 2018,
                        'anio' => $mes,
                        'fecha' => "2018-$mes-10",
                        'proveedor_id' => $faker->numberBetween(1, $cantidadDeProveedores),
                        'consorcio_id' => $consorcio->id
                    ]);
                }
            }
        }
    }
}
