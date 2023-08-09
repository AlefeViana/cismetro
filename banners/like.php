<?php

@session_start();

$user_id = $_SESSION['CdUsuario'];

if(!$user_id){
    die('Not Authorized');
}

include_once __DIR__ . "/../api/config/central.php";
include_once __DIR__ . "/../api/objects/banner.php";

$central_db = new Central();
$bannerInstance = new Banner($central_db->getConnection());

$request = json_decode(file_get_contents("php://input"));

if(!$request->banner_id){
    http_response_code(422);
    echo json_encode('Informações inválidas');
    die();
}

$bannerInstance->id = $request->banner_id;
$bannerInstance->user_id = $user_id;
$bannerInstance->contact = $request->like ? true : false;

if($bannerInstance->like()){
    http_response_code(200);
    echo json_encode('Success');
    die();
}
else{
    http_response_code(500);
    echo json_encode('success');
    die();
}

