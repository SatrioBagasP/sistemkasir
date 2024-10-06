<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\NotaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::prefix('news')->controller(ControllerNews::class)->group(function () {
//     Route::get('/', 'index')->name('news.index');
//     Route::get('/{id}', 'show')->name('news.show');
//     Route::post('/store', 'store')->name('news.store');
//     Route::put('/{id}', 'update')->name('news.update');
//     Route::delete('/{id}', 'destroy')->name('news.delete');
// });

// Route::get('/', [HomeController::class, 'index'])->name('home');
Route::prefix('/')->controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home.index');
    Route::get('kasir', 'kasir')->name('home.kasir');
    Route::get('data', 'data')->name('home.data');
    Route::POST('input', 'input')->name('home.input');
    Route::get('printnota/{id}', 'printNota')->name('home.printnota');
});
Route::prefix('/data/menu')->controller(MenuController::class)->group(function () {
    Route::get('/', 'index')->name('data.menu');
    Route::post('/store', 'store')->name('menu.store');
    Route::delete('/delete/{id}', 'delete')->name('menu.delete');
    Route::get('/show/{id}', 'show')->name('menu.show');
    Route::put('/update/{id}', 'update')->name('menu.update');
});

Route::prefix('/data/nota')->controller(NotaController::class)->group(function () {
    Route::get('/', 'index')->name('data.nota');
    Route::post('/store', 'store')->name('nota.store');
    Route::delete('/delete/{id}', 'delete')->name('nota.delete');
    Route::get('/show/{id}', 'show')->name('nota.show');
    Route::put('/update/{id}', 'update')->name('nota.update');
});
