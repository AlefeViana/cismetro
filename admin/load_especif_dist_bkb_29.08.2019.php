<?php 
	$html = header('Content-Type: text/html; charset=iso-8859-1'); 
	require_once("verifica.php");
	require("../funcoes.php");
	
	function retirar_acentos_caracteres_especiais($string) {
		$palavra = strtr($string,"ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåçèéêëìíîïðñòóôõöøùúûüýÿ",									"SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaceeeeiiiionoooooouuuuyy");
		$palavranova = str_replace("_", " ", $palavra);
		return $palavranova; 
	} 

	$periodo = $_GET['periodo'];

	$parte = explode('-',$periodo);

	$uDiaMes = ultimoDiaDoMes($parte[0],$parte[1]);

	//$TpCota = (string)$_GET['tpcota'];
	
	$pref = $_SESSION[CdOrigem];

	/*echo (string)$_GET['tpcota'];
	echo $TpCota;*/
	$sql = "SELECT tbespecproc.CdEspecProc AS CdEspec, tbespecproc.NmEspecProc, tbcotam.qts, tbfornecedor.CdForn, 
			tbfornecedor.NmForn, tbcota.cdcota
			FROM tbespecproc
			INNER JOIN tbcota ON tbespecproc.CdEspecProc = tbcota.CdEspecProc
			INNER JOIN tbcotam ON tbcota.cdcota = tbcotam.cdcota
			INNER JOIN tbgenfat ON tbcota.idgenfat = tbgenfat.idgenfat
			LEFT JOIN tbfornecedor ON tbcota.CdForn = tbfornecedor.CdForn
			WHERE cdpref = '".$pref."'
			AND tbcotam.qts > 0
			AND tbgenfat.dtini = '".$periodo."-01'
			AND tbgenfat.dtfim = '".$periodo."-".$uDiaMes."'
			ORDER BY NmEspecProc ASC";
	//echo $sql;	
	$qry = mysqli_query($db,$sql) or die (mysqli_error());
	
	if (mysqli_num_rows($qry) > 0)
	{
		echo "<option value='0'> Selecione uma especificação  </option>";
		while ($dados = mysqli_fetch_array($qry))
		{
			$disabled1 = '';
			$busca = mysqli_query($db,"SELECT
									tbdistcota.CdCota
									FROM
									tbdistcota
									WHERE CdCota = {$dados[cdcota]} AND CdPref = {$pref}");
					$resultado1 = mysqli_num_rows($busca);
					if ($resultado1) {
						$disabled1 = 'disabled';
					}

			echo "<option $disabled1 value='$dados[CdEspec]' data-id='$dados[qts]'> $dados[NmEspecProc] $dados[NmForn]</option>";
		}  //fim do while
	} //fim do if
	else {
		echo "<option value=''> Nenhuma especifica&ccedil;&atilde;o encontrada  </option>";
		//echo "<option value=\"\"> Nenhum resultado encontrado!  </option>";
	}
	

	mysqli_close();
	mysqli_free_result($qry);
	
?>