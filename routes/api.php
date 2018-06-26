<?php

use Illuminate\Http\Request;
use App\Unidad;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



# Route::post('/register', 'Auth\RegisterController@register');

Route::group(['middleware' => ['api','cors']], function () {
    Route::get('/unidad', 'UnidadController@index');
    Route::get('/consorcio', 'ConsorcioController@index');
    Route::get('/factura', 'FacturaController@index');
    Route::post('auth/register', 'Auth\ApiRegisterController@register');
});
