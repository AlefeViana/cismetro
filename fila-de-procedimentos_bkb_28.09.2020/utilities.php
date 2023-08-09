<?php
/*
Caio Chami
2020-06-29
*/

/**
 * Get info from database
 * @param object $selected
 * @return object $resource
 */
function get_item($db, object $selected)
{
    $sql = "SELECT 
    sol.CdSolCons as id, 
    sol.idcombo as combo_id,
    ag.DtAgCons as date , 
    ag.protocolopac as protocol_number ,
    ag.Status as agenda_status,
    sol.Status as solicitacao_status,
    mov.status as movimentacao_status
    FROM 
    tbsolcons sol
    LEFT JOIN tbagendacons ag ON sol.CdSolCons = ag.CdSolCons 
    LEFT JOIN tbsldmovimentacao mov ON sol.CdSolCons = mov.CdSolCons
    WHERE sol.CdSolCons = {$selected->id}
    LIMIT 0,1;";

    $resource = get_resource($db, $sql);
    if ($resource->total) {
        $resource->data->hasCombo = false;
        if ($resource->data->combo_id) {
            $resource->data->hasCombo = true;
            $resource->data->combo_items = get_combo_items(
                $db,
                $resource->data
            );
        }

        $resource->data->action = $selected->action;
    }

    return $resource->data;
}

/**
 * Get combo items
 * @param mixed $db
 * @param object $item
 * @return array $combo_items
 */

function get_combo_items($db, $item)
{
    $sql = "SELECT 
    sol.CdSolCons as id, 
    sol.idcombo as combo_id,
    ag.DtAgCons as date , 
    ag.protocolopac as protocol_number ,
    ag.Status as agenda_status,
    sol.Status as solicitacao_status,
    mov.status as movimentacao_status
    FROM 
    tbsolcons sol
    INNER JOIN tbagendacons ag ON sol.CdSolCons = ag.CdSolCons 
    INNER JOIN tbsldmovimentacao mov ON sol.CdSolCons = mov.CdSolCons
    WHERE sol.idcombo = {$item->combo_id}";

    $collection = get_collection($db, $sql);
    $combo_items = [];

    if ($collection->total) {
        foreach ($collection->data as $resource) {
            array_push($combo_items, $resource);
        }
    }

    return $combo_items;
}

/**
 * Check if resource has combo and update its siblings
 * @param object $item
 * @param mixed $db
 * @return object $item
 */

function prepare_execution($db, object $item)
{
    if ($item->hasCombo && count($item->combo_items) ) {
        foreach($item->combo_items as $combo_item){
            execute($db, $combo_item);
        }
    } 
    return execute($db, $item);
}

/**
 * @param object $item
 * @return object $item
 *
 */

function execute($db, $item)
{
    if ($item->action === "cancelar") {
        $agenda_status = 1;
        $solicitacao_status = 2;
        $movimentacao_status = 0;
    } elseif ($item->action === "confirmar") {
        $agenda_status = 2;
        $solicitacao_status = 1;
    } else {
        $agenda_status = 2;
        $solicitacao_status = "F";
        $movimentacao_status = 0;
    }
    
    $sql = "UPDATE tbsolcons SET Status = '{$solicitacao_status}' WHERE CdSolCons = {$item->id}";
    $item->update_solicitacao = mysqli_query($db, $sql);
    $sql = "UPDATE tbagendacons SET Status = {$agenda_status} WHERE CdSolCons = {$item->id}";
    $item->update_agenda = mysqli_query($db, $sql);
    $item->update_movimentacao = false;
    if (isset($movimentacao_status)) {
        $sql = "UPDATE tbsldmovimentacao SET status = {$movimentacao_status} WHERE CdSolCons = {$item->id}";
        $item->update_movimentacao = mysqli_query($db, $sql);
    }

    return $item;
}
