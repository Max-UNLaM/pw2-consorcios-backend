<?php

use App\Consorcio;
use App\Expensa;
use App\Gasto;
use App\Unidad;
use Illuminate\Database\Seeder;

class ExpensasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $anio = (string) 2018;
        $mesDesdeElQueSeCreanExpensas = 7;
        $mesHastaElQueSeCreanExpensas = 10;

        $consorcios = Consorcio::all();

        for($i = $mesDesdeElQueSeCreanExpensas; $i <= $mesHastaElQueSeCreanExpensas; $i++){
            foreach($consorcios as $consorcio){

                Expensa::generarExpensasDelMes($anio, $i, $consorcio->id);

            }
        }
    }
}
