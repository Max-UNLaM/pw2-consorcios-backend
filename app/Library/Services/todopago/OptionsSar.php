<?php
/**
 * Created by PhpStorm.
 * User: maximiliano
 * Date: 24/07/18
 * Time: 17:54
 */

namespace App\Library\Services\todopago;

use App\Library\Services\todopago\lib\Constantes;

class OptionsSar
{
    public static function getOptionsSarComercio(int $operationId)
    {
        return [
            'Security' => env("TODOPAGO_SECURITY"),
            'EncodingMethod' => 'XML',
            'Merchant' => env("TODOPAGO_MERCHANT"),
            'URL_OK' => env("APP_URL_LOCA") . Constantes::TODOPAGO_CONTROLLER_OK . '?operation_id=' . $operationId,
            'URL_ERROR' => env("APP_URL_LOCA") . Constantes::TODOPAGO_CONTROLLER_ERROR . '?operation_id=' . $operationId,
        ];
    }
}