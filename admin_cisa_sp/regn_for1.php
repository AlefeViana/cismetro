<?php
//verifica se o usuario esta logado no sistema
require ("../conecta.php");

$CdForn      = $_POST["cd_forn"];
$NmForn 	 = ucwords(mb_strtolower($_POST["nm_forn"],'ISO-8859-1'));
$NmReduzido	 = ucwords(mb_strtolower($_POST["nm_reduzido"],'ISO-8859-1'));
$IE    		 = $_POST["ie_forn"];
$CNPJ  		 = str_replace(array(".","/","-"),"",$_POST["cnpj_forn"]);
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
//recebe o tipo de acao
$acao       = $_POST["acao"];
;

if($acao=='edit')
{
$sql = mysqli_query($db,"UPDATE tbfornecedor  
	
	SET  NmForn='$NmForn', 
	NmReduzido ='$NmReduzido', 
	 IE ='$IE',
	 CNPJ ='$CNPJ',
	 CNES ='$CNES', 
	 Telefone ='$Tel', 
	 Fax ='$Fax', 
	 NmResp ='$NmResp', 
	 TelResp ='$TelResp',
	 Email ='$Email',
	 Logradouro ='$Logr', 
	 Numero ='564646', 
	 Compl ='complemento',
	 Bairro ='$Bairro', 
	 CEP ='$Cep', 
	 CdCidade ='$CdCidade'
	 WHERE ( CdForn ='$CdForn')") or die (mysqli_error());
 	
	
	//$sql2 = "DELETE FROM tbfornespec WHERE (CdForn='$CdForn') ";
	$usrdel =  (int)$_SESSION["CdUsuario"];
	$dtdel = date("Y-m-d")." ".date("H:i:s");	
	$sql = " UPDATE `tbfornespec` SET `Status` = '0',
													   `usrdel` = '$usrdel',
													   `dtdel` = '$dtdel'
							  WHERE (`CdForn`='$CdForn')";	
	$qry = mysqli_query($db,$sql2) or die (mysqli_error());
		
	
	 		$sql1 = "INSERT INTO tbfornespec (CdForn,CdEspec) VALUES ";
			foreach($_POST["serv_cons"] as $item)
			{
				$sql1 .= "($CdForn,$item),";
			}
			//remove a ultima virgula
			$sql1 = substr($sql1,0,strlen($sql1)-1);
			$sql1 .= ';';
			$qry = mysqli_query($db,$sql1) or die (mysqli_error());
			
			
			echo '<script language="JavaScript" type="text/javascript"> 
				alert("Cadastro realizado com sucesso!");
					window.location.href="../index.php?i=5";
			  </script>';	
}
 
 
 
if($acao=='i')
{
	echo "oi";
$sql = "INSERT INTO tbfornecedor (NmForn,NmReduzido,IE,CNPJ,CNES,Telefone,
Fax,NmResp,TelResp,Email,Logradouro,Numero,Compl,Bairro,CEP,CdCidade,UserInc)							
VALUES('$NmForn','$NmReduzido','$IE','$CNPJ','$CNES','$Tel','$Fax','$NmResp','$TelResp','$Email',
'$Logr','$Num','$Compl','$Bairro','$Cep','$CdCidade','$_SESSION[CdUsuario]')";	
$qry = mysqli_query($db,$sql) or die (mysqli_error());
	
	  $sql1 = "INSERT INTO tbfornespec (CdForn,CdEspec) VALUES ";
			foreach($_POST["serv_cons"] as $item)
			{
				$sql1 .= "($CdForn,$item),";
			}
			//remove a ultima virgula
			$sql1 = substr($sql1,0,strlen($sql1)-1);
			$sql1 .= ';';
			$qry1 = mysqli_query($db,$sql1) or die (mysqli_error());
			
			
			echo '<script language="JavaScript" type="text/javascript"> 
				alert("Cadastro realizado com sucesso!");
					window.location.href="../index.php?i=5";
			  </script>';
}
 
 
 
 /*
 
 
 if($acao == "del")
			{
				//excluir	
				require("../conecta.php");
				//verifica se existe algum bairro vinculado ao paciente
				$qry = mysqli_query($db,"SELECT CdSolCons FROM tbagendacons WHERE CdForn=$CdForn") or die (TrataErro(mysqli_errno(),'','../index.php?p=lista_for','regn_for:consultar forn vinculado ao agendamento'));
				$qry1 = mysqli_query($db,"SELECT CdContrato FROM tbcontrato WHERE CdForn=$CdForn") or die (TrataErro(mysqli_errno(),'','../index.php?p=lista_for','regn_for:consultar forn vinculado ao contrato'));
				
				if (mysqli_num_rows($qry) == 0 && mysqli_num_rows($qry1) == 0){
					
					
						$k = "DELETE FROM tbfornespec WHERE (CdForn='$CdForn') ";
						$qry = mysqli_query($db,$k) or die (mysqli_error());
					
				
					
					$sql = "DELETE FROM tbfornecedor WHERE CdForn=$CdForn";	
					$qry = mysqli_query($db,$sql) or die(TrataErro(mysqli_errno(),'','../index.php?p=lista_for','regn_for:delete fornecedor'));
					echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
						<script language=\"JavaScript\" type=\"text/javascript\"> 
							alert(\"Fornecedor excluído com sucesso!\");
							window.location.href=\"../index.php?i=5\";				
			 			 </script>";
				}
				else
				{
					echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						 <script language="JavaScript" type="text/javascript"> 
							alert("Fornecedor não pode ser excluído, devido ele estar associado a um ou mais agendamentos e ou possuir contratos!");
							window.location.href="../index.php?i=5";				
			 			 </script>';
				}
			}
 
 
 */
 