<?php

namespace App\Http\Controllers;

use App\User;

class TestController extends Controller
{
    public function index(){
        return User::getUserIdsByConsorcioId(1);
    }

}
