<?php 
	$html = header('Content-Type: text/html; charset=iso-8859-1'); 
	require_once("verifica.php");
	require("../conecta.php");
	
	function retirar_acentos_caracteres_especiais($string) {
		$palavra = strtr($string,"ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåçèéêëìíîïðñòóôõöøùúûüýÿ",									"SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaceeeeiiiionoooooouuuuyy");
		
	$palavranova = str_replace("_", " ", $palavra);
	return $palavranova; 
}

	$i = (int)$_GET['i'];

	if ($i == 95)
	{
		$sit = " AND (tbespecproc.sit = 'I' OR tbespecproc.sit = 'A') ";
	}else if ($i == 97)
	{
		$sit = " AND (tbespecproc.sit = 'E' OR tbespecproc.sit = 'A') ";
	}else
	{
		$sit = " ";
	}

	$CdProc = (int)$_GET['cdproc'];
	
	$sql = "SELECT tbespecproc.CdEspecProc,tbespecproc.NmEspecProc,tbespecproc.cdsus,tbfornespec.valorf,tbespecproc.valor
			FROM tbespecproc
			LEFT JOIN tbfornespec ON tbespecproc.CdEspecProc = tbfornespec.CdEspec
			WHERE tbespecproc.`Status` = 1
			AND tbespecproc.grupoceae = 0
			$sit
			AND CdProcedimento = $CdProc 
			GROUP BY NmEspecProc
			";
	echo $sql;
	
	$qry = mysqli_query($db,$sql) or die (mysqli_error());
	
	if($CdProc == 0) {
		echo "<option value='0'> Todos  </option>";
	}else 
		if (mysqli_num_rows($qry) > 0){
			echo "<option value='0'> Todos  </option>";
			while ($dados = mysqli_fetch_array($qry)){
				//echo '<option value="'.$dados["CdEspecProc"].'">'.retirar_acentos_caracteres_especiais($dados["NmEspecProc"]).'</option>';
			echo '<option value="'.$dados['CdEspecProc'].'">'.$dados['NmEspecProc'].' | R$'.$dados['valor'].'</option>';	
			}  //fim do while
		} //fim do if
		else {
			echo "<option value=''> Nenhuma especifica&ccedil;&atilde;o encontrada  </option>";
		//echo "<option value=\"\"> Nenhum resultado encontrado!  </option>";
		}
	

	mysqli_close();
	mysqli_free_result($qry);
?>