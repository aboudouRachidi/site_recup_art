<html>
<head>
	<style>
		.sidebar{
			position:fixed;
			bottom:100px;
			height:500px;
			overflow:auto;
			width:200px;
			}
	</style>
	<Link rel="stylesheet" href="style/agenda.css" />
	<link rel="stylesheet" type="text/css" href="style/calendrier.css" media="screen" />
 </head>
 <body>
<div class="sidebar">
<!-- calendrier -->
<?php
	function getEventsDate($mois, $annee) {
		$result = array();
		
		include("includes/coBDD.php");
		
		$sql = $db->query('SELECT DISTINCT jour_evenement, titre_evenement FROM calendrier c, evenements e WHERE mois_evenement='.$mois.' AND annee_evenement='.$annee.' AND c.id_evenement = e.id_evenement ORDER BY jour_evenement');
		
		//$query = mysql_query($sql) or die("Une requête a échouée.");
		
		while ($row = $sql->fetch()) {
			$result[] = $row[0];
			$result[] = $row[1];
		}
		
		//mysql_close();
		
		return $result;
	}
	
	function afficheEvent($i, $event) {
		$texte = ""; $suivant = false;
		
		foreach($event as $cle => $element) {
			if($suivant) {
				$texte .= $element."<br/>";
			}
			if($element == $i) {
				$suivant = true;
			} else {
				$suivant = false;
			}
		}
		
		return $texte;
	}



	if(isset($_GET['m']) && isset($_GET['y']) && is_numeric($_GET['m']) && is_numeric($_GET['y'])) {
		$timestamp = mktime(0, 0, 0, $_GET['m'], 1, $_GET['y']);
		
		$event = getEventsDate($_GET['m'], $_GET['y']); // Récupere les jour où il y a des événements
	}
	else { // Si on ne récupere rien dans l'url, on prends la date du jour
		$timestamp = mktime(0, 0, 0, date('m'), 1, date('Y'));
		
		$event = getEventsDate(date('m'), date('Y')); // Recupere les jour où il y a des événements
	}
	
	
	// === Si le mois correspond au mois actuel et l'ann�ee aussi, on retient le jour actuel pour le griser plus tard (sinon le jour actuel ne se situe pas dans le mois)
	if(date('m', $timestamp) == date('m') && date('Y', $timestamp) == date('Y')) $coloreNum = date('d');
	
	$m = array("01" => "Janvier", "02" => "Février", "03" => "Mars", "04" => "Avril", "05" => "Mai", "06" => "Juin", "07" => "Juillet", "08" => "Août", "09" => "Septembre", "10" => "Octobre",  "11" => "Novembre", "12" => "Décembre");
	$j = array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
	
	$numero_mois = date('m', $timestamp);
	$annee = date('Y', $timestamp);
	
	if($numero_mois == 12) {
		$annee_avant = $annee;
		$annee_apres = $annee + 1;
		$mois_avant = $numero_mois - 1;
		$mois_apres = 01;
	}
	elseif($numero_mois == 01) {
		$annee_avant = $annee - 1;
		$annee_apres = $annee;
		$mois_avant = 12;
		$mois_apres = $numero_mois + 1;
	}
	else {
		$annee_avant = $annee;
		$annee_apres = $annee;
		$mois_avant = $numero_mois - 1;
		$mois_apres = $numero_mois + 1;
	}
	
	// 0 => Dimanche, 1 => Lundi, 2 = > Mardi...
	$numero_jour1er = date('w', $timestamp);
	
	// Changement du numéro du jour car l'array commence � l'indice 0
	if ($numero_jour1er == 0) $numero_jour1er = 6; // Si c'est Dimanche, on le place en 6ème position (apr�s samedi)
	else $numero_jour1er--; // Sinon on mets lundi à 0, Mardi à 1, Mercredi à 2...
	?>
	
    
	<table class="calendrier">
		<caption><?php echo '<a href="?m='.$mois_avant.'&amp;y='.$annee_avant.'"><<</a>  '.$m[$numero_mois].' '.$annee.'  <a href="?m='.$mois_apres.'&amp;y='.$annee_apres.'">>></a>'; ?></caption>
		
		<tr><th>Lu</th><th>Ma</th><th>Me</th><th>Je</th><th>Ve</th><th>Sa</th><th>Di</th></tr>
	<?php
		// Ecriture de la 1ère ligne
		echo '<tr>';
			// Ecriture de colones vides tant que le mois ne d�marre pas
			for($i = 0 ; $i < $numero_jour1er ; $i++) {		echo '<td></td>';	}
			for($i = 1 ; $i <= 7 - $numero_jour1er; $i++) {
				// Ce jour possède un événement
				if (in_array($i, $event)) {
					echo '<td class="jourEvenement';
					
					if(isset($coloreNum) && $coloreNum == $i) echo ' lienCalendrierJour';
					
					echo '"><a href="agenda.php?d='.$i.'/'.$numero_mois.'/'.$annee.'" class="info">'.$i.'<span>'.afficheEvent($i, $event).'</span></a></div></td>';
				} else {
					echo '<td ';
					
					if(isset($coloreNum) && $coloreNum == $i) echo 'class="lienCalendrierJour"';
					
					echo '>'.$i.'</td>';
				}

			}
		echo '</tr>';
		
		$nbLignes = ceil((date('t', $timestamp) - ($i-1))/ 7); // Calcul du nombre de lignes à afficher en fonction de la 1ère (surtout pour les mois a 31 jours)
		
		for($ligne = 0 ; $ligne < $nbLignes ; $ligne++) {
			echo '<tr>';
			for($colone = 0 ; $colone < 7 ; $colone++) {
				if($i <= date('t', $timestamp))	{
					// Ce jour possède un événement
					if (in_array($i, $event)) {
						echo '<td class="jourEvenement';
						
						if(isset($coloreNum) && $coloreNum == $i) echo ' lienCalendrierJour';
						
						echo '"><a href="agenda.php?d='.$i.'/'.$numero_mois.'/'.$annee.'" class="info">'.$i.'<span>'.afficheEvent($i, $event).'</span></a></td>';
					} else {
						echo '<td ';
						
						if(isset($coloreNum) && $coloreNum == $i) echo 'class="lienCalendrierJour"';
						
						echo '>'.$i.'</td>';
					}
				} else {
					echo '<td></td>';
				}
				$i = $i +1;
			}
			echo '</tr>';
		}
	?>
	</table>
<!-- /calednrier -->	
	<!-- Panier -->
	<h4>Panier</h4>

<?php
require_once ('includes/fonctions_panier.php');
$erreur = false;

$action = (isset ( $_POST ['action'] ) ? $_POST ['action'] : (isset ( $_GET ['action'] ) ? $_GET ['action'] : null));

if ($action !== null) {
	
	if (! in_array ( $action, array (
			'ajout',
			'suppression',
			'refresh' 
	) ))
		
		$erreur = true;
	
	$l = (isset ( $_POST ['l'] ) ? $_POST ['l'] : (isset ( $_GET ['l'] ) ? $_GET ['l'] : null));
	$p = (isset ( $_POST ['p'] ) ? $_POST ['p'] : (isset ( $_GET ['p'] ) ? $_GET ['p'] : null));
	$q = (isset ( $_POST ['q'] ) ? $_POST ['q'] : (isset ( $_GET ['q'] ) ? $_GET ['q'] : null));
	$t = (isset ( $_POST ['t'] ) ? $_POST ['t'] : (isset ( $_GET ['t'] ) ? $_GET ['t'] : null));
	$f = (isset ( $_POST ['f'] ) ? $_POST ['f'] : (isset ( $_GET ['f'] ) ? $_GET ['f'] : null));
	
	$l = preg_replace ( '#\v#', '', $l );
	
	$p = floatval ( $p );
	
	$f = floatval ( $f );
	
	if (is_array ( $q )) {
		
		$QteProduit = array ();
		
		$i = 0;
		
		foreach ( $q as $contenu ) {
			
			$QteProduit [$i ++] = intval ( $contenu );
		}
	} else {
		
		$q = intval ( $q );
	}
}

if (! $erreur) {
	
	switch ($action) {
		
		case "ajout" :
			
			ajouterProduit ( $l, $q, $p, $t, $f );
			
			break;
		
		case "suppression" :
			
			supprimerProduit ( $l );
			
			break;
		
		case "refresh" :
			
			for($i = 0; $i < count ( $QteProduit ); $i ++) {
				
				modifierQteProduit ( $_SESSION ['panier'] ['libelleProduit'] [$i], round ( $QteProduit [$i] ) );
			}
			
			break;
		
		default :
			
			break;
	}
}

?>

<form method="post" action="">
		<table width="180">
			
<?php

if (isset ( $_GET ['deletePanier'] ) && $_GET ['deletePanier'] == true) {
	
	supprimerPanier ();
}

if (creationPanier ()) {
	
	$nbProduit = count ( $_SESSION ['panier'] ['libelleProduit'] );
	
	if ($nbProduit <= 0) {
		
		echo "panier vide";
	} else {
		
		for($i = 0; $i < $nbProduit; $i ++) {
			
			?>
			
			<TR>
				<td><br><?php echo $_SESSION['panier']['libelleProduit'][$i]; ?></td>
				
				
			</TR>
			<?php }	?>
			<TR>
				<td colspan="2"><br>
					<p>Total : <?php echo montantTotal(); ?> € (prix du produit + frais de port)</p>
					<br></td>
			</TR>
			<td colspan="2"><a href="?deletePanier=true&amp;action=supprimer&amp;id=<?php echo $s->id_materiaux;?>&amp;id=<?php echo $s->id_categorie;?>&amp;id=<?php echo $s->id_produit;?>">Supprimer le panier</a><br></br>
			
				<?php
	}
}
?>
</table>
	</form>
	</div>
<!-- /panier -->
</body>
</html>