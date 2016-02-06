<?php
require_once ('../includes/connexion_bdd.php');
require_once ('includes/header.php');
if(isset($_SESSION['admin_id'])){

?>
<ul>
	<li><a href = "gestionCategorie.php" id="href" ><img alt="" src="images/home.PNG" class="home"></a></li>
	<li><a href = "?action=add" id="href">Ajouter une catégorie</a></li>
	<li><a href = "?action=modify/delete" id="href"><i id="mod">Modifier</i> ou <i id="sup">Supprimer</i> une catégorie</a></li>
</ul>

<?php
 
if(isset($_GET['action'])){

	if($_GET['action'] == 'add'){
			
		if(isset($_POST['submit'])){

			$nom=addslashes($_POST['nom']);
			
			if($nom){
				
				$exist = $db->query("SELECT * FROM categorie WHERE nom_categorie = '$nom'");
				if($exist->fetchColumn()){
					echo '<script>alert("Ce categorie \"'.$nom.'\" existe déjà")</script>';
				}else{

				$insert = $db->prepare("INSERT INTO categorie VALUES('','".$nom."')");
				$insert->execute();
				
				echo "<script>alert('La catégorie \"$nom\" a été Ajouter')</script>";
				}
					}else{

						echo "<script>alert('veuillez remplir tous les champs')</script>";

							}
									}
?>
		<div id="afficher">
			<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
			<form id="monForm" action="" method="POST" style="width: 38%;">
				<div>
					<label for="for_nom_cat">Nom de la catégorie:</label>
					<input id="for_nom_cat" type="text" name="nom" autofocus required style="width: 70%"/>
				</div>
				
				<div>
					<input type="submit" name="submit" value="Ajouter" />
				</div>
			</form>
		</div>
<?php	

}else if($_GET['action'] == 'modify/delete'){

	
	$select = $db->prepare("SELECT * FROM categorie");
	$select->execute();
	
	if($trouve = $select->rowCount()) $nbLignes = true;
	else $nbLignes = false;
	
	if($nbLignes){
?>
<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
	<table id="tableau" summary="Liste des clients">
		<thead style="background-color:lightgrey;">
			<tr>
				<th scope="col">Nom de la catégorie / =>"<?php $nb=$db->query("select count(*) from categorie"); $res = $nb->fetchColumn();echo"$res";?>"Catégories</th>
				<th scope="col">Modifier</th>
				<th scope="col">Supprimer</th>
			</tr>
		</thead>
				
		 <tfoot>
			<tr>
				<td colspan="3">Liste des catégories</td>
			</tr>
		  </tfoot>
		  		
		  <tbody>
<?php 
	while($s=$select->fetch(PDO::FETCH_OBJ)){
		echo '<tr><td>';
		echo $s->nom_categorie;
		echo '</td>';
?>
			<td><a href="?action=modify&amp;id=<?php echo $s->id_categorie; ?>"><input id="modifier" type="button" value='modifier' name='modifier'/></a></td>
			<td><a href="?action=delete&amp;id=<?php echo $s->id_categorie; ?>" onclick="return(confirm('Etes-vous sûr de vouloir supprimer cette entrée?'));"><input id="supprimer" type="button" value='supprimer' name='supprimer'/></a></td>
			</tbody>
<?php
											}
		echo'</table>';
	}else{
		echo '<div id="vide">liste vide !</div>';
	}

}else if($_GET['action'] == 'modify'){
				
	$id=$_GET['id'];
				
	$select = $db->prepare("SELECT * FROM categorie where id_categorie=$id");
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
				<div>
					<label for="for_nom_cat">Nom de la catégorie :</label>
					<input id="for_nom_cat" type="text" name="nom" value="<?php echo $data->nom_categorie; ?>"/>
				</div>

				<div>
					<input type="submit" name="submit" value="Modifier" />
				</div>
				
				</form>
		</div>		
			
<?php	if(isset($_POST['submit'])){
					
				$nom=addslashes($_POST['nom']);
							
				$update = $db->prepare("UPDATE categorie SET nom_categorie='".$nom."' WHERE id_categorie=$id");
				$update->execute();
				
					echo '<script>alert("Les modifications ont bien été effectuées");
		
					document.location =location.href="?action=modify/delete" </script>';
				
				//header('location:?action=modify/delete');
				
									}
?>
				
<?php
				
}else if($_GET['action'] == 'delete'){
					
	$id=$_GET['id'];
	$appartenir = $db->query("SELECT * FROM produit,categorie WHERE categorie.id_categorie = produit.id_categorie AND produit.id_categorie = $id");
	if($appartient=$appartenir->fetch()){
		echo '<script>alert("Impossible de supprimer cette categorie car le produit **\" '.$appartient['nom_produit'].'** \" y est associé \nModifier ou supprimer d\'abord ce produit('.$appartient['nom_produit'].')");
		document.location =location.href="?action=modify/delete" </script>';
	}else{
	$delete = $db->prepare("DELETE FROM categorie WHERE id_categorie=$id");
	$delete->execute();
	
	header('location:?action=modify/delete');
		}
	
}else{
			
	die('une erreur s\'est produite');
	
	}//fin de tous les conditions de Ajout/modification/suppression

	
}else{//si on a pas d'action on affiche les elements de la base de donnée
	$num = 1;	
	$select = $db->prepare("SELECT * FROM categorie ORDER BY nom_categorie");
	$select->execute();
	
	if($trouve = $select->rowCount()) $nbLignes = true;
	else $nbLignes = false;
	
	if($nbLignes){
?>
<div class="a"  style="width: 350px;">
<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
	<table id="liste">
			<thead>nombre catégories "<?php $nb=$db->query("select count(*) from categorie"); $res = $nb->fetchColumn();echo"<thead>$res</thead>";?>"</thead>
		<tr>
			<th><h5>Position:</h5></th>
			<th><h5>Liste des catégories :</h5></th>
		</tr>

<?php 
	while($s=$select->fetch(PDO::FETCH_OBJ)){
		echo '<tr>';
		echo '<td>'.$num.'</td>';
		echo "<td>$s->nom_categorie</td>";
		$num += 1;
									}
		echo'</table></div>';
	
		}else{
			echo '<div id="vide" style="font-size:12px">Liste vide ajouter des catégories => <a href = "?action=add">ICI</a> !</div>';
		}

	}
	
}else{//si l'administrateur n'est plus logué on le redirige à la page connexion
	
		header('Location:espacePrive.php');
	}

?>