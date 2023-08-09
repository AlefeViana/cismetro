<?php 
	$html = header('Content-Type: text/html; charset=iso-8859-1'); 
	//require_once("verifica.php");
	require("../conecta.php");
	
	$sql = "SELECT
			tbfornecedor.CdForn,
			tbfornecedor.NmForn
			FROM tbfornecedor
			WHERE `Status`= 1
			ORDER BY NmForn";
	
	$qry = mysqli_query($GLOBALS['db'], $sql) or die (mysql_error());
	
	if (mysqli_num_rows($qry) > 0){
			echo "<option value=''>Selecione</option>";
		while ($dados = mysqli_fetch_array($qry)){
			if (isset($_GET['cdforn'])) {
				if ($dados['CdForn'] == $_GET['cdforn']) {
					echo '<option value="'.$dados['CdForn'].'" selected>'.$dados['CdForn'].' - '.$dados['NmForn'].'</option>';	
				}else{
					echo '<option value="'.$dados['CdForn'].'">'.$dados['CdForn'].' - '.$dados['NmForn'].'</option>';	
				}
			}else{
				echo '<option value="'.$dados['CdForn'].'">'.$dados['CdForn'].' - '.$dados['NmForn'].'</option>';
			}
		}  
	} 
	else {
		echo "<option value=''> Nenhum encontrado  </option>";
	}
	

	mysql_close();
	mysql_free_result($qry);
?>