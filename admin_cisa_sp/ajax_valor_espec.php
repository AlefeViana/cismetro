<?php
	if(isset($_POST["cd_especificacao"]))
    {   
    	require_once("../conecta.php");
        $cd_espec = $_POST["cd_especificacao"];

		$sql_verifica_valor = mysqli_query ("SELECT
											tbespecproc.valor,
											tbespecproc.valorsus
											FROM
											tbespecproc
											WHERE tbespecproc.CdEspecProc = '$cd_espec' ");
		
		$lverifica = mysqli_fetch_array($sql_verifica_valor);

		echo $lverifica[valor];
	}
?>
