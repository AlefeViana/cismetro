<?php

header('Content-Type: application/json');

/**
 * Fetch professionals from database
 * use it as rest api endpoint
 * @author Caio Chami
 */

require "../../../vendor/autoload.php";

include "../../conecta.php";

use App\Config\Connection;
use App\Objects\Professional;
use Stringy\Stringy as S;

$connection = Connection::connect($db_attributes);

$searchValue = $_GET['search'] ?? null;
$nome = $_GET['nome'] ?? null;

$filter = $_GET['filter'] ?? null;

$allowedFilters = ['id', 'name'];

if(!$filter){
    http_response_code(422);
    die('you must specify a filter value');
}

if(!in_array($filter, $allowedFilters)){
    http_response_code(422);
    die('filter is invalid');
}

if($filter === "id"){

    $id = intval($searchValue);

    if(!$id){
        http_response_code(422);
        die('id must be present');
    }
    //var_dump($nome); var_dump($id);
    $professional = Professional::find($connection, $id);
    $professional->suppliers();
    //var_dump($professional); die();
    die(json_encode($professional));
    
} else {

    echo $filter;

    $searchValue = addslashes(strip_tags($searchValue));

    $conditions = "WHERE professional.nmprof LIKE '%{$searchValue}%' ";

    $professionals = Professional::use($connection)
    ->where('professional.nmprof', "LIKE",'%caio%')
    ->where('professional.id', 2)
    ->retrieve();//Professional::all($connection, $conditions)->get();

    die(json_encode($professionals));
    
}
