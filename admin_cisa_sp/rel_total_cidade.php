<?php
	require_once("verifica.php");
	
	//funcao para tratar erro
	require("function_trata_erro.php");
	
	//verifica se o usuario tem permissão para acessar a pagina
	if ((int)$_SESSION["CdTpUsuario"] != 1 && (int)$_SESSION["CdTpUsuario"] != 2)	
	{
		echo '<script language="JavaScript" type="text/javascript"> 
			window.location.href="index.php?p=inicial";				
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
		
		if($dtini == ""){
			$dtini = date("d/m/Y");
			$dtfim = date("d/m/Y");
		}
		
//filtra por periodo
$Dtinicial = FData($dtini);
$Dtfinal   = FData($dtfim);	

//consulta solicitacao e agendamento de consultas
        $sql = "SELECT NmCidade, SUM(Valor) as Total        
				FROM tbsolcons sc INNER JOIN tbpaciente p ON sc.CdPaciente=p.CdPaciente 
		  			INNER JOIN tbbairro b ON b.CdBairro=p.CdBairro
		  			INNER JOIN tbprefeitura pr ON b.CdPref=pr.CdPref
		  			INNER JOIN tbagendacons ac ON sc.CdSolCons=ac.CdSolCons
				WHERE sc.Status <> '2' AND 
				      DtAgCons BETWEEN '$Dtinicial' AND '$Dtfinal'
				GROUP BY NmCidade	  
				ORDER BY NmCidade";

//executa a consulta
    $query = mysqli_query($db,$sql)or die (TrataErro(mysqli_errno(),'','../index.php?p=inicial','rel_total_cidade:consulta dados'));

//inicio do form de pesquisa
?>
	<div id="logo" style="position:relative; vertical-align:top;"><img src="../imagens/consaude_online.png" border="0" alt="ConsaudeOnline" /></div>
	<div id="rotina" style="text-align:center">
    	Relat&oacute;rio Gastos por Cidade<br /><br />
    </div>
	<div id="frm_pesq">
    	<form action="rel_total_cidade.php" name="frm_pesq" method="post" id="frm_pesq">
        	De data&nbsp;<input type="text" name="dtini" id="dtini" size="7" value="<?php echo $dtini;?>" 
            			onblur="if(!valida_data(this.value)){alert('Data inválida.'); this.value = ''; }" />&nbsp;at&eacute;&nbsp;
            <input type="text" name="dtfim" id="dtfim" size="7" value="<?php echo $dtfim;?>"
            onblur="if(!valida_data(this.value)){alert('Data inválida.'); this.value = ''; }" />                            
            <input type="submit" value="Filtrar" name="btnpesq" />                 
            &nbsp;
			<input type="button" name="BtnImp" id="BtnImp" value="Imprimir" />
            &nbsp;
			<input type="button" name="BtnVoltar" id="BtnVoltar" value="Voltar" />
            
        </form>
    </div>
<?php
			   
   if (mysqli_num_rows($query) > 0){
//inicio tabela conteudo
      echo '<center><br />Per&iacute;odo de '.$dtini.' at&eacute; '.$dtfim.'
	  		<table width="700" border="1" cellspacing="0" cellpadding="0">';

         echo '<tr bgcolor="#D6D9DE">';
		 	 echo "<th width=\"500\">Cidade</th>";
			 echo "<th width=\"200\">Total</th>";
         echo "</tr>";
        //cor da tabela
         $cortb = "#D5F4F4";
		 $total = 0;	
		 $contador = 0;
		 $cont = 0;
         while($l = mysqli_fetch_array($query)){
               if ($cortb == "#D5F4F4"){
                   $cortb = "#FFFFFF";
               }
               else{
                   $cortb = "#D5F4F4";
               }		
			   
                   echo "<tr bgcolor=".$cortb.">";
				   echo "<td>&nbsp;$l[NmCidade]</td>";
				   
				   $total += $l["Total"];
				   if($l["Total"] != "")
				   		$l["Total"] = number_format($l["Total"],2,",",".");
						
				   echo "<td align=\"right\">&nbsp;$l[Total]&nbsp;</td>";			
				   echo "</tr>";
				
         }//fim enquanto
		 if($total != "") $total = number_format($total,2,",",".");
		 echo "<tr>
				   <td align=\"right\">Total&nbsp;</td>
				   <td align=\"right\">$total&nbsp;</td>
			  </tr>";
         echo "</table>";
         //fim da tabela
         
      }//fim do if
      else{
          echo '<h3><center><font face="verdana","arial" color="FF6464">Nenhuma informa&ccedil;&atilde;o encontrada</font></center></h3>';
	  }
@mysqli_free_result($query);
@mysqli_close();
?>