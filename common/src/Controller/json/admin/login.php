<?php
$email=@$parameters['email'];
$pass=@$parameters['pass'];
$action = @$parameters["action"];

switch ($action) {
	
	case "login":
		$statement = $bdd->prepare(
		"
		select UT_Mail, UT_Password, UT_Type, UT_Nom, UT_Prenom, UT_Ref
		from UTILISATEUR 
		where UT_Mail = :mail
		and UT_Password = MD5(:pass)
		and UT_Type=100"
		);
		
		//~ $mdp=password_hash($pass, PASSWORD_DEFAULT);
		
		$statement->bindParam(":pass", $pass);
		$statement->bindParam(":mail", $email);
		$statement->execute();

		$res = Array();
		while ( $obj = $statement->fetchObject() )  {
			
			$_SESSION['user'] = intval($obj->UT_Ref);
			return Array(
				'errno' => 0,
				'user' => Array( 'nom' =>$obj->UT_Nom,'prenom' =>$obj->UT_Prenom,'mail' =>$obj->UT_Mail,'ref' =>$obj->UT_Ref,),
			);
		}

		return array('errno' => 1, 'message' => 'Email ou mot de passe incorrecte');
		
		
		
		
	case "logout":
		unset($_SESSION["user"]);
		return Array("errno"=>0);
		
}
?>
