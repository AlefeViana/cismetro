<?php

session_start();
include("../../funcoes.php");

//$Paciente 	= descobrePaciente($CdPaciente);
$cdpref 	 = ($_REQUEST['cdpref']) ? $_REQUEST['cdpref'] : $_SESSION['CdOrigem'];



$erro = "";

if (isset($_REQUEST['cdespec']) and isset($_REQUEST['cdpref']) and isset($_REQUEST['data_agenda'])) {

	$sqlDisponivel  = " SELECT t.cdsaldo,t.cdteto,t.idgenfat,t.valorTeto,t.valorUnidade,s.vigencia,s.`Desc`,s.cdpref,s.AbtSaldo,sp.cdprocedimento,ep.CdEspecProc
							FROM tbsldgensaldo s 
							INNER JOIN tbsldteto t on t.cdsaldo = s.cdsaldo
							INNER JOIN tbgenfat g on g.idgenfat = t.idgenfat
							INNER JOIN tbsldgensaldo_proc sp on sp.cdsaldo = s.cdsaldo AND sp.`status` = 1
							INNER JOIN tbprefeitura pf on pf.CdPref = s.cdpref
							LEFT JOIN tbespecproc ep on ep.CdProcedimento = sp.cdprocedimento
							WHERE s.cdpref = $cdpref AND '$_REQUEST[data_agenda]' BETWEEN g.dtini AND g.dtfim
							AND s.`status` = 1 AND g.estado = 'A' AND pf.`Status` = 1
                            GROUP BY s.cdsaldo,t.cdteto";
	//echo $sqlDisponivel; die();
	$sql_debug = $sqlDisponivel;
	$sqlDisponivel = mysqli_query($db, $sqlDisponivel);

	if (mysqli_num_rows($sqlDisponivel) > 0) {

		while ($l = mysqli_fetch_assoc($sqlDisponivel)) {

			$array[] = array('CdSaldo' => $l["cdsaldo"], 'CdTeto' => $l["cdteto"], 'valorUnidade' => $l["valorUnidade"], 'ValorTeto' => $l["valorTeto"], 'idgenfat' => $l["idgenfat"], 'vigencia' => $l["vigencia"], 'Descricao' => $l["Desc"],  'CdPref' => $l["cdpref"],  'AbtSaldo' => $l["AbtSaldo"]);


			$sqlRestante = "SELECT IFNULL(SUM(CASE
													WHEN s.AbtSaldo = 'PPI' THEN ac.valorppi 
													WHEN s.AbtSaldo = 'CTR' THEN ac.valor
												  END),0) as saldo
								FROM tbsldgensaldo s
								INNER JOIN tbsldteto t ON s.cdsaldo = s.cdsaldo
								INNER JOIN tbsldmovimentacao m ON m.cdteto = t.cdteto AND s.cdsaldo = m.cdsaldo
								INNER JOIN tbsolcons sc on sc.CdSolCons = m.cdsolcons
								LEFT JOIN tbagendacons ac on ac.CdSolCons = sc.CdSolCons
								LEFT JOIN tbfornecedor_mun fm on fm.CdForn = m.cdubs
								WHERE m.`status` = 1 and t.cdteto = " . $l["cdteto"];

			$sqlRestante = mysqli_query($db, $sqlRestante);

			if (mysqli_num_rows($sqlRestante) > 0) {

				while ($r = mysqli_fetch_assoc($sqlRestante)) {

					$SaldoRes = ($l['valorTeto'] - $l['valorUnidade']) - $r['saldo'];
					$SaldoRes = number_format($SaldoRes, 2, ',', '.');
					$restante[] = array('SaldoRes' => $SaldoRes);
				}
			}
		}
	} else
		$erro = "Nenhum saldo disponível!";
} else
	$erro = "Erro ao localizar saldo!";


echo json_encode(array('dados' => $array, 'restante' => $restante, 'erro' => $erro, 'sql' => $sql_debug));
