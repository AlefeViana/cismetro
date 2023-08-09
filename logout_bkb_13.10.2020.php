<?php

@session_start();

include "conecta.php";

include "functions.php";

$user_id = $_SESSION['CdUsuario'];

clear_session_from_database($db, $user_id);
clear_session_from_server();


header("Location: frm_login.php");


?>

