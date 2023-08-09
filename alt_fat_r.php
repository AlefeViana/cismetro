<script language="JavaScript" type="text/JavaScript">
ok=false;
function CheckAll() {
        if(!ok){
          for (var i=0;i<document.frm_alterar.elements.length;i++) {
                var x = document.frm_alterar.elements[i];
                if (x.name == 'conf[]') {           
                                x.checked = true;
                                ok=true;
                        }
                }
        }
        else{
        for (var i=0;i<document.frm_alterar.elements.length;i++) {
                var x = document.frm_alterar.elements[i];
                if (x.name == 'conf[]') {           
                                x.checked = false;
                                ok=false;
                        }
                }       
        }
}

</script>
<script language="JavaScript" type="text/JavaScript">
ok2=false;
function CheckAll2() {
        if(!ok2){
          for (var i=0;i<document.frm_alterar.elements.length;i++) {
                var x = document.frm_alterar.elements[i];
                if (x.name == 'canc[]') {           
                                x.checked = true;
                                ok2=true;
                        }
                }
        }
        else{
        for (var i=0;i<document.frm_alterar.elements.length;i++) {
                var x = document.frm_alterar.elements[i];
                if (x.name == 'canc[]') {           
                                x.checked = false;
                                ok2=false;
                        }
                }       
        }
}

</script>


















 <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="js/jquery.validate.js"></script>
    <script type="text/javascript" src="js/additional-methodsbr.js"></script>
    <script type="text/javascript" src="js/localization/messages_ptbr.js"></script>
    <script type="text/javascript" src="js/jquery.maskedinput-1.2.2.min.js"></script>

    <script type="text/javascript" src="js/jquery.maskMoney.0.2.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.8.4.custom.min.js"></script>
    <script type="text/javascript" src="ajax.js"></script>
    
	<script type="text/javascript"><!--//--><![CDATA[//><!--		
													 
		startList = function() 
		{ 
			if (document.all&&document.getElementById) {
				navRoot = document.getElementById("nav");
				for (i=0; i<navRoot.childNodes.length; i++) {
					node = navRoot.childNodes[i];
					if (node.nodeName=="LI") {
						node.onmouseover=function() {
							this.className+=" over";
						}
						node.onmouseout=function() {
							this.className=this.className.replace(" over", "");
						}
					}
				}
			}
    	}
    window.onload=startList;
	
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
		
		if(isNaN(dia) && isNaN(mes) && isNaN(ano)) return true;
		
		var hoje = new Date();
		var dia_a = hoje.getDate();
		if(dia_a < 10) dia_a = '0'+dia_a;
		var mes_a = hoje.getMonth() + 1; //soma se 1 devido o mes começar com 0
		if(mes_a < 10) mes_a = '0'+mes_a;
		var ano_a = hoje.getFullYear();
		//verifica se a data do agendamento é maior ou igual a data atual
		//alert(ano_a+mes_a+dia_a+'-'+ano+mes+dia);
		
		if (ano < ano_a){
			alert('A data informada deve ser maior ou igual a data atual: '+dia_a+'/'+mes_a+'/'+ano_a);
			return false;
		}else{
			if(ano == ano_a){
				if (mes < mes_a){
					alert('A data informada deve ser maior ou igual a data atual: '+dia_a+'/'+mes_a+'/'+ano_a);
					return false;
				}else{
					if (mes == mes_a){
						if (dia_a > dia){
							alert('A data informada deve ser maior ou igual a data atual: '+dia_a+'/'+mes_a+'/'+ano_a);
							return false;
						}	
					}				
				}
			}
		}
		
		if(data.length!=10 || barra1 != "/" || barra2 != "/" || isNaN(dia) || isNaN(mes) || isNaN(ano) || dia>31 || mes>12)return false;
		if((mes==4 || mes==6 || mes==9 || mes==11) && dia==31)return false;
		if(mes==2 && (dia>29 || (dia==29 && ano%4 != 0)))return false;
		if(ano < 1890)return false;
		return true;
	}					   

	function valida_horario(value) {
			//contando chars
			if(value.length!=5) return false;
			// verificando hora
			var horario   = value;
			var hora      = horario.substr(0,2);
			var separador = horario.substr(2,1);
			var minuto    = horario.substr(3,2);
			
			if(isNaN(hora) && isNaN(minuto)) return true;
			
			if(horario.length != 5 || separador != ":" || isNaN(hora) || isNaN(minuto) || hora>23 || minuto>59)return false;
			return true;
	}
	
	function abrirpop(url,nome,w,h,s){
	janela = window.open(url,nome,'width='+w+',height='+h+',top=1,left=1,scrollbars='+s+',toolbar=no,menubar=no,status=no,location=no,resizable=no');
	janela.focus();
	}
	
    //--><!]]>
    </script>

<?php 



 include "conecta.php";  
 include "funcoes.php";  
 
   
	/* Parametros */
	$data = date('d/m/Y');
	
	$cd_forn = $_GET['cd_forn'];
	$cd_pref = $_GET['cd_pref'];
	
	if($cd_forn ==0 ) {$condf = ""; } else { $condf = "AND f.CdForn ='$cd_forn'";  $condf2 = "WHERE f.CdForn ='$cd_forn'";  }
	if($cd_pref ==0 ) {$condc = " AND pr.consorciado='S' "; } else { $condc = "AND pr.CdPref='$cd_pref' AND pr.consorciado='S'";}
	
	
	
	$dtinicio = $_GET['dtinicio'];
	$dttermino = $_GET['dttermino'];
	
	$data1 = FormataDataBD($dtinicio);
	$data2 = FormataDataBD($dttermino);
		
		
	$cdrelfat = $_GET['cdrelfat'];
	

	
/* ==================================================== Atualiza Faturamento */

$ac = $_GET['ac'];

if($ac=="att")
{
	
	$CdPaciente = $_POST['CdPaciente'];
	$NmPaciente = $_POST['NmPaciente'];
	
	$CdSolCons = $_POST['CdSolCons'];
	$HoraAgCons=$_POST['HoraAgCons'];
	$DtAgCons=$_POST['DtAgCons'];
	$DtAgCons = $_POST['DtAgCons'];
	$select_forne = $_POST['select_forne'];
	$Valor=$_POST['Valor'];
	$valor_sus=$_POST['valor_sus'];
	$qts=$_POST['qts'];
	$obsc=$_POST['obsc'];
	
	$valor = $_POST['valor'];
	$valor_sus = $_POST['valor_sus'];
	$CdEspecProc = $_POST['CdEspecProc'];	
	
	$p= $_POST['p'];
	
	for($j=0;$j<=count($CdEspecProc);$j++)
	{
		$Data = FormataDataBD($DtAgCons[$j]);
		$valor_sus[$j] = moeda($valor_sus[$j]);
		$valor[$j] = moeda($valor[$j]);
		
		$sql_pactuacao = mysqli_query($db,"UPDATE `tbsolcons` SET CdEspecProc='$CdEspecProc[$j]' WHERE (CdSolCons='$CdSolCons[$j]')") or die (mysqli_error());
	
		$sql_agenda  = mysqli_query($db,"UPDATE tbagendacons SET CdForn='$select_forne[$j]', DtAgCons='$Data', 
		HoraAgCons='$HoraAgCons[$j]', valor='$valor[$j]', valor_sus='$valor_sus[$j]', qts='$qts[$j]', CdForn='$select_forne[$j]'
		WHERE (CdSolCons='$CdSolCons[$j]')") or die (mysqli_error()) ;
	}

	 $CdSolCons = $_POST['CdSolCons'];
	 $conf = $_POST['conf'];
	 if($conf != ""){
		 for($i=0;$i<=count($conf);$i++)
		  { 
			  set_realizado($conf[$i]);
		  }
	  }
	  
	  
	  $canc = $_POST['canc'];
	  if($canc != ""){
		  for($i=0;$i<=count($canc);$i++)
		  { 
			  
			 set_canc($canc[$i]);
		  }
	  }
	  
	  echo "<div  style='padding:10px; color:#009933; font-size:20px;'> Dados alterados com sucesso! </div>";
}
 
 ?>
 
 	
 
 
 
    <title>Faturamento</title>
    <?php include "conecta.php";  ?>
    
    <link rel="stylesheet" type="text/css" href="css/form.css">
    <link rel="stylesheet" type="text/css" href="css/menus.css">
    <link rel="stylesheet" type="text/css" href="css/geral.css">
    
    <script type="text/javascript"> 
    jQuery(function($){
        $("input[id=DtAgCons]").mask("99/99/9999");
        $("input[id=HoraAgCons]").mask("99:99");
        $("input[id=valor]").maskMoney({symbol:"R$",decimal:",",thousands:"."});
        $("input[id=valor_sus]").maskMoney({symbol:"R$",decimal:",",thousands:"."});
    });
    </script>
    
	    
    <body style="background:none">
    <style> table td { height:20px;  } </style>


    <div id="fat" style="padding:15px;">
    
     <!-- <h1 style="margin-bottom:2px;"> <img src="imagens/realizado.gif" width="10" height="10" />  Controle &raquo; Faturamento <strong>(Procedimentos Realizados ) </strong></h1>  -->
    <h1 style="margin-bottom:1px;" >   Controle Faturamento <?php ?> </h1>
    <h1 style="margin-bottom:0px;">   Periodo: <?php  echo $_GET['dtinicio']." 	 ".$_GET['dttermino'];        ?> </h1>

  <?php 
  
    $status = $_GET['status'];
    if($status=="T")
	{
		$status = " ((sc.Status='1' AND ac.Status='2') or (sc.Status='1' AND ac.Status='1' ))  ";
	}
    if($status=="M")
	{
		$status = " sc.Status='1' AND ac.Status='1' ";
	}
    if($status=="R")
	{
		$status = " sc.Status='1' AND ac.Status='2' ";
	}
  
  
  	$sql2 = mysqli_query($db,"SELECT  ac.CdSolCons, ac.DtAgCons,HoraAgCons,f.CdForn, f.NmForn, proc.CdProcedimento, ac.qts,
	proc.NmProcedimento, ep.CdEspecProc, ep.NmEspecProc, p.CdPaciente,p.NmPaciente,p.CdPaciente, ac.valor,ac.valor_sus,
	sc.status as ssc, ac.status as sac 
	FROM tbsolcons sc INNER JOIN tbpaciente p ON sc.CdPaciente=p.CdPaciente 
	INNER JOIN tbbairro b ON b.CdBairro=p.CdBairro
	INNER JOIN tbprefeitura pr ON b.CdPref=pr.CdPref
	INNER JOIN tbespecproc ep ON sc.CdEspecProc=ep.CdEspecProc
	INNER JOIN tbprocedimento proc ON ep.CdProcedimento=proc.CdProcedimento
	LEFT JOIN tbagendacons ac ON sc.CdSolCons=ac.CdSolCons
	LEFT JOIN tbfornecedor f ON ac.CdForn=f.CdForn
	WHERE  $status
	  $condf
	  $condc
	  AND LEFT(DtAgCons,10) BETWEEN '$data1' AND '$data2'
	  ORDER BY DtAgCons,NmPaciente
							  ");

  
	
	$n = mysqli_num_rows($sql2);

if($n>0)
{
	
	echo "<form action=\"alt_fat_r.php?dtinicio=$dtinicio&dttermino=$dttermino&cd_pref=$cd_pref&cd_forn=$cd_forn&ac=att\" name=\"frm_alterar\" id=\"frm_alterar\" method=\"post\">";
	 echo "<table id='table'>
	 <tr>
		<th > <a href='javascript:void(null)' onClick='CheckAll();'  > CONFIRMAR </a>     </th>
		<th > PACIENTE </th>
		<th > MUNICIPIO </th>
		<th > ESPECIFICACAO </th>";
		//<th >PACTUACAO </th>
		echo"
		<th > DATA      </th>
		<th > HORA      </th>
		<th  > FORNECEDOR </th>
		<th > QTDE </th>
		<th > VALOR </th>
		<th > VALOR SUS </th>
		<th > <a href='javascript:void(null)' onClick='CheckAll2();'  > CANCELAR </a>     </th>
	  </tr>";

	
	
	while($l = mysqli_fetch_array($sql2))
	{
		$CdFornac = $l[CdForn];
		$CdEspecProc = $l[CdEspecProc];
	  
    ?>
              <tr>
               <input type="hidden" name="CdSolCons[]" id="CdSolCons"  value="<?php echo $l[CdSolCons] ?>"  style="text-transform:uppercase; height:30px; padding-left:5px; font-size:15px; width:50px;"  /> 
                <input type="hidden" name="CdPaciente[]" id='CdPaciente'   value="<?php echo $l[CdPaciente] ?>"  style="text-transform:uppercase; height:30px; padding-left:5px; font-size:15px; width:70px;" /> 
               <td  style="text-align:center"  >
     
    <?php 
			// marcado 
			if(($l[ssc]== 1) and ($l[sac] == 1)) { echo "<input type='checkbox' name='conf[]'  value='$l[CdSolCons]'>";
			}
			// realizado
			if(($l[ssc]== 1) and ($l[sac] == 2)) {
			
			 echo "Realizado";  
			}
		
			?>
     </td>
    
                <td> <input type="text" name="NmPaciente[]" id='NmPaciente[]' value="<?php echo $l[NmPaciente] ?>"  class="gr"  disabled style="text-transform:uppercase; height:30px; padding-left:5px; font-size:12px; width:220px;" />   </td>
               	
                <td> 
                	<?php
						$sql = mysqli_query($db,"SELECT tbpaciente.CdPaciente, tbprefeitura.NmCidade
						FROM tbpaciente, tbbairro, tbprefeitura
						where tbpaciente.CdBairro = tbbairro.CdBairro
						AND tbbairro.CdPref = tbprefeitura.CdPref
						AND tbpaciente.CdPaciente='$l[CdPaciente]'") or die (mysqli_error());
						
						
						$ss = mysqli_fetch_array($sql);
						$city = $ss[NmCidade];
					echo "$city";
					 ?>
                </td>
                
                
               <td style="text-transform:uppercase; ">   	
               <?php  
               
                 $sql = "SELECT DISTINCT tbespecproc.CdEspecProc, tbespecproc.NmEspecProc
                          FROM tbespecproc  LEFT JOIN tbsolcons ON tbsolcons.CdEspecProc = tbespecproc.CdEspecProc";
                               $qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=inicial','lista_agendamento:consulta forn'));
                               echo "<select id=\"CdEspecProc\" name=\"CdEspecProc[]\" 
                               class='required' $disabled  style='text-transform:uppercase; height:30px; padding-left:5px; font-size:15px; width:200px;'>";
                               if(mysqli_num_rows($qry) > 0)				
                                   while($local = mysqli_fetch_array($qry)){
                                       if ($local["CdEspecProc"] == $CdEspecProc )
                                            echo "<option value=\"$local[CdEspecProc]\" title=\"$local[NmEspecProc]\" selected=\"selected\"> &raquo; $local[NmEspecProc]</option>";
                                       else
                                            echo "<option value=\"$local[CdEspecProc]\" title=\"$local[NmEspecProc]\">$local[NmEspecProc] </option>";
                                   }					
                               echo "</select>
                               ";	 
                  ?>
               </td>
                
              <!--  <td style="text-align:center"> 
				<?php 
                   $pactuacao = $l[pactuacao];
                
                   if($pactuacao ==1)  { $checked = "checked='checked'";	$vl = 1; }
                   if($pactuacao ==0) { $checked = " ";	 $vl = 0; }
                ?> 
                    <input type="checkbox" id="pactuacao" name="pactuacao" value="<?php echo $vl ?>" <?php echo $checked ?> onChange="ajax.engine('check', this,'pactuacao','<?php echo $l[CdSolCons]; ?>');" />        
                     
                 </td>--> 
            
                <?php  $DtAgCons = FormataDataBR($l[DtAgCons]); ?> 
                 <td>  <input type='text' style="text-align:center; font-size:15px; width:80px;" name='DtAgCons[]' id='DtAgCons' value='<?php echo $DtAgCons ?>'  /> </td>
               
               
                <td> <?php $HoraAgCons = $l[HoraAgCons]; ?> <input type='text' name='HoraAgCons[]' style="text-align:center; font-size:15px; width:50px;" id='HoraAgCons'   
                value='<?php echo $HoraAgCons ?>'  /> </td>
                
               <td >  
              <?php 
              $sql = "SELECT DISTINCT f.CdForn,f.NmForn,f.NmReduzido
                            FROM tbfornecedor f LEFT JOIN tbfornespec fe ON f.CdForn=fe.CdForn
                            ORDER BY NmForn
                            ";
               
                               $qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=inicial','lista_agendamento:consulta forn'));
                               echo "<select id=\"select_forne\" name=\"select_forne[]\" class='required' $disabled  style='text-transform:uppercase; height:30px; padding-left:5px; font-size:15px; width:200px;' >";
                               if(mysqli_num_rows($qry) > 0)				
                                   while($local = mysqli_fetch_array($qry)){
                                       if ($local["CdForn"] == $CdFornac)
                                            echo "<option value=\"$local[CdForn]\" title=\"$local[NmForn]\" selected=\"selected\"> &raquo; $local[NmReduzido]</option>";
                                       else
                                            echo "<option value=\"$local[CdForn]\" title=\"$local[NmForn]\">$local[NmForn] </option>";
                                   }					
                               echo "</select>
                               ";	 
                    ?>  
               </td>
                <td style="text-align:center"> <input type="text" style="font-size:15px; width:30px;" name='qts[]' id='qts' value="<?php echo $l[qts] ?>"  /> </td>
                <td style="text-align:center"> <input type="text" style="font-size:15px; width:80px;" name='valor[]' id='valor' value="<?php echo number_format($l[valor],2,',','.'); ?>"  /> </td>
                <td style="text-align:center"> <input type="text" style="font-size:15px; width:80px;" name='valor_sus[]' id='valor_sus' value="<?php echo number_format($l[valor_sus],2,',','.');  ?>"  /> </td>
               
            
            	<td> <?php echo "<input type='checkbox'  name='canc[]' id='canc' value='$l[CdSolCons]' />"; ?> </td>
            
              </tr>
            <?php 
	}
	?>
  <tr>
    <td colspan="12">
   <div id="btns">
   	<input type="submit" value="SALVAR" >
   </div>
    </td>
  </tr>
</table>
</form>
</div>


<?php } else { echo "Nenhum Faturamento encontrado"; }?>