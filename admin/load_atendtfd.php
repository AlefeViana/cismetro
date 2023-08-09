<?php 

	define("DIRECT_ACCESS",  true);

	include("../verifica.php");

	include("../funcoes.php");

	$cdpac = $_POST['cdpac'];
	$cdespec = $_POST['cdespec'];
	
	$data = veratend($cdpac, $cdespec);

	if ($data != "" && $data != null) {
		$dt = FormataDataBR($data);
	}else{
		$fail = "Não existe agendamento para esta especificação!";
	}


		echo json_encode(array('data' => $dt, 'fail' => $fail));
?>