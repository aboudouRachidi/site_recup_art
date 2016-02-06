<?php include("includes/coBDD.php");?>
<html>
<head>
<style>
.wrapper_materiaux{
 Border:solid;
 position:fixed;
 bottom:0px;
  overflow:auto;
  width:2000px;
  height:87px;
  z-index: 100;
  background-color: #c0c0c0;
 
}

.wrapper_materiaux p{
display:inline;
margin-left:20px;
font-size:30px;
position:relative;
top:25px;
}

</style>
</head>

<body>
<div class="wrapper_materiaux">
<?php
$select = $db->query ( "SELECT * FROM materiaux ORDER BY nom_materiaux ASC" );

while ( $s = $select->fetch ( PDO::FETCH_OBJ ) ) {
	?>

	
		<p><a href="materiaux.php?action=afficher_materiaux&amp;id=<?php echo $s->id_materiaux;?>"><?php echo $s->nom_materiaux;} ?></a></p>
	
</div>
</body>
</html>