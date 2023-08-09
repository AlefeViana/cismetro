<?php
    require "database/index.php"; //and consorciado = "S"

    // query de municipios
    if($_SESSION['CdTpUsuario'] == 3)
            $municipio = " AND CdPref = {$_SESSION['CdOrigem']} ";
    $municipiosResults = $db->query('
        SELECT CdPref, NmCidade FROM tbprefeitura WHERE Status = "1" and consorciado = "S" '.$municipio.' ORDER BY NmCidade 
    ');
    if($_SESSION['CdTpUsuario'] != 3){
        $municipios = [
            ['CdPref' => 0, 'NmCidade' => 'Todos'],
        ];
    }
    
    if ($municipiosResults && $municipiosResults->num_rows) {
        while($dados = $municipiosResults->fetch_array()) {
            $municipios[] = $dados;
        }
    }

    // query de fornecedores
    $fornecedoresResults = $db->query('
        SELECT CdForn, NmForn FROM tbfornecedor ORDER BY NmForn
    ');

    $fornecedores = [
        ['CdForn' => 0, 'NmForn' => 'Todos'],
    ];
    if ($fornecedoresResults && $fornecedoresResults->num_rows) {
        while($dados = $fornecedoresResults->fetch_array()) {
            $fornecedores[] = $dados;
        }
    }

    // query grupo de procedimentos
    $gruposResults = $db->query('
        SELECT cdgrupoproc, nmgrupoproc FROM tbgrupoproc ORDER BY nmgrupoproc
    ');

    $gruposProcedimentos = [
        ['cdgrupoproc' => 0, 'nmgrupoproc' => 'Todos'],
    ];
    if ($gruposResults && $gruposResults->num_rows) {
        while($dados = $gruposResults->fetch_array()) {
            $gruposProcedimentos[] = $dados;
        }
    }

    header('Content-Type: application/json');
    echo json_encode([
        'municipios' => $municipios,
        'fornecedores' => $fornecedores,
        'gruposProcedimentos' => $gruposProcedimentos,
    ]);
