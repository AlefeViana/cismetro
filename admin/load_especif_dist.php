<?php 
	
	define("DIRECT_ACCESS",  true);

	require_once("verifica.php");
	require("../funcoes.php");
	

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

			echo "<option $disabled1 value='$dados['cdcota']' data-id='$dados['qts']'> ".utf8_decode($dados['NmEspecProc'])." ".utf8_decode($dados['NmForn'])." - ".utf8_decode($dados['nmprof'])."</option>";
		}  //fim do while
	} //fim do if
	else {
		echo "<option value=''> Nenhuma especificação encontrada  </option>";
		//echo "<option value=\"\"> Nenhum resultado encontrado!  </option>";
	}
	

	mysqli_close();
	mysqli_free_result($qry);
	
?>