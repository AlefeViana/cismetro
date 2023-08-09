<?php
ini_set('display_errors', 'On');
require_once("../../funcoes.php");

if (isset($_REQUEST['cdprof']) && $_REQUEST['cdprof']) {
	$cdprof = "AND p.cdprof = $_REQUEST[cdprof]";
	$select = "nmespecialidade,cbo.cbo,p.CdProf";
} else {
	$cdprof = "";
	$select = "DISTINCT nmespecialidade,cbo.cbo";
}

$sql = mysqli_query($db, "   SELECT $select
                                FROM tbespecialidade cbo 
                                LEFT JOIN tbcredprofissional p on p.cbo = cbo.cbo $cdprof AND p.Status = 1 ");

$erro = "";
if (mysqli_num_rows($sql) > 0)
	while ($n = mysqli_fetch_array($sql)) {
		if (isset($_REQUEST['cdprof']) && $_REQUEST['cdprof']) {
			$cbo[] = array('nmespecialidade' => $n['nmespecialidade'], 'cbo' => $n['cbo'], 'CdProf' => $n['CdProf']);
		} else {
			$cbo[] = array('nmespecialidade' => $n['nmespecialidade'], 'cbo' => $n['cbo']);
		}
	}
else
	$erro = "Nenhuma cbo disponível!";

$array = array('dados' => $cbo, 'erro' => $erro);
echo json_encode($array);
