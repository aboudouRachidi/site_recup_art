<?php
require_once ('../includes/connexion_bdd.php');
require_once ('includes/header.php');

if(isset($_SESSION['admin_id'])){
?>
<ul>
	<li><a href = "gestionProduits.php" id="href" ><img alt="" src="images/home.PNG" class="home"></a></li>
	<li><a href = "?action=add" id="href">Ajouter un nouveau produit</a></li>
	<li><a href = "?action=modify/delete" id="href"><i id="mod">Modifier</i> ou <i id="sup">Supprimer</i> un produit</a></li>
	<li><a href = "?action=addPicture" id="href">Ajouter photo produit</a></li>
	<li><a href = "?action=modify/deletePicture" id="href"><i id="mod">Modifier</i> ou <i id="sup">Supprimer</i> une photo</a></li>
</ul>
<?php
 
if(isset($_GET['action'])){

	if($_GET['action'] == 'add'){
			
		if(isset($_POST['submit'])){

			$nom=addslashes($_POST['nom']);
			$description=addslashes($_POST['description']);
			$argumentaire=addslashes($_POST['argumentaire']);
			$dimensions=$_POST['dimensions'];
			$prix=$_POST['prix'];
			$type_livraison=addslashes($_POST['type_livraison']);
			$frais_livraison=$_POST['frais_livraison'];
			$disponibilite=$_POST['disponibilite'];	
			$categorie = $_POST['categorie'];
			$materiaux = $_POST['materiaux'];
			$couleur = $_POST['couleur'];

			if ($_POST['materiaux2'] == "NULL" && $_POST['couleur2'] == "NULL"){
				
			$materiaux = array(
					'id_materiaux' => $_POST['materiaux'],
					'id_materiaux1' => $_POST['materiaux1'],
					);
					
			$couleur = array(
					'id_couleur' => $_POST['couleur'],
					'id_couleur1' => $_POST['couleur1'],
					);
			
			}elseif ($_POST['materiaux2'] == "NULL"){
				
				$materiaux = array(
						'id_materiaux' => $_POST['materiaux'],
						'id_materiaux1' => $_POST['materiaux1'],
				);
					
				$couleur = array(
						'id_couleur' => $_POST['couleur'],
						'id_couleur1' => $_POST['couleur1'],
						'id_couleur2' => $_POST['couleur2'],
				);
				
			}elseif ($_POST['couleur2'] == "NULL"){
				
				$materiaux = array(
						'id_materiaux' => $_POST['materiaux'],
						'id_materiaux1' => $_POST['materiaux1'],
						'id_materiaux3' => $_POST['materiaux3'],
				);
					
				$couleur = array(
						'id_couleur' => $_POST['couleur'],
						'id_couleur1' => $_POST['couleur1'],

				);	
				
			}else{
				
				$materiaux = array(
						'id_materiaux' => $_POST['materiaux'],
						'id_materiaux1' => $_POST['materiaux1'],
						'id_materiaux2' => $_POST['materiaux2'],
				);
					
				$couleur = array(
						'id_couleur' => $_POST['couleur'],
						'id_couleur1' => $_POST['couleur1'],
						'id_couleur2' =>$_POST['couleur2']
				);
				
			}
			

		if($_POST['materiaux'] == $_POST['materiaux1'] || $_POST['couleur'] == $_POST['couleur1']){
		
			echo '<script>alert("Vous devez renseigné deux materiaux et deux couleurs différents !")</script>';
		
		}else{
			
		if($nom&&$description&&$argumentaire&&$dimensions&&$prix&&$type_livraison&&$frais_livraison
			&&$categorie!=="NULL"&&$materiaux!=="NULL"&&$couleur!=="NULL"){
				
				$exist = $db->query("SELECT * FROM produit WHERE nom_produit = '$nom'");
				
				if($exist->fetchColumn()){
					
					echo '<script>alert("Ce produit \"'.$nom.'\" existe déjà")</script>';
					
				}else{
					
				if($_FILES["file"]["name"]){
					
					$j = 0;
					
					$target_path = "../../agnes_recup_art/ressources/images/PhotoProduit/";
					
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
		$urlImg=substr($target_path,53);
        $url=$urlImg;

				$insertP = $db->prepare("INSERT INTO produit VALUES('','".$nom."','".$argumentaire."','".$description."',
						'".$dimensions."','".$prix."','".$type_livraison."','".$frais_livraison."','".$disponibilite."',
						'".$categorie."','".$url."')");
				$insertP->execute();
				

				$idP = $db->lastInsertId();
				
				foreach ($materiaux as $materiau=>$idM) {
					
				$insert = $db->prepare("INSERT INTO produit_materiaux VALUES('$idP','".$idM."')");
				$insert->execute();
				
				}
				
				foreach ($couleur as $couleurs=>$idC){
					
				$insert = $db->prepare("INSERT INTO produit_couleur VALUES('$idP','".$idC."')");
				$insert->execute();
				
				}
				
				echo "<script>alert('Le produit \" $nom \" a été ajouter');
				document.location =location.href='?action=addPicture'</script>";
									
				
			} else { echo "<script> alert('Veuillez réessayer s'il vous plaît!') </script>";	}
				
								
			}else{ echo "<script>alert('*image trop large* ou **aucun fichier renseigné**')</script>";}
					
			}//fin boucle for file   
				
			}else{ echo '<script>alert("Aucun fichier renseigné.")</script>';}	
			
			}
			
		}else{
			
			echo '<script>alert("Veuillez remplir les neuf premiers champs avec au moins : \nDeux materiaux different(Materiaux 1 et 2)\nDeux couleurs different(Couleur 1 et 2)")</script>';
		}
		
		}
}//end submit
?>

<link rel="stylesheet" type="text/css" href="style/style.css">
<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
<script type="text/javascript" src="includes/ckeditorBasic/ckeditor.js"></script>
<form id="monForm" action="" method="POST" enctype="multipart/form-data">
				 
		  <p style="margin-top: 0px;text-align:center;border:1px solid black;"><i>Complétez le formulaire. Les champs marqué par </i><em>*</em> <i>sont</i> <em style="font-size: 16px;">obligatoires</em></p>
 	
		<div>	
			<label for="for_prod">Nom du produit<em>*</em></label>
			<input id="for_prod" type="text" name="nom" autofocus required value="<?php if(isset($nom)) echo $nom;?>"/>
		</div>
		
		<div>
			<label for="for_argumentaire">Argumentaire<em>*</em></label>
			<textarea id="for_argumentaire" name="argumentaire"><?php if(isset($argumentaire)) echo $argumentaire;?></textarea>
				<script type="text/javascript">
					CKEDITOR.replace('argumentaire');
				</script>
		</div>
		
		<div>
			<label for="for_description">Description<em>*</em></label>
			<textarea id="for_description" name="description"><?php if(isset($description)) echo $description;?></textarea>
				<script type="text/javascript">
					CKEDITOR.replace('description');
				</script>
		</div>
		
		<div>
			<label for="for_dimensions">Dimensions<em>*</em></label>
			<textarea id="for_dimensions" name="dimensions" ><?php if(isset($dimensions)) echo $dimensions;?></textarea>
				<script type="text/javascript">
					CKEDITOR.replace('dimensions');
				</script>
		</div>
		
		<div>
			<label for="for_prix">Prix<em>*</em></label>
			<input id="for_prix" type="number" name="prix" step="0.01" min="0" value="<?php if(isset($prix)) echo $prix;?>"/>
		</div>
		
		<div>
			<label for="for_tlivraison">Type Livraison<em>*</em></label>
			<input id="for_tlivraison" type="text" name="type_livraison" value="<?php if(isset($type_livraison)) echo $type_livraison;?>"/>
		</div>
		
		<div>
			<label for="for_flivraison">Frais de Livraison<em>*</em></label>
			<input id="for_flivraison" type="number" step="0.01" min="0" name="frais_livraison" value="<?php if(isset($frais_livraison)) echo $frais_livraison;?>"/>
		</div>
		
	<fieldset>
	    	<legend>Disponibilite du produit</legend>
		<div class="checkbox">
			<!--<label for="for_disponibilite">Disponibilité du produit</label>  -->
			<div><input id="for_disponibilite" type="radio" name="disponibilite" value="1" checked>Disponible</div>
			<div><input id="for_disponibilite" type="radio" name="disponibilite" value="0">Indisponible</div>
		</div>
	</fieldset>

	<fieldset>
	    	<legend>Choisir catégorie</legend>
		<div>
			<label for="for_categorie">Choisir Categorie<em>*</em></label>
			<SELECT  id="for_categorie" name="categorie" style="width: 70%";>
				<?php if(isset($categorie)) echo "<option value='$categorie'>"?><?php $select=$db->query("SELECT nom_categorie FROM categorie WHERE id_categorie ='$categorie'");if ($s=$select->fetch(PDO::FETCH_OBJ)) echo $s->nom_categorie?></option>
				<OPTION VALUE="NULL">Aucun</OPTION>
				<?php 		
				$select = $db->prepare("SELECT * FROM categorie ORDER BY nom_categorie ASC");$select->execute();
				while($s=$select->fetch(PDO::FETCH_OBJ)) {?>
				<OPTION VALUE="<?php echo $s->id_categorie;?>"><?php echo $s->nom_categorie;}?> </OPTION>
			</SELECT>
		</div>
	</fieldset>
	
	<fieldset>
	    	<legend>Choix des Materiaux</legend>
		<div>
			<label for="for_categorie">Matériaux 1<em>*</em></label>
			<SELECT  id="for_categorie" name="materiaux" style="width: 70%";>
				<?php 		
				$select = $db->prepare("SELECT * FROM materiaux ORDER BY nom_materiaux ASC");$select->execute();
				while($s=$select->fetch(PDO::FETCH_OBJ)) {?>
				<OPTION VALUE="<?php echo $s->id_materiaux;?>"><?php echo $s->nom_materiaux;}?> </OPTION>
			</SELECT>
		</div>
		
		<div>
			<label for="for_categorie">Matériaux 2<em>*</em></label>
			<SELECT  id="for_categorie" name="materiaux1" style="width: 70%";>
				<?php 		
				$select = $db->prepare("SELECT * FROM materiaux ORDER BY nom_materiaux ASC");$select->execute();
				while($s=$select->fetch(PDO::FETCH_OBJ)) { ?>
				<OPTION VALUE="<?php echo $s->id_materiaux;?>"><?php echo $s->nom_materiaux;}?> </OPTION>
			</SELECT>
		</div>
		
		<div>
			<label for="for_categorie">Matériaux 3</label>
			<SELECT  id="for_categorie" name="materiaux2" style="width: 70%";>
			<?php if(isset($materiaux2)) echo "<option value='$materiaux2'>"?><?php $select=$db->query("SELECT nom_materiaux FROM materiaux WHERE id_materiaux ='$materiaux2'");if ($s=$select->fetch(PDO::FETCH_OBJ)) echo $s->nom_materiaux?></option>
				<OPTION VALUE="NULL">Aucun</OPTION>
				<?php 		
				$select = $db->prepare("SELECT * FROM materiaux ORDER BY nom_materiaux ASC");$select->execute();
				while($s=$select->fetch(PDO::FETCH_OBJ)) {?>
				<OPTION VALUE="<?php echo $s->id_materiaux;?>"><?php echo $s->nom_materiaux;}?> </OPTION>
			</SELECT>
		</div>
	</fieldset>

	<fieldset>
	    	<legend>Choix des Couleurs</legend>
		<div>
			<label for="for_categorie">Couleur 1<em>*</em></label>
			<SELECT  id="for_categorie" name="couleur" style="width: 70%";>
				<?php 		
				$select = $db->prepare("SELECT * FROM couleur ORDER BY couleur ASC");$select->execute();
				while($s=$select->fetch(PDO::FETCH_OBJ)) {?>
				<OPTION VALUE="<?php echo $s->id_couleur;?>"><?php echo $s->couleur;}?> </OPTION>
			</SELECT>
		</div>
		
		<div>
			<label for="for_categorie">Couleur 2<em>*</em></label>
			<SELECT  id="for_categorie" name="couleur1" style="width: 70%";>
				<?php 		
				$select = $db->prepare("SELECT * FROM couleur ORDER BY couleur ASC");$select->execute();
				while($s=$select->fetch(PDO::FETCH_OBJ)) { ?>
				<OPTION VALUE="<?php echo $s->id_couleur;?>"><?php echo $s->couleur;}?> </OPTION>
			</SELECT>
		</div>
		
		<div>
			<label for="for_categorie">Couleur 3</label>
			<SELECT  id="for_categorie" name="couleur2" style="width: 70%";>
			<?php if(isset($couleur2)) echo "<option value='$couleur2'>"?><?php $select=$db->query("SELECT couleur FROM couleur WHERE id_couleur ='$couleur2'");if ($s=$select->fetch(PDO::FETCH_OBJ)) echo $s->couleur?></option>
				<OPTION VALUE="NULL">Aucun</OPTION>
				<?php 		
				$select = $db->prepare("SELECT * FROM couleur ORDER BY couleur ASC");$select->execute();
				while($s=$select->fetch(PDO::FETCH_OBJ)) { ?>
				<OPTION VALUE="<?php echo $s->id_couleur;?>"><?php echo $s->couleur;}?> </OPTION>
			</SELECT>
		</div>
		
	</fieldset>
	
		<div>
			<label>Séléctionner la photo principale :</label><br>
			<input name="file[]" type="file" id="file"/>
		</div>
		<div>
			<input type="submit" name="submit" value="Ajouter" />
			<input type="reset" name="reset" value="Annuler" />
		</div>
</form>

<?php	

}else if($_GET['action'] == 'modify/delete'){

	$select = $db->prepare("SELECT * FROM produit ORDER BY nom_produit");
	$select->execute();
	
	if($trouve = $select->rowCount()) $nbLignes = true;
	else $nbLignes = false;
	
	if($nbLignes){
?>
		<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
		<table id="tableau" summary="Liste des clients">
			<thead style="background-color:lightgrey;">
					<tr>
						<th scope="col">Nom du produit / "<?php $nb=$db->query("select count(*) from produit"); $res = $nb->fetchColumn();echo"$res";?>"Articles enregistrés</th>
						<th scope="col">Modifier</th>
						<th scope="col">Supprimer</th>
					</tr>
			</thead>
							
			<tfoot>
					<tr>
						<td colspan="3">Liste des Produits</td>
					</tr>
			</tfoot>
					  		
			<tbody>
			<?php				
	while($s=$select->fetch(PDO::FETCH_OBJ)){

		echo '<tr><td>';
		echo $s->nom_produit;
		echo '</td>';
?>
		</td>
			<td><a href="?action=modify&amp;id=<?php echo $s->id_produit; ?>"><input id="modifier" type="button" value='modifier' name='modifier'/></a></td>
			<td><a href="?action=delete&amp;id=<?php echo $s->id_produit; ?>" onclick="return(confirm('Etes-vous sûr de vouloir supprimer cette entrée?'));"><input id="supprimer" type="button" value='supprimer' name='supprimer'/></a></td>
				</tr>
			</tbody>
<?php
											}
		echo'</table>';
		
		}else{
			echo '<div id="vide">Aucun produit !</div>';
		}
		
		
}else if($_GET['action'] == 'modify'){
				
	

	if(isset($_GET['id'])&&$_GET['id']!==""){//si la variable id est passée par l'url
		$id=$_GET['id'];
		
				if(!is_numeric($_GET['id'])){//juste une première sécurité
					echo '<script>document.location =location.href="?action=modify/delete" </script>';
					exit;
				}
	$verif = $db->query("select id_produit from produit where id_produit = $id");
	if($verif->fetchColumn()){
				
	$select = $db->prepare("SELECT * FROM produit WHERE id_produit=$id");
	$select->execute();	
	$data = $select->fetch(PDO::FETCH_OBJ);			
?>			
			<script type="text/javascript" src="includes/ckeditorBasic/ckeditor.js"></script>
			<form id="monForm" action="" method="POST" enctype="multipart/form-data">
				<p style="margin-top: 0px;text-align:center;border:1px solid black;"><i>Modifier le formulaire. Les champs marqué par </i><em>*</em> <i>sont</i> <em style="font-size: 16px;">obligatoires</em></p>
							<div>			
								<label for="for_prod">Nom du produit<em>*</em></label>		
								<input id="for_prod" type="text" name="nom" value="<?php echo $data->nom_produit;?>"/>
							</div>

							<div>
								<td><label for="for_argumentaire">Argumentaire<em>*</em></label>
								<td><textarea id="for_argumentaire" type="text" name="argumentaire"><?php echo $data->argumentaire;?></textarea>
									<script type="text/javascript">
										CKEDITOR.replace('argumentaire');
									</script>
							</div>
							
							<div>
								<label for="for_description">Description<em>*</em></label>
								<textarea id="for_description" name="description"><?php echo $data->description;?></textarea>
									<script type="text/javascript">
										CKEDITOR.replace('description');
									</script>
							</div>
	
							<div>
								<label for="for_dimensions">Dimensions<em>*</em></label>
								<textarea id="for_dimensions" name="dimensions"><?php echo $data->dimensions;?></textarea>
									<script type="text/javascript">
										CKEDITOR.replace('dimensions');
									</script>
							</div>
									
							<div>
								<label for="for_prix">Prix<em>*</em></label>
								<input id="for_prix" type="number" name="prix" step="0.01" min="0"value="<?php echo $data->prix;?>"/>
							</div>
								
							<div>
								<label for="for_tlivraison">Type Livraison<em>*</em></label>
								<input id="for_tlivraison" type="text" name="type_livraison" value="<?php echo $data->type_livraison;?>"/>
							</div>
		
							<div>
								<label for="for_flivraison">Frais de Livraison<em>*</em></label></td>
								<input id="for_flivraison" type="number" step="0.01" min="0" name="frais_livraison" value="<?php echo $data->frais_livraison;?>"/>
							</div>
							
						    <fieldset>
						    <legend>Disponibilite du produit : <?php if($data->disponible==1){echo "<em id='disponible'>Actuellement disponible</em>";}else{echo "<em id='indisponible'>Actuellement indisponible</em>";}?></legend>
							<div class="checkbox">
								<!--  <label for="for_disponibilite">Disponibilité du produit</label>-->
								<div><input id="for_disponibilite" type="radio" name="disponibilite" value="1" <?php if($data->disponible==1){echo 'checked';}?>>Disponible</div>
								<div><input id="for_disponibilite" type="radio" name="disponibilite" value="0" <?php if($data->disponible==0){echo 'checked';}?>>Indisponible</div>
							</div>

							</fieldset>
							
							<div>
								<label for="for_categorie">Choisir Categorie<em>*</em></label>
									<SELECT id="for_categorie" name="categorie" style="width: 70%";>
										<OPTION VALUE="<?php $cat = $data->id_categorie; echo $cat ?>"><?php $sql = $db->query("select nom_categorie FROM categorie WHERE id_categorie = $cat"); if($cat=$sql->fetch(PDO::FETCH_OBJ)) echo $cat->nom_categorie; ?></OPTION>
										
										<optgroup label="Choisir autre categorie" style="color:black">
										<?php 
										$select = $db->prepare("SELECT * FROM categorie ORDER BY nom_categorie ASC");$select->execute();
										while($s=$select->fetch(PDO::FETCH_OBJ)) { ?>
										<OPTION VALUE="<?php echo $s->id_categorie;?>"><?php echo $s->nom_categorie;}?> </OPTION>
										</optgroup>
									
									</SELECT>
							</div>
							
							
								<fieldset>
									    	<legend>Choix de Materiau supplémentaire</legend>
										<div>
											<label for="for_categorie">Matériau supplémentaire<em>*</em></label>
											<SELECT  id="for_categorie" name="materiaux" style="width: 70%";>
												<?php if(isset($materiaux)) echo "<option value='$materiaux'>"?><?php $select=$db->query("SELECT nom_materiaux FROM materiaux WHERE id_materiaux ='$materiaux'");if ($s=$select->fetch(PDO::FETCH_OBJ)) echo $s->nom_materiaux?></option>
												<OPTION VALUE="NULL">Aucun</OPTION>
												<?php 		
												$select = $db->prepare("SELECT * FROM materiaux ORDER BY nom_materiaux ASC");$select->execute();
												while($s=$select->fetch(PDO::FETCH_OBJ)) { ?>
												<OPTION VALUE="<?php echo $s->id_materiaux;?>"><?php echo $s->nom_materiaux;}?> </OPTION>
											</SELECT>
										</div>
									</fieldset>
								
									<fieldset>
									    	<legend>Choix de Couleur supplémentaire</legend>
										<div>
											<label for="for_categorie">Couleur supplémentaire<em>*</em></label>
											<SELECT  id="for_categorie" name="couleur" style="width: 70%";>
											<OPTION VALUE="NULL">Aucun</OPTION>
											<?php if(isset($couleur)) echo "<option value='$couleur'>"?><?php $select=$db->query("SELECT couleur FROM couleur WHERE id_couleur ='$couleur'");if ($s=$select->fetch(PDO::FETCH_OBJ)) echo $s->couleur?></option>
								
												<?php 		
												$select = $db->prepare("SELECT * FROM couleur ORDER BY couleur ASC");$select->execute();
												while($s=$select->fetch(PDO::FETCH_OBJ)) { ?>
												<OPTION VALUE="<?php echo $s->id_couleur;?>"><?php echo $s->couleur;}?> </OPTION>
											</SELECT>
										</div>
										
										
									</fieldset>
									
									
									<fieldset>
											<legend>Cocher le(s) matériau(x) à supprimé</legend>
										<?php $select = $db->prepare("SELECT id_materiaux FROM produit_materiaux WHERE produit_materiaux.id_produit = $id");$select->execute();
										while($dataM=$select->fetch(PDO::FETCH_OBJ)) { ?>
										<input type="checkbox" name="delmateriaux" value="<?php $m = $dataM->id_materiaux; echo $m;?>"><?php $sql = $db->query("select nom_materiaux FROM materiaux WHERE materiaux.id_materiaux = $m"); if($nom_m=$sql->fetch(PDO::FETCH_OBJ)) echo $nom_m->nom_materiaux; ?>
										<?php }?>
										
									</fieldset>
									
									<fieldset>
											<legend>Cocher le(s) couleur(s) à supprimé</legend>
										<?php $select = $db->prepare("SELECT id_couleur FROM produit_couleur WHERE produit_couleur.id_produit = $id");$select->execute();
										while($dataC=$select->fetch(PDO::FETCH_OBJ)) { ?>
										<input type="checkbox" name="delcouleur" value="<?php $c = $dataC->id_couleur; echo $c;?>"><?php $sql = $db->query("select couleur FROM couleur WHERE couleur.id_couleur = $c"); if($nom_c=$sql->fetch(PDO::FETCH_OBJ)) echo $nom_c->couleur; ?>
										<?php }?>
										
									</fieldset>
									
							<div>
								<input type="submit" name="submit" value="Modifier" />
							</div>
				</form>
			
<?php }else{echo '<script>document.location =location.href="?action=modify/delete" </script>';} }	


if(isset($_POST['submit'])){
					
			$nom=addslashes($_POST['nom']);
			$description=addslashes($_POST['description']);
			$argumentaire=addslashes($_POST['argumentaire']);
			$dimensions=addslashes($_POST['dimensions']);
			$prix=$_POST['prix'];
			$type_livraison=addslashes($_POST['type_livraison']);
			$frais_livraison=$_POST['frais_livraison'];
			$disponibilite=$_POST['disponibilite'];
			$categorie=$_POST['categorie'];
			
			if(isset($_POST['delcouleur'])){
			$delcouleur = array(
					'id_couleur' => $_POST['delcouleur']
			);
			}
			
			if(isset($_POST['delmateriaux'])){
			$delmateriaux = array(
					'id_materiaux' => $_POST['delmateriaux']
			);
			}

			
			if($_POST['materiaux'] == "NULL"){
				
					
				$couleur = array(
						'id_couleur' => $_POST['couleur'],

				);
				
			}else if($_POST['couleur'] == "NULL"){
				
					$materiaux = array(
							'id_materiaux' => $_POST['materiaux'],
				
					);
					
			}else{
			
				$materiaux = array(
						'id_materiaux' => $_POST['materiaux'],
				);
					
				$couleur = array(
						'id_couleur' => $_POST['couleur'],
				);
			
			}
			

			if($nom&&$description&&$argumentaire&&$dimensions&&$prix&&$type_livraison&&$frais_livraison){
			
					$update = $db->prepare("UPDATE produit SET nom_produit='".$nom."',description='".$description."',
							argumentaire='".$argumentaire."',dimensions='".$dimensions."',prix='".$prix."',type_livraison='".$type_livraison."',
							frais_livraison='".$frais_livraison."',disponible='".$disponibilite."',id_categorie='".$categorie."' WHERE id_produit=$id");
					$update->execute();
					
					
					if($_POST['materiaux'] !== "NULL"){
					foreach ($materiaux as $materiau=>$idM) {
							
						$insert = $db->prepare("INSERT INTO produit_materiaux VALUES('$id','".$idM."')");
						$insert->execute();
					
					}
					}
					
					if($_POST['couleur'] !== "NULL"){
					foreach ($couleur as $couleurs=>$idC){
							
						$insert = $db->prepare("INSERT INTO produit_couleur VALUES('$id','".$idC."')");
						$insert->execute();
					
					}
					}
					
					
					if(isset($_POST['delmateriaux'])){
						foreach ($delmateriaux as $materiau=>$idDelM) {
								
							$delete = $db->prepare("DELETE FROM produit_materiaux WHERE id_produit = '$id' AND id_materiaux = '".$idDelM."'");
							$delete->execute();
						}
					}
					
						
					if(isset($_POST['delcouleur'])){
						foreach ($delcouleur as $couleurs=>$idDelC){
								
							$delete = $db->prepare("DELETE FROM produit_couleur WHERE id_produit = '$id' AND id_couleur='".$idDelC."'");
							$delete->execute();	
						}
					}

					echo '<script>alert("les modifications ont bien été effectuées");
					
					document.location =location.href="?action=modify/delete" </script>';
			
			}else{
			
			
				echo" <script>alert('verifiez si vous avez rempli tous les champs important ')</script>";
			
			}
							

				
									}
?><?php
				
}else if($_GET['action'] == 'delete'){
					
	$id=$_GET['id'];
	$delete = $db->prepare("DELETE FROM photo_produit WHERE id_produit=$id");
	$delete->execute();
	
	$delete = $db->prepare("DELETE FROM produit_couleur WHERE id_produit=$id");
	$delete->execute();
	
	$delete = $db->prepare("DELETE FROM produit_materiaux WHERE id_produit=$id");
	$delete->execute();
	
	$delete = $db->prepare("DELETE FROM produit WHERE id_produit=$id");
	$delete->execute();
	
	header('location:?action=modify/delete');


}else if($_GET['action'] == 'addPicture'){
?>
<html>
    <head>
		<!-------Including jQuery from google------>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="style/script.js"></script>
		
		<!-------Including CSS File------>
        <link rel="stylesheet" type="text/css" href="style/style.css">
		<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
		</head>
		<body>
		<form id="monForm" action="" method="POST" enctype="multipart/form-data" style="width : 45%;">
			
			<div id="filediv">
       			<label>Télécharger images:</label>
            	<input name="file[]" type="file" id="file"/>
                <input type="button" id="add_more" class="upload" value="Ajouter plus"/>
			</div>
		
			<div>
				<label for="for_produit">Choisir le produit</label>
				<SELECT  id="for_produit" name="produit" style="width : 70%;">
					<?php 		
					$select = $db->prepare("SELECT * FROM produit ORDER BY id_produit DESC");$select->execute();
					while($s=$select->fetch(PDO::FETCH_OBJ)) { ?>
					<OPTION VALUE="<?php echo $s->id_produit;?>"><?php echo $s->nom_produit;}?> </OPTION>
				</SELECT>
			</div>
			
			<div>
				<input type="submit" name="submit" value="Ajouter" />
				<input type="reset" name="reset" value="Annuler" />
			</div>
			
		</form>
		</body>
	</html>
<?php
	if(isset($_POST['submit'])){
		
		$j = 0;
		
		$target_path = "../../agnes_recup_art/ressources/images/photoProduit/";
		

		for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
		
			$validextensions = array("jpeg", "jpg", "png","JPEG", "JPG", "PNG");

	        $ext = explode('.', basename($_FILES['file']['name'][$i]));
	        $file_extension = end($ext);
			$target_path = $target_path . md5(uniqid()) . "." . $ext[count($ext) - 1];
			$j = $j + 1;
		
			if (($_FILES["file"]["size"][$i] < 5000000) && in_array($file_extension, $validextensions)) {
						if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $target_path)) {
							
								
							$long = strlen($file_extension);
							$urlImg=substr($target_path,53);
							$url = $urlImg;
						//echo $url;
						//$url= "photoProduit/".$_FILES['file']['name'][$i];
		$produit=$_POST['produit'];
			
		if($produit<>"NULL"){
			$insert = $db->prepare("INSERT INTO photo_produit VALUES('','".$url."','".$produit."')");
			$insert->execute();	
			
			echo "<script> alert('Le fichier $j a été sauvegarder avec succès!') </script>";
				
		}else{
			
			echo "<script> alert('veuillez choisir le produit') </script>";
			
		}//fin verification si champs rempli
		
			} else {
				
				echo "<script> alert('Veuillez réessayer s'il vous plaît!') </script>";
			}
			
			}else{
				echo "<script>alert('***L'image $j est trop large ou n'est pas au bon format***')</script>";
			}
		}//end for


}//end submit

	}else if($_GET['action'] == 'modify/deletePicture'){
	
		$select = $db->prepare("SELECT * FROM photo_produit,produit WHERE photo_produit.id_produit = produit.id_produit ");
		$select->execute();
	
		if($trouve = $select->rowCount()) $nbLignes = true;
		else $nbLignes = false;
	
		if($nbLignes){
			?>
			<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
			<table id="tableau" summary="Liste des clients">
				<thead style="background-color:lightgrey;">
						<tr>
							<th scope="col">Photo / =>"<?php $nb=$db->query("select count(*) FROM photo_produit"); $res = $nb->fetchColumn();echo"$res";?>"Photos de produit</th>
							<th scope="col">Produit Associés</th>
							<th scope="col">Modifier</th>
							<th scope="col">Supprimer</th>
						</tr>
				</thead>
								
				<tfoot>
						<tr>
							<td colspan="4">Liste des photos produits</td>
						</tr>
				</tfoot>
						  		
				<tbody>
				<?php				
		while($s=$select->fetch(PDO::FETCH_OBJ)){
	
			echo '<tr><td>';
			echo "<img src='../../agnes_recup_art/ressources/images/PhotoProduit/$s->url_1' style='width:50px;height:50px;'/></td>";
			echo "<td>$s->nom_produit";
			echo '</td>';
?>
				<td><a href="?action=modifyPicture&amp;id=<?php echo $s->id_photo_produit?>&amp;url=<?php echo $s->url_1; ?>"><input id="modifier" type="button" value='modifier' name='modifier'/></a></td>							<!-- ainsi afficher les liens modifier et supprimer-->
				<td><a href="?action=deletePicture&amp;id=<?php echo $s->id_photo_produit?>&amp;url=<?php echo $s->url_1; ?>" onclick="return(confirm('Etes-vous sûr de vouloir supprimer cette entrée?'));"><input id="supprimer" type="button" value='supprimer' name='supprimer'/></a></td>
					</tr>
				</tbody>
<?php												}
			echo'</table>';
			
			}else{
				echo '<div id="vide">Aucune image !</div>';
			}
			
			
	}else if($_GET['action'] == 'modifyPicture'){
					
		$id=$_GET['id'];
					
		$select = $db->prepare("SELECT * FROM photo_produit,produit WHERE photo_produit.id_produit = produit.id_produit AND id_photo_produit=$id");
		$select->execute();	
		$data = $select->fetch(PDO::FETCH_OBJ);			
?>				

		<html>
		    <head>
				<!-------Including jQuery from google------>
		        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		        <script src="style/script.js"></script>
				<!-------Including CSS File------>
		        <link rel="stylesheet" type="text/css" href="style/style.css">
				<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
				</head>
				<body>
				<form id="monForm" action="" method="POST" enctype="multipart/form-data">
	
								<div>			
									<label for="">l'image</label>		
									<input type="image" src="../../agnes_recup_art/ressources/images/PhotoProduit/<?php echo $data->url_1;?>"/>
									<input name="img" type="hidden" value="../../agnes_recup_art/ressources/images/PhotoProduit/<?php echo $data->url_1;?>" size="50" />
								</div>
								
								<div id="filediv">
					       			<label for="fileToUpload">Remplacer cette image :</label><br/>
					            	<input name="file[]" type="file" id="file"/>
								</div>
							
						
								<div>			
									<label for="for_produit">Le produit associés</label>		
									<input  type="text" name="produit" disabled value="<?php echo $data->nom_produit;?>"/>
									<input name="produit" type="hidden" value="<?php echo $data->nom_produit;?>"/>
								</div>				
										
								<div>
									<input type="submit" name="submit" value="Modifier" />
									<input type="reset" name="reset" value="Annuler" />
								</div>
							</form>
						</body>
					</html>
				
<?php	if(isset($_POST['submit'])){
		
		
		$j = 0;
		
		$target_path = "../../agnes_recup_art/ressources/images/PhotoProduit/";
		for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
		
			$validextensions = array("jpeg", "jpg", "png","JPEG", "JPG", "PNG");
			$ext = explode('.', basename($_FILES['file']['name'][$i]));
			$file_extension = end($ext);
		
			$target_path = $target_path . md5(uniqid()) . "." . $ext[count($ext) - 1];
			$j = $j + 1;
		
			if (($_FILES["file"]["size"][$i] < 5000000)
					&& in_array($file_extension, $validextensions)) {
						if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $target_path)) {
		
		$urlImg=substr($target_path,53);
		$url= $urlImg;
		
		$produit = $_POST['produit'];

				
				//gestion couleur et photo à finir
				if($produit){
					
					$remove_url = $_GET['url'];
					unlink($remove_url);
				
						$update = $db->prepare("UPDATE photo_produit SET url_1='".$url."' WHERE id_photo_produit=$id");
						$update->execute();
	
						echo '<script>alert("les modifications ont bien été effectuées");
			
						document.location =location.href="?action=modify/deletePicture" </script>';
						
				}else{
				
				
					echo" <script>alert('verifiez si vous avez rempli tous les champs')</script>";
				
				}
				
				} else {
				
					echo "<script> alert('Veuillez réessayer s'il vous plaît!') </script>";
			}
				
			}else{
				
				$img = $_POST["img"];
				$url = $img;
				
				$update = $db->prepare("UPDATE photo_produit SET url_1='".$url."' WHERE id_photo_produit=$id");
				$update->execute();
				
				echo '<script>alert("Aucune modifications effectuées");
		
						document.location =location.href="?action=modify/deletePicture" </script>';
		}
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
					
	}else if($_GET['action'] == 'deletePicture'){
		
		$url = $_GET['url'];
		//retire galerie
		
		unlink($url);
						
		$id=$_GET['id'];
		$delete = $db->prepare("DELETE FROM photo_produit WHERE id_photo_produit=$id");
		$delete->execute();
		
		header('location:?action=modify/deletePicture');

}else{
	//une erreur s'est produit si on a pas d'action
?><link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/><div class="twf_info"><a href = "gestionProduits.php"><img alt="" src="images/home.PNG" class="home"></a><?php die('une erreur s\'est produite');?></div><?php 
	
}//fin de tous les conditions de Ajout/modification/suppression

	
}else{//si on a pas d'action on affiche les elements de la base de donnée
	$num = 1;	
	$select = $db->prepare("SELECT * FROM produit ORDER BY nom_produit"); 
	$select->execute();
	
	if($trouve = $select->rowCount()) $nbLignes = true;
	else $nbLignes = false;
	
	if($nbLignes){
?>
<div class="a">
<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
	<table id="liste">
	<thead>nombre de produits "<?php 	$nb=$db->query("select count(*) from produit"); $res = $nb->fetchColumn();echo"<thead>$res</thead>";?>"</thead>
		<tr>
			<th><h5>Position :</h5></th>
			<th><h5>Liste des produits :</h5></th>
		</tr>

<?php 
	while($s=$select->fetch(PDO::FETCH_OBJ)){
		
		echo '<tr>';
		echo '<td>'.$num.'</td>';
		echo "<td>$s->nom_produit</td></tr>";
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