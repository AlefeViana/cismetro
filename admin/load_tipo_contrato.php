<?php 
	
	define("DIRECT_ACCESS",  true);
	
	require_once("verifica.php");
	require("../conecta.php");
	
	$sql = "SELECT
			tbtipocontrato.sigla,
			tbtipocontrato.NomeTipo
			FROM
			tbtipocontrato
			ORDER BY sigla DESC";
	
	$qry = mysqli_query($GLOBALS['db'], $sql) or die (mysql_error());
	
	if (mysqli_num_rows($qry) > 0){
		echo "<option value=''>Selecione</option>";
		while ($dados = mysqli_fetch_array($qry)){
			if (isset($_GET['sigla'])) {
				if ($dados['sigla'] == $_GET['sigla']) {
					echo '<option value="'.$dados['sigla'].'" selected>'.$dados['NomeTipo'].'</option>';
				}else{
					echo '<option value="'.$dados['sigla'].'">'.$dados['NomeTipo'].'</option>';
				}
			}else{
				echo '<option value="'.$dados['sigla'].'">'.$dados['NomeTipo'].'</option>';
			}
		}
	} 
	else {
		echo "<option value=''> Nenhuma encontrado  </option>";
	}
	

	mysqli_close($GLOBALS['db']);
	mysqli_free_result($qry);
?>