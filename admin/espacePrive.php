<?php
require_once ('../includes/connexion_bdd.php');
?>

<?php

if(isset($_SESSION['user_id'])){
	
	echo '<h3 style="color:red">vous n\'avez pas l\'autorisation pour acceder à cet endroit</h3>';
	header('location: ../index.php');
}


if(isset($_SESSION['admin_id'])){
	
	header('location: index.php');
	
	}else{
	
		if(isset($_POST['submit'])){
			
			$email = $_POST['email'];
			$mdp = hash('sha256',$_POST['mdp']);
		
			if($email&&$mdp){
			
				$select = $db->query("SELECT id_admin FROM admin WHERE email='$email' AND mdp='$mdp'");
				//Test du résultat de la requête
				if($select->fetchColumn()){
					$select = $db->query("SELECT * FROM admin WHERE email='$email' AND mdp='$mdp'");
					$result = $select->fetch(PDO::FETCH_OBJ);
					$_SESSION['admin_id'] = $result->id_admin;
					$_SESSION['admin_nom'] = $result->nom;
					$_SESSION['admin_prenom'] = $result->prenom;
					$_SESSION['admin_email'] = $result->mail;
					$_SESSION['admin_mdp'] = $result->mdp;

	     		//Accès à l'application
				header('location: index.php');
		 
		} else {
	     //refus authentification non valide
	     	echo '<script>alert("E-mail ou Mot de Passe incorrect")</script>';
		}
	
		
		}else{
			
			echo '<script>alert("veuillez remplir tous les champs")</script>';
		
		}
	}

}

?>
<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
<html>
<head>
  
  <meta charset="utf8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Operative Access" />
  <meta name="keywords" content="Login, Flat, Dark, HTML5, CSS3" />

  <title>Administration Recup'art</title>
  
</head>
<body>
	<form action="" method="POST">
	  <div class="box">
	    <div class="content"><div><img src="images/logo.JPG" width="80" height="58" id="logo"/></div>
	      <h1>Administration Agnès Recup'Art</h1>
	      <input class="field" type="text" name="email" placeholder="Email" value=<?php if(isset($email)) echo $email?>><br>
	      <input class="field" type="password" name="mdp" placeholder="Mot de Passe"><br>
	      <input class="btn" type="submit" name="submit" value="connexion"> 
	    </div>
	  </div>
	</form>
</body>
</html>