<?php

namespace App\Http\Controllers;

use App\Expensa;
use Illuminate\Http\Request;

class ExpensaController extends Controller
{
	public function index(Request $request){
		if ($request->get('page')) {
            return $this->paginate($request);
        } else if ($request->get('id')) {
			return $this->show($request->get('id'));
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
		if(Expensa::find($request->get('id')) != null) $this->delete($request);

        Expensa::create($request->all());

		return response([
            'expensa' => $request->all()
        ]);
	}

	public function show($id)
    {
        return Expensa::find($id);
    }

    public function delete(Request $request){
	    $resp = Expensa::destroy($request->get('id'));

	    if($resp){
	        return 'ID '.$request->get('id').' deleted OK';
        } else {
	        return 'ID '.$request->get('id').' not found';
        }
    }
}