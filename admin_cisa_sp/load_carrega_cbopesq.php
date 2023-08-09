<?php 
	$html = header('Content-Type: text/html; charset=iso-8859-1'); 
	require_once("verifica.php");
	
	function retirar_acentos_caracteres_especiais($string) {
		$palavra = strtr($string,"ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåçèéêëìíîïðñòóôõöøùúûüýÿ",									"SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaceeeeiiiionoooooouuuuyy");
		
	$palavranova = str_replace("_", " ", $palavra);
	return $palavranova; 
}


	
	$cbopesq = (int)$_GET['cbopesq'];
	
	if($cbopesq==3)//Fornecedor
	{
		echo '<option value="23">Todos</option>';
		echo '<option value="20">Nome da Cidade</option>';
		echo '<option value="6">Data de Nascimento</option>';
		echo '<option value="5">Nome do Procedimento</option>';
		echo '<option value="4">Nome da Especificação</option>';
		echo '<option value="21">Nome do Paciente</option>';
	}
	
	/*if($cbopesq==8)//Data
	{
		echo '<option value="3">Nome do Fornecedor</option>';
		echo '<option value="4">Nome da Especificação</option>';
		echo '<option value="5">Nome do Procedimento</option>';
		echo '<option value="4">Data de Nascimento</option>';
		echo '<option value="20">Nome da Cidade</option>';
	}*/
	
	if($cbopesq==2)//Nome do Paciente
	{
		echo '<option value="23">Todos</option>';
		echo '<option value="6">Data de Nascimento</option>';
		echo '<option value="20">Nome da Cidade</option>';
		echo '<option value="4">Nome da Especificação</option>';
		echo '<option value="3">Nome do Fornecedor</option>';
		echo '<option value="5">Nome do Procedimento</option>';
	}
	
	if($cbopesq==6)//Data de Nascimento
	{
		echo '<option value="23">Todos</option>';
		echo '<option value="20">Nome da Cidade</option>';
		echo '<option value="3">Nome do Fornecedor</option>';
		echo '<option value="4">Nome da Especificação</option>';
		echo '<option value="5">Nome do Procedimento</option>';
		echo '<option value="21">Nome do Paciente</option>';
	}
	
	if($cbopesq==4)//Especificação
	{
		echo '<option value="23">Todos</option>';
		echo '<option value="6">Data de Nascimento</option>';
		echo '<option value="20">Nome da Cidade</option>';
		echo '<option value="3">Nome do Fornecedor</option>';
		echo '<option value="21">Nome do Paciente</option>';
		echo '<option value="5">Nome do Procedimento</option>';
	}
	
	if($cbopesq==5)//Procedimento
	{
		echo '<option value="23">Todos</option>';
		echo '<option value="6">Data de Nascimento</option>';
		echo '<option value="20">Nome da Cidade</option>';
		echo '<option value="3">Nome do Fornecedor</option>';
		echo '<option value="21">Nome do Paciente</option>';
		echo '<option value="4">Nome da Especificação</option>';
	}
		
	if($cbopesq==22)//Cidade
	{
		echo '<option value="23">Todos</option>';
		echo '<option value="6">Data de Nascimento</option>';
		echo '<option value="5">Nome do Procedimento</option>';
		echo '<option value="3">Nome do Fornecedor</option>';
		echo '<option value="21">Nome do Paciente</option>';
		echo '<option value="4">Nome da Especificação</option>';
	}
	
	if($cbopesq==23)
	{
		echo '<option value="23">Todos</option>';
		echo '<option value="6">Data de Nascimento</option>';
		echo '<option value="5">Nome do Procedimento</option>';
		echo '<option value="3">Nome do Fornecedor</option>';
		echo '<option value="21">Nome do Paciente</option>';
		echo '<option value="4">Nome da Especificação</option>';
		echo '<option value="20">Nome da Cidade</option>';
	}
	//echo '<option value="'.$dados["CdEspecProc"].'">'.retirar_acentos_caracteres_especiais($dados["NmEspecProc"]).'</option>';
?>



