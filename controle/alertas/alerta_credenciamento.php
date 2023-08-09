<?php

session_start();
include("../../funcoes.php");

$texto = '<ul class="list-group">';

$busca_alertas = "  SELECT fl.cdforn, fl.andamento, a.aprovacao, f.NmForn, l.descricao,	l.dtfim
                    FROM tblctfornecedor_licitacao fl
                    INNER JOIN tbfornecedor f ON f.CdForn = fl.cdforn
                    INNER JOIN tblctlicitacao l on l.cdlicitacao = fl.cdlicitacao and l.`status` = 1 and l.dtfim >= CURDATE()
                    INNER JOIN tbcredfornecedor cre on cre.CdForn = f.CdForn and cre.Status =1
                    LEFT JOIN tblctAnexos a ON fl.CdFornlct = a.CdFornlct AND a.`status` = 1
                    WHERE fl.`status` = 1";


if (isset($_SESSION['CdTpUsuario']) && $_SESSION['CdTpUsuario'] == 1) {
    // Novos Credenciados
    $val_alerta = 0;
    $novos_cred2 =  "SELECT cre.cdforn,cre.Nome FROM tbcredfornecedor cre 
    WHERE cre.CdForn not in ( SELECT fl.cdforn FROM tblctfornecedor_licitacao fl)
    and YEAR(DtInc) = YEAR(CURDATE()) and cre.`Status` = 1
    and !(cre.Nome = '' OR isNULL(cre.Nome))
    ORDER BY cre.Nome";
    //if($_SESSION['CdUsuario'] == 2)
    //var_dump($novos_cred); die();
    $novos_credenciados = mysqli_query($db, $novos_cred2);
    $forn_list = "";
    if (mysqli_num_rows($novos_credenciados) > 0) {
        $texto .= "<li class='list-group-item d-flex justify-content-between align-items-center'>Novos credenciados foram identificados!<div><span class='badge badge-primary badge-pill VerF' data-toggle='modal' data-target='.bd-example-modal-sm'>Ver</span> <i class='fas fa-caret-square-right irCred' data-forn='novo_fc'  alt='Ir para página!' style='color:orange;'></i> </div></li> ";
        $forn_list = '<ul class="list-group"><li class="list-group-item active">Novos pedidos de credenciamento abertos! </li>';
        while ($nmforn = mysqli_fetch_array($novos_credenciados)) {
            $forn_list .= '<li class="list-group-item">' . $nmforn['Nome'] . '<i class="fas fa-caret-square-right irCred" data-forn="novo_fc" data-value="' . $nmforn['cdforn'] . '" alt="Ir para página!" style="color:orange;"></i> </li>';
        }
        $val_alerta += 1;
        $forn_list .= '</ul>';
        //var_dump($texto); die();
    }
// REVISAR ALERTA
    $aprova_cred =  $busca_alertas . " AND ( fl.andamento = 0 AND (a.Cdanx > 0 AND a.Cdanx is not null)) GROUP BY fl.CdForn ORDER BY f.NmForn ";
    //  if($_SESSION['CdUsuario'] == 2)
    //  var_dump($aprova_cred); die();
    $aprova_credenciados = mysqli_query($db, $aprova_cred);
    $aprova_forn = "";
    if (mysqli_num_rows($aprova_credenciados) > 0) {
        $texto .= "<li class='list-group-item d-flex justify-content-between align-items-center'>Credenciados para aprovação!<div><span class='badge badge-primary badge-pill VerAprov' data-toggle='modal' data-target='.bd-example-modal-sm'>Ver</span> <i class='fas fa-caret-square-right irCred' data-forn='aprova_fc' alt='Ir para página!' style='color:orange;'></i> </div></li> ";
        $aprova_forn = '<ul class="list-group"><li class="list-group-item active">Pedidos de credenciamento para aprovação! </li>';
        while ($nmforn = mysqli_fetch_array($aprova_credenciados)) {
            $aprova_forn .= '<li class="list-group-item">' . $nmforn['NmForn'] . '<i class="fas fa-caret-square-right irCred" data-forn="' . $nmforn['cdforn'] . '" alt="Ir para página!" style="color:orange;"></i> </li>';
        }
        $val_alerta += 1;
        $aprova_forn .= '</ul>';
        //var_dump($texto); die();
    }

    $atualiza_cred =  $busca_alertas . " AND ( fl.andamento = 3) GROUP BY fl.CdForn ORDER BY f.NmForn ";
    //var_dump($novos_cred); die();
    $atualiza_credenciados = mysqli_query($db, $atualiza_cred);
    $atualiza_forn = "";
    if (mysqli_num_rows($atualiza_credenciados) > 0) {
        $texto .= "<li class='list-group-item d-flex justify-content-between align-items-center'>Credenciamentos para atualização!<div><span class='badge badge-primary badge-pill VerATT' data-toggle='modal' data-target='.bd-example-modal-sm'>Ver</span> <i class='fas fa-caret-square-right irCred' data-forn='atualiza_fc' alt='Ir para página!' style='color:orange;'></i></div> </li> ";
        $atualiza_forn = '<ul class="list-group"><li class="list-group-item active">Pedidos de atualização de credenciamento! </li>';
        while ($nmforn = mysqli_fetch_array($atualiza_credenciados)) {
            $atualiza_forn .= '<li class="list-group-item">' . $nmforn['NmForn'] . '<i class="fas fa-caret-square-right irCred" data-forn="' . $nmforn['cdforn'] . '" alt="Ir para página!" style="color:orange;"></i> </li>';
        }
        $val_alerta += 1;
        $atualiza_forn .= '</ul>';
        //var_dump($texto); die();
    }

    $atualiza_cred = " SELECT alert_prof_procedimentos, f.Nome, f.CdForn from tblctfornecedor_licitacao fl INNER JOIN tblctlicitacao l on l.cdlicitacao = fl.cdlicitacao and l.`status` = 1 and l.dtfim >= CURDATE() INNER JOIN tbcredfornecedor f on f.CdForn = fl.cdforn  WHERE fl.alert_prof_procedimentos = 1 AND fl.andamento = 3";
    $atualiza_credenciados = mysqli_query($db, $atualiza_cred);
    $atualiza_forn = "";
    if (mysqli_num_rows($atualiza_credenciados) > 0) {
        $texto .= "<li class='list-group-item d-flex justify-content-between align-items-center'>Credenciamento : Atualização de procedimentos!<div><span class='badge badge-primary badge-pill VerATT' data-toggle='modal' data-target='.bd-example-modal-sm'>Ver</span> <i class='fas fa-caret-square-right irCred' data-forn='atualiza_fc' alt='Ir para página!' style='color:orange;'></i></div> </li> ";
        $atualiza_forn = '<ul class="list-group"><li class="list-group-item active">Atualização de procedimentos no credenciamento! </li>';
        while ($nmforn = mysqli_fetch_array($atualiza_credenciados)) {
            $atualiza_forn .= '<li class="list-group-item">' . $nmforn['Nome'] . '<i class="fas fa-caret-square-right irCred" data-forn="' . $nmforn['CdForn'] . '" alt="Ir para página!" style="color:orange;"></i> </li>';
        }
        $val_alerta += 1;
        $atualiza_forn .= '</ul>';
        //var_dump($texto); die();

    }


    $doc_pendente .=  $busca_alertas . " AND (a.aprovacao = 0 OR a.aprovacao is NULL) AND a.status = 1 GROUP BY fl.CdFornlct LIMIT 0,90";
    $doc = mysqli_query($db, $doc_pendente);
    if (mysqli_num_rows($doc) > 0) {
        $texto .= "<li class='list-group-item d-flex justify-content-between align-items-center'>Documentos dos prestadores pendentes para análise!<i class='fas fa-caret-square-right irCred' data-forn='doc' alt='Ir para página!' style='color:orange;'></i></li>";
        $texto .= "<li class='list-group-item d-flex justify-content-between align-items-center'>Documentos dos profissionais pendentes para análise!<i class='fas fa-caret-square-right irCred' data-forn='doc-prof' alt='Ir para página!' style='color:orange;'></i></li>";
        $val_alerta += 2;
    }

    $busca_doc_vencidos = " SELECT  fl.CdFornlct, t.titulo,DATE_FORMAT(a.dataDoc,'%d/%m/%Y') as dtdoc , f.NmForn, l.descricao,f.CdForn
                            FROM  tblctfornecedor_licitacao fl
                            INNER JOIN tbfornecedor f ON f.CdForn = fl.cdforn
                            INNER JOIN tblctAnexos a ON fl.CdFornlct = a.CdFornlct
                            INNER JOIN tblctTermos t ON t.cdtermo = a.cdtermo
                            INNER JOIN tblctlicitacao l ON fl.cdlicitacao = l.cdlicitacao AND l.`status` = 1
                            WHERE t.tpvalidacao = 'V' AND a.dataDoc <= CURDATE()+5 AND l.dtfim >= CURDATE() and a.`status` = 1 LIMIT 90";
    $doc_vencidos = mysqli_query($db, $busca_doc_vencidos);
    if (mysqli_num_rows($doc_vencidos) > 0) {
        $texto .= "<li class='list-group-item d-flex justify-content-between align-items-center'>Documentos vencidos ou próximos a data de vencimento!<span class='badge badge-primary badge-pill VerD' data-toggle='modal' data-target='.bd-example-modal-sm'>Ver</span></li> ";
        $cdforn = 0;
        $doc_list = '<ul class="list-group">';
        while ($doc = mysqli_fetch_array($doc_vencidos)) {
            if ($doc['CdForn'] != $cdforn) {
                $doc_list .= '<li class="list-group-item text-primary">' . $doc['NmForn'] . '</li>';
            }
            $doc_list .= '<li class="list-group-item">Credenciamento:' . $doc['CdFornlct7'] . ' - ' . $doc['titulo'] . ' | ' . $doc['dtdoc'] . '</li>';
        }
        $doc_list .= '</ul>';

        $val_alerta += 1;
    }

    if ($val_alerta == 0)
        $texto .= "<li class='list-group-item' > <i class='fas fa-bell-slash' style='color:red'></i> Nenhum aviso no momento.  </li>";
} else if (isset($_SESSION['CdTpUsuario']) && $_SESSION['CdTpUsuario'] == 3) {
    // Não utiliza o Mod. de Credenciamento

} else if (isset($_SESSION['CdTpUsuario']) && $_SESSION['CdTpUsuario'] == 5 && isset($_SESSION['cdfornecedor'])) {
    $doc_pendente .=  $busca_alertas . " AND (a.aprovacao = 2 ) AND fl.cdforn = $_SESSION[cdfornecedor]  GROUP BY fl.CdFornlct";
    //var_dump($doc_pendente); die();
    $val_alerta = 0;
    $doc = mysqli_query($db, $doc_pendente);
    if (mysqli_num_rows($doc) > 0) {
        $texto .= "<li class='list-group-item d-flex justify-content-between align-items-center'>Documentos dos prestadores pendentes para análise!<i class='fas fa-caret-square-right irCred' alt='Ir para página!' style='color:orange;'></i></li>";
        $texto .= "<li class='list-group-item d-flex justify-content-between align-items-center'>Documentos dos profissionais pendentes para análise!<i class='fas fa-caret-square-right irCred' data-forn='doc-prof' alt='Ir para página!' style='color:orange;'></i></li>";
        $val_alerta += 2;
    }

    $busca_doc_vencidos = " SELECT  fl.CdFornlct, t.titulo,DATE_FORMAT(a.dataDoc,'%d/%m/%Y') as dtdoc , f.NmForn, l.descricao,f.CdForn
                            FROM  tblctfornecedor_licitacao fl
                            INNER JOIN tbfornecedor f ON f.CdForn = fl.cdforn
                            INNER JOIN tblctAnexos a ON fl.CdFornlct = a.CdFornlct
                            INNER JOIN tblctTermos t ON t.cdtermo = a.cdtermo
                            INNER JOIN tblctlicitacao l ON fl.cdlicitacao = l.cdlicitacao AND l.`status` = 1
                            WHERE t.tpvalidacao = 'V' AND a.dataDoc <= CURDATE()+5 AND l.dtfim >= CURDATE() and a.`status` = 1 and fl.cdforn = $_SESSION[cdfornecedor]";
    $doc_vencidos = mysqli_query($db, $busca_doc_vencidos);
    if (mysqli_num_rows($doc_vencidos) > 0) {
        $texto .= "<li class='list-group-item d-flex justify-content-between align-items-center'>Documentos vencidos ou próximos a data de vencimento!<span class='badge badge-primary badge-pill VerD' data-toggle='modal' data-target='.bd-example-modal-sm'>Ver</span></li> ";
        $cdforn = 0;
        $doc_list = '<ul class="list-group">';

        while ($doc = mysqli_fetch_array($doc_vencidos)) {
            if ($doc['CdForn'] != $cdforn) {
                $doc_list .= '<li class="list-group-item text-primary">' . $doc['NmForn'] . '</li>';
            }
            $doc_list .= '<li class="list-group-item">Credenciamento:' . $doc['CdFornlct7'] . ' - ' . $doc['titulo'] . ' | ' . $doc['dtdoc'] . '</li>';
            $cdforn = $doc['CdForn'];
        }
        $doc_list .= '</ul>';
        $val_alerta += 1;
    }

    if ($val_alerta == 0)
        $texto .= "<li class='list-group-item' > <i class='fas fa-bell-slash' style='color:red'></i> Nenhum aviso no momento.  </li>";
} else {
}

$texto .= '</ul>';
echo json_encode(array('forn' => $forn_list, 'aprova_forn' => $aprova_forn, 'atualiza_forn' => $atualiza_forn, 'doc' => $doc_list, 'msg' => $texto, 'num_alerts' => $val_alerta));