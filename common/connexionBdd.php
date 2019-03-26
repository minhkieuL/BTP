<?php
try
{	
	
	/* Attention à la valeur de /etc/php7/apache2/php.ini
	  
	  	; Default socket name for local MySQL connects.  If empty, uses the built-in
		; MySQL defaults.
		; http://php.net/pdo_mysql.default-socket
		#~ pdo_mysql.default_socket= /opt/lampp/var/mysql/mysql.sock
		pdo_mysql.default_socket= 
	
		Sinon utiliser 127.0.0.1
	*/
	
	$arrExtraParam= array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
	$bdd = new PDO('mysql:host=localhost;dbname=site_btp;charset=utf8', 'root', '', $arrExtraParam);
	$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

catch (Exception $e)
{
        die('Erreur : '. $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage());
		
}
?>
