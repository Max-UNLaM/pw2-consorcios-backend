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
        $mesHastaElQueSeCreanExpensasPagas = 6;

        $consorcios = Consorcio::all();
        $gasto = new Gasto();
        $expensa = new Expensa();

        for($i = 1; $i <= $mesHastaElQueSeCreanExpensasPagas; $i++){
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
                        'estado' => "PAGO",
                        'emision' => "$anio-$i-10",
                        'vencimiento' => "$anio-$i-20",
                        'importe' => $importe
                    ]);
                }
            }
        }

        $mesEnElQueSeCreanExpensasImpagas = $mesHastaElQueSeCreanExpensasPagas + 1;

        $mesEnElQueSeCreanExpensasImpagas = ($mesEnElQueSeCreanExpensasImpagas <10) ? '0'.$mesEnElQueSeCreanExpensasImpagas : $mesEnElQueSeCreanExpensasImpagas ;

        foreach ($consorcios as $consorcio){
            $unidadesDelConsorcio = \App\Unidad::obtenerIdUnidadesPorIdConsorcio($consorcio->id);

            foreach($unidadesDelConsorcio as $unidad){
                $mes = ($i < 10) ? '0'.$i : $i;

                Expensa::create([
                    'unidad_id' => $unidad->id,
                    'año' => $anio,
                    'mes' => $mesEnElQueSeCreanExpensasImpagas,
                    'estado' => 'IMPAGO',
                    'emision' => "$anio-$mesEnElQueSeCreanExpensasImpagas-10",
                    'vencimiento' => "$anio-$mesEnElQueSeCreanExpensasImpagas-20",
                    'importe' => (($gasto->importeGastosMensualConsorcio($anio, $mesEnElQueSeCreanExpensasImpagas, $consorcio->id)) * 1.2) * \App\Unidad::calcularCoeficiente($consorcio->id)
                ]);
            }
        }
    }
}
