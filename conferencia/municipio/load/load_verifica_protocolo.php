<?php

require_once("../../../conecta.php");

$protocolo = $_POST['protocolo'];
$cdsolcons = $_POST['cdsolcons'];

$sql = "SELECT  protocolopac, aghash  
        FROM    tbagendacons
        WHERE   CdSolCons  = '$cdsolcons'";

$verifica = mysqli_query($db, $sql);
$row = mysqli_fetch_assoc($verifica);

$status = $row['protocolopac'] == strtoupper($protocolo) ? true : false;
$hash = $row['aghash'];

echo json_encode(array('status' => $status, 'hash' => $hash));

mysqli_close($db);
mysqli_free_result($verifica);