<?php

use Illuminate\Support\Facades\Route;
use App\Models\Result; 
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\ResultController::class, 'show'])->name('home');

Route::get('/results/show/{result}', [App\Http\Controllers\ResultController::class, 'show'])->name('result_show');

Route::post('/submit', [App\Http\Controllers\ResultController::class, 'submit'])->name('submit'); 

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
