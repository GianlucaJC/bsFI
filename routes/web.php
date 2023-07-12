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

	Route::get('documenti', [ 'as' => 'documenti', 'uses' => 'App\Http\Controllers\MainController@documenti']);

	Route::post('documenti', [ 'as' => 'documenti', 'uses' => 'App\Http\Controllers\MainController@documenti'])
	->middleware(['role:admin']);

	Route::get('documenti_utili', [ 'as' => 'documenti_utili', 'uses' => 'App\Http\Controllers\MainController@documenti_utili']);

	Route::post('documenti_utili', [ 'as' => 'documenti_utili', 'uses' => 'App\Http\Controllers\MainController@documenti_utili']);

	Route::get('categorie_documenti', [ 'as' => 'categorie_documenti', 'uses' => 'App\Http\Controllers\ControllerArchivi@categorie_documenti']);

	Route::post('categorie_documenti', [ 'as' => 'categorie_documenti', 'uses' => 'App\Http\Controllers\ControllerArchivi@categorie_documenti']);


	Route::get('assegnazioni', [ 'as' => 'assegnazioni', 'uses' => 'App\Http\Controllers\MainController@assegnazioni'])
	->middleware(['role:admin']);

	Route::post('assegnazioni', [ 'as' => 'assegnazioni', 'uses' => 'App\Http\Controllers\MainController@assegnazioni'])
	->middleware(['role:admin']);


	Route::get('aziende', [ 'as' => 'aziende', 'uses' => 'App\Http\Controllers\ControllerArchivi@aziende'])
	->middleware(['role:admin']);
	
	Route::post('aziende', [ 'as' => 'aziende', 'uses' => 'App\Http\Controllers\ControllerArchivi@aziende'])
	->middleware(['role:admin']);


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
	Route::post('docincantiere', [ 'as' => 'docincantiere', 'uses' =>'App\Http\Controllers\AjaxController@docincantiere']);	
	Route::post('delerowcant', [ 'as' => 'delerowcant', 'uses' =>'App\Http\Controllers\AjaxController@delerowcant']);
	Route::post('update_doc_cant', [ 'as' => 'update_doc_cant', 'uses' =>'App\Http\Controllers\AjaxController@update_doc_cant']);


	Route::post('setvalue', [ 'as' => 'setvalue', 'uses' =>'App\Http\Controllers\AjaxController@setvalue']);	

	Route::post('savedata', [ 'as' => 'savedata', 'uses' =>'App\Http\Controllers\AjaxController@savedata']);

	Route::post('update_doc', [ 'as' => 'update_doc', 'uses' =>'App\Http\Controllers\AjaxController@update_doc']);

	Route::post('update_doc_utili', [ 'as' => 'update_doc_utili', 'uses' =>'App\Http\Controllers\AjaxController@update_doc_utili']);


	Route::post('inforow', [ 'as' => 'inforow', 'uses' =>'App\Http\Controllers\AjaxController@inforow']);

	Route::post('delerow', [ 'as' => 'delerow', 'uses' =>'App\Http\Controllers\AjaxController@delerow']);
	
	Route::post('getsettori', [ 'as' => 'getsettori', 'uses' =>'App\Http\Controllers\MainController@getsettori']);	
	Route::post('get_settori_aziende', [ 'as' => 'get_settori_aziende', 'uses' =>'App\Http\Controllers\MainController@get_settori_aziende']);	

});


//routing Auth
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
