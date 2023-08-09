<?php
	define("DIRECT_ACCESS",  true);

	include("../verifica.php");
	require_once("../funcoes.php");
	require "../../vendor/autoload.php";
	use Stringy\Stringy as S;

	$DtIni = $_REQUEST['dtinicio'];
	$DtFim = $_REQUEST['dttermino'];
	$CdSaldo = $_REQUEST['CdSaldo'];

	echo $CdSaldo;
	if(($_REQUEST['CdSaldo']>0) AND ($_REQUEST['dtinicio']>0) AND ($_REQUEST['dttermino']>0)){

		echo $sqlTeto = "SELECT tbsldteto.cdteto, tbgenfat.DtIni, tbgenfat.DtFim
					FROM tbsldteto 
					INNER JOIN tbgenfat on tbsldteto.idgenfat = tbgenfat.idgenfat
					WHERE dtini >= '$DtIni' AND dtfim <= '$DtFim'
					AND tbsldteto.CdSaldo = $CdSaldo
					ORDER BY dtini";

		$sqlTeto = mysqli_query($db, $sqlTeto);

		if(mysqli_num_rows($sqlTeto) > 0){

			echo '<option value="0"> Todos </option>';
			
			while ($dados = mysqli_fetch_array($sqlTeto)){
				$Desc = FormataDataBR($dados['DtIni']).' - '.FormataDataBR($dados['DtFim']);
				if (isset($_POST['cdteto'])) {
					if ($dados['cdteto'] == $_POST['cdteto']) {
						echo '<option value="'.$dados['cdteto'].'" selected>'.$Desc.'</option>';	
					}else{
						echo '<option value="'.$dados['cdteto'].'">'.$Desc.'</option>';	
					}
				}else{
					echo '<option value="'.$dados['cdteto'].'">'.$Desc.'</option>';
				}
			} 
		}else{
			echo "<option value=''> Nenhum encontrado  </option>";
		} 
	}else{
		echo '<option value="0"> Todos </option>';
	}


?>