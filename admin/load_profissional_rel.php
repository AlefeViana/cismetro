<?php 

	define("DIRECT_ACCESS",  true);

	require_once("verifica.php");
	require("../conecta.php");
	require "../../vendor/autoload.php";
	use Stringy\Stringy as S;

    $CdEspec = (isset($_REQUEST['CdEspec']))?' AND tbfornespec.CdEspec = '.$_REQUEST['CdEspec']:'';

	if ($_REQUEST['CdForn'] >0){

		$CdForn = $_REQUEST['CdForn'];

		$sqlProf = "SELECT
					tbfornespec.CdForn,
					tbfornespec.cdprof,
					tbfornespec.CdEspec,
					UPPER(tbprofissional.nmprof) AS nmprof,
					tbprofissional.cnsprof,
					tbprofissional.crm,
					tbprofissional.`status`
					FROM
					tbfornespec
					INNER JOIN tbprofissional ON tbfornespec.cdprof = tbprofissional.cdprof
					WHERE tbprofissional.status = 'a'
					AND tbfornespec.CdForn = $CdForn
					$CdEspec
					GROUP BY cdprof
					ORDER BY nmprof ASC";

		//echo $sqlProf; die();

	}else{
		$sqlProf = "SELECT
					tbprofissional.cdprof,
					UPPER(tbprofissional.nmprof) AS nmprof,
					tbprofissional.cnsprof,
					tbprofissional.crm,
					tbprofissional.`status`
					FROM
					tbprofissional
					WHERE tbprofissional.status = 'a'
					ORDER BY nmprof ASC";

	//echo $sqlProf; die();
	}

  	$sql = mysqli_query($db,$sqlProf);

	if(mysqli_num_rows($sql) > 0){

		if ($_REQUEST['CdForn']==0){
			echo '<option value="0"> Todos </option>';
			
		}else{
			echo '<option value="0"> Todos </option>';
			while ($dados = mysqli_fetch_array($sql)){

				$nmprof   = (String)S::create($dados['nmprof'])->titleize(["de", "da", "do"]);
				if (isset($_POST['cdprof'])) {
					if ($dados['cdprof'] == $_POST['cdprof']) {
						echo '<option value="'.$dados['cdprof'].'" selected>'.$nmprof.'</option>';	
					}else{
						echo '<option value="'.$dados['cdprof'].'">'.$nmprof.'</option>';	
					}
				}else{
					echo '<option value="'.$dados['cdprof'].'">'.$nmprof.'</option>';
				}
			} 
		} 
	} 
	else {
		echo "<option value=''> Nenhum encontrado  </option>";
	}

	mysqli_close($GLOBALS['db']);
	mysqli_free_result($sql);
?>