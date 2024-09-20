<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
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
Route::middleware('guest')->namespace('Auth')->group(function () {
    Route::get('/','LoginController@showLoginForm')->name('login');
    Route::get('/login','LoginController@showLoginForm')->name('login');
    Route::post('/login','LoginController@login');
});


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::post('/logout','Auth\LoginController@logout')->name('logout');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/password', [ProfileController::class, 'password'])->name('password');
    Route::patch('/password', [ProfileController::class, 'passwordUpdate'])->name('password.update');


    Route::middleware('verified')->group(function () {
        Route::get('/dashboard','DashboardController@index')->name('dashboard');
        Route::POST('/notifikasi','DashboardController@notifikasi')->name('notifikasi');

        
        Route::prefix('/users')->name('user.')->group(function () {
            Route::get('/','UserController@index')->name('index');
            Route::get('/create','UserController@create')->name('create');
            Route::post('/store','UserController@store')->name('store');
            Route::get('/{id}','UserController@show')->name('show');
            Route::get('/{id}/edit','UserController@edit')->name('edit');
            Route::post('{id}/update','UserController@update')->name('update');
            Route::delete('/{id}/delete','UserController@destroy')->name('delete');
        });
        Route::get('/get-users-by-lokasi/{lokasi_id}', [UserController::class, 'getUsersByLokasi']);


        Route::prefix('/kerusakan')->name('crash.')->group(function () {
            Route::get('/','KerusakanController@index')->name('index');
            Route::get('/create','KerusakanController@create')->name('create');
            Route::post('/store','KerusakanController@store')->name('store');
            Route::get('/export','KerusakanController@export')->name('export');
            Route::get('/{id}','KerusakanController@show')->name('show');
            Route::get('/{id}/edit','KerusakanController@edit')->name('edit');
            Route::post('{id}/update','KerusakanController@update')->name('update');
            Route::delete('/{id}/delete','KerusakanController@destroy')->name('delete');
        });
        

        Route::prefix('/kategori')->name('kategori.')->group(function () {
            Route::get('/','KategoriController@index')->name('index');
            Route::get('/select','KategoriController@select')->name('select');
            Route::get('/create','KategoriController@create')->name('create');
            Route::post('/store','KategoriController@store')->name('store');
            Route::get('/{id}','KategoriController@show')->name('show');
            Route::get('/{id}/edit','KategoriController@edit')->name('edit');
            Route::post('{id}/update','KategoriController@update')->name('update');
            Route::delete('/{id}/delete','KategoriController@destroy')->name('delete');
        });

        
        Route::prefix('/lokasi')->name('lokasi.')->group(function () {
            Route::get('/','LokasiController@index')->name('index');
            Route::get('/create','LokasiController@create')->name('create');
            Route::post('/store','LokasiController@store')->name('store');
            Route::get('/{id}','LokasiController@show')->name('show');
            Route::get('/{id}/edit','LokasiController@edit')->name('edit');
            Route::post('{id}/update','LokasiController@update')->name('update');
            Route::delete('/{id}/delete','LokasiController@destroy')->name('delete');
        });
        
        Route::prefix('/maintenance')->name('maintenance.')->group(function () {
            Route::get('/','PerbaikanController@index')->name('index');
            Route::get('/create','PerbaikanController@create')->name('create');
            Route::post('/store','PerbaikanController@store')->name('store');
            Route::get('/export','PerbaikanController@export')->name('export');
            Route::get('/{id}','PerbaikanController@show')->name('show');
            Route::get('/{id}/edit','PerbaikanController@edit')->name('edit');
            Route::post('{id}/update','PerbaikanController@update')->name('update');
            Route::delete('/{id}/delete','PerbaikanController@destroy')->name('delete');
            Route::post('{id}/confirm','PerbaikanController@confirm')->name('confirm');
        });
        
        Route::prefix('/maintener')->name('maintener.')->group(function () {
            Route::get('/','MaintenerController@index')->name('index');
            Route::get('/create','MaintenerController@create')->name('create');
            Route::post('/store','MaintenerController@store')->name('store');
            Route::get('/{id}','MaintenerController@show')->name('show');
            Route::get('/{id}/edit','MaintenerController@edit')->name('edit');
            Route::post('{id}/update','MaintenerController@update')->name('update');
            Route::delete('/{id}/delete','MaintenerController@destroy')->name('delete');
        });
        
        Route::prefix('/jabatan')->name('jabatan.')->group(function () {
            Route::get('/','JabatanController@index')->name('index');
            Route::get('/create','JabatanController@create')->name('create');
            Route::post('/store','JabatanController@store')->name('store');
            Route::get('/{id}','JabatanController@show')->name('show');
            Route::get('/{id}/edit','JabatanController@edit')->name('edit');
            Route::post('{id}/update','JabatanController@update')->name('update');
            Route::delete('/{id}/delete','JabatanController@destroy')->name('delete');
        });

        Route::prefix('/pegawai')->name('pegawai.')->group(function () {
            Route::get('/','PegawaiController@index')->name('index');
            Route::get('/data','PegawaiController@data')->name('data');
            Route::get('/create','PegawaiController@create')->name('create');
            Route::post('/store','PegawaiController@store')->name('store');
            Route::get('/{id}','PegawaiController@show')->name('show');
            Route::get('/{id}/edit','PegawaiController@edit')->name('edit');
            Route::post('{id}/update','PegawaiController@update')->name('update');
            Route::delete('/{id}/delete','PegawaiController@destroy')->name('delete');
        });

        
        Route::prefix('/inventaris')->name('inventaris.')->group(function () {
            Route::get('/','InventarisController@index')->name('index');
            Route::get('/select','InventarisController@select')->name('select');
            Route::get('/create','InventarisController@create')->name('create');
            Route::post('/store','InventarisController@store')->name('store');
            Route::get('/export','InventarisController@export')->name('export');
            Route::get('/{id}','InventarisController@show')->name('show');
            Route::get('/{id}/json','InventarisController@json')->name('json');
            Route::get('/{id}/edit','InventarisController@edit')->name('edit');
            Route::post('{id}/update','InventarisController@update')->name('update');
            Route::delete('/{id}/delete','InventarisController@destroy')->name('delete');
        });

        Route::prefix('/pindah')->name('pindah.')->group(function () {
            Route::get('/','PindahController@index')->name('index');
            Route::get('/select','PindahController@select')->name('select');
            Route::get('/create','PindahController@create')->name('create');
            Route::post('/store','PindahController@store')->name('store');
            Route::get('/{id}','PindahController@show')->name('show');
            Route::get('/{id}/edit','PindahController@edit')->name('edit');
            Route::post('{id}/update','PindahController@update')->name('update');
            Route::delete('/{id}/delete','PindahController@destroy')->name('delete');
        });


    });
});
