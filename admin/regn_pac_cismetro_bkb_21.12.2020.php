<?php
@session_start();

require "../../vendor/autoload.php";

$msg = new \Plasticbrain\FlashMessages\FlashMessages();

//$msg = new \Plasticbrain\FlashMessages\FlashMessages();
//verifica se o usuario esta logado no sistema

$user_id = $_SESSION["CdUsuario"];

$paciente_id = $_POST["cd_paciente"];

$redirect_back = "./../index.php?i=1";


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
	$msg->error("Paciente não informado", $redirect_back);
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
	$nome_bairro =  mb_strtoupper($_POST["bairro"]);
	$cidade = $_POST["cidade"];
	$cep = str_replace("-", "", $_POST["cep_paciente"]);
	$Referencia = $_POST["referencia"];

	$cdupa = (int) $_POST["und_saude"];
	$csus = $_POST["csus"];
	$logradouro_id = $_POST["logr"];
	$CertidaoMatricula = $_POST["CertidaoMatricula"];
	$matricula = $_POST["matricula"];
	$numprontuario = $_POST["numprontuario"];
	$isNotifiable = (isset($_POST["isNotifiable"]))? 0 : 1;

	//var_dump($_POST); die();
	$errors = 0;

	if (!$paciente_nome) {
		$msg->error('Nome do paciente não informado.');
		$errors++;
	}

	if (!$sexo) {
		$msg->error('Sexo não informado.');
		$errors++;
	}


	if (!$data_de_nascimento) {
		$msg->error('Data de nascimento não informada.');
		$errors++;
	}

	if (!$nacionalidade) {
		$msg->error('Nacionalidade não informada.');
		$errors++;
	}

	if (!$nome_da_mae) {
		$msg->error('Nome da mãe não informado.');
		$errors++;
	}

	/* if (!$telefone) {
		$msg->error('Telefone não informado.');
		$errors++;
	} */

	if (!$cep) {
		$msg->error('Cep não informado.');
		$errors++;
	}

	if (!$logradouro_id) {
		$msg->error('Código do logradouro não informado.');
		$errors++;
	}

	if (!$logradouro) {
		$msg->error('Código do logradouro não informado.');
		$errors++;
	}

	if (!$numero) {
		$msg->error('Número não informado.');
		$errors++;
	}

	/* if (!$bairro_id) {
		$msg->error('Bairro não informado.');
		$errors++;
	} */

	if($errors > 0) {
		$msg->error("informações inválidas", $redirect_back);
		die();
	}

	$cdbairro = "SELECT CdBairro from tbbairro b WHERE b.CEP = $cep AND b.NmBairro = '$nome_bairro' AND b.CdPref = '$cidade'";
	$query = mysqli_query($db, $cdbairro);
	if(mysqli_num_rows($query) > 0){
		$dados_bairro = mysqli_fetch_array($query);
		$bairro_id = $dados_bairro['CdBairro'];
	}else{
		$qry_insere_bairro = mysqli_query($db,"INSERT INTO tbbairro (NmBairro, CdPref, CEP, UserInc) VALUES (\"$nome_bairro\", '$cidade','$cep','$user_id')");
		$bairro_id = mysqli_insert_id($db);
	}
	//echo $bairro_id; die();
	if ($action == "cad") {
		$patientExists = checkIfexists(
			$db,
			"NmPaciente = '$paciente_nome'
			AND DtNasc = '$data_de_nascimento'
			AND NmMae = '$nome_da_mae'");
		
		if($patientExists){
			$msg->error("O nome do paciente já está cadastrado. Por favor, tente novamente", $redirect_back);
			die();
		}
		$sql = "INSERT INTO 
		tbpaciente 
		(is_notifiable,CdBairro,NmPaciente,RG,orgaorg,CPF,OutrosDocs,Sexo,DtNasc,Naturalidade,Nacionalidade,NmMae,NmPai,Telefone,Celular,Email,Profissao,Logradouro,Numero,Compl,CEP,Referencia,cdupa,UserInc,csus,cdlogr,CertidaoMatricula, Matricula, Prontuario) 		
		VALUES
		( '$isNotifiable', $bairro_id,'$paciente_nome','$RG','$orgaorg','$cpf','$OutrosDocs','$sexo','$data_de_nascimento','$Natural','$nacionalidade','$nome_da_mae','$PaiPac','$telefone','$celular','$Email','$Profissao',\"$logradouro\",'$numero','$Compl','$cep','$Referencia','$cdupa','$user_id','$csus','$logradouro_id','$CertidaoMatricula', '$matricula', '$numprontuario')";
		//echo $sql; die();
		$query = mysqli_query($db, $sql);
	
			
		if($query) $msg->success('Paciente cadastrado com sucesso!', $redirect_back.'&r=msg');
		else $msg->error('Cadastro de paciente: não foi possível realizar a operação. ',$redirect_back);
		
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
		Logradouro = \"$logradouro\",
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

		if(!$query) $msg->error('Ocorreu um erro desconhecido e o registro não pode ser cadastrado!', $redirect_back);
		$msg->info('Paciente #'.$paciente_id.' modificado com sucesso!', $redirect_back.'&r=msg');
	}
}

elseif ($action == "del") {
	//excluir

	if($_POST['paciente_status'] == 1){
	
		$query = mysqli_query($db, "SELECT CdPaciente FROM tbsolcons WHERE CdPaciente=$paciente_id");

		//or die (TrataErro(mysqli_errno(),'','../index.php?p=lista_pac','regn_pac:vinculo pac solicitacao'));
		if (mysqli_num_rows($query)) {
			$msg->error('O Registro #'.$paciente_id.' não pode ser desativado pois este possui a uma ou mais consultas!', $redirect_back);
			die();	
		}

    }
		
	$status = $_POST['paciente_status'] == '1' ? 0 : 1; 

	$sql = "UPDATE tbpaciente SET Status = '{$status}' WHERE CdPaciente={$paciente_id}";

	($query = mysqli_query($db, $sql));

	

	

	if(!$query) $msg->error("não foi possível realizar a operação");

		
	
	$msg->info("Registro #{$paciente_id} ".($status == "1" ? "ativado" : "desativado")." com sucesso!", $redirect_back);

	
	
}

else{
	$msg->error('operação não foi reconhecida', $redirect_back);
	die();
}

?>
