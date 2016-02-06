<?php
session_start ();

try {
	$db = new PDO ( "mysql:host=localhost;dbname=recup_art;charser=UTF8", 'root', '' );
	$db->setAttribute ( PDO::ATTR_CASE, PDO::CASE_LOWER ); // les noms de champs seront en caracteres miniscule
	$db->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); // les erreurs lanceront des exceptions
} catch ( Exception $e ) {

	die ( 'une erreur est survenue' );
}
?>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, maximum-scale=1">
  <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js'></script>
   <LINK rel="stylesheet" href="style/index.css" /> 
   <link rel="stylesheet" href="style/print.css" type="text/css" media="print"/>
 
 <script type="text/javascript">
$(function() {
    // Stick the #nav to the top of the window
    var nav = $('#navWrap');
    var navHomeY = nav.offset().top;
    var isFixed = false;
    var $w = $(window);
    $w.scroll(function() {
        var scrollTop = $w.scrollTop();
        var shouldBeFixed = scrollTop > navHomeY;
        if (shouldBeFixed && !isFixed) {
            nav.css({
                position: 'fixed',
                
                top: 0,
                left: nav.offset().left,
                width: nav.width()
                
            });
            isFixed = true;
        }
        else if (!shouldBeFixed && isFixed)
        {
            nav.css({
                position: 'static',
                	
            });
            isFixed = false;
        }
    });
});

var scrollTop = $w.scrollTop();
var shouldBeFixed = scrollTop > navHomeY;
if (shouldBeFixed && !isFixed) {
    nav.css({
        position: 'fixed',
        top: 0,
        left: nav.offset().left,
        width: nav.width()
    });
    isFixed = true;
}
else if (!shouldBeFixed && isFixed)
{
    nav.css({
        position: 'static'
    });
    isFixed = false;
}
</script>

 <!--  
 <script type="text/javascript">
$(function() {
    // Stick the #nav to the top of the window
    var nav = $('.materiaux');
    var navHomeY = nav.offset().top;
    var isFixed = false;
    var $w = $(window);
    $w.scroll(function() {
        var scrollTop = $w.scrollTop();
        var shouldBeFixed = scrollTop > navHomeY;
        if (shouldBeFixed && !isFixed) {
            nav.css({
                position: 'fixed',
                bottom: 0,
                left:200px,
                left: nav.offset().left,
                width: nav.width()
            });
            isFixed = true;
        }
        else if (!shouldBeFixed && isFixed)
        {
            nav.css({
                position: 'static'
            });
            isFixed = false;
        }
    });
});

var scrollTop = $w.scrollTop();
var shouldBeFixed = scrollTop > navHomeY;
if (shouldBeFixed && !isFixed) {
    nav.css({
        position: 'fixed',
        bottom: 0,
        left:220px,
        left: nav.offset().left,
        width: nav.width()
    });
    isFixed = true;
}
else if (!shouldBeFixed && isFixed)
{
    nav.css({
        position: 'static'
    });
    isFixed = false;
}
</script>
-->
  <title>Agnès Récup'Art</title>
    <div id="header">
    
      <p><a href="index.php">Agnès Récup'Art</a></p>
    </div>
    <div id="navWrap">
    	<div id="nav">
      			<p id="accueil"><a href="index.php">accueil</a></p>
     			<p id="produit"><a href="liste_produit.php">produits</a></p>
     			<p id="panier"><a href="connexion2.php">panier</a></p>
      			<p id="blog"><a href="blog.php">Blog</a></p>
      			<p id="agenda"><a href="agenda.php">Agenda</a></p>
     		 	<p id="galerie"><a href="galerie.php">Galerie</a></p>
     		 	<p id="demarche"><a href="demarche.php">Qui suis-je?</a></p>
    			<p id="contact"><a href="contact2.php">Contact</a></p>
      		</div>
      </div> 
      <?php 
$select = $db->query ( "SELECT * FROM `message_defilant`" );


while ( $s = $select->fetch ( PDO::FETCH_OBJ ) ) {
	?>


<div id=defilant><marquee direction="left"><?php echo $s->message;?> </marquee> </div>
 
<?php } ?>

</html>
 <?php if(isset($_SESSION['user_id'])){ echo '<a href="deconnexion.php"><p<div id= connexion>Déconnexion</div></a></p>';}else{ echo'<a href="connexion.php"><p<div id= connexion>Connexion</div></a></p>';}?>