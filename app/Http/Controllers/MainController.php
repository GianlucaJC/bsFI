<?php
//test
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\definizione_attivita;
use App\Models\categorie;

use DB;


class mainController extends Controller
{
public function __construct()
	{
		//$this->middleware('auth')->except(['index']);
	}	

	public function dashboard(Request $request){
		
		$attivita_index=$this->attivita_index();
		$categorie=$this->cat_index();
		$settori=$this->settori();

		return view('dashboard')->with('attivita_index', $attivita_index)->with('categorie',$categorie)->with('settori',$settori);
		
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
	
	public function settori() {
		
		$elenco=array();

		$elenco[0]['settore']="Ed.IND";
		$elenco[0]['bcolor']="yellow";
		$elenco[0]['color']="black";
		
		$elenco[1]['settore']="Ed.COOP";
		$elenco[1]['bcolor']="yellow";
		$elenco[1]['color']="black";
		
		$elenco[2]['settore']="Ed.ART.";
		$elenco[2]['bcolor']="yellow";
		$elenco[2]['color']="black";
		
		$elenco[3]['settore']="LEGNO IND";
		$elenco[3]['bcolor']="green";
		$elenco[3]['color']="white";
		
		$elenco[4]['settore']="LEGNO PMI";
		$elenco[4]['bcolor']="green";
		$elenco[4]['color']="white";
		
		$elenco[5]['settore']="LEGNO ART";
		$elenco[5]['bcolor']="green";
		$elenco[5]['color']="white";
		
		$elenco[6]['settore']="MANUF.IND";
		$elenco[6]['bcolor']="orange";
		$elenco[6]['color']="white";
		
		$elenco[7]['settore']="MANUF.PMI";
		$elenco[7]['bcolor']="orange";
		$elenco[7]['color']="white";
		
		$elenco[8]['settore']="MANUF.ART";
		$elenco[8]['bcolor']="orange";
		$elenco[8]['color']="white";
		
		$elenco[9]['settore']="LAPID.IND";
		$elenco[9]['bcolor']="blueviolet";
		$elenco[9]['color']="white";
		
		$elenco[10]['settore']="LAPID.PMI";
		$elenco[10]['bcolor']="blueviolet";
		$elenco[10]['color']="white";
		
		$elenco[11]['settore']="LAPID.ART";
		$elenco[11]['bcolor']="blueviolet";
		$elenco[11]['color']="white";
		
		$elenco[12]['settore']="CEMENTO";
		$elenco[12]['bcolor']="blueviolet";
		$elenco[12]['color']="white";
		return $elenco;
	}


}
