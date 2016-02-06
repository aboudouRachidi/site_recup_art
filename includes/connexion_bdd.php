<?php
session_start();
try{
	
	$db = new PDO('mysql:host=localhost;dbname=recup_art', 'root',''); 
	
	$db->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'"); //les contenues des champs prendra en compte l�ensemble des caract�res(utf8)
	
	$db->exec("SET NAMES 'utf8';");
	
	$db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER); // les noms de champs seront en caracteres miniscule
	
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // les erreurs lanceront des exceptions

}catch(Exception $e){

	die('une erreur est survenue');

};

?>