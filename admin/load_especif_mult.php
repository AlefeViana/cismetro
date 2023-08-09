<?php 
    
    define("DIRECT_ACCESS",  true);
 
	//include "verifica.php";
	require("../conecta.php");
	
	require "../../vendor/autoload.php";
	use Stringy\Stringy as S;
    
	$CdProc = (int)$_GET['cdproc'];
	$tpenc = substr($_GET['num'], 0,3);

	if ($tpenc == 'tfd') {
		$sql = "SELECT tbespecproctfd.CdEspecProc,tbespecproctfd.NmEspecProc,tbespecproctfd.cdsus,tbespecproctfd.valor
			FROM tbespecproctfd
			LEFT JOIN tbfornespec ON tbespecproctfd.CdEspecProc = tbfornespec.CdEspec
			WHERE tbespecproctfd.`Status` = '1' AND CdProcedimento=".$CdProc."
			GROUP BY	NmEspecProc";	
		
	}else{
		$sql = "SELECT tbespecproc.CdEspecProc,tbespecproc.NmEspecProc,tbespecproc.cdsus,tbespecproc.valor
			FROM tbespecproc
			LEFT JOIN tbfornespec ON tbespecproc.CdEspecProc = tbfornespec.CdEspec";	
		$sql .= " WHERE tbespecproc.`Status` = '1'  AND CdProcedimento=".$CdProc;		
		$sql .= " GROUP BY	NmEspecProc";
	}
	
	
	//echo $sql;
	
	$qry = mysqli_query($db,$sql) or die (mysqli_error());
	
	if($CdProc == 0) {
		echo "<option value='0'> Selecione uma especificação  </option>";
	}else 
		if (mysqli_num_rows($qry) > 0){
			echo "<option value='0'> Selecione uma especificação  </option>";
			while ($dados = mysqli_fetch_array($qry)){
				$NmEspecProc = (String)S::create($dados['NmEspecProc'])->titleize(["de", "da", "do"]);
				//echo '<option value="'.$dados["CdEspecProc"].'">'.retirar_acentos_caracteres_especiais($dados["NmEspecProc"]).'</option>';
			echo '<option value="'.$dados['CdEspecProc'].'">'.$NmEspecProc.'</option>';	
			}  //fim do while
		} //fim do if
		else {
			$msg = "Nenhuma especificação encontrada";
			echo "<option value=''>".$msg."</option>";
		//echo "<option value=\"\"> Nenhum resultado encontrado!  </option>";
		}
	

	mysqli_close($db);
	mysqli_free_result($qry);
?>