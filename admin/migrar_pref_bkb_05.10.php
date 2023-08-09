<?php 
	if(isset($_GET["cdpaciente"]) && isset($_GET["newcdpref"]))
	{
		define("DIRECT_ACCESS", true);

		include("verifica.php");
		
		require_once("../conecta.php");

    	$cdpaciente = $_GET["cdpaciente"];
		$newcdpref = $_GET["newcdpref"];
		$data = date("Y-m-d H:i:s");
		$user = (int)$_SESSION["CdUsuario"];

		$query_valpac = mysqli_query($db,"SELECT b.CdPref FROM tbpaciente p 
									 INNER JOIN tbbairro b ON p.CdBairro = b.CdBairro 
									 WHERE p.CdPaciente = '$cdpaciente'");

		if(mysqli_num_rows($query_valpac)>0)	
		{
			$result = mysqli_fetch_array($query_valpac);

			if($result['CdPref'] > 0 && $result['CdPref'] != $newcdpref)
			{
				$query_bairro = mysqli_query($db,"SELECT CdBairro FROM tbbairro WHERE CdPref = '$newcdpref'");
				$query_bairro = mysqli_fetch_array($query_bairro);
				$newcdbairro = $query_bairro['CdBairro'];

				$query = mysqli_query($db,"UPDATE tbpaciente SET CdBairro = '$newcdbairro', DtAlt = '$data', UserAlt = '$user' WHERE CdPaciente = '$cdpaciente'") or die("erro");

				if($query)
					echo  "<script language='JavaScript' type='text/javascript'> 
							alert('Migração realizada com sucesso!');
							window.location.href='../index.php?i=1';
			  			   </script>";
			}
		}
	}
	else
		echo  "<script language='JavaScript' type='text/javascript'> 
					alert('Erro na migração!');
					window.location.href='../index.php?i=1';
			   </script>";
?>