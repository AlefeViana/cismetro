<?php

session_start();
include("../../funcoes.php");

$texto = '<ul class="list-group">';
$val_alerta = 0;

if(isset($_SESSION['CdTpUsuario']) && $_SESSION['CdTpUsuario'] == 1){

    $sql_nao_conf = "   SELECT * from tbsolcons sc
                        INNER JOIN tbagendacons ac on ac.CdSolCons = sc.CdSolCons
                        WHERE (sc.`Status` = 1 and ac.`Status` = 1 ) and ac.DtAgCons <= CURDATE()";
    $sql_nao_conf = mysqli_query($db, $sql_nao_conf);
    if(mysqli_num_rows($sql_nao_conf) > 0){                   
        $texto .= "<li class='list-group-item d-flex justify-content-between align-items-center'>Existem marcações não confirmadas!<i class='fas fa-caret-square-right irFP' alt='Ir para página!' style='color:orange;'></i></li>";
        $val_alerta +=1;
    }

    $sql_agendas = "    SELECT f.CdForn,f.NmForn, ep.NmEspecProc, count(af.cdagenda_fornecedor) as qts, DATE_FORMAT(af.`data`,'%d/%m/%Y') as data_agenda
                        FROM  tbagenda_fornecedor af
                        INNER JOIN tbfornecedor f on f.CdForn = af.cdfornecedor
                        INNER JOIN tbespecproc ep on ep.CdEspecProc = af.cdespecificacao
                        WHERE af.`data` BETWEEN CURDATE() AND CURDATE() + 2
                        GROUP BY af.cdfornecedor,af.cdespecificacao,af.`data`";
    $sql_agendas = mysqli_query($db, $sql_agendas);
    if(mysqli_num_rows($sql_agendas) > 0){                   
        $texto .= "<li class='list-group-item d-flex justify-content-between align-items-center'>Existem agendas em aberto próxima a data de execução<span class='badge badge-primary badge-pill VerAGD' data-toggle='modal' data-target='.bd-example-modal-sm'>Ver</span><i class='fas fa-caret-square-right irAGD' alt='Ir para página!' style='color:orange;'></i></li>";
        $cdforn = 0;
        $val_alerta += 1;
        $agendas_list = '<ul class="list-group">';
        while($agendas = mysqli_fetch_array($sql_agendas)){
             if($agendas['CdForn'] != $cdforn){
                $agendas_list .= '<li class="list-group-item text-primary">'.$agendas['NmForn'].'</li>';
             }      
             $agendas_list .='<li class="list-group-item" title="'.$agendas['NmEspecProc'].'">'.mb_strimwidth($agendas['NmEspecProc'], 0, 19, '...').' | <b style="color:red">'.$agendas['data_agenda'].'</b></li>';    
        }
        $agendas_list .= '</ul>';
    }

    $sql_cotas = "  SELECT SUM(cm.qts) as cotaT, NmEspecProc, g.dtini, g.dtfim, c.CdForn, c.CdEspecProc 
                    FROM tbcota c 
                    INNER JOIN tbcotam cm on cm.cdcota = c.cdcota
                    INNER JOIN tbgenfat g on g.idgenfat = c.idgenfat
                    INNER JOIN tbespecproc ep on ep.CdEspecProc = c.CdEspecProc
                    WHERE CURDATE() BETWEEN g.dtini and g.dtfim
                    GROUP BY c.cdcota,c.CdEspecProc";
    $sql_cotas = mysqli_query($db, $sql_cotas);
    $valida_cota_maior = $valida_cota_menor =  0;
    while($cota = mysqli_fetch_array($sql_cotas)){
        $busca_agd ="   SELECT SUM(cdagenda_fornecedor) as agendaL 
                        FROM tbagenda_fornecedor 
                        WHERE cdfornecedor = $cota[CdForn]
                        and cdespecificacao = $cota[CdEspecProc]
                        and `data` BETWEEN '$cota[dtini]' and '$cota[dtfim]'";
         //var_dump( $busca_agd);
         $busca_agd = mysqli_query($db, $busca_agd);
         $qts_agendas = mysqli_fetch_array($busca_agd);
         
         if($cota['cotaT'] >= $qts_agendas['agendaL']){
            $dados_cotaMa .= '<li class="list-group-item active">'.$cota['NmEspecProc'].'</li><li class="list-group-item">Cota: '.$cota['cotaT'].' | Agendas: '.$qts_agendas['agendaL'].'</li>';
            $valida_cota_maior = 1;
         }
         if($cota['cotaT'] <= $qts_agendas['agendaL']){
            $dados_cotaMe .= '<li class="list-group-item active">'.$cota['NmEspecProc'].'</li><li class="list-group-item">Cota: '.$cota['cotaT'].' | Agendas: '.$qts_agendas['agendaL'].'</li>';
            $valida_cota_menor = 1;
         }
    }
    if($valida_cota_maior == 1){
        $dados_cotaMa = '<ul class="list-group">'.$dados_cotaMa.'</ul>';
        $texto .= "<li class='list-group-item d-flex justify-content-between align-items-center'>Quantidade de cota excede a quantidade de agendas liberadas<span class='badge badge-primary badge-pill VerCMa' data-toggle='modal' data-target='.bd-example-modal-sm'>Ver</span></li> ";
        $val_alerta +=1;
    }
    if($valida_cota_menor == 1){
        $dados_cotaMe = '<ul class="list-group">'.$dados_cotaMe.'</ul>';
        $texto .= "<li class='list-group-item d-flex justify-content-between align-items-center'>Quantidade de agendas excede a quantidade de cota liberada<span class='badge badge-primary badge-pill VerCMe' data-toggle='modal' data-target='.bd-example-modal-sm'>Ver</span></li> ";
        $val_alerta +=1;
    }
    $sql_nao_impresso = "   SELECT * from tbsolcons sc
                        INNER JOIN tbagendacons ac on ac.CdSolCons = sc.CdSolCons
                        WHERE (sc.`Status` = 1 and ac.`Status` = 1 ) and ac.DtAgCons >= CURDATE() and sc.impresso = 'N'";
    $sql_nao_impresso = mysqli_query($db, $sql_nao_impresso);
    if(mysqli_num_rows($sql_nao_impresso) > 0){                   
        $texto .= "<li class='list-group-item d-flex justify-content-between align-items-center'>Existem guias a serem impressas!<i class='fas fa-caret-square-right irFP-imp' alt='Ir para página!' style='color:orange;'></i></li>";
    $val_alerta +=1;
    }
    

}else if(isset($_SESSION['CdTpUsuario']) && $_SESSION['CdTpUsuario'] == 3){


    $sql_nao_conf = "   SELECT * from tbsolcons sc
                        INNER JOIN tbagendacons ac on ac.CdSolCons = sc.CdSolCons
                        WHERE (sc.`Status` = 1 and ac.`Status` = 1 ) and sc.CdPref = $_SESSION[CdOrigem] and ac.DtAgCons <= CURDATE()";
    $sql_nao_conf = mysqli_query($db, $sql_nao_conf);
    if(mysqli_num_rows($sql_nao_conf) > 0){                   
        $texto .= "<li class='list-group-item d-flex justify-content-between align-items-center'>Existem marcações não confirmadas!<i class='fas fa-caret-square-right irFP' alt='Ir para página!' style='color:orange;'></i></li>";
        $val_alerta += 1;
    }

    $sql_nao_impresso = "   SELECT * from tbsolcons sc
                        INNER JOIN tbagendacons ac on ac.CdSolCons = sc.CdSolCons
                        WHERE (sc.`Status` = 1 and ac.`Status` = 1 ) and sc.CdPref = $_SESSION[CdOrigem] and ac.DtAgCons >= CURDATE() and sc.impresso = 'N'";
    $sql_nao_impresso = mysqli_query($db, $sql_nao_impresso);
    if(mysqli_num_rows($sql_nao_impresso) > 0){                   
        $texto .= "<li class='list-group-item d-flex justify-content-between align-items-center'>Existem guias a serem impressas!<i class='fas fa-caret-square-right irFP-imp' alt='Ir para página!' style='color:orange;'></i></li>";
        $val_alerta += 1;
    }

    if($val_alerta == 0)
        $texto .= "<li class='list-group-item' > <i class='fas fa-bell-slash' style='color:red'></i> Nenhum aviso no momento.  </li>";

}else if(isset($_SESSION['CdTpUsuario']) && $_SESSION['CdTpUsuario'] == 5 && isset($_SESSION['cdfornecedor'])){

    $sql_encaminhados = " SELECT * from tbsolcons sc WHERE (sc.`Status` = 'E' ) and sc.cdfornespera = $_SESSION[cdfornecedor]";
    $sql_encaminhados = mysqli_query($db, $sql_encaminhados);
    if(mysqli_num_rows($sql_encaminhados) > 0){                   
        $texto .= "<li class='list-group-item d-flex justify-content-between align-items-center'>Existem encaminhamento de pacientes aguardando para marcação!<i class='fas fa-caret-square-right irAGDF' alt='Ir para página!' style='color:orange;'></i></li>";
        $val_alerta += 1;
    }

    if($val_alerta == 0)
        $texto .= "<li class='list-group-item' > <i class='fas fa-bell-slash' style='color:red'></i> Nenhum aviso no momento.  </li>";
}else{

}
    //var_dump($texto);
    $texto .= '</ul>';
    echo json_encode(array('cotaMe' => $dados_cotaMe,'cotaMa' => $dados_cotaMa,'agendas_list' => $agendas_list, 'msg' => $texto,'num_alerts' => $val_alerta));
?>