<?php

use Illuminate\Database\Seeder;

class DeudasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $facturas = \App\Factura::all();

        $arrayRandom = array(0, 0, 0, 1);

        foreach ($facturas as $factura){
            $tieneDeuda = $faker->randomElement($arrayRandom);

            if($tieneDeuda){
                $informes = \App\Informe::filterByMesAnioConsorcio($factura->mes, $factura->anio, $factura->consorcio_id)->get();
                foreach ($informes as $item){
                    $informe = $item;
                }
                $adeuda = $faker->numberBetween($factura->total/10, $factura->total);
                $pagoParcial = $factura->total - $adeuda;

                \App\Deuda::create([
                    'informe_id' => $informe->id,
                    'mes' => $factura->mes,
                    'anio' => $factura->anio,
                    'usuario_id' => $factura->usuario_id,
                    'total_factura' => $factura->total,
                    'pago_parcial' => $pagoParcial,
                    'adeuda' => $adeuda,
                    'vencimiento' => $factura->vencimiento
                ]);
            }
        }
    }
}
