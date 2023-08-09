<?php

require_once("../../funcoes.php");

require "../../../vendor/autoload.php";

use Stringy\Stringy as S;

//$Status = (isset($_REQUEST['Status']))?' WHERE tbprofissional.`status` ="'.$_REQUEST['Status'].'"':' WHERE tbprofissional.`status` IS NOT NULL ';
$CdEspec = (isset($_REQUEST['CdEspec'])) ? ' AND tbfornespec.CdEspec = ' . $_REQUEST['CdEspec'] : '';

if (isset($_REQUEST['CdForn']) && $_REQUEST['CdForn'] != '' && $_REQUEST['CdForn'] != null) {
	$cdforn = $_REQUEST['CdForn'];
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
					WHERE tbprofissional.status = 'a'
					AND tbfornespec.CdForn in ($cdforn)
					$CdEspec
					GROUP BY cdprof
					ORDER BY nmprof ASC";
	//echo $query1; die();

} else {
	$query1 = "SELECT
					tbprofissional.cdprof,
					UPPER(tbprofissional.nmprof) AS nmprof,
					tbprofissional.cnsprof,
					tbprofissional.crm,
					tbprofissional.`status`
					FROM
					tbprofissional
					ORDER BY nmprof ASC
					";
}
$sql = mysqli_query($db, $query1);

$erro = "";
if (mysqli_num_rows($sql) > 0)
	while ($n = mysqli_fetch_array($sql)) {
		$nmprof = (string)S::create($n['nmprof'])->titleize(["de", "da", "do"]);
		$profissional[] = array('cdprof' => $n['cdprof'], 'nmprof' => $nmprof, 'cnsprof' => $n['cnsprof'], 'crm' => $n['crm'], 'status' => $n['status']);
	}
else
	$erro = "Nenhuma profissional disponível!";

$array = array('dados' => $profissional, 'erro' => $erro);
echo json_encode($array);
