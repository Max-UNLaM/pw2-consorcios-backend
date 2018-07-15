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

Route::get('/unidad', 'UnidadController@index');

# Route::post('/register', 'Auth\RegisterController@register');

Route::get('/factura', 'FacturaController@index');

Route::group(['middleware' => ['api', 'cors']], function () {
    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/unidad', 'UnidadController@index')->middleware('auth:api');
    Route::get('/consorcio', 'ConsorcioController@index');
    Route::get('/factura', 'FacturaController@index')->middleware('auth:api', 'scope:operator,admin');
    Route::post('auth/admin/token/create', 'Auth\AdminController@addRoles')->middleware(['auth:api','scope:admin']);
    Route::post('auth/register', 'Auth\ApiRegisterController@register');
    Route::post('oauth/login', 'Auth\PassportController@login');
    Route::post('oauth/register', 'Auth\PassportController@register');
    Route::get('/expensa',  'ExpensaController@index')->middleware('auth:api', 'scope:operator,admin');
    Route::post('/expensa',  'ExpensaController@store')->middleware('auth:api', 'scope:operator,admin');
    Route::delete('/expensa',  'ExpensaController@delete')->middleware('auth:api', 'scope:operator,admin');
    Route::get('/gasto', 'GastoController@index')->middleware('auth:api', 'scope:operator,admin');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('get-details', 'Auth\PassportController@getDetails');
    });
});
