<?php

$type = @$parameters["type"];
$whereChantier=($type==1?"order by empDateDebut desc":"");
$whereDepot=($type==0?"order by em1.EM_Intitule asc, em2.EM_Intitule":"");

$statement = $bdd->prepare(
"select em1.EM_Ref as empRef, em1.EM_Intitule as empIntitule, em1.EM_Type as empType, em1.EM_DateDebut as empDateDebut, em1.EM_DateFin as empDateFin, em2.EM_Ref as sempRef, em2.EM_Intitule as sempIntitule
from EMPLACEMENT em1 left join EMPLACEMENT em2 on em2.EM_RefParent = em1.EM_Ref and em2.EM_Sommeil = 0
where em1.EM_RefParent is null
and em1.EM_Sommeil = 0
and em1.EM_Type = :type
$whereChantier
$whereDepot");
$statement->bindParam(":type", $type);
$statement->execute();

$emplacementParent = Array();
// Variables pour stocker le dernier grand emplacement 
$currentEmp = null; 
$currentEmpIntitule = null;
$currentEmpType = null;
$currentDateDebut = null;
$currentDateFin = null;
while ( $obj = $statement->fetchObject() )  {
	// 
	if($currentEmp != $obj->empRef){
		if($currentEmp!=null)
			array_push($emplacementParent,Array(

				"intitule"=>$currentEmpIntitule,
				"ref"=>$currentEmp,
				"type"=>$currentEmpType,
				"dateDebutP"=>DateConvert::SqlToText($currentDateDebut),
				"dateFinP"=>DateConvert::SqlToText($currentDateFin),
				"dateDebut"=>$currentDateDebut,
				"dateFin"=>$currentDateFin,
				"semp"=>$semp

		));
		$semp=array(); 
	}
	$currentEmp = $obj->empRef;
	$currentEmpIntitule = $obj->empIntitule;
	$currentEmpType = $obj->empType;
	$currentDateDebut = $obj->empDateDebut;
	$currentDateFin = $obj->empDateFin;
	if($obj->sempRef)
		array_push($semp,Array(


			"intitule"=>$obj->sempIntitule,
			"ref"=>$obj->sempRef

		));
}
if($currentEmp!=null)
	array_push($emplacementParent,Array(

		"intitule"=>$currentEmpIntitule,
		"ref"=>$currentEmp,
		"type"=>$currentEmpType,
		"dateDebutP"=>DateConvert::SqlToText($currentDateDebut),
		"dateFinP"=>DateConvert::SqlToText($currentDateFin),
		"dateDebut"=>$currentDateDebut,
		"dateFin"=>$currentDateFin,
		"semp"=>$semp

));
return array("emplacementParent" => $emplacementParent);

?>
