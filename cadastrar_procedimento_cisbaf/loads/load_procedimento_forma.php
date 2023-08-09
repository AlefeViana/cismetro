<?php
require("../../conecta.php");

$cdProcedimento = $_POST['cdProcedimento'];
$cdgrupoproc = $_POST['cdgrupoproc'];

$busca = '';
if (isset($_REQUEST['q'])) {
  $busca = ' AND nmgrupoproc LIKE  "%' . $_REQUEST['q'] . '%"';
}

$sql = "SELECT
          CdForma, NmForma
        FROM
          tbgrupoforma
        WHERE
          CdProcedimento = $cdProcedimento
        AND CdSubGrupo = ( SELECT CdSubGrupo FROM tbgrupoproc WHERE cdgrupoproc = $cdgrupoproc LIMIT 0, 1 )";

$verifica = mysqli_query($db, $sql);

if (mysqli_num_rows($verifica) > 0) {
  $result = [];

  while ($row = mysqli_fetch_assoc($verifica)) {

    $result[] = array(

      'id' => $row['CdForma'] ?? "0",
      'text' => $row['NmForma'] ?? "0"

    );
  }
  echo json_encode(array('itens' => $result));
} else {
  echo json_encode(array('itens' => null));
}
mysqli_close($db);
mysqli_free_result($verifica);
