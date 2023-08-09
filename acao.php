<?php
	session_start();
	include "conecta.php";
	include 'funcoes.php';

	if (isset($_POST['prioridade'])) {
		$id = $_POST['prioridade'];
		$data = date("Y/m/d H:i:s");
		mysqli_query($db,"UPDATE tbtriagem SET Prioridade = '0', DtHPrio = '{$data}' WHERE CdSolCons = {$id}");
	}
	if (isset($_POST['dprioridade'])) {
		$id = $_POST['dprioridade'];
		mysqli_query($db,"UPDATE tbtriagem SET Prioridade = '1', DtHPrio = NULL WHERE CdSolCons = {$id}");
	}
?>