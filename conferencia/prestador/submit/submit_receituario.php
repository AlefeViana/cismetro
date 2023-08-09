<?php

require("../../../conecta.php");

//recebe as variaveis do formulario
$acao       = $_POST['acao'];
$CdSolCons  = $_POST["cdsolcons"];
$cdprof     = $_POST['profissional'];
$texto      = $_POST['texto'];
$cdpaciente = $_POST['cdpaciente'];
$dataAtual  = date('Y-m-d H:i:s');
$titulo     = 'Receituario - ' . $CdSolCons;

$sql = "SELECT  id 
		FROM 	tbreceituario_new
		WHERE   cdsolcons = '$CdSolCons'";

$query     = mysqli_query($db, $sql);
$receituario_rows = mysqli_num_rows($query);

$sql = "SELECT  id 
        FROM 	tbevolucaoclinica_new
		WHERE   cdsolcons = '$CdSolCons'";

$query     = mysqli_query($db, $sql);
$evolucao_rows = mysqli_num_rows($query);

if ($acao == 'receituario') {

    if ($receituario_rows > 0) {

        $sql = "UPDATE tbreceituario_new SET receituario='$texto', `status`= '1' WHERE cdsolcons = '$CdSolCons'";
        $valida = mysqli_query($db, $sql);
    } else {
        mysqli_query($db, "INSERT INTO tbreceituario_new (cdsolcons, titulo, receituario, cdpaciente, `status`, cduserinc, datahorainc, cduserex, datahoraex, cdusered, datahoraed, tipoagenda, cdprof, entrega, acesso) VALUES ('$CdSolCons', '$titulo ', '$texto', '$cdpaciente', 'NOVO', '$_SESSION[CdUsuario]', '$dataAtual', '$_SESSION[CdUsuario]', '$dataAtual', null, null, '$_SESSION[CdTpUsuario]', '$cdprof', 'N', '0')");
    }
} else if ($acao == 'evolucao') {

    if ($evolucao_rows > 0) {

        $sql = "UPDATE tbevolucaoclinica_new SET evolucaoclinica='$texto', `status`= '1' WHERE cdsolcons = '$CdSolCons'";
        $valida = mysqli_query($db, $sql);
    } else {
        mysqli_query($db, "INSERT INTO tbevolucaoclinica_new (cdsolcons, titulo, evolucaoclinica, cdpaciente, `status`, cduserinc, datahorainc, cduserex, datahoraex, cdusered, datahoraed, tipoagenda, cdprof, entrega, acesso) VALUES ('$CdSolCons', '$titulo ', '$texto', '$cdpaciente', 'NOVO', '$_SESSION[CdUsuario]', '$dataAtual', '$_SESSION[CdUsuario]', '$dataAtual', null, null, '$_SESSION[CdTpUsuario]', '$cdprof', 'N', '0')");
    }
}

echo json_encode(true);
mysqli_close($db);
