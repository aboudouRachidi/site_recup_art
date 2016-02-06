<?php
  $user="root";
  $pass="";
  try{
    @$db=new PDO('mysql:host=127.0.0.1;dbname=recup_art',$user,$pass);
    
    @$db->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'"); //les contenues des champs prendra en compte l’ensemble des caractères(utf8)
    @$db->exec("SET NAMES 'utf8';");
	@$db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER); // les noms de champs seront en caracteres miniscule
    
    @$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // les erreurs lanceront des exceptions
  }
  catch(Exception $e){
    echo 'Echec de la connexion';
  }
?>