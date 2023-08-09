<?php 
	$html = header('Content-Type: text/html; charset=iso-8859-1'); 
	require_once("verifica.php");
	require("../funcoes.php");
	
	function retirar_acentos_caracteres_especiais($string) {
		$palavra = strtr($string,"Ãƒâ€¦Ã‚Â Ãƒâ€¦Ã¢â‚¬â„¢Ãƒâ€¦Ã‚Â½Ãƒâ€¦Ã‚Â¡Ãƒâ€¦Ã¢â‚¬Å“Ãƒâ€¦Ã‚Â¾Ãƒâ€¦Ã‚Â¸Ãƒâ€šÃ‚Â¥Ãƒâ€šÃ‚ÂµÃƒÆ’Ã¢â€šÂ¬ÃƒÆ’Ã¯Â¿Â½ÃƒÆ’Ã¢â‚¬Å¡ÃƒÆ’Ã†â€™ÃƒÆ’Ã¢â‚¬Å¾ÃƒÆ’Ã¢â‚¬Â¦ÃƒÆ’Ã¢â‚¬Â ÃƒÆ’Ã¢â‚¬Â¡ÃƒÆ’Ã‹â€ ÃƒÆ’Ã¢â‚¬Â°ÃƒÆ’Ã…Â ÃƒÆ’Ã¢â‚¬Â¹ÃƒÆ’Ã…â€™ÃƒÆ’Ã¯Â¿Â½ÃƒÆ’Ã…Â½ÃƒÆ’Ã¯Â¿Â½ÃƒÆ’Ã¯Â¿Â½ÃƒÆ’Ã¢â‚¬ËœÃƒÆ’Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å“ÃƒÆ’Ã¢â‚¬ï¿½ÃƒÆ’Ã¢â‚¬Â¢ÃƒÆ’Ã¢â‚¬â€œÃƒÆ’Ã‹Å“ÃƒÆ’Ã¢â€žÂ¢ÃƒÆ’Ã…Â¡ÃƒÆ’Ã¢â‚¬ÂºÃƒÆ’Ã…â€œÃƒÆ’Ã¯Â¿Â½ÃƒÆ’Ã…Â¸ÃƒÆ’Ã‚Â ÃƒÆ’Ã‚Â¡ÃƒÆ’Ã‚Â¢ÃƒÆ’Ã‚Â£ÃƒÆ’Ã‚Â¤ÃƒÆ’Ã‚Â¥ÃƒÆ’Ã‚Â§ÃƒÆ’Ã‚Â¨ÃƒÆ’Ã‚Â©ÃƒÆ’Ã‚ÂªÃƒÆ’Ã‚Â«ÃƒÆ’Ã‚Â¬ÃƒÆ’Ã‚Â­ÃƒÆ’Ã‚Â®ÃƒÆ’Ã‚Â¯ÃƒÆ’Ã‚Â°ÃƒÆ’Ã‚Â±ÃƒÆ’Ã‚Â²ÃƒÆ’Ã‚Â³ÃƒÆ’Ã‚Â´ÃƒÆ’Ã‚ÂµÃƒÆ’Ã‚Â¶ÃƒÆ’Ã‚Â¸ÃƒÆ’Ã‚Â¹ÃƒÆ’Ã‚ÂºÃƒÆ’Ã‚Â»ÃƒÆ’Ã‚Â¼ÃƒÆ’Ã‚Â½ÃƒÆ’Ã‚Â¿",									"SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaceeeeiiiionoooooouuuuyy");
		$palavranova = str_replace("_", " ", $palavra);
		return $palavranova; 
	} 

	$periodo = $_GET['periodo'];

	$parte = explode('-',$periodo);

	$uDiaMes = ultimoDiaDoMes($parte[0],$parte[1]);
	$datacomp = $periodo.'-01';
	//echo $datacomp;
	$cdcompt = getCompetencia($datacomp);
	$query_quest = ' AND tbcota.idgenfat ='.$cdcompt;
	//$TpCota = (string)$_GET['tpcota'];
	
	$pref = $_SESSION['CdOrigem'];

	/*echo (string)$_GET['tpcota'];
	echo $TpCota;*/
	$sql = "SELECT tbespecproc.CdEspecProc AS CdEspec, tbespecproc.NmEspecProc, tbcotam.qts, tbfornecedor.CdForn, 
			tbfornecedor.NmForn, tbcota.cdcota,nmprof
			FROM tbespecproc
			INNER JOIN tbcota ON tbespecproc.CdEspecProc = tbcota.CdEspecProc
			INNER JOIN tbcotam ON tbcota.cdcota = tbcotam.cdcota
			INNER JOIN tbprofissional on tbprofissional.cdprof = tbcota.cdprof
			LEFT JOIN tbfornecedor ON tbcota.CdForn = tbfornecedor.CdForn
			WHERE cdpref = '".$pref."'
			AND tbcotam.qts > 0
			$query_quest
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

			echo "<option $disabled1 value='$dados['cdcota']' data-id='$dados['qts']'> ".$dados['NmEspecProc']." ".$dados['NmForn']." - ".$dados['nmprof']."</option>";
		}  //fim do while
	} //fim do if
	else {
		echo "<option value=''> Nenhuma especificação encontrada  </option>";
		//echo "<option value=\"\"> Nenhum resultado encontrado!  </option>";
	}
	

	mysqli_close();
	mysqli_free_result($qry);
	
?>