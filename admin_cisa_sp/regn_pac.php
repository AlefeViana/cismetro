<?php
    @session_start();

	require "../../vendor/autoload.php";
	$msg = new \Plasticbrain\FlashMessages\FlashMessages();
	//verifica se o usuario esta logado no sistema
	
	require_once("verifica.php");
	require_once("../conecta.php");
	
	//funcao para tratar erro
	require("function_trata_erro.php");
	
	//funcao para formatar data para formato americano
	function FData($data){
		$val = explode("/",$data);
		return $val[2]."-".$val[1]."-".$val[0];	
	}
	//recebe a variavel caso chamado da solicitacao
	$PagDestino = (int)$_GET["pg"];
	
	//recebe as variaveis do formulario
	$CdPaciente = $_POST["cd_paciente"];
	$Paciente 	= ucwords(mb_strtolower($_POST["nm_paciente"]));
	$RG       	= $_POST["rg_paciente"];
	$orgaorg    = $_POST['orgaorg'];
	$CPF      	= str_replace(array(".","-"),"",$_POST["cpf_paciente"]);
	$OutrosDocs = $_POST["docs_paciente"];
	$Sexo 		= $_POST["sexo_paciente"];
	$DtNasc 	= FData($_POST["dtnasc_paciente"]);
	$Natural    = ucwords(mb_strtolower($_POST["naturalidade"]));
	$Pais	    = ucwords(mb_strtolower($_POST["nacionalidade"]));
	$MaePac 	= ucwords(mb_strtolower($_POST["mae_paciente"]));
	$PaiPac 	= ucwords(mb_strtolower($_POST["pai_paciente"]));
	$Tel 		= str_replace(array("(",")","-"),"",$_POST["tel_paciente"]);
	$Cel 		= str_replace(array("(",")","-"),"",$_POST["cel_paciente"]);
	$Email 		= mb_strtolower($_POST["email_paciente"]);
	$Profissao  = ucwords(mb_strtolower($_POST["profissao"]));
	$Logradouro = ucwords(mb_strtolower($_POST["log_paciente"]));
	$Numero 	= $_POST["num_paciente"];
	$Compl 		= ucwords(mb_strtolower($_POST["compl_paciente"]));
	$CdBairro	= $_POST["bairro"];
	$CEP 		= str_replace("-","",$_POST["cep_paciente"]);
	$Referencia	= $_POST["referencia"];
	$CdUser     = (int)$_SESSION["CdUsuario"];
	$cdupa     = (int)$_POST["und_saude"];
	$csus     = $_POST["csus"];
	$cdlogr     = $_POST["logr"];
	$CertidaoMatricula = $_POST["CertidaoMatricula"];
	$matricula = $_POST["matricula"];
	$numprontuario = $_POST["numprontuario"];



	$sql = " 
	SELECT *
	FROM tbpaciente 
	WHERE NmPaciente = '".$Paciente."'
	AND DtNasc = '".$DtNasc."'
	AND NmMae = '".$MaePac."'";

	$query = mysqli_query($db,$sql) or die ( mysqli_error() );

	$teste =  mysqli_num_rows($query)>0 ?  true  : false;
    
   
	//$teste = vdpaciente($Paciente,$DtNasc,$MaePac);


	
	//recebe o tipo de acao
	$acao  = $_POST["acao"];

    
	if($acao == "cad")
	{
	  if($teste==0)
	{
	
		require("../conecta.php");
		//gera um novo codigo
		/*
		$qry = mysqli_query($db,"SELECT MAX(CdPaciente) FROM tbpaciente") 
				or die(TrataErro(mysqli_errno(),'','../index.php?p=frm_cadpac','regn_pac:gerar novo codigo'));
		$CdPaciente = mysqli_result($qry,0) + 1;
		$sql = "INSERT INTO tbpaciente
		(CdPaciente,CdBairro,NmPaciente,RG,orgaorg,CPF,OutrosDocs,Sexo,DtNasc,Naturalidade,Nacionalidade,NmMae,NmPai,Telefone,Celular,Email,Profissao,Logradouro,Numero,Compl,CEP,Referencia,cdupa,UserInc,csus,cdlogr) 		
		VALUES($CdPaciente,$CdBairro,'$Paciente','$RG','$orgaorg','$CPF','$OutrosDocs','$Sexo','$DtNasc','$Natural','$Pais','$MaePac','$PaiPac','$Tel','$Cel','$Email','$Profissao','$Logradouro','$Numero','$Compl','$CEP','$Referencia','$cdupa','$CdUser','$csus','$cdlogr')";
		*/
	
		$sql = "INSERT INTO tbpaciente
		(CdBairro,NmPaciente,RG,orgaorg,CPF,OutrosDocs,Sexo,DtNasc,Naturalidade,Nacionalidade,NmMae,NmPai,Telefone,Celular,Email,Profissao,Logradouro,Numero,Compl,CEP,Referencia,cdupa,UserInc,csus,cdlogr,CertidaoMatricula, Matricula, Prontuario) 		
		VALUES($CdBairro,'$Paciente','$RG','$orgaorg','$CPF','$OutrosDocs','$Sexo','$DtNasc','$Natural','$Pais','$MaePac','$PaiPac','$Tel','$Cel','$Email','$Profissao','$Logradouro','$Numero','$Compl','$CEP','$Referencia','$cdupa','$CdUser','$csus','$cdlogr','$CertidaoMatricula', '$matricula', '$numprontuario')";
		$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','../index.php?p=frm_cadpac','regn_pac:insert pac'));
		//verifica a pagina de destino
		$msg->success('Paciente cadastrado com sucesso!', '../index.php?i=1');
		
			echo '<script language="JavaScript" type="text/javascript"> 
				alert("Cadastro realizado com sucesso!");
				window.location.href="../index.php?i=1";
			  </script>';
	}
	
	else {
		$msg->error('O Paciente informado já se encontra cadastrado!', '../index.php?i=1');
		echo '<script language="JavaScript" type="text/javascript"> 
					alert("Erro: O Paciente informado já se encontra cadastrado!");
					window.location.href="../index.php?i=1";
				  </script>';
		
	}
	}
	
	else
	{
		if($acao == "edit")
		{
			//alterar
			$sql = "UPDATE tbpaciente
						SET CdBairro   = $CdBairro, 
							NmPaciente = '$Paciente',
						    RG         = '$RG',
							orgaorg = '$orgaorg',
							CPF        = '$CPF',
							OutrosDocs = '$OutrosDocs',
							Sexo       = '$Sexo',
							DtNasc     = '$DtNasc',
							Naturalidade='$Natural',
							Nacionalidade='$Pais',
							NmMae      = '$MaePac',
							NmPai      = '$PaiPac',
							Telefone   = '$Tel',
							Celular    = '$Cel',
							Email      = '$Email',
							Profissao  = '$Profissao',
							Logradouro = '$Logradouro',
							Numero     = '$Numero',
							Compl      = '$Compl',
							CEP        = '$CEP',
							Referencia = '$Referencia',
							DtAlt      = NOW(),
							UserAlt    = '$CdUser',
							csus    = '$csus',
							cdlogr     = '$cdlogr',
							CertidaoMatricula = '$CertidaoMatricula',
							Matricula = '$matricula', 
							Prontuario = '$numprontuario'

						WHERE CdPaciente=$CdPaciente";
			
			require("../conecta.php");
			$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','../index.php?p=lista_pac','regn_pac:update pac'));
			$msg->info('Paciente modificado com sucesso!', '../index.php?i=1');
			echo '<script language="JavaScript" type="text/javascript"> 
				alert("Dados alterados com sucesso!");
				window.location.href="../index.php?i=1";				
			  </script>';
		}
		else
		{
			if($acao == "del")
			{
				//excluir	
				require("../conecta.php");
				$qry = mysqli_query($db,"SELECT CdPaciente FROM tbsolcons WHERE CdPaciente=$CdPaciente")
						or die ("Existe PACIENTE amarrado a outra tabela");
						//or die (TrataErro(mysqli_errno(),'','../index.php?p=lista_pac','regn_pac:vinculo pac solicitacao'));
				if (mysqli_num_rows($qry) == 0){
					
					$sql = "DELETE FROM tbpaciente WHERE CdPaciente=$CdPaciente";	
					$qry = mysqli_query($db,$sql) or die(TrataErro(mysqli_errno(),'','../index.php?p=lista_pac','regn_pac:delete pac'));
					$msg->info('Paciente excluído com sucesso.', '../index.php?i=1');
					echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						 <script language="JavaScript" type="text/javascript"> 
							alert("Paciente excluído com sucesso!");
							window.location.href="../index.php?i=1";				
			 			 </script>';
				}
				else
				{
					$msg->error('Paciente não pode ser excluído, devido ele estar associado a um ou mais agendamentos e ou possuir contratos!', '../index.php?i=1');
					echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						<script language="JavaScript" type="text/javascript"> 
							alert("Paciente não pode ser excluído, devido ele estar associado a uma ou mais consultas!");
							window.location.href="../index.php?i=1";				
			 			 </script>';
				}
			}
			
		}
	}
	@mysqli_close();
	@mysqli_free_result($qry);
//	unset($_SESSION["dados_rec"]);
	unset($_SESSION["form"]);				

?>
