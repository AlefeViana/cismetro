<?php 
	
	define("DIRECT_ACCESS",  true);
	
	require_once("verifica.php");
	require("../funcoes.php");
	
	$cdfornecedor_1000 = (int)$_GET['cdfornecedor'];


	$sql = "SELECT
			tbfornespec.CdEspec,
			tbespecproc.NmEspecProc,
			tbespecprocsub.CdEspecPai,
			tbfornespec.cdprof,
			tbprofissional.nmprof
			FROM
			tbfornespec
			INNER JOIN tbespecproc ON tbespecproc.CdEspecProc = tbfornespec.CdEspec
			INNER JOIN tbprofissional ON tbprofissional.cdprof = tbfornespec.cdprof
			LEFT JOIN tbespecprocsub ON tbfornespec.CdEspec = tbespecprocsub.CdEspecFilho
			WHERE tbfornespec.CdForn = $cdfornecedor_1000 
			AND tbfornespec.Status = 1
			AND tbespecproc.grupoceae = 0 
			AND tbespecproc.CdProcedimento = 40 
			AND tbespecprocsub.CdEspecFilho is NULL
			ORDER BY NmEspecProc";
	
	$qry = mysqli_query($db,$sql) or die (mysqli_error());
	if (mysqli_num_rows($qry) > 0){
		while ($dados = mysqli_fetch_array($qry)){
				echo '<option value="'.$dados['CdEspec'].':'.$dados['cdprof'].'">'.$dados['NmEspecProc'].' - '.$dados['nmprof'].'</option>';
		}
	}

	mysqli_close($db);
	mysqli_free_result($qry);
?>


