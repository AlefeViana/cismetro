<?php 
//verifica se o usuario esta logado no sistema
require_once("../verifica.php");
require_once("../funcoes.php");

//funcao para tratar erro
require("function_trata_erro.php");

//verifica se o usuario tem permissão para acessar a pagina
if ((int)$_SESSION["CdTpUsuario"] != 1 && (int)$_SESSION["CdTpUsuario"] != 2 && (int)$_SESSION["CdTpUsuario"] != 3 && (int)$_SESSION["CdTpUsuario"] != 4 && (int)$_SESSION["CdTpUsuario"] != 5)	
{
	echo '<script language="JavaScript" type="text/javascript"> 
		window.location.href="../index.php?p=inicial";				
	  </script>';	
}

//recebe as variaveis do formulario
$CdPaciente = $_POST["cd_paciente"];
$CdProc     = (int)$_POST["cd_proc"];	
$CdUser     = (int)$_SESSION["CdUsuario"];
$Obs        = $_POST["obs"];
$CdEspecif  = (int)$_POST["cd_especificacao"];
$Urgente    =  $_POST["urgente"];
$pactuacao    =  $_POST["pactuacao"];
$retorno    =  $_POST["retorno"];
$remarcado    =  $_POST["remarcado"];
$CdPref = $_SESSION["CdOrigem"];
$espec = $_POST['cd_especificacao'];
$dif = $_POST["dif"];
$dif = explode(".",$dif);
$dif = $dif[0]."".$dif[1];

//echo $dif;
//echo $CdEspecif;
if($_GET['add'] != "s"){
	$sql = mysqli_query($db,"SELECT bloquear FROM tbprefeitura WHERE tbprefeitura.CdPref= $CdPref");
	$l = mysqli_fetch_array($sql);
	//echo $l[bloq];
  	if ($l['bloquear'] == 1)
	{
			//echo "TESTE DE CONDIÇÃO";
		echo '<script language="JavaScript" type="text/javascript"> 
				alert("Não foi possível solicitar. Para maiores informações entre em contato com o consórcio.(bloq)");
				 window.location.href="../index.php?i=70&s=cons";
			</script>';

	}else{
		set_aguardando1($CdPaciente,$CdProc,$Obs,$CdEspecif,$Urgente,$pactuacao,$retorno,$remarcado,0,$dif);
		
	}
}else{
		// AGUARDANDO      
		$qry = mysqli_query($db,"SELECT MAX(CdSolCons) FROM tbsolcons") or die(TrataErro(mysqli_errno(),'','../index.php?p=frm_solagd','regn_solagd:gerar novo codigo'));
					
		$CdSolCons = mysqli_result($qry,0) + 1;
	    //echo $CdSolCons;
		$Protocolo  = date("Ymd").$CdPaciente."-".$CdSolCons; 
		$dtinc = date('Y-m-d');
		$hrinc = date('H:i:s');
		$userinc = $_SESSION['CdUsuario'];
		$CdUser = $_SESSION['CdUsuario'];
			
		// DESCOBRE A CIDADE DO PACIENTE 
		$sql_cidade_pac = mysqli_query($db,"SELECT tbprefeitura.CdPref, tbprefeitura.NmCidade 
		FROM 
		tbpaciente, tbprefeitura, tbbairro
		WHERE tbpaciente.CdBairro = tbbairro.CdBairro
		AND tbbairro.CdPref = tbprefeitura.CdPref
		AND tbpaciente.CdPaciente = '$CdPaciente'
		");
		$lpac = mysqli_fetch_array($sql_cidade_pac);
		$CdPref = $lpac["CdPref"];
		//echo $CdPref;
		
		// INSERE SOLICITAÇÃO
		$sql = "INSERT INTO tbsolcons (CdSolCons,CdPaciente,CdEspecProc,CdUsuario,Protocolo,Obs1,Urgente, pactuacao,retorno,remarcado,dtinc,userinc,hrinc,CdPref,extra) 
					VALUES ($CdSolCons,$CdPaciente,$CdEspecif,$CdUser,'$Protocolo','$Obs','$Urgente', '$pactuacao','$retorno','$remarcado','$dtinc','$userinc','$hrinc','$CdPref','$extra')";
		
		//Verifica se o saldo disponível é maior que o valor do procedimento
		$sql_proc = mysqli_query($db,"SELECT es.valor FROM tbespecproc AS es WHERE es.CdEspecProc = ".$CdEspecif) or die(mysqli_error());
		$proc = mysqli_fetch_array($sql_proc);
		

		$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','../index.php?p=frm_solagd','regn_solagd:insert solagd'));
		
		if($qry)
		{
			echo '<script language="JavaScript" type="text/javascript"> 
						alert("Solicitação realizada com sucesso!");
						window.location.href="../index.php?i=70";
						//var agree=confirm("Solicitação realizada com sucesso! Gostaria de fazer outra solicitação?");
						//if (agree) window.location.href="../index.php?i=70&s=cons";
						//else window.location.href="../index.php?i=70";
				</script>';						
		} else {
			echo '<script language="JavaScript" type="text/javascript"> 
						alert("Houve algum erro ao solicitar o procedimento!");
						window.location.href="../index.php?i=70";
				</script>';				
			}
}

?>