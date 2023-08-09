<?php 
	session_start();
	$html = header('Content-Type: text/html; charset=iso-8859-1'); 
	require_once("verifica.php");
	require("../conecta.php");
	
	function retirar_acentos_caracteres_especiais($string) {
		$palavra = strtr($string,"ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåçèéêëìíîïðñòóôõöøùúûüýÿ",									"SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaceeeeiiiionoooooouuuuyy");
		
	$palavranova = str_replace("_", " ", $palavra);
	return $palavranova; 
}


	$CdProc = (int)$_GET['cdproc'];

	if (isset($_GET['cdespc'])) {
		$cdEspec = 'AND cdgrupoproc ='.$_GET['cdespc'];
		$CdProc = 8;
	}else{
		$cdEspec = '';
	}

	if (isset($_GET['ref'])) {
		$referencia = "AND tbespecproc.grupoceae = 1";
	}else{
		$referencia ="AND tbespecproc.grupoceae = 0";
	}

	if ($_SESSION['CdTpUsuario']!=1) {
		$grupo = ($_SESSION['CdTpUsuario']==3)?'M':(($_SESSION['CdTpUsuario']==1)?'C':'');
		$qgrupo = "AND (tbespecproc.quemAgendar = 'T' OR tbespecproc.quemAgendar = '$grupo')";
	}else{
		$qgrupo ='';
	}

	$sql = "SELECT
			tbespecproc.CdEspecProc,
			tbespecproc.NmEspecProc,
			tbespecproc.cdsus,
			tbespecprocsub.CdEspecFilho
			FROM
			tbespecproc
			INNER JOIN tbfornespec ON tbespecproc.CdEspecProc = tbfornespec.CdEspec
			left JOIN tbespecprocsub ON tbespecproc.CdEspecProc = tbespecprocsub.CdEspecPai
			WHERE tbespecproc.Status = 1 
			$referencia
			$qgrupo
			AND tbfornespec.Status = 1 AND CdProcedimento = $CdProc AND CdEspecFilho is NULL
			$cdEspec
			GROUP BY tbespecproc.CdEspecProc
			ORDER BY tbespecproc.NmEspecProc";
	//echo $sql;
	$qry = mysqli_query($db,$sql) or die (mysqli_error());
	
	if($CdProc == 0) {
		echo "<option value='0'> Todos  </option>";
	}else 
		if (mysqli_num_rows($qry) > 0){
			echo "<option value=''> Selecione </option>";
			if ($cdEspec) {
				echo"<option value='0'> Todos </option>";
			}
			while ($dados = mysqli_fetch_array($qry)){
				//echo '<option value="'.$dados["CdEspecProc"].'">'.retirar_acentos_caracteres_especiais($dados["NmEspecProc"]).'</option>';
			echo '<option value="'.$dados['CdEspecProc'].'">'.$dados['NmEspecProc'].' | '.$dados['cdsus'].'</option>';	
			}  //fim do while
		} //fim do if
		else {
			echo "<option value=''> Nenhuma especifica&ccedil;&atilde;o encontrada  </option>";
		//echo "<option value=\"\"> Nenhum resultado encontrado!  </option>";
		}
	

	mysqli_close($db);
	mysqli_free_result($qry);
?>