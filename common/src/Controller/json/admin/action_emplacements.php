<?php
//~ require($entity_path.'Emplacement.php');
require("../common/src/Entity/Emplacement.php");
$refParent = intval(@$parameters["refParent"]);
$type = intval(@$parameters["typeEmp"]);

$action = @$parameters["action"];
$emplacementList = @$parameters["emplacements"];

switch ( $action ) {
	
	case "add":
		$statement = $bdd->prepare(
			//requete
		"insert into EMPLACEMENT(EM_Intitule, EM_RefParent, EM_Type, EM_DateDebut, EM_DateFin, EM_Sommeil)
		values(:intitule, :refParent, :type, :dateDebut, :dateFin, 0)");
		if(count($emplacementList)>0){
			$e=$emplacementList[0];
			
			$statement->bindParam(":intitule", $e["intitule"]);
			$statement->bindParam(":dateDebut", $e["dateDebut"]);
			$statement->bindParam(":dateFin", $e["dateFin"]);
			if(!$refParent) $refParent=null;
			$statement->bindParam(":refParent", $refParent);
			$statement->bindParam(":type", $type);
			$statement->execute();
		}
		break;
		
		
	case "del":
		if(count($emplacementList>0)){
			$e=$emplacementList[0];
			
			$statement = $bdd->prepare(
				//requete
			"update EMPLACEMENT
			set EM_Sommeil = 1
			where EM_Ref = :ref");
			
				
			$statement->bindParam(":ref", $e["ref"]);
			$statement->execute();
			
			}
			break;
		
	case "edit":
		if(count($emplacementList>0)){
			$e=$emplacementList[0];
		
			//requete
			$statement = $bdd->prepare(
			"update EMPLACEMENT
			set EM_Intitule = :intitule, EM_DateDebut = :dateDebut, EM_DateFin = :dateFin
			where EM_Ref = :ref");
			
			$statement->bindParam(":ref", $e["ref"]);
			$statement->bindParam(":intitule", $e["intitule"]);
			$statement->bindParam(":dateDebut", $e["dateDebut"]);
			$statement->bindParam(":dateFin", $e["dateFin"]);
			
			$statement->execute();
		}
		
		break;
			
		
	
}

return array("debug" => $emplacementList);

?>
