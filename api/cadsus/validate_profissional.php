<?php

/**
 * Checks if csus number exists and search it on cadsus service
 * 
 * @author Caio Chami
 * @since 2020-09-17
 */

@session_start();

require "../../../vendor/autoload.php";
include "../../conecta.php";

use App\Config\Connection;
use App\Objects\Patient;

use Stringy\Stringy as S;


if(!array_key_exists('CdUsuario',$_SESSION)){
    http_response_code(403);
    die('Unauthorized');
}

if(!isset($_POST)){
    http_response_code(422);
    die('Data is required');
}

if(!array_key_exists('value',$_POST)){
    http_response_code(422);
    die("csus is required");
}

if(!array_key_exists('filter',$_POST)){
    http_response_code(422);
    die("filter is required");
}


if(!in_array($_POST['filter'], ['csus','cpf'])){
    http_response_code(422);
    die("filter not found");
}

if(!array_key_exists('token',$_POST)){
    http_response_code(422);
    die('token is required');
}

if((int)$_POST['value'] === 0){
    http_response_code(422);
    die('Nenhuma informação encontrada');
}



$_POST['value'] = htmlspecialchars(strip_tags($_POST['value'])); 
$filter = htmlspecialchars(strip_tags($_POST['filter'])); 

$_POST['value'] = preg_replace('/[^0-9]/', '', $_POST['value']);

// $exists = Patient::all(Connection::connect($db_attributes), 'WHERE t.' . $filter . ' = "' . $_POST['value'] . '"');

// if($exists->count()){
//     $patient = $exists->first();
//     http_response_code(422);
//     die('Paciente já existe - ' . capitalize($patient->name) . ' - ' . capitalize($patient->city) );
// }


$ch = curl_init( $externalAppsUrl . '/services/cadsus/index.php');
$encoded = '';

foreach($_POST as $name => $value) {
  $encoded .= urlencode($name).'='.urlencode($value).'&';
}

$encoded = substr($encoded, 0, strlen($encoded)-1);
curl_setopt($ch, CURLOPT_POSTFIELDS,  $encoded);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-type: application/x-www-form-urlencoded"
]);
$response =  curl_exec($ch);
curl_close($ch);

die($response);