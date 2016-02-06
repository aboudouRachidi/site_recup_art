<?php
require_once ('../includes/connexion_bdd.php');
require_once ('includes/header.php');

if(isset($_SESSION['admin_id'])){
?>
<ul>
	<li><a href = "gestionCouleurs.php" id="href" ><img alt="" src="images/home.PNG" class="home"></a></li>
	<li><a href = "?action=addColor" id="href">Ajouter des <i id="c">C</i><i id="o">oul</i><i id="e">eurs</i></a></li>
	<li><a href = "?action=modify/deleteColor" id="href"><i id="mod">Modifier</i> ou <i id="sup">Supprimer</i> une <i id="c">c</i><i id="o">oul</i><i id="e">eur</i></a></li>
</ul>
<?php

if(isset($_GET['action'])){

	 if($_GET['action'] == 'addColor'){

if(isset($_POST['submit'])){

	$couleur=$_POST['couleur'];
	$codeCouleur=$_POST['codeCouleur'];
	
	if($couleur){
			$exist = $db->query("SELECT * FROM couleur WHERE couleur = '$couleur'");
			if($exist->fetchColumn()){
				echo '<script>alert("Cette couleur \"'.$couleur.'\" existe déjà")
			document.location =location.href="gestionCouleurs.php?action=addColor"</script>";';
			}else{
		$insert = $db->prepare("INSERT INTO couleur VALUES('','".$couleur."','".$codeCouleur."')");
		$insert->execute();
		
		echo "<script>alert('La couleur \" $couleur \" a été Ajouter')
		document.location =location.href='gestionCouleurs.php?action=addColor'</script>";
			}

	}else{


		echo "<script>alert('veuillez remplir tous les champs')</script>";

	}//fin verification si champs rempli
}//fin submit
?>
	<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
	<form id="monForm" action="" method="POST">
		
		<div>
			<label for="for_couleur">Nom couleur</label>
			<input id="for_couleur" type="text" name="couleur" autofocus value="<?php if(isset($couleur)) echo $couleur;?>"/>
		</div>
		<div>
			<label for="for_couleur">Code couleur</label>
			<input id="for_couleur" type="text" name="codeCouleur"  value="<?php if(isset($codeCouleur)) echo $codeCouleur;?>"/>
		</div>
		
		<div>
			<input type="submit" name="submit" value="Ajouter" />
		</div>
			
	</form>
<?php 
}else if($_GET['action'] == 'modify/deleteColor'){

	$select = $db->prepare("SELECT * FROM couleur ORDER BY couleur ASC");
	$select->execute();

	if($trouve = $select->rowCount()) $nbLignes = true;
	else $nbLignes = false;

	if($nbLignes){
?>
		<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
		<table id="tableau" summary="Liste des clients">
			<thead style="background-color:lightgrey;">
					<tr>
						<th scope="col">Couleur / => "<?php $nb=$db->query("select count(*) from couleur"); $res = $nb->fetchColumn();echo"$res";?>"Couleurs</th>
						<th scope="col">Modifier</th>
						<th scope="col">Supprimer</th>
					</tr>
			</thead>
							
			<tfoot>
					<tr>
						<td colspan="4">Liste des couleurs</td>
					</tr>
			</tfoot>
					  		
			<tbody>
			<?php				
	while($s=$select->fetch(PDO::FETCH_OBJ)){

		echo '<tr><td>';
		echo $s->couleur;
		echo '</td>';
?>
			<td><a href="?action=modifyColor&amp;id=<?php echo $s->id_couleur; ?>"><input id="modifier" type="button" value='modifier' name='modifier'/></a></td>
			<td><a href="?action=deleteColor&amp;id=<?php echo $s->id_couleur; ?>" onclick="return(confirm('Etes-vous sûr de vouloir supprimer cette entrée?'));"><input id="supprimer" type="button" value='supprimer' name='supprimer'/></a></td>
				</tr>
			</tbody>
<?php
											}
		echo'</table>';
		
		}else{
			echo '<div id="vide">Aucune couleur !</div>';
		}
		
		
}else if($_GET['action'] == 'modifyColor'){
				
	$id=$_GET['id'];
				
	$select = $db->prepare("SELECT * FROM couleur WHERE id_couleur=$id");
	$select->execute();	
	$data = $select->fetch(PDO::FETCH_OBJ);			
?>				
			<form id="monForm" action="" method="POST">

							<div>			
								<label for="for_couleur">Nom couleur</label>		
								<input id="for_couleur" type="text" name="couleur" value="<?php echo $data->couleur;?>"/>
							</div>
							<div>
								<label for="for_couleur">Code couleur</label>
								<input id="for_couleur" type="text" name="codeCouleur"  value="<?php echo $data->code_couleur;?>"/>
							</div>
							<div>
								<input type="submit" name="submit" value="Modifier" />
							</div>
				</form>
			
<?php	if(isset($_POST['submit'])){
	
			$couleur=$_POST['couleur'];
			$codeCouleur=$_POST['codeCouleur'];

				if($couleur){
			
					$update = $db->prepare("UPDATE couleur SET couleur='".$couleur."',code_couleur='".$codeCouleur."' WHERE id_couleur=$id");
					$update->execute();

					echo '<script>alert("les modifications ont bien été effectuées");
		
					document.location =location.href="?action=modify/deleteColor" </script>';
					
			}else{
			
			
				echo" <script>alert('verifiez si vous avez rempli tous les champs')</script>";
			
			}
							
				/*$update = $db->prepare("UPDATE produit SET nom_produit='".$nom."',description='".$description."',
						argumentaire='".$argumentaire."',dimensions='".$dimensions."',prix='".$prix."',type_livraison='".$type_livraison."',
						frais_livraison='".$frais_livraison."',disponible='".$disponibilite."',id_categorie='".$categorie."',
						id_materiaux='".$materiaux."',id_couleur='".$couleur."',id_photo='".$photo."' WHERE id_produit=$id");
				$update->execute();*/
				

				//header('location:?action=modify/delete');
				
			}
?>
				
<?php
				
}else if($_GET['action'] == 'deleteColor'){
					
	$id=$_GET['id'];
	$delete = $db->prepare("DELETE FROM couleur WHERE id_couleur=$id");
	$delete->execute();
	
	header('location:?action=modify/deleteColor');

	}else{
			
		die('une erreur s\'est produite');
	
	}//fin de tous les conditions de Ajout/modification/suppression
	
	}else{//si on a pas d'action on affiche les elements de la base de donnée
		$num = 1;
		$select = $db->prepare("SELECT * FROM couleur ORDER BY couleur");
		$select->execute();
	
		if($trouve = $select->rowCount()) $nbLignes = true;
		else $nbLignes = false;
	
		if($nbLignes){
?>
	<div class="a">
	<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
		<table id="liste">
		<thead>nombre couleurs "<?php $nb=$db->query("select count(*) from couleur"); $res = $nb->fetchColumn();echo"<thead>$res</thead>";?>"</thead>
			<tr>
				<th><h5>Position :</h5></th>
				<th><h5>Liste des couleurs :</h5></th>
			</tr>
	
<?php 
		while($s=$select->fetch(PDO::FETCH_OBJ)){
			
			echo '<tr>';
			echo '<td>'.$num.'</td>';
			echo "<td>$s->couleur</td></tr>";
			$num += 1;
										}
			echo'</table></div>';
			
			}else{
				echo '<div id="vide" style="font-size:12px">Liste vide ajouter des produits => <a href = "?action=add">ICI</a> !</div>';
			}
						
		}
		
	
}else{//si l'administrateur n'est plus logué on le redirige à la page connexion
			
		header('Location:espacePrive.php');
	}
	
?>