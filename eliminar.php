<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
    $mysql = new mysqli("localhost", "root", "", "tutorialPHP");
    if ($mysql->connect_error) {
        die('Problemas con la conexion a la base de datos');
    }
    $mysql->query("delete from usuaris where id=" . $_REQUEST['id']) or
        die($mysql->error);
    $mysql->close();
    header("Location: mostrar.php");

    ?>
</body>
</html>