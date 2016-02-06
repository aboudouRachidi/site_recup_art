<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Gestion de calendrier | Calendrier</title>
    <link rel="stylesheet" type="text/css" href="calendrier2/design/calendrier.css" media="screen" />
</head>
<body>
	<h1>Calendrier</h1>
	<br/>
<?php
	function getEventsDate($mois, $annee) {
		$result = array();
		
		include("includes/sql_connect.php");
		
		$sql = 'SELECT DISTINCT jour_evenement, titre_evenement FROM calendrier c, evenements e WHERE mois_evenement='.$mois.' AND annee_evenement='.$annee.' AND c.id_evenement = e.id_evenement ORDER BY jour_evenement';
		
		$query = mysql_query($sql) or die("Une requête a échouée.");
		
		while ($row = mysql_fetch_array($query, MYSQL_NUM)) {
			$result[] = $row[0];
			$result[] = $row[1];
		}
		
		mysql_close();
		
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
		
		$event = getEventsDate($_GET['m'], $_GET['y']); // Récupère les jour où il y a des évènements
	}
	else { // Si on ne récupère rien dans l'url, on prends la date du jour
		$timestamp = mktime(0, 0, 0, date('m'), 1, date('Y'));
		
		$event = getEventsDate(date('m'), date('Y')); // Récupère les jour où il y a des évènements
	}
	
	
	// === Si le mois correspond au mois actuel et l'année aussi, on retient le jour actuel pour le griser plus tard (sinon le jour actuel ne se situe pas dans le mois)
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
	
	// Changement du numéro du jour car l'array commence à l'indice 0
	if ($numero_jour1er == 0) $numero_jour1er = 6; // Si c'est Dimanche, on le place en 6ème position (après samedi)
	else $numero_jour1er--; // Sinon on mets lundi à 0, Mardi à 1, Mercredi à 2...
	?>
    
	<table class="calendrier">
		<caption><?php echo '<a href="calendrier.php?m='.$mois_avant.'&amp;y='.$annee_avant.'"><<</a>  '.$m[$numero_mois].' '.$annee.'  <a href="calendrier.php?m='.$mois_apres.'&amp;y='.$annee_apres.'">>></a>'; ?></caption>
		
		<tr><th>Lu</th><th>Ma</th><th>Me</th><th>Je</th><th>Ve</th><th>Sa</th><th>Di</th></tr>
	<?php
		// Ecriture de la 1ère ligne
		echo '<tr>';
			// Ecriture de colones vides tant que le mois ne démarre pas
			for($i = 0 ; $i < $numero_jour1er ; $i++) {		echo '<td></td>';	}
			for($i = 1 ; $i <= 7 - $numero_jour1er; $i++) {
				// Ce jour possède un événement
				if (in_array($i, $event)) {
					echo '<td class="jourEvenement';
					
					if(isset($coloreNum) && $coloreNum == $i) echo ' lienCalendrierJour';
					
					echo '"><a href="calendrier.php?d='.$i.'/'.$numero_mois.'/'.$annee.'" class="info">'.$i.'<span>'.afficheEvent($i, $event).'</span></a></div></td>';
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
						
						echo '"><a href="calendrier2/calendrier.php?d='.$i.'/'.$numero_mois.'/'.$annee.'" class="info">'.$i.'<span>'.afficheEvent($i, $event).'</span></a></td>';
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
	
	<br/>
	<?php 
mysql_connect("localhost", "root","");
mysql_select_db("recup_art");

$sql = "SELECT COUNT(id_evenement) As nbArt FROM evenement ";
$req=mysql_query($sql) or die (mysql_error());
$data=mysql_fetch_assoc($req);

$nbArt = $data['nbArt'];
$perPage = 4;
$nbPage = ceil($nbArt/$perPage);

if(isset($_GET['p']) && $_GET['p']>0 && $_GET['p']<=$nbPage){
	$cPage = $_GET['p'];
}
else{
	$cPage=1;
}

?>	

<?php

$select = $db->query ( "SELECT * FROM calendrier, evenement" );

while ( $s = $select->fetch ( PDO::FETCH_OBJ ) ) {
	?>

	<div class="wrapper">
 <div class="wrapper_agenda">
<h4>Titre : <?php echo $s->titre_evenement; ?></h4>
<h4>Date : <?php echo $s->jour_evenement; ?>/<?php echo $s->mois_evenement; ?>/<?php echo $s->annee_evenement; ?></h4>
<a onclick="PopupImage(this.href);return false;" href="<?php echo $s->url_photo; ?>"><img src="<?php $url_photo='url_photo'; if($url_photo){ echo $s->url_photo; }?>" width="300" height="300"></a>



<?php echo $s->contenu_evenement; ?>

</div>

<?php
}
for ($i=1;$i<=$nbPage;$i++){
	if($i==$cPage){
		echo "$i /";
	}
	else{
		echo "<a href=\"calendrier.php?p=$i\">$i </a>/";
}
}

?>
	
    <p class="centre"><a href="index.php">Revenir à l'accueil</a></p>

</body>
</html>
