<?php 
	
	define("DIRECT_ACCESS",  true);

	require_once("verifica.php");
	require("../conecta.php");
	
	
	$cdfornecedor = (int)$_GET['cdfornecedor'];
	
	// SELECTIONA OS PROCEDIMENTOS DE ACORDO  
	/*$sql = "SELECT CdEspecProc, NmEspecProc FROM tbespecproc";	
	$sql .= " WHERE Status='1' AND CdProcedimento=".$CdProc;		
	$sql .=  " ORDER BY NmEspecProc";*/
	
	$sql = "SELECT
	tbprocedimento.CdProcedimento,
	tbprocedimento.NmProcedimento,
	tbfornespec.CdEspec,
	tbespecproc.NmEspecProc,
	tbfornecedor.CdForn,
	tbfornecedor.NmForn
	FROM
	tbfornespec
	INNER JOIN tbfornecedor ON tbfornecedor.CdForn = tbfornespec.CdForn
	INNER JOIN tbespecproc ON tbespecproc.CdEspecProc = tbfornespec.CdEspec
	INNER JOIN tbprocedimento ON tbprocedimento.CdProcedimento = tbespecproc.CdProcedimento
	WHERE tbfornespec.CdForn = $cdfornecedor";
	
	$qry = mysqli_query($db,$sql) or die (mysqli_error());
	if (mysqli_num_rows($qry) > 0){
		while ($dados = mysqli_fetch_array($qry)){
				//echo '<option value="'.$dados["CdEspecProc"].'">'.retirar_acentos_caracteres_especiais($dados["NmEspecProc"]).'</option>';
				echo '<option value="'.$dados['CdEspec'].'">'.$dados['NmProcedimento'].' '.$dados['NmEspecProc'].'</option>';
		}
	}

	mysqli_close();
	mysqli_free_result($qry);
?>












