<?php 
	$html = header('Content-Type: text/html; charset=iso-8859-1'); 
	require("../funcoes.php");

	$cdp = $_GET['cdp'];
	
	echo '<b>Especificaзгo Principal:</b> '.nomeEspecificacao($cdp);

	echo '<input type="hidden" name="CdEspecProcPai" id="CdEspecProcPai"value="'.$cdp.'">';
	
?>

