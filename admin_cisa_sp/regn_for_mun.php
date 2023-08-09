<?php
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
$tpforn 	 = $_POST['tpforn'];
$cdmun		 = $_POST['cdmun'];
$cdregional		 = $_POST['cdregional'];

//recebe o tipo de acao
$acao       = $_POST["acao"];

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

		$sql = "INSERT INTO tbfornecedor_mun (NmForn,NmReduzido,IE,CNPJ,CNES,cpf,cns,Telefone,Fax,NmResp,TelResp,Email,Logradouro,Numero,Compl,Bairro,CEP,CdCidade,UserInc,tpforn,cdmun)	VALUES('$NmForn','$NmReduzido','$IE','$CNPJ','$CNES','$cpf','$cns','$Tel','$Fax','$NmResp','$TelResp','$Email','$Logr','$Num','$Compl','$Bairro','$Cep',$CdCidade,$_SESSION[CdUsuario],'$tpforn','$_SESSION[CdOrigem]')";	
		$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'CNPJ','../index.php?p=frm_cadfor','regn_for:insert fornecedor'));
		
		//AtribServices($CdForn);
		
		echo '<script language="JavaScript" type="text/javascript"> 
				
				var agree=confirm("Cadastro realizado com sucesso! \nGostaria de cadastrar outro fornecedor?");
				if (!agree)
					window.location.href="../index.php?i='.$_GET[i].'";
				else	
					window.location.href="../index.php?i='.$_GET[i].'&s=n";
				
				
			  </script>';			
	}
	else
	{
		if($acao == "edit")
		{
			//alterar
			$sql = "UPDATE tbfornecedor_mun
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
							tpforn 		= '$tpforn'
						WHERE CdForn='$CdForn' AND cdmun = '$_SESSION[CdOrigem]'";
			//echo $sql;
			
			require("../conecta.php");
			$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'CNPJ','../index.php?p=lista_for','regn_for:update fornecedor'));
			//LimpaServices($CdForn);
			//AtribServices($CdForn);
			
			echo '<script language="JavaScript" type="text/javascript"> 
				alert("Dados alterados com sucesso!");
				window.location.href="../index.php?i='.$_GET[i].'";				
			  </script>';
		}
		else
		{
			
			
		}
	}
	@mysqli_close();
	@mysqli_free_result($qry);
	@mysqli_free_result($qry1);
}
?>