<?php
/* ADMIN */
$refCateg = intval(@$parameters["refCateg"]);
$refArt = intval(@$parameters["refArt"]);
$where_categ = ($refCateg?" and (AR.CA_Ref = :refCateg or CA.CA_RefParent = :refCateg)":"");
$onlyCategorie = (@$parameters["onlyCategorie"]);
$triColonne = intval(@$parameters["classement"]);
if(!$triColonne) $triColonne = 3;
$ascendant = intval(@$parameters["ascendant"]);
$asc = 'asc';
if($ascendant==0) $asc = 'desc';

/*****requête pour effectuer la pagination*****/
$allArticle = $bdd->prepare('select * from ARTICLE where AR_Sommeil = 0');
$allArticle->execute();
$nbArticle = $allArticle->rowCount();
//~ $nbPage = $nbArticle / $articleParPage;
$depart = intval(@$parameters["depart"]);
$articleParPage = intval(@$parameters["articleParPage"]);

//~ $_SESSION['barcode'] = '8715946388625';

$articles = Array();
if(!$onlyCategorie){
	$statement = $bdd->prepare(
	"select AR.AR_Ref, AR.AR_Sommeil, AR.AR_Design,  AR.AR_DateCreation, AR.AR_DateDernierEntretien, AR.AR_DateDerniereReparation , AR.CA_Ref, AR.EM_RefRep, AR.EM_Ref, CA.CA_RefParent, base.EM_Intitule
	from ARTICLE AR
	left join EMPLACEMENT base on AR.EM_Ref = base.EM_Ref
	left join EMPLACEMENT rep on AR.EM_RefRep = rep.EM_Ref
	left join CATEGORIE CA on AR.CA_Ref = CA.CA_Ref
	where AR.AR_Sommeil = 0
	$where_categ
	order by $triColonne $asc
	limit $depart, $articleParPage
	
	");
	if ($refCateg) $statement->bindParam(":refCateg", $refCateg);
	//~ $statement->bindParam(":triColonne", $triColonne);
	$statement->execute();


	
	while ( $obj = $statement->fetchObject() )  {
		
		$statement2 = $bdd->prepare(
			"select AR.AR_Ref, AR.AR_Design, SU.SU_DateMouvement, EM.EM_Intitule, EM.EM_Type, EM.EM_Ref, ET.ET_Ref, ET.ET_Intitule, ET.ET_Color

			from ARTICLE AR
			left join SUIVI SU on SU.AR_Ref = AR.AR_Ref
			join EMPLACEMENT EM on EM.EM_Ref = SU.EM_Ref
			join ETAT ET on ET.ET_Ref = SU.ET_Ref

			where (SU.SU_DateMouvement,SU.AR_Ref) in (
			select max(SU_DateMouvement), AR_Ref
			from SUIVI
			group by AR_Ref)
			and AR.AR_Ref = :refArt

			group by AR.AR_Ref, AR.AR_Design");
			$statement2->bindParam(":refArt", $obj->AR_Ref);
			$statement2->execute();

			$dernierEmp = null;
			if( $obj2 = $statement2->fetchObject() )  {
				$color_ref = intval( $obj2->ET_Color);
				if ( $color_ref > count($color_list) ) $color_ref = 0;
				$color = $color_list[$color_ref];
				
				$dernierEmp = Array(
					
					"refArt"=>$obj2->AR_Ref,
					"design"=>$obj2->AR_Design,
					"dateMouv"=>$obj2->SU_DateMouvement,
					"intituleEmp"=>$obj2->EM_Intitule,
					"typeEmp"=>$obj2->EM_Type,
					"refEmp"=>$obj2->EM_Ref,
					"refEtat"=>$obj2->ET_Ref,
					"color"=>$color,
					"intituleEtat"=>$obj2->ET_Intitule,

				);
			}
		$_SESSION['barcode'] = intval($obj->AR_Ref);
		array_push($articles,Array(
			
			"ref"=>$obj->AR_Ref,
			"suivi"=>$dernierEmp,
			//~ "CB"=>Barcode::fpdf($im, $black, 150, 150, 0, "code128", $bj->AR_Ref, 2, 50);
			"design"=>$obj->AR_Design,
			"actif"=>$obj->AR_Sommeil,
			"dateCreation"=>DateConvert::SqlToText($obj->AR_DateCreation),
			"base"=>$obj->EM_Ref,
			"intituleBase"=>$obj->EM_Intitule,
			"rep"=>$obj->EM_RefRep,
			"categorie"=>$obj->CA_Ref,
			"dernierEntretien"=>$obj->AR_DateDernierEntretien,
			"derniereRep"=>$obj->AR_DateDerniereReparation
		));
			
			//~ if($obj->AR_DateDernierEntretien or $obj->AR_DateDerniereReparation instanceof \Date){
				//~ $obj->format('Y-m-d');
			//~ }
		
	}
}

/* categorie parent*/

$statement = $bdd->prepare(
"select ca1.CA_Ref as catRef, ca1.CA_Nom as catIntitule, ca2.CA_Ref as scatRef, ca2.CA_Nom as scatIntitule
from CATEGORIE ca1 left join CATEGORIE ca2 on ca2.CA_RefParent = ca1.CA_Ref and ca2.CA_Sommeil = 0
where ca1.CA_RefParent is null
and ca1.CA_Sommeil = 0
order by ca1.CA_Nom asc, ca2.CA_Nom");

$statement->execute();

$categorieParent = Array();
$currentCateg = null;
while ( $obj = $statement->fetchObject() )  {
	
	if($currentCateg != $obj->catRef)
		array_push($categorieParent,Array(

			"nom"=>$obj->catIntitule,
			"ref"=>$obj->catRef,

		));
	
	$currentCateg = $obj->catRef;
	if($obj->scatRef)
		array_push($categorieParent,Array(
		"nom"=>$obj->catIntitule . " > " . $obj->scatIntitule,
		"ref"=>$obj->scatRef

	));
}

/* categorie enfant*/
//~ $statement = $bdd->prepare(
//~ "select *
//~ from CATEGORIE
//~ where CA_RefParent is not null
//~ having CA_RefParent");

//~ $statement->execute();

//~ $categorie = Array();
//~ while ( $obj = $statement->fetchObject() )  {
	
	//~ array_push($categorie,Array(

		//~ "ref"=>$obj->CA_Ref,
		//~ "nom"=>$obj->CA_Nom,
		//~ "refParent"=>$obj->CA_RefParent,

	//~ ));
//~ }

$emplacement = Array();
$etat = Array();



//~ if(!$onlyCategorie){
/* etat */

$statement = $bdd->prepare(
"select *
from ETAT");
$statement->execute();

while ( $obj = $statement->fetchObject() )  {
	$color_ref = intval( $obj->ET_Color);
	if ( $color_ref > count($color_list) ) $color_ref = 0;
	$color = $color_list[$color_ref];
	
	array_push($etat,Array(
		"color"=>$color,
		"color_ref"=>$color_ref,
		"ref"=>$obj->ET_Ref,
		"intitule"=>$obj->ET_Intitule,

	));
}

/* emplacement base*/

$statement = $bdd->prepare(
"select em1.EM_Ref as empRef, 
			em1.EM_Intitule as empIntitule, 
			em2.EM_Ref as sempRef, 
			em2.EM_Intitule as sempIntitule
		
		from EMPLACEMENT em1 
			left join EMPLACEMENT em2 
				on em2.EM_RefParent = em1.EM_Ref 
				and em2.EM_Sommeil = 0 
				
		where em1.EM_RefParent is null
			and em1.EM_Sommeil = 0");
$statement->execute();
$emplacementParent = Array();
$currentEmp = null;
while ( $obj = $statement->fetchObject() )  {
	
	if($currentEmp != $obj->empRef)
		array_push($emplacementParent,Array(

			"intitule"=>$obj->empIntitule,
			"ref"=>$obj->empRef,

		));
	
	$currentEmp = $obj->empRef;
	if($obj->sempRef)
		array_push($emplacementParent,Array(
		"intitule"=>$obj->empIntitule . " > " . $obj->sempIntitule,
		"ref"=>$obj->sempRef

	));
}		



/*Classement par colonne*/
$colonne = array(
				array('nom'=> "","num_col" =>0, "sort_table" => false),
				array('nom'=> "Désignation","num_col" =>3, "sort_table" => true),
				array('nom' => "Etat", "num_col" =>2, "sort_table" => true),
				array('nom'=> "Emplacement actuel", "num_col" => 0, "sort_table" => false),
				array('nom'=> "Emplacement base", "num_col" => 12, "sort_table" => false),
				array('nom'=> "Actions", "num_col" => 0, "sort_table" => false));
				


return array("articles" =>$articles ,"etat" =>$etat, "categorieParent" =>$categorieParent, "emplacement" =>$emplacementParent, "colonne" =>$colonne, "nbArticles" => $nbArticle); 

?>
