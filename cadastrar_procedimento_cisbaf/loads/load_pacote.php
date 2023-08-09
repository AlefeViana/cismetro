<?php
require("../../conecta.php");

$CdEspecProc = $_POST['cdespecproc'];

$espec_cond = '';
if (!empty($CdEspecProc)) {
  $espec_cond = " AND app.cdespec_pact = '$CdEspecProc'";
}

$sql = "SELECT DISTINCT ep.CdEspecProc,
                        ep.NmEspecProc,
                        app.cdespec_pact,
                        app.cdespec
        FROM      tbespecproc ep
        LEFT JOIN tbespecproc_pact app  ON app.cdespec = ep.CdEspecProc 
                                        AND app.status = 1
                                        $espec_cond 
                                        WHERE 
	ep.`Status` = 1
        GROUP BY ep.CdEspecProc
        ORDER BY app.cdespec DESC";

// var_dump($sql); die();

$verifica = mysqli_query($db, $sql);

if (mysqli_num_rows($verifica) > 0) {
  $result = [];

  while ($row = mysqli_fetch_assoc($verifica)) {

    $result[] = array(

      'CdEspecProc'   => $row['CdEspecProc']  ?? "0",
      'NmEspecProc'   => $row['NmEspecProc']  ?? "0",
      'cdespec_pact'  => $row['cdespec_pact'] ?? "0",
      'cdespec'       => $row['cdespec']      ?? "0"

    );
  }

  echo json_encode(array('pacote' => $result));
} else {
  echo json_encode(array('pacote' => null));
}
mysqli_close($db);
mysqli_free_result($verifica);
