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
        for ($i = 0; $i < 5; $i++) {
            Proveedor::create([
                'nombre'    => $faker->name,
                'tel' => $faker->phoneNumber,
                'email' => $faker->email,
                'rubro' => 'electricidad'
            ]);
        }
        for ($i = 0; $i < 5; $i++) {
            Proveedor::create([
                'nombre'    => $faker->name,
                'tel' => $faker->phoneNumber,
                'email' => $faker->email,
                'rubro' => 'limpieza'
            ]);
        }
        for ($i = 0; $i < 5; $i++) {
            Proveedor::create([
                'nombre'    => $faker->name,
                'tel' => $faker->phoneNumber,
                'email' => $faker->email,
                'rubro' => 'construccion'
            ]);
        }
    }
}
