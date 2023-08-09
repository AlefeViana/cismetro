<?php

define("DIRECT_ACCESS", true);

//verifica se o usuario esta logado no sistema
require_once("verifica.php");

//verifica se o usuario tem permissão para acessar a pagina
if ((int)$_SESSION["CdTpUsuario"] != 1 && (int)$_SESSION["CdTpUsuario"] != 2)	
{
	echo '<script language="JavaScript" type="text/javascript"> 
		window.location.href="index.php";				
	  </script>';	
}

//funcao para tratar erro
require_once("function_trata_erro.php");

//recebe as variaveis do formulario
$cdespecialidade   = $_POST["cdespecialidade"];
$nmespecialidade   = ucwords(mb_strtolower($_POST["nmespecialidade"]));
$cbo    = $_POST["cbo"];

//recebe o tipo de acao
$acao       = $_POST["acao"];

//verifica campos obrigatórios
$tudook = 1;
if ($nmespecialidade == ""){
	$msg_erro .= 'Preencha o campo Especialidade<br />';
	$tudook = 0; 
}

//Controla se os campos obrigatorios estao preenchidos
if ($tudook == 0){
	echo "Favor verificar o seguinte campo!<br /><br />".$msg_erro;
	echo '<br /><br /><a href="#" onclick="javascript: history.go(-1);">Voltar</a>';		
}
else
{
	if (substr($cdespecialidade,0,4) == "Auto")
	{
		require("../conecta.php");

		$sql = "INSERT INTO tbespecialidade (nmespecialidade,cbo) VALUES('$nmespecialidade','$cbo')";	
		
		$qry = mysqli_query($db,$sql);
		
			echo '<script language="JavaScript" type="text/javascript"> 
					var agree=confirm("Cadastro realizado com sucesso!");
					window.location.href="../index.php?i=3";				
				  </script>';
					
			//echo "Cadastro realizado com sucesso!";
		
	}
	else
	{
		$cdespecialidade = (int)$cdespecialidade;
		if($acao == "edit")
		{
			//alterar
			$sql = "UPDATE tbespecialidade
						SET nmespecialidade = '$nmespecialidade',
						    cbo = '$cbo'
						WHERE cdespecialidade=$cdespecialidade";
			
			require("../conecta.php");
			
			$qry = mysqli_query($db,$sql);
					
			echo '<script language="JavaScript" type="text/javascript"> 
				alert("Dados alterados com sucesso!");
				window.location.href="../index.php?i=3&s=n&id='.$cdespecialidade.'&acao=edit";				
			  </script>';
		}
		else
		{
			if($acao == "del")
			{
				//excluir	
				require("../conecta.php");
				//verifica se existe algum especialidade vinculado ao fornecedor
				
					$sql = "DELETE FROM tbespecialidade WHERE cdespecialidade=$cdespecialidade";	
					
					$qry = mysqli_query($db,$sql);
					
					if($qry)
					{
					echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
						<script language=\"JavaScript\" type=\"text/javascript\"> 
							alert(\"Especialidade excluída com sucesso!\");
							window.location.href=\"../index.php?i=3&s=l\";				
			 			 </script>";
					}
					else
					{
					echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
						<script language=\"JavaScript\" type=\"text/javascript\"> 
							alert(\"Erro ao tentar excluir a especialidade selecionada!\");
							window.location.href=\"../index.php?i=3&s=l\";				
			 			 </script>";
					}
				
				
			}
			
		}
	}
	@mysqli_close();
	@mysqli_free_result($qry);
}
?>