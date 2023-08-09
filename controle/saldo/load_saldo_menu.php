<?php

 	session_start();
	include("../../funcoes.php");
	
	$cdpref		 = $_REQUEST['cdpref'];
	if ($cdpref > 0){
		$condpref = " s.cdpref = $cdpref AND ";
	}else{
		$condpref="";
	}

	$erro = "";

	if(isset($_REQUEST['cdpref'])){

	$sqlDisponivel  = " SELECT
                            t.cdsaldo,
                            t.cdteto,
                            t.idgenfat,
                            t.valorTeto,
                            t.valorUnidade,
                            s.vigencia,
                            s.`Desc`,
                            s.cdpref,
                            s.AbtSaldo,
                            sp.cdprocedimento,
                            ep.CdEspecProc,
                            dist.valor,
							pref.NmCidade
                        FROM
                            tbsldgensaldo s
                        INNER JOIN tbsldteto t ON t.cdsaldo = s.cdsaldo
                        INNER JOIN tbgenfat g ON g.idgenfat = t.idgenfat
                        INNER JOIN tbsldgensaldo_proc sp ON sp.cdsaldo = s.cdsaldo
                        AND sp.`status` = 1
                        INNER JOIN tbprefeitura pf ON pf.CdPref = s.cdpref
                        LEFT JOIN tbespecproc ep ON ep.CdProcedimento = sp.cdprocedimento
                        LEFT JOIN tbsldubsdist dist ON dist.cdteto = t.cdteto
						INNER JOIN tbprefeitura pref ON pref.CdPref = s.cdpref
                        WHERE
                        $condpref 
                        date(now()) BETWEEN g.dtini
                        AND g.dtfim
                        AND s.`status` = 1
                        AND g.estado = 'A'
                        AND pf.`Status` = 1
                        GROUP BY s.cdsaldo;";
						$sql1 = $sqlDisponivel;
		// echo $sqlDisponivel; die();
		$sqlDisponivel = mysqli_query($db, $sqlDisponivel);
		
		if(mysqli_num_rows($sqlDisponivel) > 0){

			while($l = mysqli_fetch_assoc($sqlDisponivel)) {
                
                $valorteto = number_format($l["valorTeto"], 2, ',', '.');
				$array[] = array('CdSaldo' => $l["cdsaldo"], 'CdTeto' => $l["cdteto"], 'valorUnidade' => $l["valorUnidade"], 'ValorTeto' => $valorteto, 'idgenfat' => $l["idgenfat"], 'vigencia' => $l["vigencia"], 'Descricao' => $l["Desc"],  'CdPref' => $l["cdpref"], 'NmCidade' => $l["NmCidade"],  'AbtSaldo' => $l["AbtSaldo"]);
			

			 	$sqlRestante = "SELECT IFNULL(SUM(CASE
													WHEN s.AbtSaldo = 'PPI' THEN ac.valorppi 
													WHEN s.AbtSaldo = 'CTR' THEN IF(ac.CdSolCons IS NULL,ep.valor,ac.valor)
												  END),0) as saldo
								FROM tbsldgensaldo s
								INNER JOIN tbsldteto t ON s.cdsaldo = s.cdsaldo
								INNER JOIN tbsldmovimentacao m ON m.cdteto = t.cdteto AND s.cdsaldo = m.cdsaldo
								INNER JOIN tbsolcons sc on sc.CdSolCons = m.cdsolcons
								INNER JOIN tbespecproc ep ON ep.CdEspecProc = sc.CdEspecProc
								LEFT JOIN tbagendacons ac on ac.CdSolCons = sc.CdSolCons
								LEFT JOIN tbfornecedor_mun fm on fm.CdForn = m.cdubs
								WHERE m.`status` = 1 and t.cdteto = ".$l["cdteto"];
								$sql2 = $sqlRestante;
			//	echo $sqlRestante; 
			//	die();
				$sqlRestante = mysqli_query($db, $sqlRestante);

				if(mysqli_num_rows($sqlRestante) > 0){

					while($r = mysqli_fetch_assoc($sqlRestante)) {
					if($cdunidade > 0){
						$SaldoRes = ($l['valor'] - $r['saldo']);
					
					}else{
						$SaldoRes = ($l['valorTeto'] - $l['valorUnidade']) - $r['saldo'];
					}

					
						if($SaldoRes < 0){ $saldo_mes = $SaldoRes*-1; $SaldoRes = 0; }else{ $saldo_mes = 0; }
						$saldo_mes = number_format($saldo_mes, 2, ',', '.');
						$SaldoRes = number_format($SaldoRes, 2, ',', '.');
						$restante[] = array('SaldoRes' => $SaldoRes,'consumo_extra' => $saldo_mes);
					}
				}
			}

		}else 
			$erro = "Nenhum saldo disponível!";
	}else
	 	$erro = "Erro ao localizar saldo!";
	

	echo json_encode(array('dados' => $array, 'restante' => $restante , 'erro' => $erro, 'sql1' => $sql1, 'sql2' => $sql2));

?>