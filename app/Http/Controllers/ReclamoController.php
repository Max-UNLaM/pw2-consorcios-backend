<?php

namespace App\Http\Controllers;

use App\Reclamo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ReclamoController extends Controller
{
    public function index(Request $request)
    {
        if ($request->get('page')) {
            return $this->paginate($request);
        } else if ($request->get('id')) {
            return $this->show($request->get('id'));
        } else {
            return Reclamo::all();
        }
    }

    public function user(Request $request)
    {
        if ($request->get('puerta')) {
            return "PATOVA";
        } elseif ($request->get('page')) {
            return $this->getAllReclamosOfUserPaginada($request, Auth::user()->getAuthIdentifier());
        } else {
            return $this->getAllReclamosOfUser(Auth::user()->getAuthIdentifier())->get(['*']);
        }
    }

    protected function getAllReclamosOfUserPaginada(Request $request, $userId)
    {
        $size = $request->get('size') ? $request->get('size') : 10;
        return $this->getAllReclamosOfUser($userId)->paginate($size);
    }

    protected function getAllReclamosOfUser($userId)
    {
        return Reclamo::getAllReclamosOfUser($userId);
    }

    public function paginate(Request $request)
    {
        return Reclamo::paginate($request->get('size'));
    }

    public function show($id)
    {
        return Reclamo::find($id);
    }

    public function store(Request $request)
    {
        $carga = [
            'usuario_id' => Auth::user()->getAuthIdentifier(),
            'unidad_id' => $request->get('unidad_id'),
            'motivo' => $request->get('motivo'),
            'fecha_reclamo' => $request->get('fecha_reclamo'),
            'fecha_resolucion' => '1989-01-01',
            'conforme' => '0:0:0'
        ];
        Reclamo::create($carga);
        return $carga;
    }

    public function delete(Request $request)
    {
        $resp = Reclamo::destroy($request->get('id'));
        if ($resp) {
            return 'ID ' . $request->get('id') . ' deleted OK';
        } else {
            return 'ID ' . $request->get('id') . ' not found';
        }
    }

    public function update(Request $request)
    {
        //Busco el reclamo correspondiente
        $reclamo = Reclamo::find($request->get('id'));

        //Pregunto si encontro un reclamo con ese id
        if ($reclamo) {
            //Actualizo los atributos del reclamo encontrado 
            $reclamo->usuario_id = $request->get('usuario_id');
            $reclamo->unidad_id = $request->get('unidad_id');
            $reclamo->motivo = $request->get('motivo');
            $reclamo->fecha_reclamo = $request->get('fecha_reclamo');
            $reclamo->fecha_resolucion = $request->get('fecha_resolucion');
            $reclamo->conforme = $request->get('conforme');

            //Guardo los cambios
            $reclamo->save();

            return response([
                'reclamoGuardado' => $reclamo
            ]);
        } else {
            //Si no lo encuentra respondo un codigo 404 (not found)
            return response(['No se encontro el reclamo que se quiere actualizar'], 404);
        }
    }
}
