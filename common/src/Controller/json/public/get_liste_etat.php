<?php
$statement = $bdd->prepare(
"select *
from ETAT");
$statement->execute();

$etat = Array();
while ( $obj = $statement->fetchObject() )  {
	
	array_push($etat,Array(

		"ref"=>$obj->ET_Ref,
		"intitule"=>$obj->ET_Intitule,

	));
}
return array("etat"=>$etat)
?>
