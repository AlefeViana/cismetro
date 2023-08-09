<?php



require "../../vendor/autoload.php";
$msg = new \Plasticbrain\FlashMessages\FlashMessages();

define("DIRECT_ACCESS", true);

//verifica se o usuario esta logado no sistema
require_once("verifica.php");
require("../conecta.php");


//verifica se o usuario tem permissão para acessar a pagina
if ((int)$_SESSION["CdTpUsuario"] != 1 && (int)$_SESSION["CdTpUsuario"] != 2)	
{
	$msg->danger("403 - Não autorizado", "../index.php");	
}

if($_GET['deactivate'] && $_GET["id"] ){
    $sql = "SELECT CdForn, NmForn, IE, CNPJ, Telefone, tprecebimento, tpforn, Status
				FROM tbfornecedor WHERE CdForn = ".$_GET["id"]." LIMIT 0,1" ;
	
	$query = mysqli_query($db,$sql);

	$count = mysqli_num_rows($query);
	
	if($count){

		if($_GET['deactivate'] == "true"){
			$status = 0;
			$action = "desativado";
				
		} else {
			$status = 1;
			$action = "ativado";
		}

		$sqldes = "UPDATE tbfornespec SET Status = {$status} WHERE CdForn = $_GET[id]";
		mysqli_query($db,$sqldes) or die($msg->danger("[UPDATE:FORNSPEC]Operação não foi concluída", "../index.php?i=5")	);
		$sqldes = "UPDATE tbfornecedor SET Status = {$status} WHERE CdForn = $_GET[id]";
		mysqli_query($db,$sqldes) or die($msg->danger("[UPDATE:FORNECEDOR]Operação não foi concluída", "../index.php?i=5") );
		$msg->info("Fornecedor #{$_GET['id']} <strong>{$action}</strong> com sucesso!", "../index.php?i=5");		

	}
	else    $msg->error("404 - Registro não encontrado", "../index.php");	

	
	
}

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
		if (isset($_POST["serv_cons"]))
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
		}
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
		$usrdel =  (int)$_SESSION["CdUsuario"];
		$dtdel = date("Y-m-d")." ".date("H:i:s");
		$sql = " UPDATE `tbfornespec` SET `Status` = '0',
													   `usrdel` = '$usrdel',
													   `dtdel` = '$dtdel'
							  WHERE (`CdForn`='$CdForn')";		
		$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','../index.php?p=lista_for','regn_for:limpar servicos, especialidade'));
		
	//	$sql = "DELETE FROM tbfornservicos WHERE CdForn=$CdForn";
	//	$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','../index.php?p=lista_for','regn_for:limpar servicos, especificacao'));
}

//recebe as variaveis do formulario
$CdForn      = $_POST["cd_forn"];
$NmForn 	 = mb_strtoupper($_POST["nm_forn"]);
$NmReduzido	 = mb_strtoupper($_POST["nm_reduzido"]);
$IE    		 = $_POST["ie_forn"];
$CNPJ  		 = str_replace(array(".","/","-"),"",$_POST["cnpj_forn"]);
$cpf  		 = str_replace(array(".","-"),"",$_POST["cpf"]);
$cns  		 = str_replace(array(".","-"),"",$_POST["cns"]);
$CNES  		 = $_POST["cnes_forn"];
$Tel    	 = str_replace(array("(",")","-"),"",$_POST["tel_forn"]);
$Fax    	 = str_replace(array("(",")","-"),"",$_POST["fax_forn"]);
$NmResp 	 = ucwords(mb_strtolower($_POST["nm_resp"]));
$TelResp   	 = str_replace(array("(",")","-"),"",$_POST["tel_resp"]);
$Email 		 = $_POST["email_forn"];
$Logr 		 = ucwords(mb_strtolower($_POST["logr_forn"]));
$Num 		 = $_POST["num_forn"];
$Compl 		 = ucwords(mb_strtolower($_POST["compl_forn"]));
$Bairro		 = ucwords(mb_strtolower($_POST["bairro_forn"]));
$Cep 		 = str_replace("-","",$_POST["cep"]);
$CdCidade	 = $_POST["cid_forn"];
$tpforn 	 = $_POST["tpforn"];
$sit 	 = $_POST["sit"];

//recebe o tipo de acao
$acao       = $_POST["acao"];

//verifica campos obrigatórios
$tudook = 1;
$msg_erro = "";
if ($NmForn == ""){
	$msg_erro .= '<li>Preencha o campo nome do fornecedor</li>';
	$tudook = 0; 
}
if ($NmReduzido == ""){
	$msg_erro .= '<li>Preencha o campo nome do reduzido</li>';
	$tudook = 0; 
}

if ($tudook == 0) $msg->error('<h5>Erros no formulário</h5><ul>'.$msg_erro.'</ul>',"../index.php?i=5&s=n");

else
{
	if (substr($CdForn,0,4) == "Auto")
	{
		
		//gera um novo codigo
		$sql = "SELECT MAX(CdForn) as total FROM tbfornecedor";
		$qry = mysqli_query($db,$sql) 
				or die(TrataErro(mysqli_errno(),'','../index.php?p=lista_for','regn_for:gerar novo codigo'));
		$result = (mysqli_fetch_assoc($qry));
		$CdForn = $result['total']+1;
		
		//inclui fornecedor
		$sql = "INSERT INTO tbfornecedor (CdForn,sit,NmForn,NmReduzido,IE,CNPJ,CNES,cpf,cns,Telefone,Fax,NmResp,TelResp,Email,Logradouro,Numero,Compl,Bairro,CEP,CdCidade,UserInc,tpforn)
		VALUES($CdForn,'$sit','$NmForn','$NmReduzido','$IE','$CNPJ','$CNES','$cpf','$cns','$Tel','$Fax','$NmResp','$TelResp','$Email','$Logr','$Num','$Compl','$Bairro','$Cep',$CdCidade,$_SESSION[CdUsuario],'$tpforn')";	
		$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'CNPJ','../index.php?p=frm_cadfor','regn_for:insert fornecedor'));
		
		//AtribServices($CdForn);

		$msg->success('Fornecedor '.$NmForn.' cadastrado com sucesso!', '../index.php?i=5');
		
		// echo '<script language="JavaScript" type="text/javascript"> 
				
		// 		var agree=confirm("Cadastro realizado com sucesso! \nGostaria de cadastrar outro fornecedor?");
		// 		if (!agree)
		// 			window.location.href="../index.php?i=5";
		// 		else	
		// 			window.location.href="../index.php?i=5&s=n";
				
				
		// 	  </script>';				
	}
	else
	{
		if($acao == "edit")
		{
			//alterar
			$sql = "UPDATE tbfornecedor
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
			
			$msg->info('Fornecedor '.$NmForn.' modificado com sucesso!', '../index.php?i=5');
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
					$sql = "DELETE FROM tbfornecedor WHERE CdForn=$CdForn";	
					$qry = mysqli_query($db,$sql) or die(TrataErro(mysqli_errno(),'','../index.php?p=lista_for','regn_for:delete fornecedor'));
					$msg->info('Fornecedor '.$NmForn.' excluído com sucesso.', '../index.php?i=5');
				}
				else
				{
					$msg->error('Fornecedor '.$NmForn.' não pode ser excluído, devido ele estar associado a um ou mais agendamentos e ou possuir contratos!', '../index.php?i=5');
					echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						 <script language="JavaScript" type="text/javascript"> 
							alert("Fornecedor não pode ser excluído, devido ele estar associado a um ou mais agendamentos e ou possuir contratos!");
							window.location.href="../index.php?i=5";				
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