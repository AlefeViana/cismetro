<?php

require_once("../../../conecta.php");

$CdSolCons  = $_POST["cdsolcons"];
$resultado  = '';

$sql = "SELECT  receituario  
        FROM    tbreceituario_new
        WHERE   cdsolcons  = '$CdSolCons'";

$query  = mysqli_query($db, $sql);
$result = mysqli_fetch_array($query);

$receituario = $result['receituario'];

$sql = "SELECT  evolucaoclinica  
        FROM    tbevolucaoclinica_new
        WHERE   cdsolcons  = '$CdSolCons'";

$query  = mysqli_query($db, $sql);
$result = mysqli_fetch_array($query);

$evolucao = $result['evolucaoclinica'];


echo json_encode(array('receituario' => $receituario, 'evolucao' => $evolucao));
mysqli_close($db);
