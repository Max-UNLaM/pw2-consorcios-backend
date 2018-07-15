<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gasto;

class GastoController extends Controller
{
    public function index(Request $request)
    {
        if ($request->get('page')) {
            return $this->paginate($request);
        } else {
            return Gasto::all();
        }
    }


    public function paginate(Request $request)
    {
        return Gasto::paginate($request->get('size'));
    }

    public function show($id)
    {
        return Gasto::find($id);
    }

    public function store(Request $request)
    {
        if(Gasto::find($request->get('id')) != null) $this->delete($request);

        Gasto::create($request->all());
        return response([
            'gasto' => $request->all()
        ]);
    }

    public function delete(Request $request){
        $resp = Gasto::destroy($request->get('id'));

        if($resp){
            return 'ID '.$request->get('id').' deleted OK';
        } else {
            return 'ID '.$request->get('id').' not found';
        }
    }
}
