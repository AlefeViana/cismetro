<?php
require("../../../conecta.php");

session_start();

$cdcredprof = $_POST['cdcredprof'] ?? 0;
$filtro     = $_REQUEST["q"];

$busca = '';
if (!empty(isset($_REQUEST['q']))) {
  $busca = " AND ep.NmEspecProc LIKE  '%$filtro%'";
}

if (isset($_REQUEST['page'])) {
  $pagInicio = (intval($_REQUEST['page']) - 1) * 5;
}

$busca_filtro_avancado = "";
if($cdcredprof > 0){
    $busca_filtro_avancado = "AND atend.CdCredProf = '$cdcredprof'";
}

$sql = "    SELECT  ep.CdEspecProc,
                    ep.NmEspecProc
    
            FROM        tbespecproc                         ep
            INNER JOIN  tbcredprofissionallocalatendespec   atendespec  ON atendespec.CdEspecProc = ep.CdEspecProc
            INNER JOIN  tbcredprofissionallocalatend        atend       ON atend.CdCredProfLocal = atendespec.CdCredProfLocal
    
            WHERE ep.`Status` = 1

		    $busca
            $busca_filtro_avancado

            GROUP BY  ep.CdEspecProc
            ORDER BY  ep.NmEspecProc ASC";
  
//var_dump($sql);die();

$verifica = mysqli_query($db, $sql);
$qtd_linhas = mysqli_num_rows($verifica);
$sql .= " LIMIT $pagInicio,5";

$query = mysqli_query($db, $sql);
$paginacao_valida = ((intval($_REQUEST['page']) * 5) < $qtd_linhas) ? true : false;

if ($qtd_linhas > 0) {
  $result = [];

  while ($row = mysqli_fetch_assoc($verifica)) {

    $result[] = array(

      'id'      => $row['CdEspecProc']  ?? "0",
      'text'    => $row['NmEspecProc']  ?? "0",

    );
  }
  echo json_encode(array('itens' => $result, 'count_filtered' => $qtd_linhas, 'more' => $paginacao_valida));
} else {
  echo json_encode(array('itens' => null, 'count_filtered' => null, 'more' => null));
}
mysqli_close($db);
mysqli_free_result($verifica);
