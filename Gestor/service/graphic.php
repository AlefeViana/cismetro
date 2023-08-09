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
    $filaDeEspera = 0;
    $aguardando = 0;
    $marcado = 0;
    $realizado = 0;
    $cancelado = 0;
    $falta = 0;

    // filtro principal
    $ano = " WHERE YEAR('{$dataAtual}') in (YEAR(sc.DtInc), YEAR(ac.DtAgCons)) ";

    $mes = " AND MONTH('{$dataAtual}') in (MONTH(sc.DtInc), MONTH(ac.DtAgCons)) ";

    $semana = " AND WEEK('{$dataAtual}') in (WEEK(sc.DtInc), WEEK(ac.DtAgCons)) ";

    $dia = " AND DAY('{$dataAtual}') in (DAY(sc.DtInc), DAY(ac.DtAgCons)) ";


    switch($_GET['main'] ?? '') {
        case 'dia':
            $main = $ano . $mes . $dia;
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
    $grafico = $db->query("
        SELECT
        COUNT(*) AS total,
        sum(if(sc.`Status` = 'E' AND ac.`Status` is NULL, 1, 0)) AS filaDeEspera,
        sum(if(sc.`Status` = '1' AND ac.`Status` is NULL, 1, 0)) AS aguardando,
        sum(if(sc.`Status` = '1' AND ac.`Status` = '1', 1, 0)) AS marcado,
        sum(if(sc.`Status` = '1' AND ac.`Status` = '2', 1, 0)) AS realizado,
        sum(if(sc.`Status` = '2' AND ac.`Status` = '1', 1, 0)) AS cancelado,
        sum(if(sc.`Status` = 'F' AND ac.`Status` = '1', 1, 0)) AS falta
        FROM tbsolcons sc
        LEFT JOIN tbagendacons ac ON sc.CdSolCons = ac.CdSolCons
        INNER JOIN tbespecproc ep ON sc.CdEspecProc = ep.CdEspecProc
        INNER JOIN tbpaciente p on sc.CdPaciente = p.CdPaciente
        $main
        $municipio
        $fornecedor
        $grupoProcedimento
        $especificacao
        $sexo
    ");

    if($grafico  && $grafico->num_rows) {
        while($dados = $grafico->fetch_array()) {
            $total = (int) $dados['total'];
            $filaDeEspera = (int) $dados['filaDeEspera'] ?? 0;
            $aguardando = (int) $dados['aguardando'] ?? 0;
            $marcado = (int) $dados['marcado'] ?? 0;
            $realizado = (int) $dados['realizado'] ?? 0;
            $cancelado = (int) $dados['cancelado'] ?? 0;
            $falta = (int) $dados['falta'] ?? 0;
        }
    }


    header('Content-Type: application/json');
    echo json_encode([
        'total' => $total,
        'filaDeEspera' => $filaDeEspera,
        'aguardando' => $aguardando,
        'marcado' => $marcado,
        'realizado' => $realizado,
        'cancelado' => $cancelado,
        'falta' => $falta,
    ]);
