<?php
ini_set('display_errors', 1);

require_once("../../../conecta.php");

$protocolo      = $_POST['protocolo']       ?? 0;
$data           = $_POST['data']            ?? 0;
$hora           = $_POST['hora']            ?? 0;
$fornecedor     = $_POST['fornecedor']      ?? 0;
$profissional   = $_POST['profissional']    ?? 0;
$procedimento   = $_POST['procedimento']    ?? 0;
$paciente       = $_POST['paciente']        ?? 0;
$dataAtual      = date('Y-m-d');

$valida_municipio = '';
if ($_SESSION['CdOrigem'] > 0) {
    $valida_municipio = ' AND sc.CdPref = ' . $_SESSION['CdOrigem'];
}

$busca_protocolo = "";
if ($protocolo > 0) {
    $busca_protocolo = " AND ag.protocolopac = '$protocolo'";
}

$busca_data = "";
if ($data > 0) {
    $busca_data = " AND ag.DtAgCons = '$data'";
} else {
    $busca_data = " AND ag.DtAgCons = '" . date('Y-m-d') . "'";
}

$busca_hora = "";
if ($hora > 0) {
    $busca_hora = " AND ag.HoraAgCons LIKE '%$hora%'";
}

$busca_fornecedor = "";
if ($fornecedor > 0) {
    $busca_fornecedor = " AND ag.CdForn = '$fornecedor'";
}

$busca_profissional = "";
if ($profissional > 0) {
    $busca_profissional = " AND ag.cdprof = '$profissional'";
}

$busca_procedimento = "";
if ($procedimento > 0) {
    $busca_procedimento = " AND sc.CdEspecProc = '$procedimento'";
}

$busca_paciente = "";
if ($paciente > 0) {
    $busca_paciente = " AND sc.CdPaciente = '$paciente'";
}

$conferencia = [];

$query = NULL;
if ($paciente > 0) {

    $query =  " SELECT  sc.CdSolCons, 
                        p.CdPaciente, 
                        p.NmPaciente, 
                        ep.NmEspecProc,
                        ag.DtAgCons,
                        ag.aghash,
                        ag.confirmacao_presenca_paciente

                FROM        tbsolcons       sc 
                INNER JOIN  tbagendacons    ag  ON ag.CdSolCons     = sc.CdSolCons
                INNER JOIN  tbespecproc     ep  ON ep.CdEspecProc   = sc.CdEspecProc
                INNER JOIN  tbpaciente      p   ON p.CdPaciente     = sc.CdPaciente
                INNER JOIN  tbprefeitura    pf  ON pf.CdPref        = sc.CdPref
                LEFT JOIN   tbfornecedor    f   ON f.CdForn         = sc.cdfornespera

                WHERE   p.`Status`      = '1'
                AND     ag.`Status`     = '1' 
                AND     sc.`Status`     = '1' 

                $busca_protocolo
                $busca_data
                $busca_hora
                $busca_fornecedor
                $busca_profissional
                $busca_procedimento
                $busca_paciente

                $valida_municipio

                GROUP BY    sc.CdSolCons
                ORDER BY    sc.dtmedsol,
                            NmPaciente ";

    //var_dump($query);die();

    $sql = mysqli_query($db, $query);
}

if (mysqli_num_rows($sql) > 0) {

    $sql2 = mysqli_query($db, $query);
    $presenca = mysqli_fetch_assoc($sql2);
    $paciente = (int)$presenca['CdPaciente'];

    $sql_presenca = "   UPDATE  	tbagendacons 	ag
		                INNER JOIN  tbsolcons 		sol ON sol.CdSolCons = ag.CdSolCons
		                SET     	ag.confirmacao_presenca_paciente = 0
		                WHERE   	sol.CdPaciente = '$paciente'";

    $atualiza_presenca = mysqli_query($db, $sql_presenca);

    while ($n = mysqli_fetch_array($sql)) {

        /*
            $NmEspecProc    = (String)S::create($n["NmEspecProc"])->titleize(["de", "da", "do"]);
            $NmPaciente     = (String)S::create($n["NmPaciente"])->titleize(["de", "da", "do"]);
            $NmForn         = (String)S::create($n["NmForn"])->titleize(["de", "da", "do"]);
            $NmCidade       = (String)S::create($n["NmCidade"])->titleize(["de", "da", "do"]);

			$conferencia[] = array('CdSolCons'=> $n['CdSolCons'],'NmPaciente' => $NmPaciente, 'DtAgCons' => $n["DtAgCons"], 'NmEspecProc'=> $NmEspecProc, 'hash' => $n["hash"]);
            */

        $conferencia[] = array(
            'CdSolCons'     => $n['CdSolCons'],
            'CdPaciente'    => $n['CdPaciente'],
            'NmPaciente'    => $n['NmPaciente'],
            'DtAgCons'      => $n["DtAgCons"],
            'NmEspecProc'   => $n['NmEspecProc'],
            'aghash'        => $n["aghash"],
            'presenca'      => $n['confirmacao_presenca_paciente']
        );
    }
    echo json_encode(array('data' => $conferencia));
} else {

    $conferencia[] = array(
        'CdSolCons'     => null,
        'NmPaciente'    => null,
        'DtAgCons'      => null,
        'NmEspecProc'   => null,
        'aghash'        => null,
        'presenca'      => null
    );

    echo json_encode(array('data' => $conferencia));
}
