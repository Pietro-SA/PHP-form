<?php
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = true;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["filetoUpload"]["tmp_name"]);
    if ($check !== false) { 
        echo "El archivo es una imagen - " . $check["mime"] . ".";
        $uploadOk = true;
    } else {
        echo "El archivo no es una imagen.";
        $uploadOk = false;
    }
}

if (file_exists($target_file)) {
    echo "Lo siento, el arhivo ya existe.";
    $uploadOk = false;
}

if ($_FILES["filesToUpload"]["size"] > 10000000) {
    echo "Lo siento, su archivo es demasiado grande.";
    $uploadOk = false;
}

if ($imageFileType != "jpg" && $imageFileType != "png" 
&& $imageFileType != "jpeg" && $imageFileType != "gif") {
    echo "Lo siento, apenas se aceptan los formatos JPG, JPEG, PNG o GIF para el archivo.";
    $uploadOk = false;
}

if ($uploadOk == false) {
    echo "Lo siento, su archivo no ha sido enviado.";
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "El archivo" . htmlspecialchars( basename ($_FILES["fileToUpload"]
        ["name"])) . "ha sido subido.";
    } else {
        echo "Lo siento, ha habido un error el la subida del archivo.";
    }
}
?>