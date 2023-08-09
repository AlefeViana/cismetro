<?php

session_start();


if($_GET['sair']){
	include('conecta.php');
	include('functions.php');
	session_destroy();
	header("Location: frm_login.php");
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

