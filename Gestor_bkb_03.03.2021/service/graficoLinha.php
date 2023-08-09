<?php
    require "database/index.php";

    $dataAtual = date('Y-m-d');

    // filtros
    $main = '';
    $grupoProcedimento = '';
    $especificacao = '';
    $fornecedor = '';
    $sexo = '';
    $municipio = '';

    $labels = [];

    // filtro principal
    $ano = " AND YEAR('{$dataAtual}') = YEAR(ac.DtAgCons) ";

    $mes = " AND MONTH('{$dataAtual}') = MONTH(ac.DtAgCons) ";

    $semana = " AND WEEK('{$dataAtual}') = WEEK(ac.DtAgCons) ";

    $dia = " AND ac.DtAgCons = '{$dataAtual}' ";

    // SELECT
    $selectColumns = '';

    $filter = $_GET['main'] ?? 'dia';

    switch($filter) {
        case 'dia':
            for($hour = 7; $hour <= 20; $hour++) {
                $labels[] = $hour;
                $selectColumns .= " sum(if( HOUR(ac.HoraAgCons) = {$hour}, 1, 0)) AS 'data:{$hour}', ";
            }

            // formata horas
            $labels = array_map(function($label) { return substr("0{$label}:00", -5); }, $labels);

            $main = $dia;
        break;

        case 'semana':
            for($day = 1; $day <= 7; $day++) {
                $selectColumns .= " sum(if( DAYOFWEEK(ac.DtAgCons) = {$day}, 1, 0)) AS 'data:{$day}', ";
            }
            $labels = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];

            $main = $ano . $mes . $semana;
        break;

        case 'mes':
            $lastDay = date('t', strtotime($dataAtual));
            for($day = 1; $day <= $lastDay; $day++) {
                $labels[] = $day;
                $selectColumns .= " sum(if( DAY(ac.DtAgCons) = {$day}, 1, 0)) AS 'data:{$day}', ";
            }

            $main = $ano . $mes;
        break;

        case 'ano':
            for($month = 1; $month <= 12; $month++) {
                $selectColumns .= " sum(if( MONTH(ac.DtAgCons) = {$month}, 1, 0)) AS 'data:{$month}', ";
            }
            $labels = ['Jan.', 'Fev.', 'Mar.', 'Abr.', 'Mai.', 'Jun.', 'Jul.', 'Ago.', 'Set.', 'Out.', 'Nov.', 'Dez.'];

            $main = $ano;
        break;

        default: $main = $ano;
    }

    // filtros secundários
    if(isset($_GET['cdgrupoproc']) && $_GET['cdgrupoproc']) {
        $grupoProcedimento = " AND ep.cdgrupoproc = {$_GET['cdgrupoproc']} ";
    }

    if(isset($_GET['CdEspecProc']) && $_GET['CdEspecProc']) {
        $especificacao = " AND sc.CdEspecProc = {$_GET['CdEspecProc']} ";
    }

    if(isset($_GET['CdForn']) && $_GET['CdForn']) {
        $fornecedor = " AND ac.CdForn = {$_GET['CdForn']} ";
    }

    if(isset($_GET['CdPref']) && $_GET['CdPref']) {
        $municipio = " AND sc.CdPref = {$_GET['CdPref']} ";
    }

    if(isset($_GET['Sexo']) && $_GET['Sexo']) {
        $sexo = " AND p.Sexo = '{$_GET['Sexo']}' ";
    }

    // query gráfico
    $query = "
        SELECT
        pref.CdPref,
        pref.NmCidade,
        $selectColumns
        COUNT(*) AS total
        FROM tbsolcons sc
        INNER JOIN tbagendacons ac ON sc.CdSolCons = ac.CdSolCons
        INNER JOIN tbespecproc ep ON sc.CdEspecProc = ep.CdEspecProc
        INNER JOIN tbpaciente p on sc.CdPaciente = p.CdPaciente
        INNER JOIN tbprefeitura pref ON sc.CdPref = pref.CdPref
        WHERE (
            sc.`Status` = '1' AND ac.`Status` = '1'
            or sc.`Status` = '1' AND ac.`Status` = '2'
        )
        $main
        $municipio
        $fornecedor
        $grupoProcedimento
        $especificacao
        $sexo
        GROUP BY sc.CdPref
        ORDER BY pref.NmCidade
    ";
    // echo $query;

    $grafico = $db->query($query);

    $municipios = [];
    if($grafico  && $grafico->num_rows) {
        while($dados = $grafico->fetch_assoc()) {
            $municipios[] = $dados;
        }
    }


    header('Content-Type: application/json');
    echo json_encode([
        'labels' => $labels,
        'municipios' => $municipios,
    ]);
