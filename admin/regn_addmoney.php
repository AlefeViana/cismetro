<?php

define("DIRECT_ACCESS", true);

//verifica se o usuario esta logado no sistema
require_once("verifica.php");

//verifica se o usuario tem permissão para acessar a pagina
if ((int)$_SESSION["CdTpUsuario"] != 1)	
{
	echo '<script language="JavaScript" type="text/javascript"> 
		window.location.href="index.php?p=inicial";				
	  </script>';	
}

//funcao para tratar erro
require_once("function_trata_erro.php");

//recebe as variaveis do formulario
$CdPref   = (int)$_POST["cd_prefeitura"];
$Valor    = $_POST["valor"];
$TpEntrada= $_POST["tpentrada"];

//verifica campos obrigatórios
$tudook = 1;
if ($CdPref < 1){
	$msg_erro .= 'Selecione uma prefeitura.<br />';
	$tudook = 0; 
}
if ($Valor == ""){
	$msg_erro .= 'Preencha o campo valor.<br />';
	$tudook = 0; 
}

//Controla se os campos obrigatorios estao preenchidos
if ($tudook == 0){
	echo "Favor verificar o seguinte campo!<br /><br />".$msg_erro;
	echo '<br /><br /><a href="#" onclick="javascript: history.go(-1);">Voltar</a>';		
}
else
{
	require('../conecta.php');
	$qry = mysqli_query($db,"SELECT MAX(CdMov) FROM tbmovimentacao")
					or die (TrataErro(mysqli_errno(),'','../index.php?p=lista_pref','regn_addmoney:select novo codigo'));	
	$CdMov = mysqli_result($qry,0) + 1;
	
	$Valor = str_replace(',','.',str_replace('.','',$Valor));
	
	$sql = "INSERT INTO tbmovimentacao(CdMov,CdPref,CdUsuario,TpMov,";
	$sql .= "Credito";

	$sql .= ")VALUES($CdMov,$CdPref,$_SESSION[CdUsuario],'$TpEntrada',$Valor)";
	$qry = mysqli_query($db,$sql)or die (TrataErro(mysqli_errno(),'','../index.php?p=lista_pref','regn_addmoney:insert mov'));	
			
	echo '<script language="JavaScript" type="text/javascript"> 
		alert("Movimento realizado com sucesso!");
		window.location.href="../index.php?i=8";				
	  </script>';
	@mysqli_close();
	@mysqli_free_result($qry);
}
?>