<?php 
	$html = header('Content-Type: text/html; charset=iso-8859-1'); 
	//require_once("verifica.php");
	require("../funcoes.php");

	$cdp = $_GET['cdp'];
	
	$sql = "SELECT
				esp.principal,
				esp.NmEspecProc,
				esp.CdEspecProc AS CdEspec,
				esp.`Status`,
				sub.`status`,
				sub.CdEspecPai
			FROM
				tbespecproc AS esp
			LEFT JOIN tbespecprocsub AS sub ON esp.CdEspecProc = sub.CdEspecFilho
			WHERE
				esp.`Status` = 1
			AND esp.principal = 0
			
			AND (
				sub.`status` IS NULL
				OR sub.`status` = 0
			)
			ORDER BY NmEspecProc";
	
	$qry = mysqli_query($db,$sql) or die (mysqli_error());
	
	if (mysqli_num_rows($qry) > 0){
		//echo "<option value=''>Selecione</option>";
		if (isset($_GET['cdp'])) {
			$filhos = descobreFilhos($cdp);
			foreach ($filhos as $filho) {
				echo '<option value="'.$filho['cd'].'" selected >'.$filho['cd'].' - '.$filho['nome'].'</option>';
			}
		}

		while ($dados = mysqli_fetch_array($qry)){
			echo '<option value="'.$dados['CdEspec'].'">'.$dados['CdEspec'].' - '.$dados['NmEspecProc'].'</option>';
		}  
	} 
	else {
		echo "<option value=''> Nenhuma encontrado  </option>";
	}
	

	mysqli_close();
	mysqli_free_result($qry);
?>

