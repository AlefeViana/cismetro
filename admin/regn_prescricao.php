<?php

	define("DIRECT_ACCESS", true);

//verifica se logado
	require_once("verifica.php");

//funcao para tratar erro
	require("function_trata_erro.php");

	//verifica se o usuario tem permissão para acessar a pagina
	if ((int)$_SESSION["CdTpUsuario"] != 7)	
	{
		echo '<script language="JavaScript" type="text/javascript"> 
			window.location.href="index.php?p=inicial";				
		  </script>';	
	}	
//recebe as variaveis do formulario
	$Descricao  = $_POST["descricao"];
	$Prescricao = $_POST["prescricao"];
	$CdPaciente = $_POST["cdpaciente"];
	if ($Prescricao == "" || $Descricao == ""){
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						 <script language="JavaScript" type="text/javascript"> 
							alert("Todos os campos são obrigatórios!");
							window.history.go(-1);				
			 			 </script>';
	}
	else{
		$data = date("Y-m-d");
		require("../conecta.php");
		$qry = mysqli_query($db,"SELECT MAX(CdHPac) FROM tbhistoricopac") 
				or die(TrataErro(mysqli_errno(),'','../index.php?p=lista_pac','regn_prescricao:gerar novo codigo'));
				
		$CdHPac = mysqli_result($qry,0) + 1;
		$sql = "INSERT INTO tbhistoricopac (CdHPac,Prescricao,Descricao,Data,CdPaciente,CdUsuario) 
					VALUES ($CdHPac,'$Prescricao','$Descricao','$data',$CdPaciente,$_SESSION[CdUsuario])";
					
		$qry = mysqli_query($db,$sql) 
				or die(TrataErro(mysqli_errno(),'','../index.php?p=lista_pac','regn_prescricao:insert prescricao'));
				
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
							 <script language="JavaScript" type="text/javascript"> 
								alert("Dados incluidos com sucesso!");
								//window.location.href="../index.php?p=lista_pac";				
							 </script>';
		
		@mysqli_close();
		@mysqli_free_result($qry);
		echo '<script language="JavaScript" type="text/javascript"> 
					
					var agree=confirm("Deseja imprimir a prescrição agora?");
					if (!agree)
						window.location.href="../index.php?p=lista_pac";
					else{	
						//window.open("rel_imp_prescricao.php?id='.$CdHPac.'");
						window.location.href="../index.php?p=lista_hist_pac&id='.$CdPaciente.'&last='.$CdHPac.'";
					}
					
				  </script>';
	}
?>