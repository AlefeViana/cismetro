<?php 
	$html = header('Content-Type: text/html; charset=iso-8859-1'); 
	require_once("verifica.php");
	require("../conecta.php");
	
	function retirar_acentos_caracteres_especiais($string) {
		$palavra = strtr($string,"ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåçèéêëìíîïðñòóôõöøùúûüýÿ",									"SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaceeeeiiiionoooooouuuuyy");
		
	$palavranova = str_replace("_", " ", $palavra);
	return $palavranova; 
}


	
	$cdforn = (int)$_GET['cdforn'];
	
	$sql = "SELECT
			tbespecproc.NmEspecProc,
			tbfornespec.CdForn,tbespecproc.CdEspecProc
			FROM
			tbfornespec
			Inner Join tbespecproc ON tbespecproc.CdEspecProc = tbfornespec.CdEspec
			WHERE tbfornespec.CdForn = $cdforn AND tbfornespec.Status = 1
			ORDER BY tbespecproc.NmEspecProc
			";
	//echo $sql;
	$qry = mysqli_query($db,$sql) or die (mysqli_error());
	
	if (mysqli_num_rows($qry) > 0){
		while ($dados = mysqli_fetch_array($qry)){
				echo '<option value="">Selecione um procedimento</option>';
				//echo '<option value="'.$dados["CdEspecProc"].'">'.retirar_acentos_caracteres_especiais($dados["NmEspecProc"]).'</option>';
				echo '<option value="'.$dados['CdEspecProc'].'">'.$dados['NmEspecProc'].'</option>';
		}
	}
	else
		echo "<option value=\"\"> Nenhum resultado encontrado!  </option>";

	mysqli_close();
	mysqli_free_result($qry);
?>