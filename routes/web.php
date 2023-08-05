<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

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
  return Inertia::render('not-authorized');
});

// Route::get('login', function () {
//     return Inertia::render('login');
// })->name('login');

// Route::post('login', [AuthenticatedSessionController::class, 'store']);
// Route::get('register', function () {
//     return Inertia::render('register');
// })->name('register');


Route::middleware('auth')->group(function () {
    
    Route::get('layout', function () {
        return Inertia::render('layout');
    });
    Route::resource('profile', ProfileController::class);

});
require __DIR__ . '/auth.php';