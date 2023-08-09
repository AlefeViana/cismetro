<?php
require('conecta.php');

use \Carbon\Carbon;

$tempo_atual = Carbon::now()->timestamp;
$timeout = $tempo_atual + $_SESSION['exp_length'];

$db = $GLOBALS['db'];
$cdusuario = $_SESSION['CdUsuario'];
$encerraSessao = "UPDATE tbuserconn SET horalogin = ?, tempo = ?, data = NOW() WHERE usuario = ?";
$stmt = $db->prepare($encerraSessao);
$stmt->bind_param('iii', $tempo_atual, $timeout, $cdusuario);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    // Declaração preparada foi bem-sucedida
    $resposta = array("success" => true);
} else {
    // Declaração preparada falhou
    $resposta = array("success" => false);
}

echo json_encode($resposta);
