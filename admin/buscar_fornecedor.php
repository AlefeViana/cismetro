<?php

	define("DIRECT_ACCESS",  true);

	session_start();
	$erro = "";
	if(isset($_POST["cdespec"]) && $_POST["cdespec"] > 0)
	{
		require_once("../funcoes.php");
		$cdespec = $_POST["cdespec"];

		$query = mysqli_query($db," SELECT fe.CdForn,f.NmForn from tbfornespec fe 
                                    INNER JOIN tbfornecedor f on f.CdForn = fe.CdForn
                                    WHERE fe.`Status` = 1 and fe.CdEspec = $cdespec ");

		if(mysqli_num_rows($query) > 0)
		{
			while($n = mysqli_fetch_array($query))
				$array[] = array('CdForn' => $n["CdForn"], 'Nome' => utf8_encode($n["NmForn"]));
		}
		else
			$erro = "Nenhum fornecedor encontrado!";
	}
	else
		$erro = "Conexão perdida, entre no sistema novamente!";

	echo json_encode(array('dados' => $array, 'erro' => $erro));	
?>