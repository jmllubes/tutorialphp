<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Actualitza l'usuari</h1>
    <?php
    $id = $_REQUEST['id'];
    $mysql = new mysqli("localhost", "root", "", "tutorialPHP");
    if ($mysql->connect_error) {
        die('Problemas con la conexion a la base de datos');
    }

    $result = $mysql->query("select * from usuaris where id = $id") or die($mysql->error);

    $row = $result->fetch_array();

    $selected_options = explode(',', $row["cicles"]);

    // Función para comprobar si la opción está seleccionada
    function isChecked($value, $selected_options)
    {
        return in_array($value, $selected_options) ? 'checked' : '';
    }

    // Función para comprobar si el valor está seleccionado
    function isRadioChecked($value, $selected_value)
    {
        return ($value === $selected_value) ? 'checked' : '';
    }

    ?>
    <form action="actualitzar.php" method="post" enctype="multipart/form-data">

        <label for="Nom">Nom</label>
        <input type="text" name="Nom" id="Nom" value="<?php echo $row["nom"]; ?>" required><br>
        <label for="Cicle">Tria els cicles que estàs interessat</label>
        <input type="checkbox" name="DAM" id="DAM" value="DAM" <?= isChecked('DAM', $selected_options); ?>>DAM
        <input type="checkbox" name="DAW" id="DAW" value="DAW" <?= isChecked('DAW', $selected_options); ?>>DAW
        <input type="checkbox" name="ASIX" id="ASIX" value="ASIX" <?= isChecked('ASIX', $selected_options); ?>>ASIX
        <input type="checkbox" name="SMX" id="SMX" value="SMX" <?= isChecked('SMX', $selected_options); ?>>SMX <br>
        <label for="correu">Correu</label>
        <input type="email" name="correu" id="correu" value="<?php echo $row["correu"]; ?>"><br>
        <label for="password">Contrasenya</label>
        <input type="password" name="password" id="password"><br>
        <label for="data">Data de naixement</label>
        <input type="date" name="data" id="data" value="<?php echo $row["data_naixement"]; ?>" required><br>
        <label for="sexe">Sexe</label>
        <input type="radio" name="sexe" id="home" value="home" <?= isRadioChecked('home', $row["sexe"]); ?>>Home
        <input type="radio" name="sexe" id="dona" value="dona" <?= isRadioChecked('dona', $row["sexe"]); ?>>Dona
        <input type="radio" name="sexe" id="altre" value="altre" <?= isRadioChecked('altre', $row["sexe"]); ?>>Altres <br>
        <br>
        <?php if (!empty($row["foto"])): ?>
            <p>Foto actual:</p>
            <img src="<?= $row["foto"]; ?>" alt="Foto actual" style="max-width: 200px;"><br>
            <p>Puedes subir una nueva foto si lo deseas:</p>
        <?php endif; ?>

        <input type="file" name="foto" id="foto"><br>
        <br>
        <input type="submit" name="submit" value="Enviar">
    </form>
    <?php 
    if (isset($_POST["submit"])) {
        $nom = $_POST["Nom"];
        $cicle = [];
        if (isset($_POST["DAM"])) {
            $cicle[] = $_POST["DAM"];
        }
        if (isset($_POST["DAW"])) {
            $cicle[] = $_POST["DAW"];
        }
        if (isset($_POST["ASIX"])) {
            $cicle[] = $_POST["ASIX"];
        }
        if (isset($_POST["SMX"])) {
            $cicle[] = $_POST["SMX"];
        }
        $correu = $_POST["correu"];
        if(!isset($_POST["password"])){
            $password = $row["password"];
        } else {
            $password = $_POST["password"];
            $hash = password_hash($password, PASSWORD_DEFAULT);
        }
        
        $data = $_POST["data"];
        $sexe = $_POST["sexe"];
        $foto = $_FILES["foto"]["name"];
        $ruta = $_FILES["foto"]["tmp_name"];
        $desti = "imatges/" . $foto;
        move_uploaded_file($ruta, $desti);

        $mysql = new mysqli("localhost", "root", "", "tutorialPHP");
        if ($mysql->connect_error) {
            die('Problemas con la conexion a la base de datos');
        }
        $mysql->query("update usuaris set Nom='$nom', Cicles='" . implode(",", $cicle) . "', Correu='$correu', Password='$hash', Data_Naixement='$data', Sexe='$sexe', Foto='$desti' where id=$id") or
            die($mysql->error);
        $mysql->close();

        header("Location: mostrar.php");
    }

    ?>
</body>

</html>