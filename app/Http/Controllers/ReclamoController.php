<?php

namespace App\Http\Controllers;

use App\Reclamo;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Resources\Reclamo as ReclamoResource;
use App\Http\Resources\ReclamoCollection;
use Illuminate\Support\Facades\DB;

class ReclamoController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->get('id');
        if($id) return new ReclamoResource(Reclamo::find($id));

        $size = $request->get('size') ? $request->get('size') : 5;
        $reclamos = Reclamo::orderByDesc('fecha_reclamo')->paginate($size);

        return new ReclamoCollection($reclamos);
    }

    public function user(Request $request)
    {
        if($request->get('puerta')) return response(["entra" => "PATOVA"]);

        $id = $request->get('id');
        if($id) return new ReclamoResource(Reclamo::find($id));

        $size = $request->get('size') ? $request->get('size') : 5;

        $user = User::find(Auth::user()->getAuthIdentifier());
        $consorcioId = User::getConsorcioIdForUser($user->id);

        $reclamos = DB::table('reclamos')
            ->join('unidads', 'reclamos.unidad_id', '=', 'unidads.id')
            ->where('unidads.consorcio_id', $consorcioId)
            ->orderByDesc('fecha_reclamo')
            ->paginate($size);

        return new ReclamoCollection($reclamos);
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
        $usuario_id = Auth::user()->getAuthIdentifier();
        $unidad_id = $request->get('unidad_id');
        $motivo = $request->get('motivo');
        $fecha_reclamo =  Carbon::now();

        if(!$unidad_id) return response("El campo unidad_id es obligatorio", 400);
        if(!$motivo) return response("El campo motivo es obligatorio", 400);
    
        $reclamo = Reclamo::create([
            'usuario_id' => $usuario_id,
            'unidad_id' => $unidad_id,
            'motivo' => $motivo,
            'fecha_reclamo' => $fecha_reclamo,
            'estado_de_reclamo_id' => 4
        ]);

        return $reclamo;
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
            $reclamo->conforme = $request->get('resuelto');

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

    public function estadoReclamo(Request $request)
    {
        $id = $request->get('id');
        if(!$id) return response ("El parametro id es obligatorio", 400);

        $estadoDeReclamoId = $request->get('estado_de_reclamo_id');
        if(!$estadoDeReclamoId) return response ("El parametro estado_de_reclamo_id es obligatorio (1 = resuelto, 2 = no resuelto, 3 = rechazado)", 400);

        $reclamo = Reclamo::find($id);

        if($reclamo){
            $reclamo->estado_de_reclamo_id = $estadoDeReclamoId;
            $reclamo->comentario_admin = $request->get('comentario_admin');

            $reclamo->save();
            return response([
                'reclamoGuardado' => $reclamo
            ]);
        } else {
            return response(['No se encontro el reclamo que se quiere actualizar'], 404);
        }
    }
}