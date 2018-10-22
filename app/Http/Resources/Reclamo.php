<?php

namespace App\Http\Resources;

use App\EstadoDeReclamo;
use App\Unidad;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class Reclamo extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $usuario = User::find($this->usuario_id);
        $unidad = Unidad::find($this->unidad_id);
        $estadoDeReclamo = EstadoDeReclamo::find($this->estado_de_reclamo_id);

        return [
            'id' => $this->id,
            'motivo' => $this->motivo,
            'comentario_user' => $this->comentario_user,
            'comentario_admin' => $this->comentario_admin,
            'fecha' => $this->fecha_reclamo,
            'usuario' => $usuario,
            'unidad' => $unidad,
            'estado_de_reclamo' => $estadoDeReclamo
        ];
    }
}
