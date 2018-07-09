<?php

namespace App\Http\Controllers;

use App\Expensa;
use Illuminate\Http\Request;

class ExpensaController extends Controller
{
    public function index() {
		return Expensa::all();
	}
}
