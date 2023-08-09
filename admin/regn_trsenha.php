<?php

define("DIRECT_ACCESS", true);

//verifica se logado
	require_once("verifica.php");

//funcao para tratar erro
require("function_trata_erro.php");
	
//recebe as variaveis do formulario
	$senha_atual = md5(trim($_POST["senha_atual"]));
	$nova_senha  = md5(trim($_POST["senha"]));
	$conf_senha  = md5(trim($_POST["confirm_senha"]));
	
	if ($nova_senha != $conf_senha){
		echo '
						 <script language="JavaScript" type="text/javascript"> 
							alert("Confirmação da senha não condiz com a nova senha!");
							window.history.go(-1);				
			 			 </script>';
	}
	else{
		require("../conecta.php");
		$qry = mysqli_query($db,"SELECT CdUsuario FROM tbusuario WHERE CdUsuario=$_SESSION[CdUsuario] AND Senha='$senha_atual'") 
					or die(TrataErro(mysqli_errno(),'','../index.php?i=13','regn_trsenha:verifica'));
		
		if (mysqli_num_rows($qry) == 1){
			$sql = "UPDATE tbusuario
					SET Senha='$nova_senha'
					WHERE CdUsuario=$_SESSION[CdUsuario]";
			$qry = mysqli_query($db,$sql) or die(TrataErro(mysqli_errno(),'','../index.php?i=13','regn_trsenha:update senha'));
			
			echo '
							 <script language="JavaScript" type="text/javascript"> 
								alert("Senha alterada com sucesso!");
								window.location.href="../login_sai.php";				
							 </script>';
		}else{
			  echo '
							 <script language="JavaScript" type="text/javascript"> 
								alert("Senha atual incorreta, tente novamente!");
								window.location.href="../index.php?i=13";				
							 </script>';
		}
		
		@mysqli_close();
		@mysqli_free_result($qry);
		
	}
?>