<?php 
	
	define("DIRECT_ACCESS",  true);
	
	require_once("verifica.php");
	require("../conecta.php");

	require "../../vendor/autoload.php";
	use Stringy\Stringy as S;
	$tp = "";
	if($_GET['tp'] !== 0){
		if($_GET['tp'] == 'I' ){
		$tp = " AND tbfornecedor.sit = 'I' ";
	}else if($_GET['tp'] == 'E'){
		$tp = " AND tbfornecedor.sit = 'E'";
	}else if($_GET['tp'] == 'A'){
		$tp = " AND tbfornecedor.sit = 'A'";
	}
}else{
		$tp = "";
	}

 echo	$sql = "SELECT
			tbfornecedor.CdForn,
			tbfornecedor.NmForn
			FROM tbfornecedor
			WHERE `Status`= 1
			$tp
			ORDER BY NmForn";
	
	$qry = mysqli_query($GLOBALS['db'], $sql) or die (mysql_error());
	
	if (mysqli_num_rows($qry) > 0){
			echo "<option value=''>Selecione</option>";
			if($_GET['tp'] >= 0){
				echo "<option value ='0'> Todos </option>";
			}
		while ($dados = mysqli_fetch_array($qry)){
			$NmForn   = (String)S::create($dados['NmForn'])->titleize(["de", "da", "do"]);

			if (isset($_GET['cdforn'])) {
				if ($dados['CdForn'] == $_GET['cdforn']) {
					echo '<option value="'.$dados['CdForn'].'" selected>'.$dados['CdForn'].' - '.$NmForn.'</option>';	
				}else{
					echo '<option value="'.$dados['CdForn'].'">'.$dados['CdForn'].' - '.$NmForn.'</option>';	
				}
			}else{
				echo '<option value="'.$dados['CdForn'].'">'.$dados['CdForn'].' - '.$NmForn.'</option>';
			}
		}  
	} 
	else {
		echo "<option value=''> Nenhum encontrado  </option>";
	}
	

	mysql_close();
	mysql_free_result($qry);
?>