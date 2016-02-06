<?php
require_once ('../includes/connexion_bdd.php');
require_once ('includes/header.php');

if(isset($_SESSION['admin_id'])){
?>

<ul>
	<li><a href = "gestionGalerie.php" id="href" ><img alt="" src="images/home.PNG" class="home"></a></li>
	<li><a href = "?action=add" id="href">Ajouter une image</a></li>
	<li><a href = "?action=modify/delete" id="href"><i id="mod">Modifier</i> ou <i id="sup">Supprimer</i> une image</a></li>
</ul>

<?php
 
if(isset($_GET['action'])){

	if($_GET['action'] == 'add'){
			
		if(isset($_POST['submit'])){
			
			$nomFile=addslashes($_POST["nameFile"]);
			$descriptionFile=addslashes($_POST["descriptionFile"]);
			
			//Verifie le nom et la description du fichier
			if($nomFile&&$descriptionFile){

				if($_FILES["file"]["name"]){
					
					$j = 0;
					
					$target_path = "../../agnes_recup_art/ressources/images/galerie/";
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
		$urlImg=substr($target_path,48);
        $url=$urlImg;
        
        
        $ajoutImage=$db->query("INSERT INTO `recup_art`.`galerie` VALUES (NULL, '".$nomFile."', '".$descriptionFile."', '".$url."')");
        echo "<script>alert('Le fichier  a été téléchargé et sauvegardé avec succès.')
		    		document.location =location.href='gestionGalerie.php?action=add'</script>";
        
        
        } else { echo "<script> alert('Veuillez réessayer s'il vous plaît!') </script>";	}
        	
        	
        }else{ echo "<script>alert('***image trop large***')</script>";}
        	
        }//fin boucle for file        


}else{ echo '<script>alert("Aucun fichier renseigné.")</script>';}

}else{ echo '<script>alert("Le nom ou la description ne sont pas renseigné.")</script>';}

	}//fin submit
?>

				<script type="text/javascript" src="includes/ckeditorStandard/ckeditor.js"></script>
				<form action="" method="post" enctype="multipart/form-data">
					<link rel="stylesheet" type="text/css" href="../style/calendrierA.css" media="screen" />
 					<table id="tabAjoutEvent">
				    	
				    		<tr>
				    			<td colspan="2"><br>
				    				<label>Séléctionner une image à télécharger :</label><br>
				    				<input name="file[]" type="file" id="file"/>
				    			</td>
				    		</tr>
				    		
				    		<tr>	
								<td colspan="2"><br>
									<label>Nom :</label><br>
									<input type="text" name="nameFile" id="nomFile" size="60" value="<?php if(isset($nomFile)) echo $nomFile; ?>"/>
								</td>
							</tr>
							
							<tr>	
								<td colspan="2"><br>
									<label>Description :</label><br>
									<textarea rows="10" cols="50" id="description" name="descriptionFile" style="height: 200px; width:500px;resize:none;margin-right:25%;"><?php if(isset($descriptionFile)) echo $descriptionFile; ?></textarea>
										<script type="text/javascript">
											CKEDITOR.replace('descriptionFile');
										</script>
								</td>
							</tr>
							
							<tr>				
				    			<td colspan="2">
				    				<input type="submit" value="Télécharger" name="submit">
				    			</td>
				    		</tr>
					</table>
				</form>

<?php	

}else if($_GET['action'] == 'modify/delete'){

	include('includes/coBDD.php');
	
	$affImg=$db->query("SELECT * FROM galerie");
	
	if($trouve = $affImg->rowCount()) $nbLignes = true;
	else $nbLignes = false;
	
	if($nbLignes){
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
			<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
</head>
				<table id="tableau">
					<thead>
						<tr>
							<th>Nom / =>"<?php 	$nb=$db->query("select count(*) from galerie"); $res = $nb->fetchColumn();echo"$res";?>"Images dans la galerie</th>
							<th>Description</th>
							<th>Image</th>
							<th>Modifier</th>
							<th>Supprimer</th>
						</tr>
					</thead>
					<tbody>
<?php
			
	while ($data = $affImg->fetch()) {
		echo ('
							<tr>
								<th="row">
									<td>'.substr($data['titre'],0,25).'...</td>
									<td>'.substr($data['description'],0,10).'[...][...]'.substr($data['description'],-8,16).'</td>
									<td><img src="../../agnes_recup_art/ressources/images/galerie/'.$data['url'].'" style="width:50px;height:50px;"></td>
									<td><a href="?action=modify&amp;id='.$data['id_galerie'].'&url='.$data['url'].'"><input id="modifier" type="button" value="modifier" name="modifier"/></a></td>
									<td><a class="info"><img id="info" src="images/info.PNG" style="height:12px;width:12px;">
										<span>Pas de message de confirmation !</span></a><a href="?action=delete&amp;id='.$data['id_galerie'].'&url='.$data['url'].'"><input id="supprimer" type="button" value="supprimer" name="supprimer"/></a></td>
								</th>
							</tr'
		);
?>
			

<?php
	}	echo '</tbody>	
			</table>';
	}else{
		echo '<div id="vide">liste vide !</div>';
	}				
	
}else if($_GET['action'] == 'modify'){
				
	
				
	include('includes/coBDD.php');
	//$id = 1;
	$id=$_GET['id'];
	$affImg=$db->query("SELECT * FROM galerie WHERE id_galerie=$id");
		
	while ($data = $affImg->fetch()) {
		echo ('			
				<script type="text/javascript" src="includes/ckeditorStandard/ckeditor.js"></script>
				<form action="" method="post" enctype="multipart/form-data">
					<link rel="stylesheet" type="text/css" href="../style/calendrierA.css" media="screen" />
 					<table id="tabAjoutEvent">

							<tr>
								<td colspan="2"><br>
									<label>Nom</label><br>
									<input type="text" name="titre" size="60" value="'.$data['titre'].'"/>
								</td>
							</tr>
				
							<tr>
								<td colspan="2"><br>
								<label>Description</label><br>
								<textarea name="description" style="height: 200px; width:500px;resize:none;margin-right:25%;">'.$data['description'].'</textarea></td>
										<script type="text/javascript">
											CKEDITOR.replace("description");
										</script>
							</tr>
								<td colspan="2"><br>
								<label>Image</label><br>
								<img src="../../agnes_recup_art/ressources/images/galerie/'.$data['url'].'" style="width:50px;">
								Changer l\'image<input name="file[]" type="file" id="file"/>
								<input name="img" type="hidden" value="../../agnes_recup_art/ressources/images/galerie/'.$data['url'].'" size="50" />
								</td>
							</tr>

							<tr>
								<td colspan="4">
									<input type="submit" name="submit" value="Modifier" />
								</td>
							</tr>
				</table>
				</form'
			);
	}?>	
			
<?php	if(isset($_POST['submit'])){
					
				$titre= addslashes($_POST['titre']);
				$description=addslashes($_POST['description']);
				$img = $_POST["img"];
				
				//Verifie le nom et la description du fichier
				if($titre&&$description){
				
					if($_FILES["file"]["name"]){
							
						$j = 0;
							
						$target_path = "../../agnes_recup_art/ressources/images/galerie/";
						for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
								
							$validextensions = array("jpeg", "jpg", "png","JPEG", "JPG", "PNG");
							$ext = explode('.', basename($_FILES['file']['name'][$i]));
							$file_extension = end($ext);
								
							$target_path = $target_path . md5(uniqid()) . "." . $ext[count($ext) - 1];
							$j = $j + 1;
								
							if (($_FILES["file"]["size"][$i] < 5000000)
									&& in_array($file_extension, $validextensions)) {
										if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $target_path)) {
							
					$urlImg=substr($target_path,48);		
					$url= $urlImg;
					$remove_url = $_GET['url'];
					unlink("../../agnes_recup_art/ressources/images/galerie/".$remove_url);
											
					$update = $db->prepare("UPDATE galerie SET titre='".$titre."',description='".$description."',url='".$url."' WHERE id_galerie=$id");
					$update->execute();
				
						echo '<script>alert("les modifications ont bien été effectuées");
						
						document.location =location.href="?action=modify/delete" </script>';
					header('location:?action=modify/delete');
				
					} else { echo "<script>alert('Veuillez réessayer s'il vous plaît!')</script>";}
					 
					 
					}else{

						
						$url = $_GET['url'];
						$update = $db->prepare("UPDATE galerie SET titre='".$titre."',description='".$description."',url='".$url."' WHERE id_galerie=$id");
						$update->execute();}
					 
						header('location:?action=modify/delete');
						
					}//fin boucle for file
					
					}else{ echo '<script>alert("Aucun fichier renseigné.")</script>';}
					
					}else{ echo '<script>alert("Le nom ou la description ne sont pas renseigné.")</script>';}
					
	}// fin submit
?><?php				
}else if($_GET['action'] == 'delete'){
	
	$url = $_GET['url'];
	//retire galerie
	$urlImg=substr($url,48);
	unlink("../../agnes_recup_art/ressources/images/galerie/".$urlImg);
	
	$id=$_GET['id'];
	$delete = $db->prepare("DELETE FROM galerie WHERE id_galerie=$id");
	$delete->execute();
	
	header('location:?action=modify/delete');
	
	
}else{
			
	die('une erreur s\'est produite');
	
	}//fin de tous les conditions de Ajout/modification/suppression

	
}else{//si on a pas d'action on affiche les elements de la base de donnée
	$num = 1;	
	$select = $db->prepare("SELECT * FROM galerie ORDER BY titre");
	$select->execute();
	
	if($trouve = $select->rowCount()) $nbLignes = true;
	else $nbLignes = false;
	
	if($nbLignes){
?>
<div class="a">
<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
	<table id="liste">
			<thead>nombre d'images "<?php 	$nb=$db->query("select count(*) from galerie"); $res = $nb->fetchColumn();echo"<thead>$res</thead>";?>"</thead>
		<tr>
			<th><h5>Position :</h5></th>
			<th><h5>liste des galeries:</h5></th>
		</tr>

<?php 
	while($s=$select->fetch(PDO::FETCH_OBJ)){
		echo '<tr>';
		echo '<td>'.$num.'</td>';
		echo "<td>$s->titre</td></tr>";
		$num += 1;
									}
		echo'</table></div>';
		
		}else{
			echo '<div id="vide" style="font-size:12px">Liste vide ajouter une image => <a href = "?action=add">ICI</a> !</div>';
		}
		
	}

}else{//si l'administrateur n'est plus logué on le redirige à la page connexion
			
		header('Location:espacePrive.php');
	}
?>