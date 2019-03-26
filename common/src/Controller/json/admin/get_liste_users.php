<?php
// echo "DOO";

$triColonne = intval(@$parameters["classement"]);
if(!$triColonne) $triColonne = 2;
$ascendant = intval(@$parameters["ascendant"]);
$asc = 'asc';
if($ascendant==0) $asc = 'desc';

$userParPage = intval(@$parameters["userParPage"]);
$allUser = $bdd->prepare('select * from UTILISATEUR where UT_Sommeil = 0');
$allUser->execute();
$nbUser = $allUser->rowCount();
//~ $nbPage = $nbUser / $userParPage;
$depart = intval(@$parameters["depart"]);


$statement = $bdd->prepare(
"select UT_Mail, UT_Nom, UT_Prenom, UT_Type, UT_Sommeil, UT_DateCreation, UT_Ref
from UTILISATEUR
where UT_Sommeil = 0
order by $triColonne $asc
limit $depart, $userParPage");


//~ $statement->bindParam();

$statement->execute();

$users = Array();
while ( $obj = $statement->fetchObject() )  {
	
	array_push($users,Array(
		
		"ref"=>$obj->UT_Ref,
		"mail"=>$obj->UT_Mail,
		"nom"=>$obj->UT_Nom,
		"prenom"=>$obj->UT_Prenom,
		"type"=>$obj->UT_Type,
		"dateCreation"=>$obj->UT_DateCreation,
		"som"=>$obj->UT_Sommeil,
		
	));
		
	
}



$statement = $bdd->prepare(
"select *
from COMPTE");


//~ $statement->bindParam();

$statement->execute();

$typeCompte = Array();
while ( $obj = $statement->fetchObject() )  {
	
	array_push($typeCompte,Array(

		"ref"=>$obj->CO_Ref,
		"intitule"=>$obj->CO_Intitule,

	));
}

/*Classement par colonne*/
$colonne = array(
				array('nom'=> "","num_col" =>null, "sort_table" => false),
				array('nom'=> "Fonction","num_col" =>4, "sort_table" => false),
				array('nom' => "Nom", "num_col" =>2, "sort_table" => true),
				array('nom'=> "Prénom", "num_col" => 3, "sort_table" => true),
				array('nom'=> "Courriel", "num_col" => 1, "sort_table" => true));
				
return array("users" =>$users , "typeCompte" =>$typeCompte, "colonne" =>$colonne, "nbUsers" => $nbUser); 

?>
