<?php
    include "conecta.php";
    if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	/*
	if(!defined('DIRECT_ACCESS')) {
		die('Direct access not permitted');
	}
	*/
	if (!isset($_SESSION["CdUsuario"],$_SESSION["NmUsuario"],$_SESSION["CdTpUsuario"]))
	{

		$msg->error("Você não está autenticado",  "./frm_login.php"  );
		die();	
		
	}

	if (!isset($_SESSION["exp"])) {
		
		$_SESSION['exp'] = time() + $_SESSION['exp_length'];

	} else if ($_SESSION['exp'] < time()) {
		
		$msg->error("Usuário ficou inativo. Sessão expirada.",  "./frm_login.php"  );
		die();	

	} else {

		$_SESSION['exp'] = time() + $_SESSION['exp_length'];

	}

	$chave_session = "SELECT chave FROM tbuserconn WHERE usuario = ".$_SESSION["CdUsuario"]." LIMIT 1";
	$qry_chave = mysqli_query($db,$chave_session);
	$chave = mysqli_fetch_assoc($qry_chave);
	// echo 'chave sql: '.$chave['chave'];
	// var_dump($_SESSION['chave']);
	if($chave['chave'] != $_SESSION["sessionData"]['chave']){

		$msg->error("Atenção! sua conta foi acessada em outra maquina.",  "./frm_login.php?dc=d");
		die();
	}
	
?>