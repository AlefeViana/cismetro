<?php
require("../../conecta.php");

$co_servico = $_POST['co_servico'];

$sql = "SELECT   co_classificacao, no_classificacao
        FROM     tbservico_classificacao 
        WHERE    co_servico = $co_servico
        ORDER BY co_classificacao";

$verifica = mysqli_query($db, $sql);

if (mysqli_num_rows($verifica) > 0) {
  $result = [];

  while ($row = mysqli_fetch_assoc($verifica)) {

    $result[] = array(

      'co_servico' => $row['co_servico'] ?? "0",
      'co_classificacao' => $row['co_classificacao'] ?? "0",
      'no_classificacao' => $row['no_classificacao'] ?? "0"

    );
  }
  echo json_encode(array('class' => $result));
} else {
  echo json_encode(array('class' => null));
}
mysqli_close($db);
mysqli_free_result($verifica);