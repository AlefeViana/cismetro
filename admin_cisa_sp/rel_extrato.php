<?php
	require_once("verifica.php");
	
	//funcao para tratar erro
	require("function_trata_erro.php");
	
	//verifica se o usuario tem permissão para acessar a pagina
	if ((int)$_SESSION["CdTpUsuario"] != 1 && (int)$_SESSION["CdTpUsuario"] != 2 && (int)$_SESSION["CdTpUsuario"] != 3)	
	{
		echo '<script language="JavaScript" type="text/javascript"> 
			window.location.href="../index.php?p=inicial";				
		  </script>';	
	}	
?>	

<script type="text/javascript" src="../js/jquery-1.4.2.min.js"></script>	
<script type="text/javascript" src="../js/jquery.maskedinput-1.2.2.min.js"></script>
<script type="text/javascript"> 
$(document).ready(function() {	
	$("#BtnImp").click(function(){
		$("#frm_pesq").hide();
		window.print();
		$("#frm_pesq").show();
	});
	$("#BtnVoltar").click(function(){
		window.location.href="../index.php?p=inicial";
	});
	$("#dtini").mask("99/99/9999");
	$("#dtfim").mask("99/99/9999");
	/*$("#btnpesq").click(function(){
			if( $("#cd_pref").val() > 0 )
			{
				 $("#rotina").html('Extrato - '+$("#cd_pref option:selected").text()+'<br /><br />'); 	
			}
			else
			{
				$("#rotina").html('Extrato<br /><br />'); 		
			}
	});*/
});

function valida_data(value) {
		//contando chars
		if(value.length!=10) return false;
		// verificando data
		var data = value;
		var dia = data.substr(0,2);
		var barra1 = data.substr(2,1);
		var mes = data.substr(3,2);
		var barra2 = data.substr(5,1);
		var ano = data.substr(6,4);
		if(data.length!=10 || barra1 != "/" || barra2 != "/" || isNaN(dia) || isNaN(mes) || isNaN(ano) || dia>31 || mes>12)return false;
		if((mes==4 || mes==6 || mes==9 || mes==11) && dia==31)return false;
		if(mes==2 && (dia>29 || (dia==29 && ano%4 != 0)))return false;
		if(ano < 1890)return false;
		return true;
}				

</script>
<?php
//funcao para formatar data para formato americano
function FData($data){
	$val = explode("/",$data);
	return $val[2]."-".$val[1]."-".$val[0];	
}
//conecta no banco
    require_once("../conecta.php");
    
//variavel do form de busca

		$dtini    = $_REQUEST["dtini"];
		$dtfim    = $_REQUEST["dtfim"];
		$CdPref   = (int)$_REQUEST["cd_pref"];
		
		//pega o nome do municipio pesquisado
		$NmCidade = '';
		if ($CdPref > 0){
			$query = mysqli_query($db,"SELECT NmCidade FROM tbprefeitura WHERE CdPref=$CdPref");
			if (mysqli_num_rows($query)){
				$NmCidade = mysqli_result($query,0,"NmCidade");
			}
		}
		
		if($dtini == ""){
			$dtini = date("01/m/Y");
			$dtfim = date("d/m/Y");
		}
		
//filtra por periodo
$Dtinicial = FData($dtini);
$Dtfinal   = FData($dtfim);	

//consulta solicitacao e agendamento de consultas
        $sql = "SELECT DtMov,Debito,Credito,TpMov,CdSolCons       
				FROM tbmovimentacao, tbtpmovfinanceira
				WHERE LEFT(DtMov,10) BETWEEN '$Dtinicial' AND '$Dtfinal'
				
				";			 				
				
		if ((int)$_SESSION["CdOrigem"] > 0)
		{
			$sql .= " AND CdPref=".(int)$_SESSION["CdOrigem"];		
			$CdPref = (int)$_SESSION["CdOrigem"];
		}
		else
		{
			$sql .= " AND CdPref=".$CdPref;	
		}
		
		$sql .= " ORDER BY DtMov";
		
//executa a consulta
    $query = mysqli_query($db,$sql)or die (TrataErro(mysqli_errno(),'','../index.php?p=inicial','rel_extrato:consulta dados'));

//inicio do form de pesquisa
?>
	<div id="logo" style="position:relative; vertical-align:top;"><img src="../imagens/consaude_online.png" border="0" alt="ConsaudeOnline" /></div>
	<div id="rotina" style="text-align:center">
    	Extrato<?php if($NmCidade != "") echo ' - '.$NmCidade; ?><br /><br />
    </div>
	<div id="frm_pesq">
    	<form action="rel_extrato.php" name="frm_pesq" method="post" id="frm_pesq">
        	De data&nbsp;<input type="text" name="dtini" id="dtini" size="7" value="<?php echo $dtini;?>" 
            			onblur="if(!valida_data(this.value)){alert('Data inválida.'); this.value = ''; }" />&nbsp;at&eacute;&nbsp;
            <input type="text" name="dtfim" id="dtfim" size="7" value="<?php echo $dtfim;?>"
            onblur="if(!valida_data(this.value)){alert('Data inválida.'); this.value = ''; }" />        
            &nbsp;Prefeitura&nbsp;
            <select name="cd_pref" id="cd_pref">
              		<?php 
						require("../conecta.php");
						$sql = "SELECT CdPref, NmCidade 
								FROM tbprefeitura
								WHERE CdPref in (SELECT CdOrigem
															  FROM tbusuario 
															  WHERE CdOrigem <> 'NULL' AND Status='1'
															 )";
						if ((int)$_SESSION["CdOrigem"]>0)
						{
							$sql .= " AND CdPref=".(int)$_SESSION["CdOrigem"];		
						}
						$sql .=  " ORDER BY NmCidade";
						
						$qry = mysqli_query($db,$sql) or die (mysqli_error());
						if (mysqli_num_rows($qry) > 0){
							while ($dados = mysqli_fetch_array($qry)){
								if ($CdPref == $dados["CdPref"])
									echo '<option value="'.$dados["CdPref"].'" selected="selected">'.$dados["NmCidade"].'</option>';	
								else
									echo '<option value="'.$dados["CdPref"].'">'.$dados["NmCidade"].'</option>';
							}
						} 
						@mysqli_close();
						@mysqli_free_result($qry);
					?>
            </select>                    
            <input type="submit" value="Filtrar" name="btnpesq" id="btnpesq" />                 
            &nbsp;
			<input type="button" name="BtnImp" id="BtnImp" value="Imprimir" />
            &nbsp;
			<input type="button" name="BtnVoltar" id="BtnVoltar" value="Voltar" />
            
        </form>
    </div>
<?php
if($CdPref > 0){
	
   if (mysqli_num_rows($query) > 0){
//inicio tabela conteudo
      echo '<center><br />Per&iacute;odo de '.$dtini.' at&eacute; '.$dtfim.'
	  		<table width="700" border="1" cellspacing="0" cellpadding="0">';

         echo '<tr bgcolor="#D6D9DE">';
		 	 echo "<th width=\"500\">Data Movimenta&ccedil;&atilde;o</th>";	
			 echo "<th width=\"200\">Tipo Movimenta&ccedil;&atilde;o</th>";
			 echo "<th width=\"200\">Cr&eacute;dito</th>";
			 echo "<th width=\"200\">D&eacute;bito</th>";			 
         echo "</tr>";
        //cor da tabela
         $cortb = "#D5F4F4";
		 $total = 0;	
         while($l = mysqli_fetch_array($query)){
               if ($cortb == "#D5F4F4"){
                   $cortb = "#FFFFFF";
               }
               else{
                   $cortb = "#D5F4F4";
               }				   			   	   
				   
				   $Credito += $l[Credito];
				   $Debito  += $l[Debito];
                   echo "<tr bgcolor=".$cortb.">";
				   	   $l[DtMov] = explode(' ',$l[DtMov]);
					   $l[DtMov][0] = explode('-',$l[DtMov][0]);
					   $l[DtMov] = $l[DtMov][0][2].'/'.$l[DtMov][0][1].'/'.$l[DtMov][0][0].' - '.$l[DtMov][1];
					   
					   echo "<td align=\"center\">&nbsp;$l[DtMov]</td>";
					   
					   switch($l["TpMov"]){
					   		case '1': $TpMov = 'Dep. Extra';
									  break;
							case '2': $TpMov = 'Contribuição';
									  break;
							case '3': $TpMov = 'FPM';
									  break;
							default:  $TpMov = '';
									  break;
					   }
					   echo "<td align=\"left\">&nbsp;$TpMov</td>";
					   
					   $l["Debito"] = number_format($l["Debito"],2,",",".");
					   $l["Credito"] = number_format($l["Credito"],2,",",".");						   
					   echo "<td align=\"right\">$l[Credito]&nbsp;</td>";
					   echo "<td align=\"right\">$l[Debito]&nbsp;</td>";					   
				   echo "</tr>";				   				   
				
         }//fim enquanto
		 $total = $Credito - $Debito;
		 
		 //calcula o saldo até a data final
		 $sql = "SELECT p.CdPref,NmCidade,SUM(Credito)-SUM(Debito) as Saldo 
				 FROM tbprefeitura p LEFT JOIN tbmovimentacao m ON p.CdPref=m.CdPref 
				 WHERE p.CdPref = $CdPref AND LEFT(DtMov,10) <= '$Dtfinal'
				 GROUP BY p.CdPref,NmCidade
			";
		 require('../conecta.php');	
		 $qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','../index.php?p=inicial','rel_extrato:consulta saldo'));	//
		 if (mysqli_num_rows($qry) == 1){
		 	$saldo = mysqli_result($qry,0,'Saldo');		
		 }
		 
		 echo "<tr>
				   <td align=\"right\" colspan=\"2\"><b>Total</b>&nbsp;</td>
				   <td align=\"right\"><b>".number_format($Credito,2,",",".")."</b>&nbsp;</td>
				   <td align=\"right\"><b>".number_format($Debito,2,",",".")."</b>&nbsp;</td>				   
			  </tr>";
		 echo "<tr>
				   <td align=\"right\" colspan=\"3\"><b>Diferen&ccedil;a</b>&nbsp;</td>
				   <td align=\"right\"><b>".number_format($total,2,",",".")."</b>&nbsp;</td>
			  </tr>";
			  echo "<tr>
				   <td align=\"right\" colspan=\"3\"><b>Saldo em $dtfim</b>&nbsp;</td>
				   <td align=\"right\"><b>".number_format($saldo,2,",",".")."</b>&nbsp;</td>
			  </tr>";
         echo "</table>";
         //fim da tabela
         
      }//fim do if
      else{
          echo '<h3><center><font face="verdana","arial" color="FF6464">Nenhuma informa&ccedil;&atilde;o encontrada</font></center></h3>';
	  }
	  
}//end if
@mysqli_free_result($query);
@mysqli_close();
?>