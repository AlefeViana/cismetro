<?php
require("../../../conecta.php");

define("DIRECT_ACCESS", true);

//recebe as variaveis do formulario
$CdSolCons = $_POST["cdsolcons"];
$cdprof = $_POST['profissional'];

$sql = "SELECT  confirmacao_presenca_prestador 
		FROM 	tbagendacons
		WHERE   CdSolCons = '$CdSolCons'";

$query 	= mysqli_query($db, $sql);
$result = mysqli_fetch_array($query);

$status = (int)$result['confirmacao_presenca_prestador'];

if($status == 1){
	$status = 0;
}else if($status == 0 || empty($status)){
	$status = 1;
}

$sql = "UPDATE  	tbagendacons 	ag
		SET     	confirmacao_presenca_prestador = if(CdSolCons = '$CdSolCons', '$status', 0)
		WHERE   	cdprof = '$cdprof'";

$qry = mysqli_query($db, $sql)
	or die('Paciente, confirmacao_presenca_paciente:update mysqli_query - Linha: 30');

echo json_encode($status);

mysqli_close($db);
