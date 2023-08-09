<?php 
error_reporting(-1);
include "../vendor/autoload.php";

include "conecta.php";

use App\Config\Connection;
use App\Objects\MedicalScheduling as Schedule;
$localConn = Connection::connect($db_attributes);
$noPackageSchedules = Schedule::all($localConn, "WHERE medical_appointment_request.CdSolCons IN (3714)" )->get();

var_dump($noPackageSchedules);

