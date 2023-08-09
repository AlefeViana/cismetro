<?php

session_start();
include("../../funcoes.php");

$cdespec = $_REQUEST['cdespec'];
$cdpref	 = $_REQUEST['cdpref'];
$principal = $_POST['principal'];

$erro = "";

$buscaEspec = " AND ep.cdespec = '$cdespec'";

if ($principal > 0) {

	$sql = "SELECT 
            CdEspecFilho
          FROM 
            tbespecprocsub
          WHERE
            CdEspecPai = '$cdespec'";

	$verifica = mysqli_query($db, $sql);
	$listEspec = mysqli_fetch_assoc($verifica);

	$in = '(' . implode(',', $listEspec) . ')';

	$buscaEspec = " AND ep.cdespec IN " . $in;
}

if (!empty($cdespec) && !empty($cdpref)) {

	$sqlDisponivel  = " SELECT 	s.vigencia,
									s.`Desc`,
									s.cdpref,
									s.AbtSaldo,
									t.cdsaldo,
									t.cdteto,
									t.idgenfat,
									SUM(t.valorTeto) 	as valorTeto,
									SUM(t.valorUnidade) as valorUnidade,
									sp.cdprocedimento,
									ep.CdEspecProc

							FROM 		tbsldgensaldo 		s 
							INNER JOIN 	tbsldteto 			t 	ON t.cdsaldo 		 = s.cdsaldo
							INNER JOIN 	tbgenfat 			g 	ON g.idgenfat 		 = t.idgenfat
							INNER JOIN 	tbsldgensaldo_proc 	sp 	ON sp.cdsaldo 		 = s.cdsaldo AND sp.`status` = 1
							INNER JOIN 	tbprefeitura 		pf 	ON pf.CdPref 		 = s.cdpref
							LEFT JOIN 	tbespecproc 		ep 	ON ep.CdProcedimento = sp.cdprocedimento

							WHERE 	 s.cdpref 		= $cdpref
							$buscaEspec 
							AND 	 s.`status` 	= 1 
							AND 	 g.estado 		= 'A' 
							AND 	 pf.`Status` 	= 1 

							GROUP BY s.cdsaldo";

	$sqlDisponivel = mysqli_query($db, $sqlDisponivel);

	if (mysqli_num_rows($sqlDisponivel) > 0) {

		while ($l = mysqli_fetch_assoc($sqlDisponivel)) {

			$array[] = array(
				'CdSaldo' 		=> $l["cdsaldo"],
				'CdTeto' 		=> $l["cdteto"],
				'valorUnidade'	=> $l["valorUnidade"],
				'ValorTeto' 	=> $l["valorTeto"],
				'idgenfat' 		=> $l["idgenfat"],
				'vigencia' 		=> $l["vigencia"],
				'Descricao' 	=> $l["Desc"],
				'CdPref' 		=> $l["cdpref"],
				'AbtSaldo' 		=> $l["AbtSaldo"]
			);

			$sqlRestante = "SELECT IFNULL(SUM(CASE
													WHEN s.AbtSaldo = 'PPI' THEN ac.valorppi 
													WHEN s.AbtSaldo = 'CTR' THEN ac.valor
												  END),0) as saldo

								FROM 		tbsldgensaldo 		s
								INNER JOIN 	tbsldteto 			t  ON s.cdsaldo		= s.cdsaldo
								INNER JOIN 	tbsldmovimentacao 	m  ON m.cdteto 		= t.cdteto AND s.cdsaldo = m.cdsaldo
								INNER JOIN 	tbsolcons 			sc ON sc.CdSolCons 	= m.cdsolcons
								LEFT JOIN 	tbagendacons 		ac ON ac.CdSolCons 	= sc.CdSolCons
								LEFT JOIN 	tbfornecedor_mun 	fm ON fm.CdForn 	= m.cdubs

								WHERE 	m.`status` 	= 1 
								and 	t.cdteto 	= " . $l["cdteto"];

			$sqlRestante = mysqli_query($db, $sqlRestante);

			if (mysqli_num_rows($sqlRestante) > 0) {

				while ($r = mysqli_fetch_assoc($sqlRestante)) {

					$SaldoRes = ($l['valorTeto'] - $l['valorUnidade']) - $r['saldo'];

					if ($SaldoRes < 0) {
						$saldo_mes = $SaldoRes * -1;
						$SaldoRes = 0;
					} else {

						$saldo_mes = 0;
					}

					$saldo_mes 	= number_format($saldo_mes, 2, ',', '.');
					$SaldoRes 	= number_format($SaldoRes, 2, ',', '.');

					$restante[] = array(
						'SaldoRes' 		=> $SaldoRes,
						'consumo_extra' => $saldo_mes
					);
				}
			}
		}
	} else
		$erro = "Nenhum saldo disponível!";
} else
	$erro = "Erro ao localizar saldo!";

echo json_encode(array('dados' => $array, 'restante' => $restante, 'erro' => $erro));
