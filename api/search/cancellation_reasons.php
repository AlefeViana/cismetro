<?php

/**
 * Fetch cancellation reasons from database
 * use it as rest api endpoint
 * @author Caio Chami
 */

header('Content-Type: application/json');

require "../../../vendor/autoload.php";

include "../../conecta.php";

use App\Config\Connection;
use App\Objects\CancellationReason AS CR;

use Stringy\Stringy as S;

$localConn = Connection::connect($db_attributes);

$searchValue = $_GET['search'];

$conditions = "";

if($searchValue){
    $searchValue = htmlspecialchars(strip_tags($searchValue));
    $conditions = "WHERE cancellation_reason.status = 1 AND  cancellation_reason.titulo LIKE '%{$searchValue}%' ";

}

$cancellationReasons = CR::all($localConn, $conditions, "ORDER BY title ASC", "LIMIT 10" )->get();

die(json_encode($cancellationReasons));