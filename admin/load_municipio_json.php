<?php
	define("DIRECT_ACCESS",  true);

	require("../funcoes.php");

    $sql = mysqli_query($db, "SELECT * FROM tbprefeitura WHERE consorciado = 'S' AND status = 1");

    while($row = mysqli_fetch_array($sql)){

        $cdpref = $row['CdPref'];
        $nmpref = $row['NmCidade'];
        $dados [] = array(
            'id' => $cdpref,
            'nome' => $nmpref
        );
    }
    $array = array('dados' => $dados);
	echo json_encode($array);
?>