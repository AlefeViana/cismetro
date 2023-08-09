<?php

function moeda($get_valor) {
                $source = array('.', ','); 
				$replace = array('', '.');
                $valor = str_replace($source, $replace, $get_valor); //remove os pontos e substitui a virgula pelo ponto
                return $valor; //retorna o valor formatado para gravar no banco
        }
		
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
$CdEspecProc       = $_POST["cd_especproc"];
$NmEspecProc       = $_POST["nm_especproc"];
$CdProcedimento    = $_POST["cd_procedimento"];
$Status			   = $_POST["status"];
$desc_sus			   = $_POST["desc_sus"];
$ppi			   = $_POST["ppi"];
$bpa			   = $_POST["bpa"];
$cdespecialidade   = $_POST["cdespecialidade"];
$cdgrupoproc	   = $_POST["cdgrupoproc"];
$nmpreparo		   = $_POST["nmpreparo"];
$cid			   = $_POST["cid"];
$maxano 		   = $_POST["maxano"];
$cdservico		   = $_POST["servico"];
$cdclass		   = $_POST["class"];

$principal		   = $_POST["principal"];
$quemAgendar 		= $_POST["quemAgendar"];

$Valor = $_POST["valor"];
$Valor =  moeda($Valor);	

$valorm = $_POST["valorm"];
$valorm = moeda($valorm);


$valorsus = $_POST["valorsus"];
$valorsus=  moeda($valorsus);	


$cdsus = $_POST["cdsus"];

$acao       = $_POST["acao"];

//verifica campos obrigatórios
$tudook = 1;
if ($NmEspecProc == ""){
	$msg_erro .= 'Preencha o campo Especificação<br />';
	$tudook = 0; 
}

//Controla se os campos obrigatorios estao preenchidos
if ($tudook == 0){
	echo "Favor verificar o seguinte campo!<br /><br />".$msg_erro;
	echo '<br /><br /><a href="#" onclick="javascript: history.go(-1);">Voltar</a>';		
}
else
{
	if (substr($CdEspecProc,0,4) == "Auto")
	{
		require("../conecta.php");
		//gera um novo codigo
		/* $qry = mysqli_query($db,"SELECT MAX(CdEspecProc) FROM tbespecproc") 
			or die(TrataErro(mysqli_errno(),'Especificação','../index.php?p=frm_cadespecproc','regn_especproc:gerar novo codigo'));
			
		$CdEspecProc = mysqli_result($qry,0) + 1; */
		$sql = "INSERT INTO tbespecproc (NmEspecProc,CdProcedimento,UserAlt,Status, valor,valorsus,cdsus, desc_sus, cdespecialidade,ppi,bpa,cdgrupoproc, nmpreparo,cid,cdservico,cdclass,valorm,maxano,principal,quemAgendar)
					VALUES('$NmEspecProc',$CdProcedimento,$_SESSION[CdUsuario],'$Status', '$Valor', '$valorsus', '$cdsus', '$desc_sus','$cdespecialidade', '$ppi','$bpa','$cdgrupoproc', '$nmpreparo','$cid','$cdservico','$cdclass','$valorm','$maxano','$principal','$quemAgendar' )";	
		
		$qry = mysqli_query($db,$sql) 
				or die (TrataErro(mysqli_errno(),'Especificação','../index.php?i=4','regn_especproc:insert especificacao'));
		
			echo '<script language="JavaScript" type="text/javascript"> 
					
					var agree=confirm("Cadastro realizado com sucesso! \nGostaria de cadastrar outro?");
					if (!agree)
						window.location.href="../index.php?i=4";
					else	
						window.location.href="../index.php?i=4&s=n";
					
					
				  </script>';
					
			//echo "Cadastro realizado com sucesso!";
		
	}
	else
	{
		$CdEspecProc = (int)$CdEspecProc;
		if($acao == "edit")
		{
	
			//alterar
			$sql = "UPDATE tbespecproc
						SET NmEspecProc    = '$NmEspecProc',	
							CdProcedimento = $CdProcedimento,
							UserAlt 	   = $_SESSION[CdUsuario],
							Status		   = '$Status',
							valor		   = '$Valor',
							cdsus		   = '$cdsus',
							valorsus		   = '$valorsus',
							desc_sus		   = '$desc_sus',
							ppi		   = '$ppi',
							bpa		   = '$bpa',
							cdgrupoproc		   = '$cdgrupoproc',
							cdespecialidade		   = '$cdespecialidade',
							nmpreparo		   = '$nmpreparo',
							cid 		= '$cid',
							maxano      = '$maxano',
							cdservico = '$cdservico',
							cdclass  = '$cdclass',
							valorm   =  '$valorm',
							principal = '$principal',
							quemAgendar='$quemAgendar',
							DtAlt  		   = NOW()
						WHERE CdEspecProc=$CdEspecProc";
			//echo $sql; die();
			require("../conecta.php");
			$qry = mysqli_query($db,$sql) 
				or die (TrataErro(mysqli_errno(),'Especificação','../index.php?p=lista_especproc','regn_especproc:update especificacao'));
				
			echo '<script language="JavaScript" type="text/javascript"> 
				alert("Dados alterados com sucesso!");
				window.location.href="../index.php?i=4&s=l";				
			  </script>';
		}
		else
		{
			if($acao == "del")
			{
				//excluir	
				require("../conecta.php");
				//verifica se existe algum especificacao vinculado ao fornecedor
			/*	$qry = mysqli_query($db,"SELECT CdEspec FROM tbfornespec WHERE CdEspec=$CdEspecProc") 
						or die (TrataErro(mysqli_errno(),'Erro'));
		*/
					$sql = "UPDATE tbespecproc SET `Status` = 0 WHERE CdEspecProc=$CdEspecProc";	
					$qry = mysqli_query($db,$sql) 
						or die(TrataErro(mysqli_errno(),'Especificação','../index.php?p=lista_especproc','regn_especproc:delete especificacao'));
						
					echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
						<script language=\"JavaScript\" type=\"text/javascript\"> 
							alert(\"Especificação Inativada com sucesso!\");
							window.location.href=\"../index.php?i=4\";				
			 			 </script>";
				
			/*	else
				{
					echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						 <script language="JavaScript" type="text/javascript"> 
							alert("Especificação não pode ser excluída, devido estar associada a um ou mais fornecedores!");
							window.location.href="../index.php?i=4";				
			 			 </script>';
				}
				*/
			}
			
		}
	}
	@mysqli_close();
	@mysqli_free_result($qry);
}
?>