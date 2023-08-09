<?php

require_once("../../../conecta.php");

$CdSolCons = $_POST["cdsolcons"];

$sql = "SELECT  aghash, tipoatend  
        FROM    tbagendacons
        WHERE   CdSolCons  = '$CdSolCons'";

$query 	= mysqli_query($db, $sql);
$result = mysqli_fetch_array($query);

$result['aghash'];

echo json_encode(empty($result['aghash'] && $result['tipoatend'] == 'T') ? false : true);

mysqli_close($db);