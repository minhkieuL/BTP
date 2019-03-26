<?php
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
return array("categorieParent" => $categorieParent);

?>
