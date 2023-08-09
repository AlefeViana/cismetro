<?php 
	
	define("DIRECT_ACCESS",  true);

	require_once("verifica.php");
	require("../conecta.php");

	require "../../vendor/autoload.php";
	use Stringy\Stringy as S;
	
	
	$CdProc = (int)$_GET['cdproc'];
	
	$sql = "SELECT CdEspecProc, NmEspecProc, cdsus FROM tbespecproc";	
	$sql .= " WHERE Status='1' AND CdProcedimento=".$CdProc;		
	$sql .=  " ORDER BY NmEspecProc";
	
	$qry = mysqli_query($db,$sql) or die (mysqli_error());
	
	if($CdProc == 0) {
		echo "<option value='0'> Todos  </option>";
	}else 
		if (mysqli_num_rows($qry) > 0){
			echo "<option value='0'> Todos  </option>";
			while ($dados = mysqli_fetch_array($qry)){
				$NmEspecProc = (String)S::create($dados['NmEspecProc'])->titleize(["de", "da", "do"]);
				//echo '<option value="'.$dados["CdEspecProc"].'">'.retirar_acentos_caracteres_especiais($dados["NmEspecProc"]).'</option>';
				echo '<option value="'.$dados['CdEspecProc'].'">'.$NmEspecProc.' | '.$dados['cdsus'].'</option>';	
			}  //fim do while
		} //fim do if
		else {
			echo "<option value=''> Nenhuma especifica&ccedil;&atilde;o encontrada  </option>";
		//echo "<option value=\"\"> Nenhum resultado encontrado!  </option>";
		}
	

	mysqli_close();
	mysqli_free_result($qry);
?>