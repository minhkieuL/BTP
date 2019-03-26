<?php
// récupérer POST[type]
$type = intval(@$parameters["type"]);
//~ $ref = intval(@$parameters["ref"]);
//~ $where_ref = ($ref?" and (EM_Ref = :ref )":"");
$whereType1 = ($type==1?" and (em1.EM_DateFin >= Now())":"");//condition date aujourdhui depassé pas affiché
$onlyWithArticles= (@$parameters["onlyWithArticles"]);

/*chantier actif*/
if ( $onlyWithArticles  && $type == 1) { // Présuppose le type 1 quand même
	$statement = $bdd->prepare("
		select em1.EM_Ref as empRef, 
			em1.EM_Intitule as empIntitule,
            count(*) as count
		from EMPLACEMENT em1 
        join ARTICLE ar on ar.AR_lastEmp = em1.EM_Ref
			
		where em1.EM_RefParent is null
			and em1.EM_Sommeil = 0
			and em1.EM_Type = :type
            
        group by em1.EM_Ref, em1.EM_Intitule
        ");
        $statement->bindParam(":type", $type);
		$statement->execute();
        
		/*Chantier  (puisqu'il n'existe pas de sous emp*/
		$emplacementParent = Array();
		while ( $obj = $statement->fetchObject() )  {

					array_push($emplacementParent,Array(

						"intitule"=>$obj->empIntitule,
						"ref"=>$obj->empRef,
						"count"=>$obj->count,

				));
				

		}
} 
/*Depot et chantier*/
else {
	//~ $statement = $bdd->prepare("
		//~ select em1.EM_Ref as empRef, 
			//~ em1.EM_Intitule as empIntitule, 
			//~ em2.EM_Ref as sempRef, 
			//~ em2.EM_Intitule as sempIntitule
		
		//~ from EMPLACEMENT em1 
			//~ left join EMPLACEMENT em2 
				//~ on em2.EM_RefParent = em1.EM_Ref 
				//~ and em2.EM_Sommeil = 0 
				//~ and em2.EM_Type = :type
				
		//~ where em1.EM_RefParent is null
			//~ and em1.EM_Sommeil = 0
			//~ and em1.EM_Type = :type
		//~ $whereType1
		
		//~ order by em1.EM_Intitule asc, em2.EM_Intitule
	//~ "); 
	//~ }

	 //~ $where_ref
	//~ $statement->bindParam(":type", $type);
	//~ if ($ref) $statement->bindParam(":ref", $ref);
	//~ $statement->execute();

	//~ /*Depot  (puisqu'il existe des sous emp*/
	//~ $emplacementParent = Array();
	//~ $currentEmp = null; 
	//~ $currentEmpIntitule = null;
	//~ while ( $obj = $statement->fetchObject() )  {
		//~ if($currentEmp != $obj->empRef){
			//~ if($currentEmp!=null)
				//~ array_push($emplacementParent,Array(

					//~ "intitule"=>$currentEmpIntitule,
					//~ "ref"=>$currentEmp,
					//~ "semp"=>$semp

			//~ ));
			//~ $semp=array(); 
		//~ }
		//~ $currentEmp = $obj->empRef;
		//~ $currentEmpIntitule = $obj->empIntitule;
		//~ if($obj->sempRef)
			//~ array_push($semp,Array(


				//~ "intitule"=>$obj->sempIntitule,
				//~ "ref"=>$obj->sempRef

			//~ ));
	//~ }
	//~ if($currentEmp!=null)
		//~ array_push($emplacementParent,Array(

			//~ "intitule"=>$currentEmpIntitule,
			//~ "ref"=>$currentEmp,
			//~ "semp"=>$semp

	//~ ));
	
	$statement = $bdd->prepare(
	"select em1.EM_Ref as empRef, 
			em1.EM_Intitule as empIntitule, 
			em2.EM_Ref as sempRef, 
			em2.EM_Intitule as sempIntitule
		
		from EMPLACEMENT em1 
			left join EMPLACEMENT em2 
				on em2.EM_RefParent = em1.EM_Ref 
				and em2.EM_Sommeil = 0 
				and em2.EM_Type = :type
				
		where em1.EM_RefParent is null
			and em1.EM_Sommeil = 0
			and em1.EM_Type = :type
		$whereType1
		
		order by em1.EM_Intitule asc, em2.EM_Intitule");
	$statement->bindParam(":type", $type);
	//~ if ($ref) $statement->bindParam(":ref", $ref);
	$statement->execute();

	$emplacementParent = Array();
	$currentEmp = null;
	while ( $obj = $statement->fetchObject() )  {
		
		if($currentEmp != $obj->empRef)
			array_push($emplacementParent,Array(

				"intitule"=>$obj->empIntitule,
				"ref"=>$obj->empRef,

			));
		
		$currentEmp = $obj->empRef;
		if($obj->sempRef)
			array_push($emplacementParent,Array(
			"intitule"=>$obj->empIntitule . " > " . $obj->sempIntitule,
			"ref"=>$obj->sempRef

		));
	}		
	
}

	

return array("emplacementParent" => $emplacementParent);
?>
