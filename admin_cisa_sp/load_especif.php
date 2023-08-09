<?php 
	$html = header('Content-Type: text/html; charset=iso-8859-1'); 
	require_once("verifica.php");
	require("../conecta.php");
	
	function retirar_acentos_caracteres_especiais($string) {
		$palavra = strtr($string,"ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåçèéêëìíîïðñòóôõöøùúûüýÿ",									"SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaceeeeiiiionoooooouuuuyy");
		
	$palavranova = str_replace("_", " ", $palavra);
	return $palavranova; 
}


	$CdProc = (int)$_GET['cdproc'];
	
	$sql = "SELECT CdEspecProc, NmEspecProc, cdsus FROM tbespecproc";	
	$sql .= " WHERE Status='1' AND CdProcedimento=".$CdProc;		
	$sql .=  " ORDER BY NmEspecProc";
	
	$qry = mysqli_query($db,$sql) or die (mysqli_error());
	
	if($CdProc == 0) {
		echo "<option value='0'> Todos  </option>";
	}else 
		if (mysqli_num_rows($qry) > 0){
			echo "<option value='0'> Todos  </option>";
			while ($dados = mysqli_fetch_array($qry)){
				//echo '<option value="'.$dados["CdEspecProc"].'">'.retirar_acentos_caracteres_especiais($dados["NmEspecProc"]).'</option>';
			echo '<option value="'.$dados['CdEspecProc'].'">'.$dados['NmEspecProc'].' | '.$dados['cdsus'].'</option>';	
			}  //fim do while
		} //fim do if
		else {
			echo "<option value=''> Nenhuma especifica&ccedil;&atilde;o encontrada  </option>";
		//echo "<option value=\"\"> Nenhum resultado encontrado!  </option>";
		}
	

	mysqli_close();
	mysqli_free_result($qry);
?>