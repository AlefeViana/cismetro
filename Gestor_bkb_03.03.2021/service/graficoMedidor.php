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
    $satisfacao = 0;

    // filtro principal
    $ano = " WHERE YEAR('{$dataAtual}') = YEAR(ac.DtAgCons) ";

    $mes = " AND MONTH('{$dataAtual}') = MONTH(ac.DtAgCons) ";

    $semana = " AND WEEK('{$dataAtual}') = WEEK(ac.DtAgCons) ";

    $dia = " WHERE ac.DtAgCons = '{$dataAtual}' ";

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
        $grupoProcedimento = " AND ep.cdgrupoproc = {$_GET['cdgrupoproc']} ";
    }

    if(isset($_GET['CdPref']) && $_GET['CdPref']) {
        $municipio = " AND sc.CdPref = {$_GET['CdPref']} ";
    }

    if(isset($_GET['CdEspecProc']) && $_GET['CdEspecProc']) {
        $especificacao = " AND sc.CdEspecProc = {$_GET['CdEspecProc']} ";
    }

    if(isset($_GET['CdForn']) && $_GET['CdForn']) {
        $fornecedor = " AND ac.CdForn = {$_GET['CdForn']} ";
    }

    if(isset($_GET['Sexo']) && $_GET['Sexo']) {
        $sexo = " AND p.Sexo = '{$_GET['Sexo']}' ";
    }

    // query gráfico
    $query = "
        SELECT
        COUNT(*) AS total,
        IFNULL(SUM(rating) / COUNT(*), 0) as satisfacao
        FROM survey sv
        INNER JOIN tbsolcons sc ON sv.agendamento_id = sc.CdSolCons
        INNER JOIN tbagendacons ac ON sc.CdSolCons = ac.CdSolCons
        INNER JOIN tbpaciente p ON sc.CdPaciente = p.CdPaciente
        INNER JOIN tbespecproc ep ON sc.CdEspecProc = ep.CdEspecProc
        $main
        $municipio
        $fornecedor
        $grupoProcedimento
        $especificacao
        $sexo
    ";
    //echo $query;

    $grafico = $db->query($query);

    if($grafico && $grafico->num_rows) {
        while($dados = $grafico->fetch_assoc()) {
            $satisfacao = (int) $dados['satisfacao'];
        }
    }


    header('Content-Type: application/json');
    echo json_encode([
        'satisfacao' => $satisfacao,
    ]);
