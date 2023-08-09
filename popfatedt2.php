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


/* ==================================================== Atualiza Faturamento */



$ac = $_GET['ac'];

if($ac=="att")
{
	
	$CdPaciente = $_POST['CdPaciente'];
	$NmPaciente = $_POST['NmPaciente'];
	
	$CdSolCons = $_POST['CdSolCons'];
	$HoraAgCons=$_POST['HoraAgCons'];
	$DtAgCons=$_POST['DtAgCons'];
	$DtAgCons = FormataDataBD($DtAgCons);
	$select_forne = $_POST['select_forne'];
	$Valor=$_POST['Valor'];
	$valor_sus=$_POST['valor_sus'];
	$qts=$_POST['qts'];
	$obsc=$_POST['obsc'];
	
	
	/* Atualiza Paciente nome do paciente  */ 
	$sql_paciente = mysqli_query($db,"UPDATE tbpaciente SET NmPaciente='$NmPaciente' WHERE (CdPaciente='$CdPaciente')") or die (mysqli_error());
	
	/* Atualiza Agenda  Data, Hora, Fornecedor, Especificação, Valor Pactuação, Valor Não Pactuado  */
	$sql_agenda  = mysqli_query($db,"UPDATE tbagendacons SET CdForn='$select_forne', DtAgCons='$DtAgCons', 
	HoraAgCons='$HoraAgCons', valor_pactuado='$valor', valor_n_pactuado='$valor_sus', qts='$qts', CdForn='$CdForn'
	WHERE (CdSolCons='$CdSolCons')");
	
	
	  /* Confirmar (Procedimento Realizado)  */
	 $sql_conf_realizado = mysqli_query($db,"UPDATE tbsolcons SET sc.Status='1', ac.Status='2'  WHERE (CdSolCons='$cd')") or die (mysqli_error());


	
	/* Cancelado (Procedimento Realizado)  */
	 $sql_cancelado = mysqli_query($db,"UPDATE tbsolcons SET sc.Status='2'  WHERE (CdSolCons='$cd')") or die (mysqli_error());
	
		
	if($sql)
	{
		echo '<script language="JavaScript" type="text/javascript"> 
			  alert("Procedimento atualizado com sucesso!");
			  window.location.href="index.php?i=27";				
			  </script>';		
	}

}

/*======================================================Cancelamento */ 
/*if($ac=="canc")
	{
		$cd = $_GET['cd'];
		$op= $_GET['op'];

		$sql = mysqli_query($db,"UPDATE tbsolcons SET Status='2' WHERE (CdSolCons='$cd')") or die (mysqli_error());
		
		
			// estorna saldo 
			$sql1 = mysqli_query($db,"SELECT tbsolcons.CdSolCons, tbprefeitura.CdPref, tbagendacons.valor 
			FROM tbagendacons, tbsolcons, tbpaciente, 
			tbprefeitura,tbbairro
			WHERE tbsolcons.CdSolCons = tbagendacons.CdSolCons
			AND tbsolcons.CdPaciente = tbpaciente.CdPaciente
			AND tbprefeitura.CdPref = tbbairro.CdPref
			AND tbbairro.CdBairro = tbpaciente.CdBairro
			AND tbsolcons.CdSolCons=$cd ");
			
			$l = mysqli_fetch_array($sql1);
			
			$valor = $l['valor'];
			$CdPref = $l['CdPref'];
		
			$sql3 = mysqli_query($db,"INSERT INTO `tbmovimentacao` (CdPref, CdUsuario, CdSolCons, TpMov, Credito)
			VALUES ('$CdPref', '$_SESSION[CdUsuario]', '$cd', '2', '$valor')"); 
		
		if($sql)
				{
					echo '<script language="JavaScript" type="text/javascript"> 
						alert("Cancelamento realizado com sucesso");
						window.location.href="?i=27";				
					  </script>';
				}
}  */
	
	

/* Parametros */
	
	$data = date('d/m/Y');

	function FormataDataBD($data){
		if ($data == '')
			return '';
		$data_f = explode('/',$data);
		return $data_f[2].'-'.$data_f[1].'-'.$data_f[0];
	}
	
	function FormataDataBR($data){
		if ($data == '')
			return '';
		$data_f = explode('-',$data);
		return $data_f[2].'/'.$data_f[1].'/'.$data_f[0];
	}
	
	
			$cd_forn = $_GET['cd_forn'];
			$cd_pref = $_GET['cd_pref'];
	
			if($cd_forn ==0 ) {$condf = ""; } else { $condf = "AND f.CdForn ='$cd_forn'";  $condf2 = "WHERE f.CdForn ='$cd_forn'";  }
			if($cd_pref ==0 ) {$condc = " AND pr.consorciado='S' "; } else { $condc = "AND pr.CdPref='$cd_pref' AND pr.consorciado='S'";}
			
			
	
			$dtinicio = $_GET['dtinicio'];
			$dttermino = $_GET['dttermino'];
			
			$data1 = FormataDataBD($dtinicio);
			$data2 = FormataDataBD($dttermino);
				
				
			$cdrelfat = $_GET['cdrelfat'];
	
		 function moeda($get_valor) {
					$source = array('.', ','); 
					$replace = array('', '.');
					$valor = str_replace($source, $replace, $get_valor); //remove os pontos e substitui a virgula pelo ponto
					return $valor; //retorna o valor formatado para gravar no banco
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
	$("input[id=valor_sus]").maskMoney({symbol:"R$",decimal:",",thousands:"."});
	$("input[id=valor]").maskMoney({symbol:"R$",decimal:",",thousands:"."}); 
});
</script>


<body style="background:none">
<style> table td { height:20px;  } </style>

<div id="fat" style="padding:15px;">

 <!-- <h1 style="margin-bottom:2px;"> <img src="imagens/realizado.gif" width="10" height="10" />  Controle &raquo; Faturamento <strong>(Procedimentos Realizados ) </strong></h1>  -->
<h1 style="margin-bottom:1px;" >   Controle Faturamento <?php ?> </h1>
<h1 style="margin-bottom:0px;">   Periodo: <?php  echo $_GET['dtinicio']." à ".$_GET['dttermino'];        ?> </h1>

  <?php 
  	$sql2 = mysqli_query($db,"SELECT  ac.CdSolCons, ac.DtAgCons,HoraAgCons,f.CdForn, f.NmForn, proc.CdProcedimento, ac.qts, ac.valor_fornecedor,
	proc.NmProcedimento, ep.CdEspecProc, ep.NmEspecProc, p.CdPaciente,p.NmPaciente,   
							  ac.valor_pactuado, ac.valor_n_pactuado, sc.pactuacao, sc.status as ssc, ac.status as sac 
							  
	FROM tbsolcons sc INNER JOIN tbpaciente p ON sc.CdPaciente=p.CdPaciente 
	INNER JOIN tbbairro b ON b.CdBairro=p.CdBairro
	INNER JOIN tbprefeitura pr ON b.CdPref=pr.CdPref
	INNER JOIN tbespecproc ep ON sc.CdEspecProc=ep.CdEspecProc
	INNER JOIN tbprocedimento proc ON ep.CdProcedimento=proc.CdProcedimento
	LEFT JOIN tbagendacons ac ON sc.CdSolCons=ac.CdSolCons
	LEFT JOIN tbfornecedor f ON ac.CdForn=f.CdForn
	WHERE ((sc.Status='1' AND ac.Status='2') or (sc.Status='1' AND ac.Status='1' )) 

							  $condf
							  $condc
							  AND LEFT(DtAgCons,10) BETWEEN '$data1' AND '$data2'
							  ");

  
	$n = mysqli_num_rows($sql2);
    $i = 0;


if($n>0)
{
	echo "<table id='table'>
	  <tr>
		<th width='24%'> CONFIRMAR </th>
		<th width='24%'> PACIENTE </th>
		<th width='9%'> ESPECIFICACAO </th>
		<th width='7%'>PACTUACAO </th>
		<th width='8%'> DATA      </th>
		<th width='7%'> HORA      </th>
		<th width='6%' > FORNECEDOR </th>
		<th width='13%'> QTDE </th>
		<th width='13%'> VALOR </th>
		<th width='10%'> VALOR SUS </th>
		<th width='10%'> VALOR FORNECEDOR </th>
	  </tr>";
		
	
	echo "<form action=\"oi.php\" name=\"frm_alterar\" id=\"frm_alterar\" method=\"post\">";
	
	while($l = mysqli_fetch_array($sql2))
	{
		
	$i++;
	
  	$CdFornac = $l[CdForn];
  	$CdEspecProc = $l[CdEspecProc];
  ?>
  
  <tr>
    <input type="hidden" name="CdSolCons" id="CdSolCons"  value="<?php echo $l[CdSolCons][$i] ?>"  style="text-transform:uppercase; height:30px; padding-left:5px; font-size:15px; width:50px;"  />   
    <input type="hidden" name="CdPaciente" id='CdPaciente'   value="<?php echo $l[CdPaciente] ?>"  style="text-transform:uppercase; height:30px; padding-left:5px; font-size:15px; width:70px;" /> 
     


    <td  style="text-align:center"  >
     
    <?php 
	// marcado 
   	if(($l[ssc]== 1) and ($l[sac] == 1)) {
	?>
     <input type="checkbox" name="confirmar" id='confirmar' value="marcado"  onChange="ajax.engine('check', this,'confirmar','<?php echo $l[CdSolCons]; ?>');" />	
	<?php
		
	}
	// realizado
   	if(($l[ssc]== 1) and ($l[sac] == 2)) {
	?>
     <input type="checkbox" name="confirmar" id='confirmar' value="realizado" checked='checked' onChange="ajax.engine('check', this,'confirmar','<?php echo $l[CdSolCons]; ?>');" />	
	<?php
	}

    ?>
     
   
     </td>
    <td> <input type="text" name="NmPaciente" id='NmPaciente' value="<?php echo $l[NmPaciente] ?>" onBlur="ajax.engine('texto', this,'NmPaciente','<?php echo $l[CdSolCons]; ?>');" class="gr" style="text-transform:uppercase; height:30px; padding-left:5px; font-size:15px; width:300px;" />   </td>
   
   <td style="text-transform:uppercase; ">   	
   <?php  
   
     $sql = "SELECT DISTINCT tbespecproc.CdEspecProc, tbespecproc.NmEspecProc
			  FROM tbespecproc  LEFT JOIN tbsolcons ON tbsolcons.CdEspecProc = tbespecproc.CdEspecProc";
				   $qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=inicial','lista_agendamento:consulta forn'));
				   echo "<select id=\"CdEspecProc\" name=\"CdEspecProc\" 
				   class='required' $disabled onChange=\"ajax.engine('select', this,'CdEspecProc','".$l[CdSolCons]."');\" style='text-transform:uppercase; height:30px; padding-left:5px; font-size:15px; width:400px;'>";
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
    
    <td style="text-align:center"> 
	<?php 
 	   $pactuacao = $l[pactuacao];
 	
	   if($pactuacao ==1)  { $checked = "checked='checked'";	$vl = 1; }
 	   if($pactuacao ==0) { $checked = " ";	 $vl = 0; }
    ?> 
    	<input type="checkbox" id="pactuacao" name="pactuacao" value="<?php echo $vl ?>" <?php echo $checked ?> onChange="ajax.engine('check', this,'pactuacao','<?php echo $l[CdSolCons]; ?>');" />        
         
     </td>

	<?php  $DtAgCons = FormataDataBR($l[DtAgCons]); ?> 
	 <td>  <input type='text' style="text-align:center; font-size:15px; width:80px;" name='DtAgCons' id='DtAgCons' value='<?php echo $DtAgCons ?>' onBlur="ajax.engine('texto', this,'DtAgCons','<?php echo $l[CdSolCons]; ?>');" /> </td>
   
   
    <td> <?php $HoraAgCons = $l[HoraAgCons]; ?> <input type='text' name='HoraAgCons' style="text-align:center; font-size:15px; width:50px;" id='HoraAgCons'   
    value='<?php echo $HoraAgCons ?>' onBlur="ajax.engine('texto', this,'HoraAgCons');" /> </td>
    
   <td >  
  <?php $sql = "SELECT DISTINCT f.CdForn,f.NmForn,f.NmReduzido
				FROM tbfornecedor f LEFT JOIN tbfornespec fe ON f.CdForn=fe.CdForn
				ORDER BY NmForn
				";
   
				   $qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=inicial','lista_agendamento:consulta forn'));
				   echo "<select id=\"select_forne\" name=\"select_forne\" class='required' $disabled  style='text-transform:uppercase; height:30px; padding-left:5px; font-size:15px; width:400px;' onChange=\"ajax.engine('select', this,'select_forne','".$l[CdSolCons]."');\">";
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

    <td style="text-align:center"> <input type="text" style="font-size:15px; width:100px;" name='qts' id='qts' value="<?php echo $l[qts] ?>" onBlur="ajax.engine('texto', this,'qts','<?php echo $l[CdSolCons]; ?>');" /> </td>
    <td style="text-align:center"> <input type="text" style="font-size:15px; width:100px;" name='valor_pactuado' id='valor_pactuado' value="<?php echo $l[valor_pactuado] ?>" onBlur="ajax.engine('texto', this,'valor_pactuado','<?php echo $l[CdSolCons]; ?>');"  /> </td>
    <td style="text-align:center"> <input type="text" style="font-size:15px; width:100px;" name='valor_n_pactuado' id='valor_n_pactuado' value="<?php echo $l[valor_n_pactuado] ?>" onBlur="ajax.engine('texto', this,'valor_n_pactuado','<?php echo $l[CdSolCons]; ?>');" /> </td>
   
    <td style="text-align:center"> <input type="text" style="font-size:15px; width:100px;" name='valor_fornecedor' id='valor_fornecedor' value="<?php echo $l[valor_fornecedor] ?>" onBlur="ajax.engine('texto', this,'valor_fornecedor','<?php echo $l[CdSolCons]; ?>');" /> </td>
  
  
  
  </tr>
<?php 
	}
?>
  <tr>
    <td colspan="12">
   <!--      <div id="btns">
     <?php  echo "<input type=\"button\" style=\"margin-top: 14px; margin-left: 249px; padding: 6px; margin-right: 20px; background:#FFFFFF; border:#CCCCCC solid 1px; cursor:pointer\" name=\"BtnSalv\" value=\"Salvar\"  onClick=\"javascript: window.document.getElementById('frm_agd_$ijk').submit();\" >"; ?>
        </div>--> 
    </td>
  </tr>
</table>
</form>
</div>


<?php } else { echo "Nenhum Faturamento encontrado"; }?>