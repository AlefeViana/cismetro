<?php 

	define("DIRECT_ACCESS",  true);

	require_once("verifica.php");
	require("../conecta.php");
	

	$cdserv = (int)$_GET['cdserv'];
	
	$sql = "SELECT tbservico_classificacao.co_classificacao,tbservico_classificacao.no_classificacao FROM tbservico_classificacao";	
	$sql .= " WHERE tbservico_classificacao.co_servico =".$cdserv;		
	$sql .=  " ORDER BY tbservico_classificacao.co_classificacao";
	
	$qry = mysqli_query($db,$sql) or die (mysqli_error());
	if (mysqli_num_rows($qry) > 0){
		while ($dados = mysqli_fetch_array($qry)){
				echo '<option value="'.$dados['co_classificacao'].'">'.$dados['co_classificacao'].' - '.$dados['no_classificacao'].'</option>';
		}
	}
	else
	{
		echo '<option value="">Selecione um servi&ccedil;o primeiro</option>';
	}
	mysqli_close();
	mysqli_free_result($qry);
?>