<?php

/**
 * @author Caio Chami
 * creates json web token
 */

ini_set('display_errors', 'On');

header("Content-Type: application/json");
header("Accept: application/json");

require "../../../vendor/autoload.php";

include '../../conecta.php';

use App\Config\Connection;

use App\Objects\Auth;

if($_SERVER['REQUEST_METHOD'] !== "POST"){
    http_response_code(403);
    die( json_encode('method not allowed') );
}

if(!Auth::check()){
    http_response_code(403);
    die( json_encode('unauthorized') );
}
// var_dump($db_attributes);
$connection =  Connection::connect($db_attributes);
// var_dump($connection);
$tokens = Auth::user($connection)->tokens();
// var_dump($tokens);
die(json_encode($tokens));

