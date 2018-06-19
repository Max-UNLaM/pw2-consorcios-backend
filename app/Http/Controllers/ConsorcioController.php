<?php

namespace App\Http\Controllers;

use App\Consorcio;
use Illuminate\Http\Request;

class ConsorcioController extends Controller
{
	public function index() {
		return Consorcio::all();
	}

}
