<?php 
session_start();
include "../../funcoes.php";

$texto = '<ul class="list-group">';
$val_alerta = 0;
$contador = 0;
$contador2=0;
$sql_contrato = "SELECT cdcontrato, valor, DtValidadef FROM tbcontrato WHERE Status  = 1 AND DtValidadef > 'NOW()'";
$query_contrato = mysqli_query($db, $sql_contrato);
while($n = mysqli_fetch_array($query_contrato)){
 $sql_contrato_espec = "SELECT SUM(valor_ctr) FROM tbcontratoespec WHERE CdContrato = ".$n['cdcontrato']." AND Status = 1";
$query_contrato_valor = mysqli_query($db, $sql_contrato_espec);
while($valor = mysqli_fetch_array($query_contrato_valor)){
 $porcentagem = (($n['valor'] - $valor['valor_ctr'])/$n['valor']) * 100; 
 
if($porcentagem < 22){
    $contador++;
    if($contador == 1){
        $texto .= "<li class='list-group-item d-flex justify-content-between align-items-center'>Existem contratos que estão acabando!
        <i class='fas fa-caret-square-right irCTR'  alt='Ir para página!' style='color:orange;'></i></a></li>";
        $val_alerta +=1;
    }  
}
}

$date = $n['DtValidadef'];
$now = date('Y-m-d');
$timestampMonth = strtotime('-1 month',$now);

if($date >= $timestampMonth){
    $contador2++;
  if($contador2 == 1){
    $texto .= "<li class='list-group-item d-flex justify-content-between align-items-center'>Existem contratos que estão vencendo!
    <a href='?i=154'> <i class='fas fa-caret-square-right irCTR'  alt='Ir para página!' style='color:orange;'></i> </a></li>";
    $val_alerta +=1;
  }
    }

}
if($val_alerta == 0){
    $texto .= "<li class='list-group-item' > <i class='fas fa-bell-slash' style='color:red'></i> Nenhum aviso no momento.  </li>";
}
$texto .= '</ul>';

echo json_encode(array('cotaMe' => $dados_cotaMe,'cotaMa' => $dados_cotaMa,'agendas_list' => $agendas_list, 'msg' => $texto,'num_alerts' => $val_alerta));



?>