<?php

define("DIRECT_ACCESS", true);

include("verifica.php");
require("../conecta.php");
require("../funcoes.php");

if (isset($_GET['acao'])) {
	$acao = $_GET['acao'];
	$CdContrato = $_GET['id'];
	$usuario = $_SESSION['CdUsuario'];

	$retorno = statusContrato($CdContrato, $acao);
	$retorno2 = logContratos($CdContrato,$usuario,$acao);

	echo $retorno;
}
?>