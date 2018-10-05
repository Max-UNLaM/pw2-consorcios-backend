<?php

use App\Consorcio;
use App\Expensa;
use \App\Factura;
use App\Unidad;
use \Faker\Factory;
use Illuminate\Database\Seeder;

class FacturasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mesDeInicio = 6;
        $mesFinal = 10;
        $anio = '2018';
        $consorcios = Consorcio::all();

        for($mes = $mesDeInicio; $mes <= $mesFinal; $mes++){
            foreach ($consorcios as $consorcio){

                Factura::facturarPeriodo($consorcio->id, $mes, $anio);

            }
        }
    }
}
