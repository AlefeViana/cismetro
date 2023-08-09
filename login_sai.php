<?php

if (session_status() == PHP_SESSION_NONE) {
	session_start();
}


include('conecta.php');
include('functions.php');

$userId = $_SESSION['CdUsuario'] ?? null;

if(!$userId){
    clearSessionFromServer();
}

if($_GET['sair']){
	destroyAuth($db, (int) $userId);
	header("Location: frm_login.php");
	die();
}

if($_GET['sair']==2){
	//session_destroy();
	unset($_SESSION['CdUsuario']);
	unset($_SESSION['NmUsuario']);
	unset($_SESSION['CdTpUsuario']);
	unset($_SESSION['CdOrigem']);
	unset($_SESSION['cdfornecedor']);
	unset($_SESSION['Login']);
	unset($_SESSION['cdgrusuario']);
	session_destroy();
	header("Location: index.php?msg=1");
}else{
	//session_destroy();
	unset($_SESSION['CdUsuario']);
	unset($_SESSION['NmUsuario']);
	unset($_SESSION['CdTpUsuario']);
	unset($_SESSION['CdOrigem']);
	unset($_SESSION['cdfornecedor']);
	unset($_SESSION['Login']);
	unset($_SESSION['cdgrusuario']);
	header("Location: frm_login.php");
}
?>

