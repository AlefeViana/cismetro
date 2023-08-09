<?php
require("../../conecta.php");

$sql = "SELECT   cdgrupoproc, nmgrupoproc
        FROM     tbgrupoproc 
        ORDER BY nmgrupoproc";

$verifica = mysqli_query($db, $sql);

if (mysqli_num_rows($verifica) > 0) {
  $result = [];

  while ($row = mysqli_fetch_assoc($verifica)) {

    $result[] = array(

      'cdgrupoproc' => $row['cdgrupoproc'] ?? "0",
      'nmgrupoproc' => $row['nmgrupoproc'] ?? "0"

    );
  }
  echo json_encode(array('grupo' => $result));
} else {
  echo json_encode(array('grupo' => null));
}
mysqli_close($db);
mysqli_free_result($verifica);
