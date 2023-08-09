<?php
require("../../conecta.php");

$CdUsuario = $_SESSION['CdUsuario'];

$sql = "SELECT   CdMaquina, Agente, DATE_FORMAT(DtInc, '%d/%m/%Y') AS DtInc
        FROM     tbmaquinas_confiaveis 
        WHERE    `status`  = 1
        AND      CdUsuario = $CdUsuario
        ORDER BY CdMaquina";
// echo $sql; die();
$verifica = mysqli_query($db, $sql);

if (mysqli_num_rows($verifica) > 0) {
    $result = [];

    while ($row = mysqli_fetch_assoc($verifica)) {

        $result[] = array(

            'id'          => $row['CdMaquina']  ?? "0",
            'navegador'   => $row['Agente']     ?? "0",
            'acesso'      => $row['DtInc']      ?? "0"

        );
    }
    echo json_encode(array('mc' => $result));
} else {
    echo json_encode(array('mc' => null));
}

mysqli_close($db);
mysqli_free_result($verifica);
