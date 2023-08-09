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
$CdPaciente 	= $_POST["cd_paciente"];//Cod. do paciente cadastrado na Fila
$CdPref 		= $_SESSION['CdOrigem'];//Cod. do município do paciente
$CdProc     	= $_POST["cd_proc"];//Cod. do Procedimento	
$CdUser     	= (int)$_SESSION["CdUsuario"];//Cod. do usuário que executa a ação
//$Obs        	= $_POST["obs"];//Observações município
$CdEspeccis  	= $_POST["cd_especcis"];//Cod. especificações atendidas pelo consórcio
$CdEspectfd  	= $_POST["cd_espectfd"];//Cod. especificações atendidas pelo tfd
$cdunid 		= $_POST["cd_unid"];//Cod. da unidade do paciente
$data			= $_POST["data"];//Data do pedido médico
$cdfornespera 	= ($_POST["cd_fornecedor"]== NULL)? NULL : $_POST["cd_fornecedor"];//Cod. fornecedor preferencial Fila de Espera
$CdTipo 		= $_POST['CdTipo'];//Tipo de atendimento
$Obscis 		= $_POST["obscis"];//Observações do município atendimento CIS
$Obstfd 		= $_POST["obstfd"];//Observações do município atendimento TFD

$Urgentecis 	=  $_POST["urgentecis"];//Urgencias atendimento CIS
$Urgentetfd 	=  $_POST["urgentetfd"];//Urgencias atendimento TFD
$pactuacao 		=  $_POST["pactuacao"];
$retorno    	=  $_POST["retorno"];
$remarcado  	=  $_POST["remarcado"];

$i 				= $_GET['i'];
//$espec 		= $_POST[cd_especificacao];
/*$cdmed 		=  $_POST["cd_med"];
$dif 		= $_POST["dif"];
$dif 		= explode(".",$dif);
$dif 		= $dif[0]."".$dif[1];
$aux 		= explode(":",$cdmed);
$cdforn 	= $aux[1];
$CdPref 	= $_POST["cd_pref"];
$sit 		= $_POST["sitforn"];
$codforn 	= $_POST["cd_forn"];
$data	 	= $_POST["data"];
$hora	 	= $_POST["hora"];*/






	$query = mysqli_query($db,"SELECT max(idcombo) as idcombo FROM tbsolcons");
	$query = mysqli_fetch_array($query);
	$idcombo = $query['idcombo']+1;
	if(isset($_POST["cd_especcis"])){
		for($n = 0; $n < count($CdEspeccis);$n++) 
		{	
			$qry = mysqli_query($db,"SELECT MAX(CdSolCons) FROM tbsolcons") or die(TrataErro(mysqli_errno(),'','../index.php?p=frm_solagd','regn_cad:gerar novo codigo'));

			$row = mysqli_fetch_array($qry);
			$CdSolCons = ceil($row[0] + 1);


			// $CdSolCons = mysqli_result($qry,0) + 1;
			$Protocolo  = date("Ymd").$CdPaciente."-".$CdSolCons; 
			$dtinc = date('Y-m-d');
			$hrinc = date('H:i:s');
			$userinc = $_SESSION['CdUsuario'];
			$CdUser = $_SESSION['CdUsuario'];
			//DEFINE OS DADOS DA INSERÇÃO FILA DE ESPERA
			$useresp = $_SESSION ['CdUsuario'];
			$dtesp = date('Y-m-d');
			$hresp = date('H:i:s');
			$datasol = FormataDataBD($data);//Transforma a data de solicitação do médico
			$status = 'E';

			$sql = "INSERT INTO tbsolcons (CdSolCons,CdPaciente,CdEspecProc,CdUsuario, Status, Protocolo,Obs1,Urgente, pactuacao,retorno,remarcado,dtinc,userinc,hrinc,CdPref,cdmed, useresp, dtesp, hresp, CdUnid, dtmedsol, cdfornespera, CdTipo,TpAgen,idcombo)
					VALUES ('$CdSolCons','$CdPaciente','$CdEspeccis[$n]','$CdUser','$status','$Protocolo','$Obscis[$n]','$Urgentecis[$n]', '$pactuacao','$retorno','$remarcado','$dtinc','$userinc','$hrinc','$CdPref','$cdmed', '$useresp', '$dtesp', '$hresp', '$cdunid', '$datasol', '$cdfornespera', '$CdTipo','$con','$idcombo')";

			$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','../index.php?p=frm_cad','regn_cad:insert solagd'));
		}	
	}
	
	if(isset($_POST["cd_espectfd"])){
		for($n = 0; $n < count($CdEspectfd);$n++) 
		{	
			$qry = mysqli_query($db,"SELECT MAX(CdSolCons) FROM tbsolcons") or die(TrataErro(mysqli_errno(),'','../index.php?p=frm_solagd','regn_cad:gerar novo codigo'));
			$CdSolCons = mysqli_result($qry,0) + 1;
			$Protocolo  = date("Ymd").$CdPaciente."-".$CdSolCons; 
			$dtinc = date('Y-m-d');
			$hrinc = date('H:i:s');
			$userinc = $_SESSION['CdUsuario'];
			$CdUser = $_SESSION['CdUsuario'];
			//DEFINE OS DADOS DA INSERÇÃO FILA DE ESPERA
			$useresp = $_SESSION ['CdUsuario'];
			$dtesp = date('Y-m-d');
			$hresp = date('H:i:s');
			$datasol = FormataDataBD($data);//Transforma a data de solicitação do médico
			$status = 'E';

			$sql = "INSERT INTO tbsolcons (CdSolCons,CdPaciente,CdEspecProc,CdUsuario, Status, Protocolo,Obs1,Urgente, pactuacao,retorno,remarcado,dtinc,userinc,hrinc,CdPref,cdmed, useresp, dtesp, hresp, CdUnid, dtmedsol, cdfornespera, CdTipo,TpAgen,idcombo)
					VALUES ('$CdSolCons','$CdPaciente','$CdEspec','$CdUser','$status','$Protocolo','$Obstfd[$n]','$Urgentetfd[$n]', '$pactuacao','$retorno','$remarcado','$dtinc','$userinc','$hrinc','$CdPref','$cdmed', '$useresp', '$dtesp', '$hresp', '$cdunid', '$datasol', '$cdfornespera', '$CdTipo','$con','$idcombo')";

			$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','../index.php?p=frm_cad','regn_cad:insert solagd'));

			$sqtfd = "INSERT INTO tbagentfd (CdSolCons, status, cdespectfd) VALUES ($CdSolCons, 'S', $CdEspectfd[$n])";
			$qtfd = mysqli_query($db,$sqtfd);
		}
	}

	if($qry)
	{ 
		echo '<script language="JavaScript" type="text/javascript"> 
				window.location.href="../index.php?i='.$i.'&s=imp&id='.$CdSolCons.'";
			</script>';		

		//echo "Solicita&ccedil;&atilde;o realizada com sucesso!";
	}

?>