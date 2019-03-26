<?php

//~ require($entity_path.'Utilisateur.php');
require("../common/src/Entity/Utilisateur.php");

$action = @$parameters["action"];
$userList = @$parameters["users"];


switch ( $action ) {
	
	case "add":
		$statement = $bdd->prepare(
			//requete
		"insert into UTILISATEUR(UT_Nom, UT_Prenom, UT_Mail, UT_Type, UT_Sommeil, UT_DateCreation, UT_Password)
		values(:nom, :prenom, :mail, :type, 0, NOW(), MD5(:pass))");
		
		if(count($userList)>0){
			$u=$userList[0];
			$pass = $u["nom"].'.'.$u["prenom"];
			
			//~ $mdp=password_hash($pass, PASSWORD_DEFAULT);
			$statement->bindParam(":mail", $u["mail"]);
			$statement->bindParam(":nom", $u["nom"]);
			$statement->bindParam(":prenom", $u["prenom"]);
			$statement->bindParam(":type", $u["type"]);
			$statement->bindParam(":pass", $pass);
			$statement->execute();
			}
			break;
		
	case "del":
		$statement = $bdd->prepare(
			//requete
		"update UTILISATEUR
		set UT_Sommeil = 1
		where UT_Ref = :ref");
		
		foreach($userList as $u){
		$statement->bindParam(":ref", $u["ref"]);
		$statement->execute();
		}
		
		break;
		
	case "edit":
		if(count($userList>0)){
			$u=$userList[0];
		
		//requete
		$statement = $bdd->prepare(
		"update UTILISATEUR
		set UT_Mail = :mail, UT_Nom = :nom, UT_Prenom = :prenom, UT_Type = :type
		where UT_Ref = :ref");
		
		$statement->bindParam(":ref", $u["ref"]);
		$statement->bindParam(":mail", $u["mail"]);
		$statement->bindParam(":nom", $u["nom"]);
		$statement->bindParam(":prenom", $u["prenom"]);
		$statement->bindParam(":type", $u["type"]);
		
		$statement->execute();
		}
		
		break;
			
		
	
}

return array("debug" => $userList);

//~ $statement = $bdd->prepare(
//~ "select *
//~ from UTILISATEUR");


//~ $statement->bindParam();

//~ $statement->execute();

//~ $res = Array();
//~ while ( $obj = $statement->fetchObject() )  {
	
	//~ array_push($res,Array(
		
		//~ "ref"=>$obj->UT_Ref,
		//~ "mail"=>$obj->UT_Mail,
		//~ "nom"=>$obj->UT_Nom,
		//~ "prenom"=>$obj->UT_Prenom,
		//~ "type"=>$obj->UT_Type,
		//~ "dateCreation"=>$obj->UT_DateCreation,
		//~ "mdp"=>$obj->UT_Password,
		//~ "som"=>$obj->UT_Sommeil,
		//~ "uniqld"=>$obj->UT_Uniqld,
		//~ "type_txt"=>( $obj->UT_Type == 10 ? "Admin" : "basic")
	//~ ));
		
	
//~ }
//~ return $res; 

?>
