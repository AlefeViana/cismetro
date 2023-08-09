<?php 
	
	define("DIRECT_ACCESS",  true);

	include("../verifica.php");
	require("../funcoes.php");

	$cdp = $_GET['cdp'];
	
	echo '<b>Especificação Principal:</b> '.nomeEspecificacao($cdp);

	echo '<input type="hidden" name="CdEspecProcPai" id="CdEspecProcPai"value="'.$cdp.'">';
	
?>

