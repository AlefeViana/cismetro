<?php 
	$html = header('Content-Type: text/html; charset=iso-8859-1'); 
	require_once("verifica.php");
	
	function retirar_acentos_caracteres_especiais($string) {
		$palavra = strtr($string,"ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåçèéêëìíîïðñòóôõöøùúûüýÿ",									"SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaceeeeiiiionoooooouuuuyy");
		
	$palavranova = str_replace("_", " ", $palavra);
	return $palavranova; 
}


	
	$cbopesq = (int)$_GET["cbopesq"];
	
	if($cbopesq==3)//Fornecedor
	{
		echo '<option value="14">Todos</option>';
		echo '<option value="7">Especificação</option>';
		//echo '<option value="8">Cidade</option>';
		echo '<option value="12">Procedimento</option>';
	}
	
	if($cbopesq==2)//Data
	{
		echo '<option value="14">Todos</option>';
		echo '<option value="6">Fornecedor</option>';
		echo '<option value="7">Especificação</option>';
		echo '<option value="12">Procedimento</option>';
		//echo '<option value="8">Cidade</option>';
	}
	
	/*if($cbopesq==5)//Cidade
	{
		echo '<option value="14">Todos</option>';
		echo '<option value="6">Fornecedor</option>';
		echo '<option value="7">Especificação</option>';
		echo '<option value="12">Procedimento</option>';
	}*/
	
	if($cbopesq==4)//Especificação
	{
		echo '<option value="14">Todos</option>';
		echo '<option value="6">Fornecedor</option>';
		//echo '<option value="8">Cidade</option>';
		echo '<option value="12">Procedimento</option>';
	}
	
	if($cbopesq==11)//Procedimento
	{
		echo '<option value="14">Todos</option>';
		echo '<option value="6">Fornecedor</option>';
		echo '<option value="7">Especificação</option>';
		//echo '<option value="8">Cidade</option>';
	}
	
	if($cbopesq==13)//Todos
	{
		echo '<option value="14">Todos</option>';
		echo '<option value="6">Fornecedor</option>';
		echo '<option value="7">Especificação</option>';
		//echo '<option value="8">Cidade</option>';
	}
?>



