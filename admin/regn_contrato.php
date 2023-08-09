<?php

define("DIRECT_ACCESS", true);

//verifica se o usuario esta logado no sistema
require_once("verifica.php");

//funcao para tratar erro
require("function_trata_erro.php");

//funcao para formatar data para formato americano
function FData($data){
	$val = explode("/",$data);
	return $val[2]."-".$val[1]."-".$val[0];	
}

//funcao para adicionar arquivo
function AddArquivo($CdContrato,$CdForn){
    // Configurações
    $extensoes = array(".doc", ".pdf", ".docx", ".jpg", ".xls", ".xlsx", ".rar", ".zip",".txt",".gif",".png");
    $caminho = "contratos/";

    // Recuperando informações do arquivo
    $nome = $_FILES['arquivo']['name'];
    $temp = $_FILES['arquivo']['tmp_name'];
    // Verifica se a extensão é permitida
    if (!in_array(strtolower(strrchr($nome, ".")), $extensoes)) {
		$msg = 'Extensão do arquivo inválida';
	}
	
    // Se não houver erro
    if (!$msg) {
        //conecta banco
		require('../conecta.php');
		$qry = mysqli_query($db,"SELECT NmReduzido FROM tbfornecedor WHERE CdForn=$CdForn") 
					or die(TrataErro(mysqli_errno(),'','../index.php?p=lista_for','regn_contrato:get forn'));
		if(mysqli_num_rows($qry) > 0){
			$Dados = mysqli_fetch_array($qry);
			$ext = strtolower(strrchr($nome, "."));
        	$nome = strtolower(str_replace(' ','',$Dados["NmReduzido"])).$CdContrato.$ext;
		}
        // Movendo arquivo para servidor
		//chmod("contratos/",0777);
        if (!move_uploaded_file($temp, $caminho . $nome)){
            $msg = 'Não foi possível anexar o arquivo';
		}else{
			$qry = mysqli_query($db,"UPDATE tbcontrato SET Arquivo='$nome' WHERE CdContrato=$CdContrato");
			$msg = 'ok';
		}
    }
	return $msg;
}

//recebe as variaveis do formulario
$CdContrato = $_REQUEST["cd_contrato"];
$Descricao  = $_POST["descricao"];
$DtValidade = FData($_POST["dtval"]);
$DtValidadef = FData($_POST["dtvalf"]);
$Arquivo    = $_FILES["arquivo"];
$CdForn     = $_REQUEST["cd_forn"];
$valor		= $_POST["valor"];
$cdgrupoproc = $_POST["cdgrupoproc"];

//recebe o tipo de acao
$acao       = $_POST["acao"];

//verifica campos obrigatórios
$tudook = 1;
if ($Descricao == ""){
	$msg_erro .= 'Preencha o campo Descri&ccedil;&atilde;o<br />';
	$tudook = 0; 
}
if ((int)$CdForn == 0){
	$msg_erro .= 'Selecione um fornecedor<br />';
	$tudook = 0; 
}
if (!checkdate((int)substr($DtValidade,5,2),(int)substr($DtValidade,8,2),(int)substr($DtValidade,0,4))){
	$msg_erro .= 'Preencha o campo Validade do Contrato com uma data v&aacute;lida<br />';
	$tudook = 0; 
}

if ((int)substr($DtValidade,0,4).(int)substr($DtValidade,5,2).(int)substr($DtValidade,8,2) < $data_atual ){
	$msg_erro .= 'Validade do Contrato deve ser maior ou igual a data atual<br />';
	$tudook = 0; 
}

//Controla se os campos obrigatorios estao preenchidos
if ($tudook == 0){
	echo "Favor verificar o seguinte campo!<br /><br />".$msg_erro;
	echo '<br /><br /><a href="#" onclick="javascript: history.go(-1);">Voltar</a>';		
}
else
{
	if (substr($CdContrato,0,4) == "Auto")
	{
			require("../conecta.php");
			
			//gera um novo codigo
			$qry = mysqli_query($db,"SELECT MAX(CdContrato) FROM tbcontrato") 
					or die(TrataErro(mysqli_errno(),'','../index.php?p=lista_for','regn_contrato:gerar novo codigo'));
			$CdContrato = mysqli_result($qry,0) + 1;
									
			$sql = "INSERT INTO tbcontrato (CdContrato,CdForn,Descricao,DtValidade,DtValidadef,UserAlt,valor,cdgrupoproc)	
						VALUES($CdContrato,$CdForn,'$Descricao','$DtValidade','$DtValidadef',$_SESSION[CdUsuario],'$valor',$cdgrupoproc)";	
			$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','../index.php?p=lista_for','regn_contrato:insert contrato'));
			
			if ($_FILES["arquivo"]["name"] != ""){
					$msgArq = AddArquivo($CdContrato,$CdForn);
					if($msgArq != 'ok'){				
							echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
							echo '<script language="JavaScript" type="text/javascript"> 
									alert("'.$msgArq.'");			
								  </script>';
					}			
			}
						
			//verifica a pagina de destino
			if ($PagDestino == 1){
				echo '<script language="JavaScript" type="text/javascript"> 
					alert("Cadastro realizado com sucesso!");
					window.location.href="../index.php?p=i=5&s=c";
				  </script>';
			}
			else
			{
				echo '<script language="JavaScript" type="text/javascript"> 
						
						var agree=confirm("Cadastro realizado com sucesso! \nGostaria de cadastrar outro contrato?");
						if (!agree)
							window.location.href="../index.php?i=5&s=pgforn&cdforn='.$CdForn.'&f=contratos";
						else	
							window.location.href="../index.php?i=5&s=pgforn&cdforn='.$CdForn.'&f=contratos";
						
						
					  </script>';
						
				//echo "Cadastro realizado com sucesso!";
			}

	}
	else
	{
		if($acao == "edit")
		{
			//alterar
			$sql = "UPDATE tbcontrato
						SET Descricao = '$Descricao',
						    CdForn    = $CdForn,
							UserAlt   = $_SESSION[CdUsuario],
							DtValidade= '$DtValidade',
							DtValidadef= '$DtValidadef',
							cdgrupoproc= '$cdgrupoproc',
							valor =       '$valor',
							DtAlt    = NOW()
						WHERE CdContrato=$CdContrato";
			
			require("../conecta.php");			
			$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','../index.php?p=lista_for','regn_contrato:update contrato'));
		
			if ($_FILES["arquivo"]["name"] != ""){
					$msgArq = AddArquivo($CdContrato,$CdForn);
					if($msgArq != 'ok'){				
							echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
							echo '<script language="JavaScript" type="text/javascript"> 
									alert("'.$msgArq.'");			
								  </script>';
					}			
			}
			
			echo '<script language="JavaScript" type="text/javascript"> 
				alert("Dados alterados com sucesso!");
				window.location.href="../index.php?i=5&s=pgforn&cdforn='.$CdForn.'&f=contratos";				
			  </script>';	
		}
		else
		{
			if($acao == "del")
			{
				/*excluir	
				require("../conecta.php");
				//verifica se existe algum bairro vinculado ao paciente
				$qry = mysqli_query($db,"SELECT CdPaciente FROM tbpaciente WHERE CdBairro=$CdBairro") 
						or die (TrataErro(mysqli_errno(),'','../index.php?p=lista_bairro','regn_bairro:vinculo bairro paciente'));
				
				if (mysqli_num_rows($qry) == 0){
					
					$sql = "DELETE FROM tbbairro WHERE CdBairro=$CdBairro";	
					$qry = mysqli_query($db,$sql) or die(TrataErro(mysqli_errno(),'','../index.php?p=lista_bairro','regn_bairro:delete bairro'));
					
					echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
						<script language=\"JavaScript\" type=\"text/javascript\"> 
							alert(\"Bairro excluído com sucesso!\");
							window.location.href=\"../index.php?p=lista_bairro\";				
			 			 </script>";
				}
				else
				{
					echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						 <script language="JavaScript" type="text/javascript"> 
							alert("Bairro não pode ser excluído, devido ele estar associado a um ou mais pacientes!");
							window.location.href="../index.php?p=lista_bairro";				
			 			 </script>';
				}*/
			}
			
		}
	}
	@mysqli_close();
	@mysqli_free_result($qry);
}
?>