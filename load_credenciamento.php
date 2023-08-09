<?php

ini_set('display_errors', 1);
require_once './conecta.php';

$qry = "SELECT l.cdlicitacao,l.descricao,l.lictanexo from tblctlicitacao l WHERE l.`status` = 1";

$stmt = $db->prepare($qry);

$stmt->execute();

$result = $stmt->get_result();

$dados = array();

while ($row = $result->fetch_assoc()) {
  $dados[] = array(
    'cdlicitacao' => $row['cdlicitacao'], 
    'descricao' => $row['descricao'], 
    'lictanexo' => $row['lictanexo'] 
    );
}
echo json_encode(['dados' => $dados]);
$stmt->close();