<?php

//~ require($entity_path.'Article.php');
require("../common/src/Entity/Article.php");

$action = @$parameters["action"];
$articleList = @$parameters["articles"];
$etat = intval(@$parameters["etat"]);
$refEmp = intval(@$parameters["refEmp"]);


/*calcul le code barre aléatoirement*/
$barcode_ts = sprintf("%s", round(microtime(true)*100));
$barcode1 = strrev( $barcode_ts );
$somme=0;
for($i=0; $i<12; $i++ ){
	$sep = $barcode1[$i];
	if ( ($i % 2) == 0) {// nbre pair
		$par = $sep*1;
	} else {// nbre impair
		$par = $sep*3;
	}

	$somme= $somme+$par;
  
}

$reste = $somme % 10;
if(!$reste)$barcode2=0; 
else $barcode2 = 10-$reste;

$barcode= $barcode1 . $barcode2;
//~ $_SESSION['barcode'] = intval($barcode);


switch ( $action ) {
	
	case "add":
		$statement = $bdd->prepare(
			//requete
		"insert into ARTICLE(AR_Ref, AR_Design, CA_Ref, AR_DateDernierEntretien, AR_DateDerniereReparation, AR_Sommeil, AR_DateCreation, EM_Ref, EM_RefRep)
		values(:ref, :design, :categorie, :dernierEntretien, :derniereRep, 0, NOW(), :base, :rep)");
		
		if(count($articleList)>0){
			$a=$articleList[0];
			$statement->bindParam(":ref", $barcode);
			$statement->bindParam(":design", $a["design"]);
			//~ $statement->bindParam(":etat", $a["etat"]);
			$statement->bindParam(":categorie", $a["categorie"]);
			$statement->bindParam(":dernierEntretien", $a["dateDernierEntretien"]);
			$statement->bindParam(":derniereRep", $a["dateDerniereReparation"]);
			$statement->bindParam(":base", $a["base"]);
			$statement->bindParam(":rep", $a["rep"]);
			$statement->execute();
		}
		
		break;
		
	case "duplic":
		$statement = $bdd->prepare(
			//requete
		"insert into ARTICLE(AR_Ref, AR_Design, AR_Etat, CA_Ref, AR_Sommeil, AR_DateCreation, EM_Ref, EM_RefRep)
		values(:ref, :design, :etat, :categorie, 0, NOW(), :base, :rep)");
		
		//~ foreach($articleList as $a){
			$barcode_ts = sprintf("%s", round(microtime(true)*100));
			$barcode1 = strrev( $barcode_ts );
			$somme=0;
			for($i=0; $i<12; $i++ ){
				$sep = $barcode1[$i];
				if ( ($i % 2) == 0) {// nbre pair
					$par = $sep*1;
				} else {// nbre impair
					$par = $sep*3;
				}

				$somme= $somme+$par;
			  
			}

			$reste = $somme % 10;
			$barcode2 = 10-$reste;

			$barcode= $barcode1 . $barcode2;
			if(count($articleList)>0){
			$a=$articleList[0]; 
			
			$statement->bindParam(":design", $a["design"]);
			$statement->bindParam(":ref", $barcode);
			$statement->bindParam(":etat", $a["etat"]);
			$statement->bindParam(":categorie", $a["categorie"]);
			$statement->bindParam(":base", $a["base"]);
			$statement->bindParam(":rep", $a["rep"]);
			
				$statement->execute();
				
		}
		break;
		
	case "del":
		$statement = $bdd->prepare(
			//requete
		"update ARTICLE
		set AR_Sommeil = 1
		where AR_Ref = :ref");
		
		foreach($articleList as $a){
		$statement->bindParam(":ref", $a["ref"]);
		$statement->execute();
		}
		
		break;
		
	case "edit":
		if(count($articleList>0)){
			$a=$articleList[0];
		
			//requete
			$statement = $bdd->prepare(
			"update ARTICLE
			set AR_Design = :design, CA_Ref = :categorie, AR_Etat = :etat, AR_DateDernierEntretien = :dernierEntretien, AR_DateDerniereReparation = :derniereRep, EM_Ref = :base, EM_RefRep = :rep
			where AR_Ref = :ref");
			
			$statement->bindParam(":ref", $a["ref"]);
			$statement->bindParam(":design", $a["design"]);
			$statement->bindParam(":etat", $a["etat"]);
			$statement->bindParam(":categorie", $a["categorie"]);
			$statement->bindParam(":dernierEntretien", $a["dernierEntretien"]);
			$statement->bindParam(":derniereRep", $a["derniereRep"]);
			$statement->bindParam(":base", $a["base"]);
			$statement->bindParam(":rep", $a["rep"]);
			
			$statement->execute();
		}
		
		break;
		
	case "updateSuivi":
		if(count($articleList>0)){
			$a=$articleList[0];
		
			//requete
			$statement = $bdd->prepare(
			"insert into SUIVI(AR_Ref, EM_Ref, ET_Ref, UT_Ref, SU_DateMouvement)
			values(:refArt, :refEmp, :refEtat, :utilisateur, NOW())");
			
			$statement->bindParam(":refArt", $a["ref"]);
			$statement->bindParam(":refEtat", $etat);
			$statement->bindParam(":utilisateur", $_SESSION['user']);
			
			
			if($refEmp!=null){
				$statement->bindParam(":refEmp", $refEmp);
			}else{
				$statement->bindParam(":refEmp", $a["bas"]);
			}
			
			
			$statement->execute();
		}
		
		break;
			
		
	
}

return array("debug" => $articleList);

?>
