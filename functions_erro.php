<?php 

require "controle/chat/jwt-generate.php";

//caio - 2020-06-25

use \Carbon\Carbon;

if (!session_id()) {
    @session_start();
}

function sessionTimeout($db, int $userId){	
	
	$msg = new \Plasticbrain\FlashMessages\FlashMessages();

	$sessions = getUserSessions($db, $userId);

	if(count($sessions)){

		$session = $sessions[0];
	
		if($session['chave'] !== session_id()){

			//die('whoops');

			clearSessionFromServer();	

			$msg->info('Você foi desconectado',
			"frm_login.php");
		}
		elseif(Carbon::now()->timestamp > $session['tempo']){

			
			destroyAuth($db, $userId);	

			$msg->info('Caro usuário, você ficou um longo período sem utilizar o sistema e sua sessão foi encerrada por segurança. Efetue novo logon!',
			"frm_login.php");
			die();
		}else{
			
			
			renewSession($db, $userId);
			
		}
	}
	else{


		destroyAuth($db, $userId);	
		$msg->info('Você foi desconectado',
			"frm_login.php");
		
		
	}
}

function renewSession($db, int $userId){
	$current_time = Carbon::now()->timestamp;
	$timeout = $current_time + MAX_LIFE_TIME;
	$sql = "UPDATE tbuserconn SET tempo='$timeout',horalogin='$current_time' WHERE usuario=".$userId;
	return $query = mysqli_query($db, $sql);
}

function initAuth($db, int $userId){
	$user = getUser($db, $userId);

	$params = [
		'session_id' => session_id(),
		'user_id' => (int) $userId,
		'timeout' => Carbon::now()->timestamp + MAX_LIFE_TIME,
		"current_time" => Carbon::now()->timestamp,
		'username' => $user['NmUsuario']
	];

	
	
	$session = createSession($db, $params);

	setAuthSession($user);

	$sessionData = getUserSessions($db, $userId);

	return [
	   'user' => $user,
	   'session' => $sessionData,
	];
	
}

function createSession($db, array $params) : bool
{
	$sql = "INSERT INTO tbuserconn (chave,usuario,tempo,horalogin,data,NmUsuario) 
	VALUES ('".$params["session_id"]."','".$params["user_id"]."','".$params["timeout"]."','".$params["current_time"]."', NOW(),'".$params["username"]."')";
	$query = mysqli_query($db,$sql);

	if($query){
		return true;
	}
	else{
		 echo 'Session:'.mysqli_error($db);
		 return false;
	}
}

function getUser($db, int $id)
{
	$user = null;

	$query = mysqli_query(
		$db, 
		"SELECT *
		FROM tbusuario
		WHERE CdUsuario = {$id} AND Status=1 
		LIMIT 1"
	);

	if($query && $query->num_rows){
		$user = mysqli_fetch_assoc($query);
		$multigrupos = getUserGroups($db,(int) $user['CdUsuario']); 
		array_push($multigrupos, $user["cdgrusuario"]);

		$user['multigrupos'] = $multigrupos;

	}

	return $user;

}



function setAuthSession(array $user)
{
	$_SESSION["CdUsuario"] = (int) $user["CdUsuario"];
    $_SESSION["NmUsuario"] = $user["NmUsuario"];
    $_SESSION["CdTpUsuario"] = (int) $user["CdTpUsuario"];
    $_SESSION["CdOrigem"] = (int) $user["CdOrigem"];
    $_SESSION["cdfornecedor"] = (int) $user["cdfornecedor"];
    $_SESSION["Login"] = $user["Login"];
    $_SESSION["cdgrusuario"] = $user["cdgrusuario"];
    $_SESSION["Email"] = $user["Email"] ? $user["Email"] : "";
    $_SESSION["Telefone"] = $user["Telefone"];
    $_SESSION["Celular"] = $user["Celular"];
    $_SESSION["Responsavel"] = $user["Responsavel"];
    $_SESSION["Multigrupos"] = $user["multigrupos"];
    $_SESSION["cdprofissional"] = (int) $user["cdprof"];
    $_SESSION["token"] = jwtCreate(
        [
            'cdCliente' => CLIENTE, 
            'cdUsuario' => (int) $user["CdUsuario"]
        ],
        'cdfec2c2d16772816f70fa345c1ffa3a8c19b651dce9bb23fd88481531c63c99'
	);
	
}

function getUserGroups($db, int $userId)
{    
    $sql = "SELECT tbmultigrupo.cdgrusuario FROM tbmultigrupo WHERE tbmultigrupo.CdUsuario = $userId";
	$result = mysqli_query($db, $sql) or die('Erro ao listar: ' . mysqli_error($db));
	
	$dados = [];
    if (mysqli_num_rows($result)) {
        while ($dado = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $dados[] = $dado['cdgrusuario'];
        }
	}	
	return $dados;
}

function destroyAuth($db, int $userId){
	clearDatabaseSession($db, $userId);
	clearSessionFromServer();
}

function getUserSessions($db, int $userId){
	$sql = "SELECT 
	id, 
	chave, 
	usuario, 
	NmUsuario, 
	horalogin, 
	tempo, 
	`data`
	FROM tbuserconn WHERE usuario='$userId' ORDER BY data DESC";

	$query = mysqli_query($db,$sql);

	$sessions = [];

	if($query->num_rows){
		while($session = mysqli_fetch_assoc($query)){
			$sessions[] = $session;
		}		
	}
	
	$_SESSION['sessionData'] = count($sessions) ? $sessions[0] : null;

	return $sessions;
}

function clearDatabaseSession($db, $userId){
	$query = "DELETE FROM tbuserconn WHERE usuario='$userId'";
	$deletar = mysqli_query($db,$query)or die (mysqli_error($db));
}

function clearSessionFromServer(){
	$_SESSION = [];

	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
	}

    session_destroy();
}

//queries

function get_resource($db , $sql){
	$result = [
		'data' => [],
		'total' => 0
	];
	
	$query = mysqli_query($db, $sql);
	if($query){
		$result['data'] = (object) mysqli_fetch_assoc($query);
		$result['total'] = $query->num_rows;
	}

	return (object) $result;
}

function get_collection($db , $sql){
	$result = [
		'data' => [],
		'total' => 0
	];

	try{
		$query = mysqli_query($db, $sql);
		$result['data'] = [];
		while($row =  mysqli_fetch_assoc($query)){ array_push($result['data'], (object) $row); }
		$result['total'] = $query->num_rows;

	}
	catch(Exception $e){
		echo $e;
	}

	return $result;
}

//manipulation

function set_resource($db , String $sql){	
	$query = mysqli_query($db, $sql);
	return $query ? true : false;
}

function encrypt_string($string){
    return openssl_encrypt($string, CIPHERING, KEY, OPTIONS, INIVECTOR);
}

function decrypt_string($encrypted_string){
    return openssl_decrypt ($encrypted_string, CIPHERING, KEY, OPTIONS, INIVECTOR); 

}

function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function getUserType($userType){
	switch ($userType) {
		case 1:
			return 'syndicate';
			break;
		case 3:
			return 'county';
			break;
		case 5:
			return 'doctor';
			break;
		default: 
			return 'undefined';
		    break;
		
	}
}


function escreve_caminho($i)
{
    include "conecta.php";
    $sql = "SELECT si.cdsubitem, si.nmsubitem, si.arquivo FROM tbsubitem AS si WHERE si.cdsubitem = $i";
    $sql = mysqli_query($GLOBALS['db'], $sql);
    $l = mysqli_fetch_array($sql);
    echo "$l[cdsubitem] - $l[nmsubitem] - $l[arquivo]";
}
function municipioBlock($CdPref)
{
    if (!$CdPref) {
        return [];
    }
    $result = mysqli_query(
        $GLOBALS['db'],
        "SELECT bloquear FROM tbprefeitura WHERE CdPref = $CdPref "
    );
    while ($dados = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        return $dados['bloquear'];
    }
}

function listmenusBlock()
{
    $result = mysqli_query(
        $GLOBALS['db'],
        "SELECT cdsubitem FROM tbsubitembloqueio WHERE status = 1 "
    );
    while ($dados = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $array[] = $dados['cdsubitem'];
    }
    return $array;
}


?>