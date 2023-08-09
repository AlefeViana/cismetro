<?php

/**
 * @author Caio Chami
 * @since 2020-09-21
 */

header('Content-Type: application/json');

require "../../../vendor/autoload.php";
require "../../conecta.php";

@session_start(); 

use App\Objects\CancellationReason as CR;
use App\Config\Connection;

$msg = new \Plasticbrain\FlashMessages\FlashMessages();

$userId = $_SESSION['CdUsuario'] ?? null;

if(!$userId){
  http_response_code('403');
  die(json_encode('unauthorized'));
}

$request = json_decode(file_get_contents('php://input'));

$countyId = intval($request->county_id ?? null);

$title = $request->title ?? "";
$text = $request->text ?? "";

if(empty($title)){
    http_response_code(422);
    die('Título é obrigatório');
}

if(empty($text)){
    http_response_code(422);
    die('Texto é obrigatório');   
}


if(!$countyId){
    http_response_code(422);
    die('O código da prefeitura é obrigatório');  
}

$connection = Connection::connect($db_attributes);

$cancellationReason = CR::create(
    $connection,
    [
        'userInc' => (int) $userId,
        'titulo' => (string) $title,
        'texto' => (string) $text,
        'userAlt' => (int) $userId,
        'CdPref' => $countyId
    ]
);

if($cancellationReason) {
    die(json_encode($cancellationReason));    
}
else {
    http_response_code(500);
    die('Não foi possível cadastrar o registro');
}



