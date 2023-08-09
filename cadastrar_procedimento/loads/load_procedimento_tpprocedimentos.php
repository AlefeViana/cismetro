<?php
require("../../conecta.php");

$sql = "SELECT   CdProcedimento, NmProcedimento
        FROM     tbprocedimento 
        WHERE `Status` = 1
        ORDER BY NmProcedimento";

$verifica = mysqli_query($db, $sql);

if (mysqli_num_rows($verifica) > 0) {
  $result = [];

  while ($row = mysqli_fetch_assoc($verifica)) {

    $result[] = array(

      'CdProcedimento' => $row['CdProcedimento'] ?? "0",
      'NmProcedimento' => $row['NmProcedimento'] ?? "0"

    );
  }
  echo json_encode(array('tpproc' => $result));
} else {
  echo json_encode(array('tpproc' => null));
}
mysqli_close($db);
mysqli_free_result($verifica);
