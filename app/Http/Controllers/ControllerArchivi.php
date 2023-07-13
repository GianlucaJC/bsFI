<?php

namespace App\Http\Controllers;
use App\Http\Controllers\mainController;
use Illuminate\Http\Request;
use App\Models\definizione_attivita;
use App\Models\categorie;
use App\Models\categorie_doc_utili;
use App\Models\aziende_custom;
use App\Models\user;

use DB;

class ControllerArchivi extends Controller
{
	public function __construct()	
	{
		$this->middleware('auth')->except(['index']);
	}		

	public function aziende(Request $request) {
		$dele_azienda=$request->input("dele_azienda");
		$msg_err="";
		if (strlen($dele_azienda)>0) {
			$az_dele=$request->input("az_dele");
			$table="assegnazioni";
			$count = DB::table($table)
			->where('azienda',"=",$az_dele)
			->count();
			$dele=false;
			if ($count==0) {
				$table="documenti";
				$count = DB::table($table)
				->where('azienda',"=",$az_dele)
				->count();
				if ($count==0) {
					$dele=true;
					aziende_custom::where('id', $dele_azienda)->delete();
				}	
			}
			if ($dele==false)
				$msg_err="<b>Attenzione!</b><hr><i>L'azienda risulta movimentata nei documenti o nelle assegnazioni e non può essere cancellata. (Eventualmente consultare il SuperAdministror)</i>";
			
		}
		$azienda_def=$request->input("azienda_def");
		if (strlen($azienda_def)!=0) {
			$table="aziende_custom";
			$count = DB::table($table)
			->where('azienda',"=",$azienda_def)
			->count();	
			if ($count>0)	
				$msg_err="<b>Attenzione!</b><hr><i>L'azienda risulta già inserita tramite questa procedura!</i>";
			else {
				$azienda_def=strtoupper($azienda_def);
				$azienda = new aziende_custom;
				$azienda->dele=0;
				$azienda->azienda=$azienda_def;
				$azienda->save();
			}	
				
		}
		$aziende_e = (new mainController)->get_aziende_e();
		$aziende_fissi = (new mainController)->get_aziende_fissi();
		$aziende_custom = (new mainController)->get_aziende_custom();

		return view('all_views/gestione/aziende')->with('aziende_e',$aziende_e)->with('aziende_fissi',$aziende_fissi)->with('aziende_custom',$aziende_custom)->with('msg_err',$msg_err);
	}

	public function definizione_utenti(Request $request){

		$user_abilita=$request->input("user_abilita");
		$dele_contr=$request->input("dele_contr");

		//assegnazione tipo profilo
		$edit_elem=0;
		if ($request->has("edit_elem")) $edit_elem=$request->input("edit_elem");
		
		if ($edit_elem!=0) {
			$profilo_user=$request->input("profilo_user");
			DB::table("model_has_roles")
			->where('model_id', $edit_elem)
			->update(['role_id' => $profilo_user]);
			
		}
		
		//abilitazione servizio
		if (strlen($user_abilita)!=0) {
			$us=DB::table('online.db as db')
			->select('db.n_tessera','db.pin','db.n_tessera','db.utentefillea')
			->where('db.id',"=",$user_abilita)
			->get();
			if (isset($us[0]->n_tessera)) {
				$n_us = new user;
				$n_us->name=$us[0]->utentefillea;
				$n_us->email=$us[0]->n_tessera;
				$n_us->password=bcrypt($us[0]->pin);
				$n_us->save();
				$new_id=$n_us->id;
				$rowData['role_id']=3;
				$rowData['model_type']="App\Models\User";
				$rowData['model_id']=$new_id;
				DB::table("model_has_roles")->insert($rowData);				
			}	
		}
		//disabilitazione servizio
		if (strlen($dele_contr)!=0) {
			user::where('id', $dele_contr)->delete();
			$model=DB::table('model_has_roles')
			->where('model_id',"=",$dele_contr)->delete();
		}
		
		$definizione_utenti=DB::table('online.db as db')
		->select('db.id','u.id as idu','db.attiva','db.n_tessera','db.utentefillea','r.role_id')
		->leftjoin('bsfi.users as u','db.n_tessera','u.email')
		->leftjoin('bsfi.model_has_roles as r','u.id','r.model_id')
		->where('db.id_prov_associate','=',66)
		->where('attiva','=',1)
		->orderBy('db.n_tessera')
		->get();

		return view('all_views/gestione/definizione_utenti')->with('definizione_utenti', $definizione_utenti);
	}

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
	

	public function categorie_documenti(Request $request){
		$edit_elem=0;
		if ($request->has("edit_elem")) $edit_elem=$request->input("edit_elem");
		$view_dele=$request->input("view_dele");
		$categ=$request->input("categ");
		$descr_contr=$request->input("descr_contr");
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");


		//Creazione nuovo elemento
		if (strlen($descr_contr)!=0 && $edit_elem==0) {
			$cat = new categorie_doc_utili;
			$descr_contr=strtoupper($descr_contr);
			$cat->dele=0;
			$cat->descrizione=$descr_contr;
			$cat->save();
		}
		
		//Modifica elemento
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			$descr_contr=strtoupper($descr_contr);
			categorie_doc_utili::where('id', $edit_elem)
			  ->update(['descrizione' => $descr_contr]);
		}
		if (strlen($dele_contr)!=0) {
			categorie_doc_utili::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			categorie_doc_utili::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		
		$categ=DB::table('categorie_doc_utili as c')
		->select('c.id','c.dele','c.descrizione as categoria')
		->when($view_dele=="0", function ($categ) {
			return $categ->where('c.dele', "=","0");
		})
		->orderBy('c.descrizione')		
		->get();
		
		$cat_doc_utili=DB::table('categorie_doc_utili as c')
		->select('c.id','c.dele','c.descrizione as categoria')
		->when($view_dele=="0", function ($cat_doc_utili) {
			return $cat_doc_utili->where('c.dele', "=","0");
		})
		->orderBy('c.descrizione')
		->get();

		$data=array('cat_doc_utili'=>$cat_doc_utili,"view_dele"=>$view_dele,"categ"=>$categ);

		return view('all_views/gestione/categorie_documenti')->with($data);
		
	}	
	
}
