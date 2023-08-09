<?php

require_once("../../../conecta.php");

$CdSolCons = $_POST["cdsolcons"];

$sql = "SELECT  confirmacao_presenca_paciente 
        FROM    tbagendacons
        WHERE   CdSolCons  = '$CdSolCons'";

$query 	= mysqli_query($db, $sql);
$result = mysqli_fetch_array($query);

$status = (int)$result['confirmacao_presenca_paciente'];

echo json_encode($status);

mysqli_close($db);