<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ConsorciosTableSeeder::class);
        # $this->call(FacturasTableSeeder::class);
        $this->call(UnidadesTableSeeder::class);
        $this->call(ProveedorsTableSeeder::class);
        $this->call(RolsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(GastosTableSeeder::class);
    }
}
