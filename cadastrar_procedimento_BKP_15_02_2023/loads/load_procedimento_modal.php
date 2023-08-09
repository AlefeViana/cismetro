<?php

require_once("../funcoes.php");

if(!$userId){
	die('unauthorized');
}

if (isset($_GET["idcombo"]) && isset($_GET["status"])) {
	$idcombo = $_GET["idcombo"] ?? null;
	$status = $_GET["status"] ?? null;

	$status_condition = "";

	switch ($status) {

		case 1:
			$status_condition .=	" WHERE (sc.Status='E' AND ac. STATUS IS NULL) "; //Fila de Espera novo
			$colspan = "10";
			break;
			/* case 1: $status_condition .=	" WHERE ( sc. STATUS = '1' 	AND ac. STATUS IS NULL )"; // aguardando
					$colspan = "10";
			break; */
		case 2:
			$status_condition .=	" WHERE sc.Status='1' AND ac.Status='1'"; // marcado
			$colspan = "14";
			break;
		case 3:
			$status_condition .=	" WHERE sc.Status='1' AND ac.Status='2'"; // realizado
			$colspan = "12";
			break;
		case 4:
			$status_condition .=	" WHERE ((sc.Status='2') or (sc.Status='2' and ac.Status=1)) "; // cancelad
			$colspan = "10";
			break;
		case 6:
			$status_condition = " WHERE tbtriagem.Status='T'"; // 
			$colspan = "9";
			break;
		case 5:
			$status_condition .=	" WHERE (sc.Status='1' OR sc.Status='2' OR ac.Status='1' OR ac.Status='2')"; // todos
			$colspan = "9";
			break;
		case 8:
			$status_condition .=	" WHERE sc.Status='F'  "; // FALTA
			$colspan = "10";
			break;

		case 9:
			$status_condition .=	" WHERE (sc.Status='E' AND ac. STATUS IS NULL) AND sc.TpAgen !='con'   "; // Em Espera
			$colspan = "11";
			break;

		case 10:
			$status_condition .=	" WHERE (sc.Status='E' AND ac. STATUS IS NULL) AND sc.TpAgen ='con'   "; // Emcaminhado
			$colspan = "11";
			break;

		default:
			$status_condition .=	" WHERE (sc.Status='1' OR sc.Status='2' OR ac.Status='1' OR ac.Status='2')"; // todos
			$colspan = "9";
			break;
	}

	$sql = "SELECT NULL as idcombo, sc.CdSolCons,DtAgCons,HoraAgCons,p.CdPaciente,sc.remarcado, p.NmPaciente,ac.Valor,p.DtNasc,
			ac.CdUsuario, u.Login,ac.CdForn,pr.NmCidade,sc.Protocolo,sc.DtInc,pr.CdPref,ep.CdEspecProc,	NmEspecProc,f.NmForn,Obs1,
			sc.Status,ac.Status as StatusAg,Urgente,NmReduzido,sc.Obs, Pa.NmProcedimento ,sc.impresso, tbtriagem.Status as StatusT, ac.aceite
			FROM tbsolcons sc 
			INNER JOIN tbpaciente p ON sc.CdPaciente=p.CdPaciente 
			INNER JOIN tbbairro b ON b.CdBairro=p.CdBairro
			INNER JOIN tbprefeitura pr ON b.CdPref=pr.CdPref
			INNER JOIN tbespecproc ep ON sc.CdEspecProc=ep.CdEspecProc 
			INNER JOIN tbprocedimento Pa ON ep.CdProcedimento = Pa.CdProcedimento
			LEFT JOIN tbtriagem ON sc.CdSolCons = tbtriagem.CdSolCons
			LEFT JOIN tbagendacons ac ON sc.CdSolCons=ac.CdSolCons
			LEFT JOIN tbfornecedor f ON ac.CdForn=f.CdForn
			LEFT JOIN tbusuario u ON ac.CdUsuario = u.CdUsuario
			$status_condition and sc.idcombo = $idcombo
			ORDER BY sc.dtm DESC,sc.hrm DESC,CdSolCons DESC, NmForn";

	$query = mysqli_query($db, $sql) or die(mysqli_error($db));

	$count = mysqli_num_rows($query);

?>