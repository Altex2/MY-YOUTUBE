<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', [\App\Http\Controllers\VideoController::class, 'index'])->name('index');

Route::get('/register', [\App\Http\Controllers\AuthController::class, 'create'])->middleware('guest');
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'store'])->middleware('guest');
Route::get('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login')->middleware('guest');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'auth'])->middleware('guest');
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'destroy'])->middleware('auth');

Route::get('/video/{id}', [\App\Http\Controllers\VideoController::class, 'individual']);

Route::get('/videos/new', [\App\Http\Controllers\VideoController::class, 'create']);
Route::post('/videos/new', [\App\Http\Controllers\VideoController::class, 'store']);

Route::get('/channel/create', [\App\Http\Controllers\ChannelController::class, 'create'])->middleware('auth');
Route::post('/channel/create', [\App\Http\Controllers\ChannelController::class, 'store'])->middleware('auth');


Route::get('/videos/{filename}', [\App\Http\Controllers\VideoController::class, 'show']);

