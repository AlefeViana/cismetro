<?php
@session_start();

error_reporting(-1); ini_set('display_errors', 'On');

require "../../vendor/autoload.php";

use App\Config\Connection;

use App\Objects\Patient;

use App\Objects\Neighborhood;

$msg = new \Plasticbrain\FlashMessages\FlashMessages();

$user_id = $_SESSION["CdUsuario"];

$patientId = $_POST["cd_paciente"] ?? null;

$redirect_back = "./../index.php?i=1&r=msg";

if (!$user_id) {	
    $msg->error('Not Authorized', $redirect_back);
}

function checkIfexists($db, $condition)
{
	$sql = "SELECT * FROM tbpaciente WHERE {$condition}";
    $query = mysqli_query($db, $sql) or die(mysqli_error($db));
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
$allowedActions = ['cad', 'edit', 'del'];

$action = $_POST['acao'] ?? "cad";

if(!in_array($action, $allowedActions )){
	die('action not allowed');
}

$connection = Connection::connect($db_attributes);

if($action !== "cad"){
	if(!$patientId){

		$msg->error("Paciente não informado", $redirect_back);
		die();

	}

	$patient = Patient::find($connection, $patientId);

}


if($action == "cad" || $action == "edit"){
	//recebe as variaveis do formulario
	
	$paciente_nome = mysqli_real_escape_string($db,$_POST["nm_paciente"]);
	$RG = mysqli_real_escape_string($db,$_POST["rg_paciente"]);
	$orgaorg = mysqli_real_escape_string($db,$_POST['orgaorg']);
	$cpf = $_POST["cpf_paciente"] ? str_replace([".", "-"], "", $_POST["cpf_paciente"]) :  null;
	$OutrosDocs = $_POST["docs_paciente"];
	$sexo = $_POST["sexo_paciente"];
	$data_de_nascimento = $_POST["dtnasc_paciente"];

	

	if(!isDateValid($data_de_nascimento, 'Y-m-d')) { die("date is not valid"); }


	$Natural = mysqli_real_escape_string($db,$_POST["naturalidade"]);
	$nacionalidade = mysqli_real_escape_string($db,$_POST["nacionalidade"]);
	$nome_da_mae = mysqli_real_escape_string($db,$_POST["mae_paciente"]);
	$PaiPac = mysqli_real_escape_string($db,$_POST["pai_paciente"]);
	$telefone = str_replace(["(", ")", "-"], "", $_POST["tel_paciente"]);
	$celular = str_replace(["(", ")", "-"], "", $_POST["cel_paciente"]);
	$Email = mb_strtolower($_POST["email_paciente"]);
	$Profissao = $_POST["profissao"];
	$logradouro = mysqli_real_escape_string($db,$_POST['log_paciente']);

	$numero = $_POST["num_paciente"];
	$Compl = $_POST["compl_paciente"];
	(string) $neighborhoodName =  $_POST["bairro"] ? mb_strtoupper($_POST["bairro"]) : null;
	
	$countyId = (int)$_POST["cidade"];
	$cep = str_replace("-", "", $_POST["cep_paciente"]);
	$Referencia = mysqli_real_escape_string($db,$_POST["referencia"]);

	(int) $cdupa = $_POST["und_saude"] ?? 0;
	$csus = $_POST["csus"] ?? null;
	$logradouro_id = $_POST["logr"];
	$CertidaoMatricula = $_POST["CertidaoMatricula"];
	$matricula = mysqli_real_escape_string($db,$_POST["matricula"]);
	$numprontuario = mysqli_real_escape_string($db,$_POST["numprontuario"]);
	$isNotifiable = (isset($_POST["isNotifiable"])) ? 0 : 1;

	//var_dump($_POST); die();
	$errors = 0;

	// if(!$csus){
	// 	$msg->error('O número do cartão SUS é obrigatório.');
	// 	$errors++;
	// }

/* 	if(!$cpf){
		$msg->error('O CPF é obrigatório.');
		$errors++;
	} */

	if (!$paciente_nome) {
		$msg->error('Nome do paciente não informado.');
		$errors++;
	}

	if (!$sexo) {
		$msg->error('Sexo não informado.');
		$errors++;
	}

	if(!$neighborhoodName){
		$msg->error('O bairro precisa ser informado');
		$errors++;
	}

	if(strlen($neighborhoodName) < 3 || strlen($neighborhoodName) > 100){
		$msg->error('O bairro deve conter de 3 a 100 caractéres.');
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


	if($errors > 0) {
		$msg->error("informações inválidas", $redirect_back);
		die();
	}

	$neighborhoodName = trim(mysqli_real_escape_string($db,$neighborhoodName));

	$condition = "WHERE neighborhood.NmBairro = '". $neighborhoodName ."' 
	AND neighborhood.CEP = {$cep}
	AND neighborhood.CdPref = {$countyId}";

	$neighborhood = Neighborhood::all($connection, $condition)->first();	


	if(!$neighborhood){
		
        $neighborhood = Neighborhood::create(
			$connection,
			[
				'NmBairro' => $neighborhoodName,
				'CdPref' => $countyId,
				'CEP' => $cep,
				'UserInc' => $user_id
			]
		);
	}

	
	
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
		$sql = 'INSERT INTO 
		tbpaciente 
		(is_notifiable,CdBairro,NmPaciente,RG,orgaorg,CPF,OutrosDocs,Sexo,DtNasc,Naturalidade,Nacionalidade,NmMae,NmPai,Telefone,Celular,Email,Profissao,Logradouro,Numero,Compl,CEP,Referencia,cdupa,UserInc,csus,cdlogr,CertidaoMatricula, Matricula, Prontuario) 		
		VALUES
		( "'.$isNotifiable.'", "'.$neighborhood->id.'","'.$paciente_nome.'","'.$RG.'","'.$orgaorg.'","'.$cpf.'","'.$OutrosDocs.'","'.$sexo.'","'.$data_de_nascimento.'","'.$Natural.'","'.$nacionalidade.'","'.$nome_da_mae.'","'.$PaiPac.'","'.$telefone.'","'.$celular.'","'.$Email.'","'.$Profissao.'","'.$logradouro.'","'.$numero.'","'.$Compl.'","'.$cep.'","'.$Referencia.'","'.$cdupa.'","'.$user_id.'","'.$csus.'","'.$logradouro_id.'","'.$CertidaoMatricula.'", "'.$matricula.'", "'.$numprontuario.'")';
		
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
	
			
		if($query){
			$msg->success('Paciente cadastrado com sucesso!', $redirect_back."&response=success");
		} 
		else $msg->error('Cadastro de paciente: não foi possí­vel realizar a operação. ',$redirect_back);
		
	} 
	else{
		//alterar
		$sql = 
		"UPDATE tbpaciente
		SET 
		CdBairro   = " . (int) $neighborhood->id . ", 
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
	    CdPaciente={$patientId}";
	
		//echo $sql; die();
		$query = mysqli_query($db, $sql);

		if(!$query) $msg->error('Ocorreu um erro desconhecido e o registro não pode ser cadastrado!', $redirect_back);
		$msg->info('Paciente #'.$patientId.' modificado com sucesso!', $redirect_back);
	}
}

elseif ($action == "del") {
	//excluir

	if($patient->status){
	
		$query = mysqli_query($db, "SELECT CdPaciente FROM tbsolcons WHERE CdPaciente=$patientId");

		//or die (TrataErro(mysqli_errno(),'','../index.php?p=lista_pac','regn_pac:vinculo pac solicitacao'));
		if (mysqli_num_rows($query)) {
			$msg->error('O Registro #'.$patientId.' não pode ser desativado pois este possui a uma ou mais consultas!', $redirect_back);
			die();	
		}

    }
		
	

	if(!$patient->toggleStatus()) $msg->error("não foi possível realizar a operação");
	
	$msg->info("Registro #{$patientId} ".($patient->status  ? "ativado" : "desativado")." com sucesso!", $redirect_back);
	
}

else{
	$msg->error('operação não foi reconhecida', $redirect_back);
	die();
}

