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

	public function dashboard(Request $request){
		$confr=$request->input("confr");
		$periodo=$request->input("periodo");
		$funzionario=$request->input("funzionario");
		
		$periodo1=$request->input("periodo1");
		$funzionario1=$request->input("funzionario1");

		$ref_user=$this->id_user;
		if ($this->tipouser==1) $ref_user=$funzionario;
		
		$users=user::select('id','name')->orderBy('name')->get();
		$periodi=$this->periodi();
		$attivita_index=$this->attivita_index();
		$categorie=$this->cat_index();
		$settori=$this->settori();
		$schema=$this->schema($request,1);
		$schema1=$this->schema($request,2);

		return view('dashboard')->with('user',$this->user)->with('attivita_index', $attivita_index)->with('categorie',$categorie)->with('settori',$settori)->with('periodi',$periodi)->with('periodo',$periodo)->with('funzionario',$funzionario)->with('periodo1',$periodo1)->with('funzionario1',$funzionario1)->with('users',$users)->with("schema",$schema)->with("schema1",$schema1)->with('ref_user',$ref_user)->with('confr',$confr);
		
	}	
	
	public function schema($request,$tipo) {
		
		if (!$request->has("periodo")) return array();
		
		
		//$this->tipouser;

		$periodo=$request->input("periodo");
		$funzionario=$request->input("funzionario");
		$periodo1=$request->input("periodo1");
		$funzionario1=$request->input("funzionario1");
		if ($tipo==2) {
			if (strlen($periodo1)==0 && strlen($funzionario1)==0) return array();
			$periodo=$periodo1;
		}

		if ($this->tipouser==1 && strlen($funzionario)==0) return array();
		$ref_user=$this->id_user;

		if ($this->tipouser==1) {
			if ($tipo==1) $ref_user=$funzionario;
			if ($tipo==2) $ref_user=$funzionario1;
		}	

		$resp=array();
		if (substr($periodo,0,7)=="Globale" || ($ref_user=="all")) {
			$annoref=substr($periodo,7);
			
			$schemi=DB::table('schemi as s')
			->select('s.id','s.dele','s.id_categoria as id_cat','s.id_attivita','s.id_settore',DB::raw('SUM(valore) AS valore'))
			->where('periodo','like',"%$annoref%")
			->when($ref_user!="all", function ($schemi) use ($ref_user) {
				return $schemi->where('id_funzionario','=',$ref_user);
			})			
			->groupBy('id_categoria')
			->groupBy('id_attivita')
			->groupBy('id_settore')
			->get();
						
		} else {
			$schemi=DB::table('schemi as s')
			->select('s.id','s.dele','s.id_categoria as id_cat','s.id_attivita','s.id_settore','s.valore')
			->where('periodo','=',$periodo)
			->where('id_funzionario','=',$ref_user)
			->get();
		}
		
		foreach($schemi as $schema) {
			$id_cat=$schema->id_cat;
			$id_attivita=$schema->id_attivita;
			$id_settore=$schema->id_settore;
			$valore=$schema->valore;
			$resp[$id_cat][$id_attivita][$id_settore]=$valore;
		}
		return $resp;
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
		->orderBy('a.ordine')->get();
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


}
