<?php 
	session_start();
	require_once("../funcoes.php");

    $Status = (isset($_REQUEST['Status']))?' WHERE tbfornespec.`Status` = '.$_REQUEST['Status'].' AND tbespecproc.`Status` ='.$_REQUEST['Status']:' WHERE tbfornespec.`Status` = 1 AND tbespecproc.`Status` =1';
    $CdForn = (isset($_REQUEST['CdForn']))?' AND tbfornespec.CdForn ='.$_REQUEST['CdForn'].' ':'';


  	$sql = mysqli_query($db,"SELECT
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
						$Status
						$CdForn
						AND tbespecproc.grupoceae = 0
						GROUP BY tbfornespec.CdEspec
						ORDER BY NmEspecProc ASC
						");

	$erro = "";
	if(mysqli_num_rows($sql) > 0)
		while($n = mysqli_fetch_array($sql))
			$especificacao[] = array('CdEspec' => $n['CdEspec'],'NmEspecProc' => $n['NmEspecProc'],'forvalorf' =>$n['forvalorf'],'forvalorc' =>$n['forvalorc'],'espvalor' =>$n['espvalor'],'espvalorsus' =>$n['espvalorsus'],'espvalorsus' =>$n['Status']);
	else 
		$erro = "Nenhuma especificação disponível!";

	$array = array('dados' => $especificacao, 'erro' => $erro);
	echo json_encode($array);
?>