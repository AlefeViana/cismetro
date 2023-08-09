<?php 
	
	define("DIRECT_ACCESS",  true);

	include("../verifica.php");
	require_once("../funcoes.php");

	require "../../vendor/autoload.php";
	use Stringy\Stringy as S;

    //$Status = (isset($_REQUEST['Status']))?' WHERE tbprofissional.`status` ="'.$_REQUEST['Status'].'"':' WHERE tbprofissional.`status` IS NOT NULL ';
    $CdEspec = (isset($_REQUEST['CdEspec']))?' AND tbfornespec.CdEspec = '.$_REQUEST['CdEspec']:'';

	if ($_REQUEST['CdForn']) {
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
					INNER JOIN tbcredprofissional cp ON cp.CdProf = tbprofissional.cdprof
					WHERE tbprofissional.status = 'a'
					AND tbfornespec.CdForn = $cdforn
					$CdEspec
					AND tbfornespec.`Status` = 1
					AND cp.StatusIdentidade = 1
					AND cp.StatusCPF = 1
					AND cp.StatusCRM = 1
					AND cp.StatusDiploma = 1
					AND cp.`Status` = 1
					GROUP BY tbprofissional.cdprof
					ORDER BY nmprof ASC";
		//echo $query1; die();
					
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
		{
			$nmprof = (String)S::create($n['nmprof'])->titleize(["de", "da", "do"]);
			$profissional[] = array('cdprof' => $n['cdprof'],'nmprof' => $nmprof, 'cnsprof' =>$n['cnsprof'],'crm' =>$n['crm'],'status' =>$n['status']);
		}
	else 
		$erro = "Nenhuma profissional disponível!";

	$array = array('dados' => $profissional, 'erro' => $erro);
	echo json_encode($array);
?>