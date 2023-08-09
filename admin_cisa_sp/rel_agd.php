<?php
	require_once("verifica.php");
	
	//funcao para tratar erro
	require("function_trata_erro.php");

	//verifica se o usuario tem permissão para acessar a pagina
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

        $busca    = $_REQUEST["pesq"];
		$cbopor   = (int)$_REQUEST["cbopesq"];
		$showall  = (int)$_REQUEST["ckbshowall"];
		$dtini    = $_REQUEST["dtini"];
		$dtfim    = $_REQUEST["dtfim"];
		
		if($dtini == ""){
			$dtini = date("d/m/Y");
			$dtfim = date("d/m/Y");
		}
		
		
		if ($cbopor == 1){
			$busca = (int)$busca;	
		}
/*
SELECT NmCidade, SUM(Valor)        
FROM tbsolcons sc INNER JOIN tbpaciente p ON sc.CdPaciente=p.CdPaciente 
		  INNER JOIN tbbairro b ON b.CdBairro=p.CdBairro
		  INNER JOIN tbprefeitura pr ON b.CdPref=pr.CdPref
		  INNER JOIN tbagendacons ac ON sc.CdSolCons=ac.CdSolCons
GROUP BY NmCidade
*/       
//consulta solicitacao e agendamento de consultas
        $sql = "SELECT sc.CdSolCons,DtAgCons,HoraAgCons,p.CdPaciente,p.NmPaciente,sc.CdEspec,ac.Valor,
		               ac.CdForn,e.NmEspec,pr.NmCidade,sc.Protocolo,sc.DtInc,pr.CdPref,ep.CdEspecProc,
					   NmEspecProc,f.NmReduzido
                FROM tbsolcons sc INNER JOIN tbpaciente p ON sc.CdPaciente=p.CdPaciente 
								  INNER JOIN tbbairro b ON b.CdBairro=p.CdBairro
								  INNER JOIN tbprefeitura pr ON b.CdPref=pr.CdPref
								  INNER JOIN tbespecialidade e ON sc.CdEspec=e.CdEspec
								  INNER JOIN tbespecproc ep ON sc.CdEspecProc=ep.CdEspecProc
								  LEFT JOIN tbagendacons ac ON sc.CdSolCons=ac.CdSolCons
								  LEFT JOIN tbfornecedor f ON ac.CdForn=f.CdForn";

//filtra as solicitações 
		if (!$showall)
			$sql .= " WHERE (ac.Status = '1' OR ac.Status = '2')";
		else
			$sql .= " WHERE ac.Status = '2'";
			
//define busca
	if ($busca != ""){
		switch ($cbopor){
			case 1: $sql .= " AND sc.CdPaciente = $busca";
					break;
			case 2: $sql .= " AND p.NmPaciente LIKE '%$busca%'";
					break;
			case 3: $sql .= " AND f.NmForn LIKE '%$busca%'";
					break;	
			case 4: $sql .= " AND e.NmEspec LIKE '%$busca%'";
					break;								
			case 5: $sql .= " AND sc.Protocolo LIKE '$busca%'";
					break;	
			case 6: $sql .= " AND NmEspecProc LIKE '%$busca%'";
					break;			
		}
    }

//filtra por periodo
$Dtinicial = FData($dtini);
$Dtfinal   = FData($dtfim);	

$sql .= " AND DtAgCons BETWEEN '$Dtinicial' AND '$Dtfinal'";

//filtra os pacientes da prefeitura que o usuario estiver associado
	if ((int)$_SESSION["CdOrigem"]>0)
	{
		$sql .= " AND b.CdPref=".(int)$_SESSION["CdOrigem"];		
	}
	
	$sql .= " ORDER BY DtAgCons, NmPaciente, NmCidade, NmForn";
    //echo $sql;
//executa a consulta
    $query = mysqli_query($db,$sql)or die (TrataErro(mysqli_errno(),'','../index.php?p=inicial','rel_agd:consulta dados'));
/*
//obtem o numero de linhas da consulta
    $qtdreg = mysqli_num_rows($query);
	//echo "Registros ".$qtdreg;

// Especifique quantos resultados você quer por página
    $lpp = 30;

// Retorna o total de páginas
    $pags = ceil($qtdreg / $lpp);

// Especifica uma valor para variavel pagina caso a mesma não esteja setada
    if(!isset($_GET["pag"])) {
         $pag = 0;
    }else{
         $pag = (int)$_GET["pag"];
	}
// Retorna qual será a primeira linha a ser mostrada no MySQL
    $inicio = $pag * $lpp;

// Executa a query no MySQL com o limite de linhas.
    $limsql = $sql." ORDER BY DtInc, CdSolCons, NmForn LIMIT $inicio, $lpp ";
    $query = mysqli_query($db,$limsql)or die("Erro 2 ".mysqli_error());*/
//inicio do form de pesquisa
?>
	<div id="logo" style="position:relative; vertical-align:top;"><img src="../imagens/consaude_online.png" border="0" alt="ConsaudeOnline" /></div>
    <div id="rotina" style="text-align:center">
    	Agendamento de Consultas e Exames Especializados<br /><br />
    </div>
	<div id="frm_pesq">
    	<form action="rel_agd.php" name="frm_pesq" method="post" id="frm_pesq">
        	Pesquisar&nbsp;<input type="text" name="pesq" value="<?php echo $busca;?>" />
            &nbsp; por &nbsp;<select name="cbopesq">
            					<option value="1" <?php if ($cbopor == 1) echo 'selected="selected"';?> >CIH</option>
                                <option value="2" <?php if ($cbopor == 2 || $cbopor == "") echo 'selected="selected"';?> >Nome do Paciente</option>
                                <option value="4" <?php if ($cbopor == 4) echo 'selected="selected"';?> >Nome da Especialidade</option>
                                <option value="6" <?php if ($cbopor == 6) echo 'selected="selected"';?> >Nome da Especifica&ccedil;&atilde;o</option>
                                <option value="3" <?php if ($cbopor == 3) echo 'selected="selected"';?> >Nome do Fornecedor</option>                                
                                <option value="5" <?php if ($cbopor == 5) echo 'selected="selected"';?> >Protocolo</option>                            
            				 </select>	
 			&nbsp;De data&nbsp;<input type="text" name="dtini" id="dtini" size="7" value="<?php echo $dtini;?>" 
            			onblur="if(!valida_data(this.value)){alert('Data inválida.'); this.value = ''; }" />&nbsp;at&eacute;&nbsp;
            <input type="text" name="dtfim" id="dtfim" size="7" value="<?php echo $dtfim;?>"
            onblur="if(!valida_data(this.value)){alert('Data inválida.'); this.value = ''; }" />                            
            <input type="submit" value="Filtrar" name="btnpesq" />                 
            &nbsp;
			<input type="button" name="BtnImp" id="BtnImp" value="Imprimir" />
            &nbsp;
			<input type="button" name="BtnVoltar" id="BtnVoltar" value="Voltar" />
            <br />
            <input type="checkbox" name="ckbshowall" value="1" <?php if ($showall) echo 'checked="checked"'; ?> />Mostrar Somente Realizados
        </form>
    </div>
<?php
			   
   if (mysqli_num_rows($query) > 0){
//inicio tabela conteudo
      echo '<center><table width="100%" border="1" cellspacing="0" cellpadding="0">';
         echo '<tr bgcolor="#D6D9DE">';
		 	 echo "<th>C&oacute;d.</th>";
			 echo "<th>Protocolo</th>";
             echo "<th>Data</th>";
			 echo "<th>Hora</th>";
			 echo "<th>CIH</th>";
			 echo "<th>Paciente</th>";
			 echo "<th>Cidade</th>";
			 echo "<th>Local</th>";
			 echo "<th>Especialidade</th>";
			 echo "<th>Especifica&ccedil;&atilde;o</th>";
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
				   echo "<td>&nbsp;$l[CdSolCons]</td>";
				   echo "<td>&nbsp;$l[Protocolo]</td>";
				   
				   if (isset($l["DtAgCons"])){
						$l["DtAgCons"] = explode("-",$l["DtAgCons"]);
						$l["DtAgCons"] = $l["DtAgCons"][2]."/".$l["DtAgCons"][1]."/".$l["DtAgCons"][0];
				   }
				   echo "<td align=\"center\">&nbsp;$l[DtAgCons]&nbsp;</td>";
                   echo "<td align=\"center\">$l[HoraAgCons]&nbsp;</td>";
				   echo "<td>$l[CdPaciente]</td>";
				   echo "<td align=\"left\" nowrap=\"nowrap\">&nbsp;$l[NmPaciente]</td>";
				   echo "<td align=\"center\" nowrap=\"nowrap\">&nbsp;$l[NmCidade]</td>";				   
				   echo "<td align=\"left\" nowrap=\"nowrap\">&nbsp;$l[NmReduzido]&nbsp;</td>";
				   echo "<td align=\"left\" nowrap=\"nowrap\">&nbsp;$l[NmEspec]</td>";
				   echo "<td align=\"left\" nowrap=\"nowrap\">&nbsp;$l[NmEspecProc]</td>";
				   echo "</tr>";
				   /*$contador++;
				   $cont++;
				   if ($contador == 5){
				   		$query = mysqli_query($db,$sql);
						$contador = 0;
				   }
				   if($cont == 50){
					   $contador = 6;
					   }*/
         }//fim enquanto
		
         echo "</table>";
         //fim da tabela
         
      }//fim do if
      else{
          echo '<h3><center><font face="verdana","arial" color="FF6464">Nenhum agendamento encontrado</font></center></h3>';
	  }
@mysqli_free_result($query);
@mysqli_close();
?>