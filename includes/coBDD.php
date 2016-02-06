<?php
  $user="root";
  $pass="";
  try{
    @$db=new PDO('mysql:host=127.0.0.1;dbname=recup_art',$user,$pass);
  }
  catch(Exception $e){
    echo 'Echec de la connexion';
  }
?>
