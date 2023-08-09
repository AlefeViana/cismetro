<?php 
	require_once("verifica.php");
	require("../conecta.php");
	
	function retirar_acentos_caracteres_especiais($string) {
		$palavra = strtr($string,"ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåçèéêëìíîïðñòóôõöøùúûüýÿ",									"SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaceeeeiiiionoooooouuuuyy");
		
	$palavranova = str_replace("_", " ", $palavra);
	return $palavranova; 
}


	
	$cdespec = (int)$_GET['cdespec'];
	
	$sql = "SELECT tbprofissional.nmprof,tbprofissional.cdprof FROM tbfornespec INNER JOIN tbprofissional ON tbprofissional.cdprof = tbfornespec.cdprof";	
	$sql .= " WHERE tbfornespec.CdEspec =".$cdespec." AND tbfornespec.`Status` = 1";		
	$sql .=  " ORDER BY tbprofissional.nmprof";
	
	$qry = mysqli_query($db,$sql) or die (mysqli_error());
	if (mysqli_num_rows($qry) > 0){
			echo "<option value=''>&Agrave; crit&eacute;rio do cons&oacute;rcio</option>";
		while ($dados = mysqli_fetch_array($qry)){
				echo '<option value="'.$dados['cdprof'].'">'.retirar_acentos_caracteres_especiais($dados['nmprof']).'</option>';
		}
	}
	else
	{
		echo "<option value=''>&Agrave; crit&eacute;rio do cons&oacute;rcio</option>";
	}
	mysqli_close();
	mysqli_free_result($qry);
?>