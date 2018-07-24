<?php
/**
 * Created by PhpStorm.
 * User: maximiliano
 * Date: 24/07/18
 * Time: 18:07
 */

namespace App\Library\Services\todopago;

use App\Dto\Pago\ExpensaDto;

class PagoFactura
{
    public function operacionElementos(array $optionsSAR) {

    }

    protected function expensaExtractor(ExpensaDto $expensa, string $propiedadExpensa, string $parametroOperacion) {
        $propiedadExpensa = 'get' . ucfirst($propiedadExpensa);
        $dato = $expensa->{$propiedadExpensa};
    }


}