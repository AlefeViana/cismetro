<?php

require_once("../../../conecta.php");

$cdsolcons = $_POST['cdsolcons'];

$sql = "SELECT  id  
        FROM    tbreceituario_new
        WHERE   cdsolcons  = '$cdsolcons'";

$verifica = mysqli_query($db, $sql);
$row = mysqli_fetch_assoc($verifica);

$idreceituario = $row['id'];

echo json_encode($idreceituario);

mysqli_close($db);
mysqli_free_result($verifica);