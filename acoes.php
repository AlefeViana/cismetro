<?php
	session_start();
	include "conecta.php";
	include 'funcoes.php';

	if (isset($_POST['prioridade'])) {
		$cd 		= $_POST['prioridade'];
		$cdcemtriagem 	= (isset($_POST['cdcemtriagem']))?$_POST['cdcemtriagem']:0;
		$cdceaetriagem 	= (isset($_POST['cdceaetriagem']))?$_POST['cdceaetriagem']:0;
		$acao = 'P';
		if ($cdcemtriagem) {
			mysqli_query($db,"UPDATE tbcemtriagem SET prioridade = 1 WHERE cdsolcons = $cd AND cdcemtriagem = $cdcemtriagem");
			setTriagemCemLog($cdcemtriagem,$acao);
		}
		if ($cdceaetriagem) {
			mysqli_query($db,"UPDATE tbceaetriagem SET prioridade = 1 WHERE cdagvivavida = $cd AND cdceaetriagem = $cdceaetriagem");
			setTriagemCeaeLog($cdceaetriagem,$acao);
		}
	}

	if (isset($_POST['dprioridade'])) {
		$cd 		= $_POST['dprioridade'];
		$cdcemtriagem 	= $_POST['cdcemtriagem'];
		$cdceaetriagem 	= $_POST['cdceaetriagem'];
		$acao = 'D';
		if ($cdcemtriagem) {
			mysqli_query($db,"UPDATE tbcemtriagem SET prioridade = 0 WHERE cdsolcons = $cd AND cdcemtriagem = $cdcemtriagem");
			setTriagemCemLog($cdcemtriagem,$acao);
		}
		if ($cdceaetriagem) {
			mysqli_query($db,"UPDATE tbceaetriagem SET prioridade = 0 WHERE cdagvivavida = $cd AND cdceaetriagem = $cdceaetriagem");
			setTriagemCeaeLog($cdceaetriagem,$acao);
		}
	}
?>