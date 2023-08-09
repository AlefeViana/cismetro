<?php 
	
	define("DIRECT_ACCESS",  true);

	require_once("verifica.php");

	
	$cbopesq = (int)$_GET['cbopesq'];
	
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
		echo '<option value="7">Especifica��o</option>';
		echo '<option value="12">Procedimento</option>';
	}*/
	
	if($cbopesq==4)//Especifica��o
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



