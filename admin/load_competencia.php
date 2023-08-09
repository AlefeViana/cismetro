<?php 
	
	define("DIRECT_ACCESS",  true);

	require("../funcoes.php");

	$sql = "SELECT * FROM tbgenfat
			ORDER BY dtini DESC";
	
	$qry = mysqli_query($db,$sql) or die (mysqli_error());
	if (mysqli_num_rows($qry) > 0){
		echo "<option value=''>Selecione</option>";
		while ($dados = mysqli_fetch_array($qry)){
			echo '<option value="'.$dados['idgenfat'].'">Cód.: '.$dados['idgenfat'].' Data: '.FormataDataBR($dados['dtini']).' -> '.FormataDataBR($dados['dtfim']).'</option>';
		}
	}

	mysqli_close();
	mysqli_free_result($qry);
?>