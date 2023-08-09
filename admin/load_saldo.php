<?php
	define("DIRECT_ACCESS",  true);

	include("../verifica.php");
	require_once("../funcoes.php");
	require "../../vendor/autoload.php";
	use Stringy\Stringy as S;

	$CdPref = (isset($_REQUEST['CdPref']))?' AND tbsldgensaldo.CdPref = '.$_REQUEST['CdPref']:'';
    $CdProc = (isset($_REQUEST['CdProc']))?' AND tbsldgensaldo_proc.cdprocedimento = '.$_REQUEST['CdProc']:'';
    $CdTeto = (isset($_REQUEST['CdTeto']))?' AND tbsldteto.CdTeto = '.$_REQUEST['CdTeto']:'';

    if ($_REQUEST['CdPref'] >0){
		$sqlSaldo = "SELECT tbsldgensaldo.cdsaldo, tbsldgensaldo.Desc
					FROM tbsldgensaldo
					INNER JOIN tbsldgensaldo_proc on tbsldgensaldo.cdsaldo = tbsldgensaldo_proc.cdsaldo
					WHERE tbsldgensaldo.status = 1				 
					$CdPref";

		if($_REQUEST['CdProc'] >0){
    		$sqlSaldo .= $CdProc;
    	}

		/*if($_REQUEST['CdPref'] >0){
    		$sqlSaldo .= $CdPref;
    	}*/

    	$sqlSaldo.= " GROUP BY tbsldgensaldo.cdsaldo ";
		$sql = mysqli_query($db,$sqlSaldo);

		if(mysqli_num_rows($sql) > 0){
			echo '<option value="0"> Todos </option>';
			while ($dados = mysqli_fetch_array($sql)){
				
				$Desc   = (String)S::create($dados['Desc'])->titleize(["de", "da", "do"]);
				if (isset($_POST['cdsaldo'])) {
					if ($dados['cdsaldo'] == $_POST['cdsaldo']) {
						echo '<option value="'.$dados['cdsaldo'].'" selected>'.$Desc.'</option>';	
					}else{
						echo '<option value="'.$dados['cdsaldo'].'">'.$Desc.'</option>';	
					}
				}else{
					echo '<option value="'.$dados['cdsaldo'].'">'.$Desc.'</option>';
				}
			} 
		}else{
			echo "<option value=''> Nenhum encontrado  </option>";
		} 

	}else{
		echo '<option value="0"> Todos </option>';
	} 


?>