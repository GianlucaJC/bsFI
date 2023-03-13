<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\schemi;
use DB;




class AjaxController extends Controller
{
	/*
	public function __construct() {
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
	}
	*/
	public function setvalue(Request $request) {

		$ref_user=$request->input("ref_user");
		$periodo=$request->input("periodo");
		$id_categoria=$request->input("id_categoria");
		$id_attivita=$request->input("id_attivita");

		$schema = DB::table("schemi")
		->select('id_settore','valore')
		->where('id_funzionario', "=",$ref_user)
		->where('periodo', "=",$periodo)
		->where('id_categoria', "=",$id_categoria)
		->where('id_attivita', "=",$id_attivita)
		->get();

		$risp=array();
		foreach($schema as $res) {
			$risp[$res->id_settore]=$res->valore;
		}
		echo json_encode($risp);
		
	}

	public function savedata(Request $request) {
		$ref_user=$request->input("ref_user");
		$periodo=$request->input("periodo");
		$id_categoria=$request->input("id_categoria");
		$id_attivita=$request->input("id_attivita");
		$dati=$request->input("dati");


		$risp=array();

		
		$risp['esito']="insert";
		foreach($dati as $k=>$v) {
			$id_ref = DB::table("schemi")
			->select('id')
			->where('id_funzionario', "=",$ref_user)
			->where('periodo', "=",$periodo)
			->where('id_categoria', "=",$id_categoria)
			->where('id_attivita', "=",$id_attivita)
			->where('id_settore', "=",$k)
			->get();
			if (!isset($id_ref[0]->id)) {
				$schemi = new schemi;
			}
			else  {
				$schemi = schemi::find($id_ref[0]->id);
			}	
			$schemi->dele=0;
			$schemi->periodo_data=date("Y-m-d");
			$schemi->periodo=$periodo;
			$schemi->id_funzionario=$ref_user;
			$schemi->id_categoria=$id_categoria;
			$schemi->id_attivita=$id_attivita;
			$schemi->id_settore=$k;
			$schemi->valore=$v;
			$schemi->save();
			
		}
		
		
		$risp['header']="OK";
		//$risp['id_s']=$id_s;
		echo json_encode($risp);
	}  

}
