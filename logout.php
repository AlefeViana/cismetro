<?php

@session_start();

include "conecta.php";

include "functions.php";

$userId = intval( $_SESSION['CdUsuario'] ?? null );

destroyAuth($db, $userId);

header("Location: frm_login.php");
?>

