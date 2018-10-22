<?php

use Illuminate\Database\Seeder;
use App\Consorcio;
use App\Gasto;
use App\Liquidacion;

class LiquidacionesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $coeficiente = 1.2;

        $mesDesdeElQueSeVaALiquidar = 6;
        $mesHastaElQueSeVaALiquidar = 8;
        $anio = 2018;

        for($mes = $mesDesdeElQueSeVaALiquidar; $mes <= $mesHastaElQueSeVaALiquidar; $mes++){
            $consorcios = Consorcio::all();

            foreach ($consorcios as $consorcio){
                Liquidacion::liquidarMesAnioConsorcio($mes, $anio, $consorcio->id);
            }
        }
    }
}
