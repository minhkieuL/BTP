<?php

/**
Gestion des routes et config
**/

include('connexionBdd.php');

setlocale(LC_TIME, "fr_FR");
date_default_timezone_set('Europe/Paris');

@session_start();
$type_route = @$_GET["type"];
$route = @$_GET["route"];
$common_path = "";
$templates_path = $common_path . "templates/";
$entity_path = $common_path . "src/Entity/";
require($entity_path.'DateConvert.php');
require($entity_path.'php-barcode.php');
$local_url='http://localhost:'.$_SERVER['SERVER_PORT'].'/';
$json_path = $common_path . "src/Controller/json/";
$skeleton_file = "skeleton.html"; // doit être commun à admin et public
$connected = false;
$connected = (intval(@$_SESSION['user']));
//~ if ( $site == "public") $connected =true;
//~ if ( $site == "admin") $connected =true;
 
$routes_json = Array(
	"admin"=>Array(
		"get_liste_users"=>Array(),
		"action_users"=>Array(),
		"get_liste_articles"=>Array(),
		"action_articles"=>Array(),
		"get_precision_articles"=>Array(),
		"get_liste_categories"=>Array(),
		"action_categories"=>Array(),
		"get_liste_emplacements"=>Array(),
		"action_emplacements"=>Array(),
		"get_liste_etats"=>Array(),
		"action_etats"=>Array(),
		"login"=>Array(),
		"gen_etiquettes_PDF"=>Array(),
		"gen_barcode_img"=>Array(),
	),
	"public"=>Array(
		"get_liste_emplacements"=>Array(),
		"get_liste_article"=>Array(),
		"reconnaissance_image"=>Array(),
		"action_suivi_article"=>Array(),
		"get_liste_categories"=>Array(),
		"get_liste_etat"=>Array(),
		"login"=>Array(),
		
		
	)
);
$routes_templates = Array(
	"admin"=>Array(
		"home"=>Array("titre"=>"Accueil"),
		"utilisateur"=>Array("titre"=>"Gestion des utilisateurs"),
		"article"=>Array("title"=>"Gestion des articles"),
		"categorie"=>Array("title"=>"Gestion des catégories"),
		"chantiers"=>Array("title"=>"Gestion des chantiers"),
		"depots"=>Array("title"=>"Gestion des dépôts"),
		"etats"=>Array("title"=>"Gestion des états"),
		"login"=>Array(),
	),
	"public"=>Array(
		"home"=>Array("titre"=>"Accueil"),
		"article"=>Array("titre"=>"Articles"),
		"emplacement"=>Array("titre"=>"Emplacements"),
		"emprunter"=>Array("titre"=>"Emprunter un matériel"),
		//~ "emprunterValide"=>Array("titre"=>"Emprunter Validation"),	
		//~ "emprunterChoixChantier"=>Array("titre"=>"Emprunter Choix Chantier"),	
		//~ "emprunterConfirmation"=>Array("titre"=>"Emprunter Confirmation"),	
		"rapporter"=>Array("titre"=>"Rapporter un matériel"),	
		//~ "rapporterValide"=>Array("titre"=>"Rapporter Valide"),
		//~ "rapporterChoixDepot"=>Array("titre"=>"Rapporter Choix Dépot"),
		//~ "rapporterConfirmation"=>Array("titre"=>"Rapporter Confirmation"),
		"rechercher"=>Array("titre"=>"Rechercher un matériel"),	

		
	)
);

$color_list = Array("purple", "red", "tomato",  "orange","seagreen", "dodgerblue");

 //~ print_r($route);
//~ print_r($site);
//~ print_r($type_route);
$menuAdmin = "
			<li><a href='http://localhost/btp/site_btp_admin/'><i class=\"fa fa-home\"></i>Accueil</a></li>
			<li class='mt20'><a href='http://localhost/btp/site_btp_admin/?route=utilisateur'><i class=\"fa fa-user\"></i>Utilisateurs</a></li>
			<li><a href='http://localhost/btp/site_btp_admin/?route=article'><i class=\"fa fa-wrench\"></i>Articles</a></li>
			<li><a href='http://localhost/btp/site_btp_admin/?route=categorie'><i class=\"fa fa-folder-open\"></i>Categories</a></li>
			<li><a href='http://localhost/btp/site_btp_admin/?route=chantiers'><i class=\"fa fa-truck	\"></i></i>Chantiers</a></li>
			<li><a href='http://localhost/btp/site_btp_admin/?route=depots'><i class=\"fa fa-map-marker\"></i></i>Depots</a></li>
			<li><a href='http://localhost/btp/site_btp_admin/?route=etats'><i class=\"fa fa-file\"></i>Etats</a></li>
			<li class='mt20'><a class='pointer' ng-click='deconnectMe()'><i class=\"fa fa-sign-out\"></i> Déconnexion</a></li>
			";
$menuPublic = "
				<li class='deplacer'><a href=\"http://localhost/btp/site_btp/?route=emprunter\">Déplacer du matériel vers un chantier</a></li>
				<li class='rapporter'><a href=\"http://localhost/btp/site_btp/?route=rapporter\">Rapporter du matériel au dépôt</a></li>
				<li class='rechercher'><a href=\"http://localhost/btp/site_btp/?route=rechercher\">Rechercher un matériel</a></li>
				<li style='margin-top:30px;' class='c3'><a style='text-decoration:none;' class='pointer' ng-click='deconnectMe()'><i class=\"fa fa-sign-out\"></i> Déconnexion</a></li>
			";
switch( $type_route ) {

	case "json":
		if (!$connected) $route = "login";
		elseif ( !$route || !array_key_exists($route,$routes_json[$site] )) die("Route inexistante"); 
		$parameters = Array();
		$json = file_get_contents('php://input');
		//~ var_dump($json);
		$json_extract = json_decode($json, true);
		//~ var_dump($json_extract);echo "hello";
		if ( !json_last_error() && is_array($json_extract) ) $parameters = $json_extract;
		echo json_encode(include $json_path . $site . "/" . $route . ".php");
		break;

	case "templates":
	default:
		// par défaut si on ne trouve pas la route
		//~ if(file_exists( $templates_path .  $site  ."/". $route . ".html")) {
					//~ echo "Le fichier $route existe.";
				//~ } else {
					//~ echo "Le fichier $route n'existe pas.";
		//~ }
		if (!$connected) $route = "login";
		elseif ( !$route || !array_key_exists($route, $routes_templates[$site]) ) {
			$route = "home";
		}
		echo str_replace(
			Array(
				"#[uniqid]",
				"#[title]",
				"#[content]",
				"#[menu]",
			), 
			Array(
				uniqid(), // #[uniqid]
				@$routes_templates[$site][$route]["titre"], // #[title]
				file_get_contents("../common/" . $templates_path .  $site  ."/". $route . ".html"), // #[content]
				//~ file_get_contents("../common/templates/public/login.html"), // #[content]
				($connected ? ($site=='admin' ? $menuAdmin: $menuPublic) : ""),
			), 
			file_get_contents( "../common/". $templates_path .  $site  ."/". $skeleton_file)
			//~ file_get_contents( "../common/templates/public/skeleton.html")
			//~ include $templates_path .  $site  ."/". $skeleton_file
		);
		
}	
