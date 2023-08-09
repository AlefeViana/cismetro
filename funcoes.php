<?php
session_start();
require('conecta.php');


function timeDiff($firstTime, $lastTime)
{
	// convert to unix timestamps
	$firstTime = strtotime($firstTime);
	$lastTime = strtotime($lastTime);

	// perform subtraction to get the difference (in seconds) between times
	$timeDifff = $lastTime - $firstTime;

	// return the difference
	return $timeDiff;
}

if (!function_exists('FormataDataBR')) {
	function FormataDataBR($data)
	{
		if ($data == '')
			return '';
		$data_f = explode('-', $data);
		return $data_f[2] . '/' . $data_f[1] . '/' . $data_f[0];
	}
}

if (!function_exists('FormataDataBD')) {
	function FormataDataBD($data)
	{
		if ($data == '')
			return '';
		$data_f = explode('/', $data);
		return $data_f[2] . '-' . $data_f[1] . '-' . $data_f[0];
	}
}

function formata_data_extenso($strDate)
{
	// Array com os dia da semana em portugu�s;
	$arrDaysOfWeek = array('Domingo', 'Segunda-feira', 'Ter&ccedil;a-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'S&aacute;bado');
	// Array com os meses do ano em portugu�s;
	$arrMonthsOfYear = array(1 => 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
	// Descobre o dia da semana
	$intDayOfWeek = date('w', strtotime($strDate));
	// Descobre o dia do m�s
	$intDayOfMonth = date('d', strtotime($strDate));
	// Descobre o m�s
	$intMonthOfYear = date('n', strtotime($strDate));
	// Descobre o ano
	$intYear = date('Y', strtotime($strDate));
	// Formato a ser retornado
	return $arrDaysOfWeek[$intDayOfWeek] . ', ' . $intDayOfMonth . ' de ' . $arrMonthsOfYear[$intMonthOfYear] . ' de ' . $intYear;
}

function addTime($hora, $ivm)
{
	$horaNova = strtotime("$hora + $ivm minutes");
	$horaNovaFormatada = date("H:i", $horaNova);
	return $horaNovaFormatada;
}

function ultimoDiaMes($data = "")
{
	if (!$data) {
		$dia = date("d");
		$mes = date("m");
		$ano = date("Y");
	} else {
		$dia = date("d", $data);
		$mes = date("m", $data);
		$ano = date("Y", $data);
	}
	$data = mktime(0, 0, 0, $mes, 1, $ano);
	return date("d", $data - 1);
}


function CalcularIdade($nascimento, $formato, $separador)
{
	//Data Nascimento
	$nascimento = explode($separador, $nascimento);

	if ($data1 > $data2) {
		return " ";
	}

	if ($formato == "dma") {
		$ano = $nascimento[2];
		$mes = $nascimento[1];
		$dia = $nascimento[0];
	} elseif ($formato == "amd") {
		$ano = $nascimento[0];
		$mes = $nascimento[1];
		$dia = $nascimento[2];
	}

	$dia1 = $dia;
	$mes1 = $mes;
	$ano1 = $ano;

	$dia2 = date("d");
	$mes2 = date("m");
	$ano2 = date("Y");

	$dif_ano = $ano2 - $ano1;
	$dif_mes = $mes2 - $mes1;
	$dif_dia = $dia2 - $dia1;

	if (($dif_mes == 0) and ($dia2 < $dia1)) {
		$dif_dia = (ultimoDiaMes($data1) - $dia1) + $dia2;
		$dif_mes = 11;
		$dif_ano--;
	} elseif ($dif_mes < 0) {
		$dif_mes = (12 - $mes1) + $mes2;
		$dif_ano--;
		if ($dif_dia < 0) {
			$dif_dia = (ultimoDiaMes($data1) - $dia1) + $dia2;
			$dif_mes--;
		}
	} elseif ($dif_dia < 0) {
		$dif_dia = (ultimoDiaMes($data1) - $dia1) + $dia2;
		if ($dif_mes > 0) {
			$dif_mes--;
		}
	}
	if ($dif_ano > 0) {
		$dif_ano = $dif_ano . " ano" . (($dif_ano > 1) ? "s " : " ");
	} else {
		$dif_ano = "";
	}
	if ($dif_mes > 0) {
		$dif_mes = $dif_mes . " mes" . (($dif_mes > 1) ? "es " : " ");
	} else {
		$dif_mes = "";
	}
	if ($dif_dia > 0) {
		$dif_dia = $dif_dia . " dia" . (($dif_dia > 1) ? "s " : " ");
	} else {
		$dif_dia = "";
	}

	return $dif_ano;
}

function verificaCadastroPaciente($cdsolcons)
{

	$CdSolCons = (int)$cdsolcons;

	$sql = 	"	SELECT	IF (p.CEP IS NOT NULL, 1, 0) AS Valida
				FROM		tbsolcons 	sc
				INNER JOIN 	tbpaciente 	p ON p.CdPaciente = sc.CdPaciente
				WHERE	sc.CdSolCons 	= '$CdSolCons'
				GROUP BY sc.CdPaciente LIMIT 1 
			";

	$query = mysqli_query($GLOBALS['db'], $sql);

	$resultado = mysqli_fetch_array($query);

	if ($resultado['Valida']) {
		return true;
	} else {
		return false;
	}
}

function buscaPaciente($cdsolcons)
{

	$CdSolCons = (int)$cdsolcons;

	$sql = 	"	SELECT		sc.CdPaciente
				FROM		tbsolcons 	sc
				INNER JOIN 	tbpaciente 	p ON p.CdPaciente = sc.CdPaciente
				WHERE		sc.CdSolCons 	= '$CdSolCons'
				AND			p.`Status`		= '1'
				GROUP BY sc.CdPaciente LIMIT 1 
			";

	$query 		= mysqli_query($GLOBALS['db'], $sql);
	$resultado 	= mysqli_fetch_array($query);
	$qtdLinhas	= mysqli_num_rows($query);

	if ($qtdLinhas > 0) {
		return $resultado['CdPaciente'];
	} else {
		return 0;
	}
}
//Calcular idade, retorna array
/*function CalcularIdade($nascimento,$formato,$separador)
{
	//Data Nascimento
	$nascimento = explode($separador, $nascimento);

	if ($data1>$data2)
	{
       return " ";
    }

	if ($formato=="dma")
	{
		$ano = $nascimento[2];
		$mes = $nascimento[1];
		$dia = $nascimento[0];
	}
	elseif ($formato=="amd")
	{
		$ano = $nascimento[0];
		$mes = $nascimento[1];
		$dia = $nascimento[2];
	}

	$dia1 = $dia;
	$mes1 = $mes;
	$ano1 = $ano;

    $dia2 = date("d");
    $mes2 = date("m");
    $ano2 = date("Y");

    $dif_ano = $ano2 - $ano1;
    $dif_mes = $mes2 - $mes1;
    $dif_dia = $dia2 - $dia1;

    if ( ($dif_mes == 0) and ($dia2 < $dia1) ) {
       $dif_dia = (ultimoDiaMes($data1) - $dia1) + $dia2;
       $dif_mes = 11;
       $dif_ano--;
    } elseif ($dif_mes < 0) {
       $dif_mes = (12 - $mes1) + $mes2;
       $dif_ano--;
       if ($dif_dia<0){
          $dif_dia = (ultimoDiaMes($data1) - $dia1) + $dia2;
          $dif_mes--;
       }
    } elseif ($dif_dia < 0) {
       $dif_dia = (ultimoDiaMes($data1) - $dia1) + $dia2;
       if ($dif_mes>0) {
          $dif_mes--;
       }
    }
    if ($dif_ano>0) {
       $dif_ano = $dif_ano . " ano" . (($dif_ano>1) ? "s ": " ") ;
    } else { $dif_ano = ""; }
    if ($dif_mes>0) {
       $dif_mes = $dif_mes . " mes" . (($dif_mes>1) ? "es ": " ") ;
    } else { $dif_mes = ""; }
    if ($dif_dia>0) {
       $dif_dia = $dif_dia . " dia" . (($dif_dia>1) ? "s ": " ") ;
    } else { $dif_dia = ""; }

    return $dif_ano . $dif_mes . $dif_dia;

}*/

// fun��o comprar duas datas  (estoque vacina��o)
function compdata($dtatual, $dtval)
{
	$dtatual = $dtatual;
	$dtval = $dtval;
	$dtval = explode('-', $dtval);
	$mes_val = $dtval[1];
	$dia_val = $dtval[2];
	$ano_val = $dtval[0];


	$dtatual = explode('-', $dtatual);
	$mes_at = $dtatual[1];
	$dia_at = $dtatual[2];
	$ano_at = $dtatual[0];

	$dtatual = mktime(0, 0, 0, $mes_at, $dia_at, $ano_at); // timestamp da data inicial
	$dtval = mktime(0, 0, 0, $mes_val, $dia_val, $ano_val); // timestamp da data final
	if ($dtatual == $dtval) // validade igual 
	{
		return 2;
	}
	if ($dtatual > $dtval)  // validade vencida
	{
		return 0;
	}
	if ($dtatual < $dtval) {
		return 1;
	}
}

if (!function_exists('moeda')) {
	function moeda($get_valor)
	{
		$source = array('.', ',');
		$replace = array('', '.');
		$valor = str_replace($source, $replace, $get_valor); //remove os pontos e substitui a virgula pelo ponto
		return $valor; //retorna o valor formatado para gravar no banco
	}
}

function gera_protocolo()
{
	//To Pull 7 Unique Random Values Out Of AlphaNumeric

	//removed number 0, capital o, number 1 and small L
	//Total: keys = 32, elements = 33
	$characters = array(
		"A", "B", "C", "D", "E", "F", "G", "H", "J", "K", "L", "M",
		"N", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z",
		"1", "2", "3", "4", "5", "6", "7", "8", "9"
	);

	//make an "empty container" or array for our keys
	$keys = array();

	//first count of $keys is empty so "1", remaining count is 1-6 = total 7 times
	while (count($keys) < 7) {
		//"0" because we use this to FIND ARRAY KEYS which has a 0 value
		//"-1" because were only concerned of number of keys which is 32 not 33
		//count($characters) = 33
		$x = mt_rand(0, count($characters) - 1);
		if (!in_array($x, $keys)) {
			$keys[] = $x;
		}
	}
	$random_chars = "";
	foreach ($keys as $key) {
		$random_chars .= $characters[$key];
	}
	return $random_chars;
}




// REGRA DE N�GOCIO SISTEMA ICONSORCIO 
// MNUDA STATUS AGENDA FORNECEDOR 
function set_ag_status($cd, $status)
{

	$a = mysqli_query($GLOBALS['db'], "	SELECT
			tbagenda_fornecedor.cdagenda_fornecedor,
			tbagenda_fornecedor.cdfornecedor,
			tbagenda_fornecedor.cdprocedimento,
			tbagenda_fornecedor.obs,
			tbagenda_fornecedor.cdespecificacao,
			tbagenda_fornecedor.cdpref,
			tbagenda_fornecedor.`data`,
			tbagenda_fornecedor.hora,
			tbagenda_fornecedor.`status`
			FROM
			tbagenda_fornecedor
			INNER JOIN tbagendacons ON tbagenda_fornecedor.cdagenda_fornecedor = tbagendacons.cdagenda_fornecedor
			INNER JOIN tbsolcons ON tbsolcons.CdSolCons = tbagendacons.CdSolCons
			WHERE  tbsolcons.CdSolCons = '$cd'
			") or die(mysqli_error());

	$lin = mysqli_fetch_array($a);

	if (mysqli_num_rows($a) > 0) // SE EXISTE 
	{
		if ($lin['status'] == "M") {

			$cdfornecedor = $lin['cdfornecedor'];
			$cdprocedimento = $lin['cdprocedimento'];
			$obs = $lin['obs'];
			$cdespecificacao = $lin['cdespecificacao'];
			$cdpref = $lin['cdpref'];
			$data = $lin['data'];
			$hora = $lin['hora'];

			$ss = mysqli_query($GLOBALS['db'], "INSERT INTO `tbagenda_fornecedor`
					(`cdfornecedor`, `cdprocedimento`, `obs`, `cdespecificacao`, `cdpref`, `data`, `hora`, `status`) 
					VALUES ('$cdfornecedor', '$cdprocedimento', '$obs ', '$cdespecificacao', '$cdpref', '$data', '$hora', '$status')") or die(mysqli_error());
		}
	}
	$cdagenda_fornecedor = $lin["cdagenda_fornecedor"];
	$sql = mysqli_query($GLOBALS['db'], "UPDATE `tbagenda_fornecedor` SET `status`='$status' WHERE (`cdagenda_fornecedor`='$cdagenda_fornecedor')") or die(mysqli_error());
}

// OBTEM SALDO MUNICIPIO 
function saldo_municipio($CdPref, $ZeroNegativo = NULL)
{
	$query = "SELECT SUM(tbmovimentacao.Credito-tbmovimentacao.Debito) as Saldo FROM tbmovimentacao
		WHERE tbmovimentacao.CdPref = $CdPref";
	$result = mysqli_query($GLOBALS['db'], $query) or die('sm - ' . mysqli_error());
	if ($result) {
		while ($dados = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			if ($ZeroNegativo) {
				return ($dados['Saldo'] < 0) ? 0 : $dados['Saldo'];
			} else {
				return $dados['Saldo'];
			}
		}
	} else {
		return 0;
	}
}
// SETA MOVIMENTA��O 
function set_mov($CdPref, $CdUsuario, $CdSolCons, $TpMov, $Debito, $Credito)
{
	$dtmov = date('Y-m-d H:i:s');
	$sql_set_mov = mysqli_query($GLOBALS['db'], "INSERT INTO `tbmovimentacao` (`CdPref`, `CdUsuario`, `CdSolCons`, `TpMov`,`DtMov`, `Debito`, `Credito`) 
		VALUES ('$CdPref', '$CdUsuario', '$CdSolCons', '$TpMov','$dtmov', '$Debito', '$Credito')") or die(mysqli_error());
	return 1;
}

// REGRA DE N�GOCIO AGENDA
function set_aguardando($CdPaciente, $CdProc, $Obs, $CdEspecif, $Urgente, $pactuacao, $retorno, $remarcado, $cdmed)
{
	// AGUARDANDO      
	// GERA NOVO C�DIGO CLEUBER
	$qry = mysqli_query($GLOBALS['db'], "SELECT MAX(CdSolCons) FROM tbsolcons") or die(TrataErro(mysqli_errno(), '', '../index.php?p=frm_solagd', 'regn_solagd:gerar novo codigo'));
	$row = mysqli_fetch_array($qry);
	$CdSolCons = $row[0] + 1;
	//$CdSolCons = mysqli_result($qry,0) + 1;
	//echo $CdSolCons;
	$Protocolo  = date("Ymd") . $CdPaciente . "-" . $CdSolCons;
	$dtinc = date('Y-m-d');
	$hrinc = date('H:i:s');
	$userinc = $_SESSION['CdUsuario'];
	$CdUser = $_SESSION['CdUsuario'];

	// DESCOBRE A CIDADE DO PACIENTE 
	$sql_cidade_pac = mysqli_query($GLOBALS['db'], "SELECT tbprefeitura.CdPref, tbprefeitura.NmCidade 
		FROM 
		tbpaciente, tbprefeitura, tbbairro
		WHERE tbpaciente.CdBairro = tbbairro.CdBairro
		AND tbbairro.CdPref = tbprefeitura.CdPref
		AND tbpaciente.CdPaciente = '$CdPaciente'
		");
	$lpac = mysqli_fetch_array($sql_cidade_pac);
	$CdPref = $lpac["CdPref"];
	//echo $CdPref;

	// INSERE SOLICITA��O
	$sql = "INSERT INTO tbsolcons (CdSolCons,CdPaciente,CdEspecProc,CdUsuario,Protocolo,Obs1,Urgente, pactuacao,retorno,remarcado,dtinc,userinc,hrinc,CdPref,cdmed) 
					VALUES ($CdSolCons,$CdPaciente,$CdEspecif,$CdUser,'$Protocolo','$Obs','$Urgente', '$pactuacao','$retorno','$remarcado','$dtinc','$userinc','$hrinc','$CdPref','$cdmed')";

	$qry = mysqli_query($GLOBALS['db'], $sql) or die(TrataErro(mysqli_errno(), '', '../index.php?p=frm_solagd', 'regn_solagd:insert solagd'));

	if ($qry) {
		$msg1 = utf8_encode("Solicitação realizada com sucesso! Gostaria de fazer outra solicitação?");
		$msg2 = utf8_encode("Solicitação realizada com sucesso!");
		echo '<script language="JavaScript" type="text/javascript"> 
						//alert("Cadastro realizado com sucesso!");
						//window.location.href="../index.php?p=frm_cadpac&id=$CdPaciente";
						var agree=confirm("' . $msg1 . '");
						if (agree) window.location.href="../index.php?i=6&s=cons";
						else window.location.href="../index.php?i=6";
				</script>';

		echo $msg2;
	}
}

function set_marcado($DtAgCons, $HoraAgCons, $CdSolCons, $select_forne, $CdUser, $valor, $valor_sus, $valorm, $qts, $obs, $protocolopac,  $cdagenda_fornecedor, $flag)
{
	//valida��o
	if (validafat_data($DtAgCons)) {
		// MARCADO 
		$dtm = date('Y-m-d');
		$hrm = date('H:i:s');
		$userm = $_SESSION['CdUsuario'];
		if ($flag == 1) {
			$redirect = '45';
		}
		if ($flag == 0) {
			$redirect = '52';
		}
		//prntc
		//
		$dadossol = getDadosSolicitacao($CdSolCons);
		$CdPref = $dadossol['CdPref'];
		$CdEspecProc = $dadossol['CdEspecProc'];
		$ppi = getTetoPPICerto($CdPref, $valor, $DtAgCons, $CdEspecProc);
		$saldoppi = getSaldoPPI($ppi);
		$saldomun = saldo_municipio($CdPref, 1);
		$saldomm = $saldomun - $valor;

		$validasaldo = $saldoppi + $saldomun;

		if ($validasaldo >= $valor) {
			$sql = mysqli_query($GLOBALS['db'], "UPDATE `tbsolcons` SET `userm`='$userm', `dtm`='$dtm', `hrm`='$hrm' WHERE (`CdSolCons`='$CdSolCons')") or die(mysqli_error());
			if ($_SESSION["CdOrigem"] > 0) {
				$sql = mysqli_query($GLOBALS['db'], "UPDATE tbagenda_fornecedor SET cdpref = '$_SESSION[CdOrigem]' WHERE cdagenda_fornecedor = '$cdagenda_fornecedor'") or die(mysqli_error());
			}
			$sql = mysqli_query($GLOBALS['db'], "INSERT INTO `tbagendacons` (`DtAgCons`,`HoraAgCons`,`CdSolCons`,`CdForn`,  `CdUsuario`,  `valor`, `valor_sus`,valormed,`qts`, `obs`,`protocolopac`,`cdagenda_fornecedor`) 
				VALUES ('$DtAgCons', '$HoraAgCons', '$CdSolCons','$select_forne', '$CdUser', '$valor', '$valor_sus','$valorm','$qts', '$obs','$protocolopac','$cdagenda_fornecedor')") or die(mysqli_error());

			if ($ppi) {
				if ($saldoppi >= $valor) {
					$ret = setMovPPI($ppi, $CdSolCons, $valor);
				} else {
					$ret = setMovPPI($ppi, $CdSolCons, $saldoppi);
					$movprateio = $valor - $saldoppi;
					$ret = set_mov($CdPref, $CdUser, $CdSolCons, 'D', $movprateio, 0);
				}
			} elseif ($saldomm >= 0) {
				$TpMov = 'D';
				$Debito = $valor;
				$ret = set_mov($CdPref, $CdUser, $CdSolCons, $TpMov, $Debito, 0);
			}

			set_ag_status($CdSolCons, 'M');


			if ($sql) {
				echo "<script language='JavaScript' type='text/javascript'> 
						alert('Procedimento marcado com sucesso!');
						window.location.href='../index.php?i=$redirect';
						</script>";
			}
			return true;
		} else {
			echo '<script language="JavaScript" type="text/javascript">
				alert("Não foi possível marcar! Saldo insuficiente!");
				window.location.href="../index.php?i=' . $redirect . '";
				</script>';
		}
	} //valida��o
	else
		echo "<script language='JavaScript' type='text/javascript'> 
				window.location.href='../index.php?i=$redirect&cdag=$CdSolCons';
			  </script>";
}




function set_realizado($cd, $red = NULL)
{
	//valida��o
	if (validafat($cd, $red)) {
		$dados =  getDadosSolicitacao($cd);
		if ($dados['StatusS'] == 1) {
			$a = mysqli_query($GLOBALS['db'], " SELECT
				tbagenda_fornecedor.cdagenda_fornecedor,
				tbagenda_fornecedor.cdfornecedor,
				tbagenda_fornecedor.cdprocedimento,
				tbagenda_fornecedor.obs,
				tbagenda_fornecedor.cdespecificacao,
				tbagenda_fornecedor.cdpref,
				tbagenda_fornecedor.`data`,
				tbagenda_fornecedor.hora,
				tbagenda_fornecedor.`status`
				FROM
				tbagenda_fornecedor
				INNER JOIN tbagendacons ON tbagenda_fornecedor.cdagenda_fornecedor = tbagendacons.cdagenda_fornecedor
				INNER JOIN tbsolcons ON tbsolcons.CdSolCons = tbagendacons.CdSolCons
				WHERE  tbsolcons.CdSolCons = '$cd'
				") or die(mysqli_error());
			$lin = mysqli_fetch_array($a);

			if (mysqli_num_rows($a) > 0) {
				$cdagenda_fornecedor = $lin["cdagenda_fornecedor"];
				$sql = mysqli_query($GLOBALS['db'], "UPDATE `tbagenda_fornecedor` SET `status`='R' WHERE (`cdagenda_fornecedor`='$cdagenda_fornecedor')") or die(mysqli_error());
			}

			$sql = mysqli_query($GLOBALS['db'], "UPDATE `tbsolcons` SET `Status`='1' WHERE (`CdSolCons`='$cd')") or die(mysqli_error());
			$sql = mysqli_query($GLOBALS['db'], "UPDATE `tbagendacons` SET `Status`='2',  atendimento_ends_at = NOW() WHERE (`CdSolCons`='$cd')") or die(mysqli_error());
			$dtrel = date('Y-m-d');
			$hrrel = date('H:i:s');
			$userrel = $_SESSION['CdUsuario'];

			$sql = mysqli_query($GLOBALS['db'], "UPDATE `tbsolcons` SET `Status`='1',`userrel`='$userrel', 	`dtrel`='$dtrel',	`hrrel`='$hrrel'	WHERE (`CdSolCons`='$cd')") or die(mysqli_error());
			voltarTriagemCem($cd);
			### CONTROLE ###
			$sqlag = mysqli_query($GLOBALS['db'], "INSERT INTO tbauditoria (descr,dtalt,usralt,cdag) VALUES ('Confirmação','" . date("Y-m-d H:i:s") . "','$_SESSION[CdUsuario]','$cd')") or die("Erro ao tentar incluir LOG!");


			if (!isset($red)) {
				if ($sql) {
					echo "<script language=\"JavaScript\" type=\"text/javascript\"> 
								 alert('Confirmação realizada com sucesso!');
								 //window.location.href='index.php?i=6&op=$red';
						</script>";
				}
			}
		} else {
			return false;
		}
	} else {
		return false;
	}
}


function set_canc_novo($cd, $op, $cdmotcanc)
{
	if (validafat($cd, $op)) {
		$dados = getDadosSolicitacao($cd);
		if ($dados[StatusS] == 1) {
			$dtcanc = date('Y-m-d');
			$hrcanc = date('H:i:s');
			$usercanc = $_SESSION['CdUsuario'];
			//valida Agendacons
			switch ($dados['Status']) {
				case '1':
					//Marcado
					if ($dados['cdagenda_fornecedor']) {
						if (estornaAgendaFornecedor($dados['cdagenda_fornecedor'])) {
							$continuar = 1;
						} else {
							$continuar = 0;
						}
					} else {
						$continuar = 1;
					}
					break;
				default:
					//Aguardando
					$continuar = 1;
					break;
			}
			if ($continuar) {
				if (descobrecdtetoppimov($cd)) {
					remMovPPI2($cd);
					$estorno = saldo_municipio_estorno($cd);
					//$estorno = 10;
					if ($estorno) {
						set_mov($CdPref, $usercanc, $cd, 'C', 0, $estorno);
					}
				} else {
					$estorno = saldo_municipio_estorno($cd);
					//$estorno = 10;
					if ($estorno) {
						set_mov($CdPref, $usercanc, $cd, 'C', 0, $estorno);
					}
				}
				$sql = mysqli_query($GLOBALS['db'], "UPDATE `tbsolcons` SET Status='2',`usercanc`='$usercanc',`dtcanc`='$dtcanc',`hrcanc`='$hrcanc'WHERE (`CdSolCons`='$cd')") or die(mysqli_error());
				voltarTriagemCem($cd);
				return 1;
			} else {
				return 0;
			}
		} //validaStatus
	} //validafat
}



function set_canc($cd, $op, $cdmotcanc)
{
	//valida��o
	if (validafat($cd, $op)) {
		$dados =  getDadosSolicitacao($cd);
		if ($dados['StatusS'] == 1) {
			$dtcanc = date('Y-m-d');
			$hrcanc = date('H:i:s');
			$usercanc = $_SESSION['CdUsuario'];

			// OBTEM O STATUS DO PROCEDIMENTO
			$sql = mysqli_query($GLOBALS['db'], "SELECT tbsolcons.CdPref, tbsolcons.`Status` as ssol , tbagendacons.`Status` as sag, tbagendacons.cdagenda_fornecedor,tbagendacons.valor
				FROM tbsolcons, tbagendacons
				WHERE tbsolcons.CdSolCons = tbagendacons.CdSolCons
				AND tbsolcons.CdSolCons = '$cd'
		  		");

			$lin = mysqli_fetch_array($sql);
			$CdPref = $lin["CdPref"];

			// SE MARCADO
			if (($lin['ssol'] = '1') and ($lin['sag'] = '1')) {
				/***********************************************************************************************************/
				// AGENDA FORNECEDOR MARCADO
				$a = mysqli_query($GLOBALS['db'], "	SELECT
					tbagenda_fornecedor.cdagenda_fornecedor,
					tbagenda_fornecedor.cdfornecedor,
					tbagenda_fornecedor.cdprocedimento,
					tbagenda_fornecedor.obs,
					tbagenda_fornecedor.cdespecificacao,
					tbagenda_fornecedor.cdpref,
					tbagenda_fornecedor.`data`,
					tbagenda_fornecedor.hora,
					tbagenda_fornecedor.`status`
					FROM
					tbagenda_fornecedor
					INNER JOIN tbagendacons ON tbagenda_fornecedor.cdagenda_fornecedor = tbagendacons.cdagenda_fornecedor
					INNER JOIN tbsolcons ON tbsolcons.CdSolCons = tbagendacons.CdSolCons
					WHERE  tbsolcons.CdSolCons = '$cd'
					") or die(mysqli_error());
				$la = mysqli_fetch_array($a);

				if (mysqli_num_rows($a) > 0) {
					$cdfornecedor = $la['cdfornecedor'];
					$cdprocedimento = $la['cdprocedimento'];
					$obs = $la['obs'];
					$cdespecificacao = $la['cdespecificacao'];
					$cdpref = $la['cdpref'];
					$data = $la['data'];
					$hora = $la['hora'];

					if ($la['status'] != 'C') {
						// INSERE NOVA AGENDA (AGUARDANDO)
						$ss = mysqli_query($GLOBALS['db'], "INSERT INTO `tbagenda_fornecedor`
							(`cdfornecedor`, `cdprocedimento`, `obs`, `cdespecificacao`, `cdpref`, `data`, `hora`, `status`,`usrinc`,`dtinc`) 
							VALUES ('$cdfornecedor', '$cdprocedimento', '$obs ', '$cdespecificacao', '0', '$data', '$hora', 'A','$_SESSION[CdUsuario]','" . date("Y-m-d H:i:s") . "')") or die(mysqli_error());

						// CANCELA A AGENDA ANTERIOR 
						$cdagenda_fornecedor = $la["cdagenda_fornecedor"];
						$sql_2 = mysqli_query($GLOBALS['db'], "UPDATE `tbagenda_fornecedor` SET `status`='C' WHERE (`cdagenda_fornecedor`='$cdagenda_fornecedor')") or die(mysqli_error());
					}
				}
			}
			voltarTriagemCem($cd);
			$sql = mysqli_query($GLOBALS['db'], "UPDATE tbsolcons SET Status='2' WHERE (CdSolCons='$cd')") or die(mysqli_error("erro 1589"));
			$sql = mysqli_query($GLOBALS['db'], "UPDATE `tbsolcons` 	SET  `usercanc`='$usercanc',  `dtcanc`='$dtcanc', 	`hrcanc`='$hrcanc' 	WHERE (`CdSolCons`='$cd')") or die(mysqli_error());

			// Validação Saldo e Contrato
			$sql_movimentação = mysqli_query($GLOBALS['db'], "UPDATE tbsldmovimentacao SET `status` = 0 WHERE cdsolcons = $cd");
			$sql_contrato = mysqli_query($GLOBALS['db'], "UPDATE tbcontratomov SET `Status` = 0 WHERE CdSolCons = $cd");
		}
	}
}


// VERIFICA COTA FORNECEDOR (AGENDA )

function GetCotaAgenda($cdcota, $cdpref, $quant)
{

	$sql = mysqli_query($GLOBALS['db'], "  SELECT tbcota.cdcota, tbcota.CdEspecProc, tbcota.CdForn, tbcota.dtinicio, tbcota.dttermino, 
			tbcotam.cdpref, tbcotam.qts, tbprefeitura.CdPref, tbprefeitura.NmCidade
			FROM tbcotam, tbcota, tbprefeitura
			WHERE tbcota.cdcota = tbcotam.cdcota
			AND tbprefeitura.CdPref = tbcotam.cdpref
			AND tbcota.cdcota ='$cdcota'
			AND tbprefeitura.CdPref = '$cdpref'
			ORDER BY tbprefeitura.NmCidade
			 ") or die(mysqli_error());

	while ($lin = mysqli_fetch_array($sql)) {
		$dtinicio =  FormataDataBr($lin['dtinicio']);
		$dttermino = FormataDataBr($lin['dttermino']);

		// AGUARDANDO 
		$sql_agenda = mysqli_query($GLOBALS['db'], "
			  SELECT count(*) as A
			  FROM tbagenda_fornecedor
			  WHERE tbagenda_fornecedor.cdespecificacao = '$lin[CdEspecProc]'
			  AND tbagenda_fornecedor.cdfornecedor = '$lin[CdForn]'
			  AND tbagenda_fornecedor.`data` BETWEEN '$lin[dtinicio]' AND '$lin[dttermino]'
			  AND tbagenda_fornecedor.`status` = 'A'
			  AND tbagenda_fornecedor.cdpref ='$cdpref'
			  ") or die(mysqli_error());

		$l1 = mysqli_fetch_array($sql_agenda);
		$A = $l1['A'];


		// MARCADO 
		$sql_agenda = mysqli_query($GLOBALS['db'], "
			  SELECT count(*) as M
			  FROM tbagenda_fornecedor
			  WHERE tbagenda_fornecedor.cdespecificacao = '$lin[CdEspecProc]'
			  AND tbagenda_fornecedor.cdfornecedor = '$lin[CdForn]'
			  AND tbagenda_fornecedor.`data` BETWEEN '$lin[dtinicio]' AND '$lin[dttermino]'
			   AND tbagenda_fornecedor.cdpref ='$cdpref'
			  AND tbagenda_fornecedor.`status` = 'M'
			  ") or die(mysqli_error());

		$l1 = mysqli_fetch_array($sql_agenda);
		$M = $l1['M'];

		// REALIZADO 
		$sql_agenda = mysqli_query($GLOBALS['db'], "
			  SELECT count(*) as R
			  FROM tbagenda_fornecedor
			  WHERE tbagenda_fornecedor.cdespecificacao = '$lin[CdEspecProc]'
			  AND tbagenda_fornecedor.cdfornecedor = '$lin[CdForn]'
			  AND tbagenda_fornecedor.`data` BETWEEN '$lin[dtinicio]' AND '$lin[dttermino]'
			   AND tbagenda_fornecedor.cdpref ='$cdpref'
			  AND tbagenda_fornecedor.`status` = 'R'
			  ") or die(mysqli_error());

		$l1 = mysqli_fetch_array($sql_agenda);
		$R = $l1['R'];


		// EXCLU�DOS(DESIST�NCIA) 
		$sql_agenda = mysqli_query($GLOBALS['db'], "
			  SELECT count(*) as D
			  FROM tbagenda_fornecedor
			  WHERE tbagenda_fornecedor.cdespecificacao = '$lin[CdEspecProc]'
			  AND tbagenda_fornecedor.cdprocedimento = '$lin[CdProcedimento]'
			  AND tbagenda_fornecedor.cdfornecedor = '$lin[CdForn]'
			  AND tbagenda_fornecedor.`data` BETWEEN '$lin[dtinicio]' AND '$lin[dttermino]'
			  AND tbagenda_fornecedor.`status` = 'E'
			  ") or die(mysqli_error());

		$l1 = mysqli_fetch_array($sql_agenda);
		$D = $l1['D'];

		$qts = (($lin["qts"]) - ($A + $M + $R + $D));
	}
	if ($quant <= $qts) {
		return true;
	} else {
		echo '<script language="JavaScript" type="text/javascript"> 
										alert("A quantidade liberada deve ser menor ou igual a quantidade disponível!");
								</script>';
		return false;
	}
}

// ADICIONA AGENDA 
function setAgendaFornecedor($cdfornecedor, $cdespecificacao, $cdpref, $data, $hora, $obs, $qts, $ivm)
{
	$cdfornecedor = $cdfornecedor;
	$data = $data;
	$data = FormataDataBD($data);
	$hora = $hora;

	// VERIFICA O TIPO DE PROCEIDMENTO (CONSULTA, EXAME)
	$sql_proc = mysqli_query($GLOBALS['db'], "SELECT
		tbprocedimento.CdProcedimento
		FROM
		tbespecproc
		INNER JOIN tbprocedimento ON tbprocedimento.CdProcedimento = tbespecproc.CdProcedimento
		WHERE tbespecproc.CdEspecProc = $cdespecificacao") or die(mysqli_error());

	$lproc = mysqli_fetch_array($sql_proc);
	$cdprocedimento = $lproc['CdProcedimento'];

	$cdpref = $cdpref;
	$obs = $obs;
	$qts = $qts; // quantidade de agendas 
	$ivm = $ivm; // intevalo em minutos

	// VERIFICA COTA AGENDA FORNECEDOR > MAIOR QUE A COTA VIGENTE 
	// $return = verificaCotaFornecedor($cdfornecedor,$cdespecificacao,$data,$qts,$cdpref);

	$sql = mysqli_query($GLOBALS['db'], " INSERT INTO `tbagenda_fornecedor` (`cdfornecedor`, `data`, `hora`, `cdprocedimento`,`cdespecificacao`,`cdpref`,`obs`) 
			VALUES ('$cdfornecedor', '$data', '$hora', '$cdprocedimento', '$cdespecificacao', '$cdpref','$obs')");


	for ($i = 2; $i <= $qts; $i++) {
		$hora2[$i] = addTime($hora, $ivm);
		$hora = addTime($hora, $ivm);

		$sql = mysqli_query($GLOBALS['db'], " INSERT INTO `tbagenda_fornecedor` (`cdfornecedor`, `data`, `hora`, `cdprocedimento`,`cdespecificacao`,`cdpref`,`obs`) 
			VALUES ('$cdfornecedor', '$data', '$hora2[$i]', '$cdprocedimento', '$cdespecificacao', '$cdpref','$obs')");
	}
}
function cabecalho_wordexcel($nome, $tam = '1016')
{
	$sql = mysqli_query($GLOBALS['db'], "SELECT * FROM tbconsorcio") or die(mysqli_error());
	$lin = mysqli_fetch_array($sql);
	$data = date('d/m/Y');
	$horario = date("H:i:s");
	/* Cabe�alho */
	echo "<table width='$tam' border='1'>
		  <tr>
			<td width='173' colspan='2' rowspan='4' align='center'><strong> $nome </strong></td>
			<td width='522' colspan='4' align='center'><strong> $lin[nmconsorcio] </strong></td>
			<td colspan='2' width='299' align='center'>Data Emiss&atilde;o:  $data </td>
		  </tr>
		  <tr>
			<td width='522' colspan='4' align='center'> $lin[enderecoconsorcio] </td>
			<td colspan='2' align='center'>Hora Emiss&atilde;o: $horario  </td>
		  </tr>
		  <tr>
			<td width='522' colspan='4' align='center'>$lin[dadosconsorcio] </td>
			<td colspan='2' align='center'>&nbsp;</td>
		  </tr>
		"; //</table>
}
//Fun��o obsoleta 05/10/2017 -- Atual getConfiguracao
/*function config($ac){
	 	$qry = mysqli_query($GLOBALS['db'],"SELECT * FROM tbconfig
	 						WHERE cdconfig = 1");

	 	$c = mysqli_fetch_array($qry);

	 	//echo $c[$ac];
	 	return $c[$ac];
	 }*

	//Add com o CEAE
		/**
		 * [readJoin description]
		 * @param  [type] $select [description]
		 * @param  [type] $tabela [description]
		 * @param  [type] $cond   [description]
		 * @return [type]         [description]
		 */
function readJoin($select, $tabela, $cond = NULL)
{
	$resultado = NULL;
	$qrRead = "SELECT {$select} FROM {$tabela} {$cond}";
	$stRead = mysqli_query($GLOBALS['db'], $qrRead) or die('Erro ao ler em ' . $tabela . ' ' . mysqli_error($GLOBALS['db']));
	$cField = mysqli_num_fields($stRead);
	for ($y = 0; $y < $cField; $y++) {
		$names[$y] = mysqli_fetch_field($stRead);
		//print_r($names[$y]->name);
	}
	for ($x = 0; $res = mysqli_fetch_assoc($stRead); $x++) {
		for ($i = 0; $i < $cField; $i++) {
			$resultado[$x][$names[$i]->name] = $res[$names[$i]->name];
		}
	}
	return $resultado;
}

/**
 * [Realizar cancelamento dos procedimentos do CEAE(Viva Vida)]
 * @param [type] $mot  [description]
 * @param [type] $cdag [description]
 */
function set_canc_vv($mot, $cdag)
{
	$sql_cac = mysqli_query($GLOBALS['db'], "UPDATE tbagvivavida SET estado = 'C', `motcanc`='$mot' WHERE cdagvivavida = '$cdag' ") or die("Erro ao tentar cancelar: " . mysqli_error());
	### CONTROLE ###	
	$sqlag = mysqli_query($GLOBALS['db'], "INSERT INTO tbauditoria_viva (descr,dtalt,usralt,cdag) VALUES ('Cancelamento','" . date("Y-m-d H:i:s") . "','$_SESSION[CdUsuario]','$cdag')") or die("Erro ao tentar incluir LOG!");
	voltarTriagemCeae($cdag);
	return $sql_cac;
}
/**
 * [set_falta_vv description]
 * @param [type] $mot  [description]
 * @param [type] $cdag [description]
 */
function set_falta_vv($mot, $cdag)
{
	$sql_cac = mysqli_query($GLOBALS['db'], "UPDATE tbagvivavida SET estado = 'F', `motfalta`='$mot' WHERE cdagvivavida = '$cdag' ") or die("Erro ao tentar falta: " . mysqli_error());
	### CONTROLE ###	
	$sqlag = mysqli_query($GLOBALS['db'], "INSERT INTO tbauditoria_viva (descr,dtalt,usralt,cdag) VALUES ('Falta','" . date("Y-m-d H:i:s") . "','$_SESSION[CdUsuario]','$cdag')") or die("Erro ao tentar incluir LOG falta!");
	voltarTriagemCeae($cdag);
	return $sql_cac;
}

/**
 * [descobreSexo description]
 * @param  [type] $cdpaciente [description]
 * @return [type]             [description]
 */
function descobreSexo($cdpaciente)
{
	$retorno = readJoin('tbpaciente.Sexo', 'tbpaciente', 'WHERE CdPaciente = ' . $cdpaciente);
	if ($retorno) {
		foreach ($retorno as $dados) :
			$saida = $dados['Sexo'];
		endforeach;
	}
	return $saida;
}

/**
 * [descobreForm description]
 * @param  [type] $cdespecproc [description]
 * @return [type]              [description]
 */
function descobreForm($cdespecproc)
{
	$retorno = readJoin('ep.formLayout, ep.formValidate, ep.tbvivavida', 'tbespecproc AS ep', 'WHERE ep.CdEspecProc = ' . $cdespecproc);
	if ($retorno) {
		foreach ($retorno as $dados) :
			$saida['formLayout'] = $dados['formLayout'];
			$saida['formValidate'] = $dados['formValidate'];
			$saida['tbvivavida'] = $dados['tbvivavida'];
		endforeach;
	}
	return $saida;
}

/**
 * [descobrePai description]
 * @param  [type] $cdespecproc [description]
 * @return [type]              [description]
 */
function descobrePai($cdespecproc)
{
	$retorno = readJoin('sub.CdEspecPai AS pai', 'tbespecprocsub AS sub', 'WHERE sub.CdEspecFilho = ' . $cdespecproc);
	if ($retorno) {
		foreach ($retorno as $dados) :
			$saida = $dados['pai'];
		endforeach;
	}
	return $saida;
}
/**
 * [nomeEspecificacao description]
 * @param  [type] $CdEspec [description]
 * @return [type]          [description]
 */
function nomeEspecificacao($CdEspec)
{
	$query = "SELECT esp.CdEspecProc,
							esp.NmEspecProc
							FROM
							tbespecproc AS esp
							WHERE
							esp.CdEspecProc = {$CdEspec}
							";
	$resultado = mysqli_query($GLOBALS['db'], $query) or die(mysqli_error());

	$l = mysqli_fetch_array($resultado);

	return $l['NmEspecProc'];
}

/**
 * [valorEspecificacao description]
 * @param  [type] $CdEspec [description]
 * @return [type]          [description]
 */
function valorEspecificacao($CdEspec)
{
	$query = "	SELECT
						tbespecproc.valor,
						tbespecproc.CdEspecProc
						FROM
						tbespecproc
						WHERE CdEspecProc = {$CdEspec}
							";
	$resultado = mysqli_query($GLOBALS['db'], $query) or die(mysqli_error());

	$l = mysqli_fetch_array($resultado);

	return $l['valor'];
}

/**
 * [descobreFilhos description]
 * @param  [type] $CdEspec [description]
 * @return [type]          [description]
 */
function descobreFilhos($CdEspec)
{
	$retorno = readJoin(
		'sub.id,esp.NmEspecProc,sub.CdEspecFilho,sub.`status`',
		'tbespecprocsub AS sub',
		'INNER JOIN tbespecproc AS esp ON esp.CdEspecProc = sub.CdEspecFilho WHERE sub.`status` = 1 AND sub.CdEspecPai = ' . $CdEspec
	);

	if ($retorno) {
		foreach ($retorno as $dados) :
			$saida[$dados['id']]['cd'] = $dados['CdEspecFilho'];
			$saida[$dados['id']]['nome'] = $dados['NmEspecProc'];

		endforeach;
	}
	return $saida;
}
/**
 * [descobreFilhosForn description]
 * @param  [type] $CdEspec [description]
 * @param  [type] $cdforn  [description]
 * @return [type]          [description]
 */
function descobreFilhosForn($CdEspec, $cdforn)
{
	$retorno = readJoin(
		'sub.id,esp.NmEspecProc,sub.CdEspecFilho,sub.`status`,esp.CdProcedimento',
		'tbespecprocsub AS sub',
		'INNER JOIN tbespecproc AS esp ON esp.CdEspecProc = sub.CdEspecFilho 
				INNER JOIN tbfornespec ON sub.CdEspecFilho = tbfornespec.CdEspec 
				WHERE sub.`status` = 1 AND sub.CdEspecPai = ' . $CdEspec . ' AND tbfornespec.CdForn = ' . $cdforn . '
				GROUP BY tbfornespec.CdForn,tbfornespec.CdEspec'
	);
	// var_dump($retorno); die();
	if ($retorno) {
		foreach ($retorno as $dados) :
			$saida[$dados['id']]['cd'] = $dados['CdEspecFilho'];
			$saida[$dados['id']]['nome'] = $dados['NmEspecProc'];
			$saida[$dados['id']]['CdProcedimento'] = $dados['CdProcedimento'];
		endforeach;
	}
	return $saida;
}
/**
 * [descobreFilhoExiste description]
 * @param  [type] $CdEspecPai   [description]
 * @param  [type] $CdEspecFilho [description]
 * @return [type]               [description]
 */
function descobreFilhoExiste($CdEspecPai, $CdEspecFilho)
{
	$retorno = readJoin(
		'sub.CdEspecPai,sub.CdEspecFilho,sub.`status`',
		'tbespecprocsub AS sub',
		'WHERE sub.CdEspecFilho = ' . $CdEspecFilho . ' AND sub.CdEspecPai = ' . $CdEspecPai
	);

	if ($retorno) {
		foreach ($retorno as $dados) :
			$saida = $dados['status'];
		endforeach;
	} else {
		$saida = 2;
	}
	return $saida;
}
function verificaNotReferencia($cdReferencia)
{
	$resultado =  mysqli_query($GLOBALS['db'], "SELECT
							tbnotificacaoceae.cdReferencia,
							tbnotificacaoceae.`status`
							FROM
							tbnotificacaoceae
							WHERE cdReferencia = {$cdReferencia}");

	$dados = mysqli_fetch_array($resultado);
	if (mysqli_num_rows($resultado) > 0) {
		return $dados['status'];
	} else {
		return NULL;
	}
}
function format_telefone($tel)
{
	$ddd_cliente = substr($tel, 0, 2);
	$numero_cliente = substr($tel, 2);

	return "(" . $ddd_cliente . ")" . $numero_cliente;
}

/*function validafat($cd, $helper = NULL, $id = NULL)
	    {
	    	//if � usado somente no Fat. Fornecedor Por Municipio - Alterar(alt_fat_r.php) para que a valida��o n�o seja retirada.
	    	if($helper != 1)
	    	{
		        $sql = mysqli_query($GLOBALS['db'],"SELECT ac.CdSolCons,ac.DtAgCons FROM tbagendacons AS ac WHERE ac.CdSolCons = '$cd' ") or die("Erro: ".mysqli_error());
		        $l = mysqli_fetch_array($sql);
		        $sql = mysqli_query($GLOBALS['db'],"SELECT g.idgenfat
		                FROM
		                tbgenfat AS g
		                WHERE '$l[DtAgCons]' BETWEEN g.dtini AND g.dtfim
		                AND g.estado = 'A'") or die("Erro: ".mysqli_error());
		        if(mysqli_num_rows($sql)>0)
		        {
		            return false;
		        }else{
		            return true;
		        }
		    }
		    else
		    	return true;    
	    }*/

function validafat_ceae($cd)
{
	$sql = mysqli_query($GLOBALS['db'], "SELECT * from tbagvivavida where cdagvivavida = '$cd' ") or die("Erro: " . mysqli_error());
	$l = mysqli_fetch_array($sql);
	$sql = mysqli_query($GLOBALS['db'], "SELECT g.idgenfat
		                         FROM
		                tbgenfat AS g
		                WHERE '$l[dtag]' BETWEEN g.dtini AND g.dtfim
		                AND g.estado = 'A'") or die("Erro: " . mysqli_error());

	return (mysqli_num_rows($sql) > 0) ? false : true;
}

/* function validafat_data($dtAgenda,$id = null)
	    {        
	        $sql = mysqli_query($GLOBALS['db'],"SELECT g.idgenfat
	                FROM
	                tbgenfat AS g
	                WHERE '$dtAgenda' BETWEEN g.dtini AND g.dtfim
	                AND g.estado = 'A'") or die("Erro: ".mysqli_error());
	        return (mysqli_num_rows($sql)>0) ? false : true;
	    }*/

function ceaeQtnFaltas($cdespecproc, $cdpac)
{
	$query = "SELECT COUNT(tbagvivavida.cdpac) as qtd FROM tbagvivavida
						INNER JOIN tbagvivavida_itens ON tbagvivavida.cdagvivavida = tbagvivavida_itens.cdagvv
						WHERE tbagvivavida.cdpac = $cdpac 
						AND tbagvivavida_itens.cdespec = $cdespecproc 
						AND tbagvivavida.estado = 'F' 
						AND tbagvivavida.motfalta = 12
						";
	$result = mysqli_query($GLOBALS['db'], $query) or die('Faltas - ' . mysqli_error());
	if ($result) {
		$lpp = mysqli_fetch_array($result);
		return $lpp['qtd'];
	} else {
		return 0;
	}
}
function ceaeAgendaDisponivel($cdagvivavida)
{
	$query = "SELECT tbagvivavida.cdagvivavida FROM tbagvivavida
						WHERE tbagvivavida.cdagvivavida = $cdagvivavida 
						AND tbagvivavida.estado = 'A' 
						AND tbagvivavida.cdpac IS NULL
						";
	$result = mysqli_query($GLOBALS['db'], $query) or die('agendadisponivel - ' . mysqli_error());
	if (mysqli_num_rows($result)) {
		return 1;
	} else {
		return 0;
	}
}
//FIM CEAE

//TRANSPORTE
function trQntMov($CdAgViagem, $CdPref)
{
	$qry = mysqli_query($GLOBALS['db'], "SELECT TipoViagem FROM tbtragviagem where CdAgViagem = '$CdAgViagem'");
	$n = mysqli_fetch_array($qry);

	$pref_q = ($CdPref > 0 && $n["TipoAgenda"] != "P") ? " and tavmov.CdPref = $CdPref " : "";
	$query = mysqli_query($GLOBALS['db'], "SELECT tavmov.CdAgViagem, sum(ttp.QntLugar) as qnt 
								  from tbtragviagemmov tavmov
								  inner join tbtrtppassageiro ttp on tavmov.CdTpPassageiro = ttp.CdTpPassageiro
								  where tavmov.`Status` = 1  
								  and tavmov.CdAgViagem = '$CdAgViagem' $pref_q
								  GROUP BY tavmov.CdAgViagem");

	if (mysqli_num_rows($query) > 0) {
		$rest = mysqli_fetch_array($query);
		return ceil($rest["qnt"]);
	} else
		return 0;
}

function trQntLugarVeiculo($CdAgViagem, $CdPref)
{
	$pref_q = ($CdPref > 0) ? " and tavm.CdPref = $CdPref " : "";
	$query = mysqli_query($GLOBALS['db'], "SELECT tav.CdAgViagem, tav.QntDisp, tav.TipoAgenda, tavm.Cota
								  from tbtragviagem tav
								  inner join tbtragviagemmun tavm on tav.CdAgViagem = tavm.CdAgViagem
								  where tavm.`Status` = 1 and tav.CdAgViagem = '$CdAgViagem' $pref_q
								  ORDER BY tavm.CdAgViagemMun");

	if (mysqli_num_rows($query) > 0) {
		$qnt = mysqli_fetch_array($query);
		$qnt_final = $qnt["QntDisp"];

		return ($CdPref > 0 && $qnt["TipoAgenda"] != "P") ? $qnt["Cota"] : $qnt["QntDisp"];
	} else
		return 0;
}

function muninicipioPaciente($CdPaciente)
{
	$result = mysqli_query($GLOBALS['db'], " SELECT tbprefeitura.CdPref
			FROM tbpaciente, tbprefeitura, tbbairro
			WHERE tbpaciente.CdBairro = tbbairro.CdBairro
			AND tbprefeitura.CdPref = tbbairro.CdPref
			AND tbpaciente.CdPaciente = $CdPaciente ") or die('MdP - ' . mysqli_error());
	if ($result) {
		$lpp = mysqli_fetch_array($result);
		return $lpp['CdPref'];
	} else {
		return 0;
	}
}

function municipioAcompanhante($CdAcompanhate)
{
	$result = mysqli_query($GLOBALS['db'], " SELECT CdPref FROM tbtracompanhante WHERE CdAcompanhante = $CdAcompanhate ") or die('MdA - ' . mysqli_error());
	if ($result) {
		$lpp = mysqli_fetch_array($result);
		return $lpp['CdPref'];
	} else {
		return 0;
	}
}

function criarTicket($CdAgViagemMov)
{
	$cdagviagemmov = $CdAgViagemMov;
	$query = "SELECT
				mov.CdAgViagemMov,
				tbtragviagem.CdAgViagem,
				tbtragviagem.`Data`,
				date_format(tbtragviagem.`Data`,'%d/%m/%Y') AS `Databr`,
				tbtragviagem.Hora,
				tbtragviagem.CdVeiculo,
				tbtragviagem.TipoViagem,
				tbtrrota.PrefInicio,
				tbtrrota.PrefFim,
				tbpaciente.NmPaciente,
				tbpaciente.RG,
				tbpaciente.CPF,
				mov.Assento,
				prefini.NmCidade AS NmPrefInicio,
				preffim.NmCidade AS NmPrefFim,
				tbtrveiculo.NmVeiculo,
				tbtrveiculo.Placa
				FROM
				tbtragviagemmov AS mov
				INNER JOIN tbtragviagem ON mov.CdAgViagem = tbtragviagem.CdAgViagem
				INNER JOIN tbtrrota ON tbtragviagem.CdRota = tbtrrota.CdRota
				INNER JOIN tbpaciente ON mov.CdPaciente = tbpaciente.CdPaciente
				INNER JOIN tbprefeitura AS prefini ON tbtrrota.PrefInicio = prefini.CdPref
				INNER JOIN tbprefeitura AS preffim ON tbtrrota.PrefFim = preffim.CdPref
				INNER JOIN tbtrveiculo ON tbtragviagem.CdVeiculo = tbtrveiculo.CdVeiculo
				WHERE mov.CdAgViagemMov = $cdagviagemmov";

	$result = mysqli_query($GLOBALS['db'], $query) or die(mysqli_error());

	if (!mysqli_num_rows($result)) {
		$texto = "Error!";
	} else {
		while ($result_row = mysqli_fetch_array($result)) {
			$result_rows[] = $result_row;
		}
		foreach ($result_rows as $row);
		if ($row['TipoViagem'] == 'I') {
			$titulo = "PASSAGEM IDA";
			$destino = '' . $row['NmPrefInicio'] . ' -> ' . $row['NmPrefFim'] . '';
		} else {
			$titulo = "PASSAGEM VOLTA";
			$destino = '' . $row['NmPrefFim'] . ' -> ' . $row['NmPrefInicio'] . '';
		}
		$texto = "........................................................\r\n\r\n$titulo\r\nCOD. VIAGEM: $row[CdAgViagem]\r\nDESTINO: $destino\r\nDATA: $row[Databr]\r\nHORA: $row[Hora]\r\nVEICULO: $row[NmVeiculo]\r\nPLACA: $row[Placa]\r\nPASSAGEIRO\r\n";
		$texto .= "NOME: $row[NmPaciente]\r\nRG: $row[RG]\r\nCPF: $row[CPF]\r\n";
		$texto .= "ASSENTO: $row[Assento]\r\n\r\n........................................................";
	}
	$caminho = "tickettxt/";
	$data = date('YmdHis');
	$nomeArq = "$CdAgViagemMov$data.txt";
	$fp = fopen("$caminho$nomeArq", "wb");
	$fp = fopen("$caminho$nomeArq", "ab");
	//$texto= "Ola\r\nOla2\r\n";
	fwrite($fp, $texto, strlen($texto));
	fclose($fp);

	return $nomeArq;
}
function descobrePassagem($CdAgViagemMov)
{
	$query = "SELECT
						tbtragviagemmov.CdAgViagemMov,
						tbtragviagemmov.TipoPassagem,
						tbtragviagemmov.CdPaciente
						FROM
						tbtragviagemmov
						WHERE tbtragviagemmov.CdAgViagemMov = $CdAgViagemMov
						";
	$result = mysqli_query($GLOBALS['db'], $query) or die('dsp - ' . mysqli_error());
	if ($result) {
		while ($dados = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			return $dados;
		}
	} else {
		return 0;
	}
}
function descobrePaciente($CdPaciente)
{
	$query = "SELECT
						tbpaciente.CdPaciente,
						tbpaciente.NmPaciente,
						tbpaciente.RG,
						tbpaciente.CPF,
						tbpaciente.Sexo,
						tbpaciente.DtNasc,
						date_format(tbpaciente.DtNasc,'%d/%m/%Y') AS DtNascbr,
						tbpaciente.Telefone,
						tbpaciente.Celular,
						tbpaciente.CdBairro,
						tbpaciente.NmMae,
						tbpaciente.Logradouro,
						tbpaciente.Numero,
						tbbairro.NmBairro,
						tbprefeitura.CdPref,
						tbprefeitura.NmCidade,
						tbestado.NmEstado,
						tbestado.UF,
						tbpaciente.csus,
						tbpaciente.CertidaoMatricula,
						tbpaciente.OutrosDocs
						FROM
						tbpaciente
						INNER JOIN tbbairro ON tbpaciente.CdBairro = tbbairro.CdBairro
						INNER JOIN tbprefeitura ON tbbairro.CdPref = tbprefeitura.CdPref
						INNER JOIN tbestado ON tbprefeitura.CdEstado = tbestado.CdEstado
						WHERE
						tbpaciente.CdPaciente = $CdPaciente
						";
	$result = mysqli_query($GLOBALS['db'], $query) or die(mysqli_error($GLOBALS['db']));
	if ($result) {
		while ($dados = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			return $dados;
		}
	} else {
		return 0;
	}
}
//FIM TRANSPORTE
function veratend($cdpac, $cdespec)
{
	$sqatend = mysqli_query($GLOBALS['db'], "SELECT sc.CdSolCons,sc.CdEspecProc,sc.CdPaciente,sc.`Status`,ac.DtAgCons,ac.`Status`,tfd.DtAgen
								FROM tbsolcons AS sc
								LEFT JOIN tbagendacons AS ac ON sc.CdSolCons = ac.CdSolCons
								LEFT JOIN tbagentfd AS tfd ON sc.CdSolCons = tfd.CdSolCons
								WHERE sc.CdPaciente = $cdpac
								AND	(sc.CdEspecProc = $cdespec OR tfd.cdespectfd = $cdespec)
								AND (
										(
											(sc.`Status` = 1 AND ac.`Status` = 1) 
											OR (sc.`Status` = 1 AND ac.`Status` = 2) 
										)
										OR(
											(sc.`Status` = 'FC' AND tfd.`status` = 1) 
											OR (sc.`Status` = 'FC' AND tfd.`status` = 'R')
											)
									)
								ORDER BY ac.DtAgCons DESC, DtAgen DESC");
	$sqa = mysqli_fetch_array($sqatend);

	if ($sqa['DtAgCons'] != "" && $sqa['DtAgCons'] != null) {
		$dt = $sqa['DtAgCons'];
	} else {
		$dt = $sqa['DtAgen'];
	}

	//'�LTIMO ATENDIMENTO DIA '.
	return $dt;
}

function quantidadeCotasDistribuida($CodCota, $cdpref)
{
	$resultado = mysqli_query($GLOBALS['db'], "SELECT
					SUM(tbdistcotauni.qts) AS qts
					FROM
					tbdistcotauni
					INNER JOIN tbdistcota ON tbdistcotauni.CdDist = tbdistcota.CdDist
					WHERE tbdistcota.CdCota = {$CodCota}
					AND CdPref = $cdpref");

	$dados = mysqli_fetch_array($resultado);

	return $dados['qts'];
}

function GetUnidade($CdUsuario)
{
	$und = mysqli_query($GLOBALS['db'], "SELECT * FROM tbusuario
							WHERE CdUsuario = $CdUsuario");

	$l = mysqli_fetch_array($und);

	return $l['cdunidade'];
}

//EFETUA AGENDAMENTOS FORA DO CONS�RCIO (TFD)
function setagdfc($CdSolCons, $CdForn, $data, $hora, $obs)
{
	$dtinc 		= date('Y-m-d');
	$hrinc 		= date('H:i:s');
	$userinc 	= $_SESSION['CdUsuario'];

	$status 	= 1;

	$ver = mysqli_query($GLOBALS['db'], "SELECT * FROM tbagentfd WHERE CdSolCons = $CdSolCons");

	if (mysqli_num_rows($ver) > 0) {
		$agdfc = mysqli_query($GLOBALS['db'], "UPDATE `tbagentfd` SET 
									`DtAgen`	= '$data', 
									`HrAgen` 	= '$hora', 
									`Obs` 		= '$obs',
									`status` 	= '$status', 
									`Userinc` 	= '$userinc', 
									`Dtinc` 	= '$dtinc', 
									`Hrinc` 	= '$hrinc', 
									`CdFornfc` 	= '$CdForn'
							WHERE (`CdSolCons` = '$CdSolCons'	)");
	} else {
		$agdfc = mysqli_query($GLOBALS['db'], "INSERT INTO `tbagentfd` (`CdSolCons`,`DtAgen`,`HrAgen`,`Obs`,`status`,`Userinc`,`Dtinc`,`Hrinc`,`CdFornfc`) 
											VALUES ('$CdSolCons','$data','$hora','$obs','$status','$userinc','$dtinc','$hrinc','$CdForn')");
	}
}

function ultimoDiaDoMes($ano, $mes)
{
	$ultimoDia = date("t", mktime(0, 0, 0, $mes, '01', $ano));
	return $ultimoDia;
}

function quantidadeCotas($prefeitura, $codigo)
{
	$resultado = mysqli_query($GLOBALS['db'], "SELECT
						tbcotam.cdcota,
						tbcotam.cdpref,
						tbcotam.qts
						FROM
						tbcotam
						WHERE cdcota = {$codigo} and cdpref= {$prefeitura}");

	$dados = mysqli_fetch_array($resultado);

	return $dados['qts'];
}

/*PPI*/
/**
 * [getConfiguracao description]
 * @param  [type] $cdconfig [description]
 * @return [type]           [description]
 */
function getConfiguracao($cdconfig)
{
	$query = "SELECT* FROM tbconfig WHERE cdconfig = $cdconfig";

	$resultado = mysqli_query($GLOBALS['db'], $query) or die('RM - ' . mysqli_error());

	$l = mysqli_fetch_array($resultado, MYSQLI_ASSOC);

	return $l;
}
/**
 * [divStatus description]
 * @param  [type] $status [description]
 * @return [type]         [description]
 */
function divStatus($status)
{
	if ($status) {
		$ret = '<div id="status_off" title=""></div>';
	} else {
		$ret = '<div id="status_on" title=""></div>';
	}
	return $ret;
}
/**
 * [verificaSol description]
 * @param  [type] $CdForn   [description]
 * @param  [type] $idgenfat [description]
 * @return [type]           [description]
 */
function verificaSol($CdForn, $idgenfat)
{
	$data = verificaFat($idgenfat);
	$valida_user_mun = ($_SESSION['CdOrigem'] > 0) ? ' AND tbsolcons.CdPref =' . $_SESSION['CdOrigem'] : '';
	$query = "SELECT COUNT(tbsolcons.CdSolCons) AS qtd FROM tbsolcons INNER JOIN tbagendacons ON tbsolcons.CdSolCons = tbagendacons.CdSolCons
					WHERE tbagendacons.CdForn = {$CdForn} AND tbsolcons.`Status` = 1 AND tbagendacons.`Status`= 1
					AND tbagendacons.DtAgCons BETWEEN '$data[dtini]' AND '$data[dtfim]' $valida_user_mun";
	$resultado = mysqli_query($GLOBALS['db'], $query) or die('vSol - ' . mysqli_error());
	$l = mysqli_fetch_array($resultado, MYSQLI_ASSOC);
	return $l['qtd'];
}
function verificaSolR($CdForn, $idgenfat)
{
	$data = verificaFat($idgenfat);
	$valida_user_mun = ($_SESSION['CdOrigem'] > 0) ? ' AND tbsolcons.CdPref =' . $_SESSION['CdOrigem'] : '';
	$query = "SELECT COUNT(tbsolcons.CdSolCons) AS qtd FROM tbsolcons INNER JOIN tbagendacons ON tbsolcons.CdSolCons = tbagendacons.CdSolCons
					WHERE tbagendacons.CdForn = {$CdForn} AND tbsolcons.`Status` = 1 AND tbagendacons.`Status`= 2
					AND tbagendacons.DtAgCons BETWEEN '$data[dtini]' AND '$data[dtfim]' $valida_user_mun";
	$resultado = mysqli_query($GLOBALS['db'], $query) or die('vSol - ' . mysqli_error());
	$l = mysqli_fetch_array($resultado, MYSQLI_ASSOC);
	return $l['qtd'];
}
/**
 * [verificaFat description]
 * @param  [type] $idgenfat [description]
 * @return [type]           [description]
 */
function verificaFat($idgenfat)
{
	$query = "SELECT tbgenfat.idgenfat, tbgenfat.dtini, tbgenfat.dtfim FROM tbgenfat WHERE idgenfat = {$idgenfat}";
	$resultado = mysqli_query($GLOBALS['db'], $query) or die('vFat - ' . mysqli_error());
	$l = mysqli_fetch_array($resultado, MYSQLI_ASSOC);
	return $l;
}
/**
 * [contarSolAgendas description]
 * @param  [int] $solcons    [Status]
 * @param  [int] $agendacons [Status]
 * @param  [data] $datainicio [description]
 * @param  [data] $datafim    [description]
 * @param  [int] $cdforn     [description]
 * @return [int]             [description]
 */
function contarSolAgendas($solcons, $agendacons, $datainicio, $datafim, $cdforn)
{
	$valida_user_mun = ($_SESSION['CdOrigem'] > 0) ? ' AND sol.CdPref =' . $_SESSION['CdOrigem'] : '';
	$query = "SELECT count(sol.CdSolCons) AS qtd FROM tbsolcons AS sol 
				INNER JOIN tbagendacons AS agc ON sol.CdSolCons = agc.CdSolCons
				WHERE agc.CdForn = {$cdforn}
				AND sol.`Status` = '{$solcons}'
				AND agc.`Status` = '{$agendacons}'
				AND agc.DtAgCons BETWEEN '{$datainicio}' AND '{$datafim}'
				$valida_user_mun
				ORDER BY sol.CdSolCons ASC";
	$resultado = mysqli_query($GLOBALS['db'], $query) or die('Erro ao Contar - ' . mysqli_error());
	$l = mysqli_fetch_array($resultado, MYSQLI_ASSOC);
	return $l['qtd'];
}

function validafat($cd, $helper = NULL, $id = NULL)
{
	//if � usado somente no Fat. Fornecedor Por Municipio - Alterar(alt_fat_r.php) para que a valida��o n�o seja retirada.
	$genfat = getConfiguracao(8);
	if ($genfat['valor']) //vide tbconfig
	{
		if ($helper != 2) {
			$sql = mysqli_query($GLOBALS['db'], "SELECT ac.CdSolCons,ac.DtAgCons FROM tbagendacons AS ac WHERE ac.CdSolCons = '$cd' ") or die("Erro: " . mysqli_error());
			$l = mysqli_fetch_array($sql);
			$sql = mysqli_query($GLOBALS['db'], "SELECT g.idgenfat
			            FROM
			            tbgenfat AS g
			            WHERE '$l[DtAgCons]' BETWEEN g.dtini AND g.dtfim
			            AND g.estado = 'A'") or die("Erro: " . mysqli_error());
			if (mysqli_num_rows($sql) > 0) {
				$aux = ($helper == 1) ? '../' : "";
				$id = ($id != NULL) ?  "index.php?i=$id" : "index.php?i=6&op=2";
				echo "<script language=\"JavaScript\" type=\"text/javascript\">
			                    alert('Periodo de faturamento já fechado');
			                   	window.location.href = '$aux'+'$id';
			              </script>";
				return false;
			} else {
				return true;
			}
		}
		return true;
	} else {
		return true;
	}
}

function validafat_data($dtAgenda)
{
	$genfat = getConfiguracao(8);
	if ($genfat['valor']) //vide tbconfig
	{
		$sql = mysqli_query($GLOBALS['db'], "SELECT g.idgenfat
	                FROM
	                tbgenfat AS g
	                WHERE '$dtAgenda' BETWEEN g.dtini AND g.dtfim
	                AND g.estado = 'A'") or die("Erro: " . mysqli_error());
		if (mysqli_num_rows($sql) > 0) {
			echo "<script language=\"JavaScript\" type=\"text/javascript\">
	                        alert('Periodo de faturamento já fechado');
	                       
	                  </script>";
			return false;
		} else
			return true;
	} else {
		return true;
	}
}
/**
 * [setMovPPI]
 * @param  [int] $cdtetoppi [Status]
 * @param  [int] $cdsolcons [Status]
 */
function setMovPPI($cdtetoppi, $cdsolcons, $valor)
{
	$usuario = $_SESSION["CdUsuario"];
	$data	= date('Y-m-d H:i:s');
	$result = mysqli_query($GLOBALS['db'], "INSERT INTO `tbtetoppimov` (`cdtetoppi`, `cdsolcons`, `data`,`usuario`,`manual`) 
		VALUES ('$cdtetoppi', '$cdsolcons','$data','$usuario', 'N')") or die('smp - ' . mysqli_error());
	$cdtetoppimov = mysqli_insert_id();
	/*atualiza��o movppi*/
	$query_update = "UPDATE `tbagendacons` SET `valorppi`='$valor' WHERE `CdSolCons`='$cdsolcons'";
	$result = mysqli_query($GLOBALS['db'], $query_update) or die('setmovppisaldo - ' . mysqli_error());

	if ($result) {
		setLogMovPPI($cdtetoppimov, 'C');
		return 1;
	} else {
		return 0;
	}
}
/**
 * [remMovPPI description]
 * @param  [int] $cdtetoppimov [description]
 * @return [type]               [description]
 */
function remMovPPI($cdtetoppimov)
{
	$query_insert = "UPDATE `tbtetoppimov` SET `status`='0' WHERE `cdtetoppimov`='$cdtetoppimov'";
	$result = mysqli_query($GLOBALS['db'], $query_insert) or die('rmp - ' . mysqli_error());
	if ($result) {
		setLogMovPPI($cdtetoppimov, 'I');
		return 1;
	} else {
		return 0;
	}
}
function descobrecdtetoppimov($CdSolCons)
{
	$query = "SELECT
					tbtetoppimov.cdtetoppimov
					FROM tbtetoppimov
					WHERE tbtetoppimov.cdsolcons = $CdSolCons 
					AND tbtetoppimov.`status` = 1
					";
	//echo $query;
	$resultado = mysqli_query($GLOBALS['db'], $query) or die('dtppm - ' . mysqli_error());
	if ($resultado) {
		$l = mysqli_fetch_array($resultado, MYSQLI_ASSOC);
		return $l['cdtetoppimov'];
	} else {
		return 0;
	}
}
function remMovPPI2($CdSolCons)
{
	if ($cdtetoppimov = descobrecdtetoppimov($CdSolCons)) {
		$query_insert = "UPDATE `tbtetoppimov` SET `status`='0' WHERE `cdsolcons`='$CdSolCons' AND `status`= 1";
		$result = mysqli_query($GLOBALS['db'], $query_insert) or die('rmp - ' . mysqli_error());
		if ($result) {
			setLogMovPPI($cdtetoppimov, 'I');
			return 1;
		} else {
			return 0;
		}
	} else {
		return 0;
	}
}
/**
 * [setLogMovPPI description]
 * @param [int] $cdtetoppimov [description]
 * @param [char] $acao         [description]
 */
function setLogMovPPI($cdtetoppimov, $acao)
{
	$usuario   	= $_SESSION["CdUsuario"];
	$data		= date('Y-m-d H:i:s');
	$result = mysqli_query($GLOBALS['db'], "INSERT INTO `tbtetoppimovlog` (`cdtetoppimov`,`acao`,`data`,`usuario`) 
		VALUES ('$cdtetoppimov','$acao','$data','$usuario')") or die('logm - ' . mysqli_error());
	if ($result) {
		return 1;
	} else {
		return 0;
	}
}
/**
 * [getTotalMovPPI description]
 * @param  [type] $cdtetoppi [description]
 * @param  [type] $Status    [description]
 * @return [type]            [description]
 * atualizado busca o valor em tbagendacons.valorppi 10/10/2017
 */
function getTotalMovPPI($cdtetoppi, $Status = NULL)
{
	$Status = ($Status == 'M') ? ' AND agen.`Status`= 1' : (($Status == 'R') ? ' AND agen.`Status`= 2' : '');
	$query = "SELECT SUM(agen.valorppi) AS total 
				FROM tbtetoppimov AS tpm 
				INNER JOIN tbagendacons AS agen ON tpm.cdsolcons = agen.CdSolCons 
				WHERE tpm.`status` = 1 AND tpm.cdtetoppi = {$cdtetoppi} 
				{$Status}
				";
	$resultado = mysqli_query($GLOBALS['db'], $query) or die('tpm - ' . mysqli_error());

	$l = mysqli_fetch_array($resultado);

	if (isset($l['total'])) {
		$total = $l['total'];
	} else {
		$total = 0;
	}
	return $total;
}
function getTetoValor($cdtetoppi)
{
	$query = "SELECT teto.valor
				FROM tbtetoppinew AS teto
				WHERE teto.cdtetoppi = {$cdtetoppi} 
				";
	$resultado = mysqli_query($GLOBALS['db'], $query) or die('ttv - ' . mysqli_error());
	$l = mysqli_fetch_array($resultado);
	$valorCont = $l['valor'];
	return $valorCont;
}
function getSaldoPPI($cdtetoppi)
{
	$valorTeto = getTetoValor($cdtetoppi);
	$totalMov = getTotalMovPPI($cdtetoppi);
	return $valorTeto - $totalMov;
}
/**
 * [statusPPi description]
 * @param  [type] $cdespec [description]
 * @param  [type] $cdforn  [description]
 * @return [type]          [description]
 */
function statusPPi($cdespec = NULL, $cdforn = NULL)
{
	if ($cdespec) {
		if ($cdforn) {
			$query = "SELECT
							tbfornespec.CdForn,
							tbfornespec.CdEspec,
							tbfornespec.ppi
							FROM
							tbfornespec
							WHERE tbfornespec.CdForn = $cdforn 
							AND tbfornespec.CdEspec = $cdespec
							AND tbfornespec.ppi = 'S'
							LIMIT 1
						";
			$resultado = mysqli_query($GLOBALS['db'], $query) or die('stppiforn - ' . mysqli_error());
			if (mysqli_num_rows($resultado) > 0) {
				return 1;
			} else {
				return 0;
			}
		} else {
			$query = "SELECT
							tbespecproc.CdEspecProc,
							tbespecproc.ppi
							FROM
							tbespecproc
							WHERE CdEspecProc = $cdespec 
							AND ppi = 'S'
							LIMIT 1
						";
			$resultado = mysqli_query($GLOBALS['db'], $query) or die('stppiespec - ' . mysqli_error());
			if (mysqli_num_rows($resultado) > 0) {
				return 1;
			} else {
				return 0;
			}
		}
	} else {
		return 1;
	}
}
function getTetoPPICerto($cdpref, $valor, $data, $cdespec = NULL, $cdforn = NULL)
{
	$saida = 0;
	if (statusPPi($cdespec, $cdforn)) {
		$query = "SELECT teto.*,
						tbgenfat.dtini,
						tbgenfat.dtfim
						FROM
						tbtetoppinew AS teto
						INNER JOIN tbgenfat ON teto.idgenfat = tbgenfat.idgenfat
						WHERE teto.cdprefeitura = {$cdpref}
						AND '{$data}' BETWEEN tbgenfat.dtini AND tbgenfat.dtfim";
		//echo $query;
		$resultado = mysqli_query($GLOBALS['db'], $query) or die('ttc - ' . mysqli_error());
		if ($resultado) {
			while ($dados = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
				$total = getSaldoPPI($dados['cdtetoppi']);
				/**Alterado para nova regra**/
				//$total = $total-$valor;
				if ($total > 0) {
					$saida = $dados['cdtetoppi'];
					break;
				}
			}
		}
	}
	return $saida;
}
function getCompetencia($data, $vigencia = NULL)
{
	if ($vigencia == 'M') {
		$query = " SELECT tbgenfat.idgenfat FROM tbgenfat WHERE '{$data}' BETWEEN tbgenfat.dtini AND tbgenfat.dtfim ";
	} else if ($vigencia == 'A') {
		$query = " SELECT idgenfat FROM tbgenfat WHERE dtini = '{$data}-01-01' and dtfim = '{$data}-12-31'";
	} else {
		$query = " SELECT tbgenfat.idgenfat FROM tbgenfat WHERE '{$data}' BETWEEN tbgenfat.dtini AND tbgenfat.dtfim ";
	}

	$resultado = mysqli_query($GLOBALS['db'], $query) or die('ttc - ' . mysqli_error());
	$trows = mysqli_num_rows($resultado);
	if ($trows) {
		while ($dados = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
			$comp[] = $dados['idgenfat'];
		}
		return $comp[0];
	}
	return 0;
}
function getDataInicial($data)
{
	$query = "SELECT tbgenfat.idgenfat FROM tbgenfat WHERE '{$data}' BETWEEN tbgenfat.dtini AND tbgenfat.dtfim";
	$resultado = mysqli_query($GLOBALS['db'], $query) or die('ttc - ' . mysqli_error());
	$trows = mysqli_num_rows($resultado);
	if ($trows) {
		while ($dados = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
			$comp[] = $dados['idgenfat'];
		}
		return $comp[0];
	}
	return 0;
}

function saldo_municipio_estorno($CdSolCons)
{
	$query = "SELECT
					tbmovimentacao.CdSolCons,
					tbmovimentacao.DtMov,
					tbmovimentacao.Debito
					FROM
					tbmovimentacao
					WHERE tbmovimentacao.CdSolCons = $CdSolCons
					AND tbmovimentacao.TpMov = 'D'
					ORDER BY DtMov DESC
					LIMIT 0,1
					";
	$result = mysqli_query($GLOBALS['db'], $query) or die('sme - ' . mysqli_error());
	if ($result) {
		while ($dados = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			return $dados['Debito'];
		}
	} else {
		return 0;
	}
}
/**
 * [getDadosSolicitacao description]
 * @param  [type] $CdSolCons [description]
 * @return [type]            [description]
 */
function getDadosSolicitacao($CdSolCons)
{
	$query = "SELECT *,tbsolcons.Status AS StatusS FROM tbsolcons
					LEFT JOIN tbagendacons ON tbsolcons.CdSolCons = tbagendacons.CdSolCons
					WHERE tbsolcons.CdSolCons = $CdSolCons
					";
	$result = mysqli_query($GLOBALS['db'], $query) or die('sme - ' . mysqli_error());
	if ($result) {
		while ($dados = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			return $dados;
		}
	} else {
		return 0;
	}
}


/**
 * [getValorProcedimentoGlobal description]
 * @param  [type] $CdEspec [description]
 * @return [type]          [description]
 */
function getValorProcedimentoGlobal($CdEspec)
{
	$query = "SELECT
					tbespecproc.CdEspecProc,
					tbespecproc.valor,
					tbespecproc.valorsus,
					tbespecproc.`Status`
					FROM
					tbespecproc
					WHERE tbespecproc.CdEspecProc = {$CdEspec}
				";
	//echo $query;
	$resultado = mysqli_query($GLOBALS['db'], $query) or die('vprg - ' . mysqli_error());
	$numrow = mysqli_num_rows($resultado);
	if ($numrow) {
		while ($dados = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
			$valor['f'] = $dados['valorsus'];
			$valor['c'] = $dados['valor'];
		}
	} else {
		$valor['f'] = 0;
		$valor['c'] = 0;
	}
	return $valor;
}
/**
 * [getValorProcedimento description]
 * @param  [type] $CdEspec      [description]
 * @param  [type] $cdfornecedor [description]
 * @return [type]               [description]
 */
function getValorProcedimento($CdEspec, $cdfornecedor = NULL)
{
	$validafornecedor = ($cdfornecedor) ? " AND tbfornespec.CdForn = $cdfornecedor " : "";
	$query = "SELECT tbfornespec.valorf,tbfornespec.valorc
				FROM tbfornespec
				WHERE tbfornespec.CdEspec = {$CdEspec}
				$validafornecedor
				AND tbfornespec.`Status` = 1 
				ORDER BY tbfornespec.dtinc DESC 
				LIMIT 0,1
				";
	//echo $query;
	$resultado = mysqli_query($GLOBALS['db'], $query) or die('vpr - ' . mysqli_error($GLOBALS['db']));
	$numrow = mysqli_num_rows($resultado);
	if ($numrow) {
		while ($dados = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
			if ($dados['valorf'] && $dados['valorc']) {
				$valor['f'] = $dados['valorf'];
				$valor['c'] = $dados['valorc'];
			} else {
				$valorg = getValorProcedimentoGlobal($CdEspec);
				$valor['f'] = $valorg['f'];
				$valor['c'] = $valorg['c'];
			}
		}
	} else {
		$valorg = getValorProcedimentoGlobal($CdEspec);
		$valor['f'] = $valorg['f'];
		$valor['c'] = $valorg['c'];
	}
	return $valor;
}
/**
 * [lancamentoSemAgenda description]
 * @param  [int] $CdPaciente   [description]
 * @param  [int] $CdEspecProc  [description]
 * @param  [date] $DtAgCons     [description]
 * @param  [time] $HoraAgCons   [description]
 * @param  [int] $select_forne [description]
 * @param  [int] $CdPref       [description]
 * @param  [decimal] $valor        [description]
 * @param  [decimal] $valorsus     [description]
 * @param  [string] $marcacao     [description]
 * @param  [string] $Obs          [description]
 * @param  [int] $Urgente      [description]
 * @param  [int] $pactuacao    [description]
 * @param  [int] $retorno      [description]
 * @param  [int] $remarcado    [description]
 * @return [int]               [description]
 */
function lancamentoSemAgenda($CdPaciente, $CdEspecProc, $DtAgCons, $HoraAgCons, $select_forne, $CdPref, $valor, $valorsus, $marcacao = NULL, $Obs = NULL, $Urgente = NULL, $pactuacao = NULL, $retorno = NULL, $remarcado = NULL)
{
	$CdSolCons = mysqli_query($GLOBALS['db'], "SELECT max(tbsolcons.CdSolCons) as cd FROM tbsolcons");
	$l = mysqli_fetch_array($CdSolCons);
	$CdSolCons = $l["cd"] + 1;

	$qryprof = mysqli_query($GLOBALS['db'], "SELECT cdprof FROM tbfornespec where CdEspec = '$CdEspecProc' and CdForn = '$select_forne'");
	$p = mysqli_fetch_array($qryprof);
	$cdprof = $p["cdprof"];

	$Protocolo  = date("Ymd") . $CdPaciente . "-" . $CdSolCons;
	$dtinc = date('Y-m-d');
	$hrinc = date('H:i:s');
	$dtm = $dtinc;
	$hrm = $hrinc;
	$userinc = $_SESSION['CdUsuario'];
	$CdUser = $userinc;
	$userm = $userinc;
	$qts2 = 1;
	$obs = "LANÇAMENTO PRODUÇÃO";
	$Status = ($marcacao == "real") ? "2" : "1";

	$protocolopac 	= gera_protocolo();

	$sol = "INSERT INTO tbsolcons (CdSolCons,CdPaciente,CdEspecProc,CdUsuario,Protocolo,Obs1,Urgente, pactuacao,retorno,remarcado,dtinc,userinc,hrinc,CdPref,userm,dtm,hrm) 
			VALUES ($CdSolCons,$CdPaciente,$CdEspecProc,$CdUser,'$Protocolo','$Obs','$Urgente','$pactuacao','$retorno','$remarcado','$dtinc','$userinc','$hrinc','$CdPref','$userm','$dtm','$hrm')";
	$sql1 = mysqli_query($GLOBALS['db'], $sol) or die('solicita - ' . mysqli_error());

	if ($sql1) {
		$agen = "INSERT INTO `tbagendacons` (`DtAgCons`,`HoraAgCons`,`CdSolCons`,`CdForn`,`CdUsuario`,`valor`,`valor_sus`,`qts`,`obs`,`protocolopac`,`Status`,`cdprof`) 
							VALUES ('$DtAgCons','$HoraAgCons:00','$CdSolCons','$select_forne','$CdUser','$valor','$valorsus','$qts2','$obs','$protocolopac','$Status',$cdprof)";
		$sql2 = mysqli_query($GLOBALS['db'], $agen) or die('agendamento - ' . mysqli_error());
		if ($sql2) {
			return $CdSolCons;
		} else {
			return 0;
		}
	} else {
		return 0;
	}
}
/**
 * [getNomeProcedimentos description]
 * @param  [int] $array [description]
 * @return [type]        [description]
 */
function getNomeProcedimentos($array)
{
	$lista = implode(',', $array);
	$query = "SELECT
				tbespecproc.CdEspecProc,
				tbespecproc.NmEspecProc
				FROM
				tbespecproc
				WHERE tbespecproc.CdEspecProc IN ($lista)
				ORDER BY tbespecproc.NmEspecProc ASC
				";
	$resultado = mysqli_query($GLOBALS['db'], $query) or die('nproc - ' . mysqli_error());
	$numrow = mysqli_num_rows($resultado);
	if ($numrow) {
		while ($dados = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
			$teste .= $dados['NmEspecProc'];
			$teste .= ', ';
		}
	}
	return $teste;
}
/*PPI*/

function getNmCidade($CdPref)
{
	$query = "SELECT * FROM tbprefeitura WHERE CdPref = $CdPref";
	$result = mysqli_query($GLOBALS['db'], $query) or die('gNmCidade - ' . mysqli_error());
	if ($result) {
		while ($dados = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			return $dados;
		}
	} else {
		return 0;
	}
}
/**
 * [getComboOftalmo description]
 * @param  [type] $CdSolCons [description]
 * @return [type]            [description]
 */
function getComboOftalmo($CdSolCons)
{
	$query = "SELECT tbsolcons.comboOftalmo FROM tbsolcons where tbsolcons.CdSolCons = $CdSolCons";
	$result = mysqli_query($GLOBALS['db'], $query) or die('ComboOftalmo - ' . mysqli_error());
	if ($result) {
		while ($dados = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			return $dados['comboOftalmo'];
		}
	} else {
		return 0;
	}
}
/**
 * [getCdSolConsComboOftalmo description]
 * @param  [type] $comboOftalmo [description]
 * @param  [type] $CdEspecProc  [description]
 * @return [type]               [description]
 */
function getCdSolConsComboOftalmo($comboOftalmo, $CdEspecProc)
{
	$query = "SELECT tbsolcons.CdSolCons FROM tbsolcons where tbsolcons.comboOftalmo = $comboOftalmo AND tbsolcons.CdEspecProc = $CdEspecProc";
	$result = mysqli_query($GLOBALS['db'], $query) or die('ComboOftalmo - ' . mysqli_error());
	if ($result) {
		while ($dados = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			return $dados['CdSolCons'];
		}
	} else {
		return 0;
	}
}

function set_falta($cd)
{
	if (validafat($cd, 1)) {
		$usercanc = $_SESSION['CdUsuario'];
		$dados =  getDadosSolicitacao($cd);
		if ($dados['StatusS'] == 1) {
			$sql = "UPDATE tbsolcons SET `Status` = 'F' WHERE CdSolCons = $cd";
			$result = mysqli_query($GLOBALS['db'], $sql) or die("Erro set_falta");
			$sql = mysqli_query($GLOBALS['db'], "INSERT INTO tblogag (tipo,dtalt,cdag) VALUES('Falta','" . date("Y-m-d H:i:s") . "','$cd')");
			if ($result) {
				/*if (descobrecdtetoppimov($cd)) {
							remMovPPI2($cd);
							$estorno = saldo_municipio_estorno($cd);
							if ($estorno) {
								$CdPref = $dados[CdPref];
								set_mov($CdPref,$usercanc,$cd,'C',0,$estorno);
							}
						}else{
							$estorno = saldo_municipio_estorno($cd);
							if ($estorno) {
								$CdPref = $dados[CdPref];
								set_mov($CdPref,$usercanc,$cd,'C',0,$estorno);
							}
						}*/
				voltarTriagemCem($cd);
				return 1;
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	} else {
		return 0;
	}
}
function set_voltar($cd, $aba)
{
	if (validafat($cd, 1)) {
		$usr = (int)$_SESSION["CdUsuario"];
		$dt = date("Y-m-d") . " " . date("H:i:s");

		$dadosS = getDadosSolicitacao($cd);
		if ($dadosS['StatusS'] != 1) {
			$conficota = getConfiguracao(9);

			if (($conficota['valor']) and ($dadosS['cdcota'] != NULL)) {
				$disponivel = cotaDisponivel($dadosS['cdcota'], $dadosS['CdPref']);
			} else {
				$disponivel = 1;
			}
			if ($disponivel > 0) {
				$ppi = getTetoPPICerto($dadosS['CdPref'], $dadosS['valor'], $dadosS['DtAgCons'], $dadosS['CdEspecProc']);
				$saldoppi = getSaldoPPI($ppi);
				$saldomun = saldo_municipio($dadosS['CdPref'], 1);
				$saldomm = $saldomun - $dadosS['valor'];

				$validasaldo = $saldoppi + $saldomun;
				if ($validasaldo >= $dadosS['valor']) {
					$sc = mysqli_query($GLOBALS['db'], "UPDATE tbsolcons SET Status='1',usrret='$usr',dtret='$dt',cdmotcanc=0 WHERE CdSolCons = $cd") or die('set_voltar');
					$ag = mysqli_query($GLOBALS['db'], "UPDATE tbagendacons SET Status='1' WHERE CdSolCons = $cd") or die('set_voltar');

					if ($ppi) {
						if ($saldoppi >= $dadosS['valor']) {
							$ret = setMovPPI($ppi, $cd, $dadosS['valor']);
						} else {
							$ret = setMovPPI($ppi, $cd, $saldoppi);
							$movprateio = $dadosS['valor'] - $saldoppi;
							$ret = set_mov($dadosS['CdPref'], $usr, $cd, 'D', $movprateio, 0);
						}
					} elseif ($saldomm >= 0) {
						$TpMov = 'D';
						$Debito = $dadosS['valor'];
						$ret = set_mov($dadosS['CdPref'], $usr, $cd, $TpMov, $Debito, 0);
					}
					return 1;
				} else {
					return 0;
				}
			} else {
				return 0;
			}
		} else {
			$sc = mysqli_query($GLOBALS['db'], "UPDATE tbsolcons SET Status='1',usrret='$usr',dtret='$dt',cdmotcanc=0 WHERE CdSolCons = $cd") or die('set_voltar');
			$ag = mysqli_query($GLOBALS['db'], "UPDATE tbagendacons SET Status='1' WHERE CdSolCons = $cd") or die('set_voltar');
			return 1;
		}
	} else {
		return 0;
	}
}
/**
 * Inserir dados em tabelas
 * 09/11/2017
 * @param  [type] $tabela [description]
 * @param  array  $dados  [description]
 * @return [type]         [description]
 */
function create($tabela, array $dados)
{
	$campos = implode(", ", array_keys($dados));
	$values = "'" . implode("', '", array_values($dados)) . "'";
	$query = "INSERT INTO {$tabela} ($campos) VALUES ($values)";
	$result = mysqli_query($GLOBALS['db'], $query) or die('Erro ao cadastrar em ' . $tabela . ' ' . mysqli_error($GLOBALS['db']));
	if ($result) {
		return mysqli_insert_id($GLOBALS['db']);
	}
}
/**
 * Atualizar dados em tabelas
 * 09/11/2017
 * @param  [type] $tabela [description]
 * @param  array  $dados  [description]
 * @param  [type] $where  [description]
 * @return [type]         [description]
 */
function update($tabela, array $dados, $where)
{
	foreach ($dados as $campo => $values) {
		$campos[] = "$campo = '$values'";
	}
	$campos = implode(", ", $campos);
	$query = "UPDATE {$tabela} SET $campos WHERE {$where}";
	$result = mysqli_query($GLOBALS['db'], $query) or die('Erro ao atualizar em ' . $tabela . ' ' . mysqli_error() . ' ' . mysqli_errno());
	if ($result) {
		return true;
	}
}
/**
 * [delete description]
 * @param  [type] $tabela [description]
 * @param  [type] $where  [description]
 * @return [type]         [description]
 */
function delete($tabela, $where)
{
	$qrDelete = "DELETE FROM {$tabela} WHERE {$where}";
	$stDelete = mysqli_query($GLOBALS['db'], $qrDelete) or die('Erro ao deletar em ' . $tabela . ' ' . mysqli_error());
}
/**
 * [validaDadosFormRefCeae description]
 * @param  [type] $dado [description]
 * @param  [type] $tipo [description]
 * @return [type]       [description]
 */
function validaDadosFormRefCeae($dado, $tipo)
{
	if ($dado) {
		switch ($tipo) {
			case 'chk':
				$dado = 'checked';
				break;
			default:
				break;
		}
		echo $dado;
	} else {
		echo '';
	}
}
/**
 * [logFormRefCeae description]
 * @param  [type] $cdreferencia [description]
 * @param  [type] $acao         [description]
 * @return [type]               [description]
 */
function logFormRefCeae($cdreferencia, $acao)
{
	$usuario  = $_SESSION["CdUsuario"];
	$data	= date('Y-m-d H:i:s');
	$dados = array('cdreferenciaceae' => $cdreferencia, 'acao' => $acao, 'data' => $data, 'usuario' => $usuario);
	return create("tbceaereferencialog", $dados);
}
/**
 * [getFormRefCeaeIrregularidade description]
 * @param  [type] $cdreferenciaceae [description]
 * @return [type]                   [description]
 */
function getFormRefCeaeIrregularidade($cdreferenciaceae)
{
	$retorno = readJoin('tbnotificacaoceae.irregularidade', 'tbnotificacaoceae', 'WHERE cdReferencia = ' . $cdreferenciaceae);
	if ($retorno) {
		foreach ($retorno as $dados) :
			$saida = $dados['irregularidade'];
		endforeach;
	}
	return $saida;
}
function getFormRefCeaeLayout($cdreferenciaceae)
{
	$retorno = readJoin('CdEspecProc', 'tbceaereferencia', 'WHERE cdreferenciaceae = ' . $cdreferenciaceae);
	foreach ($retorno as $dados);
	return descobreForm($dados['CdEspecProc']);
}
function limpaFormRefCeae($cdreferenciaceae)
{
	$arquivo = getFormRefCeaeLayout($cdreferenciaceae);
	$retorno = readJoin('*', $arquivo['tbvivavida'], 'WHERE cdreferenciaceae = ' . $cdreferenciaceae);
	foreach ($retorno as $dados);

	foreach ($dados as $key => $value) {
		$dados[$key] = NULL;
	}

	return $dados;
}
function verificaRefPorPaciente($CdPaciente, $CdEspecProc)
{
	$query = "SELECT count(cdreferenciaceae) AS qtd FROM tbceaereferencia WHERE CdPaciente = $CdPaciente AND CdEspecProc = $CdEspecProc AND statustratamento = 'EA'";
	$result = mysqli_query($GLOBALS['db'], $query) or die('verificaRefPorPaciente - ' . mysqli_error());
	if ($result) {
		while ($dados = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			return $dados['qtd'];
		}
	} else {
		return 0;
	}
}
function listarMenuProntuario(array $cdgrusuario)
{
	$values = implode(",", array_values($cdgrusuario));
	$query = "SELECT
						tbprtmenupermissao.cdprtmenu,
						tbprtmenupermissao.cdgrusuario,
						tbprtmenu.nome,
						tbprtmenu.caminho,
						tbprtmenu.listar,
						tbprtmenu.`status`
						FROM
						tbprtmenupermissao
						INNER JOIN tbprtmenu ON tbprtmenupermissao.cdprtmenu = tbprtmenu.cdprtmenu
						WHERE tbprtmenupermissao.cdgrusuario IN ($values) AND tbprtmenu.`status` = 1 AND tbprtmenu.listar = 1
						GROUP BY tbprtmenu.cdprtmenu
						ORDER BY tbprtmenu.nome ASC";
	$result = mysqli_query($GLOBALS['db'], $query) or die('Erro ao listar menu prontuario: ' . mysqli_error());
	if (mysqli_num_rows($result) > 0) {
		while ($dado = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$dados[] = $dado;
		}
		return $dados;
	} else {
		return 0;
	}
}
function acessarMenuProntuario(array $cdgrusuario, $cdprtmenu)
{
	$values = implode(",", array_values($cdgrusuario));
	$query = "SELECT
						tbprtmenupermissao.cdprtmenu,
						tbprtmenupermissao.cdgrusuario,
						tbprtmenu.nome,
						tbprtmenu.caminho,
						tbprtmenu.listar,
						tbprtmenu.`status`
						FROM
						tbprtmenupermissao
						INNER JOIN tbprtmenu ON tbprtmenupermissao.cdprtmenu = tbprtmenu.cdprtmenu
						WHERE tbprtmenupermissao.cdprtmenu = $cdprtmenu AND tbprtmenupermissao.cdgrusuario IN ($values) AND tbprtmenu.`status` = 1
						GROUP BY tbprtmenu.cdprtmenu";
	$result = mysqli_query($GLOBALS['db'], $query) or die('Erro ao listar menu prontuario: ' . mysqli_error());
	if (mysqli_num_rows($result) > 0) {
		while ($dados = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			return $dados;
		}
	} else {
		return 0;
	}
}

function dados_consorcio($tipo)
{
	$query = mysqli_query($GLOBALS['db'], "SELECT CdDadosConsorcio, Tipo, Nome, Titulo, Endereco, Numero, Bairro, Cidade, Estado, CEP, Telefone, Email, Imagem, CNES, CNPJ
			      FROM tbdadosconsorcio where Tipo = '" . strtoupper($tipo) . "' ");

	return mysqli_fetch_array($query);
}

function getCotaMunicipio($cdcota, $cdpref)
{
	$retorno = readJoin('tbcotam.qts', 'tbcotam', 'WHERE cdcota=' . $cdcota . ' AND cdpref=' . $cdpref . ' ');
	if ($retorno) {
		foreach ($retorno as $dados) :
			$saida = $dados['qts'];
		endforeach;
	}
	return $saida;
}
function getCotaDados($cdcota)
{
	$query = mysqli_query($GLOBALS['db'], "SELECT
									tbespecproc.NmEspecProc,
									tbfornecedor.NmForn,
									tbcotatipo.nome,
									tbgenfat.dtini,
									tbgenfat.dtfim,
									date_format(tbgenfat.dtini,'%d/%m/%Y') as `dtinibr`,
									date_format(tbgenfat.dtfim,'%d/%m/%Y') as `dtfimbr`,
									tbprofissional.nmprof,
									tbcota.cdcota,
									tbcota.cdcotatipo,
									tbcota.idgenfat,
									tbcota.CdEspecProc,
									tbcota.CdForn,
									tbcota.cdprof,
									tbcota.`status`
									FROM
									tbcota
									INNER JOIN tbespecproc ON tbcota.CdEspecProc = tbespecproc.CdEspecProc
									LEFT JOIN tbfornecedor ON tbcota.CdForn = tbfornecedor.CdForn
									INNER JOIN tbcotatipo ON tbcota.cdcotatipo = tbcotatipo.cdcotatipo
									INNER JOIN tbgenfat ON tbcota.idgenfat = tbgenfat.idgenfat
									LEFT JOIN tbprofissional ON tbcota.cdprof = tbprofissional.cdprof
									WHERE tbcota.cdcota = $cdcota
									");

	return mysqli_fetch_array($query, MYSQLI_ASSOC);
}
/**
 * [getCotaGastas description]
 * @param  [type] $cdcota [description]
 * @param  [type] $cdpref [description]
 * @return [type]         [description]
 */
function getCotaGastas($cdcota, $cdpref)
{
	$query = "SELECT
					COUNT(tbagendacons.CdSolCons) as qts
					FROM
					tbsolcons
					INNER JOIN tbagendacons ON tbsolcons.CdSolCons = tbagendacons.CdSolCons
					WHERE tbsolcons.`Status` = 1 
					AND tbsolcons.CdPref = $cdpref
					AND tbsolcons.cdcota = $cdcota
					AND tbsolcons.TpAgen != 'und'
					";
	//echo $query;
	//echo'<br/>';
	$result = mysqli_query($GLOBALS['db'], $query);

	if (mysqli_num_rows($result) > 0) {
		while ($dado = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$qts = $dado['qts'];
		}
		return $qts;
	} else {
		return 0;
	}
}
function getAgendaFornDados($cdagenda_fornecedor)
{
	$query = mysqli_query($GLOBALS['db'], "SELECT
									tbagenda_fornecedor.cdagenda_fornecedor,
									tbagenda_fornecedor.cdfornecedor,
									tbagenda_fornecedor.cdprof,
									tbagenda_fornecedor.cdprocedimento,
									tbagenda_fornecedor.cdespecificacao,
									tbagenda_fornecedor.cdpref,
									tbagenda_fornecedor.hora,
									tbagenda_fornecedor.`data`,
									date_format(tbagenda_fornecedor.`data`,'%d/%m/%Y') as `databr`,
									tbagenda_fornecedor.`status`,
									tbagenda_fornecedor.obs,
									tbfornecedor.NmForn,
									tbprofissional.nmprof,
									tbprocedimento.NmProcedimento,
									tbespecproc.NmEspecProc
									FROM
									tbagenda_fornecedor
									LEFT JOIN tbfornecedor ON tbagenda_fornecedor.cdfornecedor = tbfornecedor.CdForn
									LEFT JOIN tbprofissional ON tbagenda_fornecedor.cdprof = tbprofissional.cdprof
									INNER JOIN tbprocedimento ON tbagenda_fornecedor.cdprocedimento = tbprocedimento.CdProcedimento
									INNER JOIN tbespecproc ON tbagenda_fornecedor.cdespecificacao = tbespecproc.CdEspecProc
									WHERE
									tbagenda_fornecedor.cdagenda_fornecedor = $cdagenda_fornecedor");

	return mysqli_fetch_array($query, MYSQLI_ASSOC);
}
/**
 * [estornaAgendaFornecedor description]
 * @param  [type] $cdagenda_fornecedor [description]
 * @return [type]                      [description]
 */
function estornaAgendaFornecedor($cdagenda_fornecedor)
{
	/*#7840 Solicita estorno para munic�pio a qual pertencia a agenda*/
	$ddagforn = getAgendaFornDados($cdagenda_fornecedor);
	if ($ddagforn['status'] != 'C') {
		$usrinc = $_SESSION['CdUsuario'];
		$dtinc 	= date('Y-m-d H:i:s');
		$sql_1 = mysqli_query($GLOBALS['db'], "UPDATE `tbagenda_fornecedor` SET `status`='C' WHERE `cdagenda_fornecedor`='$cdagenda_fornecedor' ") or die(mysqli_error());
		if ($sql_1) {
			if ($ddagforn['data'] > date('Y-m-d')) {
				$query_inserir = "INSERT INTO `tbagenda_fornecedor` (`cdfornecedor`, 
																		`data`, 
																		`hora`, 
																		`cdprocedimento`,
																		`cdprof`,
																		`cdespecificacao`,
																		`cdpref`,
																		`obs`,
																		`usrinc`,
																		`dtinc`)
									VALUES ('$ddagforn[cdfornecedor]',
											'$ddagforn[data]',
											'$ddagforn[hora]',
											'$ddagforn[cdprocedimento]',
											NULLIF('$ddagforn[cdprof]',''),
											'$ddagforn[cdespecificacao]',
											'$ddagforn[cdpref]',
											'$ddagforn[obs]',
											'$usrinc',
											'$dtinc')";
				$sql_2 = mysqli_query($GLOBALS['db'], $query_inserir) or die(mysqli_error());
			}
			return 1;
		} else {
			return 0;
		}
	} else {
		return 0;
	}
}
/**
 * [cotaDisponivel description]
 * @param  [type] $cdcota [description]
 * @param  [type] $cdpref [description]
 * @return [type]         [description]
 */
function cotaDisponivel($cdcota, $cdpref)
{
	$cotaMun 			= getCotaMunicipio($cdcota, $cdpref);
	$cotaGasta 			= getCotaGastas($cdcota, $cdpref);
	$cotaDistribuida 	= quantidadeCotasDistribuida($cdcota, $cdpref);
	$disponivel 		= $cotaMun - $cotaGasta - $cotaDistribuida;
	return $disponivel;
}
function getStatusRef($status)
{
	$nome = array('I' => 'Irregular', 'C' => 'Cancelado', 'R' => 'Regular', 'N' => 'Novo');
	return $nome[$status];
}
/**
 * [setTriagemCemLog description]
 * @param [type] $cdcemtriagem [description]
 * @param [type] $acao         [description]
 */
function setTriagemCemLog($cdcemtriagem, $acao)
{
	$data = date('Y-m-d H:i:s');
	$usuario = $_SESSION['CdUsuario'];
	$query_inserir_log = "INSERT INTO `tbcemtriagemlog`(cdcemtriagem,data,acao,usuario) VALUES ('$cdcemtriagem','$data','$acao','$usuario')";
	$result = mysqli_query($GLOBALS['db'], $query_inserir_log) or die(mysqli_error());
	return $result;
}

/**
 * [setTriagemCem description]
 * @param [type] $cdsolcons [description]
 */
function setTriagemCem($cdsolcons)
{
	$retorno = readJoin('cdcemtriagem', 'tbcemtriagem', 'WHERE cdsolcons = ' . $cdsolcons);
	if ($retorno) {
		foreach ($retorno as $dados) :
			$saida = $dados['cdcemtriagem'];
		endforeach;
	} else {
		$saida = 0;
	}
	if ($saida) {
		$cdcemtriagem = $saida;
		$query_update_triagem = "UPDATE `tbcemtriagem` SET `status`=1 WHERE cdsolcons = $cdsolcons AND cdcemtriagem = $cdcemtriagem";
		$result = mysqli_query($GLOBALS['db'], $query_update_triagem) or die(mysqli_error());
	} else {
		$query_inserir_triagem = "INSERT INTO `tbcemtriagem`(cdsolcons) VALUES ('$cdsolcons')";
		$result = mysqli_query($GLOBALS['db'], $query_inserir_triagem) or die(mysqli_error());
		$cdcemtriagem = mysqli_insert_id();
	}
	$acao = 'N';
	if ($result) {
		setTriagemCemLog($cdcemtriagem, $acao);
		return 1;
	} else {
		return 0;
	}
}
/**
 * [voltarTriagemCem description]
 * @param  [type] $cdsolcons    [description]
 * @param  [type] $cdcemtriagem [description]
 * @return [type]               [description]
 */
function voltarTriagemCem($cdsolcons)
{
	$retorno = readJoin('cdcemtriagem', 'tbcemtriagem', 'WHERE cdsolcons = ' . $cdsolcons);
	if ($retorno) {
		foreach ($retorno as $dados) :
			$cdcemtriagem = $dados['cdcemtriagem'];
		endforeach;
	} else {
		$cdcemtriagem = 0;
	}
	if ($cdcemtriagem) {
		$query_update = "UPDATE `tbcemtriagem` SET `status`=0 WHERE cdsolcons = $cdsolcons";
		$result = mysqli_query($GLOBALS['db'], $query_update) or die(mysqli_error());
		$acao = 'I';
		if ($result) {
			setTriagemCemLog($cdcemtriagem, $acao);
			return 1;
		} else {
			return 0;
		}
	} else {
		return 0;
	}
}
/**
 * [statusAtdTriagemCem description]
 * @param  [type] $cdsolcons [description]
 * @param  [type] $acao      [description]
 * @return [type]            [description]
 */
function statusAtdTriagemCem($cdsolcons, $acao)
{
	$retorno = readJoin('cdcemtriagem', 'tbcemtriagem', 'WHERE cdsolcons = ' . $cdsolcons);
	if ($retorno) {
		foreach ($retorno as $dados) :
			$cdcemtriagem = $dados['cdcemtriagem'];
		endforeach;
	} else {
		$cdcemtriagem = 0;
	}
	if ($cdcemtriagem) {
		$query_update = "UPDATE `tbcemtriagem` SET `statusAtendimento`='$acao' WHERE cdsolcons = $cdsolcons";
		$result = mysqli_query($GLOBALS['db'], $query_update) or die(mysqli_error());
		if ($result) {
			setTriagemCemLog($cdcemtriagem, $acao);
			return 1;
		} else {
			return 0;
		}
	} else {
		return 0;
	}
}

/**
 * [setTriagemCeaeLog description]
 * @param [type] $cdceaetriagem [description]
 * @param [type] $acao          [description]
 */
function setTriagemCeaeLog($cdceaetriagem, $acao)
{
	$data = date('Y-m-d H:i:s');
	$usuario = $_SESSION['CdUsuario'];
	$query_inserir_log = "INSERT INTO `tbceaetriagemlog`(cdceaetriagem,data,acao,usuario) VALUES ('$cdceaetriagem','$data','$acao','$usuario')";
	$result = mysqli_query($GLOBALS['db'], $query_inserir_log) or die(mysqli_error());
	return $result;
}
/**
 * [setTriagemCeae description]
 * @param [type] $cdagvivavida [description]
 */
function setTriagemCeae($cdagvivavida)
{
	$retorno = readJoin('cdceaetriagem', 'tbceaetriagem', 'WHERE cdagvivavida = ' . $cdagvivavida);
	if ($retorno) {
		foreach ($retorno as $dados) :
			$saida = $dados['cdceaetriagem'];
		endforeach;
	} else {
		$saida = 0;
	}
	if ($saida) {
		$cdceaetriagem = $saida;
		$query_update_triagem = "UPDATE `tbceaetriagem` SET `status`=1 WHERE cdagvivavida = $cdagvivavida AND cdceaetriagem = $cdceaetriagem";
		$result = mysqli_query($GLOBALS['db'], $query_update_triagem) or die(mysqli_error());
	} else {
		$query_inserir_triagem = "INSERT INTO `tbceaetriagem`(cdagvivavida) VALUES ('$cdagvivavida')";
		$result = mysqli_query($GLOBALS['db'], $query_inserir_triagem) or die(mysqli_error());
		$cdceaetriagem = mysqli_insert_id();
	}
	$acao = 'N';
	if ($result) {
		setTriagemCemLog($cdceaetriagem, $acao);
		return 1;
	} else {
		return 0;
	}
}
/**
 * [voltarTriagemCeae description]
 * @param  [type] $cdsolcons     [description]
 * @param  [type] $cdceaetriagem [description]
 * @return [type]                [description]
 */
function voltarTriagemCeae($cdagvivavida)
{
	$retorno = readJoin('cdceaetriagem', 'tbceaetriagem', 'WHERE cdagvivavida = ' . $cdagvivavida);
	if ($retorno) {
		foreach ($retorno as $dados) :
			$cdceaetriagem = $dados['cdceaetriagem'];
		endforeach;
	} else {
		$cdceaetriagem = 0;
	}
	if ($cdceaetriagem) {
		$query_update = "UPDATE `tbceaetriagem` SET `status`=0 WHERE cdagvivavida = $cdagvivavida";
		$result = mysqli_query($GLOBALS['db'], $query_update) or die(mysqli_error());
		$acao = 'I';
		if ($result) {
			setTriagemCeaeLog($cdceaetriagem, $acao);
			return 1;
		} else {
			return 0;
		}
	} else {
		return 0;
	}
}
/**
 * [statusAtdTriagemCeae description]
 * @param  [type] $cdagvivavida [description]
 * @param  [type] $acao         [description]
 * @return [type]               [description]
 */
function statusAtdTriagemCeae($cdagvivavida, $acao)
{
	$retorno = readJoin('cdceaetriagem', 'tbceaetriagem', 'WHERE cdagvivavida = ' . $cdagvivavida);
	if ($retorno) {
		foreach ($retorno as $dados) :
			$cdceaetriagem = $dados['cdceaetriagem'];
		endforeach;
	} else {
		$cdceaetriagem = 0;
	}
	if ($cdceaetriagem) {
		$query_update = "UPDATE `tbceaetriagem` SET `statusAtendimento`='$acao' WHERE cdagvivavida = $cdagvivavida";
		$result = mysqli_query($GLOBALS['db'], $query_update) or die(mysqli_error());
		if ($result) {
			setTriagemCemLog($cdceaetriagem, $acao);
			return 1;
		} else {
			return 0;
		}
	} else {
		return 0;
	}
}
/**
 * [statusTriagemAtendimento description]
 * @param  [type] $status [description]
 * @return [type]         [description]
 */
function statusTriagemAtendimento($status)
{
	if ($status) {
		$array = array('A' => 'Aguardando', 'E' => 'Em Atendimento', 'F' => 'Finalizado', 'B' => 'Bloqueado', 'N' => 'Novo', 'I' => 'Inativo', 'P' => 'Prioridade', 'D' => 'Sem Prioridade');
		return $array[$status];
	} else {
		return '';
	}
}
/*funcoes do CISTM*/
/**
 * [getValuesMovimentacao description]
 * @param  [type] $CdMov  [description]
 * @param  [type] $filtro [description]
 * @return [type]         [description]
 */
function getValuesMovimentacao($CdMov, $filtro = NULL)
{
	$filtro = ($filtro) ? " mov.CdSolCons = 0 AND " : "";
	$query = "SELECT * FROM tbmovimentacao AS mov WHERE {$filtro} mov.CdMov = {$CdMov}";
	$resultado = mysqli_query($GLOBALS['db'], $query) or die(mysqli_error());
	$l = mysqli_fetch_array($resultado, MYSQLI_ASSOC);
	return $l;
}
/**
 * [logMovimentacao description]
 * @param  [type] $CdMov     [description]
 * @param  [type] $TpMov     [description]
 * @param  [type] $CdPref    [description]
 * @param  [type] $Valor     [description]
 * @param  [type] $CdTpMov   [description]
 * @param  [type] $CdUsuario [description]
 * @return [type]            [description]
 */
function logMovimentacao($CdMov, $TpMov, $CdPref, $Valor, $CdTpMov, $CdUsuario, $DtMov)
{
	$DtExcluir = date('Y-m-d H:i:s');

	$query = "INSERT INTO `tbmovimentacaolog`(CdMov,TpMov,CdPref,Valor,CdTpMov,CdUsuario,DtExcluir,DtMov) 
				VALUES ('$CdMov','$TpMov','$CdPref','$Valor','$CdTpMov','$CdUsuario','$DtExcluir','$DtMov')";

	$resultado = mysqli_query($GLOBALS['db'], $query) or die(mysqli_error());

	return $resultado;
}
/**
 * [saldoRemMovimentacao description]
 * @param  [type] $CdMov [description]
 * @return [type]        [description]
 */
function saldoRemMovimentacao($CdMov)
{
	$query = "DELETE FROM tbmovimentacao WHERE CdSolCons = 0 AND `CdMov` = '$CdMov'";

	$resultado = mysqli_query($GLOBALS['db'], $query) or die(mysqli_error());

	return $resultado;
}
/**
 * [getEnderecoFornDifente description]
 * @param  [type] $cdforn      [description]
 * @param  [type] $cdespecproc [description]
 * @return [type]              [description]
 */
function getEnderecoFornDif($cdforn, $cdespecproc)
{
	$query = "SELECT
				fes.CdEndereco,
				endr.Logradouro,
				endr.Numero,
				endr.Compl,
				endr.Bairro,
				endr.CEP,
				pr.NmCidade,
				est.NmEstado,
				est.UF
				FROM
				tbfornespec AS fes
				INNER JOIN tbendereco AS endr ON fes.CdEndereco = endr.CdEndereco
				INNER JOIN tbprefeitura AS pr ON endr.CdCidade = pr.CdPref
				INNER JOIN tbestado AS est ON pr.CdEstado = est.CdEstado
				WHERE fes.CdForn = $cdforn
				AND fes.CdEspec = $cdespecproc
				";
	$resultado = mysqli_query($GLOBALS['db'], $query);
	$l = mysqli_fetch_array($resultado, MYSQLI_ASSOC);
	return $l;
}
/**
 * [getEnderecoForn description]
 * @param  [type] $cdforn [description]
 * @return [type]         [description]
 */
function getEnderecoForn($cdforn)
{
	$query = "SELECT
					tbfornecedor.CdForn,
					tbfornecedor.Logradouro,
					tbfornecedor.Numero,
					tbfornecedor.Compl,
					tbfornecedor.Bairro,
					tbfornecedor.CdCidade,
					tbprefeitura.NmCidade,
					tbestado.NmEstado,
					tbestado.UF
					FROM
					tbfornecedor
					INNER JOIN tbprefeitura ON tbfornecedor.CdCidade = tbprefeitura.CdPref
					INNER JOIN tbestado ON tbprefeitura.CdEstado = tbestado.CdEstado
					WHERE
					tbfornecedor.CdForn = $cdforn
				";
	$resultado = mysqli_query($GLOBALS['db'], $query);
	$l = mysqli_fetch_array($resultado, MYSQLI_ASSOC);
	return $l;
}

function getEnderecoFornCerto($cdforn, $cdespecproc = NULL)
{
	if ($cdespecproc) {
		$end = getEnderecoFornDif($cdforn, $cdespecproc);
	}

	if ($end['CdEndereco']) {
		return $end;
	} else {
		$end = getEnderecoForn($cdforn);
	}
	return $end;
}

function buscasiglaconselho($cdprof)
{
	$query = "SELECT
				tbconselhos.sigla
				FROM
				tbprofconselho
				INNER JOIN tbconselhos ON tbprofconselho.cdconselho = tbconselhos.cdconselho
				WHERE tbprofconselho.cdprof = $cdprof
				LIMIT 1
				";
	$resultado = mysqli_query($GLOBALS['db'], $query);
	$l = mysqli_fetch_array($resultado, MYSQLI_ASSOC);
	return $l['sigla'];
}

//CONSELHO DOS PROFISSIONAIS DE MUNIC�PIO
function buscasiglaconselhomun($cdprof)
{
	$query = "SELECT
				tbconselhos.sigla
				FROM
				tbprofconselho_mun
				INNER JOIN tbconselhos ON tbprofconselho_mun.cdconselho = tbconselhos.cdconselho
				WHERE tbprofconselho_mun.cdprof = $cdprof
				LIMIT 1
				";
	$resultado = mysqli_query($GLOBALS['db'], $query);
	$l = mysqli_fetch_array($resultado, MYSQLI_ASSOC);
	return $l['sigla'];
}


//INICIO COTAS GRUPO
function cotagprp_usada($cdcotagrp)
{
	$query = mysqli_query($GLOBALS['db'], "SELECT count(*) as qnt FROM tbpedidoexameespec where cdcotagrp = $cdcotagrp and `Status` = 1");

	if (mysqli_num_rows($query) > 0) {
		$d_cota = mysqli_fetch_array($query);
		return $d_cota["qnt"];
	}
	return 0;
}

function cotagrp_restante($cdcotagrp)
{
	$qnt_return = 0;

	$query = mysqli_query($GLOBALS['db'], "SELECT qnt FROM tbcotagrp where cdcotagrp = $cdcotagrp");

	if (mysqli_num_rows($query) > 0) {
		$d_cota = mysqli_fetch_array($query);
		return $d_cota["qnt"] - cotagprp_usada($cdcotagrp);
	}

	return $qnt_return;
}

function cotagrp_certo($cdgrp, $cdforn, $data, $qnt = null)
{
	$qnt = ($qnt == null) ? 1 : $qnt;

	$query = mysqli_query($GLOBALS['db'], "SELECT cdcotagrp, cdforn, cdgrupoproc, qnt, `status` from tbcotagrp
							  where cdgrupoproc = $cdgrp and `status` = 1
							  and '$data' BETWEEN dtinicio and dtfim
							  order by cdcotagrp");

	if (mysqli_num_rows($query) > 0)
		while ($n = mysqli_fetch_array($query))
			if (cotagrp_restante($n["cdcotagrp"]) >= $qnt)
				return $n["cdcotagrp"];

	return 0;
}

function cotagrp_data($cdcotagrp, $tipo)
{
	$tipo = ($tipo == "asc") ? " asc " : " desc ";
	$query = mysqli_query($GLOBALS['db'], "SELECT pe.DataSol from tbpedidoexameespec pee 
							  inner join tbpedidoexame pe on pee.CdPedidoExame = pe.CdPedidoExame
							  where cdcotagrp = $cdcotagrp and pee.`Status` = 1 and pe.`Status` = 1
							  order by pe.DataSol $tipo limit 1");

	if (mysqli_num_rows($query) > 0) {
		$dd = mysqli_fetch_array($query);
		return $dd["DataSol"];
	} else
		return "";
}

//FIM COTAS GRUPO

//Agendamento Aten��o B�sica
function setagatb($cdag, $cdpaciente)
{
	$dtinc 		= date('Y-m-d');
	$hrinc 		= date('H:i:s');
	$userinc 	= $_SESSION['CdUsuario'];
	$pref 		= $_SESSION['CdOrigem'];
	$status 	= 'M';

	$sqlag = mysqli_query($GLOBALS['db'], "SELECT tbagenda_mun.cdagenda_mun,tbagenda_mun.cdunidade,tbagenda_mun.cdprocedimento,tbagenda_mun.obs,
								tbagenda_mun.cdespecificacao,tbagenda_mun.cdpref,tbagenda_mun.`data`,tbagenda_mun.hora,tbagenda_mun.`status`,
								tbagenda_mun.cdprof
								FROM tbagenda_mun
								WHERE cdagenda_mun = $cdag");
	$lag = mysqli_fetch_array($sqlag);

	$sql = mysqli_query($GLOBALS['db'], "INSERT INTO tbagtab (cdespec,cdunidade,cdprof,cdpref,cdpaciente,dtag,horaag,cdagenda_mun,status,cduserinc,dtinc,hrinc)
										VALUES ('$lag[cdespecificacao]','$lag[cdunidade]','$lag[cdprof]','$lag[cdpref]','$cdpaciente','$lag[data]','$lag[hora]','$cdag','$status','$userinc','$dtinc','$hrinc')");

	$cod = mysqli_insert_id();

	if ($sql) {
		AtualizaAgenda_mun($cdag, 'M');

		return $cod;
	} else {
		return 0;
	}
}

function AtualizaAgenda_mun($cdag, $status)
{

	if ($status == 'M') {
		$sqlup = mysqli_query($GLOBALS['db'], "UPDATE tbagenda_mun SET status = 'M' WHERE cdagenda_mun = $cdag");
	}
}


//IN�CIO COTAS UNIDADE
//Quantidade de cotas recebidas pela unidade
function getCotaUnidade($cddist, $cdunid)
{
	$retorno = readJoin('tbdistcotauni.qts', 'tbdistcotauni', 'WHERE CdDist=' . $cddist . ' AND CdForn=' . $cdunid . ' ');

	if ($retorno) {
		foreach ($retorno as $dados) :
			$saida = $dados['qts'];
		endforeach;
	}
	return $saida;
}

//Quantidade de cotas gastas pela unidade
function getCotaGastasUnidade($cdcota, $cdpref, $cdunid)
{
	$query = "SELECT
					COUNT(tbagendacons.CdSolCons) as qts
					FROM
					tbsolcons
					INNER JOIN tbagendacons ON tbsolcons.CdSolCons = tbagendacons.CdSolCons
					WHERE tbsolcons.`Status` = 1 
					AND tbsolcons.CdPref = $cdpref
					AND tbsolcons.cdcota = $cdcota
					AND tbsolcons.CdUnid = $cdunid
					AND tbsolcons.TpAgen = 'und'
					";
	//echo $query;
	//echo'<br/>';
	$result = mysqli_query($GLOBALS['db'], $query);

	if ((mysqli_num_rows($result) > 0) or $cotadist > 0) {
		while ($dado = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$qts = $dado['qts'];
		}
		return $qts;
	} else {
		return 0;
	}
}

//Quantidade de cotas dispon�veis para a unidade
function cotaDisponivelUnidade($cdcota, $cdpref, $cddist, $cdunid)
{
	$cotaUnd 	= getCotaUnidade($cddist, $cdunid);
	$cotaGastaU 	= getCotaGastasUnidade($cdcota, $cdpref, $cdunid);
	$disponivel = $cotaUnd - $cotaGastaU;
	return $disponivel;
}
//FIM COTAS UNIDADE

function getCotaGastasTT($cdcota, $cdpref)
{
	$query = "SELECT
					COUNT(tbagendacons.CdSolCons) as qts
					FROM
					tbsolcons
					INNER JOIN tbagendacons ON tbsolcons.CdSolCons = tbagendacons.CdSolCons
					WHERE tbsolcons.`Status` = 1 
					AND tbsolcons.CdPref = $cdpref
					AND tbsolcons.cdcota = $cdcota
					";
	//echo $query;
	//echo'<br/>';
	$result = mysqli_query($GLOBALS['db'], $query);

	if (mysqli_num_rows($result) > 0) {
		while ($dado = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$qts = $dado['qts'];
		}
		return $qts;
	} else {
		return 0;
	}
}

function Verificapacagdm($cdpac, $cdespec, $cdforn, $cdprof, $data)
{
	$sqlpac = "SELECT sc.CdSolCons, sc.CdEspecProc, sc.CdPaciente, ac.CdForn, ac.cdprof
					FROM tbsolcons sc
					INNER JOIN tbagendacons ac ON sc.CdSolCons = ac.CdSolCons
					WHERE
						sc.CdPaciente = $cdpac
					AND sc.CdEspecProc = $cdespec
					AND DtAgCons = '$data'
					AND ac.cdprof = $cdprof
					AND ac.CdForn = $cdforn
					AND sc.status <> 2";

	//echo $sqlpac;

	$result = mysqli_query($GLOBALS['db'], $sqlpac);

	if (mysqli_num_rows($result) > 0) {
		return 1;
	} else {
		return 0;
	}
}

function Getdadosagenda($cdagenda)
{
	$sqlagenda = "SELECT cdagenda_fornecedor,cdfornecedor,cdprof,cdprocedimento,obs,cdespecificacao,cdpref,					`data`,hora,`status`,usrexc,dtexc,usrinc,dtinc,data_ant,user_ant,dtalt_ant
						FROM tbagenda_fornecedor
						WHERE cdagenda_fornecedor = $cdagenda";

	$result = mysqli_query($GLOBALS['db'], $sqlagenda) or die('dadosagenda - ' . mysqli_error());
	if ($result) {
		while ($dados = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			return $dados;
		}
	} else {
		return 0;
	}
}

//INICIO - VAlIDA��ES DO CONTRATO

/**
 * [statusContrato description]
 * @param  [int] $CdContrato [description]
 * @param  [char] $acao       [description]
 * @return [int]             [description]
 */
function statusContrato($CdContrato, $acao)
{
	switch ($acao) {
		case 'A':
			$Status = 1;
			break;
		case 'D':
			$Status = 0;
			break;
	}

	$query = "UPDATE tbcontrato SET Status = $Status WHERE CdContrato=$CdContrato";

	$resultado = mysqli_query($GLOBALS['db'], $query) or die(mysqli_error($GLOBALS['db']));

	return $resultado;
}

/**
 * [logContratos description]
 * @param  [int] $cdcontrato [description]
 * @param  [int] $usuario    [description]
 * @param  [char] $opcao      [description]
 * @return [int]             [description]
 */
function logContratos($cdcontrato, $usuario, $opcao)
{
	$datalog	= date('Y-m-d H:i:s');

	$query = "INSERT INTO `tbcontratolog`(cdcontrato,opcao,usuario,data) VALUES ('$cdcontrato','$opcao','$usuario','$datalog')";

	$resultado = mysqli_query($GLOBALS['db'], $query) or die(mysqli_error($GLOBALS['db']));

	return $resultado;
}

/**
 * [contratoAddMovimentacao description]
 * @param  [int] $CdSolCons  [description]
 * @param  [int] $CdContrato [description]
 * @param  [int] $CdUsuario  [description]
 * @return [type]             [description]
 */
function contratoAddMovimentacao($CdSolCons, $CdContrato, $CdUsuario)
{
	$Data = date('Y-m-d H:i:s');

	$query = "INSERT INTO `tbcontratomov`(CdSolCons,CdContrato,Data,CdUsuario,Status) VALUES ('$CdSolCons','$CdContrato','$Data','$CdUsuario','1')";

	$resultado = mysqli_query($GLOBALS['db'], $query) or die('ADD - ' . mysqli_error($GLOBALS['db']));

	return $resultado;
}

/**
 * [contratoVoltaMovimentacao description]
 * @param  [type] $CdSolCons [description]
 * @return [type]            [description]
 */
function contratoVoltaMovimentacao($CdSolCons)
{
	$Status = 1;
	$queryA = "SELECT mov.id, mov.`Status`
					FROM tbcontratomov AS mov
					WHERE mov.CdSolCons = {$CdSolCons}
					";
	$resultadoA = mysqli_query($GLOBALS['db'], $queryA) or die('ADD - ' . mysqli_error($GLOBALS['db']));

	$l = mysqli_fetch_array($resultadoA);

	$idMov = $l['id'];

	if ($idMov && $l['Status'] == 0) {

		$queryU = "UPDATE tbcontratomov SET Status = $Status WHERE id=$idMov";

		$resultadoU = mysqli_query($GLOBALS['db'], $queryU) or die(mysqli_error($GLOBALS['db']));

		return 1;
	} else {
		return 0;
	}
}

/**
 * [contratoRemMovimentacao description]
 * @param  [type] $CdSolCons  [description]
 * @param  [type] $CdContrato [description]
 * @return [type]             [description]
 */
function contratoRemMovimentacao($CdSolCons)
{
	$Status = 0;
	$query = "SELECT mov.id
				FROM tbcontratomov AS mov
				WHERE mov.`Status` = 1 AND mov.CdSolCons = {$CdSolCons}
				";

	$resultado = mysqli_query($GLOBALS['db'], $query) or die('RM - ' . mysqli_error($GLOBALS['db']));

	$l = mysqli_fetch_array($resultado);

	$idMov = $l['id'];

	if ($idMov) {

		$queryU = "UPDATE tbcontratomov SET Status = $Status WHERE id=$idMov";

		$resultadoU = mysqli_query($GLOBALS['db'], $queryU) or die(mysqli_error($GLOBALS['db']));

		return 1;
	} else {
		return 0;
	}
}

/**
 * [contratoValor description]
 * @param  [type] $CdContrato [description]
 * @return [type]             [description]
 */
function contratoValor($CdContrato)
{
	$query = "SELECT con.Valor
				FROM tbcontrato AS con
				WHERE con.CdContrato = {$CdContrato} 
				";

	$resultado = mysqli_query($GLOBALS['db'], $query) or die('VR - ' . mysqli_error($GLOBALS['db']));

	$l = mysqli_fetch_array($resultado);

	$valorCont = $l['Valor'];

	return $valorCont;
}

/**
 * [contratoTotalMovimentacao description]
 * @param  [type] $CdContrato [description]
 * @return [type]             [description]
 */
function contratoTotalMovimentacao($CdContrato, $Status = NULL)
{
	$Status = ($Status == 'M') ? ' AND agen.`Status`= 1' : (($Status == 'R') ? ' AND agen.`Status`= 2' : '');
	$query = "SELECT SUM(agen.valor) AS total 
				FROM tbcontratomov AS cmov 
				INNER JOIN tbagendacons AS agen ON cmov.CdSolCons = agen.CdSolCons 
				WHERE cmov.`Status` = 1 AND cmov.CdContrato = $CdContrato 
				$Status";
	//echo $query;
	$resultado = mysqli_query($GLOBALS['db'], $query) or die('Erro MovContr' . mysqli_error($GLOBALS['db']));
	//echo $resultado;
	$l = mysqli_fetch_array($resultado);

	if (isset($l['total'])) {
		$total = $l['total'];
	} else {
		$total = 0;
	}

	return $total;
}

/**
 * [contratoValorRestante description]
 * @param  [type] $CdContrato [description]
 * @return [type]             [description]
 */
function contratoValorRestante($CdContrato)
{
	$valorCont = contratoValor($CdContrato);

	$totalMov = contratoTotalMovimentacao($CdContrato);

	return $valorCont - $totalMov;
}

/**
 * [contratoQuantMovimentacao description]
 * @param  [type] $CdContrato [description]
 * @return [type]             [description]
 */
function contratoQuantMovimentacao($CdContrato)
{
	$query = "SELECT count(cmov.CdSolCons) AS total 
				FROM tbcontratomov AS cmov 
				WHERE cmov.`Status` = 1 AND cmov.CdContrato = {$CdContrato}
				";
	$resultado = mysqli_query($GLOBALS['db'], $query) or die(mysqli_error($GLOBALS['db']));

	$l = mysqli_fetch_array($resultado);

	$total = $l['total'];

	return $total;
}

/**
 * [contratoCerto description]
 * @param  [type] $CdEspec      [description]
 * @param  [type] $CdFornecedor [description]
 * @param  [date] $data         [description]
 * @return [type]               [description]
 * Foi editado no dia 18/04 para validar o status do procedimento linha 8
 */
function contratoCerto($CdEspec, $CdFornecedor = NULL, $data = NULL)
{
	$saida = 0;
	if ($data) {
		$valida = ' AND cont.CdForn = ' . $CdFornecedor . ' AND "' . $data . '" BETWEEN cont.DtValidade AND cont.DtValidadeF ';
	} else {
		$data = date('Y-m-d');
		$valida = 'AND (cont.DtValidade >= "' . $data . '" OR "' . $data . '" BETWEEN cont.DtValidade AND cont.DtValidadeF)';
	}
	$retorno = readJoin(
		'cont.CdContrato,cont.Valor,cont.DtValidade,cont.DtValidadef,cont.CdForn',
		'tbcontratoespec AS conte',
		'INNER JOIN tbcontrato AS cont ON conte.CdContrato = cont.CdContrato
							WHERE cont.`Status` = 1
							AND conte.CdEspecProc = ' . $CdEspec . '
							AND conte.`Status` = 1
							' . $valida . '
							ORDER BY cont.DtValidade ASC'
	);
	if ($retorno) {
		foreach ($retorno as $dados) :

			$valorProc = valorProcedimento($CdEspec);
			$total = contratoTotalMovimentacao($dados['CdContrato']);
			$total += $valorProc;
			$total = $dados['Valor'] - $total;


			if ($total >= 0) {
				$saida = $dados['CdContrato'];
				break;
			}
		endforeach;
	}
	return $saida;
}

/**
 * [valorProcedimento description]
 * @param  [type] $CdFornecedor [description]
 * @param  [type] $CdEspec      [description]
 * @return [type]               [description]
 */
function valorProcedimento($CdEspec)
{
	$query = "SELECT valor from tbespecproc where CdEspecProc = '$CdEspec' and `Status` = '1'";
	$resultado = mysqli_query($GLOBALS['db'], $query) or die(mysqli_error($GLOBALS['db']));
	$l = mysqli_fetch_array($resultado);

	return $l['valor'];
}

/**
 * [possuiContrato description]
 * @param  [type] $CdFornecedor [description]
 * @param  [type] $CdEspec      [description]
 * @return [type]               [description]
 */
function possuiContrato($CdFornecedor, $CdEspec)
{
	$query = "SELECT cont.CdForn,
				cont.CdContrato,
				espec.CdEspecProc
				FROM tbcontrato AS cont
				INNER JOIN tbcontratoespec AS espec ON cont.CdContrato = espec.CdContrato
				WHERE cont.CdForn = {$CdFornecedor} AND espec.CdEspecProc = {$CdEspec} AND cont.status = 1 AND espec.`Status` = 1
				";

	$resultado = mysqli_query($GLOBALS['db'], $query) or die(mysqli_error($GLOBALS['db']));

	$l = mysqli_fetch_array($resultado);

	if (isset($l['CdContrato'])) {
		$resposta = 'SIM';
	} else {
		$resposta = 'N�O';
	}

	return $resposta;
}

/**
 * [especificacaoContrato description]
 * @param  [type] $CdContrato [description]
 * @return [type]             [description]
 * Foi editado no dia 18/04 para validar o status dos procedimentos linha 5
 */
function especificacaoContrato($CdContrato)
{
	$retorno = readJoin(
		'conte.CdEspecProc,esp.NmEspecProc',
		'tbcontratoespec AS conte',
		'INNER JOIN tbespecproc AS esp ON conte.CdEspecProc = esp.CdEspecProc 
							WHERE conte.CdContrato = ' . $CdContrato . '
							AND conte.`Status` = 1'
	);
	if ($retorno) {
		$saida[0] = count($retorno);
		($saida[0] > 1) ? $virgula = "," : $virgula = "";
		foreach ($retorno as $dados) :
			$saida[1] .= ' ' . $dados['NmEspecProc'] . $virgula;
		endforeach;
	} else {
		$saida[1] = "";
	}

	return $saida;
}
//FIM - VALIDA��ES DO CONTRATO


// IN�CIO - NOVAS VALIDA��ES SALDO


function contratoValorProcedimento($CdContrato, $CdEspecproc)
{

	$query = "SELECT IFNULL(valor_ctr,0) as Valor
				  FROM tbcontratoespec
				  WHERE CdContrato = $CdContrato
				  AND CdEspecProc = $CdEspecproc";

	$resultado = mysqli_query($GLOBALS['db'], $query) or die('VPR - ' . mysqli_error($GLOBALS['db']));
	$l = mysqli_fetch_array($resultado);

	$valorCont = $l['Valor'];

	return $valorCont;
}

function validaSaldoContrato($CdContrato, $CdEspecproc)
{

	$saldoContrato = contratoValorRestante($CdContrato);
	$valorProcedimento = contratoValorProcedimento($CdContrato, $CdEspecproc);

	$saldoRestante = $saldoContrato - $valorProcedimento;

	if ($saldoRestante >= 0)
		return true;

	else
		return false;
}

function validaTeto($DtAg, $CdPref, $CdTeto = NULL, $CdSaldo = NULL)
{

	if ($CdTeto)
		$teto = " AND teto.CdTeto = " . $CdTeto . " ";
	else
		$teto = "";

	if ($CdSaldo)
		$saldo = " AND gensaldo.cdsaldo = " . $CdSaldo . " ";
	else
		$saldo = "";

	$sqlTeto = "SELECT
					teto.cdteto,
					teto.cdsaldo, 
					teto.idgenfat,
					teto.valorTeto,
					gensaldo.`Desc`,
					gensaldo.AbtSaldo
					FROM
					tbsldteto teto
					INNER JOIN tbsldgensaldo gensaldo ON teto.cdsaldo = gensaldo.cdsaldo
					INNER JOIN tbgenfat g ON g.idgenfat = teto.idgenfat
					WHERE '" . $DtAg . "' BETWEEN g.dtini AND g.dtfim
					AND gensaldo.cdpref = '" . $CdPref . "'
					$teto
					$saldo
					AND teto.`status` = 1
					AND gensaldo.`status` = 1";

	$resultado = mysqli_query($GLOBALS['db'], $sqlTeto) or die('dadosteto - ' . mysqli_error());

	if ($resultado) {
		while ($dados = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
			return $dados;
		}
	} else {
		return false;
	}
}

function getValorProc($CdEspec, $config, $cdfornecedor = NULL, $dtagcons = null)
{
	if ($config == 1) {

		$query = "	SELECT ce.valor_ctr, ce.valor_mun,valorsus from tbcontrato c
						INNER JOIN tbcontratoespec ce on ce.CdContrato = c.CdContrato
						LEFT JOIN tbespecproc ep on ep.CdEspecProc = ce.CdEspecProc
						WHERE c.CdForn = $cdfornecedor and ce.CdEspecProc = $CdEspec and '$dtagcons' BETWEEN c.DtValidade and c.DtValidadef 
						LIMIT 0,1 ";
		// if ($_SESSION['CdUsuario'] == 2) {
			// echo $query; die();	
		// }

		$resultado = mysqli_query($GLOBALS['db'], $query) or die('vpr - ' . mysqli_error($GLOBALS['db']));
		$numrow = mysqli_num_rows($resultado);
		if ($numrow) {
			while ($dados = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
				if ($dados['valor_ctr'] && $dados['valorsus']) {
					$valor['valor_ctr'] = $dados['valor_ctr'];
					$valor['valor_mun'] = $dados['valor_mun'];
					$valor['valor_sus'] = $dados['valorsus'];
				}
			}
		}
	} else if ($config == 2) {

		$query = "	SELECT	valor, valorsus FROM tbespecproc WHERE CdEspecProc = $CdEspec";
		$resultado = mysqli_query($GLOBALS['db'], $query) or die('vpr - ' . mysqli_error($GLOBALS['db']));
		$numrow = mysqli_num_rows($resultado);
		if ($numrow) {
			while ($dados = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
				if ($dados['valor'] && $dados['valorsus']) {
					$valor['valor_ctr'] = $dados['valor'];
					$valor['valor_mun'] = $dados['valor'];
					$valor['valor_sus'] = $dados['valorsus'];
				}
			}
		}
	} else if ($config == 3) {

		$validafornecedor = ($cdfornecedor) ? " AND tbfornespec.CdForn = $cdfornecedor " : "";
		$query = " SELECT tbfornespec.valorf,tbfornespec.valorc, ep.valorsus
					   FROM tbfornespec
					   LEFT JOIN tbespecproc ep on ep.CdEspecProc = tbfornespec.CdEspec
					   WHERE tbfornespec.CdEspec = {$CdEspec}	$validafornecedor
					   AND tbfornespec.`Status` = 1 
					   ORDER BY tbfornespec.dtinc DESC LIMIT 0,1";
		//echo $query;
		$resultado = mysqli_query($GLOBALS['db'], $query) or die('vpr - ' . mysqli_error($GLOBALS['db']));
		$numrow = mysqli_num_rows($resultado);
		if ($numrow) {
			while ($dados = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
				if ($dados['valorf'] && $dados['valorc']) {
					$valor['valor_ctr'] = $dados['valorf'];
					$valor['valor_mun'] = $dados['valorc'];
					$valor['valor_sus'] = $dados['valorsus'];
				}
			}
		}
	}
	//var_dump($valor);
	return $valor;
}

function add_recepcionado($CdSolCons, $user)
{
	$data_atual = date("Y-m-d H:i:s");

	$valida_recep = mysqli_query($GLOBALS['db'], "SELECT * from tbsolcons sc
									 inner join tbagendacons ac on sc.CdSolCons = ac.CdSolCons
									 left join tbrecepcionado recep on sc.CdSolCons = recep.CdSolCons
									 where sc.`Status` = 1 and ac.`Status` = 1 and sc.CdSolCons = '$CdSolCons' 
									 and (recep.`Status` = 1 or recep.`Status` is null)");

	if (mysqli_num_rows($valida_recep) > 0) {
		$recep = mysqli_fetch_array($valida_recep);

		if ($recep["CdRecepcionado"] != null)
			$gogo = mysqli_query($GLOBALS['db'], "UPDATE tbrecepcionado set DtRecep = '$data_atual', UserRecep = '$user' where CdSolCons = '$CdSolCons'");
		else
			$gogo = mysqli_query($GLOBALS['db'], "INSERT INTO tbrecepcionado (CdSolCons, UserRecep, DtRecep, `Status`) values('$CdSolCons', '$user', '$data_atual', 1)");
	}
}

function getValuesAgendamento($CdSolCons)
{
	$sql = "SELECT age.valor,sol.CdPref,sol.CdPaciente,sol.CdEspecProc,age.CdForn,age.DtAgCons,sol.dtinc,sol.Status
				FROM tbsolcons AS sol
				LEFT JOIN tbagendacons AS age ON sol.CdSolCons = age.CdSolCons
				WHERE sol.CdSolCons = {$CdSolCons}";



	$resultado = mysqli_query($GLOBALS['db'], $sql) or die('RM - ' . mysql_error());

	$l = mysqli_fetch_assoc($resultado);

	return $l;
}

function verificaMovContrato($CdSolCons)
{
	$query = "SELECT COUNT(id) AS qtd
					FROM
					tbcontratomov
					WHERE CdSolCons = $CdSolCons AND `Status` = 1";
	$resultado = mysqli_query($GLOBALS['db'], $query) or die(mysql_error());

	$l = mysqli_fetch_array($resultado);
	if (getConfiguracaoEstado(100)) {
		return $l[qtd];
	} else {
		return 1;
	}
}

function getConfiguracaoEstado($cdconfig)
{
	$query = "SELECT * FROM tbconfig WHERE cdconfig = $cdconfig";

	$resultado = mysqli_query($GLOBALS['db'], $query) or die('RM - ' . mysql_error());

	$l = mysqli_fetch_assoc($resultado);

	return ($l['estado'] == 'A') ? TRUE : FALSE;
}

function verificaMovSaldo($CdSolCons)
{
	$query = "SELECT tbmovimentacao.CdMov,tbmovimentacao.CdSolCons,tbmovimentacao.DtMov,tbmovimentacao.Debito,tbmovimentacao.Credito,
				tbmovimentacao.TpMov, count(CdSolCons) as qtd,tbmovimentacao.CdPref
				FROM tbmovimentacao
				WHERE CdSolCons = $CdSolCons
				AND TpMov = 'D'";

	$resultado = mysqli_query($GLOBALS['db'], $query) or die(mysqli_error());

	$l = mysqli_fetch_array($resultado);
	if (getConfiguracaoEstado(102)) {
		return $l;
	} else {
		return 1;
	}
}

function getSolicitacao($CdSolCons)
{
	$query = "SELECT sol.CdPref,sol.CdPaciente,sol.CdEspecProc,sol.dtinc
				  FROM tbsolcons AS sol
				  WHERE sol.CdSolCons = {$CdSolCons}";
	//echo $query;
	$resultado = mysqli_query($GLOBALS['db'], $query) or die('RM - ' . mysql_error());

	$l = mysqli_fetch_array($resultado);

	return $l;
}

function Audiespera($cd, $descr, $usr, $i, $obs = NULL)
{
	$dt = date('Y-m-d');
	$hr = date('H:i:s');
	//$usr = $_SESSION[CdUsuario];

	$sqlaudi = ("INSERT INTO `tblogespera` (`CdSolCons`, `Descr`, `obs`, `usralt`, `dtalt`, `hralt`, `cdsubitem`) 
											VALUES ('$cd', '$descr', '$obs', '$usr','$dt', '$hr', '$i')");
	$qry = mysqli_query($GLOBALS['db'], $sqlaudi);
}
// FIM - NOVAS VALIDA��ES SALDO

// VALIDA?O PRA ENVIO DE SMS
function enviar_sms($celular, $paciente, $data, $hora, $procedimento, $fornecedor, $tipoproc)
{
	$dataf = FormataDataBR($data);
	//$hora  = split (":", $hora);
	$horaf = $hora[0] . ":" . $hora[1];
	setlocale(LC_CTYPE, 'pt_BR');
	$forn = (string)$fornecedor;
	//$msgEncoded = urlencode(" Caro paciente, seu(sua) ".$tipoproc." foi agendado para ".$dataf." as ".$horaf.".Favor comparecer no local indicado na Guia de Marcacao com seus documentos");
	$msgEncoded = urlencode(" Informa: Caro paciente, seu atendimento foi marcado para o dia " . $dataf . ". Procure a sua unidade de saude, para retirada da guia.");
	$user = 'sitconsms';
	$senha = 'sms1422sms';
	//986611696
	$destinatario = $celular;
	$urlChamada = "https://www.facilitamovel.com.br/api/simpleSend.ft?user=" . $user . "&password=" . $senha . "&destinatario=" . $destinatario . "&msg=" . $msgEncoded;
	//echo $urlChamada;
	//echo $msgEncoded;
	file_get_contents($urlChamada);
}

// FUNCAO DE ENVIO DE SMS PARA RECUPERACAO DE SENHAS
function enviar_sms_senha($celular, $recuperacao_senha)
{
	setlocale(LC_CTYPE, 'pt_BR');

	$msgEncoded = urlencode("Caro usuario, foi solicitado uma recuperação de senha, por gentileza acesse o sistema inserindo sua nova senha: " . $recuperacao_senha);

	$user = 'sitconsms';
	$senha = 'sms1422sms';
	$destinatario = $celular;

	$urlChamada = "http://api.facilitamovel.com.br/api/simpleSend.ft?user=" . $user . "&password=" . $senha . "&destinatario=" . $destinatario . "&msg=" . $msgEncoded;

	file_get_contents($urlChamada);
}

function enviar_sms_2fa($celular, $recuperacao_senha)
{
	setlocale(LC_CTYPE, 'pt_BR');

	$msgEncoded = urlencode("Caro usuario, foi solicitado um código de autenticação para seu acesso, por gentileza acesse o sistema inserindo o código: " . $recuperacao_senha);

	$user = 'sitconsms';
	$senha = 'sms1422sms';
	$destinatario = $celular;

	$urlChamada = "http://api.facilitamovel.com.br/api/simpleSend.ft?user=" . $user . "&password=" . $senha . "&destinatario=" . $destinatario . "&msg=" . $msgEncoded;

	file_get_contents($urlChamada);
}

// FIM - NOVAS VALIDAÇÕES SALDO

function valida_autentificacao($cdsolcons, $CdUsuario)
{
	$hoje = date('Y-m-d H:m:s');
	$sql_upsol = "UPDATE tbsolcons SET userimp = '$CdUsuario', dataimp = '$hoje' WHERE CdSolCons = $cdsolcons";
	//echo $sql_upsol;
	$qry = mysqli_query($GLOBALS['db'], $sql_upsol);
	$sql_log = "INSERT INTO tblog_autentificacao (CdSolCons,userimp,dataimp) VALUES ('$cdsolcons','$CdUsuario','$hoje')";
	//echo $sql_log;
	$qry1 = mysqli_query($GLOBALS['db'], $sql_log);
}

function hash_agendamento($cdsolcons, $idcombo)
{

	/**
	 * Gera um hash sha1 para um ou mais IDs de solicitação de consulta ou combos de exame.
	 * Atualiza a tabela tbagendacons com o hash gerado.
	 *
	 * @param int|array $cdsolcons O ID ou array de IDs das solicitações de consulta.
	 * @param int|array $idcombo O ID ou array de IDs dos combos de exame.
	 *
	 * @return int|array Retorna o número de linhas afetadas pela consulta gerada ou array de consultas geradas.
	 */

	$db = $GLOBALS['db'];

	if (is_array($idcombo)) {
		$updates = array();
		foreach ($idcombo as $key) {
			$hash = sha1($key);
			$stmt = $db->prepare("UPDATE tbagendacons ac INNER JOIN tbsolcons sc ON sc.CdSolCons = ac.CdSolCons SET hash_assinatura = ? WHERE sc.idcombo = ?");
			$stmt->bind_param('si', $hash, $key);
			$stmt->execute();
			$updates[] = $stmt->affected_rows;
		}
		$stmt->close();
		$result = $updates;
	} elseif (is_array($cdsolcons)) {
		$updates = array();
		foreach ($cdsolcons as $key) {
			$hash = sha1($key);
			$stmt = $db->prepare("UPDATE tbagendacons ac SET hash_assinatura = ? WHERE ac.CdSolCons = ?");
			$stmt->bind_param('si', $hash, $key);
			$stmt->execute();
			$updates[] = $stmt->affected_rows;
		}
		$stmt->close();
		$result = $updates;
	} else {

		// Verifica se um ID válido foi fornecido
		if ((!isset($idcombo) || $idcombo <= 0) && (!isset($cdsolcons) || $cdsolcons <= 0)) {
			$result = false;
		} else {
			// Gerar hash e atualizar tabela
			$hash = sha1(isset($idcombo) ? $idcombo : $cdsolcons);

			if ($idcombo > 0) {
				$stmt = $db->prepare("UPDATE tbagendacons ac INNER JOIN tbsolcons sc ON sc.CdSolCons = ac.CdSolCons SET hash_assinatura = ? WHERE sc.idcombo = ?");
				$stmt->bind_param('si', $hash, $idcombo);
			} else {
				$stmt = $db->prepare("UPDATE tbagendacons ac SET hash_assinatura = ? WHERE ac.CdSolCons = ?");
				$stmt->bind_param('si', $hash, $cdsolcons);
			}

			$stmt->execute();
			$result = $stmt->affected_rows;

			$stmt->close();
		}
	}

	return $result;
}

function hash_autoriza_guia($CdUsuario, $cdsolcons, $idcombo)
{
	/**
	 * Função responsável por autorizar a impressão de guias de solicitação de consulta
	 *
	 * @param integer $CdUsuario ID do usuário que está autorizando a impressão
	 * @param array|integer $cdsolcons Array contendo os IDs das solicitações de consulta a serem autorizadas ou um único ID de uma solicitação de consulta
	 * @param array|integer $idcombo Array contendo os IDs dos combos de solicitações de consulta a serem autorizados ou um único ID de um combo de solicitações de consulta
	 * @return boolean Retorna verdadeiro em caso de sucesso
	 */
	$db = $GLOBALS['db'];

	// Seleciona o hash da guia do usuário
	$stmt = $db->prepare("SELECT u.hash_guia FROM tbusuario u WHERE u.CdUsuario = ?");
	$stmt->bind_param('i', $CdUsuario);
	$stmt->execute();

	$resultado = $stmt->get_result();
	$row = $resultado->fetch_assoc();
	$hash = $row['hash_guia'];

	// Caso o hash não exista, cria um novo hash para o usuário
	if ($hash == null) {
		$hash = sha1($CdUsuario);

		$stmt = $db->prepare("UPDATE tbusuario u SET u.hash_guia = ? WHERE u.CdUsuario = ?");
		$stmt->bind_param('si', $hash, $CdUsuario);
		$stmt->execute();
	}

	// Verifica se $idcombo é um array
	if (is_array($idcombo)) {
		// Seleciona o campo 'impresso' das solicitações de consulta cujos IDs estão presentes no array $idcombo
		$stmt = $db->prepare("SELECT sc.impresso FROM tbsolcons sc WHERE sc.idcombo IN (" . rtrim(str_repeat('?, ', count($idcombo)), ', ') . ")");
		$stmt->bind_param(str_repeat('i', count($idcombo) + 1), $CdUsuario, ...$idcombo);
		$stmt->execute();

		$resultado = $stmt->get_result();
		$impresso = array();

		while ($row = $resultado->fetch_assoc()) {
			$impresso[] = $row['impresso'];
		}

		// Se pelo menos uma das solicitações de consulta não foi impressa, atualiza o campo 'impresso' e 'user_imp' para 'S' e $CdUsuario, respectivamente
		if (in_array('N', $impresso)) {
			$stmt = $db->prepare("UPDATE tbsolcons sc SET  sc.impresso = 'S' , sc.user_imp = ?, sc.hash_guia = ? WHERE sc.idcombo IN (" . rtrim(str_repeat('?, ', count($idcombo)), ', ') . ")");
			$stmt->bind_param('is' . str_repeat('i', count($idcombo)), $CdUsuario, $hash, ...$idcombo);
			$stmt->execute();
		}
		// Verifica se $cdsolcons é um array
	} elseif (is_array($cdsolcons)) {
		// Seleciona o campo 'impresso' das solicitações de consulta cujos IDs estão presentes no array $cdsolcons
		$stmt = $db->prepare("SELECT sc.impresso FROM tbsolcons sc WHERE sc.cdsolcons IN (" . rtrim(str_repeat('?, ', count($cdsolcons)), ', ') . ")");
		$stmt->bind_param(str_repeat('i', count($cdsolcons) + 1), $CdUsuario, ...$cdsolcons);
		$stmt->execute();
		$resultado = $stmt->get_result();
		$impresso = array();

		while ($row = $resultado->fetch_assoc()) {
			$impresso[] = $row['impresso'];
		}

		// Se pelo menos uma das solicitações de consulta não foi impressa, atualiza o campo 'impresso' e 'user_imp' para 'S' e $CdUsuario, respectivamente
		if (in_array('N', $impresso)) {
			$stmt = $db->prepare("UPDATE tbsolcons sc SET  sc.impresso = 'S' , sc.user_imp = ?, sc.hash_guia = ? WHERE sc.cdsolcons IN (" . rtrim(str_repeat('?, ', count($cdsolcons)), ', ') . ")");
			$stmt->bind_param('is' . str_repeat('i', count($cdsolcons)), $CdUsuario, $hash, ...$cdsolcons);
			$stmt->execute();
		}
		// Se não for um array, seleciona o campo 'impresso' da solicitação de consulta com o ID $cdsolcons
	} elseif ($idcombo > 0) {
		$stmt = $db->prepare("SELECT sc.impresso FROM tbsolcons sc WHERE sc.idcombo = ?");
		$stmt->bind_param('i', $idcombo);
		$stmt->execute();

		$resultado = $stmt->get_result();
		while ($row = $resultado->fetch_assoc()) {
			$impresso[] = $row['impresso'];
		}

		if (in_array('N', $impresso)) {
			$stmt = $db->prepare("UPDATE tbsolcons sc SET  sc.impresso = 'S' , sc.user_imp = ?, sc.hash_guia = ? WHERE sc.idcombo IN (?)");
			$stmt->bind_param('isi', $CdUsuario, $hash, $idcombo);
			$stmt->execute();
		}
	} elseif ($cdsolcons > 0) {
		$stmt = $db->prepare("SELECT sc.impresso FROM tbsolcons sc WHERE sc.CdSolcons = ?");
		$stmt->bind_param('i', $cdsolcons);
		$stmt->execute();

		$resultado = $stmt->get_result();
		$row = $resultado->fetch_assoc();

		if ($row['impresso'] == 'N') {
			// Se a solicitação de consulta não foi impressa, atualiza o campo 'impresso' e 'user_imp' para 'S' e $CdUsuario, respectivamente
			$stmt = $db->prepare("UPDATE tbsolcons sc SET  sc.impresso = 'S' , sc.user_imp = ?, sc.hash_guia = ? WHERE sc.CdSolcons IN (?)");
			$stmt->bind_param('isi', $CdUsuario, $hash, $cdsolcons);
			$stmt->execute();
		}
	}

	return true;
}

function verificaTermo($cdfornlct)
{
	$db = $GLOBALS['db'];
	$versao_termo = "SELECT MAX(t.versao_termo) as 'versao_atual' FROM tbdadostermo t WHERE t.cdfornlct = ?";
	$stmt = $db->prepare($versao_termo);
	$stmt->bind_param('i', $cdfornlct);
	$stmt->execute();

	$resultado = $stmt->get_result();
	$row = $resultado->fetch_assoc();
	$versao = $row['versao_atual'];

	if ($versao == null) {
		return 1;
	} else {
		$versao++;
		return $versao;
	}
}

function insereDadosTermo($cdespec, $valor, $cdfornlct, $versao)
{
	$db = $GLOBALS['db'];
	$versao_termo = "INSERT INTO tbdadostermo (cdespec, valor, cdfornlct, versao_termo, dtinc, userinc) VALUES (?,?,?,?,NOW(),?)";
	$stmt = $db->prepare($versao_termo);
	$stmt->bind_param('idiii', $cdespec, $valor, $cdfornlct, $versao, $_SESSION['CdUsuario']);
	$stmt->execute();

	return true;
}

function assinaturaConsorcio($cdfornlct)
{
	$db = $GLOBALS['db'];
	$cdusuario = $_SESSION['CdUsuario'];
	$versao_termo = "UPDATE tblctfornecedor_licitacao SET assinaturaconsorcio = ? WHERE CdFornlct = ?";
	$stmt = $db->prepare($versao_termo);
	$stmt->bind_param('ii', $cdusuario, $cdfornlct);
	$stmt->execute();
	return true;
}

function assinaturaFornecedor($cdfornlct)
{
	$db = $GLOBALS['db'];
	$cdusuario = $_SESSION['CdUsuario'];
	$versao_termo = "UPDATE tblctfornecedor_licitacao SET assinaturafornecedor = ? WHERE CdFornlct = ?";
	$stmt = $db->prepare($versao_termo);
	$stmt->bind_param('ii', $cdusuario, $cdfornlct);
	$stmt->execute();
	return true;
}
