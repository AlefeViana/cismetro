<?php 
	
	define("DIRECT_ACCESS",  true);

	require_once("verifica.php");
	require("../conecta.php");

	$sql = "SELECT CdMedicacao, NmMedicacao FROM tbmedicacao";	
	$sql .=  " ORDER BY NmMedicacao";
	
	$qry = mysqli_query($db,$sql) or die (mysqli_error());
	if (mysqli_num_rows($qry) > 0){
		while ($dados = mysqli_fetch_array($qry)){
				echo '<option value="'.$dados['CdMedicacao'].'">'.$dados['NmMedicacao'].'</option>';
		}
	}

	//mysqli_close();
	//mysqli_free_result($qry);
?>