<?php
include("coBDD.php");
session_start();
$target_dir = "../../images/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

$uploadOk = 1;
$messageTel="";
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        $messageTel= "Votre image est : " . $check["mime"] . ". ";
        $uploadOk = 1;
    } else {
        $messageTel="Votre fichier n'est pas une image.";
        $uploadOk = 0;
    }
}


// Vérifie si le fichier existe
if (file_exists($target_file)) {
    $messageTel="Votre fichier existe déjà .";
    $uploadOk = 0;
}


// Verifie la taille du fichier
if ($_FILES["fileToUpload"]["size"] > 5000000) {
    $messageTel="Votre image est trop large.";
    $uploadOk = 0;
}

// Vérifie l'extension du fichier
if($imageFileType != "jpg" && $imageFileType != "JPG" && $imageFileType != "png" && $imageFileType != "PNG" 
		&& $imageFileType != "jpeg" && $imageFileType != "JPEG" && $imageFileType != "gif" && $imageFileType != "GIF") {
    echo $messageTel="Désolé, seul les formats JPG, JPEG, PNG & GIF sont autorisés.";
    $uploadOk = 0;
}

//Verifie le nom et la description du fichier
if(isset($_POST["nameFile"])&&isset($_POST["descriptionFile"])){
    $nomFile=$_POST["nameFile"];
    $descriptionFile=$_POST["descriptionFile"];
}else{
    $messageTel="Le nom ou la description ne sont pas renseigné.";
    $uploadOk = 0;
}

// Vérifie si $uploadOk est à  0 ou s'il y a une erreur
if ($uploadOk == 0) {
    header("location:../index.php");
    $messageFin="Votre fichier n'est pas téléchargeable.";
    $_SESSION["messageFinTel"]=$messageTel+$messageFin;
// Si tout est ok, on essaye de télécharger l'image
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        header("location:../index.php");
        //Mettre l'image dans la bdd.
        $url="images/".$_FILES["fileToUpload"]["name"];
        $ajoutImage=$db->query("INSERT INTO `recup_art`.`galerie` VALUES (NULL, '".$nomFile."', '".$descriptionFile."', '".$url."')");
        echo $messageFin= "Le fichier ". basename( $_FILES["fileToUpload"]["name"]). " a été téléchargé.";
        $_SESSION["messageFinTel"]=$messageFin;
    } else {
        echo $messageFin= "Une erreur a été rencontrée lors du téléchargement.";
        $_SESSION["messageFinTel"]=$messageTel." ".$messageFin;
    }
}
?> 