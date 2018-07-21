<?php

use Illuminate\Database\Seeder;
use App\Rol;

class RolsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Roles pre definidos
        // Admin
        Rol::create([
            'nombre' => 'Administrador',
            'scope' => 'admin'
        ]);
        // Operator
        Rol::create([
            'nombre' => 'Operador',
            'scope' => 'operator'
        ]);
        // User
        Rol::create([
            'nombre' => 'Operador',
            'scope' => 'user'
        ]);
    }
}
