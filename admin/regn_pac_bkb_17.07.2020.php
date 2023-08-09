<?php
@session_start();

require "../../vendor/autoload.php";

$msg = new \Plasticbrain\FlashMessages\FlashMessages();

//$msg = new \Plasticbrain\FlashMessages\FlashMessages();
//verifica se o usuario esta logado no sistema

$user_id = $_SESSION["CdUsuario"];

$paciente_id = $_POST["cd_paciente"];

$redirect_back = "./../index.php?i=1&r=msg";


if (!$user_id) {	
    $msg->error('Not Authorized', $redirect_back);
}

function checkIfexists($db, $condition)
{
	$sql = "SELECT * FROM tbpaciente WHERE {$condition}";
    $query = mysqli_query($db, $sql) or die(mysqli_error());
    return $query->num_rows ? true : false;
}

function FData($data)
{
    $val = explode("/", $data);
    return $val[2] . "-" . $val[1] . "-" . $val[0];
}

//verifica se o usuario esta logado no sistema

require "../conecta.php";

//funcao para tratar erro
require "function_trata_erro.php";

//recebe o tipo de acao
$action = $_POST["acao"];

if($action != "cad" && !$paciente_id){
	$msg->error("Paciente nÃ£o informado", $redirect_back);
    die();
}


if($action == "cad" || $action == "edit"){
	//recebe as variaveis do formulario
	
	$paciente_nome = $_POST["nm_paciente"];
	$RG = $_POST["rg_paciente"];
	$orgaorg = $_POST['orgaorg'];
	$cpf = str_replace([".", "-"], "", $_POST["cpf_paciente"]);
	$OutrosDocs = $_POST["docs_paciente"];
	$sexo = $_POST["sexo_paciente"];
	$data_de_nascimento = FData($_POST["dtnasc_paciente"]);
	$Natural = $_POST["naturalidade"];
	$nacionalidade = $_POST["nacionalidade"];
	$nome_da_mae = $_POST["mae_paciente"];
	$PaiPac = $_POST["pai_paciente"];
	$telefone = str_replace(["(", ")", "-"], "", $_POST["tel_paciente"]);
	$celular = str_replace(["(", ")", "-"], "", $_POST["cel_paciente"]);
	$Email = mb_strtolower($_POST["email_paciente"]);
	$Profissao = $_POST["profissao"];
	$logradouro = $_POST["log_paciente"];
	$numero = $_POST["num_paciente"];
	$Compl = $_POST["compl_paciente"];
	$bairro_id = $_POST["bairro"];
	$cep = str_replace("-", "", $_POST["cep_paciente"]);
	$Referencia = $_POST["referencia"];

	$cdupa = (int) $_POST["und_saude"];
	$csus = $_POST["csus"];
	$logradouro_id = $_POST["logr"];
	$CertidaoMatricula = $_POST["CertidaoMatricula"];
	$matricula = $_POST["matricula"];
	$numprontuario = $_POST["numprontuario"];
	$isNotifiable = $_POST["isNotifiable"];

	//var_dump($_POST); die();
	$errors = 0;

	if (!$paciente_nome) {
		$msg->error('Nome do paciente nÃ£o informado.');
		$errors++;
	}

	if (!$sexo) {
		$msg->error('Sexo nÃ£o informado.');
		$errors++;
	}


	if (!$data_de_nascimento) {
		$msg->error('Data de nascimento nÃ£o informada.');
		$errors++;
	}

	if (!$nacionalidade) {
		$msg->error('Nacionalidade nÃ£o informada.');
		$errors++;
	}

	if (!$nome_da_mae) {
		$msg->error('Nome da mÃ£e nÃ£o informado.');
		$errors++;
	}

	/* if (!$telefone) {
		$msg->error('Telefone nÃ£o informado.');
		$errors++;
	} */

	if (!$cep) {
		$msg->error('Cep nÃ£o informado.');
		$errors++;
	}

	if (!$logradouro_id) {
		$msg->error('CÃ³digo do logradouro nÃ£o informado.');
		$errors++;
	}

	if (!$logradouro) {
		$msg->error('CÃ³digo do logradouro nÃ£o informado.');
		$errors++;
	}

	if (!$numero) {
		$msg->error('NÃƒÂºmero nÃ£o informado.');
		$errors++;
	}

	if (!$bairro_id) {
		$msg->error('Bairro nÃ£o informado.');
		$errors++;
	}

	if($errors > 0) {
		$msg->error("InformaÃ§Ãµes invÃ¡lidas", $redirect_back);
		die();
	}

	if ($action == "cad") {
		$patientExists = checkIfexists(
			$db,
			"NmPaciente = '$paciente_nome'
			AND DtNasc = '$data_de_nascimento'
			AND NmMae = '$nome_da_mae'");
	
		
		
		if($patientExists){
			$msg->error("O nome do paciente jÃ¡ estÃ¡ cadastrado. Por favor, tente novamente", $redirect_back);
			die();
		}
		$sql = "INSERT INTO 
		tbpaciente 
		(CdBairro,NmPaciente,RG,orgaorg,CPF,OutrosDocs,Sexo,DtNasc,Naturalidade,Nacionalidade,NmMae,NmPai,Telefone,Celular,Email,Profissao,Logradouro,Numero,Compl,CEP,Referencia,cdupa,UserInc,csus,cdlogr,CertidaoMatricula, Matricula, Prontuario) 		
		VALUES
		($bairro_id,'$paciente_nome','$RG','$orgaorg','$cpf','$OutrosDocs','$sexo','$data_de_nascimento','$Natural','$nacionalidade','$nome_da_mae','$PaiPac','$telefone','$celular','$Email','$Profissao','$logradouro','$numero','$Compl','$cep','$Referencia','$cdupa','$user_id','$csus','$logradouro_id','$CertidaoMatricula', '$matricula', '$numprontuario')";
		
		$query = mysqli_query($db, $sql);
	
			
		if($query) $msg->success('Paciente cadastrado com sucesso!', $redirect_back);
		else $msg->error('Cadastro de paciente: nÃ£o foi possÃƒÂ­vel realizar a operaÃ§Ã£o. ',$redirect_back);
		
	} 
	else{
		//alterar
		$sql = 
		"UPDATE tbpaciente
		SET 
		CdBairro   = $bairro_id, 
		NmPaciente = '$paciente_nome',
		RG         = '$RG',
		orgaorg = '$orgaorg',
		CPF        = '$cpf',
		OutrosDocs = '$OutrosDocs',
		Sexo       = '$sexo',
		DtNasc     = '$data_de_nascimento',
		Naturalidade='$Natural',
		Nacionalidade='$nacionalidade',
		NmMae      = '$nome_da_mae',
		NmPai      = '$PaiPac',
		Telefone   = '$telefone',
		Celular    = '$celular',
		Email      = '$Email',
		Profissao  = '$Profissao',
		Logradouro = '$logradouro',
		Numero     = '$numero',
		Compl      = '$Compl',
		CEP        = '$cep',
		Referencia = '$Referencia',
		DtAlt      = NOW(),
		UserAlt    = '$user_id',
		csus    = '$csus',
		cdlogr     = '$logradouro_id',
		CertidaoMatricula = '$CertidaoMatricula',
		Matricula = '$matricula', 
		Prontuario = '$numprontuario',
		is_notifiable = '$isNotifiable'
		WHERE 
	    CdPaciente={$paciente_id}";
	
		//echo $sql; die();
		$query = mysqli_query($db, $sql);

		if(!$query) $msg->error('Ocorreu um erro desconhecido e o registro nÃ£o pode ser cadastrado!', $redirect_back);
		$msg->info('Paciente #'.$paciente_id.' modificado com sucesso!', $redirect_back);
	}
}

elseif ($action == "del") {
	//excluir

	if($_POST['paciente_status'] == 1){
	
		$query = mysqli_query($db, "SELECT CdPaciente FROM tbsolcons WHERE CdPaciente=$paciente_id");

		//or die (TrataErro(mysqli_errno(),'','../index.php?p=lista_pac','regn_pac:vinculo pac solicitacao'));
		if (mysqli_num_rows($query)) {
			$msg->error('O Registro #'.$paciente_id.' nÃ£o pode ser desativado pois este possui a uma ou mais consultas!', $redirect_back);
			die();	
		}

    }
		
	$status = $_POST['paciente_status'] == '1' ? 0 : 1; 

	$sql = "UPDATE tbpaciente SET Status = '{$status}' WHERE CdPaciente={$paciente_id}";

	($query = mysqli_query($db, $sql));

	

	

	if(!$query) $msg->error("nÃ£o foi possÃ­Â­vel realizar a operaÃ§Ã£o");

		
	
	$msg->info("Registro #{$paciente_id} ".($status == "1" ? "ativado" : "desativado")." com sucesso!", $redirect_back);

	
	
}

else{
	$msg->error('OperaÃ§Ã£o nÃ£o foi reconhecida', $redirect_back);
	die();
}

?>
