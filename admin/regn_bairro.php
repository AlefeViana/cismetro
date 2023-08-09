<?php
// ini_set('display_errors', 1);
// define("DIRECT_ACCESS", true);
require("../conecta.php");
//verifica se o usuario esta logado no sistema
require_once("verifica.php");

//funcao para tratar erro
require("function_trata_erro.php");

//recebe a variavel caso chamado do Cadastro de Paciente
$PagDestino = (int)$_GET["pg"];

//recebe as variaveis do formulario
$CdBairro   = $_POST["cd_bairro"];
$NmBairro 	= $_POST["nm_bairro"];
$CdPref    	= $_POST["cd_pref"];


//recebe o tipo de acao
$acao       = $_POST["acao"];

//verifica campos obrigatórios
$tudook = 1;
if ($NmBairro == "") {
	$msg_erro .= 'Preencha o campo Bairro<br />';
	$tudook = 0;
}

if (isset($_GET['deactivate'])) {
	$CdBairro = $_GET['id'];
	$cdusuario = $_SESSION['CdUsuario'];
	$status = $_GET['deactivate'];
	if ($status == 'true') {
		$sql = "UPDATE tbbairro
				SET `Status` = 0,
				UserAlt  = $cdusuario,
				DtAlt    = NOW()
				WHERE CdBairro = $CdBairro";
		mysqli_query($db, $sql) or die(mysqli_error($db));
	} else {
		$sql = "UPDATE tbbairro
				SET `Status` = 1,
				UserAlt  = $cdusuario,
				DtAlt    = NOW()
				WHERE CdBairro = $CdBairro";
		$qry = mysqli_query($db, $sql) or die(mysqli_error($db));
	}
	echo '<script language="JavaScript" type="text/javascript">window.location.href="../index.php?i=2";</script>';
} elseif ($tudook == 0) {
	//Controla se os campos obrigatorios estao preenchidos
	echo "Favor verificar o seguinte campo!<br /><br />" . $msg_erro;
	echo '<br /><br /><a href="#" onclick="javascript: history.go(-1);">Voltar</a>';
} else {
	if (substr($CdBairro, 0, 4) == "Auto") {
		
		//verifica se já existe o bairro na cidade
		$qry = mysqli_query($db, "SELECT NmBairro FROM tbbairro WHERE CdPref=$CdPref AND NmBairro='$NmBairro'")
			or die(TrataErro(mysqli_errno(), '', '../index.php?p=frm_cadbairro', 'regn_bairro:verifica bairro'));
		if (mysqli_num_rows($qry) == 0) {
			//gera um novo codigo
			/* $qry = mysqli_query($db,"SELECT MAX(CdBairro) FROM tbbairro") 
					or die(TrataErro(mysqli_errno(),'','../index.php?p=frm_cadbairro','regn_bairro:gerar novo codigo'));
			$CdBairro = mysqli_result($qry,0) + 1; */
			$sql = "INSERT INTO tbbairro (NmBairro,CdPref,UserInc)	VALUES('$NmBairro',$CdPref,$_SESSION[CdUsuario])";
			$qry = mysqli_query($db, $sql) or die(TrataErro(mysqli_errno(), '', '../index.php?p=frm_cadbairro', 'regn_bairro:insert bairro'));
			//verifica a pagina de destino
			if ($PagDestino == 1) {
				echo '<script language="JavaScript" type="text/javascript"> 
					alert("Cadastro realizado com sucesso!");
					window.location.href="../index.php?p=frm_cadpac";
				  </script>';
			} else {
				echo '<script language="JavaScript" type="text/javascript"> 
						
						var agree=confirm("Cadastro realizado com sucesso! \nGostaria de cadastrar outro bairro?");
						if (!agree)
							window.location.href="../index.php?i=2";
						else	
							window.location.href="../index.php?i=2&s=n";
						
						
					  </script>';

				//echo "Cadastro realizado com sucesso!";
			}
		} else {
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
			echo '<script language="JavaScript" type="text/javascript"> 
					alert("Você está tentando entrar com um campo já existente nessa tabela. Nome do Campo: Bairro");
					window.location.href="../index.php?i=2&s=n";
				  </script>';
		}
	} else {
		if ($acao == "edit") {
			//alterar
			$sql = "UPDATE tbbairro
						SET NmBairro = '$NmBairro',
						    CdPref   = $CdPref,
							UserAlt  = $_SESSION[CdUsuario],
							DtAlt    = NOW()
						WHERE CdBairro=$CdBairro";

			
			//verifica se já existe o bairro na cidade
			$qry = mysqli_query($db, "SELECT NmBairro FROM tbbairro WHERE CdPref=$CdPref AND NmBairro='$NmBairro'")
				or die(TrataErro(mysqli_errno(), '', '../index.php?p=frm_cadbairro', 'regn_bairro:verifica bairro'));
			if (mysqli_num_rows($qry) == 0) {
				$qry = mysqli_query($db, $sql) or die(TrataErro(mysqli_errno(), '', '../index.php?p=lista_bairro', 'regn_bairro:update bairro'));
				echo '<script language="JavaScript" type="text/javascript"> 
					alert("Dados alterados com sucesso!");
					window.location.href="../index.php?i=2";				
				  </script>';
			} else {
				echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
				echo '<script language="JavaScript" type="text/javascript"> 
					alert("Você está tentando entrar com um campo já existente nessa tabela. Nome do Campo: Bairro");
					window.location.href="../index.php?i=2&s=n";
				  </script>';
			}
		} else {
			if ($acao == "del") {
				//excluir	
				//verifica se existe algum bairro vinculado ao paciente
				$qry = mysqli_query($db, "SELECT CdPaciente FROM tbpaciente WHERE CdBairro=$CdBairro")
					or die(TrataErro(mysqli_errno(), '', '../index.php?p=lista_bairro', 'regn_bairro:vinculo bairro paciente'));

				if (mysqli_num_rows($qry) == 0) {

					$sql = "DELETE FROM tbbairro WHERE CdBairro=$CdBairro";
					$qry = mysqli_query($db, $sql) or die(TrataErro(mysqli_errno(), '', '../index.php?p=lista_bairro', 'regn_bairro:delete bairro'));

					echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
						<script language=\"JavaScript\" type=\"text/javascript\"> 
							alert(\"Bairro excluído com sucesso!\");
							window.location.href=\"../index.php?i=2&s=l\";				
			 			 </script>";
				} else {
					echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						 <script language="JavaScript" type="text/javascript"> 
							alert("Bairro não pode ser excluído, devido ele estar associado a um ou mais pacientes!");
							window.location.href="../index.php?i=2&s=l";				
			 			 </script>';
				}
			}
		}
	}
	@mysqli_close();
	@mysqli_free_result($qry);
}
