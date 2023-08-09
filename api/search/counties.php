<?php

/**
 * Fetch counties from database
 * 
 * @author Caio Chami
 */
@session_start();
header('Content-Type: application/json');

require "../../../vendor/autoload.php";

include "../../conecta.php";

use App\Config\Connection;
use App\Objects\County AS County;

$connection = Connection::connect($db_attributes);

$searchValue = $_GET['search'] ?? null;
$filter = $_GET['filter'] ?? null;

$allowedFilters = ['id', 'nmcidade', 'cdestado', 'all'];

if(!in_array($filter, $allowedFilters)){
    http_response_code(422);
    die('invalid params');
}

$searchValue = htmlspecialchars(strip_tags($searchValue));

if($filter === "id" && $searchValue ){
    $county = County::find($connection, (int)$searchValue);
    die(json_encode($county));
}

$counties = County::use($connection)->where('status', 1);

if($searchValue && $filter){
   
    $counties = $counties->where( $filter ,'LIKE', $searchValue);
    if($_SESSION['CdTpUsuario'] === 3){
        $counties = $counties->where('cdpref', $_SESSION['CdOrigem']);
    }
    
    die(json_encode($counties->retrieve()));
}

$counties = $counties->orderBy('nmcidade')->limit(200);

die(json_encode($counties->retrieve()));



