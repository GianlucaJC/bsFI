@php 
	global $utenti;
	global $user_az;
	global $info_cantieri;
	global $user;
@endphp
<?php
use App\Models\User;

$id = Auth::user()->id;
$user = User::find($id);
$utenti=user::select('id','name','email')->orderBy('name')->get();
$tessere=array();
foreach($utenti as $info) {
	$tessere[]=strtoupper($info->email);
}

$today=date("Y-m-d");

/*
//Metodo nativo per attingere alle assegnazioni dei cantieri tramite join fra tabelle
$cantieri=DB::table('filleago.aziende_segnalazioni as a')
->join('filleago.segnalazioni as s','s.id','a.id_segnalazione')
->leftjoin('filleago.aziende as d','a.id_azienda','d.p_iva')
->select('a.utente','a.denominazione','a.id_azienda','s.indirizzo_c')
->whereIn('a.utente',$tessere)
->where(function ($cantieri) use ($today) {
	$cantieri->whereNull('s.fine_lavori')
	->orWhere("s.fine_lavori",">=", $today);
})
->groupBy('s.id')
->get();
*/


//Metodo alternativo (diretto) per attingere alle assegnazioni dei cantieri senza join ma tramite una tabella ad hoc sincronizzata con le altre (in sede di assegnazioni/revoche dei cantieri agli operatori in filleago)
$cantieri=DB::table('filleago.assegnazioni_rendiconta as a')
->select('a.id_user as utente','a.azienda as denominazione','a.id_azienda','a.indirizzo_c')
->whereIn('a.id_user',$tessere)
->where(function ($cantieri) use ($today) {
	$cantieri->whereNull('a.data_fine_lavori')
	->orWhere("a.data_fine_lavori",">=", $today);
})
->get();


$info_cantieri=array();
foreach ($cantieri as $cantiere) {
	if (!isset($info_cantieri[$cantiere->utente])) $indice=0;
	else $indice=count($info_cantieri[$cantiere->utente]);
	
	$info_cantieri[$cantiere->utente][$indice]['azienda']=$cantiere->denominazione;
	$info_cantieri[$cantiere->utente][$indice]['id_azienda']=$cantiere->id_azienda;
	$info_cantieri[$cantiere->utente][$indice]['indirizzo_c']=$cantiere->indirizzo_c;
}

$assegnazioni=DB::table('assegnazioni as a')
->select('a.*')
->orderBy('a.id_user')
->orderBy('a.azienda')
->get();
$user_az=array();
$scx=0;$old_us=0;

foreach($assegnazioni as $assegnazione) {
	$id_user=$assegnazione->id_user;
	if ($old_us==0) $old_us=$id_user;
	if ($old_us!=$id_user) $scx=0;
	$user_az[$id_user][$scx]['azienda']=$assegnazione->azienda;
	$user_az[$id_user][$scx]['id_fiscale']=$assegnazione->id_fiscale;
	$scx++;
	$old_us=$id_user;
}
?>
<!--
<style>
	.nav-sidebar>.nav-item .nav-icon {
		margin-left: 0rem!important;
		font-size: 1.2rem;
		margin-right: 0rem!important;
		text-align: center;
		width: 1.6rem!important;
	}

	.sidebar-mini .main-sidebar .nav-link,
	.sidebar-mini-md .main-sidebar .nav-link,
	.sidebar-mini-xs .main-sidebar .nav-link {
		width: calc(300px - .5rem * 2)!important;
	}

	.sidebar-collapse.sidebar-mini .main-sidebar .sidebar {
		width: calc(300px - .5rem * 2)!important;
	}

	aside {
		width: 300px!important;
	}

	body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .content-wrapper,
	body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .main-header {
		transition: margin-left .3s ease-in-out;
		margin-left: 300px!important;
	}

	.layout-navbar-fixed .wrapper .brand-link {
		width: 300px!important;
	}

	.navbar-nav>.navbar-item>.navbar-link {
		display: none!important;
	}

</style>
!-->