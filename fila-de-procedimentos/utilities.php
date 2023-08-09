<?php
/**
 *  @author Caio Chami 
 *  @since 2020-06-29
 */

use App\Objects\MedicalScheduling;
use App\Objects\MedicalSchedulingAuditorship AS Auditorship;

/**
 * @param App\Objects\MedicalScheduling $schedule
 * @param string $action
 * @return array
 * 
 */


function handle(MedicalScheduling $schedule, object $request){
    
    $action = $request->action;

    $LOGGED_USER_ID = $request->logged_user_id;

    $response = [
        'action' => $action,
        'id' => $schedule->id,
        'package_id' => $schedule->package_id,
        'supplier_schedule_id' => $schedule->supplier_schedule_id,
        'status' => $schedule->status(),
        'has_error' => false,
        'error_message' => "",
        'movement_debt' => false,
        'movement_reversal' => false,
        'movement_id' => null,       
        'contract_movement_id' => null,       
        'contract_movement_reversal' => false,
        'contract_movement_debt' => false,
        'schedule_update' => false
    ];   

    //get movements    
    $contractMovement = $schedule->contract_movements()->first();
    $movement = $schedule->movements()->first();

    $response['movement_id'] = $movement->id ?? null;   
    $response['contract_movement_id'] = $contractMovement->id ?? null;        

    if($action === "voltar"){
        
        $response['movement_debt'] = set_voltar_saldo($schedule->id);

        if(!$response['movement_debt']){
            $response['has_error'] = true;
            $response['error_message'] = "Não foi possível voltar este atendimento";
            return $response;
        }

        /* if($movement){
            $response['movement_debt'] =  $movement->setStatus(true);  
        } */

        if($contractMovement){
            $response['contract_movement_debt'] =  $contractMovement->setStatus(true);
        }    
        
        Auditorship::audit($schedule, 'voltar');
        $scheduleUpdate = $schedule->schedule();
    }   

    if ($action === "cancelar") {
        $schedule_status = 1;
        $request_schedule_status = 2;
        
        $actionDescription = "Cancelamento";
        $actionDate = ", dtcanc = CURRENT_DATE()";
        $actionTime = ", hrcanc = CURRENT_TIME()";
        $scheduleUpdate = $schedule->cancel($request->cancellation_reason_id);

        
    } elseif ($action === "confirmar") {
        $schedule_status = 2;
        $request_schedule_status = 1;
        $actionDescription = "Confirmação";
        $actionDate = ", dtrel = CURRENT_DATE()";
        $actionTime = ", hrrel = CURRENT_TIME()";
        $scheduleUpdate = $schedule->confirm();

    }     
    elseif ($action === "voltar_encaminhado"){
         $schedule_status = null;
        $request_schedule_status = null;
        $actionDescription = "Voltar encaminhado";
        $actionDate = ", dtrel = CURRENT_DATE()";
        $actionTime = ", hrrel = CURRENT_TIME()";
        $scheduleUpdate = $schedule->rollback_forwarded_request();
    }
    else {
        $schedule_status = 2;
        $request_schedule_status = "F";
        
        $actionDescription = "Falta";
        $actionDate = ", dtrel = NULL, dtcanc = NULL";
        $actionTime = ", hrrel = NULL, hrcanc = NULL";
        $scheduleUpdate = $schedule->unattend();
    }

    //handle movements
   
    if($action === "cancelar" || $action === "falta"){   
        if($movement){               
            $response['movement_reversal'] =  $movement->setStatus(false);  
        }

        if($contractMovement){               
            $response['contract_movement_reversal'] =  $contractMovement->setStatus(false);
        }     
    }
 
    $response['schedule_update'] = $scheduleUpdate;  

    return $response;

}

/**
 * Generates sql
 * @param string $conditioning
 * @return string
 * @deprecated this function is not being used anymore
 */

function getSql($conditioning = ""){
    return "SELECT 
    sol.CdSolCons as id, 
    sol.idcombo as package_id,
    sol.CdPref as city_id,
    sol.CdEspecProc AS especification_id,
    especification.CdProcedimento AS procedure_id,
    ag.CdForn as supplier_id,
    ag.CdSolCons as scheduling_id,
    ag.cdagenda_fornecedor AS supplier_schedule_id,
    ag.DtAgCons as date , 
    ag.protocolopac as protocol_number ,
    ag.Status as schedule_status,
    sol.Status as request_schedule_status,
    mov.status as movement_status
    FROM 
    tbsolcons sol
    LEFT JOIN tbagendacons ag ON sol.CdSolCons = ag.CdSolCons 
    LEFT JOIN tbespecproc especification ON especification.CdEspecProc = sol.CdEspecProc 
    LEFT JOIN tbsldmovimentacao mov ON sol.CdSolCons = mov.CdSolCons
    {$conditioning}";
}

/**
 * Get info from database
 * @param object $selected
 * @return object $resource
 * @deprecated this function is no being used anymore...
 */
function getItem($db, object $selected)
{
    $sql = getSql(" WHERE sol.CdSolCons = {$selected->id}
    LIMIT 0,1;");   

    $resource = get_resource($db, $sql);

    extract( get_object_vars ($resource));
 
    if ($total) {        
        $data->actionable = validateStatus($data);
        $data->has_package = false;
        if($data->supplier_schedule_id){
            $data->supplier_schedule = getSupplierSchedule(
                $db,
                "WHERE cdagenda_fornecedor = {$data->supplier_schedule_id}"
            );
        }
        if ($data->package_id) {
            $data->has_package = true;
            $data->package_items = getPackageItems(
                $db,
                $data
            );
        }
    }

    return $resource->data;
}

/**
 * Get package items "combo"
 * @param mixed $db
 * @param object $schedule
 * @return array $package_items
 * @deprecated this function is no being used anymore...
 */

function getPackageItems($db, $schedule)
{
    $sql = getSql(" WHERE sol.idcombo = {$schedule->package_id} AND sol.CdSolCons != {$schedule->id}");
   
    $package_items = [];

    $collection = get_collection($db, $sql);

    if ($collection['total']) {
        foreach ($collection['data'] as $resource) {
            if($resource->supplier_schedule_id){
                $resource->actionable = validateStatus($resource);
                $resource->supplier_schedule = getSupplierSchedule(
                    $db,
                    "WHERE cdagenda_fornecedor = {$resource->supplier_schedule_id}"
                );
            }
            array_push($package_items, $resource);
        }
    }
    return $package_items;
}

/**
 * Check if resource has package and update its siblings
 * @param object $item
 * @param mixed $db
 * @return object $item
 * @deprecated this function is no being used anymore...
 */

function prepare_execution($db, object $item)
{
    if ($item->has_package && count($item->package_items) ) {
        foreach($item->package_items as $package_item){
            if($package_item->actionable) execute($db, $package_item);
        }
    } 

    return $item->actionable ?  execute($db, $item) : $item;
}

/**
 * @param object $item
 * @return object $item
 * @deprecated this function is no being used anymore...
 * 
 *
 */

function execute($db, $item)
{
    $LOGGED_USER_ID = $_SESSION['CdUsuario'];

    $actionDescription = "";
    $actionDate = "";
    $actionTime = "";

    $reversal = false;
    
    //caio - 2020-08-27 - agendamento do fornecedor
    $item->action = ACTION;

    if (ACTION === "cancelar" ) {
        $schedule_status = 1;
        $request_schedule_status = 2;
        $movement_status = 0;
        $actionDescription = "Cancelamento";
        $actionDate = ", dtcanc = CURRENT_DATE()";
        $actionTime = ", hrcanc = CURRENT_TIME()";
        

        
    } elseif (ACTION === "confirmar") {
        $schedule_status = 2;
        $request_schedule_status = 1;
        $actionDescription = "Confirma��o";
        $actionDate = ", dtrel = CURRENT_DATE()";
        $actionTime = ", hrrel = CURRENT_TIME()";

    } else {
        $schedule_status = 2;
        $request_schedule_status = "F";
        $movement_status = 0;
        $actionDescription = "Falta";
        $actionDate = ", dtrel = NULL, dtcanc = NULL";
        $actionTime = ", hrrel = NULL, hrcanc = NULL";
    }

    $logSql = "INSERT INTO tblogag
    (valor_old, valor_new, tipo, dtalt, cdag) 
    VALUES ('{$item->schedule_status}', '{$schedule_status}', 'Altera��o Status : {$actionDescription}', NOW(), {$item->id})";
    mysqli_query($db, $logSql);     

    $userLogSql = "INSERT INTO tbusralt
    (cdusr, cdag, dtalt, cdpacienteant, cdpacientenov)
    VALUES($LOGGED_USER_ID, {$item->id}, NOW(), NULL, NULL);";
    mysqli_query($db, $userLogSql);
    
    
    $sql = "UPDATE tbsolcons SET Status = '{$request_schedule_status}' {$actionDate} {$actionTime} WHERE CdSolCons = {$item->id}";
    $item->request_schedule_update = mysqli_query($db, $sql);
    $sql = "UPDATE tbagendacons SET Status = {$schedule_status} WHERE CdSolCons = {$item->id}";
    $item->schedule_update = mysqli_query($db, $sql);
    
    /* if($item->request_schedule_update && ACTION === "cancelar" && $item->supplier_schedule_id){
             
    } */

    if (isset($movement_status)) {
        $sql = "UPDATE tbsldmovimentacao SET status = {$movement_status} WHERE CdSolCons = {$item->id}";
        $item->update_movimentacao = mysqli_query($db, $sql);
    }

    return $item;
}

/**
 * check if schedule status is "falta"
 * @param object $schedule
 * @return bool 
 * @deprecated this function is no being used anymore...
 */
function validateStatus($schedule){
    return $schedule->request_schedule_status !== "F";
}

/**
 * Get supplier schedule info "agenda_fornecedor"
 * @param mixed $db
 * @param string $conditioning
 * @return array $collection
 * @deprecated this function is no being used anymore...
 */

function getSupplierSchedule($db, $conditioning){
    $sql = "SELECT 
    cdagenda_fornecedor AS id,
    cdfornecedor AS supplier_id,
    cdprof AS professional_id,
    cdprocedimento AS procedure_id,
    obs AS note,
    cdespecificacao AS especification_id,
    cdpref AS city_id,
    `data` AS date,
    hora AS time,
    status,
    usrinc AS user_id,
    dtinc AS created_at
    FROM tbagenda_fornecedor tf
    {$conditioning};";

    $collection = get_collection($db, $sql);

    return $collection['data'];

}

/**
 * @deprecated this function is no being used anymore...
 */

function handleSupplierSchedule($db,$id){
    $sql = 
    "UPDATE tbagenda_fornecedor SET status = 'C' 
    WHERE cdagenda_fornecedor = {$id}";

    $updated = set_resource($db, $sql);
    $stored = storeSupplierSchedule($db, $id);   
    return [ $id => ['updated'=> $updated, 'stored' => $stored]];
}


/**
 * Store supplier schedule "agenda_fornecedor"
 * @param mixed $db
 * @param object $schedule
 * @return object $supplier_schedule
 * @deprecated this function is no being used anymore...
 */

function storeSupplierSchedule($db, $id){

    $sql = 
    "INSERT INTO tbagenda_fornecedor 
    (cdfornecedor , cdprof , cdprocedimento , obs, cdespecificacao , cdpref, `data` ,hora , status , usrinc ,dtinc )
    (SELECT 
        cdfornecedor , cdprof , cdprocedimento , obs, cdespecificacao , cdpref, `data` ,hora , 'A' , usrinc ,dtinc
    FROM 
        tbagenda_fornecedor 
    WHERE 
    cdagenda_fornecedor = {$id} );";

    return set_resource($db, $sql);
    
}

