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
	Route::post('dashboard', [ 'as' => 'dashboard', 'uses' => 'App\Http\Controllers\MainController@dashboard']);


	Route::get('definizione_attivita', [ 'as' => 'definizione_attivita', 'uses' => 'App\Http\Controllers\ControllerArchivi@definizione_attivita'])
	->middleware(['role:admin']);
	
	Route::post('definizione_attivita', [ 'as' => 'definizione_attivita', 'uses' => 'App\Http\Controllers\ControllerArchivi@definizione_attivita'])
	->middleware(['role:admin']);

	Route::get('definizione_utenti', [ 'as' => 'definizione_utenti', 'uses' => 'App\Http\Controllers\ControllerArchivi@definizione_utenti'])
	->middleware(['role:admin']);
	
	Route::post('definizione_utenti', [ 'as' => 'definizione_utenti', 'uses' => 'App\Http\Controllers\ControllerArchivi@definizione_utenti'])
	->middleware(['role:admin']);

});	


//routing Ajax
Route::group(['only_log' => ['auth']], function () {
	Route::post('setvalue', [ 'as' => 'setvalue', 'uses' =>'App\Http\Controllers\AjaxController@setvalue']);	

	Route::post('savedata', [ 'as' => 'savedata', 'uses' =>'App\Http\Controllers\AjaxController@savedata']);
	
	Route::post('getsettori', [ 'as' => 'getsettori', 'uses' =>'App\Http\Controllers\MainController@getsettori']);	
});


//routing Auth
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
