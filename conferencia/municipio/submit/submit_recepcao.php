<?php
require("../../../conecta.php");

define("DIRECT_ACCESS", true);

//recebe as variaveis do formulario
$CdSolCons = $_POST["cdsolcons"];
$cdpaciente = $_POST['cdpaciente'];

$senha = '';
function gerar_hash($tamanho, $maiusculas, $minusculas, $numeros, $simbolos, $senha)
{
	$ma = "ABCDEFGHIJKLMNOPQRSTUVYXWZ";
	$mi = "abcdefghijklmnopqrstuvyxwz";
	$nu = "0123456789";
	$si = "@*";

	if ($maiusculas) {
		$senha .= str_shuffle($ma);
	}
	if ($minusculas) {
		$senha .= str_shuffle($mi);
	}
	if ($numeros) {
		$senha .= str_shuffle($nu);
	}
	if ($simbolos) {
		$senha .= str_shuffle($si);
	}
	return substr(str_shuffle($senha), 0, $tamanho);
}

$sql = "SELECT  confirmacao_presenca_paciente, aghash 
		FROM 	tbagendacons
		WHERE   CdSolCons = '$CdSolCons'";

$query 	= mysqli_query($db, $sql);
$result = mysqli_fetch_array($query);

$status = (int)$result['confirmacao_presenca_paciente'];

if ($status == 1) {
	$status = 0;
} else if ($status == 0 || empty($status)) {
	$status = 1;
}

$setaghash = '';
if (empty($result['aghash'])) {

	$hash_confirmacao = gerar_hash(15, true, true, true, true, $senha);
	$setaghash = ", ag.aghash = '" . $hash_confirmacao . "'";
}

$sql = "UPDATE  	tbagendacons 	ag
		INNER JOIN 	tbsolcons 		sol ON sol.CdSolCons = ag.CdSolCons
		SET     	ag.confirmacao_presenca_paciente = if(ag.CdSolCons = '$CdSolCons', '$status', 0)
					$setaghash
		WHERE   	sol.CdPaciente = '$cdpaciente'";

$qry = mysqli_query($db, $sql)
	or die('Paciente, confirmacao_presenca_paciente:update mysqli_query - Linha: 38');

echo json_encode($status);

mysqli_close($db);
