<?php

use Illuminate\Database\Seeder;
use App\Proveedor;
use \Faker\Factory;

class ProveedorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        $telefonosPrefijo = array('4653', '4656', '4657', '4488', '5237');

        for ($i = 0; $i < 5; $i++) {
            Proveedor::create([
                'nombre'    => $faker->name,
                'tel' => $faker->randomElement($telefonosPrefijo).'-'.$faker->randomNumber(4, true),
                'email' => $faker->email,
                'rubro' => 'Electricidad'
            ]);
        }
        for ($i = 0; $i < 5; $i++) {
            Proveedor::create([
                'nombre'    => $faker->name,
                'tel' => $faker->randomElement($telefonosPrefijo).'-'.$faker->randomNumber(4, true),
                'email' => $faker->email,
                'rubro' => 'Limpieza'
            ]);
        }
        for ($i = 0; $i < 5; $i++) {
            Proveedor::create([
                'nombre'    => $faker->name,
                'tel' => $faker->randomElement($telefonosPrefijo).'-'.$faker->randomNumber(4, true),
                'email' => $faker->email,
                'rubro' => 'Construccion'
            ]);
        }
    }
}
