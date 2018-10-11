<?php

namespace App\Http\Resources;

use App\Consorcio;
use App\Factura;
use App\User;
use App\Gasto;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class Pago extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $propietario = User::find($this->propietario_id);
        $usuarioQueGeneraElPago = User::find($this->usuario_que_genera_el_pago_id);
        $factura = Factura::find($this->factura_id);
        $mes = (int) explode("-", $this->fecha)[1];
        $anio = (int) explode("-", $this->fecha)[0];
        $consorcioId = Consorcio::obtenerConsorcioIdDesdePagoId($this->id);

        return [
            "id" => $this->id,
            "fecha" => $this->fecha,
            "monto" => $this->monto,
            "estado" => $this->estado,
            "medio_de_pago" => $this->medio_de_pago,
            "codigo_comprobante" => $this->codigo_comprobante,
            "propietario" => $propietario,
            "usuario_que_genera_el_pago" => $usuarioQueGeneraElPago,
            "factura" => $factura,
            'gastos_del_periodo' => DB::table('gastos')
                                        ->where('mes', $mes)
                                        ->where('anio', $anio)
                                        ->where('consorcio_id', $consorcioId)
                                        ->get()
        ];
    }
}
