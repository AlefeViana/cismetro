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
$cdprof   = $_POST["cdprof"];
$nmprof   = ucwords(mb_strtolower($_POST["nmprof"],'ISO-8859-1'));
$cnsprof    = $_POST["cnsprof"];
$crm 		= $_POST["crmprof"];
$cdconselho = $_POST["cdconselho"];
$credenciado = $_POST["credenciado"];

//recebe o tipo de acao
$acao       = $_POST["acao"];

//verifica campos obrigatórios
$tudook = 1;
if ($nmprof == ""){
	$msg_erro .= 'Preencha o campo nome<br />';
	$tudook = 0; 
}

//Controla se os campos obrigatorios estao preenchidos
if ($tudook == 0){
	echo "Favor verificar o seguinte campo!<br /><br />".$msg_erro;
	echo '<br /><br /><a href="#" onclick="javascript: history.go(-1);">Voltar</a>';		
}
else
{
	if (substr($cdprof,0,4) == "Auto")
	{
		require("../conecta.php");
		//gera um novo codigo
		$qry = mysqli_query($db,"SELECT MAX(cdprof) FROM tbprofissional") 
				or die(TrataErro(mysqli_errno(),'','../index.php?p=frm_cadespec','regn_profissional:gerar novo codigo'));
				
		$cdprof = mysqli_result($qry,0) + 1;
		$usrinc =  (int)$_SESSION["CdUsuario"];
		$dtinc = date("Y-m-d")." ".date("H:i:s");

		$caminho = "";

		if(isset($_FILES["assin"]) && $_FILES["assin"]["error"] == 0)
		{
			$arquivo_tmp = $_FILES["assin"]["tmp_name"];
    		$nome = $_FILES["assin"]["name"];
    		$extensao = strtolower(pathinfo($nome, PATHINFO_EXTENSION));
    		$caminho = "img/assinaturas/p_".$cdprof.".".$extensao;
    		move_uploaded_file($arquivo_tmp,"../".$caminho);
		}

		$sql = "INSERT INTO tbprofissional (cdprof,nmprof,cnsprof,crm,usrinc,dtinc,AssinaturaScan,credenciado) VALUES('$cdprof','$nmprof','$cnsprof','$crm','$usrinc','$dtinc', '$caminho', '&credenciado')";	
		$qry = mysqli_query($db,$sql) 
				or die (TrataErro(mysqli_errno(),'Especialidade','../index.php?p=frm_cadespec','regn_profissional:insert especialidade'));
	
		$sql = "INSERT INTO tbprofconselho (cdprof,cdconselho) VALUES('$cdprof','$cdconselho')";	
		$qry = mysqli_query($db,$sql);

			echo '<script language="JavaScript" type="text/javascript"> 
					
					var agree=confirm("Cadastro realizado com sucesso! \nGostaria de cadastrar outro profissional?");
					if (!agree)
						window.location.href="../index.php?i=69";
					else	
						window.location.href="../index.php?i=69&s=n";
					
					
				  </script>';
					
			//echo "Cadastro realizado com sucesso!";		
	}
	else
	{
		$cdprof = (int)$cdprof;
		if($acao == "edit")
		{
			$usralt =  (int)$_SESSION["CdUsuario"];
			$dtalt = date("Y-m-d")." ".date("H:i:s");

			$caminho_q = "";
			if(isset($_FILES["assin"]) && $_FILES["assin"]["error"] == 0)
			{
				$arquivo_tmp = $_FILES["assin"]["tmp_name"];
	    		$nome = $_FILES["assin"]["name"];
	    		$extensao = strtolower(pathinfo($nome, PATHINFO_EXTENSION));
	    		$caminho = "img/assinaturas/p_".$cdprof.".".$extensao;
	    		move_uploaded_file($arquivo_tmp,"../".$caminho);
	    		$caminho = " , AssinaturaScan = '$caminho' ";
			}

			//alterar
			$sql = "UPDATE tbprofissional
						SET nmprof = '$nmprof',
						    cnsprof = '$cnsprof',
						    crm = '$crm',
							usralt = '$usralt',
							credenciado = '$credenciado',
							dtalt = '$dtalt'
							$caminho
						WHERE cdprof=$cdprof";
			
			require("../conecta.php");
			
			$qry = mysqli_query($db,$sql) 
					or die (TrataErro(mysqli_errno(),'Especialidade','../index.php?p=lista_espec','regn_profissional:update especialidade'));

			$sql = "UPDATE tbprofconselho
						SET cdconselho = '$cdconselho'
						WHERE cdprof=$cdprof";
			$qry = mysqli_query($db,$sql) 
					or die (TrataErro(mysqli_errno(),'Cd Conselho','../index.php?p=lista_espec','regn_profissional:update especialidade'));
					
			echo '<script language="JavaScript" type="text/javascript"> 
				alert("Dados alterados com sucesso!");
				window.location.href="../index.php?i=69&s=n&id='.$cdprof.'&acao=edit";				
			  </script>';
		}
		else
		{
			if($acao == "del")
			{
				//excluir	
				require("../conecta.php");
				//verifica se existe algum especialidade vinculado ao fornecedor
				
					//$sql = "DELETE FROM tbprofissional WHERE cdprof=$cdprof";
					$usrdel = (int)$_SESSION["CdUsuario"];
					$dtdel = date("Y-m-d")." ".date("H:i:s");
					$sql = "UPDATE tbprofissional
						SET usrdel = '$usrdel',
							status = 'i',
							dtdel = '$dtdel'
						WHERE cdprof=$cdprof";						
					
					$qry = mysqli_query($db,$sql);
					
					if($qry)
					{
					echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
						<script language=\"JavaScript\" type=\"text/javascript\"> 
							alert(\"Profissional excluído com sucesso!\");
							window.location.href=\"../index.php?i=69&s=l\";				
			 			 </script>";
					}
					else
					{
					echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
						<script language=\"JavaScript\" type=\"text/javascript\"> 
							alert(\"Erro ao tentar excluir o profissional selecionado!\");
							window.location.href=\"../index.php?i=69&s=l\";				
			 			 </script>";
					}
				
				
			}
			
		}
	}
	@mysqli_close();
	@mysqli_free_result($qry);
}
?>