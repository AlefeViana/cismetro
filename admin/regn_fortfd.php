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

//funcao para validar data
function ValidaData($dat){
	$data = explode("-","$dat"); // fatia a string $dat em pedados, usando / como referência
	$d = $data[2];
	$m = $data[1];
	$y = $data[0];

	// verifica se a data é válida!
	// 1 = true (válida)
	// 0 = false (inválida)
	$res = checkdate($m,$d,$y);
	return $res;
}

//funcao atribui servicos ao fornecedor
function AtribServices($CdForn){
		require("../conecta.php");
		
		//atribui consultas ao fornecedor---------------------------------------------------------------------------
		unset($sql);
		/*if (isset($_POST["serv_cons"]))
		{
			$sql = "INSERT INTO tbfornespec (CdForn,CdEspec) VALUES ";
			foreach($_POST["serv_cons"] as $item)
			{
				$sql .= "($CdForn,$item),";
			}
			//remove a ultima virgula
			$sql = substr($sql,0,strlen($sql)-1);
			$sql .= ';';
			$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'CdEspec','../index.php?p=lista_for','regn_for:incluir os servicos consulta'));
		}*/
		//----------------------------------------------------------------------------------------------------------
		
		//atribui exames e procedimentos cirurgicos ao fornecedor---------------------------------------------------
		unset($sql);
	/*	if (isset($_POST["forn_service"]))
		{
			$sql = "INSERT INTO tbfornservicos(CdForn,CdEspecProc,UserAlt) VALUES ";
			foreach($_POST["forn_service"] as $item)
			{
				$sql .= "($CdForn,$item,$_SESSION[CdUsuario]),";
			}
			//remove a ultima virgula
			$sql = substr($sql,0,strlen($sql)-1);
			$sql .= ';';
			//echo $sql.'<br>';
			$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'CdEspecProc','../index.php?p=lista_for','regn_for:incluir os servicos exames e procedimentos cirurgicos'));	
		}
		//-------------------------------------------------------------------------------------------------------------*/
}

//funcao limpa servicos do fornecedor
function LimpaServices($CdForn){
		require("../conecta.php");
		
		//$sql = "DELETE FROM tbfornespec WHERE CdForn=$CdForn";
		/*$usrdel =  (int)$_SESSION["CdUsuario"];
		$dtdel = date("Y-m-d")." ".date("H:i:s");
		$sql = " UPDATE `tbfornespec` SET `Status` = '0',
													   `usrdel` = '$usrdel',
													   `dtdel` = '$dtdel'
							  WHERE (`CdForn`='$CdForn')";		
		$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','../index.php?p=lista_for','regn_for:limpar servicos, especialidade'));*/
		
	//	$sql = "DELETE FROM tbfornservicos WHERE CdForn=$CdForn";
	//	$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','../index.php?p=lista_for','regn_for:limpar servicos, especificacao'));
}

//recebe as variaveis do formulario
$CdForn      = $_POST["cd_forn"];
$NmForn 	 = mb_strtoupper($_POST["nm_forn"],'ISO-8859-1');
$NmReduzido	 = mb_strtoupper($_POST["nm_reduzido"],'ISO-8859-1');
$IE    		 = $_POST["ie_forn"];
$CNPJ  		 = str_replace(array(".","/","-"),"",$_POST["cnpj_forn"]);
$cpf  		 = str_replace(array(".","-"),"",$_POST["cpf"]);
$cns  		 = str_replace(array(".","-"),"",$_POST["cns"]);
$CNES  		 = $_POST["cnes_forn"];
$Tel    	 = str_replace(array("(",")","-"),"",$_POST["tel_forn"]);
$Fax    	 = str_replace(array("(",")","-"),"",$_POST["fax_forn"]);
$NmResp 	 = ucwords(mb_strtolower($_POST["nm_resp"],'ISO-8859-1'));
$TelResp   	 = str_replace(array("(",")","-"),"",$_POST["tel_resp"]);
$Email 		 = $_POST["email_forn"];
$Logr 		 = ucwords(mb_strtolower($_POST["logr_forn"],'ISO-8859-1'));
$Num 		 = $_POST["num_forn"];
$Compl 		 = ucwords(mb_strtolower($_POST["compl_forn"],'ISO-8859-1'));
$Bairro		 = ucwords(mb_strtolower($_POST["bairro_forn"],'ISO-8859-1'));
$Cep 		 = str_replace("-","",$_POST["cep"]);
$CdCidade	 = $_POST["cid_forn"];
$tpforn 	 = $_POST[tpforn];
$sit 		 = $_POST[sit];

//recebe o tipo de acao
$acao       = $_POST["acao"];

$i 			= $_GET["i"];

//verifica campos obrigatórios
$tudook = 1;
if ($NmForn == ""){
	$msg_erro .= 'Preencha o campo nome do fornecedor<br />';
	$tudook = 0; 
}
if ($NmReduzido == ""){
	$msg_erro .= 'Preencha o campo nome do reduzido<br />';
	$tudook = 0; 
}
/* if ($CNPJ == ""){
	$msg_erro .= 'Preencha o campo CNPJ<br />';
	$tudook = 0; 
} */

//Controla se os campos obrigatorios estao preenchidos
if ($tudook == 0){
	echo "Favor verificar o seguinte campo!<br /><br />".$msg_erro;
	echo '<br /><br /><a href="#" onclick="javascript: history.go(-1);">Voltar</a>';		
}
else
{
	if (substr($CdForn,0,4) == "Auto")
	{
		require("../conecta.php");
		//gera um novo codigo
		$qry = mysqli_query($db,"SELECT MAX(CdForn) FROM tbfornecedortfd") 
				or die(TrataErro(mysqli_errno(),'','../index.php?p=lista_for','regn_for:gerar novo codigo'));
		$CdForn = mysqli_result($qry,0) + 1;
		//inclui fornecedor
		$sql = "INSERT INTO tbfornecedortfd (CdForn,NmForn,NmReduzido,IE,CNPJ,CNES,cpf,cns,Telefone,Fax,NmResp,TelResp,Email,Logradouro,Numero,Compl,Bairro,CEP,CdCidade,UserInc,tpforn,sit)			                 VALUES($CdForn,'$NmForn','$NmReduzido','$IE','$CNPJ','$CNES','$cpf','$cns','$Tel','$Fax','$NmResp','$TelResp','$Email','$Logr','$Num','$Compl','$Bairro','$Cep',$CdCidade,$_SESSION[CdUsuario],'$tpforn','$sit')";	
		$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'CNPJ','../index.php?p=frm_cadfor','regn_for:insert fornecedor'));
		
		//AtribServices($CdForn);
		
		echo '<script language="JavaScript" type="text/javascript"> 
				
				var agree=confirm("Cadastro realizado com sucesso! \nGostaria de cadastrar outro fornecedor?");
				if (!agree)
					window.location.href="../index.php?i='.$i.'";
				else	
					window.location.href="../index.php?i='.$i.'&s=n";
				
				
			  </script>';				
	}
	else
	{
		if($acao == "edit")
		{
			//alterar
			$sql = "UPDATE tbfornecedortfd
						SET NmForn   	= '$NmForn',
							NmReduzido  = '$NmReduzido',
						    IE  	 	= '$IE',
							CNPJ  		= '$CNPJ',
							CNES		= '$CNES',							
							cpf		= '$cpf',	
							cns      = '$cns',						
							Telefone 	= '$Tel',
							Fax		 	= '$Fax',
							NmResp      = '$NmResp',
							TelResp     = '$TelResp',
							Email 		= '$Email',	
							Logradouro 	= '$Logr',
							Numero 		= '$Num',
							Compl 		= '$Compl',
							Bairro		= '$Bairro',
							CEP			= '$Cep',
							CdCidade	= $CdCidade,
							DtAlt 		= NOW(),
							UserAlt 	= $_SESSION[CdUsuario],
							tpforn 		= '$tpforn',
							sit			= '$sit'
						WHERE CdForn=$CdForn";
			
			require("../conecta.php");
			$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'CNPJ','../index.php?p=lista_for','regn_for:update fornecedor'));
			//LimpaServices($CdForn);
			//AtribServices($CdForn);
			
			echo '<script language="JavaScript" type="text/javascript"> 
				alert("Dados alterados com sucesso!");
				window.location.href="../index.php?i='.$i.'";				
			  </script>';
		}
		else
		{
			if($acao == "del")
			{
				//excluir	
				require("../conecta.php");
				//verifica se existe algum bairro vinculado ao paciente
				$qry = mysqli_query($db,"SELECT CdSolCons FROM tbagendacons WHERE CdForn=$CdForn") or die (TrataErro(mysqli_errno(),'','../index.php?p=lista_for','regn_for:consultar forn vinculado ao agendamento'));
				$qry1 = mysqli_query($db,"SELECT CdContrato FROM tbcontrato WHERE CdForn=$CdForn") or die (TrataErro(mysqli_errno(),'','../index.php?p=lista_for','regn_for:consultar forn vinculado ao contrato'));
				
				if (mysqli_num_rows($qry) == 0 && mysqli_num_rows($qry1) == 0){
					LimpaServices($CdForn);
					$sql = "DELETE FROM tbfornecedortfd WHERE CdForn=$CdForn";	
					$qry = mysqli_query($db,$sql) or die(TrataErro(mysqli_errno(),'','../index.php?p=lista_for','regn_for:delete fornecedor'));
					echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
						<script language=\"JavaScript\" type=\"text/javascript\"> 
							alert(\"Fornecedor excluído com sucesso!\");
							window.location.href=\"../index.php?i=".$i."\";				
			 			 </script>";
				}
				else
				{
					echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						 <script language="JavaScript" type="text/javascript"> 
							alert("Fornecedor não pode ser excluído, devido ele estar associado a um ou mais agendamentos e ou possuir contratos!");
							window.location.href="../index.php?i='.$i.'";				
			 			 </script>';
				}
			}
			
		}
	}
	@mysqli_close();
	@mysqli_free_result($qry);
	@mysqli_free_result($qry1);
}
?>