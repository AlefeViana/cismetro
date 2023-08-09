<?php
	require_once("verifica.php");
	include("conecta.php");
	
	$qtd_dia = $_POST[qtd_dia];
	$mes = $_POST[mes];
	$cd = explode("-",$_POST[cd]);
	
	$sql = mysqli_query($db,"UPDATE tbmes SET capdia = '$qtd_dia' WHERE cdmes = '$mes'") or die("Erro");	
	
	echo '<script language="JavaScript" type="text/javascript">
						window.location.href="index.php?i=5&s=pgforn&cdforn='.$cd[0].'&cdprof='.$cd[1].'&f=d&mes='.$cd[2].'&ano='.$cd[3].'";
				</script>';	

?>