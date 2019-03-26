<?php
require($entity_path.'Categorie.php');
$refParent = intval(@$parameters["refParent"]);

$action = @$parameters["action"];
$categorieList = @$parameters["categories"];

switch ( $action ) {
	
	case "add":
		$statement = $bdd->prepare(
			//requete
		"insert into CATEGORIE(CA_Nom, CA_RefParent, CA_Sommeil)
		values(:nom, :refParent, 0)");
		if(count($categorieList)>0){
			$c=$categorieList[0];
			
			$statement->bindParam(":nom", $c["nom"]);
			if(!$refParent) $refParent=null;
			$statement->bindParam(":refParent", $refParent);
			$statement->execute();
		}
		break;
		
		
	case "del":
		if(count($categorieList>0)){
			$c=$categorieList[0];
			
			$statement = $bdd->prepare(
				//requete
			"update CATEGORIE
			set CA_Sommeil = 1
			where CA_Ref = :ref");
			
				
			$statement->bindParam(":ref", $c["ref"]);
			$statement->execute();
			
			}
			break;
		
	case "edit":
		if(count($categorieList>0)){
			$c=$categorieList[0];
		
			//requete
			$statement = $bdd->prepare(
			"update CATEGORIE
			set CA_Nom = :nom
			where CA_Ref = :ref");
			
			$statement->bindParam(":ref", $c["ref"]);
			$statement->bindParam(":nom", $c["nom"]);
			
			$statement->execute();
		}
		
		break;
			
		
	
}

return array("debug" => $categorieList);

?>
?>
