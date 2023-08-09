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
	
	$sql = "SELECT CdBairro, NmBairro, b.CdPref, NmCidade FROM tbbairro b INNER JOIN tbprefeitura p ON b.CdPref=p.CdPref";	
	$sql .= " WHERE b.CdPref=".$CdPref;		
	$sql .=  " ORDER BY NmBairro";
	
	$qry = mysqli_query($db,$sql) or die (mysqli_error());
	if (mysqli_num_rows($qry) > 0){
		while ($dados = mysqli_fetch_array($qry)){
				echo '<option value="'.$dados['CdBairro'].'">'.retirar_acentos_caracteres_especiais($dados['NmBairro']).'</option>';
		}
	}
	else
	{
		echo '<option value="">Selecione uma cidade primeiro</option>';
	}
	
	mysqli_free_result($qry);
?>