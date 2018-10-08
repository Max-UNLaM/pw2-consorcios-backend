<?php

use App\Factura;
use App\Pago;
use App\User;
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

        $user = User::find(1);

        $mediosDePago = array(
            'Efectivo',
            'Deposito',
            'Transferencia',
            'RapiPago',
            'PagoFacil'
        );

        foreach ($facturas as $factura){
            $mes = ($factura->mes < 10) ? '0'.$factura->mes : $factura->mes;
            $dia = $faker->numberBetween(11, 28);

            $pagoTotal = $faker->randomElement($arrayRandom);
            $medioDePago = $faker->randomElement($mediosDePago);

            if($pagoTotal == 1 || $mes != $mesAnterior){
                Pago::realizarPago($factura->id, $factura->adeuda, "2018-$mes-$dia", $user, $medioDePago, null);
            } else {
                $pagoParcial = $faker->randomElement($arrayRandom);
                if($pagoParcial == 1){
                    $montoAPagar = $faker->numberBetween(($factura->adeuda / 10), $factura->adeuda);
                    Pago::realizarPago($factura->id, $montoAPagar, "2018-$mes-$dia", $user, $medioDePago, null);
                }
            }

        }
    }
}
