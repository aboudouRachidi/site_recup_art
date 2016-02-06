<?php
function creationPanier() {
	try {
		$db = new PDO ( 'mysql:host=localhost;dbname=recup_art', 'root', '' );
		$db->setAttribute ( PDO::ATTR_CASE, PDO::CASE_LOWER ); // les noms de champs seront en caracteres miniscule
		$db->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); // les erreurs lanceront des exceptions
	} catch ( Exception $e ) {
		
		die ( 'une erreur est survenue' );
	}
	
	if (! isset ( $_SESSION ['panier'] )) {
		
		$_SESSION ['panier'] = array ();
		$_SESSION ['panier'] ['libelleProduit'] = array ();
		$_SESSION ['panier'] ['qteProduit'] = array ();
		$_SESSION ['panier'] ['prixProduit'] = array ();
		$_SESSION ['panier'] ['typelivraison'] = array ();
		$_SESSION ['panier'] ['fraislivraison'] = array ();
		$_SESSION ['panier'] ['verrou'] = false;
	}
	return true;
}
function ajouterProduit($libelleProduit, $qteProduit, $prixProduit, $typelivraison, $fraislivraison) {
	if (creationPanier () && ! isVerouille ()) {
		
		$position_produit = array_search ( $libelleProduit, $_SESSION ['panier'] ['libelleProduit'] );
		
		if ($position_produit !== false) {
			
			$_SESSION ['panier'] ['qteProduit'] [$position_produit] += $qteProduit;
		} else {
			
			array_push ( $_SESSION ['panier'] ['libelleProduit'], $libelleProduit );
			array_push ( $_SESSION ['panier'] ['qteProduit'], $qteProduit );
			array_push ( $_SESSION ['panier'] ['prixProduit'], $prixProduit );
			array_push ( $_SESSION ['panier'] ['typelivraison'], $typelivraison );
			array_push ( $_SESSION ['panier'] ['fraislivraison'], $fraislivraison );
		}
	} else {
		
		echo 'Erreur, veuillez contacter l\'administrateur';
	}
}
function modifierQteProduit($libelleProduit, $qteProduit) {
	if (creationPanier () && ! isVerouille ()) {
		
		if ($qteProduit > 0) {
			
			$positionProduit = array_search ( $libelleProduit, $_SESSION ['panier'] ['libelleProduit'] );
			
			if ($positionProduit !== false) {
				
				$_SESSION ['panier'] ['qteProduit'] [$positionProduit] = $qteProduit;
			}
		} else {
			
			supprimerProduit ( $libelleProduit );
		}
	} else {
		
		echo 'Erreur, veuillez contacter l\'administrateur';
	}
}
function supprimerProduit($libelleProduit) {
	if (creationPanier () && ! isVerouille ()) {
		$tmp = array ();
		$tmp ['libelleProduit'] = array ();
		$tmp ['qteProduit'] = array ();
		$tmp ['prixProduit'] = array ();
		$tmp ['typelivraison'] = array ();
		$tmp ['fraislivraison'] = array ();
		$tmp ['verrou'] = $_SESSION ['panier'] ['verrou'];
		
		
		for($i = 0; $i < count ( $_SESSION ['panier'] ['libelleProduit'] ); $i ++) {
			
			if ($_SESSION ['panier'] ['libelleProduit'] [$i] !== $libelleProduit) {
				
				array_push ( $tmp ['libelleProduit'], $_SESSION ['panier'] ['libelleProduit'] [$i] );
				array_push ( $tmp ['qteProduit'], $_SESSION ['panier'] ['qteProduit'] [$i] );
				array_push ( $tmp ['prixProduit'], $_SESSION ['panier'] ['prixProduit'] [$i] );
				array_push ( $tmp ['typelivraison'], $_SESSION ['panier'] ['typelivraison'] [$i] );
				array_push ( $tmp ['fraislivraison'], $_SESSION ['panier'] ['fraislivraison'] [$i] );
			}
		}
		
		$_SESSION ['panier'] = $tmp;
		
		unset ( $tmp );
	} else {
		
		echo 'Erreur, veuillez contacter l\'administrateur';
	}
}
function montantTotal() {
	$total = 0;
	
	for($i = 0; $i < count ( $_SESSION ['panier'] ['libelleProduit'] ); $i ++) {
		$total += $_SESSION ['panier'] ['qteProduit'] [$i] * $_SESSION ['panier'] ['prixProduit'] [$i] + $_SESSION ['panier'] ['fraislivraison'] [$i] *  $_SESSION ['panier'] ['qteProduit'] [$i];
	}
	return $total;
}
function supprimerPanier() {
	unset ( $_SESSION ['panier'] );
}
function isVerouille() {
	if (isset ( $_SESSION ['panier'] ) && $_SESSION ['panier'] ['verrou']) {
		
		return true;
	} else {
		
		return false;
	}
}
function compterProduit() {
	if (isset ( $_SESSION ['panier'] )) {
		
		return count ( $_SESSION ['panier'] ['libelleProduit'] );
	} else {
		return 0;
	}
}

?>