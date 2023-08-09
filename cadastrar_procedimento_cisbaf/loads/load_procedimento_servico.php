<?php
require("../../conecta.php");

$sql = "SELECT   co_servico, no_servico
        FROM     tbservico 
        ORDER BY no_servico";

$verifica = mysqli_query($db, $sql);

if (mysqli_num_rows($verifica) > 0) {
  $result = [];

  while ($row = mysqli_fetch_assoc($verifica)) {

    $result[] = array(

      'co_servico' => $row['co_servico'] ?? "0",
      'no_servico' => $row['no_servico'] ?? "0"

    );
  }
  echo json_encode(array('servico' => $result));
} else {
  echo json_encode(array('servico' => null));
}
mysqli_close($db);
mysqli_free_result($verifica);
