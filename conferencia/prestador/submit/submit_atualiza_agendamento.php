<?php

require("../../../conecta.php");

//recebe as variaveis do formulario
$CdSolCons  = $_POST["cdsolcons"];
/*
$cdprof     = $_POST['profissional'];
$texto      = $_POST['texto'];
$cdpaciente = $_POST['cdpaciente'];
$dataAtual  = date('Y-m-d H:i:s');
*/

$sql = "UPDATE      tbagendacons    ag
        INNER JOIN  tbsolcons       sc ON sc.CdSolCons = ag.CdSolCons
        SET     ag.`Status` = '2', 
                sc.`status` = '1' 
        WHERE   ag.CdSolCons   = '$CdSolCons'";

$valida = mysqli_query($db, $sql);
