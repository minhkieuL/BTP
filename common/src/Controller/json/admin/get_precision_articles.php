<?php
/* admin */
$refArt =@$parameters["refArt"];
if(!$refArt) $refArt=1;

/*****requête pour effectuer la pagination du module de suivi*****/

//~ $allSuivi = $bdd->prepare('select * from SUIVI where AR_Ref = :refArt');
//~ $allSuivi->bindParam(":refArt", $refArt);
//~ $allSuivi->execute();
//~ $nbSuivi = $allSuivi->rowCount();
//~ $nbPage = $nbSuivi / $suiviParPage;

$depart = intval(@$parameters["depart"]);
$suiviParPage = intval(@$parameters["suiviParPage"]);


$statement = $bdd->prepare(
"
select EM1.EM_Intitule as 'EmplacementEnfant', 
	EM2.EM_Intitule as 'EmplacementParent', 
	SU.AR_Ref,
	SU.SU_DateMouvement,
	ET.ET_Ref,
	ET.ET_Intitule,
	UT.UT_Ref,
	UT.UT_Nom,
	UT.UT_Prenom
	
	 
	
from SUIVI SU 
	left join UTILISATEUR UT on UT.UT_Ref = SU.UT_Ref
	left join EMPLACEMENT EM1 on EM1.EM_Ref = SU.EM_Ref 
	left join EMPLACEMENT EM2 on EM2.EM_Ref = EM1.EM_RefParent
	left join ETAT ET on ET.ET_Ref = SU.ET_Ref 
	
where SU.AR_Ref = :refArt
order by SU.SU_DateMouvement DESC
limit $depart, $suiviParPage"
);
$statement->bindParam(":refArt", $refArt, PDO::PARAM_STR);
$statement->execute();


//sleep(4);
$suivi = Array();
while ( $obj = $statement->fetchObject() )  {
	
	array_push($suivi,Array(
		
		"ref"=>$obj->AR_Ref,
		//~ "nbSuivi"=>$obj->nbSuivi,
		"dateMouv"=>DateConvert::SqlToFullDate($obj->SU_DateMouvement),
		"intitule"=> ($obj->EmplacementParent ? "{$obj->EmplacementParent} > " : "") . $obj->EmplacementEnfant,
		//~ "refEmp"=>$obj->EM_Ref,
		"refEtat"=>$obj->ET_Ref,
		"intituleEtat"=>$obj->ET_Intitule,
		"refUt"=>$obj->UT_Ref,
		"nomUt"=>$obj->UT_Nom,
		"prenomUt"=>$obj->UT_Prenom,
		
		
	));
}




return array("suivi" =>$suivi); 
?>
