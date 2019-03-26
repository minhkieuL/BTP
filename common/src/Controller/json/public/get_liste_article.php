<?php
// récupérer POST[type]
$emplacement =intval(@$parameters["emplacement"]);
$where_emp = ($emplacement?" and (SU.EM_Ref = :emplacement)":"");
$categ =intval(@$parameters["categ"]);
$where_categ = ($categ?" and (CA1.CA_Ref = :categ or CA2.CA_Ref = :categ)":"");
$refArt =(@$parameters["refArt"]);
$typeEmp =(@$parameters["typeEmp"]);
$where_refArt = ($refArt?" and (AR.AR_Ref = :refArt )":"");
$where_need = ($typeEmp?" and 
(
	(SU.SU_DateMouvement,SU.AR_Ref) in (
	select max(SU_DateMouvement), AR_Ref
	from SUIVI
	group by AR_Ref
	) 
)":"");

//~ $emplacement = 1;
//~ $categ = 2;


//~ return Array($emplacement, $categ);

$statement = $bdd->prepare(
"select AR.AR_Ref, AR.AR_Design, CA1.CA_Nom, CA2.CA_Nom, SU.SU_DateMouvement, rep.EM_Ref, rep.EM_Intitule, EM.EM_Intitule, base.EM_Ref, base.EM_Intitule, EM.EM_Type, AR.AR_dateCreation, AR.AR_DateDernierEntretien, AR.AR_DateDerniereReparation, CA1.CA_Ref, CA2.CA_Ref

from ARTICLE AR
left join EMPLACEMENT base on AR.EM_Ref = base.EM_Ref
left join EMPLACEMENT rep on AR.EM_RefRep = rep.EM_Ref
left join CATEGORIE CA1 on CA1.CA_Ref = AR.CA_Ref
left join CATEGORIE CA2 on CA2.CA_Ref = CA1.CA_RefParent
left join SUIVI SU on SU.AR_Ref = AR.AR_Ref
left join EMPLACEMENT EM on EM.EM_Ref = SU.EM_Ref

where AR_Sommeil = 0
$where_need
$where_emp
$where_categ
$where_refArt

group by AR.AR_Ref, AR.AR_Design, CA1.CA_Nom, CA2.CA_Nom"); 


if ($emplacement) $statement->bindParam(":emplacement", $emplacement);
if ($categ) $statement->bindParam(":categ", $categ);
if ($refArt) $statement->bindParam(":refArt", $refArt);
$statement->execute();


$articles = Array();
while ( $obj = $statement->fetchObject() )  {
	$statement2 = $bdd->prepare(
			"select AR.AR_Ref, AR.AR_Design, SU.SU_DateMouvement, EM.EM_Intitule as intituleEmp, EM.EM_RefParent, EM2.EM_Ref, EM2.EM_Intitule as empParent, EM.EM_Type, EM.EM_Ref, ET.ET_Ref, ET.ET_Intitule

			from ARTICLE AR
			left join SUIVI SU on SU.AR_Ref = AR.AR_Ref
			join EMPLACEMENT EM on EM.EM_Ref = SU.EM_Ref
            left join EMPLACEMENT EM2 on EM2.EM_Ref = EM.EM_RefParent
			join ETAT ET on ET.ET_Ref = SU.ET_Ref
			
			where (SU.SU_DateMouvement,SU.AR_Ref) in (
			select max(SU_DateMouvement), AR_Ref
			from SUIVI
			group by AR_Ref)
			and AR.AR_Ref = :refArt

			group by AR.AR_Ref, AR.AR_Design");
			$statement2->bindParam(":refArt", $obj->AR_Ref);
			$statement2->execute();

			$dernierEmp = null;
			if( $obj2 = $statement2->fetchObject() )  {
				$dernierEmp = Array(

					"refArt"=>$obj2->AR_Ref,
					"design"=>$obj2->AR_Design,
					"dateMouv"=>$obj2->SU_DateMouvement,
					"intituleEmp"=>$obj2->intituleEmp,
					"intituleEmpParent"=>$obj2->empParent,
					"typeEmp"=>$obj2->EM_Type,
					"refEmp"=>$obj2->EM_Ref,
					"refEtat"=>$obj2->ET_Ref,
					"intituleEtat"=>$obj2->ET_Intitule,
					//~ "refEtat"=>$obj2->ET_Ref,
					//~ "refIntitule"=>$obj2->ET_Intitule,

				);
			}
	$statement3 = $bdd->prepare(
			"select AR.AR_Ref, AR.EM_RefRep, rep.EM_Intitule, rep.EM_Ref

			from ARTICLE AR
			left join EMPLACEMENT rep on AR.EM_RefRep = rep.EM_Ref

			where AR.AR_Ref = :refArt
			group by AR.AR_Ref");
			
			$statement3->bindParam(":refArt", $obj->AR_Ref);
			$statement3->execute();

			$rep = null;
			if( $obj2 = $statement3->fetchObject() )  {
				$rep = Array(

					"refArt"=>$obj2->AR_Ref,
					"intituleRep"=>$obj2->EM_Intitule,
					"refRep"=>$obj2->EM_Ref,

				);
			}
	array_push($articles,Array(
		
		"ref"=>$obj->AR_Ref,
		"design"=>$obj->AR_Design,
		//~ "actif"=>$obj->AR_Sommeil,
		//~ "dateCreation"=>$obj->AR_DateCreation,
		//~ "etatIntitule"=>$obj->ET_Intitule,
		"dernierEntretien"=>$obj->AR_DateDernierEntretien,
		"derniereRep"=>$obj->AR_DateDerniereReparation,
		"refCat"=>$obj->CA_Ref,
		"dateDernierMouv"=>DateConvert::SqlToFullDate($obj->SU_DateMouvement),
		"dernierEmp"=>$dernierEmp,
		"baseIntitule"=>$obj->EM_Intitule,
		"base"=>$obj->EM_Ref,
		"rep"=>$rep,
	));
}



return array("articles" =>$articles); 
?>
