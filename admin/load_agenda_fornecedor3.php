<?php 
	
	define("DIRECT_ACCESS",  true);
	
	require_once("verifica.php");

	$cbopesq = (int)$_GET['cbopesq'];
	
	if($cbopesq!=1 && $cbopesq!=2)//Diferente de Data, 
	{
		echo '<option value="9">Data de Início</option>';
		
	}
?>