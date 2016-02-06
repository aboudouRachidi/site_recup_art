<?php include("includes/coBDD.php");?>
<html>
<head>
<style>
.categorie{


}
.wrapper_categorie{
position:fixed;
right:0px;
Border:solid;
width:200px;
bottom:90px;
height:480px;
overflow:auto;
text-align:center;
background-color: #c0c0c0;
}

</style>
</head>
<body>

<div class="wrapper_categorie">
<?php
$select = $db->query ( "SELECT * FROM categorie" );

while ( $s = $select->fetch ( PDO::FETCH_OBJ ) ) {
?>

	<nav class="categorie" >
		<p> <a href="categorie.php?action=afficher_categories&amp;id=<?php echo $s->id_categorie;?>"><?php echo $s->nom_categorie; }?></a></p>
	</nav>
</div>
</body>
</html>