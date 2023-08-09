<?php

/**
 * Fetch cids from database
 * use it as rest api endpoint
 * @author Caio Chami
 */

header('Content-Type: application/json');

require "../../../vendor/autoload.php";

include "../../conecta.php";

use App\Config\Connection;
use App\Objects\Cid;

use Stringy\Stringy as S;

$localConn = Connection::connect($db_attributes);

$searchValue = $_GET['search'];

$conditions = "";

if($searchValue){
    $searchValue = htmlspecialchars(strip_tags($searchValue));
    $conditions = "WHERE nmcid LIKE '%{$searchValue}%'";

}

$cids = Cid::all($localConn, $conditions, "ORDER BY nmcid ASC", "LIMIT 10" )->get();

$cids = array_map(function($cid){
    return ['id' => $cid->id, 'name' => (string)S::create($cid->name)->titleize(['de','da','do'])];
},$cids);


die(json_encode($cids));