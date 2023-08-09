<?php
/*
Caio Chami
2020-06-29
*/
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header(
    "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"
);

@session_start();

include "../../vendor/autoload.php";

include "../conecta.php";

include "../functions.php";

include "utilities.php";

$user = (object) $_SESSION['userData'];

$msg = new \Plasticbrain\FlashMessages\FlashMessages();

$today_datetime = \Carbon\Carbon::now();

$today = $today_datetime->format('Y-m-d');
$now = $today_datetime->format('H:i:s');

//none
$isDoctor = $_SESSION["CdTpUsuario"] === 5;
// cancelar
$isCounty = $_SESSION["CdTpUsuario"] === 3;
//cancelar ; confirmar ;  falta;
$isSyndicate = $_SESSION["CdTpUsuario"] === 1;

//ações permitidas
$allowedActions = ["cancelar", "confirmar", "falta"];

//validations
if (!$user->id) {
    $msg->error("Not authorized", "../frm_login.php");
}

$request = json_decode(file_get_contents("php://input"));

if (!$request->selected) {
    http_response_code(422);
    echo json_encode("As informações fornecidas são inválidas");
    die();
}

if (!$request->action) {
    http_response_code(404);
    echo json_encode("Operação não identificada");
    die();
}

if (!in_array($request->action, $allowedActions)) {
    http_response_code(403);
    echo json_encode("Not authorized");
    die();
}

if (!is_array($request->selected)) {
    echo json_encode("not ready");
    http_response_code(500);
    die();
} else {
    $items = [];

    foreach ($request->selected as $selected) {
        $selected->action = $request->action;
        $item = get_item($db, $selected);
        array_push($items, $item);
    }

    $execs = [];

    foreach ($items as $item) {
        array_push($execs, prepare_execution($db, $item));
    }

    echo json_encode(["executions" => $execs, "action" => $request->action]);
}

http_response_code(200);
