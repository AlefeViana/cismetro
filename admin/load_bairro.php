<?php 

	define("DIRECT_ACCESS",  true);
	require_once("verifica.php");
	require("../conecta.php");

	require "../../vendor/autoload.php";
	use Stringy\Stringy as S;
	
	
	$CdPref = (int)$_GET['cdpref'];
	if ((int)$_SESSION['CdOrigem'])
		$CdPref = (int)$_SESSION['CdOrigem'];
	
	$sql = "SELECT CdBairro, NmBairro, b.CdPref, NmCidade FROM tbbairro b INNER JOIN tbprefeitura p ON b.CdPref=p.CdPref";	
	$sql .= " WHERE b.CdPref=".$CdPref;		
	$sql .=  " ORDER BY NmBairro";
	
	$qry = mysqli_query($db,$sql) or die (mysqli_error());
	if (mysqli_num_rows($qry) > 0){
		while ($dados = mysqli_fetch_array($qry)){
				echo '<option value="'.$dados['CdBairro'].'">'.(String)S::create($dados["NmBairro"])->titleize(["de", "da", "do"]).'</option>';
		}
	}
	else
	{
		echo '<option value="">Selecione uma cidade primeiro</option>';
	}

?>