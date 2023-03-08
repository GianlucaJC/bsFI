<?php

use App\Http\Controllers\ProfileController;
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


Route::group(['only_log' => ['auth']], function () {
	Route::get('dashboard', [ 'as' => 'dashboard', 'uses' => 'App\Http\Controllers\MainController@dashboard']);
	//->middleware(['permission:gestione_archivi'])


	Route::get('definizione_attivita', [ 'as' => 'definizione_attivita', 'uses' => 'App\Http\Controllers\ControllerArchivi@definizione_attivita']);
	//->middleware(['permission:gestione_archivi'])
	
	Route::post('definizione_attivita', [ 'as' => 'definizione_attivita', 'uses' => 'App\Http\Controllers\ControllerArchivi@definizione_attivita']);
	//->middleware(['permission:gestione_archivi'])	
});	


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
