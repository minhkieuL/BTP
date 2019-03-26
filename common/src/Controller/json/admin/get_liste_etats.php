<?php



$statement = $bdd->prepare(
"
select *	
from ETAT
"
);
//~ $statement->bindParam(":refArt", $refArt, PDO::PARAM_STR);
$statement->execute();


//sleep(4);
$etats = Array();
while ( $obj = $statement->fetchObject() )  {
	
	$color_ref = intval( $obj->ET_Color);
	if ( $color_ref > count($color_list) ) $color_ref = 0;
	$color = $color_list[$color_ref];
	
	
	array_push($etats,Array(
		
		"ref"=>$obj->ET_Ref,
		"color"=>$color,
		"color_ref"=>$color_ref,
		"intitule"=>$obj->ET_Intitule,
		
		
	));
}




return array("etats" =>$etats, "colors"=>$color_list); 
?>
