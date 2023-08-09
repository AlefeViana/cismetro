<?php
	define("DIRECT_ACCESS",  true);

	include("../verifica.php");
	require_once("../funcoes.php");
	require "../../vendor/autoload.php";
	use Stringy\Stringy as S;

    $CdForn = (isset($_REQUEST['CdForn']))?' AND tbcontrato.CdForn = '.$_REQUEST['CdForn']:'';
    $CdEspec = (isset($_REQUEST['CdEspec']))?' AND tbcontratoespec.CdEspecProc = '.$_REQUEST['CdEspec']:'';

    if (isset($_REQUEST['CdForn'])){   

    	$CdEspecProc = $_REQUEST['CdEspec'];

		$sqlContrato = "SELECT tbcontrato.CdContrato, tbcontrato.Descricao, tbcontratoespec.CdEspecProc
						FROM tbcontrato
						INNER JOIN tbcontratoespec on tbcontrato.CdContrato = tbcontratoespec.CdContrato
						WHERE tbcontrato.`Status` = 1 and tbcontratoespec.`Status` = 1
						$CdForn";

    	if($CdEspecProc>0) {
    		$sqlContrato .= $CdEspec;
    	}

    	//echo $sqlContrato;
 	}
	 
	if ($_REQUEST['CdForn']==0){
		echo '<option value="0"> Todos </option>';

	}else{

		$sql = mysqli_query($db,$sqlContrato);
		if(mysqli_num_rows($sql) > 0){
			echo '<option value="0"> Todos </option>';
			while ($dados = mysqli_fetch_array($sql)){

				$Descricao   = (String)S::create($dados['Descricao'])->titleize(["de", "da", "do"]);
				if (isset($_POST['CdContrato'])) {
					if ($dados['CdContrato'] == $_POST['CdContrato']) {
						echo '<option value="'.$dados['CdContrato'].'" selected>'.$Descricao.'</option>';	
					}else{
						echo '<option value="'.$dados['CdContrato'].'">'.$Descricao.'</option>';	
					}
				}else{
					echo '<option value="'.$dados['CdContrato'].'">'.$Descricao.'</option>';
				}
			} 
		}else{
			echo "<option value=''> Nenhum encontrado  </option>";
		} 
	}

?>