<?php
    $especificacoes = [
        ['CdEspecProc' => 0, 'NmEspecProc' => 'Todos'],
    ];

    if(isset($_GET['cdgrupoproc']) && $_GET['cdgrupoproc']) {
        require "database/index.php";

        $cdgrupoproc = $_GET['cdgrupoproc'];

        // query de municipios
        $especificacoesResults = $db->query("
            SELECT CdEspecProc, NmEspecProc
            FROM tbespecproc
            WHERE cdgrupoproc = $cdgrupoproc
            ORDER BY NmEspecProc
        ");

        if ($especificacoesResults && $especificacoesResults->num_rows) {
            while($dados = $especificacoesResults->fetch_array()) {
                $especificacoes[] = $dados;
            }
        }
    }

    header('Content-Type: application/json');
    echo json_encode([
        'especificacoes' => $especificacoes,
    ]);
