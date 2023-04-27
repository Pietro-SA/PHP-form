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
            <p>Nombre Completo: </p>
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
            <div class="upload-file">
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
                                $image = $target_file;
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

            $servername = "localhost";
            $username = "root";
            $password = "pAr_Ado?X8";
            $dbname = "php_form";
        
             try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                echo "Connected successfully";

                
                $sql = "CREATE TABLE data_collection (
                    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    full_name VARCHAR(50) NOT NULL,
                    gender VARCHAR(10) NOT NULL,
                    date_birth VARCHAR(10) NOT NULL,
                    country VARCHAR(30) NOT NULL,
                    find_about_us VARCHAR(70) NOT NULL,
                    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                    )";
                $conn->exec($sql);
                echo "<br>Table data_collection created successfully";
                

                $sql = "INSERT INTO data_collection (full_name, gender, date_birth, country, find_about_us)
                VALUES ('$name', '$gender', '$date_birth', '$country', '$find_about_us')";
                $conn->exec($sql);
                echo "<br>New record created successfully";
            } catch(PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
            $conn = null;
        }
        ?>

        <h2>Muchas gracias por rellenar este formulario</h2>
        <p>Aquí tienes una copia de la información que acabas de enviarnos:</p>
        <p><b>Nombre Completo: </b><?php echo $name ?></p>
        <p><b>Género: </b><?php echo $gender ?></p>
        <p><b>Data de nacimiento: </b><?php echo $date_birth ?></p>
        <p><b>País de residencia: </b><?php echo $country ?></p>
        <p><b>¿Como te has enterado por primera vez de nosotros?: </b><?php echo $find_about_us ?></p>

        <h2>Server side</h2>
        <img src="<?php echo $image ?>" alt="¡La imagen que subas se verá aqui!">
        <div class="data-retrieved">
            <?php

            echo "<table style='border: solid 1px black;'>";
            echo "<tr><th>Id</th><th>Firstname</th><th>Lastname</th></tr>";

            class TableRows extends RecursiveIteratorIterator {
                function __construct($it) {
                    parent::__construct($it, self::LEAVES_ONLY);
                }

                function current() {
                    return "<td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
                }

                function beginChildren() {
                    echo "<tr>";
                }

                function endChildren() {
                    echo "</tr>" . "\n";
                }
                
            }
            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $conn->prepare("SELECT * FROM data_collection");
                $stmt->execute();

                $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
                  echo $v;
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            $conn = null;
            echo "</table>";

            echo "<form method='post' action=borrar.php'>";
            echo "<input type='hidden' name='id' value='" . $fila["id"] . "'>";
            echo "<button type='submit'>Borrar</button>";
            echo "</form>";
            ?>
        </div>
    </main>
    <footer>
        <?php echo date("Y"); ?> Pietro Forms
    </footer>
</body>
</html>