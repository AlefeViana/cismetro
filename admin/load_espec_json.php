<?php 
	
	define("DIRECT_ACCESS",  true);

	include("../verifica.php");
	require_once("../funcoes.php");

	require "../../vendor/autoload.php";
    use Stringy\Stringy as S;
    
	$CdProc = (int)$_REQUEST['CdProc'];
    $erro = "";
    $sql = "SELECT CdEspecProc, NmEspecProc, cdsus FROM tbespecproc";	
	$sql .= " WHERE Status='1' AND CdProcedimento=".$CdProc;		
    $sql .=  " ORDER BY NmEspecProc";
	//	echo $sql;
   $sql =  mysqli_query($db, $sql);
	if(mysqli_num_rows($sql) > 0)
		while($n = mysqli_fetch_array($sql))
		{
			$NmEspecProc   = (String)S::create($n['NmEspecProc'])->titleize(["de", "da", "do"]);
			// $especificacao[] = array('CdEspec' => $n['CdEspecProc'],'NmEspecProc' => $NmEspecProc,'forvalorf' =>$n['forvalorf'],'forvalorc' =>$n['forvalorc'],'espvalor' =>$n['espvalor'],'espvalorsus' =>$n['espvalorsus'],'espvalorsus' =>$n['Status']);
			$especificacao[] = array('CdEspec' => $n['CdEspecProc'],'NmEspecProc' => $NmEspecProc,'cdsus' =>$n['cdsus']);
		}
	else 
		$erro = "Nenhuma especificação disponível!";

	$array = array('dados' => $especificacao, 'erro' => $erro);
	echo json_encode($array);
?>