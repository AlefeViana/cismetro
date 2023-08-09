<?php 
	session_start();
	require_once("../funcoes.php");

    $CdEspecProc = (isset($_REQUEST['CdEspecProc']))?' AND tbagvivavida_itens.cdespec ='.$_REQUEST['CdEspecProc']:'';
    $CdForn = (isset($_REQUEST['CdForn']))?' AND tbagvivavida.cdforn ='.$_REQUEST['CdForn']:'';

  	$sql = mysqli_query($db,"SELECT
							tbagvivavida.cdagvivavida,
							tbagvivavida.hrag,
							tbagvivavida.dtag,
							date_format(tbagvivavida.dtag,'%d/%m/%Y') as dtagbr,
							tbprofissional.nmprof
							FROM
							tbagvivavida
							INNER JOIN tbagvivavida_itens ON tbagvivavida.cdagvivavida = tbagvivavida_itens.cdagvv
							INNER JOIN tbprofissional ON tbagvivavida.cdmed = tbprofissional.cdprof
							WHERE tbagvivavida.estado = 'A'
							AND tbagvivavida.cdpac is NULL
							$CdForn
							$CdEspecProc
							ORDER BY tbagvivavida.dtag ASC,tbagvivavida.hrag ASC
						");

	$erro = "";
	if(mysqli_num_rows($sql) > 0)
		while($n = mysqli_fetch_array($sql))
			$agendaceae[] = array('cdagvivavida'=>$n["cdagvivavida"],'nmprof'=>$n["nmprof"],'dtagbr'=>$n["dtagbr"],'dtag'=>$n["dtag"],'hrag'=>$n["hrag"]);
	else 
		$erro = "Nenhuma agenda CEAE disponÃ­vel!";

	$array = array('dados' => $agendaceae, 'erro' => $erro);
	echo json_encode($array);
?>