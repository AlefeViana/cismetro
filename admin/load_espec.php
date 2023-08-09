<?php 
	
	define("DIRECT_ACCESS",  true);

	include("../verifica.php");
	require_once("../funcoes.php");

	require "../../vendor/autoload.php";
	use Stringy\Stringy as S;

    $CdProc = (isset($_REQUEST['CdProc']))?' AND tbespecproc.CdProcedimento = '.$_REQUEST['CdProc']:'';

    if ($_REQUEST['CdProc'] >0){

	  	$sqlEspec = "SELECT tbespecproc.NmEspecProc, tbespecproc.CdEspecProc
					 FROM tbfornespec
					 INNER JOIN tbespecproc ON tbfornespec.CdEspec = tbespecproc.CdEspecProc
					 WHERE tbespecproc.grupoceae = 0
					 $CdProc
					 GROUP BY tbfornespec.CdEspec
					 ORDER BY NmEspecProc ASC";

		//print_r($sqlEspec);
		mysqli_query($db,$sqlEspec);

		$erro = "";
	  	$sql = mysqli_query($db,$sqlEspec);

		if(mysqli_num_rows($sql) > 0){
			
			echo "<option value='0'> Todos </option>";
			while ($dados = mysqli_fetch_array($sql)){
				$nmespec   = (String)S::create($dados['NmEspecProc'])->titleize(["de", "da", "do"]);


				if (isset($_POST['CdEspecProc'])){
					if ($dados['CdEspecProc'] == $_POST['CdEspecProc']) {
						echo '<option value="'.$dados['CdEspecProc'].'" selected>'.$nmespec.'</option>';	
					}else{
						echo '<option value="'.$dados['CdEspecProc'].'">'.$nmespec.'</option>';	
					}
				}else{
					echo '<option value="'.$dados['CdEspecProc'].'">'.$nmespec.'</option>';
				}
			} 
			
		}else {
			echo "<option value=''> Nenhuma encontrada  </option>";
		}
	}else{
		echo "<option value='0'> Todos  </option>";

	}

	mysqli_close($GLOBALS['db']);
	mysqli_free_result($sql);
?>