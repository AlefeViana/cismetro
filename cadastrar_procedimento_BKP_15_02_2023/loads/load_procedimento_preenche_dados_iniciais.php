<?php
require("../../conecta.php");

$CdEspecProc = $_POST["CdEspecProc"];

$sql = "SELECT ep.NmEspecProc, 
               ep.cdsus,
               ep.valorsus, 
               ep.valor, 
               ep.desc_sus, 
               ep.cid,
               ep.`Status`,
               gp.*,
               p.CdProcedimento, 
               p.NmProcedimento, 
               e.cdespecialidade, 
               e.nmespecialidade, 
               s.co_servico,
               ep.quemAgendar,
               ep.principal,
               s.no_servico,
               ep.ppi, 
               ep.bpa, 
               scl.co_classificacao, 
               scl.no_classificacao,
               ep.nmpreparo
        FROM tbespecproc ep
        INNER JOIN tbgrupoproc gp ON gp.cdgrupoproc = ep.cdgrupoproc
        LEFT JOIN tbprocedimento p ON p.CdProcedimento = ep.CdProcedimento
        LEFT JOIN tbespecialidade e ON e.cdespecialidade = ep.cdespecialidade
        LEFT JOIN tbservico s ON s.co_servico = ep.cdservico
        LEFT JOIN tbservico_classificacao scl ON scl.co_classificacao = ep.cdclass
				AND scl.co_servico = s.co_servico
        WHERE ep.CdEspecProc = $CdEspecProc";

$verificaEspectLct = mysqli_query($db, $sql);

if (mysqli_num_rows($verificaEspectLct) >= 0) {
  
  while ($row = mysqli_fetch_assoc($verificaEspectLct)) {

    $array_1[] = array(

      /* ------ Campos Text ------ */

      'NmEspecProc' => $row['NmEspecProc']  ?? "0",
      'cdsus'       => $row['cdsus']        ?? "0",
      'valorsus'    => $row['valorsus']     ?? "0",
      'valor'       => $row['valor']        ?? "0",
      'desc_sus'    => $row['desc_sus']     ?? "0",
      'cid'         => $row['cid']          ?? "0",
      'nmpreparo'   => $row['nmpreparo']    ?? "0",

      /* ------ Campos Option ------ */

      'cdgrupoproc'     => $row['cdgrupoproc']      ?? "0",
      'nmgrupoproc'     => $row['nmgrupoproc']      ?? "0",
      'CdProcedimento'  => $row['CdProcedimento']   ?? "0",
      'NmProcedimento'  => $row['NmProcedimento']   ?? "0",
      'cdespecialidade' => $row['cdespecialidade']  ?? "0",
      'nmespecialidade' => $row['nmespecialidade']  ?? "0",
      'co_servico'      => $row['co_servico']       ?? "0",
      'quemAgendar'     => $row['quemAgendar']      ?? "0",

      'status'            => $row['Status']           ?? "0",
      'principal'         => $row['principal']        ?? "0",
      'no_servico'        => $row['no_servico']       ?? "0",
      'ppi'               => $row['ppi']              ?? "0",
      'bpa'               => $row['bpa']              ?? "0",
      'co_classificacao'  => $row['co_classificacao'] ?? "0",
      'no_classificacao'  => $row['no_classificacao'] ?? "0" 

    );

    $array_2[] = array(
      'co_classificacao' => $row['co_classificacao'] ?? "0",
      'no_classificacao' => $row['no_classificacao'] ?? "0"    
    );
    $result = array('dados' => $array_1, 'classificacao' => $array_2);
  }
  echo json_encode(array('espec' => $result));
} else {
  echo json_encode(array('espec' => null));
}
mysqli_close($db);
mysqli_free_result($verificaEspectLct);
