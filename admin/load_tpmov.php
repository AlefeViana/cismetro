<?php 

	define("DIRECT_ACCESS",  true);

	require_once("verifica.php");
	require("../conecta.php");
	
	$cdtp = (int)$_GET['cdtp'];
		$query = "SELECT
				tbtpmovfinanceira.cdtpmovfinanceira,
				tbtpmovfinanceira.acao
				FROM
				tbtpmovfinanceira
				WHERE tbtpmovfinanceira.cdtpmovfinanceira = $cdtp"
				;
		$resultado = mysqli_query($db,$query)or die('nproc - '.mysqli_error());
		$numrow = mysqli_numrows($resultado);
		if ($numrow) {
			while ($dados = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
				$acao = $dados['acao'];
			}
			if ($acao =='A') {
				echo '<option value="">Selecione .. </option>
					<option value="C">CR&Eacute;DITO</option>
					<option value="D">D&Eacute;BITO</option>';
			}else if ($acao =='C') {
				echo '<option value="">Selecione .. </option>
					<option value="C">CR&Eacute;DITO</option>';
			}else if ($acao =='D') {
				echo '<option value="">Selecione .. </option>
					<option value="D">D&Eacute;BITO</option>';
			}

		}
	
	mysqli_close();
	mysqli_free_result($resultado);
?>