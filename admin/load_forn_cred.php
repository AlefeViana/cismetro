<?php 
	
	define("DIRECT_ACCESS",  true);
	
	require_once("verifica.php");
	require("../conecta.php");

	require "../../vendor/autoload.php";
	use Stringy\Stringy as S;
	$cred = '';
	if(isset($_GET['cdcred']) && $_GET['cdcred'] > 0 ){
		$cdcred = $_GET['cdcred'];
		if(isset($_GET['mun']) && $_GET['mun'] > '' ){
			$mun = $_GET['mun'];
			$cred = " WHERE flc.cdlicitacao = $cdcred AND cpl.Cidade LIKE '%$mun%'";
		}else
			$cred = " WHERE flc.cdlicitacao = $cdcred";
	}
	if(isset($_GET['mun']) && $_GET['mun'] > '' ){
		$cred = " WHERE cpl.Cidade LIKE '%$mun%'";
	}

	if(isset($_GET['mun'])){
		$sql = "SELECT f.CdForn, f.NmForn , cpl.Cidade
		FROM tblctfornecedor_licitacao flc
		INNER JOIN tbcredfornecedor cf on cf.CdForn = flc.cdforn
		INNER JOIN tbcredprofissional cp on cp.CdCredForn  = cf.CdCredForn
		INNER JOIN tbcredprofissionallocalatend cpl on cpl.CdCredProf = cp.CdCredProf
		INNER JOIN tbfornecedor f on f.CdForn = flc.cdforn
		$cred
		GROUP BY f.CdForn
		ORDER BY f.NmForn";

	}else{
		$sql = "SELECT f.CdForn, f.NmForn 
		FROM tblctfornecedor_licitacao flc
		INNER JOIN tbfornecedor f on f.CdForn = flc.cdforn
		$cred
		GROUP BY f.CdForn
		ORDER BY f.NmForn";
	}
	
	//echo $sql;
	$qry = mysqli_query($GLOBALS['db'], $sql) or die (mysql_error());
	
	if (mysqli_num_rows($qry) > 0){
			echo "<option value='0'> Todos </option>";
			
		while ($dados = mysqli_fetch_array($qry)){
				$NmForn   = (String)S::create($dados['NmForn'])->titleize(["de", "da", "do"]);
				echo '<option value="'.$dados['CdForn'].'">'.$dados['CdForn'].' - '.$NmForn.'</option>';
		}  
	} 
	else {
		echo "<option value=''> Nenhum fornecedor encontrado  </option>";
	}
	

	mysql_close();
	mysql_free_result($qry);
?>