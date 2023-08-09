<?php

function inserir_movimentacao_saldo($cdsolcons,$data_agenda,$cdsaldo,$cdteto,$cdubs = null){
    // inseri movimentação
    $sql_insert_mov = "INSERT INTO tbsldmovimentacao (`cdsaldo`, `cdteto`, `cdsolcons`, `dtmov`, `cdubs`, `status`) VALUES ('$cdsaldo', '$cdteto', '$cdsolcons', '$data_agenda', '$cdubs', '1')";
    //var_dump($sql_insert_mov); die();
    $insert_mov = mysqli_query($GLOBALS['db'], $sql_insert_mov);
    if($insert_mov){
        return true;
    }else{
        return false;
    }
}

function set_canc_saldo($cdsolcons){
    $sql_update_mov = "UPDATE tbsldmovimentacao SET `status` = 0 WHERE cdsolcons = '$cdsolcons'";
    $Update_mov = mysqli_query($GLOBALS['db'],$sql_update_mov);
}

function set_voltar_saldo($cdsolcons = null, $idcombo = null){
    if($cdsolcons > 0 ){
        $busca_dados_agd = "SELECT m.*,ac.DtAgCons,ac.CdForn,ac.valor,ac.valor_sus,sc.CdEspecProc,sc.idcombo 
                            FROM tbsldmovimentacao m
                            INNER JOIN tbsolcons sc on sc.CdSolCons = m.cdsolcons
                            LEFT JOIN tbagendacons ac on ac.CdSolCons = sc.CdSolCons
                            WHERE sc.CdSolCons = $cdsolcons";

    }else{
        $busca_dados_agd = "SELECT m.*,ac.DtAgCons,ac.CdForn,ac.valor,ac.valor_sus,sc.CdEspecProc,sc.idcombo 
                            FROM tbsldmovimentacao m
                            INNER JOIN tbsolcons sc on sc.CdSolCons = m.cdsolcons
                            LEFT JOIN tbagendacons ac on ac.CdSolCons = sc.CdSolCons
                            WHERE sc.idcombo = $idcombo";

    }

    $result = mysqli_query($GLOBALS['db'],$busca_dados_agd)or die('sme - '.mysqli_error());
    
    while($dados = mysqli_fetch_array($result)){
        $valida_saldo = validar_saldo_disponivel( $dados['DtAgCons'], $dados['CdPref'], $dados['CdEspecProc'], $dados['valor'], $dados['valor_sus'], $dados['cdteto'], $dados['cdubs']);
        $sql_update_mov = " UPDATE tbsldmovimentacao SET `status` = 1 WHERE cdsolcons = '$cdsolcons'";
        $Update_mov = mysqli_query($GLOBALS['db'], $sql_update_mov);
    }
    
}

function buscar_saldo_resto($data_agenda = null,$cdpref = null,$cdespec = null,$cdubs = null, $cdteto = null){
    // quando se ja sabe o saldo a ser realizado.
    $erro = "";
    if($cdteto > 0){

        $sql_teto = "SELECT s.limitacao, s.cdsaldo FROM tbsldgensaldo s INNER JOIN tbsldteto t on t.cdsaldo = s.cdsaldo WHERE t.cdteto = $cdteto and s.status = 1 and t.status = 1";
        //echo $sql_teto; die();
        $query_teto = mysqli_query($GLOBALS['db'], $sql_teto);
        $teto = mysqli_fetch_array($query_teto);
        $limitacao = $teto['limitacao'];
        $cdsaldo = $teto['cdsaldo'];

        if($cdubs == null){
            if($limitacao == 'CM'){
                $sql_munCM = "SELECT  t.cdsaldo, t.cdteto, s.limitacao, s.AbtSaldo, (IFNULL(SUM(t.valorTeto),0) - IFNULL(SUM(t.valorUnidade),0)) AS totalTeto
                              FROM tbsldgensaldo s
                              INNER JOIN tbsldteto t ON t.cdsaldo = s.cdsaldo
                              INNER JOIN tbgenfat g on g.idgenfat = t.idgenfat
                              WHERE s.cdsaldo = $cdsaldo AND CURDATE() >= g.dtini AND s.limitacao = '$limitacao'";
                $query_munCM = mysqli_query($GLOBALS['db'], $sql_munCM);
                $dados_saldo = mysqli_fetch_array($query_munCM);

                $sql_munmovCM = "SELECT IFNULL(sum(ac.valor_sus),0) AS totalSUS,
                                        IFNULL(sum(ac.valor),0) AS totalCTR,
	                                    IFNULL(sum(hm.valor_hrmed), 0) AS totalHM
                                 FROM tbsldmovimentacao m
                                 INNER JOIN tbagendacons ac ON ac.CdSolCons = m.cdsolcons
                                 INNER JOIN tbsolcons sc on sc.CdSolCons = ac.CdSolCons
                                 LEFT JOIN tbhrmedico hm on hm.CdHoraMed = sc.cdhrmed
                                 WHERE m.cdsaldo = $cdsaldo AND (m.cdubs is null OR m.cdubs = 0) AND m.`status` = 1";
                $query_munmovCM = mysqli_query($GLOBALS['db'], $sql_munmovCM);
                $dados_custos = mysqli_fetch_array($query_munmovCM);


            }else if($limitacao = 'NC'){
                $sql_munNC = "SELECT  t.cdsaldo, t.cdteto, s.limitacao, s.AbtSaldo, (IFNULL(SUM(t.valorTeto),0) - IFNULL(SUM(t.valorUnidade),0)) AS totalTeto
                              FROM tbsldgensaldo s
                              INNER JOIN tbsldteto t ON t.cdsaldo = s.cdsaldo
                              WHERE t.cdsaldo = $cdsaldo AND t.cdteto = $cdteto AND s.limitacao = '$limitacao'";
                //echo $sql_munNC; die();
                $query_munNC = mysqli_query($GLOBALS['db'], $sql_munNC);
                $dados_saldo = mysqli_fetch_array($query_munNC);

                $sql_munmovNC = "SELECT IFNULL(sum(ac.valor_sus),0) AS totalSUS,
                                        IFNULL(sum(ac.valor),0) AS totalCTR,
	                                    IFNULL(sum(hm.valor_hrmed), 0) AS totalHM 
                                 FROM tbsldmovimentacao m
                                 INNER JOIN tbagendacons ac ON ac.CdSolCons = m.cdsolcons
                                 INNER JOIN tbsolcons sc on sc.CdSolCons = ac.CdSolCons
                                 LEFT JOIN tbhrmedico hm on hm.CdHoraMed = sc.cdhrmed
                                 WHERE m.cdsaldo = $cdsaldo AND m.cdteto = $cdteto AND (m.cdubs is null OR m.cdubs = 0)  AND m.`status` = 1";
                //echo $sql_munmovNC; die();
                $query_munmovNC = mysqli_query($GLOBALS['db'], $sql_munmovNC);
                $dados_custos = mysqli_fetch_array($query_munmovNC);
            }
        }else if ($cdubs > 0 ){
            if($limitacao == 'CM'){
                $sql_ubsCM = "SELECT t.cdsaldo, t.cdteto, s.limitacao, s.AbtSaldo, SUM(ubs.valor) AS totalTeto
                              FROM tbsldgensaldo s
                              INNER JOIN tbsldteto t ON t.cdsaldo = s.cdsaldo
                              INNER JOIN tbgenfat g on g.idgenfat = t.idgenfat
                              INNER JOIN tbsldubsdist ubs ON ubs.cdsaldo = s.cdsaldo AND ubs.cdteto = t.cdteto
                              WHERE s.cdsaldo = $cdsaldo AND CURDATE() >= g.dtini AND ubs.cdunidade = $cdubs AND s.limitacao = '$limitacao'";
                $query_ubsCM = mysqli_query($GLOBALS['db'], $sql_ubsCM);
                $dados_saldo = mysqli_fetch_array($query_ubsCM);

                $sql_ubsmovCM = "SELECT IFNULL(sum(ac.valor_sus),0) AS totalSUS,
                                        IFNULL(sum(ac.valor),0) AS totalCTR,
	                                    IFNULL(sum(hm.valor_hrmed), 0) AS totalHM
                                 FROM tbsldmovimentacao m
                                 INNER JOIN tbagendacons ac on ac.CdSolCons = m.cdsolcons
                                 INNER JOIN tbsolcons sc on sc.CdSolCons = ac.CdSolCons
                                 LEFT JOIN tbhrmedico hm on hm.CdHoraMed = sc.cdhrmed
                                 WHERE m.cdsaldo = $cdsaldo and m.cdubs = $cdubs and m.`status` = 1";
                $query_ubsmovCM = mysqli_query($GLOBALS['db'], $sql_ubsmovCM);
                $dados_custos = mysqli_fetch_array($query_ubsmovCM);

            }else if($limitacao = 'NC'){
                $sql_ubsNC = "SELECT  t.cdsaldo, t.cdteto, s.limitacao, s.AbtSaldo, SUM(ubs.valor) as totalTeto
                              FROM tbsldgensaldo s
                              INNER JOIN tbsldteto t ON t.cdsaldo = s.cdsaldo
                              INNER JOIN tbsldubsdist ubs ON ubs.cdsaldo = s.cdsaldo AND ubs.cdteto = t.cdteto
                              WHERE t.cdteto = $cdteto AND ubs.cdunidade = $cdubs and s.limitacao = '$limitacao'";
                $query_ubsNC = mysqli_query($GLOBALS['db'], $sql_ubsNC);
                $dados_saldo = mysqli_fetch_array($query_ubsNC);

                $sql_ubsmovNC = "SELECT IFNULL(sum(ac.valor_sus),0) AS totalSUS,
                                        IFNULL(sum(ac.valor),0) AS totalCTR,
	                                    IFNULL(sum(hm.valor_hrmed), 0) AS totalHM
                                 FROM tbsldmovimentacao m
                                 INNER JOIN tbagendacons ac on ac.CdSolCons = m.cdsolcons
                                 INNER JOIN tbsolcons sc on sc.CdSolCons = ac.CdSolCons
                                 LEFT JOIN tbhrmedico hm on hm.CdHoraMed = sc.cdhrmed
                                 WHERE m.cdsaldo = $cdsaldo and m.cdteto = $cdteto and m.cdubs = $cdubs and m.`status` = 1";
                $query_ubsmovNC = mysqli_query($GLOBALS['db'], $sql_ubsmovNC);
                $dados_custos = mysqli_fetch_array($query_ubsmovNC);
            }
            
        }

        $dados = array('cdsaldo' => $dados_saldo['cdsaldo'], 'AbtSaldo' => $dados_saldo['AbtSaldo'], 'teto' => $dados_saldo['totalTeto'], 'tlsus' => $dados_custos['totalSUS'], 'tlctr' => $dados_custos['totalCTR'], 'tlhrmed' => $dados_custos['totalHM'], 'erro' => $erro );
        //var_dump($dados); die();
        return $dados;
    }
    $erro = 'Não possui saldo disponivél para marcação!';
    return $dados = array('cdsaldo' => null,'erro' => $erro );
    //var_dump('Erro ao buscar saldo restante!'); die();
/*     else if($cdteto == null){
        if($cdubs == null){

        }else if ($cdubs > 0 ){
            
        }
    } */
}

function buscar_teto_disp($cdespec, $cdpref, $dtag,$valor_procedimento = null){

    $sql_teto = "  SELECT t.cdteto from tbsldgensaldo g 
                    INNER JOIN tbsldteto t on t.cdsaldo = g.cdsaldo 
                    INNER JOIN tbgenfat gf on gf.idgenfat = t.idgenfat 
                    INNER JOIN tbsldgensaldo_proc gp on gp.cdsaldo = g.cdsaldo
                    INNER JOIN tbespecproc ep on ep.CdEspecProc = $cdespec and ep.CdProcedimento = gp.cdprocedimento 
                    WHERE g.cdpref = $cdpref and '$dtag' BETWEEN gf.dtini and gf.dtfim and g.status = 1 and t.status = 1 and gf.estado = 'A'";
    //var_dump($sql_teto); die();
    $sql_teto = mysqli_query($GLOBALS['db'], $sql_teto);
    while($dados_teto = mysqli_fetch_array($sql_teto)){
        $valida_saldo = validar_saldo_disponivel(null,$dtag,$cdpref,$cdespec,$valor_procedimento,$valor_procedimento,$dados_teto['cdteto'],null);
        if($valida_saldo['status'])
        return $dados_teto['cdteto'];
    }
    
}

function validar_saldo_disponivel( $CdSolCons, $data_agenda, $cdpref, $cdespec, $valor_mun, $valor_sus,  $cdteto = null, $cdubs = null){
    
    if($cdteto > 0){
       $dados_teto = buscar_saldo_resto(null,null,null,null,$cdteto);
      
    }else{
       $dados_teto = buscar_saldo_resto($data_agenda,$cdpref,$cdespec,$cdubs);
    }
    //var_dump($dados_teto); die();
    
    if($dados_teto['erro'] == ''){
        if($dados_teto['AbtSaldo'] == 'CTR'){
            //$saldo_restante = $dados_teto['teto'] - ($dados_teto['tlctr'] + $dados_teto['tlhrmed']);
            $saldo_restante = $dados_teto['teto'] - ($dados_teto['tlctr']);
            //echo $valor_crt; die();
            //echo 'Resto : '.$dados_teto['teto'].' - ( '.$dados_teto['tlctr'].' + '.$dados_teto['tlhrmed'].' ) | '.$dados_teto['cdsaldo'].' Val: '.$saldo_restante.' >= '.$valor_mun.'teste fim'; die();
            if($saldo_restante >= $valor_mun){
                $status = true;
            }else{
                $status = false;
            }
        }else if($dados_teto['AbtSaldo'] == 'PPI'){
            $saldo_restante = $dados_teto['teto'] - $dados_teto['tlsus'];
            if($saldo_restante >= $valor_sus){
                $status = true;
            }else{
                $status = false;
            }
        }
    }else{
        $status = false;
    }

    
    //var_dump($status); die();
    return $dados = array('cdsaldo' => $dados_teto['cdsaldo'], 'status' => $status);
}

?>