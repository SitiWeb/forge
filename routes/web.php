<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServerController;
use \App\Http\Controllers\SiteController;
use \App\Http\Controllers\DataController;
use \App\Http\Controllers\ImportController;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\DirectadminController;
use App\Http\Controllers\AdminBackupController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SSHKeyController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\DatabaseUserController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\CronController;
use Illuminate\Support\Facades\Auth;
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

Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// Define routes that require authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/servers', [ServerController::class, 'index'])->name('servers.index')->middleware('checkRole:Admin,Moderator');
    Route::get('/servers/{server}/check-backup-config', [ServerController::class, 'checkBackupConfig'])->name('servers.checkbackup')->middleware('checkRole:Admin,Moderator');
    Route::get('/servers/sync', [ServerController::class, 'syncServers'])->name('servers.sync')->middleware('checkRole:Admin,Moderator');
    Route::get('/servers/{id}', [ServerController::class, 'show'])->name('servers.show')->middleware('checkRole:Admin,Moderator');

    Route::get('/sites/{server}/create', [SiteController::class, 'create'])->name('projects.create')->middleware('checkRole:Admin,Moderator');
    Route::get('/sites', [SiteController::class, 'index'])->name('projects.index')->middleware('checkRole:Admin,Moderator');
    Route::post('/sites', [SiteController::class, 'store'])->name('projects.store')->middleware('checkRole:Admin,Moderator');
    Route::get('/sites/{server}/{site}/delete', [SiteController::class, 'deleteSite'])->name('projects.delete.site')->middleware('checkRole:Admin,Moderator');
    Route::get('/sites/{server}/{site}/updateEnvFile', [SiteController::class, 'updateEnvFile'])->name('projects.delete.updateEnvFile')->middleware('checkRole:Admin,Moderator');

    Route::get('/sites/{server}/{site}/deploy', [SiteController::class, 'doDeploy'])->name('projects.deploy')->middleware('checkRole:Admin,Moderator');
    Route::get('/sites/{server}/{site}/ssl', [SiteController::class, 'installSsl'])->name('projects.ssl')->middleware('checkRole:Admin,Moderator');
    Route::get('/sites/{server}/{site}/install', [SiteController::class, 'installNew'])->name('projects.install')->middleware('checkRole:Admin,Moderator');
    Route::post('/sites/{server}/{site}/command', [SiteController::class, 'command'])->name('projects.command')->middleware('checkRole:Admin,Moderator');
    Route::post('/sites/{server}/{site}/loginas', [SiteController::class, 'loginas'])->name('projects.loginas')->middleware('checkRole:Admin,Moderator');
    Route::get('/sites/{server}/{site}/ssoinstall', [SiteController::class, 'installSSO'])->name('projects.installsso')->middleware('checkRole:Admin,Moderator');
    Route::get('/sites/{server}/{site}/syncadmins', [SiteController::class, 'syncWordpressAdmins'])->name('projects.syncadmins')->middleware('checkRole:Admin,Moderator');
    Route::get('/wpcron/create/{site}', [CronController::class, 'createWPCron'])->name('wpcron.createcron');
    Route::get('/wpcron/delete/{site}', [CronController::class, 'deleteWPCron'])->name('wpcron.deletecron');
    Route::get('/database/{server}/{database}/delete', [SiteController::class, 'deleteDatabase'])->name('projects.delete.database')->middleware('checkRole:Admin,Moderator');
    Route::get('/sites/{server}/{site}', [SiteController::class, 'show'])->name('projects.show')->middleware('checkRole:Admin,Moderator');
    Route::get('/sites/sync', [SiteController::class, 'syncSites'])->name('sites.sync')->middleware('checkRole:Admin,Moderator');
    Route::get('/imports/rsync/{import}', [ImportController::class, 'rsync'])->name('import.rsync')->middleware('checkRole:Admin');
    Route::get('/databases/sync', [DatabaseController::class, 'syncDatabses'])->name('sites.databases')->middleware('checkRole:Admin,Moderator');
    Route::get('/databaseusers/sync', [DatabaseUserController::class, 'syncDatabseUsers'])->name('sites.syncDatabseUsers')->middleware('checkRole:Admin,Moderator');

    Route::post('/sites/{site}/backupconfig', [SiteController::class, 'backupconfig'])->name('backups.config')->middleware('checkRole:Admin,Moderator');
    Route::get('/server/{server}/all-backups', [ServerController::class, 'AllBackups'])->name('allbackups.config')->middleware('checkRole:Admin');
    Route::resource('/imports',ImportController::class)->middleware('checkRole:Admin,Moderator');
    Route::resource('/users',UserController::class)->middleware('checkRole:Admin,Moderator');
    Route::resource('/roles',RoleController::class)->middleware('checkRole:Admin');
    Route::resource('/adminbackups',AdminBackupController::class)->middleware('checkRole:Admin');
    Route::resource('/directadmin',DirectadminController::class)->middleware('checkRole:Admin,Moderator')->middleware('checkRole:Admin');
    Route::resource('/sshkeys',SSHKeyController::class)->middleware('checkRole:Admin,Moderator')->middleware('checkRole:Admin');
    Route::resource('/databases',DatabaseController::class)->middleware('checkRole:Admin,Moderator')->middleware('checkRole:Admin');
    Route::resource('/databaseusers',DatabaseUserController::class)->middleware('checkRole:Admin,Moderator')->middleware('checkRole:Admin');
    Route::get('/directadmin/{directadmin}/test', [DirectadminController::class, 'test'])->name('directadmin.test')->middleware('checkRole:Admin');

    Route::get('/data/server/sites/{server}', [DataController::class, 'sites'])->name('data.sites')->middleware('checkRole:Admin,Moderator');
    Route::get('/data/sites/deployment/{server}/{site}/{deployment_id}', [DataController::class, 'deployment'])->name('data.deployment')->middleware('checkRole:Admin,Moderator');
    Route::get('/data/sites/console/{server}/{site}', [DataController::class, 'console'])->name('data.console')->middleware('checkRole:Admin,Moderator');
    Route::get('/data/sites/command/{server}/{site}/{command}', [DataController::class, 'command'])->name('data.command')->middleware('checkRole:Admin,Moderator');
    Route::get('/data/sites/admins/{server}/{site}', [DataController::class, 'listAdmins'])->name('data.admins')->middleware('checkRole:Admin,Moderator');
    Route::post('/data/sites/loginas/{server}/{site}', [DataController::class, 'loginUrl'])->name('data.login')->middleware('checkRole:Admin,Moderator');
    Route::post('/data/action/{directadmin}/{action}', [DirectadminController::class, 'dataSwitch'])->name('directadmin.action')->middleware('checkRole:Admin,Moderator');



    Route::get('/files', [FileController::class, 'index'])->name('files.index');
    Route::get('/files/config/{site}', [FileController::class, 'createConfigFile'])->name('files.createconfig');
    
    Route::post('/files/upload', [FileController::class, 'upload'])->name('uploadFile');
    Route::get('/files/file/{filename}', [FileController::class, 'show'])->name('showFile');
    Route::delete('/files/{id}', [FileController::class, 'delete'])->name('files.delete');
    
});

// Route::get('/capture-screenshot', [SiteController::class, 'captureScreenshot']);
// Route::get('/test', [DirectadminController::class, 'index']);