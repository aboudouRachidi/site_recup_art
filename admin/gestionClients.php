<?php
require_once ('../includes/connexion_bdd.php');
require_once ('includes/header.php');
if(isset($_SESSION['admin_id'])){
?>
<ul>
	<li><a href = "gestionClients.php" id="href" ><img alt="" src="images/home.PNG" class="home"></a></li>
	<li><a href = "?action=afficher" id="href">Afficher les clients</a></li>
	<li><a href = "?action=afficherAbonne" id="href">Afficher les abonnés</a></li>
	<!-- <a href = "?action=modify/delete">Modifier ou supprimer une categorie</a></br></br>-->
</ul>

<?php
 
if(isset($_GET['action'])){

 if($_GET['action'] == 'afficher'){
	
	$select = $db->prepare("SELECT * FROM client ORDER BY nom");
	$select->execute();
	
	if($trouve = $select->rowCount()) $nbLignes = true;
	else $nbLignes = false;
	
	if($nbLignes){		
?>
 		
 	 	<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
 	 	<table id="tableau" summary="Liste des clients">
 			<thead style="background-color:lightgrey;">
 				<tr>
 					<th scope="col">Nom</th>
 			
 					<th scope="col">Prenom</th>
 					
 					<th scope="col">E-mail</th>
 					
  					<th scope="col">Mot de Passe</th>
 					
 					<th scope="col">Téléphone</th>
 					
 					<th scope="col">Adresse Domicile</th>
 					
 					<th scope="col">CP Domicile</th>
 					
 					<th scope="col">Ville Domicile</th>
 					
 					<th scope="col">Adresse Livraison</th>
 					
 					<th scope="col">CP Livraison</th>
 					
 					<th scope="col">Ville Domicile</th>
 					
 					<th scope="col">Action</th>
 				</tr>
 			</thead>
 	 		<tfoot>
 				    <tr>
 				      <td colspan="12">Liste des clients</td>
 				    </tr>
 	  		</tfoot>
<?php 			
	while($s=$select->fetch(PDO::FETCH_OBJ)){?>
  		<tbody>		
			<tr>			
	 			<td><?php echo $s->nom;?></td>
	 
				<td><?php echo $s->prenom;?></td>
			
				<td><?php echo $s->email;?></td>
				
				<td><input type="password" value="<?php echo $s->mdp;?>" disabled ><a href="?action=modifMdpClient&amp;id=<?php echo $s->id_client; ?>"><input id="modifier" type="button" value='Modifier le MDP' name='modifier' style="width: 50%"/></a></td>
	
				<td><?php echo $s->telephone;?></td>
	
				<td><?php echo $s->adresse_domicile;?></td>
	
				<td><?php echo $s->code_postale_domicile;?></td>
	
				<td><?php echo $s->ville_domicile;?></td>
			
				<td><?php echo $s->adresse_livraison;?></td>
	
				<td><?php echo $s->code_postale_livraison;?></td>
	
				<td><?php echo $s->ville_livraison;?></td>
			
				<td><a href="?action=delete&amp;id=<?php echo $s->id_client; ?>" onclick="return(confirm('Etes-vous sûr de vouloir supprimer cette entrée?'));"><input id="supprimer" type="button" value='supprimer' name='supprimer'/></a></td>
				
			</tr>
			</tbody>
<?php
			}
				echo'</table>';
	}else{
		echo '<div id="vide">liste vide !</div>';
	}								
											
											
}else if($_GET['action'] == 'afficherAbonne'){
	
	$select = $db->prepare("SELECT * FROM abonne ORDER BY email");
	$select->execute();
	
	if($trouve = $select->rowCount()) $nbLignes = true;
	else $nbLignes = false;
	
	if($nbLignes){
		
?>
		<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
 	 	<table id="tableau" summary="Liste des clients">
 			<thead style="background-color:lightgrey;">
 				<tr>
 					
 					<th scope="col">E-mail</th>	
					<th scope="col">Supprimer</th>	
 				</tr>
 			</thead>
 	 		<tfoot>
 				    <tr>
 				      <td colspan="2">Liste des abonnés</td>
 				    </tr>
 	  		</tfoot>
<?php 				
	while($s=$select->fetch(PDO::FETCH_OBJ)){?>
  		<tbody>		
			<tr>			
			
				<td><?php echo $s->email;?></td>
				<td><a href="?action=deleteAbonne&amp;id=<?php echo $s->id_abonne; ?>" onclick="return(confirm('Etes-vous sûr de vouloir supprimer cette entrée?'));"><input id="supprimer" type="button" value='supprimer' name='supprimer'/></a></td>

			</tr>
			</tbody>
<?php
											}
			echo'</table>';
	}else{
		echo '<div id="vide">liste vide !</div>';
	}						
											
											
}else if($_GET['action'] == 'deleteAbonne'){
	
	$id=$_GET['id'];
	
	$delete = $db->prepare("DELETE FROM abonne WHERE id_abonne=$id");
	$delete->execute();
	
		header('location:?action=afficherAbonne');		
		
}else if($_GET['action'] == 'modifMdpClient'){				
?>
 <form id ="monForm" action="" method="POST" style="width: 60%">
					<div>
					<p style="margin-top: 0px;text-align:center;border:1px solid black;"><i>Un client a oublié sont mot de passe? <em style="font-size: 16px;">Remplisser ce formulaire </em></i></p>
							<label for="nom" style="font-variant: small-caps;">Le client</label>
							<SELECT id="for_categorie" name="client" style="width: 70%;color:black;font-size:11px;font-family :Caflisch Script, Adobe Poetica, Sanvito, Ex Ponto, Snell Roundhand, Zapf-Chancery, cursive;">
								<?php 
								$id=$_GET['id'];
								$select = $db->query("SELECT * FROM client WHERE id_client=$id;");
								while($s=$select->fetch(PDO::FETCH_OBJ)) { ?>
								<OPTION VALUE="<?php echo $s->id_client;?>"style="font-size:11px;font-family :Caflisch Script, Adobe Poetica, Sanvito, Ex Ponto, Snell Roundhand, Zapf-Chancery, cursive;">>
								<?php echo "$s->nom";echo '&nbsp'; echo "$s->prenom";echo'&nbsp&nbsp;&nbsp;&nbsp;';echo "ville : $s->ville_domicile";echo'&nbsp&nbsp;&nbsp;&nbsp;';echo "Email : $s->email";}?>
								</OPTION>
							</SELECT>
					</div>
					
					<div>
							<label for="mdp" style="font-size: 10px;font-variant: small-caps;">Nouveau mot de passe</label>
							<input type="password" name="mdp" required="required"/>
					</div>
					
					<div>
							<label for="retap_mdp" style="font-size: 10px;font-variant: small-caps;">Retapez le nouveau mot de passe</label>
							<input type="password" name="retap_mdp" required="required"/>
					</div>
					
					<div>
							<input type="submit" name="submit" value="Modifier" />
					</div>
				</form>			
<?php 		
if(isset($_POST['submit'])){
					

				$mdp=hash('sha256',$_POST['mdp']);
				$retap_mdp=hash('sha256',$_POST['retap_mdp']);
				$id = $_POST['client'];
					
				if($mdp === $retap_mdp){
					$exist = $db->query("SELECT nom FROM client WHERE id_client = '$id'");
					if($nom=$exist->fetch()){
				
				$update = $db->prepare("UPDATE client SET mdp='".$mdp."' WHERE id_client=$id");
				$update->execute();
				
					echo '<script>alert("le client \" '.$nom['nom'].' \" a un nouveau mot de passe");
		
					document.location =location.href="gestionClients.php?action=afficher" </script>';
				
				//header('location:?action=modify/delete');
					}

					}else{
						echo '<script>alert("Les nouveau mot de passe ne correspondent pas")</script>';
					}
				

}
?>
				
<?php			
}else if($_GET['action'] == 'delete'){
					
	$id=$_GET['id'];
	

	$deleteCommande = $db->prepare("DELETE FROM detail_commande WHERE detail_commande.id_commande IN (select id_commande FROM commande WHERE commande.id_client = $id)");
	$deleteCommande->execute();
	
	$deleteCommande = $db->prepare("DELETE FROM commande WHERE commande.id_client =$id");
	$deleteCommande->execute();
	
	$deleteClient = $db->prepare("DELETE FROM client WHERE client.id_client=$id");
	$deleteClient->execute();
	
		header('location:?action=modify/delete');
	
}else{
			
	die('une erreur s\'est produite');
	
	}//fin de tous les conditions de Ajout/modification/suppression

	
}else{//si on a pas d'action on affiche les elements de la base de donnée
	
	$num = 1;	
	$select = $db->prepare("SELECT * FROM client ORDER BY nom");
	$select->execute();
	
	if($trouve = $select->rowCount()) $nbLignes = true;
	else $nbLignes = false;
	
	if($nbLignes){
?>
<div class="a">
<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
	<table id="liste">
			<thead>nombre de clients "<?php 	$nb=$db->query("select count(*) from client"); $res = $nb->fetchColumn();echo"<thead>$res</thead>";?>"</thead>
		<tr>
			<td><h5>Position : </h5></td>
			<td><h5>liste des clients : </h5></td></tr>

<?php 
	while($s=$select->fetch(PDO::FETCH_OBJ)){
		echo '<tr>';
		echo '<td>'.$num.'</td>';
		echo "<td>$s->nom</td></tr>";
		$num += 1;
									}
		echo'</table></div>';
		
		}else{
			echo '<div id="vide" style="font-size:12px">Liste des clients vide !</div>';
		}
	
		
	}

}else{//si l'administrateur n'est plus logué on le redirige à la page connexion
	
		header('Location:espacePrive.php');
	}
?>