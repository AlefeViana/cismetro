<?php 
	$html = header('Content-Type: text/html; charset=iso-8859-1'); 
	//require_once("verifica.php");
	require("../conecta.php");

	$sql = "SELECT tbgrupoproc.cdgrupoproc,
					tbgrupoproc.nmgrupoproc,
					tbtemplateevolucaoclinica.grupo
			FROM tbtemplateevolucaoclinica
			LEFT JOIN tbgrupoproc ON tbtemplateevolucaoclinica.grupo = tbgrupoproc.cdgrupoproc 
			WHERE tbtemplateevolucaoclinica.`status` != 'DELETADO' 
			GROUP BY tbtemplateevolucaoclinica.grupo";
	//echo $sql;

	$qry = mysqli_query($db,$sql) or die (mysqli_error());
	
	if (mysqli_num_rows($qry) > 0){
		echo "<option value=''>Selecione</option>";
		while ($dados = mysqli_fetch_array($qry)){
			$dados['nmgrupoproc'] = ($dados['nmgrupoproc']=='')? Outros : $dados['nmgrupoproc'];
			if (isset($_GET['grupo'])) {
				if ($dados['grupo'] == $_GET['grupo']) {
					echo '<option value="'.$dados['grupo'].'" selected>'.$dados['nmgrupoproc'].'</option>';
				}else{
					echo '<option value="'.$dados['grupo'].'">'.$dados['nmgrupoproc'].'</option>';
				}
			}else{
				echo '<option value="'.$dados['grupo'].'">'.$dados['nmgrupoproc'].'</option>';
			}
		}
	} 
	else {
		echo "<option value=''> Nenhum grupo encontrado!</option>";
	}

	mysqli_close();
	mysqli_free_result($qry);
?>