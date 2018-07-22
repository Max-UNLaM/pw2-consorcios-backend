<?php

use App\EstadoDeReclamo;
use Illuminate\Database\Seeder;

class EstadoDeReclamosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EstadoDeReclamo::create([
            'id' => '1',
            'detalle' => 'Resuelto'
        ]);

        EstadoDeReclamo::create([
            'id' => '2',
            'detalle' => 'No Resuelto'
        ]);

        EstadoDeReclamo::create([
            'id' => '3',
            'detalle' => 'Rechazado'
        ]);

        EstadoDeReclamo::create([
            'id' => '4',
            'detalle' => 'Esperando confirmacion'
        ]);
    }
}
