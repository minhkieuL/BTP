<?php

require($entity_path.'Article.php');

$suivi = @$parameters["suivi"];

$refArt = @$parameters["refArt"];
$refEmp = @$parameters["refEmp"];
$refEtat = intval(@$parameters["refEtat"]);
//~ $refUt = @$parameters["refUt"];
//~ $refEmp = 31;

$statement = $bdd->prepare(
	//requete
"insert into SUIVI(AR_Ref, EM_Ref, ET_Ref, UT_Ref, SU_DateMouvement)
values(:refArt, :refEmp, :refEtat, :utilisateur, NOW())");

$statement2 = $bdd->prepare(
	//requete
"update ARTICLE set AR_lastEmp = :refEmp where AR_Ref = :refArt");

if(count($suivi)>0){
	$s=$suivi[0];
	$statement->bindParam(":refArt", $refArt);
	$statement->bindParam(":utilisateur", $_SESSION['user']);
	$statement2->bindParam(":refArt", $refArt);
	/*si on ne renseigne pas la ref de l'emplacement, la requete prend la refEmp du suivi renseigné normalement precedemment par l'utilisateur dans Emprunter / ChoixChantier*/
	if($refEmp!=null){
		$statement->bindParam(":refEmp", $refEmp);
		$statement2->bindParam(":refEmp", $refEmp);
	}else{
		$statement->bindParam(":refEmp", $s["refEmp"]);
		$statement2->bindParam(":refEmp", $s["refEmp"]);
	}
	//~ if($refEtat!=null){ //Si l'etat est null
		$statement->bindParam(":refEtat", $refEtat);
	//~ }else{
		//~ $statement->bindParam(":refEtat", $s["refEtat"]);
	//~ }
	//~ $statement->bindParam(":refUt", $refUt);
	$statement->execute();
	$statement2->execute();
}

return array("debug" => $suivi);

?>
