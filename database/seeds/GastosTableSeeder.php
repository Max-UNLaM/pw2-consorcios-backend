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
        $meses = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
        $cantidadDeProveedores = sizeof(\App\Proveedor::all());
        $cantidadDeGastosMensuales = 25;

        foreach ($consorcios as $consorcio){
            foreach ($meses as $mes){
                for ($i = 0; $i < $cantidadDeGastosMensuales; $i++) {
                    Gasto::create([
                        'nombre' => $faker->name,
                        'valor' => $faker->numberBetween(0,5000),
                        'fecha' => "2018-'.$mes.'-10",
                        'proveedor_id' => $faker->numberBetween(1, $cantidadDeProveedores),
                        'consorcio_id' => $consorcio->id
                    ]);
                }
            }
        }
    }
}
