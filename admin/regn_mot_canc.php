<?php

define("DIRECT_ACCESS", true);

//verifica se o usuario esta logado no sistema
require_once("verifica.php");

//funcao para tratar erro
require("function_trata_erro.php");

//recebe a variavel caso chamado do Cadastro de Paciente
$PagDestino = (int)$_GET["pg"];

//recebe as variaveis do formulario
$cdmotcanc   = $_POST["cdmotcanc"];
$nmmotcanc 	= ucwords(mb_strtolower($_POST["nmmotcanc"]));
$tpmot = $_POST["tpmot"];

//recebe o tipo de acao
$acao       = $_POST["acao"];

	if (substr($cdmotcanc,0,4) == "Auto")
	{
		require("../conecta.php");
		
			$sql = "INSERT INTO tbmotcanc (nmmotcanc,tpmot) VALUES ('$nmmotcanc','$tpmot')";	
			$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','../index.php?p=frm_cadbairro','regn_bairro:insert bairro'));
			//verifica a pagina de destino
			if ($PagDestino == 1){
				echo '<script language="JavaScript" type="text/javascript"> 
					alert("Cadastro realizado com sucesso!");
					window.location.href="../index.php?p=frm_cadpac";
				  </script>';
			}
			else
			{
				echo '<script language="JavaScript" type="text/javascript"> 
						
						var agree=confirm("Cadastro realizado com sucesso! \nGostaria de cadastrar outro motivo?");
						if (!agree)
							window.location.href="../index.php?i=74";
						else	
							window.location.href="../index.php?i=74&s=n";
						
						
					  </script>';
						
				//echo "Cadastro realizado com sucesso!";
			}

	}
	
	@mysqli_close();
	@mysqli_free_result($qry);
?>