<?php

use \App\Factura;
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

	    $faker = Factory::create();

	    for ($i = 0; $i < 10; $i++) {
		    Factura::create([
			    'nombre' => "Consorcio_$i",
			    'direccion' => $faker->streetAddress,
			    'localidad' => "Localidad_$i",
			    'provincia' => 'Buenos Aires',
			    'telefono' => $faker->phoneNumber
		    ]);
	    }

	    for ($i = 10; $i < 20; $i++) {
		    Factura::create([
			    'nombre' => "Consorcio_$i",
			    'direccion' => $faker->streetAddress,
			    'localidad' => "Localidad_$i",
			    'provincia' => 'Córdoba',
			    'telefono' => $faker->phoneNumber
		    ]);
	    }
    }
}
