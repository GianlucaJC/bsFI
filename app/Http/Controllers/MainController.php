<?php
//test
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\definizione_attivita;
use App\Models\categorie;
use App\Models\User;
use App\Models\schemi;
use App\Models\documenti;
use App\Models\documenti_utili;
use App\Models\aziende_custom;
use App\Models\assegnazioni;
use App\Models\notifiche;
use Illuminate\Support\Facades\Auth;

use DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class mainController extends Controller
{
private $id_user;
private $user;	
private $tipouser;

public function __construct()
	{
		$this->middleware('auth')->except(['index']);

		$this->middleware(function ($request, $next) {			
			$id=Auth::user()->id;
			$user = User::find($id);
			$this->id_user=$id;

			if ($user->hasRole('admin')) $this->tipouser=1;
			else $this->tipouser=0;
			$this->user = $user;
			return $next($request);
		});		
		
	}	

	public function dashboard(){
		$request=request();

		
		$confr=$request->input("confr");
		$periodo=$request->input("periodo");
		
		if ($request->input("periodo")==null) $periodo=$this->current_period();
		$per_sel="";
		if ($request->has("per_sel")) {
			$per_sel=$request->input("per_sel"); //in sidemenu
			if (!$request->has("periodo")) $periodo=$per_sel;
		}
		if ($request->has("periodo")) $per_sel=$periodo;

		
		$funzionario=$request->input("funzionario"); //in dashboard
		if ($request->input("funzionario")==null) $funzionario=Auth::user()->id;;
		$oper_sel=0;
		if ($request->has("oper_sel")) {
			$oper_sel=$request->input("oper_sel"); //in sidemenu
			if (!$request->has("funzionario")) $funzionario=$oper_sel;
		}
		
		if ($request->has("funzionario")) $oper_sel=$funzionario;
		
		$azienda=$request->input("azienda"); //in dashboard
		$azi_sel="";
		if ($request->has("azi_sel")) {
			$azi_sel=$request->input("azi_sel"); //in sidemenu
			if (!$request->has("azienda")) $azienda=$azi_sel;
		}	
		if ($request->has("azienda")) $azi_sel=$azienda;

		
			
		$periodo1=$request->input("periodo1");
		$funzionario1=$request->input("funzionario1");

		$num_noti=0;
		$ref_user=$this->id_user;
		if (strlen($ref_user)>0) {
			$notifiche=notifiche::select('notifiche')
			->where("id_user","=",$ref_user)
			->get();
			if (isset($notifiche[0])) {
				$num_noti=$notifiche[0]->notifiche;
			}	
		}
		//if ($this->tipouser==1) 
		$ref_user=$funzionario;



		




		$users=user::select('id','name')->orderBy('name')->get();
		$periodi=$this->periodi();
		$attivita_index=$this->attivita_index();
		$categorie=$this->cat_index();
		$settori=$this->settori();
		$schema=$this->schema($request,1,$funzionario,$azienda,$periodo);
		
		$schema1=$this->schema($request,2,$funzionario,$azienda,$periodo);

		return view('dashboard')->with('user',$this->user)->with('attivita_index', $attivita_index)->with('categorie',$categorie)->with('settori',$settori)->with('periodi',$periodi)->with('periodo',$periodo)->with('funzionario',$funzionario)->with('periodo1',$periodo1)->with('funzionario1',$funzionario1)->with('users',$users)->with("schema",$schema)->with("schema1",$schema1)->with('ref_user',$ref_user)->with('confr',$confr)->with("num_noti",$num_noti)->with('oper_sel',$oper_sel)->with('azi_sel',$azi_sel)->with('azienda',$azienda)->with('per_sel',$per_sel);
		
	}	
	
	public function schema($request,$tipo,$funzionario,$azienda,$periodo) {
		
		if (strlen($periodo)==0) return array();
		
		
		//$this->tipouser;

		
		

		$periodo1=$request->input("periodo1");
		$funzionario1=$request->input("funzionario1");
		if ($tipo==2) {
			if (strlen($periodo1)==0 && strlen($funzionario1)==0) return array();
			$periodo=$periodo1;
		}

		if ($this->tipouser==1 && strlen($funzionario)==0) return array();
		$ref_user=$this->id_user;

		//if ($this->tipouser==1) {
			if ($tipo==1) $ref_user=$funzionario;
			if ($tipo==2) $ref_user=$funzionario1;
		//}	

		$test=0;
		if ($test==1) DB::enableQueryLog();
		
		$resp=array();
		$annoref="";
		if (substr($periodo,0,7)=="Globale") $annoref=substr($periodo,7);

		$schemi=DB::table('schemi as s')
		->select('s.id','s.dele','s.id_categoria as id_cat','s.id_attivita','s.id_settore','s.valore')
		->join('documenti as d','d.id_schema','s.id');
		if (strlen($annoref)!=0)
			$schemi=$schemi->where('s.periodo','like',"%$annoref%");
		else
			$schemi=$schemi->where('s.periodo','=',$periodo);
		
		$schemi->when(strlen($azienda)!=0, function ($schemi) use ($azienda) {
			return $schemi->where('d.azienda','=',$azienda);
		})		
		->when($ref_user!="all", function ($schemi) use ($ref_user) {
			return $schemi->where('s.id_funzionario','=',$ref_user);
		});


		$schemi=$schemi->get();
		
		if ($test==1) {
			$queries = DB::getQueryLog();
			print_r($queries);
			
		}		
		
		
		foreach($schemi as $schema) {
			$id_cat=$schema->id_cat;
			$id_attivita=$schema->id_attivita;
			$id_settore=$schema->id_settore;
			$valore=$schema->valore;

			if (!isset($resp[$id_cat][$id_attivita][$id_settore]))
				$resp[$id_cat][$id_attivita][$id_settore]=1;
			else
				$resp[$id_cat][$id_attivita][$id_settore]++;
		}
		return $resp;
	}
	public function current_period() {
		$annocur=intval(date("Y"));
		$mesecur=intval(date("m"));
		if ($mesecur==1) $per="GEN";
		if ($mesecur==2) $per="FEB";
		if ($mesecur==3) $per="MAR";
		if ($mesecur==4) $per="APR";
		if ($mesecur==5) $per="MAG";
		if ($mesecur==6) $per="GIU";
		if ($mesecur==7) $per="LUG";
		if ($mesecur==8) $per="AGO";
		if ($mesecur==9) $per="SET";
		if ($mesecur==10) $per="OTT";
		if ($mesecur==11) $per="NOV";
		if ($mesecur==12) $per="DIC";
		$cur=$per.trim($annocur);
		return $cur;
	}
	
	public function periodi() {
		$periodi=$periodi=array();
		$annocur=intval(date("Y"));
		$mesecur=intval(date("m"));
		for ($anno=$annocur;$anno>=2022;$anno--) {
			$mese=$mesecur;
			if ($anno!=$annocur) $mese=12;
			$inizio=0;
			for ($sca=$mese;$sca>=1;$sca--) {
				if ($inizio==0 && $sca!=12) $periodi["Globale$anno"]="Globale$anno";
				if ($sca==1) $per="GEN";
				if ($sca==2) $per="FEB";
				if ($sca==3) $per="MAR";
				if ($sca==4) $per="APR";
				if ($sca==5) $per="MAG";
				if ($sca==6) $per="GIU";
				if ($sca==7) $per="LUG";
				if ($sca==8) $per="AGO";
				if ($sca==9) $per="SET";
				if ($sca==10) $per="OTT";
				if ($sca==11) $per="NOV";
				if ($sca==12) $per="DIC";
				$periodo=$per.trim($anno);
				$periodi[$periodo]=$periodo;
				if ($sca==12) {
					$periodi["Globale$anno"]="Globale$anno";
				}
				$inizio=1;
			}
		}
		
		
		return $periodi;
	}
	
	public function attivita_index() {
		$definizione_attivita=DB::table('definizione_attivita as a')
		->select('a.id','a.dele','a.descrizione','c.id as id_cat','c.categoria','a.ref_categoria')
		->join('categorie as c','a.ref_categoria','c.id')
		->where('a.dele', "=","0")
		->orderBy('c.id')
		->orderBy('a.ordine')
		->get();
		$resp=array();$sc=0;
		$id_old=0;
		foreach($definizione_attivita as $attivita) {
			$id_cat=$attivita->id_cat;
			if ($sc==0) $id_old=$id_cat;
			if ($id_old!=$id_cat) {$id_old=$id_cat;$sc=0;}

			$resp[$id_cat][$sc]['id_attivita']=$attivita->id;
			$resp[$id_cat][$sc]['descrizione']=$attivita->descrizione;

			$resp[$id_cat][$sc]['categoria']=$attivita->categoria;
			$sc++;
		}
		
		return $resp;
	}
	
	public function cat_index() {
		$table="categorie";
		$elenco = DB::table($table)
		->select('id','categoria')
		->where('dele', "=","0")
		->orderBy('ordine')->get();

		$cat=array();
		foreach($elenco as $res) {
			$cat[$res->id]=$res->categoria;
		}
		
		return $cat;
	}	
	
	public function get_settori_aziende() {
		$settori=$this->settori();
		$aziende_e=$this->get_aziende_e();
		$aziende_fissi=$this->get_aziende_fissi();
		$aziende_custom=$this->get_aziende_custom();
		$risp=array();
		$risp['settori']=$settori;
		$risp['aziende_e']=$aziende_e;
		$risp['aziende_fissi']=$aziende_fissi;
		$risp['aziende_custom']=$aziende_custom;
		echo json_encode($risp);
	}

	public function get_aziende_custom() {
		$table="aziende_custom";
		$elenco = DB::table($table)
		->select('azienda','id')
		->orderBy('azienda')->get();
		return $elenco;		
	}
	public function get_aziende_fissi() {
		$table="cpnl.iscritti";
		$elenco = DB::table($table)
		->select('azienda','partita_iva as id_fiscale')
		->where('provincia_fo', "=","FI")
		->groupBy('azienda')
		->orderBy('azienda')->get();
		return $elenco;		
	}

	public function get_aziende_e() {
		$table="anagrafe.t2_tosc_a";
		$elenco = DB::table($table)
		->select('denom as azienda','c2 as id_fiscale')
		//->where('attivi', "=","S")
		->whereRaw('LENGTH(denom) > ?', [0])
		->groupBy('azienda')
		->orderBy('azienda')->get();
		return $elenco;
	}
	

	
	public function getsettori() {
		$settori=$this->settori();
		echo json_encode($settori);
	}
	
	public function settori() {
		
		$elenco=array();

		$elenco[0]['settore']="Ed.IND";
		$elenco[0]['bcolor']="yellow";
		$elenco[0]['color']="black";
		$elenco[0]['dele']=0;
		
		$elenco[1]['settore']="Ed.COOP";
		$elenco[1]['bcolor']="yellow";
		$elenco[1]['color']="black";
		$elenco[1]['dele']=0;
		
		$elenco[2]['settore']="Ed.ART.";
		$elenco[2]['bcolor']="yellow";
		$elenco[2]['color']="black";
		$elenco[2]['dele']=0;
		
		$elenco[3]['settore']="LEGNO IND";
		$elenco[3]['bcolor']="green";
		$elenco[3]['color']="white";
		$elenco[3]['dele']=0;
		
		$elenco[4]['settore']="LEGNO PMI";
		$elenco[4]['bcolor']="green";
		$elenco[4]['color']="white";
		$elenco[4]['dele']=0;
		
		$elenco[5]['settore']="LEGNO ART";
		$elenco[5]['bcolor']="green";
		$elenco[5]['color']="white";
		$elenco[5]['dele']=0;
		
		$elenco[6]['settore']="MANUF.IND";
		$elenco[6]['bcolor']="orange";
		$elenco[6]['color']="white";
		$elenco[6]['dele']=0;
		
		$elenco[7]['settore']="MANUF.PMI";
		$elenco[7]['bcolor']="orange";
		$elenco[7]['color']="white";
		$elenco[7]['dele']=0;
		
		$elenco[8]['settore']="MANUF.ART";
		$elenco[8]['bcolor']="orange";
		$elenco[8]['color']="white";
		$elenco[8]['dele']=0;
		
		$elenco[9]['settore']="LAPID.IND";
		$elenco[9]['bcolor']="blueviolet";
		$elenco[9]['color']="white";
		$elenco[9]['dele']=0;
		
		$elenco[10]['settore']="LAPID.PMI";
		$elenco[10]['bcolor']="blueviolet";
		$elenco[10]['color']="white";
		$elenco[10]['dele']=0;
		
		$elenco[11]['settore']="LAPID.ART";
		$elenco[11]['bcolor']="blueviolet";
		$elenco[11]['color']="white";
		$elenco[11]['dele']=0;
		
		$elenco[12]['settore']="CEMENTO";
		$elenco[12]['bcolor']="blueviolet";
		$elenco[12]['color']="white";
		$elenco[12]['dele']=0;
		return $elenco;
	}
	
	public function documenti(Request $request) {
		$users=user::select('id','name')->orderBy('name')->get();
		
		//azzeramento notifiche 
			notifiche::where("id_user","=",$this->id_user)
			->update(['notifiche' =>0]);
		//
		$utenti=array();
		foreach ($users as $us) {
			$utenti[$us->id]=$us->name;
		}
		
		$definizione_attivita=DB::table('definizione_attivita as a')
		->select('a.id','a.descrizione')->get();
		
		$attivita=array();
		foreach ($definizione_attivita as $at) {
			$attivita[$at->id]=$at->descrizione;
		}
		
		$dele_contr=$request->input("dele_contr");
		
		if (strlen($dele_contr)!=0) {
			documenti::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}		
		
		$categorie=$this->cat_index();
		$settori=$this->settori();		
		
		if ($this->tipouser==1 || 1==1) {
			$documenti=DB::table('documenti as d')
			->select('d.*')
			->where('d.dele','=',0)
			->orderBy("created_at","desc")
			->get();
		} else {
			$aziende=assegnazioni::select("azienda")
			->where("id_user","=",$this->id_user)
			->get();
			
			$documenti = DB::table('documenti as d')
			->where('d.dele','=',0)
			->where(function($query) use($aziende){
				foreach ($aziende as $azienda) {
					$query->orWhere("azienda", '=', $azienda->azienda);
				}
			})->orWhere("id_funzionario", '=', $this->id_user)
			->orderBy("created_at","desc")->get();
			
		}
		

		return view('all_views/gestione/documenti')->with('utenti', $utenti)->with('documenti', $documenti)->with('attivita',$attivita)->with('categorie',$categorie)->with('settori',$settori);
	}
	

	public function documenti_utili(Request $request) {
		$users=user::select('id','name')->orderBy('name')->get();
		$utenti=array();
		foreach ($users as $us) {
			$utenti[$us->id]=$us->name;
		}
		$dele_contr=$request->input("dele_contr");
		
		if (strlen($dele_contr)!=0) {
			$doc = documenti_utili::find($dele_contr);	
			$doc->delete();
			$doc_remove=$doc->url_completo;
			@unlink($doc_remove);
		}		
			
		$documenti_utili = documenti_utili::select("*")
		->where('dele','=',0)
		->orderBy("created_at","desc")->get();

		return view('all_views/gestione/documenti_utili')->with('documenti_utili', $documenti_utili)->with('utenti',$utenti);
	}


	public function assegnazioni(Request $request) {
		$utenti=user::select('id','name')->orderBy('name')->get();

		//nuova azienda da associare
		$azienda=$request->input("azienda");
		$id_fiscale=$request->input("id_fiscale");
		$user_ass=$request->input("user_ass");
		$msg_err="";
		if (strlen($azienda)!=0 && strlen($user_ass)!=0) {
			$count=DB::table('assegnazioni as a')
			->select('a.id')
			->where('a.azienda', "=",$azienda)
			->count();
			if ($count==0) {
				$assegnazioni = new assegnazioni;
				$assegnazioni->dele=0;
				$assegnazioni->id_user=$user_ass;
				$assegnazioni->azienda=$azienda;
				$assegnazioni->id_fiscale=$id_fiscale;
				$assegnazioni->save();
			} else $msg_err="<b>Attenzione!</b><hr><i>L'azienda <b>$azienda</b> risulta già associata!</i>";
		}
		//


		//cancellazione azienda associata
		if ($request->has("az_dele")) {
			if ($request->input("idus_dele")!=null && $request->input("az_dele")!=null) {
				$az_dele=$request->input("az_dele");
				$idus_dele=$request->input("idus_dele");
				$assegnazioni=DB::table('assegnazioni')
				->where('azienda',"=",$az_dele)
				->where('id_user',"=",$idus_dele)
				->delete();

			}
		}	
		//
		
		$assegnazioni=DB::table('assegnazioni as a')
		->select('a.*')
		->orderBy('a.id_user')
		->orderBy('a.azienda')
		->get();
		$user_az=array();
		foreach($assegnazioni as $assegnazione) {
			$id_user=$assegnazione->id_user;
			$user_az[$id_user][]=$assegnazione->azienda;
		}
		$aziende_e=$this->get_aziende_e();
		$aziende_fissi=$this->get_aziende_fissi();	

		return view('all_views/gestione/assegnazioni')->with('utenti', $utenti)->with('assegnazioni',$assegnazioni)->with('user_az',$user_az)->with('aziende_e',$aziende_e)->with('aziende_fissi',$aziende_fissi)->with('msg_err',$msg_err);
	}


}
