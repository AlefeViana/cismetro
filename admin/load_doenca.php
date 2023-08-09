<?php 
	
	define("DIRECT_ACCESS",  true);

	require_once("verifica.php");
	require("../conecta.php");

	$sql = "SELECT CdDoenca, NmDoenca FROM tbdoenca WHERE TpDoenca=1 AND Status='1'";	
	$sql .=  " ORDER BY NmDoenca";
	
	$qry = mysqli_query($db,$sql) or die (mysqli_error());
	if (mysqli_num_rows($qry) > 0){
		while ($dados = mysqli_fetch_array($qry)){
				echo '<option value="'.$dados['CdDoenca'].'">'.$dados['NmDoenca'].'</option>';
		}
	}

?>