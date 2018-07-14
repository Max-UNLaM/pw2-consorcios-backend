<?php

namespace App\Http\Controllers;

use App\Expensa;
use Illuminate\Http\Request;

class ExpensaController extends Controller
{
	public function index(Request $request) 
	{
		if ($request->get('page')) {
            return $this->paginate($request);
        } else if ($request->get('id')) {
			$this->show($request->get('id'));
		} else {
			return Expensa::all();
		}
	}
	public function paginate(Request $request)
    {
        return Expensa::paginate($request->get('size'));
    }
	public function store(Request $request)
	{
		$expensa = Expensa::create($request->all());
		return response([
            'expensa' => $expensa
        ]);
	}
	public function show($id)
    {
        return Expensa::find($id);
    }

    public function delete(Request $request){
	    Expensa::destroy($request->get('id'));
    }
}