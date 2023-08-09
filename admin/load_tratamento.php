<?php 
	
	define("DIRECT_ACCESS",  true);

	require_once("verifica.php");
	require("../conecta.php");

	$sql = "SELECT CdTratamento, NmTratamento FROM tbtratamento WHERE Status='1'";	
	$sql .=  " ORDER BY NmTratamento";
	
	$qry = mysqli_query($db,$sql) or die (mysqli_error());
	if (mysqli_num_rows($qry) > 0){
		while ($dados = mysqli_fetch_array($qry)){
				echo '<option value="'.$dados['CdTratamento'].'">'.$dados['NmTratamento'].'</option>';
		}
	}

?>