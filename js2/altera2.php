<style>
 * {
 	 font-family:"Arial Narrow", Helvetica, sans-serif; 
	 font-size:13px; 
	 color:#333333;
	 padding:0px;
	 margin:0px;
	 list-style:none; 
	 width:1500px;
	 }
	 .pq { width:50px; }
	 .gr { width:180px; }

	#table { width:1500px; }
	#table { background:#FFF;   border:#DFEAF3 solid 1px;     }
	#table td { border-bottom:#DFEAF3 solid 1px;  font-size:13px; padding:3px;  }
	#table tr { border-bottom:#F3F7FB solid 1px;   }
	#table tr:hover td {  background:#FFFFCC}	
	#table th { background:url(img/b1.png) repeat; padding:5px; border-right:#FFF solid 1px; border-bottom:#DFEAF3 solid 1px; }
  	#table tr:nth-child(2n+1) { background-color: #F8FCFF}
	h1 { background:#F5F5F5; padding:20px; font-size:15PX; color:#06C  }	
</style>


<script language="javascript">
//-----------------------------------------------------
//Funcao: MascaraMoeda
//Sinopse: Mascara de preenchimento de moeda
//Parametro:
//   objTextBox : Objeto (TextBox)
//   SeparadorMilesimo : Caracter separador de milésimos
//   SeparadorDecimal : Caracter separador de decimais
//   e : Evento
//Retorno: Booleano
//Autor: Gabriel Fróes - www.codigofonte.com.br
//-----------------------------------------------------
function MascaraMoeda(objTextBox, SeparadorMilesimo, SeparadorDecimal, e){
    var sep = 0;
    var key = '';
    var i = j = 0;
    var len = len2 = 0;
    var strCheck = '0123456789';
    var aux = aux2 = '';
    var whichCode = (window.Event) ? e.which : e.keyCode;
    if (whichCode == 13) return true;
    key = String.fromCharCode(whichCode); // Valor para o código da Chave
    if (strCheck.indexOf(key) == -1) return false; // Chave inválida
    len = objTextBox.value.length;
    for(i = 0; i < len; i++)
        if ((objTextBox.value.charAt(i) != '0') && (objTextBox.value.charAt(i) != SeparadorDecimal)) break;
    aux = '';
    for(; i < len; i++)
        if (strCheck.indexOf(objTextBox.value.charAt(i))!=-1) aux += objTextBox.value.charAt(i);
    aux += key;
    len = aux.length;
    if (len == 0) objTextBox.value = '';
    if (len == 1) objTextBox.value = '0'+ SeparadorDecimal + '0' + aux;
    if (len == 2) objTextBox.value = '0'+ SeparadorDecimal + aux;
    if (len > 2) {
        aux2 = '';
        for (j = 0, i = len - 3; i >= 0; i--) {
            if (j == 3) {
                aux2 += SeparadorMilesimo;
                j = 0;
            }
            aux2 += aux.charAt(i);
            j++;
        }
        objTextBox.value = '';
        len2 = aux2.length;
        for (i = len2 - 1; i >= 0; i--)
        objTextBox.value += aux2.charAt(i);
        objTextBox.value += SeparadorDecimal + aux.substr(len - 2, len);
    }
    return false;
}
</script>

<script language='JavaScript'>
function SomenteNumero(e){
    var tecla=(window.event)?event.keyCode:e.which;   
    if((tecla>47 && tecla<58)) return true;
    else{
    	if (tecla==8 || tecla==0) return true;
	else  return false;
    }
}
</script>


<script language="JavaScript" type="text/javascript">
//adiciona mascara de data
  function MascaraData(data){
    if(mascaraInteiro(data)==false){
      event.returnValue = false;
    }       
    return formataCampo(data, '00/00/0000', event);
  }
</script>








<script type="text/javascript" src="ajax.js"> </script>
<?php require('conecta.php'); 
include "funcoes.php";


 $d1 = FormataDataBd($_GET[dtinicio]);
 $d2 =  FormataDataBd($_GET[dttermino]);


 $sql = " 
 SELECT
 tbagendacons.CdSolCons,
 tbpaciente.NmPaciente,
 tbpaciente.DtNasc,
 tbfornecedor.CdForn,
 tbfornecedor.NmForn,
 tbagendacons.DtAgCons,
 tbagendacons.HoraAgCons,
 tbsolcons.CdPref,
 tbprefeitura.NmCidade,
 tbagendacons.valor,
 tbagendacons.valor_sus,
 tbagendacons.ppi,
 tbsolcons.`Status` as sol,
 tbagendacons.`Status` as ag,
 tbespecproc.NmEspecProc,
 tbespecproc.CdEspecProc,
 tbagendacons.qts
 FROM
 tbagendacons
 INNER JOIN tbsolcons ON tbsolcons.CdSolCons = tbagendacons.CdSolCons
 INNER JOIN tbfornecedor ON tbfornecedor.CdForn = tbagendacons.CdForn
 INNER JOIN tbpaciente ON tbpaciente.CdPaciente = tbsolcons.CdPaciente
 INNER JOIN tbprefeitura ON tbprefeitura.CdPref = tbsolcons.CdPref
 INNER JOIN tbespecproc ON tbsolcons.CdEspecProc = tbespecproc.CdEspecProc
 
 WHERE tbprefeitura.CdPref = '$_GET[cd_pref]'
 AND DtAgCons BETWEEN  '$d1' AND '$d2'
";



//executa a consulta
    $query = mysqli_query($db,$sql)or die (mysqli_errno());

//obtem o numero de linhas da consulta
    $qtdreg = mysqli_num_rows($query);
	//echo "Registros ".$qtdreg;

// Especifique quantos resultados você quer por página
    $lpp = 20;

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
    $limsql = $sql." ORDER BY ppi, NmForn, DtAgCons, HoraAgCons, NmPaciente LIMIT $inicio, $lpp ";
    $sql = mysqli_query($db,$limsql)or die(mysqli_error());
?>















<div id="tb">
<?php 
$n = mysqli_num_rows($sql);
// echo "Total de Registros: ".$n;



echo "<table id=table>
<tr>
 <th> Status </th>
  <th> PPI  </th>
 <th> Fornecedor </th>
 <th> Munícipio Fornecedor </th>

 <th> Código </th>
 <th> Data </th>
 <th> Hora </th>
 <th> Paciente </th>
 <th> Municipio </th>
 <th> Idade </th>

 <th> Procedimento</th>
 <th> QTDE </th>
 <th> Valor </th>
 <th> Valor SUS  </th>
</tr>";
?>

<title>Alterar Faturamento Especial </title>
<h1> Alterar Faturamento</h1>
<div id="cont"> </div>

    <form method="POST" action="<?php echo $destino; ?>" id="commentForm">
    <!--  <div id="msg" style="padding:10px; font-size:15px; "> Os Campos com * devem ser preechidos obrigatóriamente </div>--> 
     <?php
    while($lin = mysqli_fetch_array($sql))
    {
      ?>
      
      
      
      
      <?php
	    
		$NmForn = $lin[NmForn];
		
	  	if($NmForn == $lin[NmForn])
		{
			
			echo "<tr> 
				<th colspan='14'> <?php echo $lin[NmForn] ?>  </th>
			  </tr>";
		}
	  
	  ?>
     
      
      
      
      
      
      
      
      <tr>
      
       <td>  
       <select id="conf" name="conf" onchange="ajax.engine('texto',this.value,<?php echo $lin[CdSolCons] ?>,'conf')" style="width:100px;" /> 
            <option value="M" <?php if(($lin[sol]== 1) and ($lin[ag] == 1)) { echo "selected=selected"; }  ?> > Marcado </option>
            <option value="R" <?php if(($lin[sol]== 1) and ($lin[ag] == 2)) { echo "selected=selected";  } ?> > Realizado </option>
            <option value="C" <?php if(($lin[sol]== 2) and ($lin[ag] == 2)) { echo "selected=selected";  } ?> > Cancelado </option>
       </select>
        </td>
        
      <td>  
        <select id="ppi" name="ppi" class="pq" onchange="ajax.engine('texto',this.value,<?php echo $lin[CdSolCons] ?>,'ppi')">
            
            <option value=""  <?php if($lin[ppi]=="") { echo "selected=selected"; }?>>   </option>
            <option value='S' <?php if($lin[ppi]=="S") { echo "selected=selected"; } ?>>  S </option>
            <option value='N' <?php if($lin[ppi]=="N") { echo "selected=selected"; } ?>>  N </option>
        </select>
        </td>  

         <td>  
        <select name="CdForn" id="CdForn" class="gr" onchange="ajax.engine('select',this.value,<?php echo $lin[CdSolCons] ?>,'CdForn')"  /> 
        <?php 
        $s1 = mysqli_query($db," SELECT * FROM tbfornecedor ORDER by NmForn ");
        while($l1 = mysqli_fetch_array($s1))
        {
          if($l1[CdForn]==$lin[CdForn]) { $selec = "selected=selected";  $a = "&raquo;";  } else { $selec = ""; $a = " ";  }
          echo "<option value='$l1[CdForn]' $selec > $a  $l1[NmForn] </option>";
        }
        ?>
        </select>
        </td>
        
        <td > <li style="width:140px;">   
        <?php
          $sql1 = mysqli_query($db, " SELECT
        tbprefeitura.NmCidade
        FROM
        tbfornecedor
        INNER JOIN tbprefeitura ON tbfornecedor.CdCidade = tbprefeitura.CdPref
        WHERE CdForn = '$lin[CdForn]'
        ") or die (mysqli_error());
        $l1 = mysqli_fetch_array($sql1);
        
        echo $l1[NmCidade];
        ?> 
        </td>
        
        
       <td style="text-align:center">  <input type="hidden" readonly="readonly" name="CdSolCons" id="CdSolCons" value="<?php echo $lin[CdSolCons] ?>" style="background:#F3F3F3S " class="pq"   />  <?php echo $lin[CdSolCons] ?> </td>
       <td><input type="text" name="DtAgCons" id="DtAgCons" value="<?php echo FormataDataBr($lin[DtAgCons]) ?>"  onblur="ajax.engine('texto',this.value,<?php echo $lin[CdSolCons] ?>,'DtAgCons')"   style="width:70px;  font-size:13px;"   /></td>
       <td> <input type="text" name="HoraAgCons" i?d="HoraAgCons" value="<?php echo $lin[HoraAgCons] ?>"  onblur="ajax.engine('texto',this.value,<?php echo $lin[CdSolCons] ?>,'HoraAgCons')" class="pq"   /></td>
        <td > <li style="width:180px;"> <?php echo $lin[NmPaciente] ?> </td>
         <td > <li style="width:140px;">  <?php echo $lin[NmCidade] ?> </td>
         <td title="<?php echo FormataDataBr($lin[DtNasc]) ?>"> <li style="width:140px;">   <?php echo CalcularIdade(FormataDataBr($lin[DtNasc]),'dma','/') ?> </td>
        
       
    
        <td>  
        <select id="CdEspecProc" name="CdEspecProc" class="gr" onchange="ajax.engine('texto',this.value,<?php echo $lin[CdSolCons] ?>,'CdForn')" >
        <?php 
        $s1 = mysqli_query($db," SELECT * FROM tbespecproc ORDER by NmEspecProc ");
        while($l1 = mysqli_fetch_array($s1))
        {
          if($l1[CdEspecProc]==$lin[CdEspecProc]) { $selec = "selected=selected";   } else { $selec = "";  }
          echo "<option value='$l1[CdEspecProc]' $selec>  $l1[NmEspecProc] </option>";
        }
        ?>
        </select>
        </td>
        
        <td>  <input type="text" name="qts" id="qts" value="<?php echo $lin[qts] ?>" class="pq" onkeypress='return SomenteNumero(event)' onblur="ajax.engine('texto',this.value,<?php echo $lin[CdSolCons] ?>,'qts')"  /> </td>
        <td>  <input type="text" name="valor" id="valor" value="<?php echo number_format($lin[valor], 2, ',', '.'); ?>" class="pq" onKeyPress="return(MascaraMoeda(this,'.',',',event))"  onblur="ajax.engine('texto',this.value,<?php echo $lin[CdSolCons] ?>,'valor')"  /> </td>
        <td>  <input type="text" name="valor_sus" id="valor_sus" value="<?php echo number_format($lin[valor_sus], 2, ',', '.'); ?>" class="pq" onKeyPress="return(MascaraMoeda(this,'.',',',event))" onblur="ajax.engine('texto',this.value,<?php echo $lin[CdSolCons] ?>,'valor_sus')"  /> </td>
      </tr>
    <?php   } ?>
    </table>
    </form>
</div>


 
 
  <style>
		
		#pag2 { height:35px; border:#DFEAF3 solid 1px; background:#F8FCFF  } 
		#pag2 li   { padding:10px;  } 
		#pag2 li a:hover   { color:#06C  } 
		
		 </style>
        
        <?php 
								
								echo "<div id='pag2'>";	
				 
                                  $param = "?i=1&s=l&pesq=$busca&cbopesq=$cbopor&dtinicio=$_GET[dtinicio]&dttermino=$_GET[dttermino]&cd_pref=$_GET[cd_pref]#h1"; 
                                 if ($pags > 1)
                                 // echo "<br><br>Ver p&aacute;gina:&nbsp;";
                                 if($pag > 0) {
                                      $menos = $pag - 1;
								//	   echo "<li> <a href=?i=$cdsubitem title=Primeira> Primeira </a> </li>"; // Vai para a página anterior
                                      $url = "$PHP_SELF?pag=$menos".$param;
                                      echo '<li> <a href='.$url.' title=Anterior>&laquo; Anterior </a> </li>'; // Vai para a página anterior
                                 }
								 
								 if ($pags > 1)
									{ 
										
										$kb = $pag+1; echo  "<li> Página <strong>$kb</strong> de <strong>$pags</strong> páginas  </li> "; 
									}
 								 // Exibe Números 
                               /*  for($i=0;$i<$pags;$i++) { // Gera um loop com o link para as páginas
                                        $url = "$PHP_SELF?pag=$i".$param;
                                        $j = $i + 1;
                                        if ($pag == $i)
                                            echo " <span>$j</span>";
                                        else
                                            echo " <a href=".$url.">$j</a>";
                                 }*/
								 
								 
                                 if($pag < ($pags - 1)) {
                                        $mais = $pag + 1;
                                        $url = "$PHP_SELF?pag=$mais".$param;	
                                        echo ' <li> <a href='.$url.' title=Próxima>Pr&oacute;xima &raquo;</a> </li> ';
										$ul = $pags-1;
										 $url2 = "$PHP_SELF?pag=$ul".$param;
									//	echo '<li> <a href='.$url2.' title=Última> Última</a> </li>'; // Vai para a página anterior

                                 }	
						// echo "<li style='float:right'> Total de Registros:<strong> $qtreg_total</strong> </li>";				
                    echo "</div>"; 	
					
					
 
