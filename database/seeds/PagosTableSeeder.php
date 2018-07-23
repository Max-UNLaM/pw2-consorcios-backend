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

        foreach ($facturas as $factura){

            $mes = ($factura->mes < 10) ? '0'.$factura->mes : $factura->mes;
            $dia = $faker->numberBetween(11, 28);

            Pago::pagoParcial($factura->id, $factura->adeuda, "2018-$mes-$dia");
        }
    }
}
