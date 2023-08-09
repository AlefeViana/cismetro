<?php

require_once("../../../conecta.php");

$protocolo = $_POST['protocolo'];
$cdsolcons = $_POST['cdsolcons'];

$sql = "SELECT  ac.protocolopac, sc.CdPaciente  
        FROM    tbagendacons ac
        INNER JOIN tbsolcons sc ON sc.CdSolCons = ac.CdSolCons
        WHERE   ac.CdSolCons  = '$cdsolcons'";

$verifica = mysqli_query($db, $sql);
$row = mysqli_fetch_assoc($verifica);

$status = $row['protocolopac'] == strtoupper($protocolo) ? true : false;

echo json_encode(array('status' => $status, 'cdpaciente' => $row['CdPaciente']));

mysqli_close($db);
mysqli_free_result($verifica);