<?php 
	session_start();
	require_once("../../funcoes.php");

	require "../../../vendor/autoload.php";
	use Stringy\Stringy as S;

	$CdPref = (isset($_REQUEST["cdpref"]))?" AND tbprefeitura.CdPref = $_REQUEST[cdpref] ": (($_SESSION['CdOrigem'] > 0)? " AND tbprefeitura.CdPref = ".$_SESSION['CdOrigem']:"");
	$Status = (isset($_REQUEST["status"]))?" AND tbprefeitura.`Status` = $_REQUEST[status] ":"";
	$CdEstado = (isset($_REQUEST["cdestado"]))?" AND tbprefeitura.CdEstado = $_REQUEST[cdestado] ":"";
	$Consorciado = (isset($_REQUEST["consorciado"]))?" AND tbprefeitura.consorciado = '$_REQUEST[consorciado]' ":"";


	$query = "SELECT
			tbprefeitura.CdPref,
			tbprefeitura.NmCidade,
			tbprefeitura.`Status`,
			tbprefeitura.consorciado
			FROM
			tbprefeitura
			WHERE tbprefeitura.CdPref IS NOT NULL
			$CdPref
			$CdEstado
			$Status
			$Consorciado
			AND tbprefeitura.consorciado = 'S'
			ORDER BY tbprefeitura.NmCidade ASC";

	//echo $query;
	$sql = mysqli_query($GLOBALS['db'],$query);

	$erro = "";
	if(mysqli_num_rows($sql) > 0)
		while($n = mysqli_fetch_array($sql))
		{
			$NmCidade   = (String)S::create($n['NmCidade'])->titleize(["de", "da", "do"]);
			$municipio[] = array('CdPref' => $n["CdPref"], 'NmCidade' =>$NmCidade,'Status' => $n["Status"],'Consorciado' => $n["consorciado"] );
		}
	else 
		$erro = "Nenhuma Município disponível!";

	$array = array('dados' => $municipio, 'erro' => $erro);
	echo json_encode($array);