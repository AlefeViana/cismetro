<?php 

function inserir_movimentacao_contrato($cdsolcons, $cdcontrato, $data_agenda, $CdUsuario){
      // inseri movimentação
      $sql_insert_mov = "INSERT INTO tbcontratomov (`CdSolCons`, `CdContrato`, `Data`, `CdUsuario`, `Status`) VALUES ('$cdsolcons', '$cdcontrato', '$data_agenda', '$CdUsuario', '1')";
      //var_dump($sql_insert_mov); die();
      $insert_mov = mysqli_query($GLOBALS['db'], $sql_insert_mov);
      if($insert_mov){
          return true;
      }else{
          return false;
      }
}

function buscar_contrato($cdforn,$cdespec,$data_agenda,$cdcontrato = null){
    if($cdcontrato == null){
        $qry_busca =" SELECT c.CdContrato, c.Valor,ce.valor_ctr, ce.qts, ce.CdEspecProc
                      FROM tbcontrato c 
                      INNER JOIN tbcontratoespec ce ON ce.CdContrato = c.CdContrato
                      WHERE ce.CdEspecProc = $cdespec AND c.CdForn = $cdforn AND '$data_agenda' BETWEEN c.DtValidade AND c.DtValidadef";
        //var_dump($qry_busca); die();
        $query_contrato = mysqli_query($GLOBALS['db'], $qry_busca);
        // Melhoria: se encontrar mais de dois contratos deve conferir qual possui saldo disponivél e selecionar para continuidade das validações.
        //echo ($query_contrato); die();
        if(mysqli_num_rows($query_contrato) > 0){
            $contrato = mysqli_fetch_array($query_contrato);
            $sql_custo =" SELECT IFNULL(SUM(ac.valormed), 0 ) AS totalCusto, sum(ac.qts) as qtsUsado
                          FROM tbcontratomov m
                          INNER JOIN tbagendacons ac on ac.CdSolCons = m.CdSolCons
                          WHERE m.CdContrato = $contrato[CdContrato] and m.`Status` = 1";
           // var_dump($sql_custo); die();
            $query_custo = mysqli_query($GLOBALS['db'], $sql_custo);
            $dados_custos = mysqli_fetch_array($query_custo);

            $sql_qts ="   SELECT IFNULL(SUM(ac.qts), 0) as qtsUsado
                          FROM tbcontratomov m
                          INNER JOIN tbagendacons ac on ac.CdSolCons = m.CdSolCons
                          INNER JOIN tbsolcons sc on sc.CdSolCons = ac.CdSolCons
                          WHERE m.CdContrato = $contrato[CdContrato] and sc.CdEspecProc = $contrato[CdEspecProc] and m.`Status` = 1";
           // echo $sql_qts; die();
            $query_qts = mysqli_query($GLOBALS['db'], $sql_qts);
            $dados_qts = mysqli_fetch_array($query_qts);

            $saldo_restante = $contrato['Valor'] - $dados_custos['totalCusto'];

            $dados = array('saldo_rest' => $saldo_restante, 'CdContrato' => $contrato['CdContrato'], 'vlproc' => $contrato['valor_ctr'], 'qtsUsado' => $dados_qts['qtsUsado'], 'qts' => $contrato['qts']);
            //var_dump($dados); die();
            return $dados;
        }else
            return false;
        
    }else if($cdcontrato > 0 ){
        // Em Desenvolvimento
    }
}

function validar_contrato($cdforn,$cdespec,$data_agenda, $qts = null,$cdcontrato = null){
    if($cdcontrato == null){
        $dados_contrato = buscar_contrato($cdforn,$cdespec,$data_agenda);
        //var_dump($dados_contrato); die();
    }else if($cdcontrato > 0 ){
        $dados_contrato = buscar_contrato($cdcontrato);
    }

    if($dados_contrato){
        if($dados_contrato['saldo_rest'] >= $dados_contrato['valor_ctr']){
            if(($dados_contrato['qts'] == 0 OR $dados_contrato['qts'] == null) OR ($dados_contrato['qts'] >= $dados_contrato['qtsUsada'])){
                $status = true;
            }else{
                $status = false;
            }
        }else{
            $status = false;
        }
    }else{
        $status = false;
    }
    
    return $dados = array('CdContrato' => $dados_contrato['CdContrato'], 'status' => $status);
}

?>