<?php 
	
	define("DIRECT_ACCESS",  true);

	include("../verifica.php");
	require_once("../funcoes.php");

	require "../../vendor/autoload.php";
	use Stringy\Stringy as S;

    $Status = (isset($_REQUEST['Status']))?' WHERE tbfornespec.`Status` = '.$_REQUEST['Status'].' AND tbespecproc.`Status` ='.$_REQUEST['Status']:' WHERE tbfornespec.`Status` = 1 AND tbespecproc.`Status` =1';
    $CdForn = (isset($_REQUEST['CdForn']))?' AND tbfornespec.CdForn ='.$_REQUEST['CdForn'].' ':'';
	$CdProf = (isset($_REQUEST['CdProf']))?' AND tbfornespec.cdprof ='.$_REQUEST['CdProf'].' ':'';

  	$sql_consulta = "SELECT
						tbfornespec.`Status`,
						tbfornespec.CdEspec,
						tbfornespec.valorf AS forvalorf,
						tbfornespec.valorc AS forvalorc,
						tbespecproc.valor AS espvalor,
						tbespecproc.valorsus AS espvalorsus,
						tbespecproc.NmEspecProc,
						tbespecproc.`Status`
						FROM
						tbfornespec
						INNER JOIN tbespecproc ON tbfornespec.CdEspec = tbespecproc.CdEspecProc
						INNER JOIN tbcontrato ON tbcontrato.CdForn = tbfornespec.CdForn AND ( tbcontrato.DtValidadef >= NOW() AND tbcontrato.DtValidade <= NOW())
						INNER JOIN tbcontratoespec ON tbcontrato.CdContrato = tbcontratoespec.CdContrato AND tbcontratoespec.CdEspecProc = tbfornespec.CdEspec AND tbcontratoespec.Status = 1
						LEFT JOIN tbespecprocsub sub on sub.CdEspecFilho = tbcontratoespec.CdEspecProc
						$Status
						$CdForn
						$CdProf
						AND tbespecproc.grupoceae = 0
						GROUP BY tbfornespec.CdEspec
						ORDER BY NmEspecProc ASC
						";
	// if($_SESSION['CdUsuario'] == 2){
	// 	echo $sql_consulta; die();
	// }
	$sql = mysqli_query($db,$sql_consulta);
	$erro = "";
	if(mysqli_num_rows($sql) > 0)
		while($n = mysqli_fetch_array($sql))
		{
			$NmEspecProc   = (String)S::create($n['NmEspecProc'])->titleize(["de", "da", "do"]);
			$especificacao[] = array('CdEspec' => $n['CdEspec'],'NmEspecProc' => $NmEspecProc,'forvalorf' =>$n['forvalorf'],'forvalorc' =>$n['forvalorc'],'espvalor' =>$n['espvalor'],'espvalorsus' =>$n['espvalorsus'],'espvalorsus' =>$n['Status']);
		}
	else 
		$erro = "Nenhuma especificação disponível!";

	$array = array('dados' => $especificacao, 'erro' => $erro);
	echo json_encode($array);
?>