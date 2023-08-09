<?php 
	//$html = header('Content-Type: text/html; charset=iso-8859-1'); 
	//require_once("verifica.php");
	require("../conecta.php");

	$cdforn = $_GET['cdforn'];


	if (isset($_GET['cdespec'])) {
		$cdespec = $_GET['cdespec'];
		//print_r($cdespec);
 		$fcdespec = " AND ce.CdEspecProc in ({$cdespec}) ";	
 		$incontespec = " INNER JOIN tbcontratoespec ce on ce.CdContrato = cont.CdContrato ";
	}else{
		$fcdespec = '';
		$incontespec = '';
	}
	
	$sql = "SELECT cont.CdContrato,
			cont.Descricao
			FROM
			tbcontrato AS cont
			$incontespec
			WHERE cont.TpContrato = 'p' 
			AND cont.CdForn = {$cdforn} 
			$fcdespec
			order by cont.CdContrato desc
			";
	//echo $sql;
	$qry = mysqli_query($db,$sql);
	
	if (mysqli_num_rows($qry) > 0){
		echo "<option value=''>Selecione</option>";
		while ($dados = mysqli_fetch_array($qry)){
			if (isset($_GET['cdcontrato'])) {
				if ($dados["CdContrato"] == $_GET['cdcontrato']) {
					echo '<option value="'.$dados["CdContrato"].'" selected>'.$dados["CdContrato"].' - '.$dados["Descricao"].'</option>';
				}else{
					echo '<option value="'.$dados["CdContrato"].'">'.$dados["CdContrato"].' - '.$dados["Descricao"].'</option>';
				}
			}else{
				echo '<option value="'.$dados["CdContrato"].'">'.$dados["CdContrato"].' - '.$dados["Descricao"].'</option>';
			}
		}
	} 
	else {
		echo "<option value=''> Nenhum contrato encontrado!</option>";
	}
	

	mysqli_close();
	mysqli_free_result($qry);
?>