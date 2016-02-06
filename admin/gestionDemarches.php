<?php
require_once ('../includes/connexion_bdd.php');
require_once ('includes/header.php');

if(isset($_SESSION['admin_id'])){

?>
<ul>
	<li><a href = "gestionDemarches.php" id="href" ><img alt="" src="images/home.PNG" class="home"></a></li>
	<li><a href = "?action=add" id="href">Ajouter une démarche</a></li>
	<li><a href = "?action=modify/delete" id="href"><i id="mod">Modifier</i> ou <i id="sup">Supprimer</i> une démarche</a></li>
</ul>
<?php 

if(isset($_GET['action'])){

	if($_GET['action'] == 'add'){
		
		if(isset($_POST['submit'])){
			
			$titre = $_POST['titre'];
			
			$maxSize = 102400000000;

			$target_dir = "demarche/";
			
			$target_file = $target_dir . basename($_FILES['pdf']["name"]);
			

			if (is_uploaded_file($_FILES['pdf']['tmp_name'])) {
			
				if ($_FILES['pdf']['type'] != "application/pdf") {
					
					echo '<script>alert("Erreur ce fichier **'.$_FILES['pdf']["name"].'** n\'est pas au format PDF.")</script>';
					
				} else if ($_FILES['pdf']['size'] > $maxSize) {
					
						echo '<script>alert("File troppo grande. Dimensione massima: ' . $maxSize . 'KB")</script>';
					
				} else {

					if ( move_uploaded_file($_FILES['pdf']['tmp_name'], $target_file)){
						

			$url="demarche/".$_FILES["pdf"]["name"];
			

			if($titre){
				
				$insert = $db->prepare("INSERT INTO demarche VALUES('','".$titre."','".$url."')");
				$insert->execute();
				echo "<script>alert('Le fichier  a été téléchargé et sauvegardé avec succès.')
		    		document.location =location.href='gestionDemarches.php?action=add'</script>";
				
				}else{
				
					echo "<script>alert('Veuillez remplir tous les champs')</script>";	
			}
			
			}else{
			
				echo '<script>alert("Le document '.$_FILES['pdf']["name"].' n\'est pas télécharger.\n\nVeuillez réessayer svp !")</script>';
			} #endIF
			} #endIF
		}
		}
?>
<html>
<head>
	<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
</head>
<body>
	<form id="monForm" action="" method="post" enctype="multipart/form-data" style="width:500px;">
			<div>
				<label>Titre de la démarche :</label>
				<input type="text" name="titre" maxlength="35"/>
			</div>	
				
			<div>
					<label>Démarche (fichier.pdf):</label><a class="info">
					<img id="info" src="images/info.PNG"><span>Attention aux caractères spéciaux du fichier !
					 Rennomez-le avant le téléchargement</span></a>
					<input type="file" name="pdf" value=""/>
			</div>
			
			<div>
					<input type="submit" name="submit" value="Ajouter" />
			</div>

	</form>
</body>
</html>


<?php 
		
		}else if($_GET['action'] == 'modify/delete'){

			$select = $db->prepare("SELECT * FROM demarche");
			$select->execute();
			
			if($trouve = $select->rowCount()) $nbLignes = true;
			else $nbLignes = false;
			
			if($nbLignes){
?>
		<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
			<table id="tableau" summary="Liste des clients">
				<thead style="background-color:lightgrey;">
					<tr>
						<th scope="col">Titre de la demarche</th>
						<th scope="col">Fichier(cliquez pour le visualiser)</th>
						<th scope="col">Modifier</th>
						<th scope="col">Supprimer</th>
					</tr>
				</thead>
									
				<tfoot>
					<tr>
						<td colspan="4">Liste de demarche</td>
					</tr>
				</tfoot>
							  		
				<tbody>
<?php 	
			while($s=$select->fetch(PDO::FETCH_OBJ)){
				echo '<tr><td>';
				echo $s->titre;
				echo '</td>';
				echo '<td>';
					$tableau_fichiers = glob($s->url);
					foreach ($tableau_fichiers as $filename) {
						   echo '<a href="'.$filename.'" target="_blank" id="pdf">'.basename($filename).'</a>';};
				echo '</td>';
				?>
					
						<td>
							<a href="?action=modify&amp;id=<?php echo $s->id_demarche; ?>"><input id="modifier" type="button" value='modifier' name='modifier'/></a>
						</td>
						
						<td>
							<a href="?action=delete&amp;id=<?php echo $s->id_demarche;?>&amp;url=<?php echo $s->url?>" onclick="return(confirm('Etes-vous sûr de vouloir supprimer cette entrée?'));"><input id="supprimer" type="button" value='supprimer' name='supprimer'/></a>
						</td>
					</tr>
			</tbody>
				
		<?php
													}
			echo'</table>';
			
			}else{
				echo '<div id="vide">Aucune démarche !</div>';
			}
			
			
		}else if($_GET['action'] == 'modify'){
						
			$id=$_GET['id'];
						
			$select = $db->prepare("SELECT * FROM demarche where id_demarche=$id");
			$select->execute();	
			$data = $select->fetch(PDO::FETCH_OBJ);			
?>				
				<html>
					<head>
							<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
					</head>
				<body>
					<form id="monForm" action="" method="post" enctype="multipart/form-data">
							<div>
								<label>Titre de la démarche :</label>
								<input type="text" name="titre" value="<?php echo $data->titre;?>"/>
							</div>	
								
							<div>
									<label>Démarche (fichier.pdf):</label>
									<input type="text" disabled value="<?php echo $data->url;?>"/>
									<input type="file" name="pdf" value="<?php echo $data->url;?>"/>
							</div>
							
							<div>
									<input type="submit" name="submit" value="Modifier" />
							</div>
							
						</form>
					</body>
				</html>
						
					
		<?php	if(isset($_POST['submit'])){
							
						$titre=$_POST['titre'];
						
						$target_dir = "demarche/";
							
						$target_file = $target_dir . basename($_FILES['pdf']["name"]);
							
						
						if (is_uploaded_file($_FILES['pdf']['tmp_name'])) {
								
							if ($_FILES['pdf']['type'] != "application/pdf") {
									
								echo '<script>alert("Erreur ce fichier **'.$_FILES['pdf']["name"].'** n\'est pas au format PDF.")</script>';
									
							} else {
						
								if ( move_uploaded_file($_FILES['pdf']['tmp_name'], $target_file)){
						
						
									$url="demarche/".$_FILES["pdf"]["name"];
						
									
						$update = $db->prepare("UPDATE demarche SET titre='".$titre."',url='".$url."' WHERE id_demarche=$id");
						$update->execute();
						
							echo '<script>alert("les modifications ont bien été effectuées");
				
							document.location =location.href="?action=modify/delete" </script>';
						//header('location:?action=modify/delete');
						
											
											
								}else{
													
							echo '<script>alert("Le document '.$_FILES['pdf']["name"].' n\'est pas télécharger.\n\nVeuillez réessayer svp !")</script>';
							
							} #endIF
						} #endIF
				}
		}
?>
						
		<?php
						
		}else if($_GET['action'] == 'delete'){
			
			$url = $_GET['url'];
			//retire galerie
			$urlPdf=substr($url,8);
			unlink("demarche/".$urlPdf);
											
							$id=$_GET['id'];
							$delete = $db->prepare("DELETE FROM demarche WHERE id_demarche=$id");
							$delete->execute();
							
							header('location:?action=modify/delete');
			
						}else{
									
							die('une erreur s\'est produite');
							
							}//fin de tous les conditions de Ajout/modification/suppression
		
			
}else{//si on a pas d'action on affiche les éléments de la base de donnée
		
	$num = 1;
	$select = $db->prepare("SELECT * FROM demarche");
	$select->execute();
			
	if($trouve = $select->rowCount()) $nbLignes = true;
	else $nbLignes = false;
			
	if($nbLignes){
?>
	<div class="a">
	<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
	<table id="liste">
		<tr>
			<th><h5>Position :</h5></th>
			<th><h5>Demarche :</h5></th>
		</tr>
			
<?php 
	while($s=$select->fetch(PDO::FETCH_OBJ)){
		echo '<tr>';
		echo '<td>'.$num.'</td>';
		echo "<td>$s->url</td></tr>";
		$num += 1;
												}
			echo'</table></div>';
				
		}else{
			echo '<div id="vide" style="font-size:12px">Liste vide ajouter une démarche => <a href = "?action=add">ICI</a> !</div>';
	}
						
}
			
			
}else{//si l'administrateur n'est plus logué on le redirige à la page connexion
			
		header('Location:espacePrive.php');
	}
?>