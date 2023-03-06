<?php
//test
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;


class mainController extends Controller
{
public function __construct()
	{
		//$this->middleware('auth')->except(['index']);
	}	
    public function exportUsers(Request $request){
		//la classe è in app/exports/
        return Excel::download(new ExportUser, 'users.xlsx');
    }
	
	public function check_scadenze_contratti() {
		$today=date("Y-m-d");
		$scadenze=candidati::select('nominativo','data_fine')
		->where("data_fine","<=", $today)
		->where(function ($scadenze) {
			$scadenze->where("notif_contr_web","=", null)
			->orWhere("notif_contr_web","<>", 1);
		})
		->get();
		
		$up=candidati::select('nominativo','data_fine')
		->where("data_fine","<=", $today)
		->where(function ($up) {
			$up->where("notif_contr_web","=", null)
			->orWhere("notif_contr_web","<>", 1);
		})
		->update(['notif_contr_web' => 0]);
		
		return $scadenze;
	}


}
