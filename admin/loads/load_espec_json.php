<?php 
	
	define("DIRECT_ACCESS",  true);

	// include("../../verifica.php");
	require_once("../../funcoes.php");

	require "../../../vendor/autoload.php";
    use Stringy\Stringy as S;
    $CdProc = (isset($_REQUEST['CdProc']))? " AND CdProcedimento in (".$_REQUEST['CdProc'].") " : "";
    $erro = "";
    $sql = " SELECT CdEspecProc, NmEspecProc, cdsus FROM tbespecproc 
			 WHERE Status='1' ".$CdProc."	
    		 ORDER BY NmEspecProc";
   $sql =  mysqli_query($db, $sql);
	if(mysqli_num_rows($sql) > 0)
		while($n = mysqli_fetch_array($sql))
		{
			$NmEspecProc   = (String)S::create($n['NmEspecProc'])->titleize(["de", "da", "do"]);
			$especificacao[] = array('CdEspec' => $n['CdEspecProc'],'NmEspecProc' => $NmEspecProc,'cdsus' =>$n['cdsus']);
		}
	else 
		$erro = "Nenhuma especificação disponível!";

	$array = array('dados' => $especificacao, 'erro' => $erro);
	echo json_encode($array);
?>