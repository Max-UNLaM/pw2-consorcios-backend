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
        $mesDeInicio = 5;
        $mesFinal = 7;
        $anio = '2018';
        $consorcios = Consorcio::all();

        foreach ($consorcios as $consorcio){
            $propietarios = Consorcio::obtenerPropietarios($consorcio->id);

            for($mes = $mesDeInicio; $mes <= $mesFinal; $mes++){
                foreach ($propietarios as $propietario){
                    $idUnidades = Unidad::obtenerIdDeUnidadesPorUsuarioYConsorcio($propietario->id, $consorcio->id);

                    $total = Expensa::obtenerImporteMensualPorMesAnioUnidades($mes, $anio, $idUnidades);

                    Factura::create([
                        'consorcio_id' => $consorcio->id,
                        'usuario_id' => $propietario->id,
                        'mes' => $mes,
                        'anio' => $anio,
                        'emision' => "$anio-$mes-10",
                        'vencimiento' => "$anio-$mes-20",
                        'total' => $total,
                        'pago_parcial' => 0,
                        'adeuda' => $total
                    ]);
                }
            }
        }
    }
}
