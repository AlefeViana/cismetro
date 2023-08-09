<?php
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
$nmespecialidade   = ucwords(mb_strtolower($_POST["nmespecialidade"],'ISO-8859-1'));
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
		//gera um novo codigo
		$qry = mysqli_query($db,"SELECT MAX(cdespecialidade) FROM tbespecialidade") 
				or die(TrataErro(mysqli_errno(),'','../index.php?p=frm_cadespec','regn_especialidade:gerar novo codigo'));
				
		$cdespecialidade = mysqli_result($qry,0) + 1;
		$sql = "INSERT INTO tbespecialidade (cdespecialidade,nmespecialidade,cbo) VALUES('$cdespecialidade','$nmespecialidade','$cbo')";	
		
		$qry = mysqli_query($db,$sql) 
				or die (TrataErro(mysqli_errno(),'Especialidade','../index.php?p=frm_cadespec','regn_especialidade:insert especialidade'));
		
			echo '<script language="JavaScript" type="text/javascript"> 
					
					var agree=confirm("Cadastro realizado com sucesso! \nGostaria de cadastrar outra especialidade?");
					if (!agree)
						window.location.href="../index.php?i=3";
					else	
						window.location.href="../index.php?i=3&s=n";
					
					
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
			
			$qry = mysqli_query($db,$sql) 
					or die (TrataErro(mysqli_errno(),'Especialidade','../index.php?p=lista_espec','regn_especialidade:update especialidade'));
					
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