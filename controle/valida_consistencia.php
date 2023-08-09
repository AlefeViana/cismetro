<?php

function valida_consistencia($cdforn,$cdespec,$data_agenda){
    $sql_consistencia_contrato = "SELECT c.CdContrato,MAX(c.DtValidadef) as DtValidadef FROM tbcontrato c WHERE	c.CdForn = $cdforn";
    $consitencia_contrato = mysqli_query($GLOBALS['db'], $sql_consistencia_contrato) or die(mysqli_error($GLOBALS['db']));
    if($consitencia_contrato){
        $consistencia_contrato = mysqli_fetch_array($consitencia_contrato);
        if($consistencia_contrato['DtValidadef'] >= $data_agenda){
            $sql_consistencia = "   SELECT 
                                        c.CdContrato, 
                                        CAST(ce.valor_ctr as DECIMAL(9,2)) as valor_ctr, 
                                        CAST(ce.valor_mun as DECIMAL(9,2)) as valor_mun,
                                        CAST(ep.valor as DECIMAL(9,2)) as valor
                                    FROM tbcontrato c 
                                    INNER JOIN tbcontratoespec ce on c.CdContrato = ce.CdContrato and ce.`Status` = 1
                                    INNER JOIN tbespecproc ep on ep.CdEspecProc = ce.CdEspecProc and ce.`Status` = 1
                                    WHERE c.CdForn = $cdforn AND ce.CdEspecProc = $cdespec
                                    AND '".$data_agenda."' BETWEEN c.DtValidade and c.DtValidadef
                                    LIMIT 1";

            // var_dump($sql_consistencia); die();
            $consitencia = mysqli_query($GLOBALS['db'], $sql_consistencia) or die(mysqli_error($GLOBALS['db']));
            
            if($consitencia){
                $dados_consistencia = mysqli_fetch_array($consitencia);
                if($dados_consistencia['valor_ctr'] == $dados_consistencia['valor'] && $dados_consistencia['valor_mun'] == $dados_consistencia['valor']){
                    return $dados = array('status' => true, 'mensagem' => '');
                }else{
                    return $dados = array('status' => false, 'mensagem' => ' Caro usuário, foi detectada uma inconsistência em seu agendamento, gentileza entrar em contato com o suporte técnico através do Whatsapp 31 3822-4656.');
                }
            }else{
                return $dados = array('status' => false, 'mensagem' => 'Agendamento não concluído. O procedimento não está habilitado no contrato deste prestador!');
            }
        }else{
            $data_f = explode('-', $consistencia_contrato['DtValidadef']);
	        $dataFimContrato = $data_f[2] . '/' . $data_f[1] . '/' . $data_f[0];
            return $dados = array('status' => false, 'mensagem' => 'Agendamento não concluído. O contrato do prestador expirou em '.$dataFimContrato.'.');
        }
    }else{
        return $dados = array('status' => false, 'mensagem' => 'O prestador não possui nenhum contrato ativo!');
    }
    
}