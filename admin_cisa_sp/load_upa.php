<?php 
	require_once("verifica.php");
	require("../conecta.php");
	
	function retirar_acentos_caracteres_especiais($string) {
		$palavra = strtr($string,"ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåçèéêëìíîïðñòóôõöøùúûüýÿ",									"SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaceeeeiiiionoooooouuuuyy");
		
	$palavranova = str_replace("_", " ", $palavra);
	return $palavranova; 
}


	
	$CdPref = (int)$_GET['cdpref'];
	
	if ((int)$_SESSION['CdOrigem']>0)
		$CdPref = (int)$_SESSION['CdOrigem'];
	
	$sql = "SELECT cdupa, nmupa FROM tbupa";	
	$sql .= " WHERE cdpref=".$CdPref;		
	$sql .=  " ORDER BY nmupa";
	
	$qry = mysqli_query($db,$sql) or die (mysqli_error());
	if (mysqli_num_rows($qry) > 0){
		while ($dados = mysqli_fetch_array($qry)){
				echo '<option value="'.$dados['cdupa'].'">'.retirar_acentos_caracteres_especiais($dados['nmupa']).'</option>';
		}
	}
	else
	{
		echo '<option value="">Nenhuma Unidade de Saúde cadastrada para esta cidade.</option>';
	}
	mysqli_close();
	mysqli_free_result($qry);
?>