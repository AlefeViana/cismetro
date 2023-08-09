<?php 

define("DIRECT_ACCESS", true);

require_once("../verifica.php");
require_once("../funcoes.php");

$dados 		= $_POST;
$arquivo 	= descobreForm($dados['cd_especificacao']);
$usuario 	= $_SESSION["CdUsuario"];
$data 		= date('Y-m-d H:i:s');
$CdPref 	= muninicipioPaciente($dados['cd_paciente']);

if (isset($_POST)) {
	//print_r($dados);
	$arquivos = $_FILES['ceaeanexos'];
	//print_r($arquivos);
	$query_referencia = "INSERT INTO tbceaereferencia(CdEspecProc,CdPaciente,CdPref,infoComplementares,medicamento,profissinal)
			VALUES ('$dados[cd_especificacao]','$dados[cd_paciente]','$CdPref','$dados[infoComplementares]','$dados[medicamento]','$dados[profissinal]')";
	$gravar1 = mysqli_query($db,$query_referencia) or die("Erro ao tentar incluir referencia: ".mysqli_error());

	if ($gravar1) {
		$cdreferenciaceae = mysqli_insert_id($db);
		/*Anexar Arquivos Referencia*/
		for ($i=0; $i < count($arquivos['tmp_name']); $i++) {
			$nome 	= $arquivos['name'][$i];
			$ext 	= strrchr($nome, '.');
			$imagem = time().uniqid(md5(1)).$ext;
			$tipo 	= $arquivos['type'][$i];

			if(move_uploaded_file($arquivos['tmp_name'][$i], '../pg/ceaefrm/anexos/'.$imagem)){
				$query_referencia_arquivos = "INSERT INTO `tbceaereferenciaanexo` (cdreferenciaceae,ext,nome,url,tipo,data,usuario) VALUES ('$cdreferenciaceae','$ext','$nome','$imagem','$tipo','$data','$usuario')";
				$gravar3 = mysqli_query($db,$query_referencia_arquivos);
			}
		}
		/*Anexar Arquivos Referencia*/
		$query_referencia_log = "INSERT INTO `tbceaereferencialog`(cdreferenciaceae,acao,data,usuario) VALUES('$cdreferenciaceae','C','$data','$usuario')";
		$gravar2 = mysqli_query($db,$query_referencia_log) or die(mysqli_error());

		unset($dados['cd_paciente'],$dados['cd_especificacao'],$dados['cd_proc'],$dados['infoComplementares'],$dados['medicamento'],$dados['profissinal']);
		$dados += array('cdreferenciaceae'=>$cdreferenciaceae);
		//print_r($dados);
		create($arquivo['tbvivavida'],$dados);

		mysqli_close();
		echo "<script language='JavaScript' type='text/javascript'> 
				alert('Solicitação realizada com sucesso!');
				window.location.href='../index.php?i=$_GET[i]';
				</script>";
	}else{
		echo "<script language='JavaScript' type='text/javascript'> 
			alert('Não foi possível!');
			window.location.href='../index.php?i=$_GET[i]';
			</script>";
	}
}else{
	echo "<script language='JavaScript' type='text/javascript'> 
			alert('Não foi possível!');
			window.location.href='../index.php?i=$_GET[i]';
			</script>";
}




?>