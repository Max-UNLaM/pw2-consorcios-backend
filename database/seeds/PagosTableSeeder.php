<?php

use App\Factura;
use App\Pago;
use Faker\Factory;
use Illuminate\Database\Seeder;

class PagosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        $facturas = Factura::all();

        $arrayRandom = array(0, 0, 1);

        $mesAnterior = '09';

        foreach ($facturas as $factura){
            $mes = ($factura->mes < 10) ? '0'.$factura->mes : $factura->mes;
            $dia = $faker->numberBetween(11, 28);

            $pagoTotal = $faker->randomElement($arrayRandom);

            if($pagoTotal == 1 || $mes != $mesAnterior){
                Pago::realizarPago($factura->id, $factura->adeuda, "2018-$mes-$dia");
            } else {
                $pagoParcial = $faker->randomElement($arrayRandom);
                if($pagoParcial == 1){
                    $montoAPagar = $faker->numberBetween(($factura->adeuda / 10), $factura->adeuda);
                    Pago::realizarPago($factura->id, $montoAPagar, "2018-$mes-$dia");
                }
            }

        }
    }
}
