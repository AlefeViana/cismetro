<?php
require("../../conecta.php");

$cdProcedimento = $_POST['cdProcedimento'];

$busca = '';
if (isset($_REQUEST['q'])) {
  $busca = ' AND nmgrupoproc LIKE  "%' . $_REQUEST['q'] . '%"';
}

$sql = "SELECT 
          cdgrupoproc, nmgrupoproc
        FROM
          tbgrupoproc 
        WHERE
          CdProcedimento = $cdProcedimento
          $busca
        ORDER BY 
          nmgrupoproc";

$verifica = mysqli_query($db, $sql);

if (mysqli_num_rows($verifica) > 0) {
  $result = [];

  while ($row = mysqli_fetch_assoc($verifica)) {

    $result[] = array(

      'id' => $row['cdgrupoproc'] ?? "0",
      'text' => $row['nmgrupoproc'] ?? "0"

    );
  }
  echo json_encode(array('itens' => $result));
} else {
  echo json_encode(array('itens' => null));
}
mysqli_close($db);
mysqli_free_result($verifica);
