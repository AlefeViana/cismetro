<?php 

	include("../funcoes.php");

	$cdpac = $_POST['cdpac'];
	$cdespec = $_POST['cdespec'];
	
	$data = veratend($cdpac, $cdespec);

	if ($data != "" && $data != null) {
		$dt = FormataDataBR($data);
	}else{
		$fail = "NÃ£o existe agendamento para esta especificaÃ§Ã£o!";
	}

	header('Content-Type: application/json; charset=utf-8');

		echo json_encode(array('data' => $dt, 'fail' => $fail));
?>