<?php

 	session_start();
	include("../../funcoes.php");

	if($_SESSION['CdOrigem'] > 0){
		$cdpref = " AND CdPref = " . $_SESSION['CdOrigem'] . " ";
	}

	$sqlPref  = " SELECT CdPref, NmCidade FROM tbprefeitura WHERE `Status` = 1 $cdpref";

	$sqlPref = mysqli_query($db, $sqlPref);

  $erro = '';

	if(mysqli_num_rows($sqlPref) > 0){

		while($l = mysqli_fetch_assoc($sqlPref)) {
			$sqlSaldo = "SELECT t.cdteto, s.cdsaldo, s.vigencia, s.limitacao, s.`Desc`, s.cdpref, s.AbtSaldo, s.`status`,IFNULL(SUM(t.valorTeto),0) AS valor,t.cdteto
      FROM tbsldgensaldo s
      LEFT JOIN tbsldteto t on t.cdsaldo = s.cdsaldo
      WHERE cdpref = ".$l['CdPref']."
      AND s.`status` = 1
      GROUP BY s.cdpref
      ORDER BY s.cdsaldo ";

      // $sql_gastos = "SELECT sl.cdpref, IFNULL( SUM(IF(ac.CdSolCons IS NULL, ep.valor, ac.valor)), 0) AS gastos from tbsldmovimentacao s 
			// LEFT JOIN tbagendacons ac on ac.CdSolCons = s.cdsolcons
      // INNER JOIN tbsolcons sc ON sc.CdSolCons = s.CdSolCons
      // INNER JOIN tbespecproc ep ON sc.CdEspecProc = ep.CdEspecProc
      // INNER JOIN tbsldgensaldo sl ON sl.cdsaldo = s.cdsaldo
			// WHERE sl.cdpref = ".$l['CdPref']." and s.status = 1
      // AND sc.`Status` <> 2 AND sl.`status` = 1";

      $sqlAgendadoMarcado = "SELECT
      sum(
    
        IF (
          sc.`Status` = 'E'
          AND ac.`Status` IS NULL
          AND sc.cdfornespera > 0,
          ep.valor,
          0
        )
      ) AS encaminhadoValor,
      sum(
    
        IF (
          sc.`Status` = 'E'
          AND ac.`Status` IS NULL,
          ep.valor,
          0
        )
    
    ) AS agendadoValor,
    
      sum(
    
        IF (
          sc.`Status` = '1'
          AND ac.`Status` = '1',
          ac.valor,
          0
        )
      ) AS marcadoValor,
      sum(
    
        IF (
          sc.`Status` = '1'
          AND ac.`Status` = '2',
          ac.valor,
          0
        )
      ) AS realizadoValor,
      sum(
    
        IF (
          sc.`Status` = 'F'
          AND ac.`Status` = '1',
          ac.valor,
          0
        )
      ) AS faltaValor
    FROM
      tbsolcons sc
    LEFT JOIN tbagendacons ac ON sc.CdSolCons = ac.CdSolCons
    INNER JOIN tbespecproc ep ON sc.CdEspecProc = ep.CdEspecProc
    INNER JOIN tbpaciente p ON sc.CdPaciente = p.CdPaciente
    WHERE
    sc.CdPref = ".$l['CdPref']."
    AND	YEAR (DATE(NOW())) IN (
        YEAR (
    
          IF (
            sc.`Status` = 'E',
            sc.DtInc,
            ac.DtAgCons
          )
        )
      )";

      $qry_saldo = mysqli_query($db, $sqlSaldo);
      $saldo = mysqli_fetch_assoc($qry_saldo);
      // $qry_gastos = mysqli_query($db,$sql_gastos);
      // $saldo_utilizado = mysqli_fetch_array($qry_gastos);
      $qry_AgendadoMarcado = mysqli_query($db, $sqlAgendadoMarcado);
      $saldoMarcadoAgendado = mysqli_fetch_assoc($qry_AgendadoMarcado);

      
      if(mysqli_num_rows($qry_saldo)){

        $saldo_utilizado = $saldoMarcadoAgendado['agendadoValor'] + $saldoMarcadoAgendado['marcadoValor'] + $saldoMarcadoAgendado['realizadoValor'] + $saldoMarcadoAgendado['faltaValor'];
        
        $saldo_disponivel = $saldo['valor'] - $saldoMarcadoAgendado['agendadoValor'] - $saldoMarcadoAgendado['marcadoValor'] - $saldoMarcadoAgendado['faltaValor'] - $saldoMarcadoAgendado['realizadoValor'];
  
        $array[] = array('nmPref'        =>    $l['NmCidade'], 
                        'valorTeto'      =>    number_format($saldo['valor'], 2, ',', '.'),
                        'valorUtilizado' =>    number_format($saldo_utilizado, 2, ',', '.'), 
                        'valorDisp'      =>    number_format($saldo_disponivel, 2, ',', '.'),
                        'valorAgendado'  =>    number_format($saldoMarcadoAgendado['agendadoValor'], 2, ',', '.'),
                        'valorMarcado'   =>    number_format($saldoMarcadoAgendado['marcadoValor'], 2, ',', '.'));
      }      
		}
	} else {
    $erro = 'Nenhum município encontrado!';
  }

  if (!isset($array)) {   
    $erro = 'Nenhum saldo encontrado!';
  }

	echo json_encode(array('dados' => $array, 'erro' => $erro));
