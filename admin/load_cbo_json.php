<?php 
	
	define("DIRECT_ACCESS",  true);

	include("../verifica.php");
	require_once("../funcoes.php");

	require "../../vendor/autoload.php";
    
    $cdprof = $_REQUEST['cdprof'];

  	$sql = mysqli_query($db,"   SELECT nmespecialidade,cbo.cbo,p.CdProf
                                FROM tbespecialidade cbo 
                                LEFT JOIN tbcredprofissional p on p.cbo = cbo.cbo AND p.cdprof = $cdprof AND p.Status = 1 
								ORDER BY p.CdProf DESC");

	$erro = "";
	if(mysqli_num_rows($sql) > 0)
		while($n = mysqli_fetch_array($sql))
		{
			$cbo[] = array('nmespecialidade' => $n['nmespecialidade'],'cbo' => $n['cbo'],'CdProf' =>$n['CdProf']);
		}
	else 
		$erro = "Nenhuma endereço de atendimento disponível!";

	$array = array('dados' => $cbo, 'erro' => $erro);
	echo json_encode($array);
?>