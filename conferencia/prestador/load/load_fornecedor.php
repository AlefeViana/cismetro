<?php

require("../../../conecta.php");
require_once("../../../funcoes.php");

$filtro     = $_REQUEST["q"];

$busca = '';
if (isset($_REQUEST['q'])) {
  $busca = " AND Nome LIKE  '%$filtro%'";
}
if (isset($_REQUEST['page'])) {
  $pagInicio = (intval($_REQUEST['page']) - 1) * 50;
}

$sql = "SELECT      CdCredForn, Nome, CdForn
        FROM        tbcredfornecedor
        WHERE       `Status` = 1
        $busca
        GROUP BY    CdCredForn";

//var_dump($sql); die();

$verifica = mysqli_query($db, $sql);

$qtd_linhas = mysqli_num_rows($verifica);
$sql .= " LIMIT $pagInicio,50";

$verifica = mysqli_query($db, $sql);
$paginacao_valida = ((intval($_REQUEST['page']) * 50) < $qtd_linhas) ? true : false;

$result = [];

if ($qtd_linhas > 0) {

  while ($row = mysqli_fetch_assoc($verifica)) {

    $result[] = array(

      'id'      => $row['CdCredForn']   ?? "0",
      'text'    => $row['Nome']         ?? "0",
      'CdForn'  => $row['CdForn']       ?? "0"

    );
  }
  echo json_encode(array('itens' => $result, 'count_filtered' => $qtd_linhas, 'more' => $paginacao_valida));
} else {
  echo json_encode(array('itens' => $result, 'count_filtered' => $qtd_linhas, 'more' => $paginacao_valida));
}
mysqli_close($db);
mysqli_free_result($verifica);
