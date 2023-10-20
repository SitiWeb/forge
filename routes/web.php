<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServerController;
use \App\Http\Controllers\SiteController;
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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// Define routes that require authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/servers', [ServerController::class, 'index'])->name('servers.index');
    Route::get('/servers/sync', [ServerController::class, 'syncServers'])->name('servers.sync');
    Route::get('/servers/{id}', [ServerController::class, 'show'])->name('servers.show');

    Route::get('/sites/{server}/create', [SiteController::class, 'create'])->name('projects.create');
    Route::post('/sites', [SiteController::class, 'store'])->name('projects.store');
    Route::get('/sites/{server}/{site}/delete', [SiteController::class, 'deleteSite'])->name('projects.delete.site');
    Route::get('/sites/{server}/{site}/deploy', [SiteController::class, 'doDeploy'])->name('projects.deploy');
    Route::get('/sites/{server}/{site}/ssl', [SiteController::class, 'installSsl'])->name('projects.ssl');
    Route::get('/sites/{server}/{site}/install', [SiteController::class, 'installNew'])->name('projects.install');
    Route::get('/database/{server}/{database}/delete', [SiteController::class, 'deleteDatabase'])->name('projects.delete.database');
    Route::get('/sites/{server}/{site}', [SiteController::class, 'show'])->name('projects.show');
});