<?php
require("../../../conecta.php");

session_start();

$cdcredforn = $_POST['cdcredforn'] ?? 0;
$filtro     = $_REQUEST["q"];

$busca = 'AND cprof.CdProf <> ""';
if (!empty(isset($_REQUEST['q']))) {
  $busca = " AND cprof.Nome LIKE  '%$filtro%' AND cprof.CdProf <> ''";
}

if (isset($_REQUEST['page'])) {
  $pagInicio = (intval($_REQUEST['page']) - 1) * 50;
}

$busca_filtro_avancado = "";
if($cdcredforn > 0){
    $busca_filtro_avancado = "AND cforn.CdCredForn = '$cdcredforn'";
}

$sql = "    SELECT  cprof.CdCredProf, 
                    cprof.CdProf,
                    cprof.Nome
    
            FROM        tbcredprofissional  cprof
            INNER JOIN  tbcredfornecedor    cforn ON cforn.CdCredForn = cprof.CdCredForn
    
            WHERE cprof.`Status` = 1

		    $busca
            $busca_filtro_avancado

            GROUP BY  cprof.CdCredProf
            ORDER BY  cprof.Nome ASC";
  
//var_dump($sql);die();

$verifica = mysqli_query($db, $sql);
$qtd_linhas = mysqli_num_rows($verifica);
$sql .= " LIMIT $pagInicio,50";

$query = mysqli_query($db, $sql);
$paginacao_valida = ((intval($_REQUEST['page']) * 50) < $qtd_linhas) ? true : false;

if ($qtd_linhas > 0) {
  $result = [];

  while ($row = mysqli_fetch_assoc($verifica)) {

    $result[] = array(

      'id'      => $row['CdCredProf']   ?? "0",
      'text'    => $row['Nome']         ?? "0",
      'CdProf'  => $row['CdProf']       ?? "0"

    );
  }
  echo json_encode(array('itens' => $result, 'count_filtered' => $qtd_linhas, 'more' => $paginacao_valida));
} else {
  echo json_encode(array('itens' => null, 'count_filtered' => null, 'more' => null));
}
mysqli_close($db);
mysqli_free_result($verifica);
