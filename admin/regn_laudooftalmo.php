<?php

	define("DIRECT_ACCESS", true);

	include("../verifica.php");
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
	$QIAOD  		= $_POST["qiaod"];
	$QIAOE  		= $_POST["qiaoe"];
	$AMOD			= $_POST["amod"];
	$AMOE			= $_POST["amoe"];
	$EFOD	 		= $_POST["evidenciaod"];
	$EFOE 			= $_POST["evidenciaoe"];
	$OAOD 			= $_POST["outrasaltod"];
	$OAOE 			= $_POST["outrasaltoe"];
	$LOD	 		= $_POST["laudood"];
	$LOE	 		= $_POST["laudooe"];
	$COD 			= $_POST["comentariosod"];
	$COE 			= $_POST["comentariosoe"];
	$CdPaciente 	= $_POST["cdpaciente"];
	
	if (false){
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						 <script language="JavaScript" type="text/javascript"> 
							alert("Todos os campos são obrigatórios!");
							window.history.go(-1);				
			 			 </script>';
	}
	else{
		
		require("../conecta.php");
		
		$DtInc = date('Y-m-d H:i:s');
		
		$sql1 = "INSERT INTO tblaudo (CdPaciente,DtInc,UserInc,Tabela) 
					VALUES ($CdPaciente,'$DtInc',$_SESSION[CdUsuario],'tblaudooftalmo')";
					
		$sql2 = "INSERT INTO tblaudooftalmo (CdPaciente,DtInc,QIAOD,QIAOE,AMOD,AMOE,EFOD,EFOE,OAOD,OAOE,LOD,LOE,COD,COE) 
					VALUES ($CdPaciente,'$DtInc','$QIAOD','$QIAOE','$AMOD','$AMOE','$EFOD','$EFOE','$OAOD','$OAOE','$LOD','$LOE','$COD','$COE')";
					
		$qry = mysqli_query($db,$sql1) 
				or die(TrataErro(mysqli_errno(),'','../index.php?p=lista_pac','regn_laudooftalmo:insert laudo'));
		
		$qry = mysqli_query($db,$sql2) 
				or die(TrataErro(mysqli_errno(),'','../index.php?p=lista_pac','regn_laudooftalmo:insert laudo oftalmo'));
				
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
							 <script language="JavaScript" type="text/javascript"> 
								alert("Dados incluidos com sucesso!");
								var agree=confirm("Deseja imprimir o laudo agora?");
								if (!agree)
									window.location.href="../index.php?p=lista_pac";
								else{					
									window.location.href="../index.php?p=lista_laudo_oftalmo&id='.$CdPaciente.'";
								}
							 </script>';
		
		@mysqli_close();
		@mysqli_free_result($qry);
	}
?>