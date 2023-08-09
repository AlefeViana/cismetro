<?php

/**
 * @author Caio Chami
 * creates json web token
 */

ini_set('display_errors', 'On');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization ,X-Requested-With");

header("Content-Type: application/json");
header("Accept: application/json");

require "../../../vendor/autoload.php";

include '../../conecta.php';

use App\Config\Connection;

use App\Objects\Auth;

if($_SERVER['REQUEST_METHOD'] !== "POST"){
    http_response_code(403);
    die(json_encode('method not allowed'));
}

if(!Auth::check()){
    http_response_code(403);
    die( json_encode('unauthorized') );
}

$connection =  Connection::connect($db_attributes);

$user = Auth::user($connection);

if(!$user->canCreate()){
    http_response_code(403);
    die( json_encode('Max number of active tokens exceeded') );
}

$plainTextToken = $user->createToken();

die(json_encode($plainTextToken));

