<?php 
	
	define("DIRECT_ACCESS",  true);

	require_once("verifica.php");
	
	
	$cbopesq = (int)$_GET["cbopesq"];
	
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



