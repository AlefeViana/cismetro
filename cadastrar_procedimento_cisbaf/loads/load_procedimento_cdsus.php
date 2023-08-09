<?php

require("../../conecta.php");

$cdsus = $_POST['cdsus'];
$dataVigencia = date("Y-m-d");
$codigoEspecProcSQL = $CdEspecProc;
$codigoEspecProcSQL = (int)$codigoEspecProcSQL;

/////////////////////////////////// SELECT`S ////////////////////////////////////////////////

/// Busca a licitacao vigente
$sqlBuscaLctVigente = "SELECT fl.cdlicitacao
                       FROM tblctfornecedor_licitacao fl
                       INNER JOIN  tblctespeclicitacao lel ON fl.cdlicitacao = lel.cdlicitacao 
                       INNER JOIN tbespecproc ep ON ep.CdEspecProc = lel.cdespec
                       WHERE fl.`status` = '1'
                       AND fl.datainicio <= '$dataVigencia'
                       AND fl.datafim >= '$dataVigencia'
                       ORDER BY fl.cdlicitacao DESC LIMIT 1";

$resultSQLVigente = mysqli_query($db, $sqlBuscaLctVigente);
$credenciadoVigente = mysqli_fetch_array($resultSQLVigente) or die('Nenhum credenciamento vigente encontrado! Atualize os dados de credenciamento! - Linha 93');

/// Convertendo a variavel cdlicitacao para tipo de variavel numero inteiro
$codigoLicitacao = $credenciadoVigente['cdlicitacao'];
$codigoLicitacao = (int)$codigoLicitacao;

/// Busca pela Espec na tabela da atual licitação vigente
$sqlBuscaEspecLct = "SELECT * 
                          FROM
                            tblctespeclicitacao lel
                          INNER JOIN
                            tbespecproc ep ON ep.CdEspecProc = lel.cdespec
                          WHERE
                            lel.cdlicitacao = $codigoLicitacao
                          AND
                            ep.cdsus = '$cdsus'
                          AND 
                            lel.`status` = '1'
                          ";

$verificaEspectLct = mysqli_query($db, $sqlBuscaEspecLct);

if (mysqli_num_rows($verificaEspectLct) >= 0) {
  $result = [];

while ($row = mysqli_fetch_assoc($verificaEspectLct)) {

    $array = array(
      'cdsus' => $row['cdsus'] ?? "0",
      'NmEspecProc' => $row['NmEspecProc'] ?? "0",
      'valor' => $row['valor'] ?? "0",
      'valorsus' => $row['valorsus'] ?? "0",
      'CdEspecProc' => $row['CdEspecProc'] ?? "0"
    );
    $result[] = $array;
  }
  echo json_encode(array('dados' => $result));
} else {
  echo json_encode(array('dados' => null));
}
mysqli_close($db);
mysqli_free_result($verificaEspectLct);

