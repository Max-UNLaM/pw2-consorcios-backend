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
    //Rutas de expensas a replicar en el resto de los models, tener en cuenta los /user/ y /admin/
    Route::get('/admin/expensa', 'ExpensaController@index')->middleware('auth:api', 'scope:operator,admin');
    Route::get('/user/expensa', 'ExpensaController@user')->middleware('auth:api', 'scope:user');
    Route::post('/admin/expensa', 'ExpensaController@store')->middleware('auth:api', 'scope:operator,admin');
    Route::put('/admin/expensa', 'ExpensaController@update')->middleware('auth:api', 'scope:operator,admin');
    Route::delete('/admin/expensa', 'ExpensaController@delete')->middleware('auth:api', 'scope:operator,admin');

    Route::get('/unidad', 'UnidadController@index')->middleware('auth:api', 'scope:operator,admin');
    Route::post('/unidad', 'UnidadController@store')->middleware('auth:api', 'scope:operator,admin');
    Route::delete('/unidad', 'UnidadController@delete')->middleware('auth:api', 'scope:operator,admin');
    Route::get('/consorcio', 'ConsorcioController@index')->middleware('auth:api', 'scope:operator,admin');
    Route::post('/consorcio', 'ConsorcioController@store')->middleware('auth:api', 'scope:operator,admin');
    Route::delete('/consorcio', 'ConsorcioController@delete')->middleware('auth:api', 'scope:operator,admin');
    Route::get('/factura', 'FacturaController@index')->middleware('auth:api', 'scope:operator,admin');
    Route::post('auth/admin/token/create', 'Auth\AdminController@addRoles')->middleware(['auth:api', 'scope:admin']);
    Route::post('auth/register', 'Auth\ApiRegisterController@register');
    Route::post('oauth/login', 'Auth\PassportController@login');
    Route::post('oauth/register', 'Auth\PassportController@register');
    Route::get('/gasto/mensual', 'GastoController@gastosMensual');
    Route::get('/gasto', 'GastoController@index')->middleware('auth:api', 'scope:operator,admin');
    Route::post('/gasto', 'GastoController@store')->middleware('auth:api', 'scope:operator,admin');
    Route::delete('/gasto', 'GastoController@delete')->middleware('auth:api', 'scope:operator,admin');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('get-details', 'Auth\PassportController@getDetails');
    });
});
