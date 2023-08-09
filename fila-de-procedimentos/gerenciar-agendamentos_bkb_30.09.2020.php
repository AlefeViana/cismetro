<?php
/**
* @author Caio Chami
* @since 2020-06-29
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

include "../controle/valida_saldo.php";

use App\Config\Connection;
use App\Objects\MedicalScheduling as Schedule;
use App\Objects\SupplierSchedule;
use App\Objects\Movement;



$userId = $_SESSION['CdUsuario'] ?? null;

//validations
if (!$userId) {
   http_response_code(403);
   die(json_encode("Not authorized"));
}

$userType = $_SESSION["CdTpUsuario"] ?? null;

$today_datetime = \Carbon\Carbon::now();

$today = $today_datetime->format('Y-m-d');
$now = $today_datetime->format('H:i:s');

$localConn = Connection::connect($db_attributes);

//none
$isDoctor = $userType === 5;
// cancelar
$isCounty = $userType === 3;
//cancelar ; confirmar ;  falta;
$isSyndicate = $userType === 1;

//ações permitidas
$allowedActions = ["cancelar", "confirmar", "falta", "voltar", "voltar_encaminhado"];



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
    echo json_encode("Action not recognized");
    die();
}

if (!is_array($request->selected)) {

    echo json_encode("not ready");
    http_response_code(500);
    die();

} else {

    if($request->action === "cancelar" && !$request->cancellation_reason_id)
    {
        http_response_code(422);
        die(json_encode('Você deve selecionar o motivo do cancelamento'));
    }

    

    $items = [];
    
    $noPackageIds = array_map(
        function($item){
            return $item->id;
        },
        array_filter($request->selected,function($item){
        return !$item->combo_id;
    }));

    $packageIds = array_unique(array_map(
        function($item){
            return $item->combo_id;
        },
        array_filter($request->selected,function($item){
            return $item->combo_id;
        })
    ));

    $packageSchedules = []; 
    $noPackageSchedules = [];

   
    
    
    //at this point , I created two groups. the ones tha have no package and the ones that have packages and merged them both to create a single group
    
    if(count($packageIds)){
        $packageSchedules = Schedule::all($localConn, "WHERE medical_appointment_request.idcombo IN (".implode(',', $packageIds). ")" )->get();
    }

    if(count($noPackageIds)){
        $noPackageSchedules = Schedule::all($localConn, "WHERE medical_appointment_request.CdSolCons IN (".implode(',', $noPackageIds). ")" )->get();
    }
    
    $schedules = array_merge($noPackageSchedules,$packageSchedules);

    if(!count($schedules)){
        http_response_code(404);
        die(json_encode('No schedules found'));        
    }

    $results = [
        'schedules' => [],
        'action' => $request->action
    ];

    //follow the rules normally
    //sets up the status according to action value
    //logs activity
    //sets up financial movement status based on action
    foreach($schedules as $schedule){
        $request->logged_user_id = $userId;
        $results['schedules'][] = handle($schedule, $request);        
    }

   

    if($request->action === "cancelar" || $request->action === "falta" || $request->action === "voltar"){        

        //handling supplier schedules
        //removing possible duplicates

        $uniqueSupplierSchedulesIds = 
        array_unique(array_map( function($schedule){
            return $schedule['supplier_schedule_id'];
        }, array_filter(
            $results['schedules'], 
            function($result){
                return $result['supplier_schedule_id'] !== null && !$result['has_error'];
            }
        )));

        $supplierSchedules = SupplierSchedule::all($localConn, "WHERE supplier_schedule.cdagenda_fornecedor IN (" . implode(",",$uniqueSupplierSchedulesIds) . ") ");
        
        $results['supplier_schedules']['found'] = $supplierSchedules->count();
        $results['supplier_schedules']['ids'] = [];

        foreach($supplierSchedules->get() as $schedule){

            if($request->action === "voltar"){
                $results['supplier_schedules']['ids'][$schedule->id]['supplier_schedule_set_status_to_M'] = $schedule->setStatus("M");
                
            }
            else{
                $setStatus = $schedule->setStatus("C");
                $results['supplier_schedules']['ids'][$schedule->id]['supplier_schedule_set_status_to_C'] = $setStatus;
                if($request->action !== "falta"){
                    $results['supplier_schedules']['ids'][$schedule->id]['supplier_schedule_replicate_with_status_A'] = $setStatus ? $schedule->copyWithStatus() : false;
                }
            }
        }
    }

    die(json_encode($results));
}

http_response_code(200);
