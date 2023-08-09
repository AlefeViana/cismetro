<?php

header('Content-Type: application/json');

/**
 * Fetch patients from database
 * use it as rest api endpoint
 * @author Caio Chami
 */

require "../../../vendor/autoload.php";

include "../../conecta.php";

use App\Config\Connection;
use App\Objects\Patient;
use Stringy\Stringy as S;

$searchValue = $_GET['search'];

$conditions = "";

if($searchValue){
    $searchValue = htmlspecialchars(strip_tags($searchValue));
    $conditions = "WHERE t.NmPaciente LIKE '%{$searchValue}%' ";

}

$conditions .= "ORDER BY t.NmPaciente ASC LIMIT 10";

$localConn = Connection::connect($db_attributes);
$patients = Patient::all($localConn, $conditions)->get();

$patients = array_map(function($patient){
    return ['id' => $patient->id, 'name' => (string)S::create($patient->name)->titleize(['de','da','do'])];
},$patients);


die(json_encode($patients));