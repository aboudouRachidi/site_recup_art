<?php
require_once ('../includes/connexion_bdd.php');
require_once ('includes/header.php');

if(isset($_SESSION['admin_id'])){
?>
<ul>
	<li><a href = "gestionMateriaux.php" id="href" ><img alt="" src="images/home.PNG" class="home"></a></li>
	<li><a href = "?action=add" id="href">Ajouter materiaux</a></li>
	<li><a href = "?action=modify/delete" id="href"><i id="mod">Modifier</i> ou <i id="sup">Supprimer</i> matériaux</a></li>
</ul>
<?php
 
if(isset($_GET['action'])){

	if($_GET['action'] == 'add'){
			
		if(isset($_POST['submit'])){

			$nom=addslashes($_POST['nom']);

			
			if($nom){
				
				$exist = $db->query("SELECT * FROM materiaux WHERE nom_materiaux = '$nom'");
				if($exist->fetchColumn()){
					echo '<script>alert("Ce materiau \" '.$nom.' \" existe déjà")</script>';
				}else{
				
				$insert = $db->prepare("INSERT INTO materiaux VALUES('','".$nom."')");
				$insert->execute();

					echo "<script>alert('Le matériau \"$nom\" a été Ajouter')</script>";
				}
				
			}else{

					echo "<script>alert('veuillez remplir tous les champs')</script>";

						}
	}
?>

		<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
			<form id="monForm" action="" method="POST" style="width: 38%;">
				<div>
					<label for="for_nom_mat">Nom du matériau :</label>
					<input id="for_nom_mat" type="text" name="nom" autofocus required style="width: 70%"/>
				</div>
				
				<div>
					<input type="submit" name="submit" value="Ajouter" />
				</div>
			</form>

<?php	

}else if($_GET['action'] == 'modify/delete'){

	$select = $db->prepare("SELECT * FROM materiaux ORDER BY nom_materiaux ASC ");
	$select->execute();
	
	if($trouve = $select->rowCount()) $nbLignes = true;
	else $nbLignes = false;
	
	if($nbLignes){
?>
	<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
		<table id="tableau" summary="Liste des clients">
			<thead style="background-color:lightgrey;">
				<tr>
					<th scope="col">Nom du matériau / =>"<?php $nb=$db->query("select count(*) from materiaux"); $res = $nb->fetchColumn();echo"$res";?>"materiaux</th>
					<th scope="col">Modifier</th>
					<th scope="col">Supprimer</th>
					</tr>
			</thead>
						
			<tfoot>
				<tr>
					<td colspan="4">Liste des matériaux</td>
				</tr>
			</tfoot>
				  		
			<tbody>
<?php 				
	while($s=$select->fetch(PDO::FETCH_OBJ)){
		echo '<tr><td>';
		echo "$s->nom_materiaux</td>";
?>

			<td><a href="?action=modify&amp;id=<?php echo $s->id_materiaux; ?>"><input id="modifier" type="button" value='modifier' name='modifier'/></a></td>
			<td><a href="?action=delete&amp;id=<?php echo $s->id_materiaux; ?>" onclick="return(confirm('Etes-vous sûr de vouloir supprimer cette entrée?'));"><input id="supprimer" type="button" value='supprimer' name='supprimer'/></a></td>
				</tr>
			</tbody>
			
<?php
											}
			echo'</table>';
			
		}else{
				echo '<div id="vide">Aucun matériaux !</div>';
		}
			
}else if($_GET['action'] == 'modify'){
				
	$id=$_GET['id'];
				
	$select = $db->prepare("SELECT * FROM materiaux where id_materiaux=$id");
	$select->execute();	
	$data = $select->fetch(PDO::FETCH_OBJ);			
?>				
				<html>
					<head>
						<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
					</head>
				<body>
					<form id="monForm" action="" method="POST">
						<table id="tableau" >
							<div>
								<label for="for_nom_mat">Nom du matériau:</label>
								<input id="for_nom_mat" type="text" name="nom" value="<?php echo $data->nom_materiaux; ?>"/>
							</div>						
							
							<div>
								<input type="submit" name="submit" value="Modifier" />
							</div>
					</form>
			
<?php	if(isset($_POST['submit'])){
					
				$nom=addslashes($_POST['nom']);
							
				$update = $db->prepare("UPDATE materiaux SET nom_materiaux='".$nom."' WHERE id_materiaux=$id");
				$update->execute();
				
					echo '<script>alert("Les modifications ont bien été effectuées");
		
					document.location =location.href="?action=modify/delete" </script>';

					
				//header('location:?action=modify/delete');
				
									}
?>
				
<?php
				
}else if($_GET['action'] == 'delete'){
					
	$id=$_GET['id'];
	$delete = $db->prepare("DELETE FROM materiaux WHERE id_materiaux=$id");
	$delete->execute();
	
	header('location:?action=modify/delete');
	
}else{
			
	die('une erreur s\'est produite');
	
	}//fin de tous les conditions de Ajout/modification/suppression

	
}else{//si on a pas d'action on affiche les elements de la base de donnée
	$num = 1;	
	$select = $db->prepare("SELECT * FROM materiaux ORDER BY nom_materiaux ASC");
	$select->execute();
	
	if($trouve = $select->rowCount()) $nbLignes = true;
	else $nbLignes = false;
	
	if($nbLignes){
		
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
</head>
<div class="a">

	<table id="liste">
			<thead>nombre de matériaux "<?php $nb=$db->query("select count(*) from materiaux"); $res = $nb->fetchColumn();echo"<thead>$res</thead>";?>"</thead>
		<tr>
			<th><h5>Position :</h5></th>
			<th><h5>Liste des materiaux :</h5></th>
		</tr>

<?php 
	while($s=$select->fetch(PDO::FETCH_OBJ)){
		echo '<tr>';
		echo '<td>'.$num.'</td>';
		echo "<td>$s->nom_materiaux</td></tr>";
		$num += 1;
									}
		echo'</table></div></html>';
	
		}else{
			echo '<div id="vide" style="font-size:12px">Liste vide ajouter des matériaux => <a href = "?action=add">ICI</a> !</div>';
		}
			
	}



}else{//si l'administrateur n'est plus logué on le redirige à la page connexion
			
		header('Location:espacePrive.php');
	}
?>