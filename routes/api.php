<?php

use App\Http\Controllers\Jefe;
use App\Http\Controllers\Juego;
use App\Http\Controllers\Mapa;
use App\Http\Controllers\Tipo;
use App\Models\jefes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/Tipos')->group(function()
{
    Route::get('/insertar',[Tipo::class,'insertarTipo']);
    Route::get('/modificar/{id}',[Tipo::class,'modificarTipo']);
    Route::get('/consultar',[Tipo::class,'consultarTipos']);
    Route::get('/consultar/{id}',[Tipo::class,'consultarTipo']);
});
Route::prefix('/Jefes')->group(function()
{
    Route::get('/modificar/{id}',[Jefe::class,'modificarJefe']);
    Route::get('/insertar',[Jefe::class,'insertarJefe']);
    Route::get('/consultar', [jefe::class, 'consultarJefes']);
    Route::get('/consultar/{id}', [jefe::class, 'consultarJefe']);
});
Route::prefix('/Mapas')->group(function()
{
    Route::get('/modificar/{id}',[Mapa::class,'modificarMapa']);
    Route::get('/insertar',[Mapa::class,'insertarMapa']);
    Route::get('/consultar',[Mapa::class,'consultarMapas']);
});
Route::prefix('/Juegos')->group(function()
{
    Route::get('/modificar/{id}',[Juego::class,'modificarJuego']);
    Route::get('/insertar',[Juego::class,'insertarJuego']);
    Route::get('/consultar',[Juego::class,'consultarJuegos']);
    Route::get('/consultar/{id}',[Juego::class,'consultarJuego']);
});
    

