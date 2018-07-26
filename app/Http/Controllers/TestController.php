<?php

namespace App\Http\Controllers;

use App\Consorcio;
use App\Expensa;
use App\Gasto;
use App\Pago;
use App\Unidad;
use App\User;

class TestController extends Controller
{
    public function index(){
<<<<<<< HEAD
        return Unidad::getAllUnidadIdOfUser(4);
=======
        return Consorcio::obtenerPropietarios(1);
>>>>>>> develop
    }

}
