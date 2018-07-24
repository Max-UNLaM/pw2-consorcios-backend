<?php

namespace App\Http\Controllers;

class TestController extends Controller
{
    public function index(){
        $ec = new EstadisticaController();
        return $ec->estadisticasPorConsorcio(3);
    }

}
