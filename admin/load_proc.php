<?php 
	
	define("DIRECT_ACCESS",  true);
	require_once("verifica.php");
	require("../conecta.php");
	
	
	$sqlProc = "SELECT tbprocedimento.CdProcedimento, tbprocedimento.NmProcedimento
				FROM tbprocedimento";

	$qry = mysqli_query($db,$sqlProc) or die (mysqli_error());
	
	if (mysqli_num_rows($qry) > 0){
		echo "<option value=0>Todos</option>";
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
	}else {
		echo "<option value=''> Nenhum procedimento encontrado!</option>";
	}

	mysqli_close();
	mysqli_free_result($qry);
?>