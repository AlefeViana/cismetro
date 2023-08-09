<?php 
	
	define("DIRECT_ACCESS",  true);

	require_once("verifica.php");
	require("../conecta.php");
	
	if (isset($_GET['status'])) {
		$status = $_GET['status'];
		$querystatus = " WHERE tbprocedimento.`Status` = $status ";
	}else{
		$querystatus = "";
	}

	$sql = "SELECT
			tbprocedimento.CdProcedimento,
			tbprocedimento.NmProcedimento,
			tbprocedimento.`Status`
			FROM
			tbprocedimento
			$querystatus
			";
	//echo $sql;

	$qry = mysqli_query($db,$sql) or die (mysqli_error());
	
	if (mysqli_num_rows($qry) > 0){
		echo "<option value=''>Selecione</option>";
		while ($dados = mysqli_fetch_array($qry)){
			if (isset($_GET['cdprocedimento'])) {
				if ($dados['CdProcedimento'] == $_GET['cdprocedimento']) {
					echo '<option value="'.$dados['CdProcedimento'].'" selected>'.$dados['NmProcedimento'].'</option>';
				}else{
					echo '<option value="'.$dados['CdProcedimento'].'">'.$dados['NmProcedimento'].'</option>';
				}
			}else{
				echo '<option value="'.$dados['CdProcedimento'].'">'.$dados['NmProcedimento'].'</option>';
			}
		}
	} 
	else {
		echo "<option value=''> Nenhum procedimento encontrado!</option>";
	}

	mysqli_close();
	mysqli_free_result($qry);
?>