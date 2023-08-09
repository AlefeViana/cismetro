<?php
    require "database/index.php";

    $dataAtual = date('Y-m-d');

    // filtros
    $main = '';
    $grupoProcedimento = '';
    $municipio = '';
    $especificacao = '';
    $fornecedor = '';
    $sexo = '';

    // retorno
    $total = 0;
    $disponibilizado = 0;
    $ocupadas = 0;
    $naoAgendadas = 0;


    // filtro principal
    $ano = " AND YEAR('{$dataAtual}') = YEAR(af.data) ";

    $mes = " AND MONTH('{$dataAtual}') = MONTH(af.data) ";

    $semana = " AND WEEK('{$dataAtual}') = WEEK(af.data) ";

    $dia = " AND af.data = '{$dataAtual}' ";


    switch($_GET['main'] ?? '') {
        case 'dia':
            $main = $dia;
        break;

        case 'semana':
            $main = $ano . $mes . $semana;
        break;

        case 'mes':
            $main = $ano . $mes;
        break;

        case 'ano':
            $main = $ano;
        break;

        default:  $main = $ano;
    }

    // filtros secundários
    if(isset($_GET['cdgrupoproc']) && $_GET['cdgrupoproc']) {
        $grupoProcedimento = " AND af.cdprocedimento = {$_GET['cdgrupoproc']} ";
    }

    if((isset($_GET['CdPref']) && $_GET['CdPref']) || $_SESSION['CdTpUsuario'] == 3) {
        if($_SESSION['CdTpUsuario'] == 3)
            $municipio = " AND sc.CdPref = {$_SESSION['CdOrigem']} ";
        else
            $municipio = " AND sc.CdPref = {$_GET['CdPref']} ";
    }

    if(isset($_GET['CdEspecProc']) && $_GET['CdEspecProc']) {
        $especificacao = " AND af.cdespecificacao = {$_GET['CdEspecProc']} ";
    }

    if(isset($_GET['CdForn']) && $_GET['CdForn']) {
        $fornecedor = " AND af.cdfornecedor = {$_GET['CdForn']} ";
    }

    if(isset($_GET['Sexo']) && $_GET['Sexo']) {
        $sexo = " AND p.Sexo = '{$_GET['Sexo']}' ";
    }

    // query gráfico
    $query = "
        SELECT DISTINCT
        COUNT(*) AS total,
        SUM(IF( af.`status` IN ('A', 'M', 'R'), 1, 0)) AS disponibilizado,
        SUM(IF( af.`status` IN ('M', 'R'), 1, 0)) AS ocupadas,
        SUM(IF( af.`status` = 'A', 1, 0)) AS naoAgendadas
        FROM tbagenda_fornecedor af
        LEFT JOIN tbagendacons ac on af.cdagenda_fornecedor = ac.cdagenda_fornecedor
        LEFT JOIN tbsolcons sc ON ac.CdSolCons = sc.CdSolCons
        LEFT JOIN tbpaciente p ON sc.CdPaciente = p.CdPaciente
        WHERE af.`status` IN ('A', 'M', 'R')
        $main
        $municipio
        $fornecedor
        $grupoProcedimento
        $especificacao
        $sexo
    ";
    // echo $query;

    $grafico = $db->query($query);

    if($grafico && $grafico->num_rows) {
        while($dados = $grafico->fetch_assoc()) {
            $total = (int) $dados['total'];
            $disponibilizado = (int) $dados['disponibilizado'];
            $ocupadas = (int) $dados['ocupadas'];
            $naoAgendadas = (int) $dados['naoAgendadas'];
        }
    }


    header('Content-Type: application/json');
    echo json_encode([
        'total' => $total,
        'disponibilizado' => $disponibilizado,
        'ocupadas' => $ocupadas,
        'naoAgendadas' => $naoAgendadas,
    ]);
