<?php 
	session_start();
	require_once("../funcoes.php");

    $Status = (isset($_REQUEST['Status']))?' WHERE tbprofissional.`status` ="'.$_REQUEST['Status'].'"':' WHERE tbprofissional.`status` IS NOT NULL ';
    $CdEspec = (isset($_REQUEST['CdEspec']))?' AND tbfornespec.CdEspec = '.$_REQUEST['CdEspec']:'';

	if ($_REQUEST['CdForn']) {
		$query1 = "SELECT
					tbfornespec.CdForn,
					tbfornespec.cdprof,
					tbfornespec.CdEspec,
					UPPER(tbprofissional.nmprof) AS nmprof,
					tbprofissional.cnsprof,
					tbprofissional.crm,
					tbprofissional.`status`
					FROM
					tbfornespec
					INNER JOIN tbprofissional ON tbfornespec.cdprof = tbprofissional.cdprof
					$Status
					AND tbfornespec.CdForn = $_REQUEST[CdForn]
					$CdEspec
					GROUP BY cdprof
					ORDER BY nmprof ASC";
	}else{
		$query1 = "SELECT
					tbprofissional.cdprof,
					UPPER(tbprofissional.nmprof) AS nmprof,
					tbprofissional.cnsprof,
					tbprofissional.crm,
					tbprofissional.`status`
					FROM
					tbprofissional
					$Status
					ORDER BY nmprof ASC
					";
	}
  	$sql = mysqli_query($db,$query1);

	$erro = "";
	if(mysqli_num_rows($sql) > 0)
		while($n = mysqli_fetch_array($sql))
			$profissional[] = array('cdprof' => $n['cdprof'],'nmprof' => $n['nmprof'], 'cnsprof' =>$n['cnsprof'],'crm' =>$n['crm'],'status' =>$n['status']);
	else 
		$erro = "Nenhuma profissional disponÃ­vel!";

	$array = array('dados' => $profissional, 'erro' => $erro);
	echo json_encode($array);
?>