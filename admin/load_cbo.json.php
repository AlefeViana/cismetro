<?php 
ini_set('display_errors', 'On');
	require_once("../funcoes.php");
    
    $cdprof = $_REQUEST['cdprof'];

  	$sql = mysqli_query($db,"   SELECT nmespecialidade,cbo.cbo,p.CdProf
                                FROM tbespecialidade cbo 
                                LEFT JOIN tbcredprofissional p on p.cbo = cbo.cbo AND p.cdprof = $cdprof AND p.Status = 1 ");

	$erro = "";
	if(mysqli_num_rows($sql) > 0)
		while($n = mysqli_fetch_array($sql))
		{
			$cbo[] = array('nmespecialidade' => $n['nmespecialidade'],'cbo' => $n['cbo'],'CdProf' =>$n['CdProf']);
		}
	else 
		$erro = "Nenhuma cbo disponível!";

	$array = array('dados' => $cbo, 'erro' => $erro);
	echo json_encode($array);
?>