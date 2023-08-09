<?php

function displayItem($item)
{
    $data = array(
            
        "id" => $item->id,
        "agendamento_data" => $item->agendamento_data,
        "protocolo_paciente" => $item->protocolo_paciente,
        "aprovacao_status" => $item->aprovacao_status,
        "aprovacao_verificado" => $aprovacao_verificado,
        "fornecedor_nome" => $item->fornecedor_nome,
        "paciente_nome" => $item->paciente_nome,
        "procedimento" => $item->procedimento
  
    );

    return $data;
}

?>