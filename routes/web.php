<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
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
require __DIR__.'/auth.php';

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::group(['prefix' => 'admin', 'middleware'=> ['auth','verified','role:Super Admin|Admin']],function () {

    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');


    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);

    // Mass Destroy
    Route::post('users/mass-destroy', [UserController::class, 'massDestroy'])->name('users.mass-destroy');
    Route::post('roles/mass-destroy', [RoleController::class, 'massDestroy'])->name('roles.mass-destroy');
});


Route::permanentRedirect('/admin','/admin/dashboard');
