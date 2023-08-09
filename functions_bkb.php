<?php 

use \Carbon\Carbon;

function login_session_timeout( $MAX_LIFE_TIME){
	include "conecta.php";
	$msg = new \Plasticbrain\FlashMessages\FlashMessages();

	$now = Carbon::now();
	$current_time = $now->timestamp;
	$session_id = session_id();
	$user_id = $_SESSION['CdUsuario'];
	$session_info = get_session_info($db, $user_id);	
	$session_total = $session_info['total_rows'];	
	$logged_at = $session_info['horalogin'];
	$timeout = $session_info['tempo'];
	
	if($session_total){
		if($current_time > $timeout){
			$_SESSION = [];			
			
			clear_session_from_database($db, $user_id);	

			$msg->info('Caro usuário, você ficou um longo período sem utilizar o sistema e sua sessão foi encerrada por segurança. Efetue novo logon!',
			"frm_login.php");
			die();
		}else{
			if($session_info['chave'] == $session_id){
				
				$timeout = $current_time + $MAX_LIFE_TIME;
				$update = mysqli_query($db,"UPDATE tbuserconn SET tempo='$timeout',horalogin='$current_time' WHERE usuario='$user_id'")or die (mysqli_error());
			}else{
				$_SESSION = [];		
				$msg->info('Caro usuário, seu se encontra em uso em outro local. É permitido somente uma sessão ativa por usuário!',
				"frm_login.php");
				die();
			}	
		}
	}else{
	
		$query = mysqli_query($db,"SELECT tb.NmUsuario  FROM tbusuario tb WHERE CdUsuario='$user_id'");
		
		$user_data = mysqli_fetch_assoc($query);
		$username		= $user_data['NmUsuario'];

		$timeout = $current_time + ($MAX_LIFE_TIME);

	
		
		$query = mysqli_query($db,
		"INSERT INTO tbuserconn (chave,usuario,tempo,horalogin,data,NmUsuario) 
		VALUES ('$session_id','$user_id','$timeout','$current_time', NOW(),'$username')")or die('Erro ao cadastrar:'.mysqli_error());
	}
}

function get_session_info($db, $user_id){
	$sql = "SELECT 
	id, 
	chave, 
	usuario, 
	NmUsuario, 
	horalogin, 
	tempo, 
	`data`
	FROM tbuserconn WHERE usuario='$user_id'";

	$query = mysqli_query($db,$sql);

	$data = mysqli_fetch_assoc($query);

	
    $data['last_sign_in'] = Carbon::now()->format("d/m/Y H:i:s");
	$data['total_rows'] = $query->num_rows;	

	return $data;
}

function clear_session_from_database($db, $user_id){
	$query = "DELETE FROM tbuserconn WHERE usuario='$user_id'";
	$deletar = mysqli_query($db,$query)or die (mysqli_error());
}

function clear_session_from_server(){
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