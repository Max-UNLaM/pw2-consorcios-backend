<?php

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
        $anio = 2018;
        $mesHastaElQueSeCreanExpensasPagas = 6;

        $consorcios = \App\Consorcio::all();
        $gasto = new \App\Gasto();
        $expensa = new Expensa();

        for($i = 1; $i <= $mesHastaElQueSeCreanExpensasPagas; $i++){
            foreach($consorcios as $consorcio){
                $unidadesDelConsorcio = \App\Unidad::obtenerIdUnidadesPorIdConsorcio($consorcio->id);

                foreach($unidadesDelConsorcio as $unidad){
                    $mes = ($i < 10) ? '0'.$i : $i;

                    Expensa::create([
                        'unidad_id' => $unidad->id,
                        'año' => $anio,
                        'mes' => $mes,
                        'estado' => 'pago',
                        'emision' => "$anio-$mes-10",
                        'vencimiento' => "$anio-$mes-20",
                        'importe' => (($gasto->importeGastosMensualConsorcio($anio, $mes, $consorcio->id)) * 1.2) * \App\Unidad::calcularCoeficiente($consorcio->id)
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
                    'estado' => 'impago',
                    'emision' => "$anio-$mesEnElQueSeCreanExpensasImpagas-10",
                    'vencimiento' => "$anio-$mesEnElQueSeCreanExpensasImpagas-20",
                    'importe' => (($gasto->importeGastosMensualConsorcio($anio, $mesEnElQueSeCreanExpensasImpagas, $consorcio->id)) * 1.2) * \App\Unidad::calcularCoeficiente($consorcio->id)
                ]);
            }
        }
    }
}
