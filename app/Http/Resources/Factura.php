<?php

namespace App\Http\Resources;

use App\Consorcio;
use App\Unidad;
use App\User;
use App\Expensa;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class Factura extends JsonResource
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
        $consorcio = Consorcio::find($this->consorcio_id);
        $expensa = Expensa::find($this->expensa_id);
        $unidad = Unidad::find($expensa->unidad_id);
        $mes = (int) $this->mes;
        $anio = (int) $this->anio;

        return [
            "id" => $this->id,
            "mes" => $mes,
            "anio" => $anio,
            "emision" => $this->emision,
            "vencimiento" => $this->vencimiento,
            "total" => $this->total,
            "pago_parcial" => $this->pago_parcial,
            "adeuda" => $this->adeuda,
            "pago" => $this->pago,
            'usuario' => $usuario,
            'consorcio' => $consorcio,
            'unidad' => $unidad,
            'expensa' => $expensa,
            'gastos_del_periodo' => DB::table('gastos')
                ->where('gastos.mes', $mes)
                ->where('gastos.anio', $anio)
                ->where('gastos.consorcio_id', $this->consorcio_id)
                ->get()
        ];
    }
}
