<?php

use Illuminate\Http\Request;

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
Route::get('/test', 'TestController@index');


# Route::post('/register', 'Auth\RegisterController@register');

Route::group(['middleware' => ['api', 'cors']], function () {
    // OAUTH
    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('auth/admin/token/create', 'Auth\AdminController@addRoles')->middleware(['auth:api', 'scope:admin']);
    Route::post('auth/register', 'Auth\ApiRegisterController@register');
    Route::post('oauth/login', 'Auth\PassportController@login');
    Route::post('oauth/register', 'Auth\PassportController@register');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('get-details', 'Auth\PassportController@getDetails');
    });
    // Consorcio
    // Admin
    Route::get('/admin/consorcio', 'ConsorcioController@index')->middleware('auth:api', 'scope:operator,admin');
    Route::post('/admin/consorcio', 'ConsorcioController@store')->middleware('auth:api', 'scope:operator,admin');
    Route::delete('/admin/consorcio', 'ConsorcioController@delete')->middleware('auth:api', 'scope:operator,admin');
    Route::put('/admin/consorcio', 'ConsorcioController@update')->middleware('auth:api', 'scope:operator,admin');
    Route::get('/user/consorcio', 'ConsorcioController@user')->middleware('auth:api', 'scope:user,operator,admin');


    // Unidad
    // Admin
    Route::get('/admin/unidad', 'UnidadController@index')->middleware('auth:api', 'scope:operator,admin');
    Route::post('/admin/unidad', 'UnidadController@store')->middleware('auth:api', 'scope:operator,admin');
    Route::delete('/admin/unidad', 'UnidadController@delete')->middleware('auth:api', 'scope:operator,admin');
    // User
    Route::get('/user/unidad', 'UnidadController@user')->middleware('auth:api', 'scope:user:operator,admin');

    // Expensas
    // Admin
    Route::get('/admin/expensa', 'ExpensaController@index')->middleware('auth:api', 'scope:operator,admin');
    Route::post('/admin/expensa', 'ExpensaController@store')->middleware('auth:api', 'scope:operator,admin');
    Route::put('/admin/expensa', 'ExpensaController@update')->middleware('auth:api', 'scope:operator,admin');
    Route::delete('/admin/expensa', 'ExpensaController@delete')->middleware('auth:api', 'scope:operator,admin');
    Route::put('/admin/generar-expensas', 'ExpensaController@generarExpensas')->middleware('auth:api', 'scope:operator,admin');
    Route::get('/admin/obtener-expensas-pagas', 'ExpensaController@obtenerExpensasPagas')->middleware('auth:api', 'scope:operator,admin');
    Route::get('/admin/obtener-expensas-impagas', 'ExpensaController@obtenerExpensasImpagas')->middleware('auth:api', 'scope:operator,admin');
    // User
    Route::get('/user/expensa', 'ExpensaController@user')->middleware('auth:api', 'scope:user,operator,admin');

    // Factura
    // Admin
    Route::get('/admin/factura', 'FacturaController@index')->middleware('auth:api', 'scope:operator,admin');
    Route::post('/admin/factura', 'FacturaController@store')->middleware('auth:api', 'scope:operator,admin');
    // User
    Route::get('/user/factura', 'FacturaController@user')->middleware('auth:api', 'scope:user,operator,admin');

    // Pago
    // Admin
    Route::get('/admin/pago', 'PagoController@index')->middleware('auth:api', 'scope:operator,admin');
    Route::delete('/admin/pago', 'PagoController@delete')->middleware('auth:api', 'scope:operator,admin');
    Route::post('/admin/pago', 'PagoController@store')->middleware('auth:api', 'scope:operator,admin');
    Route::put('/admin/pago', 'PagoController@update')->middleware('auth:api', 'scope:operator,admin');
    // User
    Route::get('/user/pago', 'PagoController@user')->middleware('auth:api', 'scope:user,operator,admin');
    Route::post('/user/pago', 'PagoController@store')->middleware('auth:api', 'scope:user,operator,admin');

    // Reclamo
    // Admin
    Route::get('/admin/reclamo', 'ReclamoController@index')->middleware('auth:api', 'scope:operator,admin');
    Route::put('/admin/reclamo', 'ReclamoController@update')->middleware('auth:api', 'scope:operator,admin');
    Route::delete('/admin/reclamo', 'ReclamoController@delete')->middleware('auth:api', 'scope:operator,admin');
    // User
    Route::get('/user/reclamo', 'ReclamoController@user')->middleware('auth:api', 'scope:user,operator,admin');
    Route::post('/user/reclamo', 'ReclamoController@store')->middleware('auth:api', 'scope:user,operator,admin');


    // Gasto
    Route::get('/admin/gasto/mensual', 'GastoController@gastosMensual')->middleware('auth:api', 'scope:operator,admin');
    Route::get('/admin/gasto', 'GastoController@index')->middleware('auth:api', 'scope:operator,admin');
    Route::post('/admin/gasto', 'GastoController@store')->middleware('auth:api', 'scope:operator,admin');
    Route::delete('/admin/gasto', 'GastoController@delete')->middleware('auth:api', 'scope:operator,admin');
    Route::put('/admin/gasto', 'GastoController@update')->middleware('auth:api', 'scope:operator,admin');

    //Liquidacion
    Route::get('/admin/liquidacion', 'LiquidacionController@index')->middleware('auth:api', 'scope:operator,admin');
    Route::post('/admin/liquidacion', 'LiquidacionController@store')->middleware('auth:api', 'scope:operator,admin');

    // Estadística
    // Admin
    Route::get('/admin/estadistica', 'EstadisticaController@index')->middleware('auth:api', 'scope:operator,admin');

    //Proveedores
    Route::get('/admin/proveedor', 'ProveedorController@index')->middleware('auth:api', 'scope:operator,admin');
    Route::post('/admin/proveedor', 'ProveedorController@store')->middleware('auth:api', 'scope:operator,admin');
    Route::put('/admin/proveedor', 'ProveedorController@update')->middleware('auth:api', 'scope:operator,admin');
    Route::delete('/admin/proveedor', 'ProveedorController@delete')->middleware('auth:api', 'scope:operator,admin');
});
