<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
       'status' => session('status')
    ]);
})->name('welcome');

Route::post('/', [\App\Http\Controllers\UserController::class, 'searchUser'])->name('welcome');
Route::patch('/', [\App\Http\Controllers\UserController::class, 'store'])->name('welcome');
Route::get('/download', [\App\Http\Controllers\UserController::class, 'export'])->name('welcome');
