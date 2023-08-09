<?php 

define("DIRECT_ACCESS", true);

//verifica se o usuario esta logado no sistema
require_once("../verifica.php");
require_once("../funcoes.php");
require_once("../conecta.php");

//funcao para tratar erro
require("function_trata_erro.php");

//verifica se o usuario tem permissão para acessar a pagina
if ((int)$_SESSION["CdTpUsuario"] != 1 && (int)$_SESSION["CdTpUsuario"] != 2 && (int)$_SESSION["CdTpUsuario"] != 3 && (int)$_SESSION["CdTpUsuario"] != 4)	
{
	echo '<script language="JavaScript" type="text/javascript"> 
		window.location.href="index.php?p=inicial";				
	  </script>';	
}

//recebe as variaveis do formulario
$CdPaciente = $_POST["cd_paciente"];
$CdProc     = (int)$_POST["cd_proc"];	
$CdUser     = (int)$_SESSION["CdUsuario"];
$Obs        = $_POST["obs"];
$CdEspecif  = (int)$_POST["cd_especificacao"];
$Urgente    =  $_POST["urgente"];
$pactuacao  =  $_POST["pactuacao"];
$retorno    =  $_POST["retorno"];
$remarcado  =  $_POST["remarcado"];
$cdmed 		=  $_POST["cd_med"];



$sqlp = "SELECT tbespecproc.CdEspecProc,tbespecproc.maxano FROM tbespecproc WHERE tbespecproc.CdEspecProc = '$CdEspecif'";
//echo $sqlp."<br />";
$sqlp = mysqli_query($db,$sqlp) or die("Erro 452: regn_solagd.php");
$lp = mysqli_fetch_array($sqlp);
if($lp['maxano'] > 0){
	#Busca por agendas realizadas no período pelo paciente
	$sql = "SELECT count(ac.CdSolCons) as qts
			FROM tbagendacons AS ac
			INNER JOIN tbsolcons AS sc ON sc.CdSolCons = ac.CdSolCons
			WHERE ((sc.Status = '1' AND ac.Status = '2') OR (sc.Status = '1' AND ac.Status = '1') OR sc.Status = 'F')
			AND sc.CdPaciente = $CdPaciente AND ac.DtAgCons BETWEEN '".date("Y")."-01-01' AND '".date("Y")."-12-31'";
	//echo $sql."<br />";		
	$sql = mysqli_query($db,$sql) or die("Erro 453: regn_solagd.php");
	$l = mysqli_fetch_array($sql);
	
		if($l['qts'] < $lp['maxano']){	
			set_aguardando($CdPaciente,$CdProc,$Obs,$CdEspecif,$Urgente,$pactuacao,$retorno,$remarcado,$cdmed);
		}else{
				echo '<script language="JavaScript" type="text/javascript"> 
					  alert("Não foi possível agendar! Paciente já realizou o máximo de atendimentos permitidos no ano!");
					  window.location.href=\'../index.php?i=29\';			
			  </script>';
		}
} else {
	set_aguardando($CdPaciente,$CdProc,$Obs,$CdEspecif,$Urgente,$pactuacao,$retorno,$remarcado,$cdmed);
}


@mysqli_close();

?>