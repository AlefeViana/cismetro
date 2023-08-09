<?php 
	
	define("DIRECT_ACCESS",  true);
	require_once("../verifica.php");
	require("../funcoes.php");

	require "../../vendor/autoload.php";
	use Stringy\Stringy as S;

	$cdforn = $_GET['cdforn'];
	
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
			AND esp.principal = 1
			
			AND (
				sub.`status` IS NULL
				OR sub.`status` = 0
			)";
	//echo $sql;
	$qry = mysqli_query($db,$sql) or die (mysqli_error());
	
	if (mysqli_num_rows($qry) > 0){
		echo "<option value=''>Selecione</option>";
		while ($dados = mysqli_fetch_array($qry)){
			if (descobreFilhos($dados["CdEspec"])) {
			}else{
				$NmEspecProc = (String)S::create($dados['NmEspecProc'])->titleize(["de", "da", "do"]);
				echo '<option value="'.$dados["CdEspec"].'">'.$dados["CdEspec"].' - '.$NmEspecProc.'</option>';
			}
		}  
	} 
	else {
		echo "<option value=''> Nenhuma encontrada  </option>";
	}
	

	mysqli_close($db);
	mysqli_free_result($qry);
?>

