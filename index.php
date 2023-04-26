<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Formulario Ecológico</title>
</head>
<?php
    $name = $date_birth = $country = "";

    function testInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    function verify($name, $message, &$value) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($_POST["$name"])) {
                return $message;
            } else {
                $value = testInput($_POST["$name"]);
                return "";
            }
        }
    }
?>
<body>
    <header>
        <h1>Nos gustaría saber si tienes hábitos amigables con el medio ambiente</h1>
        <p>Por favor, rellena este formulario honestamente para ayudarnos a entender mejor el impacto ambiental y la huella de carbono de las personas</p>
    </header>
    <main>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <p>Nombre: </p>
            <input type="text" class="generic" name="name">
            <span class="error">* <?php echo verify("name", "El nombre no puede estar en blanco", $name); ?></span>
            <p>Género: </p>
            <input type="radio" name="gender" value="Masculino"><span>Masculino</span>
            <input type="radio" name="gender" value="Femenino"><span>Femenino</span>
            <input type="radio" name="gender" value="Otro" checked><span>Otro</span>
            <br>
            <p>Data de nacimiento: </p>
            <input type="date" class="generic" name="date_birth">
            <span class="error">* <?php echo verify("date_birth", "La data de nacimiento no puede estar en blanco", $date_birth); ?></span>
            <p>País de residencia: </p>
            <input type="text" class="generic" name="country">
            <span class="error">* <?php echo verify("country", "El país no puede estar en blanco", $country); ?></span>
            <p>¿Como te has enterado por primera vez de nosotros?</p>
            <select name="find_about_us">
                <option value="Me he enterado através de un amigo o familiar">Me he enterado através de un amigo o familiar</option>
                <option value="He visto un anuncio en Internet">He visto un anuncio en Internet</option>
                <option value="Otro medio">Otro medio</option>
            </select>
            <br>
            <p>OPCIONAL: Envíanos una foto de un paisaje natural que te encante</p><input type="file" name="fileToUpload">
            <br>
            <div class="upload-log">
                <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $target_dir = "uploads/";
                        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                        $uploadOk = true;
                        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

                        if (isset($_POST["submit"])) {
                            $check = getimagesize($_FILES["filetoUpload"]["tmp_name"]);
                            if (file_exists($target_file)) {
                                echo "Lo siento, el arhivo ya existe.";
                                $uploadOk = false;
                            } else if ($_FILES["fileToUpload"]["size"] > 10000000) {
                                echo "Lo siento, su archivo es demasiado grande.";
                                $uploadOk = false;
                            } else if ($imageFileType != "jpg" && $imageFileType != "png" 
                            && $imageFileType != "jpeg" && $imageFileType != "gif") {
                                echo "Lo siento, apenas se aceptan los formatos JPG, JPEG, PNG o GIF para el archivo.";
                                $uploadOk = false;
                            } else if ($check !== false) { 
                                echo "El archivo es una imagen - " . $check["mime"] . ".";
                                $uploadOk = true;
                            } else {
                                echo "El archivo no es una imagen.";
                                $uploadOk = false;
                            }
                        }
                        if ($uploadOk == false) {
                            echo "Lo siento, su archivo no ha sido enviado.";
                        } else {
                            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                                echo "El archivo" . htmlspecialchars( basename ($_FILES["fileToUpload"]
                                ["name"])) . " ha sido subido.";
                            } else {
                                echo "Lo siento, ha habido un error el la subida del archivo.";
                            }
                        }
                    }
                ?>
            </div>
            <input type="submit" class="submit" value="Finalizar y enviar datos">
        </form>

        <?php
        $gender = $find_about_us = $image = "";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $gender = testInput($_POST["gender"]);
            $find_about_us = testInput($_POST["find_about_us"]);
            if ($uploadOk) {
                $image = $target_file;
            }
        }
        /* if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($_POST["name"])) {
               $nameErr = "El nombre no puede estar en blanco";
            } else {
                $name = testInput($_POST["name"]);
            }
            $gender = testInput($_POST["gender"]);
            if (empty($_POST["date_birth"])) {
                $date_birthErr = "La data de nacimiento no puede estar en blanco";
            } else {
                $date_birth = testInput($_POST["date_birth"]);
            }
            if (empty($_POST["country"])) {
                $countryErr = "EL país no puede estar en blanco";
            } else {
                $country = testInput($_POST["country"]);
            }
            $find_about_us = testInput($_POST["find_about_us"]);
        } */
        ?>

        <h2>Muchas gracias por rellenar este formulario</h2>
        <p>Aquí tienes una copia de la información que acabas de enviarnos:</p>
        <p><b>Nombre: </b><?php echo $name ?></p>
        <p><b>Género: </b><?php echo $gender ?></p>
        <p><b>Data de nacimiento: </b><?php echo $date_birth ?></p>
        <p><b>País de residencia: </b><?php echo $country ?></p>
        <p><b>¿Como te has enterado por primera vez de nosotros?: </b><?php echo $find_about_us ?></p>
        <img src="<?php echo $image ?>" alt="¡La imagen que subas se verá aqui!">
    </main>
    <footer>
        2023 Pietro Forms
    </footer>
</body>
</html>