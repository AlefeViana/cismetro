<?php
include_once "../../controle/valida_contrato.php";

$diff = "";
function inserir_movimentacao_saldo($cdsolcons,$data_agenda,$cdsaldo,$cdteto,$cdubs = 0){
    $cdubs = (int) $cdubs;
    // inseri movimentação
    $sql_insert_mov = "INSERT INTO tbsldmovimentacao (`cdsaldo`, `cdteto`, `cdsolcons`, `dtmov`, `cdubs`, `status`) VALUES ('$cdsaldo', '$cdteto', '$cdsolcons', '$data_agenda', $cdubs, '1')";
    //var_dump($sql_insert_mov); die();
    $insert_mov = mysqli_query($GLOBALS['db'], $sql_insert_mov) or die(mysqli_error($GLOBALS['db']));
    if($insert_mov){
        return true;
    }else{
        return false;
    }
}

function update_movimentacao_saldo($cdsolcons,$data_agenda,$cdsaldo,$cdteto,$cdubs = 0){
    $cdubs = (int) $cdubs;
    // inseri movimentação
    $sql_insert_mov = "UPDATE tbsldmovimentacao SET `cdsaldo` = '$cdsaldo', `cdteto` = '$cdteto', `dtmov` = '$data_agenda' WHERE cdsolcons = $cdsolcons";
    //var_dump($sql_insert_mov); die();
    $insert_mov = mysqli_query($GLOBALS['db'], $sql_insert_mov) or die(mysqli_error($GLOBALS['db']));
    if($insert_mov){
        return true;
    }else{
        return false;
    }
} 

function set_voltar_encaminhado_saldo($cdsolcons = null,$idcombo = null){
    if($cdsolcons > 0 ){
        $sql_update_mov = " DELETE FROM tbsldmovimentacao WHERE cdsolcons = '$cdsolcons'";
    }elseif($idcombo > 0 ){
        $sql_update_mov = " DELETE FROM tbsldmovimentacao m INNER JOIN tbsolcons sc on sc.CdSolCons = m.cdsolcons WHERE idcombo = '$idcombo' AND (sc.`Status` = 1 OR sc.`Status` = 'E') ";
    }else{

    }
   /*  echo  $sql_update_mov;
    die(); */
    $Update_mov = mysqli_query($GLOBALS['db'],$sql_update_mov);
}

function set_canc_saldo($cdsolcons = null,$idcombo = null){

    
    if($cdsolcons > 0 ){
        $sql_update_mov = " UPDATE tbsldmovimentacao SET `status` = 0 WHERE cdsolcons = '$cdsolcons'";
    }elseif($idcombo > 0 ){
        $sql_update_mov = " UPDATE tbsldmovimentacao m INNER JOIN tbsolcons sc on sc.CdSolCons = m.cdsolcons SET m.`status` = 0 WHERE idcombo = '$idcombo' AND (sc.`Status` = 1 OR sc.`Status` = 'E') ";
    }else{

    }
   /*  echo  $sql_update_mov;
    die(); */
    $Update_mov = mysqli_query($GLOBALS['db'],$sql_update_mov);
}

function set_voltar_saldo($cdsolcons = null, $idcombo = null){

    $sql = "SELECT m.*,ac.DtAgCons,ac.CdForn,ac.valor,ac.valor_sus,sc.CdEspecProc,sc.idcombo,sc.CdPref 
    FROM tbsldmovimentacao m
    INNER JOIN tbsolcons sc on sc.CdSolCons = m.cdsolcons
    LEFT JOIN tbagendacons ac on ac.CdSolCons = sc.CdSolCons";

    if($cdsolcons){
        $sql .= " WHERE sc.CdSolCons = $cdsolcons";

    }else{
        $sql .= " WHERE sc.idcombo = $idcombo and (sc.`Status` = 'F' OR sc.`Status` = 2)";
    }   

    if($_SESSION['CdUsuario'] == 2){
        // echo $sql;die();
    }

    $result = mysqli_query($GLOBALS['db'],$sql)or die('sme - '.mysqli_error($GLOBALS['db']));
    $valida_combo = 1;

    while($dados = mysqli_fetch_array($result)){
        
   
        $valida_saldo = validar_saldo_disponivel( $cdsolcons, $dados['DtAgCons'], $dados['CdPref'], $dados['CdEspecProc'], $dados['valor'], $dados['valor_sus'], $dados['cdteto'], $dados['cdubs']);
        $config_ModContrato = getConfiguracao(16);
        if($config_ModContrato['estado'] == 'A'){
            $valida_contrato = validar_contrato($dados['CdForn'],$dados['CdEspecProc'],$dados['DtAgCons']);
        }
        //die();
        if($valida_saldo['status'] && $cdsolcons > 0 && ($valida_contrato['status'] == true && $config_ModContrato['estado'] == 'A') || ($config_ModContrato['estado'] == 'I')){
            $sql_update_mov = " UPDATE tbsldmovimentacao SET `status` = 1 WHERE cdsolcons = '$cdsolcons'";
            $Update_mov = mysqli_query($GLOBALS['db'], $sql_update_mov);
            if($config_ModContrato['estado'] == 'I'){
                $valida_contrato = true;
            }else{
                $valida_contrato = set_estorno_contrato($cdsolcons,$idcombo);
            }
            if($valida_contrato == true){
                return array('status' =>true, 'msg' => '');
            }else{
                return array('status' =>false, 'msg' => 'Saldo de contrato nâo é suficiente');
            }
            
        }
        if(!$valida_saldo['status'] && $idcombo > 0)
            $valida_combo = 0;
    }
    if($valida_combo == 1 && $idcombo > 0){
        $sql_update_mov = " UPDATE tbsldmovimentacao s 
                            INNER JOIN tbagendacons ac on ac.CdSolCons = s.cdsolcons
                            INNER JOIN tbsolcons sc on sc.CdSolCons = s.cdsolcons
                            SET s.`status` = 1
                            where idcombo = $idcombo and (sc.`Status` = 'F' OR sc.`Status` = 2)";
        $Update_mov = mysqli_query($GLOBALS['db'], $sql_update_mov);
        if($config_ModContrato['estado'] == 'I'){
            $valida_contrato = true;
        }else{
            $valida_contrato = set_estorno_contrato($cdsolcons,$idcombo);
        }
        if($valida_contrato == true){
            return array('status' =>true, 'msg' => '');
        }else{
            return array('status' =>false, 'msg' => 'Saldo de contrato não é suficiente');
        }
    }
    return array('status' =>false, 'msg' => 'Saldo insuficiente para voltar o agendamento!');
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

                // $sql_munmovCM = "SELECT IFNULL(sum(ac.valor_sus),0) AS totalSUS,
                //                         IFNULL(sum(IF(ac.CdSolCons is NULL ,ce.valor_mun,ac.valor)),0) AS totalCTR,
	            //                         IFNULL(sum(hm.valor_hrmed), 0) AS totalHM,
                //                         IFNULL(sum(acr.valor),0) AS taxa
                //                  FROM tbsldmovimentacao m
                //                  INNER JOIN tbsolcons sc ON sc.CdSolCons = m.cdsolcons
                //                  LEFT JOIN tbacrescimo acr on sc.CdSolCons = acr.CdSolCons
                //                  INNER JOIN tbespecproc ep on ep.CdEspecProc = sc.CdEspecProc
                //                  INNER JOIN tbcontratomov cm on cm.CdSolCons = sc.CdSolCons
				// 				 INNER JOIN tbcontratoespec  ce on ce.CdContrato = cm.CdContrato and sc.CdEspecProc = ce.CdEspecProc
                //                  LEFT JOIN tbagendacons ac ON ac.CdSolCons = sc.CdSolCons
                //                  LEFT JOIN tbhrmedico hm ON hm.CdHoraMed = sc.cdhrmed
                //                  WHERE m.cdsaldo = $cdsaldo AND (m.cdubs is null OR m.cdubs = 0) AND m.`status` = 1";
                $sql_munmovCM = "SELECT
                                    sum(ac.valor_sus) AS totalSUS,
                                    sum(ac.valor) AS totalCTR,
                                    sum(hm.valor_hrmed) AS totalHM,
                                    sum(acr.valor) AS taxa
                                FROM	tbsldmovimentacao m
                                INNER JOIN tbsolcons sc ON sc.CdSolCons = m.cdsolcons
                                LEFT JOIN tbacrescimo acr ON sc.CdSolCons = acr.CdSolCons
                                INNER JOIN tbagendacons ac ON ac.CdSolCons = sc.CdSolCons
                                LEFT JOIN tbhrmedico hm ON hm.CdHoraMed = sc.cdhrmed
                                WHERE	m.cdsaldo = $cdsaldo
                                AND (m.cdubs IS NULL OR m.cdubs = 0)
                                AND m.`status` = 1";
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
                                        IFNULL(sum(IF(ac.CdSolCons is NULL ,ce.valor_mun,ac.valor)),0) AS totalCTR,
	                                    IFNULL(sum(hm.valor_hrmed), 0) AS totalHM ,
                                        IFNULL(sum(acr.valor),0) AS taxa
                                 FROM tbsldmovimentacao m
                                 INNER JOIN tbsolcons sc ON sc.CdSolCons = m.cdsolcons
                                 LEFT JOIN tbacrescimo acr on sc.CdSolCons = acr.CdSolCons
                                 INNER JOIN tbespecproc ep on ep.CdEspecProc = sc.CdEspecProc
                                 INNER JOIN tbcontratomov cm on cm.CdSolCons = sc.CdSolCons
								 INNER JOIN tbcontratoespec  ce on ce.CdContrato = cm.CdContrato and sc.CdEspecProc = ce.CdEspecProc
                                 LEFT JOIN tbagendacons ac ON ac.CdSolCons = sc.CdSolCons
                                 LEFT JOIN tbhrmedico hm ON hm.CdHoraMed = sc.cdhrmed
                                 WHERE m.cdsaldo = $cdsaldo AND m.cdteto = $cdteto AND (m.cdubs is null OR m.cdubs = 0)  AND m.`status` = 1";
               // echo $sql_munmovNC; die();
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

                // $sql_ubsmovCM = "SELECT IFNULL(sum(ac.valor_sus),0) AS totalSUS,
                //                         IFNULL(sum(IF(ac.CdSolCons is NULL ,ce.valor_mun,ac.valor)),0) AS totalCTR,
	            //                         IFNULL(sum(hm.valor_hrmed), 0) AS totalHM,
                //                         IFNULL(sum(acr.valor),0) AS taxa
                //                  FROM tbsldmovimentacao m
                //                  INNER JOIN tbsolcons sc ON sc.CdSolCons = m.cdsolcons
                //                  LEFT JOIN tbacrescimo acr on sc.CdSolCons = acr.CdSolCons
                //                  INNER JOIN tbespecproc ep on ep.CdEspecProc = sc.CdEspecProc
                //                  INNER JOIN tbcontratomov cm on cm.CdSolCons = sc.CdSolCons
				// 				 INNER JOIN tbcontratoespec  ce on ce.CdContrato = cm.CdContrato and sc.CdEspecProc = ce.CdEspecProc
                //                  LEFT JOIN tbagendacons ac ON ac.CdSolCons = sc.CdSolCons
                //                  LEFT JOIN tbhrmedico hm ON hm.CdHoraMed = sc.cdhrmed
                //                  WHERE m.cdsaldo = $cdsaldo and m.cdubs = $cdubs and m.`status` = 1";
                $sql_ubsmovCM = "SELECT
                                    sum(ac.valor_sus) AS totalSUS,
                                    sum(ac.valor) AS totalCTR,
                                    sum(hm.valor_hrmed) AS totalHM,
                                    sum(acr.valor) AS taxa
                                FROM	tbsldmovimentacao m
                                INNER JOIN tbsolcons sc ON sc.CdSolCons = m.cdsolcons
                                LEFT JOIN tbacrescimo acr ON sc.CdSolCons = acr.CdSolCons
                                INNER JOIN tbagendacons ac ON ac.CdSolCons = sc.CdSolCons
                                LEFT JOIN tbhrmedico hm ON hm.CdHoraMed = sc.cdhrmed
                                WHERE	m.cdsaldo = $cdsaldo
                                and m.cdubs = $cdubs
                                AND m.`status` = 1";
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

                // $sql_ubsmovNC = "SELECT IFNULL(sum(ac.valor_sus),0) AS totalSUS,
                //                         IFNULL(sum(IF(ac.CdSolCons is NULL ,ce.valor_mun,ac.valor)),0) AS totalCTR,
	            //                         IFNULL(sum(hm.valor_hrmed), 0) AS totalHM,
                //                         IFNULL(sum(acr.valor),0) AS taxa
                //                  FROM tbsldmovimentacao m
                //                  INNER JOIN tbsolcons sc ON sc.CdSolCons = m.cdsolcons
                //                  INNER JOIN tbespecproc ep on ep.CdEspecProc = sc.CdEspecProc
                //                  INNER JOIN tbcontratomov cm on cm.CdSolCons = sc.CdSolCons
				// 				 INNER JOIN tbcontratoespec  ce on ce.CdContrato = cm.CdContrato and sc.CdEspecProc = ce.CdEspecProc
                //                  LEFT JOIN tbacrescimo acr on sc.CdSolCons = acr.CdSolCons
                //                  LEFT JOIN tbagendacons ac ON ac.CdSolCons = sc.CdSolCons
                //                  LEFT JOIN tbhrmedico hm ON hm.CdHoraMed = sc.cdhrmed
                //                  WHERE m.cdsaldo = $cdsaldo and m.cdteto = $cdteto and m.cdubs = $cdubs and m.`status` = 1";
                $sql_ubsmovNC = "SELECT
                                    sum(ac.valor_sus) AS totalSUS,
                                    sum(ac.valor) AS totalCTR,
                                    sum(hm.valor_hrmed) AS totalHM,
                                    sum(acr.valor) AS taxa
                                FROM	tbsldmovimentacao m
                                INNER JOIN tbsolcons sc ON sc.CdSolCons = m.cdsolcons
                                LEFT JOIN tbacrescimo acr ON sc.CdSolCons = acr.CdSolCons
                                INNER JOIN tbagendacons ac ON ac.CdSolCons = sc.CdSolCons
                                LEFT JOIN tbhrmedico hm ON hm.CdHoraMed = sc.cdhrmed
                                WHERE	m.cdsaldo = $cdsaldo
                                and m.cdteto = $cdteto 
                                and m.cdubs = $cdubs
                                AND m.`status` = 1";
                $query_ubsmovNC = mysqli_query($GLOBALS['db'], $sql_ubsmovNC);
                $dados_custos = mysqli_fetch_array($query_ubsmovNC);
            }
            
        }

        $dados = array('taxa'=> $dados_custos['taxa'], 'cdsaldo' => $dados_saldo['cdsaldo'],'cdteto' => $dados_saldo['cdteto'], 'AbtSaldo' => $dados_saldo['AbtSaldo'], 'teto' => $dados_saldo['totalTeto'], 'tlsus' => $dados_custos['totalSUS'], 'tlctr' => $dados_custos['totalCTR'], 'tlhrmed' => $dados_custos['totalHM'], 'erro' => $erro );
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
   // var_dump($sql_teto); die();
    $sql_teto = mysqli_query($GLOBALS['db'], $sql_teto) or die(mysqli_error($GLOBALS['db']));
    while($dados_teto = mysqli_fetch_array($sql_teto)){
        $valida_saldo = validar_saldo_disponivel(null,$dtag,$cdpref,$cdespec,$valor_procedimento,$valor_procedimento,$dados_teto['cdteto'],null);
      
        return $dados_teto['cdteto'];
    }
    
}

function validar_saldo_disponivel( $CdSolCons = null, $data_agenda, $cdpref, $cdespec, $valor_mun, $valor_sus,  $cdteto = null, $cdubs = null){
    $diff = "";
    if($cdteto > 0){
       $dados_teto = buscar_saldo_resto(null,null,null,null,$cdteto);
      
    }else{
       $dados_teto = buscar_saldo_resto($data_agenda,$cdpref,$cdespec,$cdubs, $cdteto);
    
    }

    // var_dump($cdteto); die();
    
    if($dados_teto['erro'] == ''){
        if($dados_teto['AbtSaldo'] == 'CTR'){
            $saldo_restante = $dados_teto['teto'] - ($dados_teto['tlctr'] + $dados_teto['taxa']);
            //echo $valor_crt; die();
            if($_SESSION["CdUsuario"] == 150){
				// var_dump($valida_saldo);  var_dump($valida_contrato); var_dump($valor); die();
			}
            //echo 'Resto : '.$dados_teto['teto'].' - '.$dados_teto['tlctr'].' | '.$dados_teto['cdsaldo'].' Val: '.$saldo_restante.' >= '.$valor_mun.'teste fim'; die();
            if($saldo_restante >= $valor_mun){
                $status = true;
            }else{
                $diff =$valor_mun - $saldo_restante;
                $status = false;
            }
        }else if($dados_teto['AbtSaldo'] == 'PPI'){
            $saldo_restante = $dados_teto['teto'] - $dados_teto['tlsus'];
            if($saldo_restante >= $valor_sus){
                $status = true;
            }else{
                $diff =  $valor_mun - $saldo_restante;
                $status = false;
            }
        }
    }else{
        $status = false;
    }

    
    //var_dump($status); die();
    return $dados = array('cdsaldo' => $dados_teto['cdsaldo'],'cdteto' => $dados_teto['cdteto'], 'status' => $status,'diff' => $diff);
}

?>