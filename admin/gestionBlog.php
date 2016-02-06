<?php
require_once ('../includes/connexion_bdd.php');
require_once ('includes/header.php');

if(isset($_SESSION['admin_id'])){
?>
<ul>
	<li><a href = "gestionBlog.php" id="href" ><img alt="" src="images/home.PNG" class="home"></a></li>
	<li><a href = "?action=add" id="href">Ajouter un article</a></li>
	<li><a href = "?action=modify/delete" id="href"><i id="mod">Modifier</i> ou <i id="sup">Supprimer</i> article</a></li>
</ul>
<?php
 
if(isset($_GET['action'])){

	if($_GET['action'] == 'add'){
			
		if(isset($_POST['submit'])){
			
		    $nameFile=$_POST["nameFile"];
		    $date=$_POST["date"];
		    $texte=$_POST["texte"];
		    $url_video=$_POST["url_video"];

		    if($nameFile&&$url_video==""){	    

			$j = 0;
			$target_path = "../../agnes_recup_art/ressources/images/blog/";

			for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
				
				$validextensions = array("jpeg", "jpg", "png","JPEG", "JPG", "PNG");
				$ext = explode('.', basename($_FILES['file']['name'][$i]));
				$file_extension = end($ext);
			
				$target_path = $target_path . md5(uniqid()) . "." . $ext[count($ext) - 1];
				$j = $j + 1;
			
				if (($_FILES["file"]["size"][$i] < 5000000)
						&& in_array($file_extension, $validextensions)) {
							if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $target_path)) {
				
				$urlImg=substr($target_path,45);
				$url= $urlImg;
			

				$insert = $db->prepare("INSERT INTO `recup_art`.`blog` VALUES ('','".$nameFile."','".$date."','".$texte."',
        		'".$url."',NULL)");
				$insert->execute();
				
					echo "<script> alert('Vos fichier ont été sauvegarder avec succès!') </script>";
					
					} else {
					
						echo "<script> alert('Veuillez réessayer s'il vous plaît!') </script>";
					}
					
					
					}else{
					
						echo "<script>alert('***image trop large***')</script>";
					}
					
					}
						
			}else if($nameFile&&$url_video<>""){
				
				$insert = $db->prepare("INSERT INTO `recup_art`.`blog` VALUES (NULL,'".$nameFile."','".$date."','".$texte."',
        		NULL,'".$url_video."')");
				$insert->execute();
				
				echo "<script> alert('Vos fichier ont été sauvegarder avec succès!') </script>";			
			
}else{


	echo "<script> alert('veuillez indiquer le titre')</script>";

}//fin verification si champs rempli
	
}//fin submit
?>
		<!DOCTYPE html>
			<html>
				<head>
					 <meta charset="utf-8" />
				    <link rel="stylesheet" type="text/css" href="../style/calendrierA.css" media="screen" />
				    <script type="text/javascript" src="includes/ckeditorStandard/ckeditor.js"></script>
					<!-------Including jQuery from google------>
			        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
			        <script src="style/script.js"></script>
					
					<!-------Including CSS File------>
			        <link rel="stylesheet" type="text/css" href="style/style.css">
			  </head>
			  <body>    
				<form action=""  method="post" enctype="multipart/form-data">
					<table id="tabAjoutEvent">
					
 					 <h3 style="font-size:25px;text-align:center;">Attention vous devez coller un <u> iframe(lien) youtube</u> ou </u>séléctionner <u>une image à télécharger :</h3>
 					 						
								  <tr>
								    <td><label>Url <a style="color: red;font-size:18px;" href="https://support.google.com/youtube/answer/171780?hl=fr"> voici un tutoriel cliquer ici </a></label>
										<input type="text" style="width:90%;" font-family:Garamond;font-weight:bold;" name="url_video" placeholder = 'Exemple : <iframe width="560" height="315" src="https://www.youtube.com/embed/kfvxmEuC7bU" frameborder="0" allowfullscreen></iframe>'></td>
									</tr>
									<tr>
								  	<td><b style="color: red;font-size:25px;">ou</b></td>
									</tr>
									<tr>
										<td><input name="file[]" type="file" id="file"/></td>
								  </tr>
								 
								<tr>
									<td colspan="2">
										<label>Titre :</label><br>
								 		<input type="text" name="nameFile" id="nomFile" size="40" value="<?php if(isset($nameFile)) echo $nameFile?>"/>
								 	</td>
								</tr>

								<tr>
									<td colspan="2">
										<label>Date :</label><br>
										<input type="date" name="date" value="<?php $date=date("Y-m-d"); echo "$date";?>"/>
									</td>
								</tr> 
								
								<tr>
									<td colspan="4">
										<label>Texte :</label><br>
										<textarea id="editor1" name="texte"><?php if(isset($texte)) echo $texte?></textarea>
										<script type="text/javascript">
											CKEDITOR.replace('texte');
										</script>
									</td>
								 </tr>	
								
								<tr>
				    				<td colspan="2">
				    					<input type="submit" value="Télécharger" name="submit">
				    				</td>
				    			</tr>
				    	</div>
				    </table>
				</form>
			</body>
			</html>

<?php	

}else if($_GET['action'] == 'modify/delete'){

	include('includes/coBDD.php');
	
	$affImg=$db->query("SELECT *,DATE_FORMAT(date, '%d / %m / %Y') AS date FROM blog ORDER BY titre");
	
	if($trouve = $affImg->rowCount()) $nbLignes = true;
	else $nbLignes = false;
		
	if($nbLignes){
?>
<!DOCTYPE html>
<html>
<head>
 <meta charset="utf-8" />
	<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
</head>
				<table id="tableau">
					<thead>
						<tr>
							<th>Titre / =>"<?php $nb=$db->query("select count(*) from blog"); $res = $nb->fetchColumn();echo"$res";?>"Articles</th>
							<th>Date</th>
							<th>Texte</th>
							<th>Image</th>
							<th>Lien de la video</th>
							<th>Modifier</th>
							<th>Supprimer </th>
						</tr>
					</thead>
					<tbody>
<?php
	while ($data = $affImg->fetch()) {
		if($data['url_video']==""){$data['url_video']="Ce blog est representé par une image";};
		echo ('
							<tr>
								<th="row">
									<td>'.substr($data['titre'],0,18).'</td>
									<td>'.$data['date'].'</td>
									<td>'.substr($data['texte'],0,20).'...</td>
									<td><img src="../../agnes_recup_art/ressources/images/blog/'.$data['url_photo'].'" style="width:50px;height:50px"></td>
									<td>'.$data['url_video'].'</td>
									<td><a href="?action=modify&amp;id='.$data['id_blog'].'&url='.$data['url_photo'].'"><input id="modifier" type="button" value="modifier" name="modifier"/></a></td>
									<td><a href="?action=delete&amp;id='.$data['id_blog'].'&url='.$data['url_photo'].'"><i class="info"><img id="info" src="images/info.PNG" style="height:12px;width:12px;">
										<span>Pas de confirmation !</span></i><input id="supprimer" type="button" value="supprimer" name="supprimer"/></a></td>
								</th>
							</tr'
		);
?>
			

<?php
	}	echo '</tbody>	
			</table>
		</html>';
	
	}else{
		echo '<div id="vide">Aucun Article !</div>';
	}		
	
}else if($_GET['action'] == 'modify'){
				
	
				
	include('includes/coBDD.php');
	//$id = 1;
	$id=$_GET['id'];
	$affImg=$db->query("SELECT *,DATE_FORMAT(date, '%d/%m/%Y') AS date FROM blog WHERE id_blog=$id");
		
	while ($data = $affImg->fetch()) {
		if($data['url_video']=="NULL"){$data['url_video']="";};

		echo ('			
				
				<form action="" method="POST" enctype="multipart/form-data">
				<script type="text/javascript" src="includes/ckeditorStandard/ckeditor.js"></script>
				<link rel="stylesheet" type="text/css" href="../style/calendrierA.css" media="screen" />
					<table id="tabAjoutEvent">
				
							<tr>
								<td><label>Image</label><img src="../../agnes_recup_art/ressources/images/blog/'.$data['url_photo'].'" style="width:50px;height:50px"><br>
											<input name="img" type="hidden" value="../../agnes_recup_art/ressources/images/blog/'.$data['url_photo'].'" size="50" /><br>
											Changer l\'image<input name="file[]" type="file" id="file"/></td>
					
								<td><input type="hidden" name="url_video" value="'.$data['url_video'].'" size="50" font-family:Garamond;font-weight:bold;" placeholder = "ex: https://www.youtube.com/watch?v=PnKF7DaB26I"/></td>
							</tr>
				
							<tr>
								<td colspan="2"><br>
									<label>Titre</label><br>
									<input type="text" name="nomFile" value="'.$data['titre'].'" size="90"/>
								</td>
							</tr>
				
							<tr>
								<td colspan="2">
									<label>Date</label><br>
									<input type="date" name="date" value="'.$date=date("Y-m-d").'"/>
								</td>
							</tr>
				
							<tr>
								<td colspan="2"><label>Texte</label><br>
									<textarea id="editor1" name="texte">'.($data['texte']).'</textarea><script type="text/javascript">
											CKEDITOR.replace("texte");
									</script>
								</td>
							</tr>
						
							<tr>
								<td colspan="2">
									<input type="submit" name="submit" value="Modifier" />
								</td>
							</tr>
					
				</table>
				</form'
			);
	}?>	
			
<?php
	if(isset($_POST['submit'])){

	$nomFile=$_POST["nomFile"];
	$date=$_POST["date"];
	$texte=$_POST["texte"];
	$url_video=$_POST["url_video"];

	$img = $_POST["img"];
	
	if($_FILES['file']['name']&&$nomFile&&$url_video==""){
	
	$j = 0;
	
	$target_path = "../../agnes_recup_art/ressources/images/blog/";
	for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
	
		$validextensions = array("jpeg", "jpg", "png","JPEG", "JPG", "PNG");
		$ext = explode('.', basename($_FILES['file']['name'][$i]));
		$file_extension = end($ext);
	
		$target_path = $target_path . md5(uniqid()) . "." . $ext[count($ext) - 1];
		$j = $j + 1;
	
		if (($_FILES["file"]["size"][$i] < 5000000)
				&& in_array($file_extension, $validextensions)) {
					if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $target_path)) {
	
				$urlImg=substr($target_path,45);
				$url= $urlImg;
				$remove_url = $_GET['url'];
				unlink("../../agnes_recup_art/ressources/images/blog/".$remove_url);
							
				$update = $db->prepare("UPDATE blog SET titre='".$nomFile."',date='".$date."',texte='".$texte."',
						url_photo='".$url."',url_video=NULL WHERE id_blog=$id");
				
				$update->execute();

				
					echo '<script>alert("les modifications ont bien été effectuées");
					
					document.location =location.href="?action=modify/delete" </script>';
				
				
				} else {
				
					echo "<script>alert('Veuillez réessayer s'il vous plaît!') </script>";
				}
				
				}else{
					
					$url = $_GET['url'];
					$update = $db->prepare("UPDATE blog SET titre='".$nomFile."',date='".$date."',texte='".$texte."',
        					url_photo='".$url."' WHERE id_blog=$id");
					$update->execute();
					
					echo '<script>alert("les modifications ont bien été effectuées");
					
					document.location =location.href="?action=modify/delete" </script>';
				}
				}
				
				
				
			}else if($nomFile&&$url_video<>""){
				

					$update = $db->prepare("UPDATE blog SET titre='".$nomFile."',date='".$date."',texte='".$texte."',
        		url_photo=NULL WHERE id_blog=$id");
					$update->execute();
					
					echo "<script> alert('les modifications ont bien été effectuées!') </script>";
									
			}else{
				echo" <script>alert('verifiez si vous avez rempli tous les champs')</script>";
									
}

}
?>
				
<?php
				
}else if($_GET['action'] == 'delete'){
	
	$url = $_GET['url'];
	//retire galerie
	$urlImg=substr($url,45);
	unlink("../../agnes_recup_art/ressources/images/blog/".$urlImg);
	

	$id=$_GET['id'];
	$delete = $db->prepare("DELETE FROM blog WHERE id_blog=$id");
	$delete->execute();
	
	header('location:?action=modify/delete');
	
}else{
			
	die('une erreur s\'est produite');
	
	}//fin de tous les conditions de Ajout/modification/suppression

	
}else{//si on a pas d'action on affiche les elements de la base de donnée
	$num = 1;	
	$select = $db->prepare("SELECT * FROM blog ORDER BY titre");
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
			<thead>nombre d'articles "<?php $nb=$db->query("select count(*) from blog"); $res = $nb->fetchColumn();echo"<thead>$res</thead>";?>"</thead>
		<tr>
			<th><h5>Position : </h5></th>
			<th><h5>liste des article actuel : </h5></th>
		</tr>

<?php 
	while($s=$select->fetch(PDO::FETCH_OBJ)){
		echo '<tr>';
		echo '<td>'.$num.'</td>';
		echo "<td>$s->titre</td></tr>";
		$num += 1;
									}
		echo'</table></div></html>';
		
		}else{
			echo '<div id="vide" style="font-size:12px">Liste vide ajouter un article => <a href = "?action=add">ICI</a> !</div>';
		}
	
		
	}

}else{//si l'administrateur n'est plus logué on le redirige à la page connexion
			
		header('Location:espacePrive.php');
	}
	
?>