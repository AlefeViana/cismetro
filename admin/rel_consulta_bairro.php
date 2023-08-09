<?php

	define("DIRECT_ACCESS", true);

	require_once("verifica.php");
	
	//funcao para tratar erro
	require("function_trata_erro.php");

	
	//verifica se o usuario tem permiss�o para acessar a pagina
	if ((int)$_SESSION["CdTpUsuario"] != 3 && (int)$_SESSION["CdTpUsuario"] != 4)	
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
		$("#grafico").hide();
		window.print();
		$("#frm_pesq").show();
		$("#grafico").show();
	});
	$("#BtnVoltar").click(function(){
		window.location.href="../index.php?p=inicial";
	});
	$("#dtini").mask("99/99/9999");
	$("#dtfim").mask("99/99/9999");
});

function abrirpop(url,nome,w,h,s){
	janela = window.open(url,nome,'width='+w+',height='+h+',top=1,left=1,scrollbars='+s+',toolbar=no,menubar=no,status=no,location=no,resizable=no');
	janela.focus();
}

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
        $sql = "SELECT p.CdBairro,NmBairro, COUNT(sc.CdSolCons) as Total        
				FROM tbsolcons sc INNER JOIN tbpaciente p ON sc.CdPaciente=p.CdPaciente 
		  			INNER JOIN tbbairro b ON b.CdBairro=p.CdBairro
		  			INNER JOIN tbprefeitura pr ON b.CdPref=pr.CdPref
				WHERE sc.Status <> '2' AND 
				      LEFT(sc.DtInc,10) BETWEEN '$Dtinicial' AND '$Dtfinal'";

//filtra os dados da prefeitura que o usuario estiver associado
	if ((int)$_SESSION["CdOrigem"]>0)
	{
		$sql .= " AND b.CdPref=".(int)$_SESSION["CdOrigem"];		
	}					  
					  
		$sql .= " GROUP BY p.CdBairro,NmBairro	  
				  ORDER BY NmBairro";

//executa a consulta
    $query = mysqli_query($db,$sql)or die (TrataErro(mysqli_errno(),'','../index.php?p=inicial','rel_consulta_bairro:consulta dados'));

//inicio do form de pesquisa
?>
	<div id="logo" style="position:relative; vertical-align:top;"><img src="../imagens/consaude_online.png" border="0" alt="Iconsorcio" /></div>
	<div id="rotina" style="text-align:center">
    	Relat&oacute;rio Pedidos de Consultas por Bairro<br /><br />
    </div>
	<div id="frm_pesq">
    	<form action="rel_consulta_bairro.php" name="frm_pesq" method="post" id="frm_pesq">
        	De data&nbsp;<input type="text" name="dtini" id="dtini" size="7" value="<?php echo $dtini;?>" 
            			onblur="if(!valida_data(this.value)){alert('Data inv�lida.'); this.value = ''; }" />&nbsp;at&eacute;&nbsp;
            <input type="text" name="dtfim" id="dtfim" size="7" value="<?php echo $dtfim;?>"
            onblur="if(!valida_data(this.value)){alert('Data inv�lida.'); this.value = ''; }" />                            
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
		 	 echo "<th width=\"500\">Bairro</th>";
			 echo "<th width=\"200\">Total de Pedidos</th>";
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
				   echo "<td>&nbsp;$l[NmBairro]</td>";
				   
				   $total += $l["Total"];
						
				   echo "<td align=\"right\">&nbsp;$l[Total]&nbsp;</td>";			
				   echo "</tr>";
			  $dados .= 'data['.$l["NmBairro"].']='.$l["Total"].'&';		
  
         }//fim enquanto
		 //$dados = substr($dados,0,strlen($dados)-1);
		 $dados .= 'BackgroundColor=FFFFFF';		 
		 echo "<tr>
				   <td align=\"right\">Total&nbsp;</td>
				   <td align=\"right\">$total&nbsp;</td>
			  </tr>";
         echo "</table>";
		 
		 echo"<br />
		 	<a href=\"#\" onclick=\"abrirpop('rel_grafico.php?$dados','','650','330','yes');\" id=\"grafico\">Ver Gr&aacute;fico</a>";
         //fim da tabela
         
      }//fim do if
      else{
          echo '<h3><center><font face="verdana","arial" color="FF6464">Nenhuma informa&ccedil;&atilde;o encontrada</font></center></h3>';
	  }
@mysqli_free_result($query);
@mysqli_close();
?>