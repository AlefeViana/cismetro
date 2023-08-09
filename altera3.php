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


<div style="margin:15px;">

<script type="text/javascript" src="ajax.js"> </script>
<?php 

  require('conecta.php'); 
  include "funcoes.php";

	$cd_forn = $_GET["cd_forn"];
	$cd_pref = $_GET['cd_pref'];
	$status = $_GET['status'];
	
   $d1 = FormataDataBd($_GET[dtinicio]);
   $d2 =  FormataDataBd($_GET[dttermino]);


 $d1 = FormataDataBd($_GET[dtinicio]);
 $d2 =  FormataDataBd($_GET[dttermino]);

$sql = "SELECT DISTINCT f.CdForn, f.NmForn, pr.CdPref, pr.NmCidade,  sc.CdSolCons,DtAgCons,HoraAgCons,p.CdPaciente,p.NmPaciente,sc.pactuacao, 
			 ac.CdForn,pr.NmCidade,sc.Protocolo,sc.DtInc,pr.CdPref,ep.CdEspecProc,ac.valor_sus,ac.qts,ac.valor,
			 NmEspecProc, proc.NmProcedimento,sc.pactuacao, f.tprecebimento,f.tpforn						  
			 FROM tbsolcons sc INNER JOIN tbpaciente p ON sc.CdPaciente=p.CdPaciente 
			 INNER JOIN tbbairro b ON b.CdBairro=p.CdBairro
			 INNER JOIN tbprefeitura pr ON b.CdPref=pr.CdPref
			 INNER JOIN tbespecproc ep ON sc.CdEspecProc=ep.CdEspecProc
			 INNER JOIN tbprocedimento proc ON ep.CdProcedimento=proc.CdProcedimento
			 LEFT JOIN tbagendacons ac ON sc.CdSolCons=ac.CdSolCons
			 LEFT JOIN tbfornecedor f ON ac.CdForn=f.CdForn
			 WHERE  pr.CdPref = '$cd_pref' AND f.CdForn = '$cd_forn'
			  AND LEFT(DtAgCons,10) BETWEEN '$d1' AND '$d2'";
			 


	$busca    = mysqli_real_escape_string($_REQUEST["pesq"]);
	$cbopor   = (int)$_REQUEST["cbopesq"];
	
	
   if ($busca != ""){
		switch ($cbopor){
			case 1: $sql .= " AND tbagendacons.CdSolCons = $busca";
					break;
			case 2: $sql .= " AND NmPaciente LIKE '$busca%'";
					break;
			case 3: $sql .= " AND NmEspecProc LIKE '$busca%'";
					break;
			case 4: $sql .= " AND NmForn LIKE '$busca%'";
					break;
			/* case 3:
					$busca = explode("/",$busca);
					$dia = $busca[0];
					$mes = $busca[1];
					$ano =  $busca[2];
					 
				 	$sql .= "  WHERE YEAR(p.DtNasc)=$ano AND MONTH(p.DtNasc)=$mes AND DAY(p.DtNasc)=$dia";
					break;
			case 4: $sql .= " WHERE NmCidade LIKE '%$busca%'";
			break; */
		}
    }

?>
<h1 style="margin-bottom:20px;"> <a href="<?php echo $param   ?>"> Alterar Faturamento </a> </h1>

<div id="pnl_pesq" style="clear:both; height:50px;" >
      <form action="altera2.php?dtinicio=<?php echo $_GET[dtinicio] ?>&dttermino=<?php echo $_GET[dttermino] ?>&cd_pref=<?php echo $_GET[cd_pref] ?>" method="post">
        <input type="text" name="pesq" value="Pesquisar..." onFocus="if(this.value=='Pesquisar...')this.value='';" 
        onblur="if(this.value=='')this.value='Pesquisar...';" style=" float:left; padding:8px; border:#CCCCCC solid 1px; width:200px; font-style:italic; background:url(img/icon_lupa.jpg) no-repeat; padding-left:25px; " />
        <select name="cbopesq" style="float:left; width:130px; height:35px; margin-left:5px; border:#999999 solid 1px; " >
          <option value="1" selected="selected" <?php if ($cbopor == 1) echo 'selected="selected"';?> >C&oacute;digo</option>
          <option value="2" <?php if ($cbopor == 2 || $cbopor == "") echo 'selected="selected"';?> > Paciente </option>
          <option value="3" <?php if ($cbopor == 3 || $cbopor == "") echo 'selected="selected"';?> > Procedimento </option>
          <option value="4" <?php if ($cbopor == 4 || $cbopor == "") echo 'selected="selected"';?> > Fornecedor </option>
        </select>	
        <input type="submit" value="Buscar" name="btnpesq" style=" float:left; width:80px; margin-left:5px; padding:8px; background:#FFFFFF; border:#CCCCCC solid 1px; cursor:pointer" />                 
        </form> 
    </div>

<?php

// Executa a query no MySQL com o limite de linhas.
    $limsql = $sql." ORDER BY f.NmForn, pr.NmCidade, ac.DtAgCons, p.NmPaciente";
    $sql = mysqli_query($db,$limsql)or die(mysqli_error());
?>



<BODY id="minwidth-body" onLoad="ajax.engine('select',this.value,<?php echo $lin[CdSolCons] ?>,'CdForn')" >

<div id="tb">
<?php 
$n = mysqli_num_rows($sql);
// echo "Total de Registros: ".$n;

$param = "$_GET[dtinicio]&dttermino=$_GET[dttermino]&cd_pref=$_GET[cd_pref]";

echo "<table id=table>
<tr>
  <th> Status  </th>
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
<div id="cont"> 
  
</div>

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
       <select id="conf" name="conf" onChange="ajax.engine('texto',this.value,<?php echo $lin[CdSolCons] ?>,'conf')" style="width:100px;" /> 
            <option value="M" <?php if(($lin[sol]== 1) and ($lin[ag] == 1)) { echo "selected=selected"; }  ?> > Marcado </option>
            <option value="R" <?php if(($lin[sol]== 1) and ($lin[ag] == 2)) { echo "selected=selected";  } ?> > Realizado </option>
            <option value="C" <?php if(($lin[sol]== 2) and ($lin[ag] == 2)) { echo "selected=selected";  } ?> > Cancelado </option>
       </select>
        </td>
        
         <td>  
        <select name="CdForn" id="CdForn" class="gr" onChange="ajax.engine('texto',this.value,<?php echo $lin[CdSolCons] ?>,'CdForn')" /> 
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
        
        
       <td style="text-align:center">  <input type="hidden" readonly name="CdSolCons" id="CdSolCons" value="<?php echo $lin[CdSolCons] ?>" style="background:#F3F3F3S " class="pq"   />  <?php echo $lin[CdSolCons] ?> </td>
       <td><input type="text" name="DtAgCons" id="DtAgCons" value="<?php echo FormataDataBr($lin[DtAgCons]) ?>"  onblur="ajax.engine('texto',this.value,<?php echo $lin[CdSolCons] ?>,'DtAgCons')"   style="width:70px;  font-size:13px;"   /></td>
       <td> <input type="text" name="HoraAgCons" i?d="HoraAgCons" value="<?php echo $lin[HoraAgCons] ?>"  onblur="ajax.engine('texto',this.value,<?php echo $lin[CdSolCons] ?>,'HoraAgCons')" class="pq"   /></td>
        <td > <li style="width:180px;"> <?php echo $lin[NmPaciente] ?> </td>
         <td > <li style="width:140px;">  <?php echo $lin[NmCidade] ?> </td>
         <td title="<?php echo FormataDataBr($lin[DtNasc]) ?>"> <li style="width:140px;">   <?php echo CalcularIdade(FormataDataBr($lin[DtNasc]),'dma','/') ?> </td>
        <td>  
        <select id="CdEspecProc" name="CdEspecProc" class="gr" onChange="ajax.engine('texto',this.value,<?php echo $lin[CdSolCons] ?>,'CdEspecProc')" >
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
        
        <td>  <input type="text" name="qts" id="qts_<?php echo $lin[CdSolCons] ?>" value="<?php echo $lin[qts] ?>" class="pq" onkeypress='return SomenteNumero(event)'  /> </td>
        <td>  <input type="text" name="valor" id="valor_<?php echo $lin[CdSolCons] ?>" value="<?php echo number_format($lin[valor], 2, ',', '.'); ?>" class="pq" /> </td>
        <td>  <input type="text" name="valor_sus" id="valor_sus_<?php echo $lin[CdSolCons] ?>" value="<?php echo number_format($lin[valor_sus], 2, ',', '.'); ?>" class="pq" /> </td>
      </tr>
    <?php   } ?>
    </table>
    </form>
</div>


 
 
  <style>
		
		#pag2 { height:35px; border:#DFEAF3 solid 1px; background:#F8FCFF  } 
		#pag2 li   {float:left; padding:10px; width:120px;  height:35px;  } 
		#pag2 li a:hover   { color:#06C  } 
		
		 </style>
        
        <?php 
		
		
		
		$param = "$_GET[dtinicio]&dttermino=$_GET[dttermino]&cd_pref=$_GET[cd_pref]";
								
								echo "<div id='pag2' style='margin-bottom:20px;'>";	
				 
                                  $param = "&i=1&s=l&pesq=$busca&cbopesq=$cbopor&dtinicio=$_GET[dtinicio]&dttermino=$_GET[dttermino]&cd_pref=$_GET[cd_pref]#h1"; 
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
		?>
        
        </div>;
			
					
 
