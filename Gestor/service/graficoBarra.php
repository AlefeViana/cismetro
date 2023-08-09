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
    $quantitativo = [0, 0, 0, 0, 0, 0];
    $valores = [0, 0, 0, 0, 0, 0];

    // filtro principal
    $ano = " WHERE YEAR('{$dataAtual}') = if(sc.`Status` = 'E', YEAR(sc.DtInc), YEAR(ac.DtAgCons)) ";

    $mes = " AND MONTH('{$dataAtual}') = if(sc.`Status` = 'E', MONTH(sc.DtInc), MONTH(ac.DtAgCons)) ";

    $semana = " AND WEEK('{$dataAtual}') = if(sc.`Status` = 'E', WEEK(sc.DtInc), WEEK(ac.DtAgCons)) ";

    $dia = " WHERE '{$dataAtual}' = if(sc.`Status` = 'E', sc.DtInc, ac.DtAgCons) ";


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

    if((isset($_GET['CdPref']) && $_GET['CdPref']) || $_SESSION['CdTpUsuario'] == 3) {
        if($_SESSION['CdTpUsuario'] == 3)
            $municipio = " AND sc.CdPref = {$_SESSION['CdOrigem']} ";
        else
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

        sum(if(sc.`Status` = 'E' AND ac.`Status` is NULL AND sc.cdfornespera > 0, 1, 0)) AS encaminhado,
        sum(if(sc.`Status` = 'E' AND ac.`Status` is NULL, 1, 0)) AS filaDeEspera,
        sum(if(sc.`Status` = '1' AND ac.`Status` is NULL, 1, 0)) AS aguardando,
        sum(if(sc.`Status` = '1' AND ac.`Status` = '1', 1, 0)) AS marcado,
        sum(if(sc.`Status` = '1' AND ac.`Status` = '2', 1, 0)) AS realizado,
        sum(if(sc.`Status` = '2' AND ac.`Status` = '1', 1, 0)) AS cancelado,
        sum(if(sc.`Status` = 'F' AND ac.`Status` = '1', 1, 0)) AS falta,

        sum(if(sc.`Status` = 'E' AND ac.`Status` is NULL AND sc.cdfornespera > 0, ep.valor, 0)) AS encaminhadoValor,
        sum(if(sc.`Status` = 'E' AND ac.`Status` is NULL, ep.valor, 0)) AS filaDeEsperaValor,
        sum(if(sc.`Status` = '1' AND ac.`Status` = '1', ac.valor, 0)) AS marcadoValor,
        sum(if(sc.`Status` = '1' AND ac.`Status` = '2', ac.valor, 0)) AS realizadoValor,
        sum(if(sc.`Status` = '2' AND ac.`Status` = '1', ac.valor, 0)) AS canceladoValor,
        sum(if(sc.`Status` = 'F' AND ac.`Status` = '1', ac.valor, 0)) AS faltaValor

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
    ";
    //echo $query;

    $grafico = $db->query($query);

    if($grafico  && $grafico->num_rows) {
        while($dados = $grafico->fetch_array()) {
            $quantitativo = [
                (int) $dados['filaDeEspera'],
                // (int) $dados['aguardando'],
                (int) $dados['encaminhado'],
                (int) $dados['marcado'],
                (int) $dados['realizado'],
                (int) $dados['cancelado'],
                (int) $dados['falta']
            ];
            setlocale(LC_MONETARY, 'pt_BR');
            $valores = [
                money_format('%.2n', $dados['filaDeEsperaValor']),
                //0,
                money_format('%.2n', $dados['encaminhadoValor']),
                money_format('%.2n', $dados['marcadoValor']),
                money_format('%.2n', $dados['realizadoValor']),
                money_format('%.2n', $dados['canceladoValor']),
                money_format('%.2n', $dados['faltaValor']),
            ];
        }
    }


    header('Content-Type: application/json');
    echo json_encode([
        'quantitativo' => $quantitativo,
        'valores' => $valores,
    ]);
