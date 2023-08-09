<?php
require("../../conecta.php");

$sql = "SELECT   cdespecialidade, nmespecialidade
        FROM     tbespecialidade 
        ORDER BY nmespecialidade";

$verifica = mysqli_query($db, $sql);

if (mysqli_num_rows($verifica) > 0) {
  $result = [];

  while ($row = mysqli_fetch_assoc($verifica)) {

    $result[] = array(

      'cdespecialidade' => $row['cdespecialidade'] ?? "0",
      'nmespecialidade' => $row['nmespecialidade'] ?? "0"

    );
  }
  echo json_encode(array('especialidade' => $result));
} else {
  echo json_encode(array('especialidade' => null));
}
mysqli_close($db);
mysqli_free_result($verifica);