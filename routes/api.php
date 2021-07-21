<?php

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

Route::get('/', 'App\Http\Controllers\API\ColumnController@index');
Route::post('/', 'App\Http\Controllers\API\ColumnController@create');
Route::delete('/{id}', 'App\Http\Controllers\API\ColumnController@delete');

Route::post('/card/{column_id}', 'App\Http\Controllers\API\CardController@create');
Route::delete('/card/{id}', 'App\Http\Controllers\API\CardController@delete');
Route::patch('/card/{id}', 'App\Http\Controllers\API\CardController@update');

Route::post('/dump', function () {
    \Spatie\DbDumper\Databases\Sqlite::create()
        ->setDbName(config('database.connections.sqlite.database'))
        ->dumpToFile('dump.sql');
        return response()->json(['status' => 'OK']);
});
