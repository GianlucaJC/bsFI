<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\definizione_attivita;
use App\Models\categorie;
use DB;

class ControllerArchivi extends Controller
{
	public function definizione_attivita(Request $request){
		$edit_elem=0;
		if ($request->has("edit_elem")) $edit_elem=$request->input("edit_elem");
		$view_dele=$request->input("view_dele");
		$categ=$request->input("categ");
		$descr_contr=$request->input("descr_contr");
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");


		//Creazione nuovo elemento
		if (strlen($descr_contr)!=0 && $edit_elem==0) {
			$def = new definizione_attivita;
			$descr_contr=strtoupper($descr_contr);
			$arr=array();
			$def->dele=0;
			$def->ordine=0;
			$def->descrizione=$descr_contr;
			$def->ref_categoria=$categ;
			$def->save();
			$id_def=$def->id;
			$ordine=$id_def*100;
			definizione_attivita::where('id', $id_def)
			  ->update(['ordine' => $ordine]);			
		}
		
		//Modifica elemento
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			$descr_contr=strtoupper($descr_contr);
			definizione_attivita::where('id', $edit_elem)
			  ->update(['descrizione' => $descr_contr,'ref_categoria' => $categ]);
		}
		if (strlen($dele_contr)!=0) {
			definizione_attivita::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			definizione_attivita::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		
		$categorie=categorie::select("id","categoria")
		->where("dele","=",0)
		->orderBy('ordine')
		->get();
		
		$definizione_attivita=DB::table('definizione_attivita as a')
		->select('a.id','a.dele','a.descrizione','c.categoria','a.ref_categoria')
		->join('categorie as c','a.ref_categoria','c.id')
		->when($view_dele=="0", function ($definizione_attivita) {
			return $definizione_attivita->where('a.dele', "=","0");
		})
		->orderBy('c.ordine')
		->orderBy('a.ordine')
		->get();

		return view('all_views/gestione/definizione_attivita')->with('definizione_attivita', $definizione_attivita)->with("view_dele",$view_dele)->with("categorie",$categorie);
		
	}
}
