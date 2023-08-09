<?php

 	session_start();
	include("../../funcoes.php");
	
	//$Paciente  = descobrePaciente($CdPaciente);
	//$cdespec 	 = $_REQUEST['cdespec'];
	$CdPref		 = $_REQUEST['cd_pref'];
    $CdProc		 = $_REQUEST['cd_proc'];
	$dtinicio    = $_REQUEST['dtinicio'];
    $dttermino   = $_REQUEST['dttermino'];

	$erro = "";

    if ($CdPref <> null && $CdPref <> 0) {
        $Pref = " s.cdpref IN ($CdPref)";
    } else {
        $Pref = " ";
    }

    if ($CdProc <> null && $CdProc <> 0) {
        $Proc = " AND sp.cdprocedimento IN ($CdProc)";
    } else {
        $Proc = " ";
    }

	if(isset($_REQUEST['cd_proc']) AND isset($_REQUEST['cd_pref']) AND isset($_REQUEST['dtinicio']) AND isset($_REQUEST['dttermino'])){

		$sqlDisponivel  = " SELECT t.cdsaldo,t.cdteto,t.idgenfat,SUM(t.valorTeto) as valorTeto,SUM(t.valorUnidade) as valorUnidade,s.vigencia,s.`Desc`,s.cdpref,s.AbtSaldo,sp.cdprocedimento,ep.CdEspecProc
							FROM tbsldgensaldo s 
							INNER JOIN tbsldteto t on t.cdsaldo = s.cdsaldo
							INNER JOIN tbgenfat g on g.idgenfat = t.idgenfat
							INNER JOIN tbsldgensaldo_proc sp on sp.cdsaldo = s.cdsaldo AND sp.`status` = 1
							INNER JOIN tbprefeitura pf on pf.CdPref = s.cdpref
							LEFT JOIN tbespecproc ep on ep.CdProcedimento = sp.cdprocedimento
							WHERE $Pref AND IF(s.limitacao = 'CM','$dtinicio' >=  g.dtini,'$dtinicio' >=  g.dtini AND '$dttermino' <= g.dtfim)
							AND s.`status` = 1 AND g.estado = 'A' $Proc AND pf.`Status` = 1 
							GROUP BY s.cdsaldo";
		// echo $sqlDisponivel; die();
		$sqlDisponivel = mysqli_query($db, $sqlDisponivel);

		if(mysqli_num_rows($sqlDisponivel) > 0){

			while($l = mysqli_fetch_assoc($sqlDisponivel)) {

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
								WHERE m.`status` = 1 and t.cdteto = ".$l["cdteto"];
				//echo $sqlRestante; 
				$sqlRestante = mysqli_query($db, $sqlRestante);

				if(mysqli_num_rows($sqlRestante) > 0){

					while($r = mysqli_fetch_assoc($sqlRestante)) {

						$SaldoRes = ($l['valorTeto'] - $l['valorUnidade']) - $r['saldo'];
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
	

	echo json_encode(array('dados' => $array, 'restante' => $restante , 'erro' => $erro));

?>