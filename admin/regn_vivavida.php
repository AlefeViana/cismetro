<?php 
// ini_set('display_errors',1);
define("DIRECT_ACCESS", true);

// require_once("../verifica.php");
require_once("../funcoes.php");

$dados 		= $_POST;
isset($_POST['formulario']) ? $formulario = $_POST['formulario'] : $menu1 = true;
isset($formulario) ? $cd_especificacao = $formulario['cd_especificacao'] : $formulario = null;
// $arquivo 	= descobreForm($dados['cd_especificacao']) ?? descobreForm($cd_especificacao);
!isset($formulario['cd_especificacao']) ? $arquivo = descobreForm($dados['cd_especificacao']) : $arquivo = descobreForm($cd_especificacao);
$usuario 	= $_SESSION["CdUsuario"];
$data 		= date('Y-m-d H:i:s');
$CdPref 	= muninicipioPaciente($dados['cd_paciente']);

if (isset($_POST)) {
	//print_r($dados);
	isset($_FILES['ceaeanexos']) ? $arquivos = $_FILES['ceaeanexos'] : $menu1 = true;
	//print_r($arquivos);
	($_POST['acao'] != 'salvar') ? $query_referencia = "INSERT INTO tbceaereferencia(CdEspecProc,CdPaciente,CdPref,statusformulario)
			VALUES ('$dados[cd_especificacao]','$dados[cd_paciente]','$CdPref','R')" 
			: $query_referencia = "INSERT INTO tbceaereferencia(CdEspecProc,CdPaciente,CdPref,statusformulario)
			VALUES ('$formulario[cd_especificacao]','$_POST[cd_paciente]','$CdPref','R')";
			
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
				chmod('../pg/ceaefrm/anexos/'.$imagem, 0777);
				$query_referencia_arquivos = "INSERT INTO `tbceaereferenciaanexo` (cdreferenciaceae,ext,nome,url,tipo,data,usuario) VALUES ('$cdreferenciaceae','$ext','$nome','$imagem','$tipo','$data','$usuario')";
				$gravar3 = mysqli_query($db,$query_referencia_arquivos);
			}
		}
		/*Anexar Arquivos Referencia*/
		$query_referencia_log = "INSERT INTO `tbceaereferencialog`(cdreferenciaceae,acao,data,usuario) VALUES('$cdreferenciaceae','C','$data','$usuario')";
		$gravar2 = mysqli_query($db,$query_referencia_log) or die(mysqli_error());

		unset($dados['cd_paciente'],$dados['cd_especificacao'],$dados['cd_proc'],$dados['infoComplementares'],$dados['medicamento'],$dados['profissinal']);
		unset($formulario['cd_paciente'],$formulario['cd_especificacao'],$formulario['cd_proc'],$formulario['infoComplementares'],$formulario['medicamento'],$formulario['profissinal']);
		$dados += array('cdreferenciaceae'=>$cdreferenciaceae);
		isset($formulario) ? $formulario += array('cdreferenciaceae'=>$cdreferenciaceae) : $menu1 = true;
		//print_r($dados);
		
		($_POST['acao'] != 'salvar') ? mysqli_query($db, "INSERT INTO tbceaereferencia (infoComplementares) VALUES ($dados[infoComplementares_mun])") : $id = create('tbceaereferencia',$formulario);
		
		($_POST['acao'] != 'salvar') ? $sql_plano =  "INSERT INTO tbceaeplanodecuidado (`plano_cuidado`,`user`,`dtinct`,`hora`,`cdformceae`) 
							VALUES ('$dados[infoComplementares_mun]','$_SESSION[CdUsuario]',NOW(),NOW(),'$dados[cdreferenciaceae]')"
							: $sql_plano =  "INSERT INTO tbceaeplanodecuidado (`user`,`dtinct`,`hora`,`cdformceae`) 
							VALUES ('$_SESSION[CdUsuario]',NOW(),NOW(),'$formulario[cdreferenciaceae]')";

		// var_dump($sql_plano);
		// var_dump($dados);
		// die();
		mysqli_query($db,$sql_plano);

		mysqli_close($db);
		echo "<script language='JavaScript' type='text/javascript'> 
				alert('Estratificação realizada com sucesso!');
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