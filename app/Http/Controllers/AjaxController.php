<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\schemi;
use App\Models\documenti;
use App\Models\documenti_cantiere;
use App\Models\documenti_utili;
use App\Models\documenti_azienda;
use App\Models\notifiche;
use App\Models\assegnazioni;
use DB;




class AjaxController extends Controller
{
	/*
	public function __construct() {
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
	}
	*/
	
	public function docincantiere(Request $request) {
		$id_cantiere=$request->input("id_cantiere");
		/*
		$path = "allegati/cantieri/$id_cantiere";
		$risp=array();
		foreach (glob("$path/*") as $filename) {
			//echo "$filename size " . filesize($filename) . "\n";
			$risp[]=$filename;
		}	
		echo json_encode($risp);
		*/
		$inforow = DB::table("documenti_cantiere as d")
		->select("d.id",'d.id_funzionario','u.name','d.created_at','d.filename','d.file_user','d.url_completo')
		->join ("users as u","d.id_funzionario","u.id")
		->where('id_cantiere', "=",$id_cantiere)
		->get();
		if (isset($inforow[0])) $inforow[0]->user_log=Auth::user()->id;
		echo json_encode($inforow);		
	}
	
	public function docinazienda(Request $request) {
		$id_azienda=$request->input("id_azienda");
		$id_a=$request->input("id_a");
		$id_a=hash("md5", $id_a);
		
		$inforow = DB::table("documenti_azienda as d")
		->select("d.id",'d.id_funzionario','u.name','d.created_at','d.filename','d.file_user','d.url_completo')
		->join ("users as u","d.id_funzionario","u.id")
		->where('id_azienda', "=",$id_azienda)
		->orWhere('id_azienda', "=",$id_a)
		->get();
		if (isset($inforow[0])) $inforow[0]->user_log=Auth::user()->id;
		echo json_encode($inforow);		
	}	
	

	function load_sc(Request $request) {
		$id_categ=$request->input("id_categ");

		$risp = DB::table("sotto_cat_doc_utili")
		->select('id','descrizione')
		->where('dele', "=",0)
		->where('id_categoria', "=",$id_categ)
		->orderBy('descrizione')
		->get();
		echo json_encode($risp);
		
	}
	
	function delerowcant(Request $request) {
		$id_doc=$request->input("id_doc");

		$doc = documenti_cantiere::find($id_doc);	
		$doc->delete();
		$doc_remove=$doc->url_completo;
		if (@unlink($doc_remove))
			$risp['status']="OK";
		else
			$risp['status']="KO";
		echo json_encode($risp);
		
	}

	function delerowazi(Request $request) {
		$id_doc=$request->input("id_doc");

		$doc = documenti_azienda::find($id_doc);	
		$doc->delete();
		$doc_remove=$doc->url_completo;
		if (@unlink($doc_remove))
			$risp['status']="OK";
		else
			$risp['status']="KO";
		echo json_encode($risp);
		
	}
	
	public function setvalue(Request $request) {

		$ref_user=$request->input("ref_user");
		$periodo=$request->input("periodo");
		$id_categoria=$request->input("id_categoria");
		$id_attivita=$request->input("id_attivita");

		/*
			12.07.2023
			Prima di questa data utilizzavo il campo 'valore' per tener
			traccia del 'numeratore' ossia un contatore dei documenti per periodo-id_categoria-id_attivita e quindi ad ogni inserimento o cancellazione aggiornavo questo campo che poi mostravo.
			Per dei problemi nelle query (soprattutto in caso di riepilogo con uso di SUM), ho preferito, per il conteggio, usare la join con documenti e contare i record restituiti (vedi MainController function schema)
		*/
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

	
	function update_doc(Request $request) {
		
		$ref_user=$request->input("ref_user");
		$periodo=$request->input("periodo");
		$id_categoria=$request->input("id_categoria");
		$id_attivita=$request->input("id_attivita");
		$id_settore=$request->input("id_settore");
		$azienda=$request->input("azienda");
		$filename=$request->input("filename");
		$file_user=$request->input("file_user");
		$cantiere_custom=$request->input("cantiere_custom");
		$url_completo="allegati/$ref_user/$periodo/$id_categoria/$id_attivita/$id_settore/$filename";
		
		///il documento inviato, popola anche i documenti azienda
		
		$azienda = trim(preg_replace('/\s+/', ' ', $azienda));
		//rimozione eventuali caratteri di troppo
		$id_a=hash("md5",$azienda);
		$sub="allegati/aziende/$id_a";
		@mkdir($sub);
		$url_azienda = "$sub/".$filename;
		copy ($url_completo,$url_azienda);
	
		$documenti = new documenti_azienda;
		$documenti->dele=0;
		$documenti->id_funzionario=$ref_user;
		$documenti->filename=$filename;
		$documenti->azienda=$azienda;
		$documenti->id_azienda=$id_a;
		$documenti->file_user=$file_user;
		$documenti->url_completo=$url_azienda;
		$documenti->cantiere_custom=$cantiere_custom;

		$documenti->save();		
		////////////////////////////////////////////////////

		
		$id_ref = DB::table("schemi")
		->select('id')
		->where('id_funzionario', "=",$ref_user)
		->where('periodo', "=",$periodo)
		->where('id_categoria', "=",$id_categoria)
		->where('id_attivita', "=",$id_attivita)
		->where('id_settore', "=",$id_settore)
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
		$schemi->id_settore=$id_settore;
		//per il campo 'valore' vedi nota scritta in function setvalue
		if (!isset($id_ref[0]->id)) 
			$schemi->valore=1;
		else
			$schemi->increment('valore');
		$schemi->save();
		$id_schema=$schemi->id;
		
		
		$documenti = new documenti;
		$documenti->dele=0;
		$documenti->periodo_data=date("Y-m-d");
		$documenti->periodo=$periodo;
		$documenti->id_funzionario=$ref_user;
		$documenti->id_categoria=$id_categoria;
		$documenti->id_attivita=$id_attivita;
		$documenti->id_settore=$id_settore;
		$documenti->filename=$filename;
		$documenti->file_user=$file_user;
		$documenti->azienda=$azienda;
		$documenti->url_completo=$url_completo;
		$documenti->id_schema=$id_schema;
		$documenti->save();
			
		////sistema notifiche	
		$id_log=Auth::user()->id;
		$assegnazioni = DB::table("assegnazioni")
		->select('id_user')
		->where('azienda', "=",$azienda)
		->get();
		if (isset($assegnazioni[0])) {
			$id_not=$assegnazioni[0]->id_user;
			if ($id_not!=$id_log) {
				$count = DB::table("notifiche")
				->where('id_user', "=",$id_not)
				->count();
				if ($count==0) {
					$noti = new notifiche;
					$noti->notifiche=1;
				}
				else {
					$notifiche = DB::table("notifiche")
					->where('id_user', "=",$id_not)
					->get();				
					$id=$notifiche[0]->id;
					$noti = notifiche::find($id);
					$noti->increment('notifiche');
				}
				$noti->id_user=$id_not;
				$noti->save();
			}			
			
		}
		
		$risp=array();

		$risp['status']="OK";
		$risp['esito']="insert";
		echo json_encode($risp);

	}
	

	function update_doc_cant(Request $request) {
		
		$ref_user=Auth::user()->id;	
		$filename=$request->input("filename");
		$file_user=$request->input("file_user");
		$id_cantiere=$request->input("id_cantiere");
		$url_completo="allegati/cantieri/$id_cantiere/$filename";

		
		$documenti = new documenti_cantiere;
		$documenti->dele=0;
		$documenti->id_funzionario=$ref_user;
		$documenti->id_cantiere=$id_cantiere;
		$documenti->filename=$filename;
		$documenti->file_user=$file_user;
		$documenti->url_completo=$url_completo;
		$documenti->save();
			
		$risp=array();

		$risp['status']="OK";
		$risp['esito']="insert";
		echo json_encode($risp);

	}	
	
	function update_doc_utili(Request $request) {
		
		$ref_user=Auth::user()->id;	
		$filename=$request->input("filename");
		$file_user=$request->input("file_user");
		$categ=$request->input("categ");
		$url_completo="allegati/documenti_utili/$filename";

		
		$documenti = new documenti_utili;
		$documenti->dele=0;
		$documenti->id_funzionario=$ref_user;
		$documenti->filename=$filename;
		$documenti->file_user=$file_user;
		$documenti->url_completo=$url_completo;
		$documenti->id_categoria=$categ;
		$documenti->save();
			
		$risp=array();

		$risp['status']="OK";
		$risp['esito']="insert";
		echo json_encode($risp);

	}		
	
	
	function update_file_azi(Request $request) {
		
		$ref_user=Auth::user()->id;	
		$filename=$request->input("filename");
		$file_user=$request->input("file_user");
		$id_azienda=$request->input("id_azienda");
		$azienda=$request->input("azienda");
		if (strlen($id_azienda)==0) $id_azienda = hash("md5", $azienda);
		$url_completo="allegati/aziende/$id_azienda/$filename";

		
		$documenti = new documenti_azienda;
		$documenti->dele=0;
		$documenti->id_funzionario=$ref_user;
		$documenti->filename=$filename;
		$documenti->azienda=$azienda;
		$documenti->id_azienda=$id_azienda;
		$documenti->file_user=$file_user;
		$documenti->url_completo=$url_completo;

		$documenti->save();
			
		$risp=array();

		$risp['status']="OK";
		$risp['esito']="insert";
		echo json_encode($risp);

	}			
	
	function inforow(Request $request) {
		$ref_user=$request->input("ref_user");
		$periodo=$request->input("periodo");
		$annoref="";
		if (substr($periodo,0,4)=="Glob") $annoref=substr($periodo,7,4);
		$id_categoria=$request->input("id_categoria");
		$id_attivita=$request->input("id_attivita");
		$id_settore=$request->input("id_settore");

		$inforow = DB::table("documenti")
		->select("id",DB::raw("DATE_FORMAT(documenti.periodo_data,'%d-%m-%Y') as periodo_data"),'id_funzionario','filename','file_user','url_completo','azienda')
		->when(strlen($ref_user)!=0 && $ref_user!="all", function ($inforow) use ($ref_user) {
			return $inforow->where('id_funzionario','=',$ref_user);
		})			
		->when(strlen($annoref)==0, function ($inforow) use ($periodo) {
			return $inforow->where('periodo','=',$periodo);
		})			
		->when(strlen($annoref)!=0, function ($inforow) use ($annoref) {
			return $inforow->where('periodo','like',"%$annoref%");
		})
		->where('id_categoria', "=",$id_categoria)
		->where('id_attivita', "=",$id_attivita)
		->where('id_settore', "=",$id_settore)
		->get();
		if (isset($inforow[0])) $inforow[0]->user_log=Auth::user()->id;		
		echo json_encode($inforow);
		
	}
	
	function delerow(Request $request) {
		$id_row=$request->input("id_row");
		$info_doc = DB::table("documenti")
		->select('id_funzionario','periodo','id_categoria','id_attivita','id_settore')
		->where('id', "=",$id_row)
		->get();
		//per decrementare il valore inserito nello schema
		//devo risalire, tramite id del doc, all'id dello schema
		//non direttamente ma con altre variabili
		
		//per il campo 'valore' vedi nota scritta in function setvalue
		$id_funzionario=$info_doc[0]->id_funzionario;
		$periodo=$info_doc[0]->periodo;
		$id_categoria=$info_doc[0]->id_categoria;
		$id_attivita=$info_doc[0]->id_attivita;
		$id_settore=$info_doc[0]->id_settore;

		$doc = DB::table("documenti")
		->where('id', "=",$id_row)
		->delete();

		$schema = DB::table("schemi")
		->select('id','valore')
		->where('id_funzionario', "=",$id_funzionario)
		->where('periodo', "=",$periodo)
		->where('id_categoria', "=",$id_categoria)
		->where('id_attivita', "=",$id_attivita)
		->where('id_settore', "=",$id_settore)
		->get();
		if (isset($schema[0]->id)) {
			if ($schema[0]->valore<=1) {
				$dele = DB::table("schemi")
				->where('id', "=",$schema[0]->id)
				->delete();				
			} else  {
				$schemi = schemi::find($schema[0]->id);
				$schemi->decrement('valore');
				$schemi->save();				
			} 
		}

		
		$risp['status']="OK";
		echo json_encode($risp);
	}

}
