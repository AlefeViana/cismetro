<?php
require("../funcoes.php");

if (isset($_GET['acao'])) {
	$acao = $_GET['acao'];
	$CdPreparo = $_GET['id'];
	$usuario = $_SESSION[CdUsuario];

	$retorno = statusPreparo($CdPreparo, $acao);
	$retorno2 = logPreparo($CdPreparo,$usuario,$acao);

	echo $retorno;
}
?>