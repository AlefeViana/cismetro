<?php
	require_once("verifica.php");
	include("conecta.php");
	
	$dia 	= $_GET[var1];
	$mes 	= $_GET[var2];
	$op 	= $_GET[var3];
	$cdforn = $_GET[var4];
	$cdprof = $_GET[var5];
	$ano 	= $_GET[var6];
	
	$dia = str_pad($dia, 2, '0', STR_PAD_LEFT);
	$mes = str_pad($mes, 2, '0', STR_PAD_LEFT);
	
	$sql = mysqli_query($db,"SELECT cddias, data FROM tbdias_aten WHERE data = '$ano-$mes-$dia' AND cdforn = '$cdforn' AND cdprof = '$cdprof'") or die("Erro");
	$del = mysqli_fetch_array($sql);
	
	if(mysqli_num_rows($sql) < 1){
		mysqli_query($db,"INSERT INTO tbdias_aten(data,cdforn,op,cdprof) VALUES('$ano-$mes-$dia','$cdforn','$op','$cdprof')") or die("Erro");
		
		echo "Cadastro realizado com sucesso!";
	}else {
		mysqli_query($db,"DELETE FROM tbdias_aten WHERE tbdias_aten.cddias = '$del[cddias]'") or die("Erro");
		//echo "COD: ".$del[cddias];
		
		mysqli_query($db,"DELETE FROM `tbagenda_fornecedor` WHERE data = '$ano-$mes-$dia' AND cdfornecedor = '$cdforn' AND status = 'A'") or die("Erro");
		
	    echo "resetar"; 	
	}


?>