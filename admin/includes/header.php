<!DOCTYPE html>
<html>
<head>

<title>Administration-Agnès Recup'Art</title>
<link rel='stylesheet' type="text/css" href="style/styleAdmin.css"/>
<meta charset="utf-8">
</head>
 <body>
    <div id="header">
      <p><a href="index.php">Agnès Récup'Art</a></p>
    </div>
	<!--
    <div id="nom"><marquee direction="right" scrolldelay=110" behavior="slide"><?php setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
echo (strftime("%A %d %B %Y"));?></marquee></div>  
    -->
    <nav class="navbar">
      <p id="accueil"><a href="index.php">Accueil</a></p>
      <p id="categories"><a href="gestionCategorie.php">Gestion des Categories</a></p>
      <p id="materiaux"><a href="gestionMateriaux.php">Gestion des materiaux</a></p>
      <p id="couleurs"><a href="gestionCouleurs.php">Gestion des <i id="c">c</i><i id="o">ou</i><i>l</i><i id="e">eu</i>rs</a></p>
      <p id="produits"><a href="gestionProduits.php">Gestion des produits</a></p>
      <!--<p id="demarche"><a href="gestionDemarches.php">Gestion de démarche</a></p>-->
      <p id="agenda"><a href="gestionAgenda.php">Gestion de l'agenda</a></p>
      <p id="blog"><a href="gestionBlog.php">Gestion du Blog</a></p>
      <p id="galerie"><a href="gestionGalerie.php">Gestion de la galerie</a></p>
      <p id="clients"><a href="gestionClients.php">Gestion des clients</a></p>
      <p id="miseAvant"><a href="gestionMiseEnAvant.php">Gestion de la page d'accueil</a></p>
    </nav>
 
   	<select id="deconnexion" onchange="location = this.options[this.selectedIndex].value;">
		<option ><em>B</em>ienvenue Mme <?php echo $_SESSION['admin_nom']; ?>&nbsp;&equiv;</option>
     <div id="deconnexion">
    	<option value="modifierCompte.php?action=modify/delete">Modifier compte</option>
    	<option value="includes/deconnexionAdmin.php">Deconnexion</option>
    </div>
</select>
</body>
</html>