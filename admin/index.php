<?php
require_once ('../includes/connexion_bdd.php');
require_once ('includes/header.php');

if(isset($_SESSION['admin_id'])){
?>
<ul>
	<li><a href = "index.php" id="href" ><img alt="" src="images/home.PNG" class="home"></a></li>
	<li><a href = "?action=modifyWaitOrder" id="href">Afficher les commandes <i id="sup">en attente </i></a></li>
	<li><a href = "?action=modifyProgressOrder" id="href">Afficher les commandes en cours de livraison</a></li>
	<li><a href = "?action=modifyFinishOrder" id="href">Afficherles commandes <i id="mod">terminées</i> </a></li>
</ul>
<?php

if(isset($_GET['action'])){

	if($_GET['action'] == 'modifyWaitOrder'){
			
	$select = $db->prepare("SELECT * FROM commande,client WHERE client.id_client = commande.id_client AND etat = 0");
	$select->execute();
	
	if($trouve = $select->rowCount()) $nbLignes = true;
	else $nbLignes = false;
	
	if($nbLignes){
?>
<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
	<table id="tableau" summary="Liste des clients">
		<thead style="background-color:lightgrey;">
			<tr>
				<th scope="col">Reference de la commande</th>
				<th scope="col">Date</th>
				<th scope="col">Total</th>
				<th scope="col">Client</th>
				<th scope="col">Etat</th>
				<th scope="col">Code suivi</th>
				<th scope="col">Modifier</th>
			</tr>
		</thead>
				
		 <tfoot>
			<tr>
				<td colspan="7">Liste des commandes en attente "<?php $nb=$db->query("select count(*) from commande where etat = 0"); $res = $nb->fetchColumn();echo"$res";?>"Commandes en attente</td>
			</tr>
		  </tfoot>
		  		
		  <tbody>
<?php 
	while($s=$select->fetch(PDO::FETCH_OBJ)){
		if($s->etat == 0){$etat = "en attente";}elseif($s->etat == 1){$etat = "en cours";}else{$etat = "Terminée";}
		
		echo '<tr>';
		echo '<td>R_A'.$s->id_commande.'</td>';
		echo '<td>'.$s->date_commande.'</td>';
		echo '<td>'.$s->total.' €</td>';
		echo '<td>'.$s->nom.'&nbsp;'.$s->prenom.'</td>';
		echo '<td>'.$etat.'</td>';
		echo '<td>'.$s->code_suivi.'</td>';
?>
			<td><a href="?action=modifyWO&amp;id=<?php echo $s->id_commande; ?>"><input id="modifier" type="button" value='modifier' name='modifier'/></a></td>
			</tbody>
<?php
											}
		echo'</table>';
	}else{
		echo '<div id="vide">liste vide !</div>';
	}
	
}else if($_GET['action'] == 'modifyWO'){
				
	$id=$_GET['id'];
				
	$select = $db->prepare("SELECT * FROM commande,client where client.id_client = commande.id_client AND id_commande=$id");
	$select->execute();	
	$data = $select->fetch(PDO::FETCH_OBJ);	
	
?>				
	<div id="afficher">
			<html>
				<head>
					<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
				</head>
				<body>
					<form id="monForm" action="" method="POST">
					
				<fieldset>
				<legend>Commande en attente</legend>
					<div>
					<label for="for_nom_cat">Reference :</label>
						<input type="text" disabled value="R_A<?php echo $data->id_commande; ?>">
					</div>
					<div>
					<label for="for_nom_cat">Total :</label>
						<input type="text" disabled value="<?php echo $data->total; ?>">
					</div>
				<div>
					<label for="for_nom_cat">Nom du client :</label>
					<input id="for_nom_cat" disabled type="text" name="nom" value="<?php echo $data->nom; ?>"/>
				</div>
				<div>
					<label for="for_nom_cat">prenom du client :</label>
					<input id="for_nom_cat" disabled type="text" name="nom" value="<?php echo $data->prenom; ?>"/>
				</div>
				<div>
					<label for="for_nom_cat">Coordonnées :</label>
					<input id="for_nom_cat" disabled type="text" name="nom" value="<?php echo $data->telephone; ?> - <?php echo $data->email; ?>"/>
				</div>
				<div>
					<label for="for_nom_cat">Date de la commande :</label>
					<input id="for_nom_cat" disabled type="text" name="nom" value="<?php echo $data->date_commande; ?>"/>
				</div>
				<div>
					<label for="for_nom_cat">Date de la commande :</label>
					<input id="for_nom_cat" disabled type="text" name="nom" value="<?php echo $data->date_commande; ?>"/>
				</div>

				<div>
					<label style="background:yellow" for="for_nom_cat">Code suivi en ligne :</label>
					<input id="for_nom_cat" type="text" name="codeSuivi" autofocus value="<?php echo $data->code_suivi; ?>"/>
				</div>
				
				<div>
					<label for="for_nom_cat">Etat de la commande :</label>
					<div>
					<input type="radio" name="etat" <?php if($data->etat == 0) echo "checked"?> value="0"/>En attente
					</div>
					<div>
					<input type="radio" name="etat" <?php if($data->etat == 1) echo "checked"?> value="1"/>En Cours
					</div>
				</div>
				
				</fieldset>
				
				<div>
					<input type="submit" name="submit" value="Modifier" />
				</div>
				
				</form>
		</div>		
<?php		
			if($trouve = $select->rowCount()) $nbLignes = true;
			else $nbLignes = false;
			
			if($nbLignes){
			?>

			<div class="a"  style="color:black;font-family:calibri;font-size:17px;width:auto;margin-top:2%;text-transform: capitalize;font-variant: small-caps;">
	<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/><?php 
	
	$select = $db->query("SELECT *,DATE_FORMAT(date_commande, '%d  / %m / %Y') as date_commande 
			FROM commande,client,produit,detail_commande WHERE client.id_client = commande.id_client AND 
			produit.id_produit = detail_commande.id_produit AND commande.id_commande = detail_commande.id_commande AND commande.id_commande = $id");
	
	while($s=$select->fetch(PDO::FETCH_OBJ)){
		
		echo "<i> <b id='client'>$s->nom</b> a commandé <b><u id='article'>$s->quantite $s->nom_produit</b> \"prix $s->prix € Frais de $s->frais_livraison €\"</u> le <b id='date'>$s->date_commande</b> <b>destination : $s->adresse_livraison $s->ville_livraison $s->code_postale_livraison</b>";
		echo '<hr>';
	
						}
		}else{
			echo '<div id="vide" style="font-size:12px">Aucune commande éffectuée actuellement !</div>';
			}
						
						?>
	</div>			
<?php	if(isset($_POST['submit'])){
					
				$etat=$_POST['etat'];
				$codeSuivi=$_POST['codeSuivi'];
							
				$update = $db->prepare("UPDATE commande SET etat='".$etat."',code_suivi='".$codeSuivi."' WHERE id_commande=$id");
				$update->execute();
				
					echo '<script>alert("Les modifications ont bien été effectuées");
		
					document.location =location.href="?action=modifyWaitOrder" </script>';
				
			
				
									}
?>
	<!--fin commande en attente-->			
<?php
}elseif($_GET['action'] == 'modifyProgressOrder'){
			
	$select = $db->prepare("SELECT * FROM commande,client WHERE client.id_client = commande.id_client AND etat = 1");
	$select->execute();
	
	if($trouve = $select->rowCount()) $nbLignes = true;
	else $nbLignes = false;
	
	if($nbLignes){
?>
<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
	<table id="tableau" summary="Liste des clients">
		<thead style="background-color:lightgrey;">
			<tr>
				<th scope="col">Reference de la commande</th>
				<th scope="col">Date</th>
				<th scope="col">Total</th>
				<th scope="col">Client</th>
				<th scope="col">Etat</th>
				<th scope="col">Code suivi</th>
				<th scope="col">Modifier</th>
			</tr>
		</thead>
				
		 <tfoot>
			<tr>
				<td colspan="7">Liste des commandes en Cours "<?php $nb=$db->query("select count(*) from commande where etat = 1"); $res = $nb->fetchColumn();echo"$res";?>"Commandes en cours</td>
			</tr>
		  </tfoot>
		  		
		  <tbody>
<?php 
	while($s=$select->fetch(PDO::FETCH_OBJ)){
		if($s->etat == 0){$etat = "en attente";}elseif($s->etat == 1){$etat = "en cours";}else{$etat = "Terminée";}
		
		echo '<tr>';
		echo '<td>R_A'.$s->id_commande.'</td>';
		echo '<td>'.$s->date_commande.'</td>';
		echo '<td>'.$s->total.' €</td>';
		echo '<td>'.$s->nom.'&nbsp;'.$s->prenom.'</td>';
		echo '<td>'.$etat.'</td>';
		echo '<td>'.$s->code_suivi.'</td>';
?>
			<td><a href="?action=modifyPO&amp;id=<?php echo $s->id_commande; ?>"><input id="modifier" type="button" value='modifier' name='modifier'/></a></td>
			</tbody>
<?php
											}
		echo'</table>';
	}else{
		echo '<div id="vide">liste vide !</div>';
	}
	
}else if($_GET['action'] == 'modifyPO'){
				
	$id=$_GET['id'];
				
	$select = $db->prepare("SELECT * FROM commande,client where client.id_client = commande.id_client AND id_commande=$id");
	$select->execute();	
	$data = $select->fetch(PDO::FETCH_OBJ);	
	
?>				
	<div id="afficher">
			<html>
				<head>
					<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
				</head>
				<body>
					<form id="monForm" action="" method="POST">
					
				<fieldset>
				<legend>Commande en cours </legend>
					<div>
					<label for="for_nom_cat">Reference :</label>
						<input type="text" disabled value="R_A<?php echo $data->id_commande; ?>">
					</div>
					<div>
					<label for="for_nom_cat">Total :</label>
						<input type="text" disabled value="<?php echo $data->total; ?>">
					</div>
				<div>
					<label for="for_nom_cat">Nom du client :</label>
					<input id="for_nom_cat" disabled type="text" name="nom" value="<?php echo $data->nom; ?>"/>
				</div>
				<div>
					<label for="for_nom_cat">prenom du client :</label>
					<input id="for_nom_cat" disabled type="text" name="nom" value="<?php echo $data->prenom; ?>"/>
				</div>
				<div>
					<label for="for_nom_cat">Coordonnées :</label>
					<input id="for_nom_cat" disabled type="text" name="nom" value="<?php echo $data->telephone; ?> - <?php echo $data->email; ?>"/>
				</div>
				<div>
					<label for="for_nom_cat">Date de la commande :</label>
					<input id="for_nom_cat" disabled type="text" name="nom" value="<?php echo $data->date_commande; ?>"/>
				</div>

				<div>
					<label style="background:yellow" for="for_nom_cat">Code suivi en ligne :</label>
					<input id="for_nom_cat" type="text" autofocus name="codeSuivi" value="<?php echo $data->code_suivi; ?>"/>
				</div>
				
				<div>
					<label for="for_nom_cat">Etat de la commande :</label>
					<div>
					<input type="radio" name="etat" <?php if($data->etat == 0) echo "checked"?> value="0"/>En attente
					</div>
					<div>
					<input type="radio" name="etat" <?php if($data->etat == 1) echo "checked"?> value="1"/>En Cours
					</div>
				</div>
				<div>
					<label for="for_nom_cat">Etat de la commande :</label>
					<div>
					<input type="radio" name="etat" <?php if($data->etat > 1) echo "checked"?> value="2"/>Terminée
					</div>
				</div>
				
				</fieldset>
				
				<div>
					<input type="submit" name="submit" value="Modifier" />
				</div>
				
				</form>
		</div>		
<?php		
			if($trouve = $select->rowCount()) $nbLignes = true;
			else $nbLignes = false;
			
			if($nbLignes){
			?>

			<div class="a"  style="color:black;font-family:calibri;font-size:17px;width:auto;margin-top:2%;text-transform: capitalize;font-variant: small-caps;">
	<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/><?php 
	
	$select = $db->query("SELECT *,DATE_FORMAT(date_commande, '%d  / %m / %Y') as date_commande 
			FROM commande,client,produit,detail_commande WHERE client.id_client = commande.id_client AND 
			produit.id_produit = detail_commande.id_produit AND commande.id_commande = detail_commande.id_commande AND commande.id_commande = $id");
	
	while($s=$select->fetch(PDO::FETCH_OBJ)){
		
		echo "<i> <b id='client'>$s->nom</b> a commandé <b><u id='article'>$s->quantite $s->nom_produit</b> \"prix $s->prix € Frais de $s->frais_livraison €\"</u> le <b id='date'>$s->date_commande</b> <b>destination : $s->adresse_livraison $s->ville_livraison $s->code_postale_livraison</b>";
		echo '<hr>';
	
						}
		}else{
			echo '<div id="vide" style="font-size:12px">Aucune commande éffectuée actuellement !</div>';
			}
						
						?>
	</div>	
<?php	if(isset($_POST['submit'])){
					
				$etat=$_POST['etat'];
				$codeSuivi=$_POST['codeSuivi'];
							
				$update = $db->prepare("UPDATE commande SET etat='".$etat."',code_suivi='".$codeSuivi."' WHERE id_commande=$id");
				$update->execute();
				
					echo '<script>alert("Les modifications ont bien été effectuées");
		
					document.location =location.href="?action=modifyProgressOrder" </script>';
				
				//header('location:?action=modify/delete');
				
									}
?>
	<!--Fin commande en cours -->

<?php
}elseif($_GET['action'] == 'modifyFinishOrder'){
			
	$select = $db->prepare("SELECT * FROM commande,client WHERE client.id_client = commande.id_client AND etat > 1");
	$select->execute();
	
	if($trouve = $select->rowCount()) $nbLignes = true;
	else $nbLignes = false;
	
	if($nbLignes){
?>
<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
	<table id="tableau" summary="Liste des clients">
		<thead style="background-color:lightgrey;">
			<tr>
				<th scope="col">Reference de la commande</th>
				<th scope="col">Date</th>
				<th scope="col">Total</th>
				<th scope="col">Client</th>
				<th scope="col">Etat</th>
				<th scope="col">Code suivi</th>
				<th scope="col">Modifier</th>
			</tr>
		</thead>
				
		 <tfoot>
			<tr>
				<td colspan="7">Liste des commandes terminées "<?php $nb=$db->query("select count(*) from commande where etat > 1"); $res = $nb->fetchColumn();echo"$res";?>"Commandes Terminées</td>
			</tr>
		  </tfoot>
		  		
		  <tbody>
<?php 
	while($s=$select->fetch(PDO::FETCH_OBJ)){
		if($s->etat == 0){$etat = "en attente";}elseif($s->etat == 1){$etat = "en cours";}else{$etat = "Terminée";}
		echo '<tr>';
		echo '<td>R_A'.$s->id_commande.'</td>';
		echo '<td>'.$s->date_commande.'</td>';
		echo '<td>'.$s->total.' €</td>';
		echo '<td>'.$s->nom.'&nbsp;'.$s->prenom.'</td>';
		echo '<td>'.$etat.'</td>';
		echo '<td>'.$s->code_suivi.'</td>';
?>
			<td><a href="?action=modifyFO&amp;id=<?php echo $s->id_commande; ?>"><input id="modifier" type="button" value='modifier' name='modifier'/></a></td>
			</tbody>
<?php
											}
		echo'</table>';
	}else{
		echo '<div id="vide">liste vide !</div>';
	}
	
}else if($_GET['action'] == 'modifyFO'){
				
	$id=$_GET['id'];
				
	$select = $db->prepare("SELECT * FROM commande,client where client.id_client = commande.id_client AND id_commande=$id");
	$select->execute();	
	$data = $select->fetch(PDO::FETCH_OBJ);	
	
?>				
	<div id="afficher">
			<html>
				<head>
					<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
				</head>
				<body>
					<form id="monForm" action="" method="POST">
					
				<fieldset>
					<legend>Commande terminée</legend>
					<div>
					<label for="for_nom_cat">Reference :</label>
						<input type="text" disabled value="R_A<?php echo $data->id_commande; ?>">
					</div>
					<div>
					<label for="for_nom_cat">Total :</label>
						<input type="text" disabled value="<?php echo $data->total; ?>">
					</div>
				<div>
					<label for="for_nom_cat">Nom du client :</label>
					<input id="for_nom_cat" disabled type="text" name="nom" value="<?php echo $data->nom; ?>"/>
				</div>
				<div>
					<label for="for_nom_cat">prenom du client :</label>
					<input id="for_nom_cat" disabled type="text" name="nom" value="<?php echo $data->prenom; ?>"/>
				</div>
				<div>
					<label for="for_nom_cat">Coordonnées :</label>
					<input id="for_nom_cat" disabled type="text" name="nom" value="<?php echo $data->telephone; ?> - <?php echo $data->email; ?>"/>
				</div>
				<div>
					<label for="for_nom_cat">Date de la commande :</label>
					<input id="for_nom_cat" disabled type="text" name="nom" value="<?php echo $data->date_commande; ?>"/>
				</div>

				<div>
					<label style="background:yellow" for="for_nom_cat">Code suivi en ligne :</label>
					<input id="for_nom_cat" type="text" autofocus name="codeSuivi" value="<?php echo $data->code_suivi; ?>"/>
				</div>
				
				<div>
					<label for="for_nom_cat">Etat de la commande :</label>
					<div>
					<input type="radio" name="etat" <?php if($data->etat == 0) echo "checked"?> value="0"/>En attente
					</div>
					<div>
					<input type="radio" name="etat" <?php if($data->etat == 1) echo "checked"?> value="1"/>En Cours
					</div>
				</div>
				<div>
				<label for="for_nom_cat">Etat de la commande :</label>
					<div>
					<input type="radio" name="etat" <?php if($data->etat > 1) echo "checked"?> value="2"/>Terminée
					</div>
				</div>
				</fieldset>
				
				<div>
					<input type="submit" name="submit" value="Modifier" />
				</div>
				
				</form>
		</div>		
<?php		
			if($trouve = $select->rowCount()) $nbLignes = true;
			else $nbLignes = false;
			
			if($nbLignes){
			?>

			<div class="a"  style="color:black;font-family:calibri;font-size:17px;width:auto;margin-top:2%;text-transform: capitalize;font-variant: small-caps;">
	<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/><?php 
	
	$select = $db->query("SELECT *,DATE_FORMAT(date_commande, '%d  / %m / %Y') as date_commande 
			FROM commande,client,produit,detail_commande WHERE client.id_client = commande.id_client AND 
			produit.id_produit = detail_commande.id_produit AND commande.id_commande = detail_commande.id_commande AND commande.id_commande = $id");
	
	while($s=$select->fetch(PDO::FETCH_OBJ)){
		
		echo "<i> <b id='client'>$s->nom</b> a commandé <b><u id='article'>$s->quantite $s->nom_produit</b> \"prix $s->prix € Frais de $s->frais_livraison €\"</u> le <b id='date'>$s->date_commande</b> <b>destination : $s->adresse_livraison $s->ville_livraison $s->code_postale_livraison</b>";
		echo '<hr>';
	
						}
		}else{
			echo '<div id="vide" style="font-size:12px">Aucune commande éffectuée actuellement !</div>';
			}
						
						?>
	</div>	
<?php	if(isset($_POST['submit'])){
					
				$etat=$_POST['etat'];
				$codeSuivi=$_POST['codeSuivi'];
							
				$update = $db->prepare("UPDATE commande SET etat='".$etat."',code_suivi='".$codeSuivi."' WHERE id_commande=$id");
				$update->execute();
				
					echo '<script>alert("Les modifications ont bien été effectuées");
		
					document.location =location.href="?action=modifyProgressOrder" </script>';
				
				//header('location:?action=modify/delete');
				
									}
?>

<!--fin commande terminée-->
	
<?php
			
}else if($_GET['action'] == 'delete'){
					
	$id=$_GET['id'];

	$deleteDetail = $db->prepare("DELETE FROM detailCommande,commande WHERE commande.id_commande = detail_commande.id_commande AND id_commande=$id");
	$deleteDetail->execute();
	
	header('location:?action=modify/delete');
		


}else{
			
	die('une erreur s\'est produite');
	
	}//fin de tous les conditions de Ajout/modification/suppression

	
}else{

	$num = 1;
	$select = $db->prepare("SELECT count(*) as nb_commande,DATE_FORMAT(date_commande, '%d  / %m  / %Y') as date_commande 
			FROM `commande` group by date_commande LIMIT 0,20;");
	$select->execute();
	
	if($trouve = $select->rowCount()) $nbLignes = true;
	else $nbLignes = false;
	
	if($nbLignes){
		?>
	<div class="a"  style="width:auto;margin-top:1%;">
	<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
		<table id="liste">
			<tr>
				<th><h5>Position:</h5></th>
				<th><h5>Nombre de commande :</h5></th>
				<th><h5>Date de commande :</h5></th>
			</tr>
	
	<?php 
		while($s=$select->fetch(PDO::FETCH_OBJ)){
			$commande = "commande";
			if($s->nb_commande > 1){ $commande = "commandes";}else{$commande = "commande";}
			echo '<tr>';
			echo '<td>'.$num.'<hr></td>';
			echo "<td>$s->nb_commande <i>$commande</i><hr></td>";
			echo "<td>$s->date_commande <hr></td></tr>";
			$num += 1;
										}
			echo'</table></div>';
		
			}else{
				echo '<div id="vide" style="font-size:12px">Aucune statistique de commande actuellement !</div>';
			}
			
			
			
			/*
			if($trouve = $select->rowCount()) $nbLignes = true;
			else $nbLignes = false;
			
			if($nbLignes){
			?>

	<!--		<div class="a"  style="color:black;font-family:calibri;font-size:13px;width:auto;margin-top:2%;text-transform: capitalize;font-variant: small-caps;">
	<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>-->
	
	<?php /*
	
	$select = $db->query("SELECT *,DATE_FORMAT(date_commande, '%d  / %m / %Y') as date_commande 
			FROM commande,client,produit WHERE client.id_client = commande.id_client AND 
			produit.id_produit = commande.id_produit ORDER BY id_commande DESC LIMIT 0,20;");
	
	while($s=$select->fetch(PDO::FETCH_OBJ)){
		
		echo "<i> <b id='client'>$s->nom</b> a commandé <u id='article'>$s->quantite $s->nom_produit \"prix $s->prix € Frais de $s->frais_livraison €\"</u> le <b id='date'>$s->date_commande</b> pour un total de $s->total euros <a href = '?action=afficher&amp;id=$s->id_client' class='infoclient'>Afficher informations</a></i>";
		echo '<hr>';
	
						}
		}else{
			echo '<div id="vide" style="font-size:12px">Aucune commande éffectuée actuellement !</div>';
			}
						
						?>
	<!--</div>-->
			<?php/*
			if(isset($_GET['action'])){
			
				if($_GET['action'] == 'afficher'){
					$id = $_GET['id'];
					echo '<div class="a"  style="width:auto;position :absolute;margin-top:2%;">';
					$select = $db->query("SELECT * FROM client WHERE id_client = $id ;");
					while($s=$select->fetch(PDO::FETCH_OBJ)){
					
						echo "<i> <p id='adresse'>Client : <i class='adresse'>$s->nom $s->prenom </i></p>
									<p id='adresse'>Adresse livraison : <i class='adresse'>$s->adresse_livraison </i></p>
									<p id='adresse'>CP Livraison : <i class='adresse'>$s->code_postale_livraison</i></p>
									<p id='adresse'>Ville Livraison : <i class='adresse'>$s->ville_livraison </i></p>
									<p id='tel'>Telephone : $s->telephone </p>
									<p id='mail'>E-mail : $s->email </p>";
						echo '<hr>';
					}
					echo'</div>';
					
				}
			}
			*/
		}	

}else{

	header('Location:espacePrive.php');
}
?>