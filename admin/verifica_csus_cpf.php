<?php 

	define("DIRECT_ACCESS", true);

	require_once("verifica.php");
	require("../conecta.php");
	
	$p = $_GET['csus'];
	$c = str_replace(array(".","-"),"",$_GET['cpf_paciente']);
	
	if(!empty($p)){
		$sql = "SELECT p.NmPaciente,p.csus,pr.NmCidade FROM tbpaciente AS p
				INNER JOIN tbbairro AS b ON b.CdBairro = p.CdBairro
				INNER JOIN tbprefeitura AS pr ON pr.CdPref = b.CdPref
				WHERE p.csus =  '$p'";
		$sql = mysqli_query($db,$sql) or die("Erro ao verificar cartão SUS");
		
		if(mysqli_num_rows($sql) > 0){
			$l = mysqli_fetch_array($sql);
			echo '"Já cadastrado: '.$l['NmPaciente'].', '.$l['NmCidade'].'"';
		}
		else
			echo 'true';
		exit(0);
	}
	if(!empty($c)){
		$sql = "SELECT tbpaciente.NmPaciente,tbpaciente.cpf FROM tbpaciente WHERE tbpaciente.cpf = '$c'";
		$sql = mysqli_query($db,$sql) or die("Erro ao verificar cartão SUS");
		
		if(mysqli_num_rows($sql) > 0)
			echo 'false';
		else
			echo 'true';
		exit(0);
	}	
	mysqli_close($db);
?>