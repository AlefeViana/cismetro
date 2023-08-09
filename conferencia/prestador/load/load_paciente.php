<?php
require("../../../conecta.php");

session_start();

$protocolo      = $_POST['protocolo']       ?? 0;
$data           = $_POST['data']            ?? 0;
$hora           = $_POST['hora']            ?? 0;
$fornecedor     = $_POST['fornecedor']      ?? 0;
$profissional   = $_POST['profissional']    ?? 0;
$procedimento   = $_POST['procedimento']    ?? 0;
$dataAtual      = date('Y-m-d');

$filtro         = $_REQUEST["q"];

$busca = '';
if (!empty(isset($_REQUEST['q']))) {
    $busca = " AND p.NmPaciente LIKE  '%$filtro%'";
}

if (isset($_REQUEST['page'])) {
    $pagInicio = (intval($_REQUEST['page']) - 1) * 5;
}

$busca_protocolo = "";
if($protocolo > 0){
    $busca_protocolo = "AND ag.protocolopac = '$protocolo'";
}

$busca_data = "";
if ($data > 0) {
    $busca_data = " AND ag.DtAgCons = '$data'";
}else{   
    $busca_data = " AND ag.DtAgCons = '".date('Y-m-d')."'";
}

$busca_hora = "";
if($hora > 0){
    $busca_hora = "AND ag.HoraAgCons LIKE '%$hora%'";
}

$busca_fornecedor = "";
if($fornecedor > 0){
    $busca_fornecedor = "AND ag.CdForn = '$fornecedor'";
}

$busca_profissional = "";
if($profissional > 0){
    $busca_profissional = "AND ag.cdprof = '$profissional'";
}

$busca_procedimento = "";
if($procedimento > 0){
    $busca_procedimento = "AND sol.CdEspecProc = '$procedimento'";
}

$sql = "    SELECT  p.CdPaciente,
                    p.NmPaciente
    
            FROM       tbagendacons ag 
            INNER JOIN tbsolcons    sol ON sol.CdSolCons = ag.CdSolCons
            INNER JOIN tbpaciente   p   ON p.CdPaciente  = sol.CdPaciente
    
            WHERE   ag.`Status`     = '1'
            AND     sol.`Status`    = '1'
            AND     p.`Status`      = '1'
            AND     ag.DtAgCons     >= '$dataAtual'
            
            $busca_protocolo
            $busca_data
            $busca_hora
            $busca_fornecedor
            $busca_profissional
            $busca_procedimento 

		    $busca         

            GROUP BY  p.CdPaciente
            ORDER BY  p.NmPaciente ASC";

//var_dump($sql);die();

$verifica = mysqli_query($db, $sql);
$qtd_linhas = mysqli_num_rows($verifica);
$sql .= " LIMIT $pagInicio,5";

$query = mysqli_query($db, $sql);
$paginacao_valida = ((intval($_REQUEST['page']) * 5) < $qtd_linhas) ? true : false;

if ($qtd_linhas > 0) {
    $result = [];

    while ($row = mysqli_fetch_assoc($verifica)) {

        $result[] = array(

            'id'      => $row['CdPaciente']  ?? "0",
            'text'    => $row['NmPaciente']  ?? "0",

        );
    }
    echo json_encode(array('itens' => $result, 'count_filtered' => $qtd_linhas, 'more' => $paginacao_valida));
} else {
    echo json_encode(array('itens' => null, 'count_filtered' => null, 'more' => null));
}
mysqli_close($db);
mysqli_free_result($verifica);
