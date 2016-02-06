<?php 
require_once ('../includes/connexion_bdd.php');
require_once ('includes/header.php');
if(isset($_SESSION['admin_id'])){
?>

<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>



			<form id ="monForm" action="" method="POST">
			<p style="margin-top: 0px;text-align:center;border:1px solid black;"><i>Modifier votre compte <em style="font-size: 16px;">Administration</em></i></p>
					<div>
							<label for="for_nom" style="font-variant: small-caps;">Nom</label>
							<input id="for_nom" type="text" name="nom"  style="font-variant: small-caps ;" value="<?php echo $_SESSION['admin_nom']; ?>"/>
					</div>
					
					<div>
							<label for="for_prenom" style="font-variant: small-caps;">Prenom</label>
							<input id="for_prenom" type="text" name="prenom"  style="font-variant: small-caps ;" value="<?php echo $_SESSION['admin_prenom']; ?>"/>
					</div>


					<div>
							<label for="for_ancien_mdp" style="font-size: 10px;font-variant: small-caps">Ancien mot de passe</label>
							<input id="for_ancien_mdp" type="password" name="ancien_mdp" required="required"/>
					</div>
					
					<div>
							<label for="for_mdp" style="font-size: 10px;font-variant: small-caps;">Nouveau mot de passe</label>
							<input id="for_mdp" type="password" name="mdp" required="required"/>
					</div>
					
					<div>
							<label for="for_retap_retap_mdp" style="font-size: 10px;font-variant: small-caps;">Retapez le nouveau mot de passe</label>
							<input id="for_retap_mdp" type="password" name="retap_mdp" required="required"/>
					</div>
		
					<div>
							<input type="submit" name="submit" value="Modifier" />
					</div>
				</form>
			
<?php	if(isset($_POST['submit'])){
					
				$nom=$_POST['nom'];
				$prenom=$_POST['prenom'];
				$ancien_mdp=hash('sha256',$_POST['ancien_mdp']);
				$mdp=hash('sha256',$_POST['mdp']);
				$retap_mdp=hash('sha256',$_POST['retap_mdp']);
				$id =$_SESSION['admin_id'];
				
			if($ancien_mdp === $_SESSION['admin_mdp']){
					
					if($mdp === $retap_mdp){
							
				
				$update = $db->prepare("UPDATE admin SET nom='".$nom."',prenom='".$prenom."',mdp='".$mdp."' WHERE id_admin=$id");
				$update->execute();
				
					echo '<script>alert("les modifications ont bien été éffectuées\nVous allez être déconnecté de la session");
		
					document.location =location.href="includes/deconnexionAdmin.php" </script>';
				
				//header('location:?action=modify/delete');

					}else{
						echo '<script>alert("Les nouveau mot de passe ne correspondent pas")</script>';
					}
				
			}else{
				
				echo '<script>alert("L\'ancien mot de passe ne correspond pas")</script>';
				
			}
}
									
	

	
}else{//si l'administrateur n'est plus logué on le redirige à la page connexion
	
		header('Location:espacePrive.php');
	}
?>