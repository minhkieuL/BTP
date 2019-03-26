<?php

$ref = @$parameters["ref"];
$color_ref = @$parameters["color_ref"];
$intitule = @$parameters["intitule"];

if ( ! $ref ) return Array("errno"=>1);

$statement =  $bdd->prepare(
"update ETAT 
set ET_Intitule = :intitule, 
ET_Color = :color_ref 
where ET_Ref = :ref"
);
$statement->bindParam(":intitule", $intitule);
$statement->bindParam(":color_ref", $color_ref);
$statement->bindParam(":ref", $ref);

$statement->execute();
	
return Array("errno"=>0);
