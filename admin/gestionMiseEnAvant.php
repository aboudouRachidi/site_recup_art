<?php
require_once ('../includes/connexion_bdd.php');
require_once ('includes/header.php');
if(isset($_SESSION['admin_id'])){


?>
<ul>
	<li><a href = "gestionMiseEnAvant.php" id="href" ><img alt="" src="images/home.PNG" class="home"></a></li>
	<li><a href = "?action=addDefilant" id="href">Ajouter un message defilant</a></li>
	<li><a href = "?action=modify/deleteDefilant" id="href"><i id="mod">Modifier</i> ou <i id="sup">Supprimer</i> Message defilant</a></li>
	<li><a href = "?action=addProduitAccueil" id="href">Choisir le produit à mettre à l'accueil </a></li>
	<li><a href = "?action=modify/deleteProduitAccueil" id="href"><i id="sup">Supprimer</i> le produit  à l'accueil</a></li>
	<li><a href = "?action=addMentionsLegales" id="href">Ajouter texte légale du site</a></li>
	<li><a href = "?action=modify/deleteMentionsLegales" id="href"><i id="mod">Modifier</i> ou <i id="sup">Supprimer</i> des textes legales</a></li>
</ul>
<?php
 
if(isset($_GET['action'])){

	if($_GET['action'] == 'addDefilant'){
			
		if(isset($_POST['submit'])){

			$message= addslashes($_POST['message']);
			$afficher = $_POST['afficher'];
			
			if($message){

				
				$insert = $db->prepare("INSERT INTO message_defilant VALUES('','".$message."','".$afficher."')");
				$insert->execute();
				
				echo "<script>alert('Message Ajoutés')</script>";

					}else{

						echo "<script>alert('veuillez remplir tous les champs')</script>";

							}
									}
?>

			<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
			<form id="monForm" action="" method="POST" style="width:50%";>
				<div>
					<label for="for_nom_message">Message:</label>
					<input id="for_nom_message" type="text" name="message" value="<?php if(isset($message)) echo $message;?>"/>
				</div>
				
				<fieldset>
					 <legend>Affichage du message</legend>
						<div class="checkbox">
							<div style="font-size: 12px"><input id="for_afficher" type=radio name="afficher" value="1" checked>Afficher</div>
							<div style="font-size: 12px"><input id="for_nonafficher" type=radio name="afficher" value="0">Ne pas Afficher</div>
						</div>
				</fieldset>
					
					<div>
						<input type="submit" name="submit" value="Ajouter" />
					</div>

			</form>
<?php	

}else if($_GET['action'] == 'modify/deleteDefilant'){

	
	$select = $db->prepare("SELECT * FROM message_defilant");
	$select->execute();

		
	if($trouve = $select->rowCount()) $nbLignes = true;
	else $nbLignes = false;
		
	if($nbLignes){
	
?>
<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
	<table id="tableau" summary="Liste des clients">
		<thead style="background-color:lightgrey;">
			<tr>
				<th scope="col">Message</th>
				<th scope="col">Modifier</th>
				<th scope="col">Supprimer</th>
			</tr>
		</thead>
				
		 <tfoot>
			<tr>
				<td colspan="3">Le message actuel</td>
			</tr>
		  </tfoot>
		  		
		  <tbody>
<?php 
	while($s=$select->fetch(PDO::FETCH_OBJ)){
		echo '<tr><td>';
		echo $s->message;
		echo '</td>';
?>
			<td><a href="?action=modifyDefilant&amp;id=<?php echo $s->id_message; ?>"><input id="modifier" type="button" value='modifier' name='modifier'/></a></td>
			<td><a href="?action=deleteDefilant&amp;id=<?php echo $s->id_message; ?>" onclick="return(confirm('Etes-vous sûr de vouloir supprimer cette entrée?'));"><input id="supprimer" type="button" value='supprimer' name='supprimer'/></a></td>
			</tbody>
<?php
											}
		echo'</table>';
		
		
}else{
			echo '<div id="vide">Aucun message !</div>';
		}		
		

}else if($_GET['action'] == 'modifyDefilant'){
				
	$id=$_GET['id'];
				
	$select = $db->prepare("SELECT * FROM message_defilant where id_message=$id");
	$select->execute();	
	$data = $select->fetch(PDO::FETCH_OBJ);
?>				
		<div id="afficher">
				<html>
					<head>
						<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
					</head>
				<body>
					<form id="monForm" action="" method="POST" style="width:50%";>
						<div>
							<label for="for_nom_message">message :</label>
							<input id="for_nom_message" type="text" name="message" required value="<?php echo $data->message; ?>"/>
						</div>
						
						<fieldset>
							 <legend>Affichage du message :<?php if($data->afficher==1){echo "<em id='disponible'>Actuellement Afficher</em>";}else{echo "<em id='indisponible'>Actuellement Non Afficher</em>";}?></legend>
								<div class="checkbox">
									<div style="font-size: 12px"><input id="for_afficher" type=radio name="afficher" value="1" <?php if($data->afficher==1){echo 'checked';}?>>Afficher</div>
									<div style="font-size: 12px"><input id="for_nonafficher" type=radio name="afficher" value="0" <?php if($data->afficher==0){echo 'checked';}?>>Ne pas Afficher</div>
								</div>
						</fieldset>
					
						<div>
							<input type="submit" name="submit" value="Modifier" />
						</div>
				</form>
		</div>		
			
<?php	if(isset($_POST['submit'])){
					
				$message= addslashes($_POST['message']);
				$afficher=$_POST['afficher'];
							
				$update = $db->prepare("UPDATE message_defilant SET message='".$message."',afficher='".$afficher."' WHERE id_message=$id");
				$update->execute();
				
					echo '<script>alert("les modifications ont bien été effectuées");
		
					document.location =location.href="?action=modify/deleteDefilant" </script>';
				
				//header('location:?action=modify/delete');
				
									}
				
}else if($_GET['action'] == 'deleteDefilant'){
					
	$id=$_GET['id'];
	$delete = $db->prepare("DELETE FROM message_defilant WHERE id_message=$id");
	$delete->execute();
	
	header('location:?action=modify/deleteDefilant');
	

}else if($_GET['action'] == 'addProduitAccueil'){
?>			
			<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
			<form id="monForm" action="" method="POST" style="width: 70%">
			
						<div>
							<label for="for_produit">Choisir les produits (3 minimum)</label>
							<SELECT  id="for_produit" name="produit" style="width: 70%">
								<OPTION VALUE="NULL">-</OPTION>
								<?php 		
								$select = $db->prepare("SELECT * FROM produit ORDER BY id_produit DESC ");$select->execute();
								while($s=$select->fetch(PDO::FETCH_OBJ)) { ?>
								<OPTION VALUE="<?php echo $s->id_produit;?>"><?php echo $s->nom_produit;}?> </OPTION>
							</SELECT>
						</div>
				
					
					<div>
						<input type="submit" name="submit" value="Ajouter" />
					</div>

			</form>
<?php
if(isset($_POST['submit'])){
	
	$produit = $_POST['produit'];
	
	if($produit<>"NULL"){
			$exist = $db->query("SELECT * FROM slider WHERE id_produit = '$produit'");
			if($exist->fetchColumn()){
			echo '<script>alert("Veuillez choisir un autre produit celui-ci existe déjà !")</script>';
			}else{
				
		$insert = $db->prepare("INSERT INTO slider VALUES('','".$produit."')");
		$insert->execute();
	
		echo "<script>alert('Enregistré')</script>";
				}
	}else{
	
		echo "<script>alert('veuillez choisir le produit')</script>";
	
	}
	
	
	
}

}else if($_GET['action'] == 'modify/deleteProduitAccueil'){


	$select = $db->prepare("SELECT * FROM slider,produit WHERE slider.id_produit = produit.id_produit");
	$select->execute();

	if($trouve = $select->rowCount()) $nbLignes = true;
	else $nbLignes = false;
	
	if($nbLignes){

	
	?>
<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
	<table id="tableau" summary="Liste des clients">
		<thead style="background-color:lightgrey;">
			<tr>
				<th scope="col">Le produit</th>
				<th scope="col">Supprimer</th>
			</tr>
		</thead>
				
		 <tfoot>
			<tr>
				<td colspan="2">Le produit à l'accueil</td>
			</tr>
		  </tfoot>
		  		
		  <tbody>
<?php 
	while($s=$select->fetch(PDO::FETCH_OBJ)){
		echo '<tr><td>';
		echo $s->nom_produit;
		echo '</td>';
		
?>
			<td><a href="?action=deleteProduitAccueil&amp;id=<?php echo $s->id_slider; ?>" onclick="return(confirm('Etes-vous sûr de vouloir supprimer cette entrée?'));"><input id="supprimer" type="button" value='supprimer' name='supprimer'/></a></td>
			</tbody>
<?php
											}
		echo'</table>';

		}else{
			echo '<div id="vide" style="font-size: 13px;">Aucun produit à l\'accueil !</div>';
		}


				
}else if($_GET['action'] == 'deleteProduitAccueil'){
					
	$id=$_GET['id'];
	$delete = $db->prepare("DELETE FROM slider WHERE id_slider=$id");
	$delete->execute();
	
	header('location:?action=modify/deleteProduitAccueil');

	
}else if($_GET['action'] == 'addMentionsLegales'){
		?>
<!DOCTYPE html>
<html>
	<head>
		 <meta charset="utf-8" />
		<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
	    <script type="text/javascript" src="includes/ckeditorStandard/ckeditor.js"></script>
	</head>
			  <body>    
				<form action=""  method="post" id="monForm"  style="width:70%;height: 80%;">
				
		<div>
			<label for="for_nom_cat">Titre :</label>
			<input disabled id="for_nom_cat" type="text" name="titre" value="<?php if(isset($titre)) echo $titre?>"/>
		</div>
		
		<div>
			<label>Texte :</label>
				<textarea disabled id="editor1" name="texte" ><?php if(isset($texte)) echo $texte?></textarea>
					<script type="text/javascript">
					CKEDITOR.replace('texte');
				</script>
		</div>
			<div>
	    		<input type="submit" value="Valider" name="submit">
	    	</div>

	</form>
</body>
</html>
	<?php
	if(isset($_POST['submit'])){
		$titre = $_POST['titre'];
		$texte = $_POST['texte'];
		
		if($texte&&$titre){
		
			$insert = $db->prepare("INSERT INTO mention VALUES('','".$titre."','".$texte."')");
			$insert->execute();
		
			echo "<script>alert('Enregistré')</script>";
		
		}else{
		
			echo "<script>alert('veuillez saisir votre texte')</script>";
		
		}
		
		
		
	}
	
	}else if($_GET['action'] == 'modify/deleteMentionsLegales'){
	
	
		$select = $db->prepare("SELECT * FROM mention");
		$select->execute();
	
		if($trouve = $select->rowCount()) $nbLignes = true;
		else $nbLignes = false;
	
		if($nbLignes){
	
	
			?>
	<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
		<table id="tableau" summary="Liste des clients">
			<thead style="background-color:lightgrey;">
				<tr>
					<th scope="col">Le titre</th>
					<th scope="col">Modifier</th>
					<th scope="col">Supprimer</th>
				</tr>
			</thead>
					
			 <tfoot>
				<tr>
					<td colspan="3">Mention legale</td>
				</tr>
			  </tfoot>
			  		
			  <tbody>
<?php
		while($s=$select->fetch(PDO::FETCH_OBJ)){
			echo '<tr><td>';
			echo $s->titre;
			echo '</td>';
?>
			<td><a href="?action=modifyMentionLegale&amp;id=<?php echo $s->id_mention; ?>"><input id="modifier" type="button" value='modifier' name='modifier'/></a></td>
				<td><a href="?action=deleteProduitAccueil&amp;id=<?php echo $s->id_mention; ?>" onclick="return(confirm('Etes-vous sûr de vouloir supprimer cette entrée?'));"><input id="supprimer" type="button" value='supprimer' name='supprimer'/></a></td>
				</tbody>
<?php
												}
			echo'</table>';
	
			}else{
				echo '<div id="vide" style="font-size: 13px;">Aucun mention legale!</div>';
			}
	

}else if($_GET['action'] == 'modifyMentionLegale'){

	$id=$_GET['id'];

	$select = $db->prepare("SELECT * FROM mention where id_mention=$id");
	$select->execute();
	$data = $select->fetch(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html>
	<head>
		 <meta charset="utf-8" />
		<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
	    <script type="text/javascript" src="includes/ckeditorStandard/ckeditor.js"></script>
	</head>
			  <body>    
				<form action=""  method="post" id="monForm"  style="width:70%;height: 80%;">
				
		<div>
			<label for="for_nom_cat">Titre :</label>
			<input disabled id="for_nom_cat" type="text" name="titre" value="<?php echo $data->titre;?>"/>
		</div>
		<div>
			<label>Texte :</label>
				<textarea id="editor1" name="texte" ><?php echo $data->texte;?></textarea>
					<script type="text/javascript">
					CKEDITOR.replace('texte');
				</script>
		</div>
			<div>
	    		<input type="submit" value="Modifier" name="submit">
	    	</div>

	</form>
</body>
</html>
			
<?php
			if(isset($_POST['submit'])){
					
				$texte= addslashes($_POST['texte']);
				
							
				$update = $db->prepare("UPDATE mention SET texte='".$texte."' WHERE id_mention=$id");
				$update->execute();
				
					echo '<script>alert("les modifications ont bien été effectuées");
		
					document.location =location.href="?action=modify/deleteMentionsLegales" </script>';
				
				//header('location:?action=modify/delete');
				
									}
					
}else if($_GET['action'] == 'deleteMentionLegale'){
						
		$id=$_GET['id'];
		$delete = $db->prepare("DELETE FROM mention WHERE id_mention=$id");
		$delete->execute();
		
		header('location:?action=modify/deleteMentionLegale');
}else{
			
	die('une erreur s\'est produite');
	
	}//fin de tous les conditions de Ajout/modification/suppression

	
}else{//si on a pas d'action on affiche les elements de la base de donnée
	$num = 1;	
	$select = $db->prepare("SELECT * FROM slider,produit WHERE produit.id_produit = slider.id_produit");
	$select->execute();
	
	if($trouve = $select->rowCount()) $nbLignes = true;
	else $nbLignes = false;
		
	if($nbLignes){
?>
<div class="a">
<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
	<table id="liste">
		<tr>
			<th><h5>Position : </h5></th>
			<th><h5>A l'accueil: </h5></th>
		</tr>
<?php
	while($s=$select->fetch(PDO::FETCH_OBJ)){
		echo '<tr>';
		echo '<td>'.$num.'</td>';
		echo "<td>$s->nom_produit</tr>";
		$num += 1;
									}
		echo'</table></div>';
		
		}else{
			echo '<div id="vide" style="font-size:12px">Aucun élément à l\'accueil !</div>';
		}
		
	}
	
}else{//si l'administrateur n'est plus logué on le redirige à la page connexion
	
		header('Location:espacePrive.php');
	}
?>