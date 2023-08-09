<?php
require('conecta.php');

$db = $GLOBALS['db'];
$cdusuario = $_SESSION['CdUsuario'];
$encerraSessao = "DELETE FROM tbuserconn WHERE usuario = ?";
$stmt = $db->prepare($encerraSessao);
$stmt->bind_param('i', $cdusuario);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    // Declaração preparada foi bem-sucedida
    $resposta = array("success" => true);
} else {
    // Declaração preparada falhou
    $resposta = array("success" => false);
}

echo json_encode($resposta);