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
        $mesDesdeElQueSeCreanExpensas = 5;
        $mesHastaElQueSeCreanExpensas = 7;

        $consorcios = Consorcio::all();

        for($i = $mesDesdeElQueSeCreanExpensas; $i <= $mesHastaElQueSeCreanExpensas; $i++){
            foreach($consorcios as $consorcio){
                $gastosMensualesConsorcio = Gasto::importeGastosMensualConsorcio($anio, $i, $consorcio->id);
                $unidadesDelConsorcio = Unidad::obtenerIdUnidadesPorIdConsorcio($consorcio->id);

                foreach($unidadesDelConsorcio as $unidad){
                    $coeficienteDeLaUnidad = Unidad::calcularCoeficiente($consorcio->id);
                    $coeficienteGanancia = 1.2;
                    $importe = $gastosMensualesConsorcio * $coeficienteGanancia * $coeficienteDeLaUnidad;

                    Expensa::create([
                        'unidad_id' => $unidad->id,
                        'año' => $anio,
                        'mes' => (strlen($i) < 10) ? '0'.$i : $i,
                        'pago' => 0,
                        'emision' => "$anio-$i-10",
                        'vencimiento' => "$anio-$i-20",
                        'importe' => $importe
                    ]);
                }
            }
        }
    }
}
