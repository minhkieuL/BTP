<?php
$t0=microtime(true);

function image_rotate($filename, $angle) {
   $image = imagecreatefromjpeg($filename);
   $image = imagerotate($image, $angle, 0);
   imagejpeg($image, $filename , 90);
}


$img = @$parameters['inputImg'];
if(strlen($img)<100)
	return array('erno' =>2, "msg"=>"Erreur : taille de l'image invalide");

//~ Enregistrement de l'image
$imgPath= "/srv/www/files/" . uniqid() . ".jpg";
file_put_contents($imgPath, file_get_contents($img));

//~ Pour chaque angle
for ( $angle = 0; $angle < 360 ; $angle+=90 ) {
	
	//~ Rotation de l'image
	if ($angle) image_rotate( $imgPath, $angle );
	
	//~ Lecture du code bar avec Zbar
	$bar_code_raw=trim(exec("zbarimg $imgPath"));

	//~ Pas de code barre reconnu
	if( substr($bar_code_raw,0,7)!='EAN-13:') continue; 
	$ean = substr($bar_code_raw,7,13);
	
	//~ Recherche de l'article
	$statement = $bdd->prepare("
		select count(*) as count
		from ARTICLE AR
		where AR_Sommeil = 0
		and AR_Ref = :refArt
	"); 
	$statement->bindParam(":refArt", $ean);
	$statement->execute();
	while ( $obj = $statement->fetchObject() )  {
		//~ L'article est trouvé
		if ( $obj->count ) return array('errno' => 0, 'ean'=> $ean, "msg"=>"Bravo : Produit reconnu");
	}

	
}

//~ Pas d'article après 4 rotations
return array('errno' => 1, "msg"=>"Erreur : pas d'article trouvé après 4 rotations" );


?>
