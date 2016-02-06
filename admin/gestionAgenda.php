<?php
require_once ('../includes/connexion_bdd.php');
require_once ('includes/header.php');

if(isset($_SESSION['admin_id'])){
?>
<ul>
	<li><a href = "gestionAgenda.php" id="href" ><img alt="" src="images/home.PNG" class="home"></a></li>
	<li><a href = "?action=add" id="href">Ajouter un événement</a></li>
	<li><a href = "?action=modify/delete" id="href"><i id="mod">Modifier</i> ou <i id="sup">Supprimer</i> événement</a></li>
</ul>
<?php
 
if(isset($_GET['action'])){

	if($_GET['action'] == 'add'){
			

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Gestion de calendrier | Ajout d'événement</title>
    <link rel="stylesheet" type="text/css" href="../style/calendrierA.css" media="screen" />
    <script type="text/javascript" src="includes/ckeditorStandard/ckeditor.js"></script>
</head>
<body>
<?php
		// Variables vides pour les valeurs par défaut des champs

		
		if(isset($_POST['envoi'])){

			if($_FILES["file"]["name"]){

				$j = 0;
				$target_path = "../../agnes_recup_art/ressources/images/agenda/";
				
				for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
				
					$validextensions = array("jpeg", "jpg", "png","JPEG", "JPG", "PNG");
					$ext = explode('.', basename($_FILES['file']['name'][$i]));
					$file_extension = end($ext);
						
					$target_path = $target_path . md5(uniqid()) . "." . $ext[count($ext) - 1];
					$j = $j + 1;
						
					if (($_FILES["file"]["size"][$i] < 5000000)
							&& in_array($file_extension, $validextensions)) {
								if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $target_path)) {	
						
					//Mettre l'image dans la bdd.
					//$url="agenda/".$_FILES["fileToUpload"]["name"];
					$urlImg=substr($target_path,47);
					$url= $urlImg;
			
					// Traitement de l'envoi de l'événement
					$titre = htmlentities(addslashes($_POST['titre']));
					$description = $_POST['description'];
					$dateDebut = htmlentities($_POST['debut']);
					$dateFin = htmlentities($_POST['fin']);
			
			
					
        			$insert = $db->prepare("INSERT INTO agenda VALUES ('', '$dateDebut', '$dateFin', '$titre', '$description','$url')");
					$insert->execute();
					
					
					
					echo '<script>alert("Evénement  enregistré !");
					document.location =location.href="gestionAgenda.php?action=add"</script>';
					

		
				} else { echo "<script> alert('Veuillez réessayer s'il vous plaît!') </script>";	}
					
					
				}else{ echo "<script>alert('***image trop large***')</script>";}
					
				}//fin boucle for file


				}else{echo 'choisir image';}
			
}//end submit
?>
    
    <!-- Formulaire d'envoi -->
	<h1>Ajouter un événement</h1>
    
    <form method="post" action="" enctype="multipart/form-data">
    	<table id="tabAjoutEvent">
        	<tr>
            	<td><label>Du : <input type="date" name="debut" value="<?php if(isset($Debut)) echo $debut; ?>" /></label></td>
                <td><label>Au : <input type="date" name="fin" value="<?php if(isset($Fin)) echo $fin; ?>" /></label></td>
            </tr>
       		<tr>
       			<td colspan="2"><br/>
                	<label for="titre">Titre de l'événement :</label><br/>
       				<input type="text" name="titre" id="titre" size="70" value="<?php if(isset($titre)) echo $titre; ?>" /><br/><br/>
                </td>
       		</tr>
            <tr>
            	<td colspan="2">
       				<label for="description">Description de l'événement :</label><br/>
       				<textarea rows="10" cols="50" id="editor1" name="description" style="height: 200px; width:500px;resize:none;">
       				<?php if(isset($description)) echo $description; ?></textarea>
       				
       				<script type="text/javascript">
						CKEDITOR.replace('description');
					</script>
                </td>
            </tr>
            
            <tr>
            	<td colspan="2">
       				<label for="fileToUpload">Séléctionner une image à télécharger :</label><br/>
            		<input name="file[]" type="file" id="file"/>
            </tr>
            
            <tr>
            	<td colspan="2"><input type="submit" class="submit" name="envoi" value="Envoyer" onclick='return(confirm("Confirmez-vous la date de debut et de fin ? Vous ne pourrez plus modifier ces deux champs plus tard !"));'/></td>
            </tr>
       </table>
    </form>
    
    <p class="centre"><br/><a href="index.php">Revenir à l'accueil</a></p>
</body>
</html>

<?php	

}else if($_GET['action'] == 'modify/delete'){
?>

	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	    <link rel="stylesheet" type="text/css" href="../style/calendrier.css" media="screen" />
	</head>
	<body>
<?php
			
			// Récupération des événements
			$select = $db->prepare("SELECT * FROM agenda ");
			$select->execute();
			
			if($trouve = $select->rowCount()) $nbLignes = true;
			else $nbLignes = false;
			
			if($nbLignes){
?>
			<table id="tableau" class="listeEvent">
				<thead>
					<tr>
						<th>titre / =>"<?php 	$nb=$db->query("select count(*) FROM agenda"); $res = $nb->fetchColumn();echo"$res";?>"événements</th>
						<th>contenu</th>
						<th>date debut</th>
						<th>date fin</th>
						<th>image associés</th>
						<th>Modifier</th>
						<th>Supprimer</th>
					</tr>
				</thead>
				<tbody>
<?php			
			while($s=$select->fetch()) {
				 //substr($s['contenu_evenement'],0,20);
				echo '<tr>
					<td>'.html_entity_decode(substr($s['titre'],0,25)).'...</td>
					<td>'.html_entity_decode(substr($s['description'],0,15)).'...</td>
					<td>'.html_entity_decode($s['date_debut']).'</td>
					<td>'.html_entity_decode($s['date_fin']).'</td>
					<td><img src="../../agnes_recup_art/ressources/images/agenda/'.$s['url_photo'].'" style="width:50px;height:50px;"></td>
					<td><a href="?action=modify&amp;id='.$s['id_agenda'].'&url='.$s['url_photo'].'"><input id="modifier" type="button" value="modifier" name="modifier"/></a></td>
					<td><a href="?action=delete&amp;id='.$s['id_agenda'].'&url='.$s['url_photo'].'"><i class="info"><img id="info" src="images/info.PNG" style="height:12px;width:12px;">
										<span>Pas de confirmation !</span></i><input id="supprimer" type="button" value="supprimer" name="supprimer"/></a></td>
				
				</tr>';
			}
echo 			'</tbody>
				</table>
				<br/><br/>';
}else{
	echo '<div id="vide">Aucun événement !</div>';
}
?>
	    
	    
	    <p class="centre"><a href="index.php">Revenir à l'accueil</a></p>
	</body>
	</html>
<?php 					

}else if($_GET['action'] == 'modify'){
				
	
				
	include('includes/coBDD.php');
	//$id = 1;
	$id=$_GET['id'];
	$affImg=$db->query("SELECT * FROM agenda WHERE id_agenda=$id");
		
	while ($data = $affImg->fetch()) {
		echo('			
				
				<form action="" method="POST" enctype="multipart/form-data">
				<link rel="stylesheet" type="text/css" href="../style/calendrierA.css" media="screen" />
				<script type="text/javascript" src="includes/ckeditorStandard/ckeditor.js"></script>
					<table id="tabAjoutEvent">
						<tr>
							<td><label>Du :<input type="text" name="debut" value="'.$data['date_debut'].'" disabled/></label></td><br>
							<td><label>Au :<input type="text" name="fin" value="'.$data['date_fin'].'" disabled/></label></td>
						</tr>
				
						<tr>
							<td colspan="2"><br/>
				
                			<label>Titre de l\'événement :</label><br/>
							<input type="text" size="40" name="titre" value="'.$data['titre'].'"/></td>
				
						</tr>
						
				<tr>
					<td colspan="2"><label>Image</label><img src="../../agnes_recup_art/ressources/images/agenda/'.$data['url_photo'].'" style="width:50px;"><input name="img" type="hidden" value="../../agnes_recup_art/ressources/images/agenda/'.$data['url_photo'].'" size="50" /><br>
											Changer l\'image<input name="file[]" type="file" id="file"/></td>	
				
				</tr>
				<tr>
					<td colspan="4"><label>contenu</label><textarea name="description" style="height: 200px; width:500px;resize:none;font-family:verdana;">'.($data["description"]).'</textarea>
						<script type="text/javascript">
							CKEDITOR.replace("description");
						</script>
					</td>
				
				
				
				</tr>

							<tr>
								<td colspan="4" style="text-align:center;">
									<input type="submit" name="submit" value="Modifier" />
								</td>
							</tr>

				</table>
				</form'
			);
}?>	
			
<?php	if(isset($_POST['submit'])){
	

	$titre = htmlentities(addslashes($_POST['titre']));
	$description = $_POST['description'];
	$img = $_POST["img"];

	$j = 0;
	
	$target_path = "../../agnes_recup_art/ressources/images/agenda/";
	for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
	
		$validextensions = array("jpeg", "jpg", "png","JPEG", "JPG", "PNG");
		$ext = explode('.', basename($_FILES['file']['name'][$i]));
		$file_extension = end($ext);
	
		$target_path = $target_path . md5(uniqid()) . "." . $ext[count($ext) - 1];
		$j = $j + 1;
	
		if (($_FILES["file"]["size"][$i] < 5000000)
				&& in_array($file_extension, $validextensions)) {
					if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $target_path)) {
	
				$urlImg=substr($target_path,47);
				$url= $urlImg;
				$remove_url = $_GET['url'];
				unlink("../../agnes_recup_art/ressources/images/agenda/".$remove_url);
	
		if(!empty($titre) && !empty($description)){
				
													
				$update = $db->prepare("UPDATE agenda SET titre='".$titre."',description='".$description."', url_photo='".$url."' WHERE id_agenda=$id");
				$update->execute();
				

				
				header('location:?action=modify/delete');
				
				}else{
						
					echo '<script>alert("Titre ou description de l\'événement non renseigné.")</script>';
						
				}
				
				} else {
				
					echo "<script>alert('Veuillez réessayer s'il vous plaît!') </script>";
				}
				
				}else{
				$url = $_GET['url'];
				$update = $db->prepare("UPDATE agenda SET titre='".$titre."',description='".$description."', url_photo='".$url."' WHERE id_agenda=$id");
				$update->execute();
						
					header('location:?action=modify/delete');
				}
	}
	}
				
}else if($_GET['action'] == 'delete'){

	$url = $_GET['url'];
	
	//retire image du dossier
	$urlImg=substr($url,47);
	unlink("../../agnes_recup_art/ressources/images/agenda/".$urlImg);
	
	$id=$_GET['id'];
	
	$delete = $db->prepare("DELETE FROM agenda WHERE id_agenda = " .$id);
	$delete->execute();
	
	
	header('location:?action=modify/delete');
	
}else{
			
	die('une erreur s\'est produite');
	
	}//fin de tous les conditions de Ajout/modification/suppression

	
}else{//si on a pas d'action on affiche les elements de la base de donnée
	$num = 1;	
	$select = $db->prepare("SELECT * FROM agenda");
	$select->execute();
	
	if($trouve = $select->rowCount()) $nbLignes = true;
	else $nbLignes = false;
		
	if($nbLignes){
?>
<div class="a" style="width: 490px;">
<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
	<table id="liste">
		<thead>nombre d'événements "<?php $nb=$db->query("select count(*) from agenda"); $res = $nb->fetchColumn();echo"<thead>$res</thead>";?>"</thead>
		<tr>
			<th><h5>Position:</h5></th>
			<th><h5>Liste des événements:</h5></th>
			<th><h5>Date debut:</h5></th>
			<th><h5>Date fin:</h5></th>
		</tr>

<?php 
	while($s=$select->fetch(PDO::FETCH_OBJ)){
		echo '<tr>';
		echo '<td>'.$num.'</td>';
		echo "<td>$s->titre</td>";
		echo "<td>$s->date_debut</td>";
		echo "<td>$s->date_fin</td></tr>";
		$num += 1;
									}
		echo'</table></div>';
		
		}else{
			echo '<div id="vide" style="font-size:12px">Liste vide ajouter un événement => <a href = "?action=add">ICI</a> !</div>';
		}
		
	}
}else{//si l'administrateur n'est plus logué on le redirige à la page connexion
			
		header('Location:espacePrive.php');
}