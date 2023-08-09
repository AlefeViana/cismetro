<?php

/*
Desenvolvido por: Vanessa Schissato
Data: 12/12/2005
Calendario dinamico com navegacao pelos meses
*/
/*

	Change log
	v1.2  7/10/2006 Fabio Issamu Oshiro
	As datas come�am pelo Domingo
	Hash de Feriados incluindo a P�scoa, Carnaval e outras datas m�veis
*/
/* Adaptado para agenda m�dica por Juarez Ribeiro --> 25/01/2013 */
?>
<script type="text/javascript" language="javascript">
var dia,op_ant;
/* Os navegadores retornam as cores em RGB, para compararmos setaremos esses padr�es nas vari�veis */
//var vermelho = "rgb(255, 83, 83)";
var verde = "rgb(102, 255, 51)";
var branco = "rgb(248, 248, 248)";

	function atualiza(d,m,ano,flag,cdforn,cdprof){
		//alert('Passar para: '+flag);
		
		try{
			xmlhttp = new XMLHttpRequest();
		  }catch(ee){
			try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
			}catch(e){
				try{
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}catch(E){
					xmlhttp = false;
				}
			}
		  }	
		
		xmlhttp.open("GET", "atualiza_calendario.php?var1="+d+"&var2="+m+"&var3="+flag+"&var4="+cdforn+"&var5="+cdprof+"&var6="+ano,true);
		xmlhttp.onreadystatechange=function() {
		  if (xmlhttp.readyState == 4){
			  //document.getElementById('msg').innerHTML = "Salvando..";
		    if ( xmlhttp.status == 200) {
			   //document.getElementById('msg').innerHTML = "Salvo..";
			   
			    //alert(xmlhttp.responseText);
				
				if(flag == "0"){
					document.getElementById('dia_'+d).style.display = " table-row";
					document.getElementById('dia_'+d+'_1').style.display = " table-row";
				}else if(flag == "2"){
					document.getElementById('dia_'+d).style.display = " none";
					document.getElementById('dia_'+d+'_1').style.display = " none";
				}
			   	
				if(xmlhttp.responseText == 'resetar'){
					window.location.reload();
				}else{
					document.getElementById('msg').innerHTML = xmlhttp.responseText;
				}
				
				//op_msg  = xmlhttp.responseText;
				//res_msg = op_msg.split('|', 4);
			}
		  }
		}
		xmlhttp.send(null);

	}

function mudar_cor_over(celula,d,m,ano,flag,cdforn,cdprof){
	//alert(celula.style.backgroundColor);
	//alert("Atual: "+flag);
	if(celula.style.backgroundColor == verde) {
		if(confirm('Deseja deletar a data selecionada?')){
   			celula.style.backgroundColor = "#F8F8F8";
			atualiza(d,m,ano,2,cdforn,cdprof);
		}
		//alert("VERDE, mudar para BRANCO");
	}else
	/*if(celula.style.backgroundColor == vermelho){
		celula.style.backgroundColor = "#F8F8F8";
		atualiza(d,m,ano,2,cdforn,cdprof);
		//alert("op2");
	}else*/
	if(celula.style.backgroundColor == "" || celula.style.backgroundColor == branco){
		celula.style.backgroundColor = "#66ff33";
		atualiza(d,m,ano,0,cdforn,cdprof);
		//alert("BRANCO, mudar para VERDE");
	}	

} 
function mudar_cor_out(celula){ 
   //celula.style.backgroundColor="#dddddd" 
} 


function replicar_todas(){
	
	var dia_inicio = document.getElementById('dia_inicio').value;
	
	if(dia_inicio != '' && dia_inicio != '0'){
		var combo_1 = document.getElementById('cmb_per['+dia_inicio+']').value;
		var quant_1 = document.getElementById('txt_qtd['+dia_inicio+']').value;
		var espec_1 = document.getElementById('cmb_espec['+dia_inicio+']').value;
		var horas_1 = document.getElementById('txt_hora['+dia_inicio+']').value;
		var inter_1 = document.getElementById('txt_inter['+dia_inicio+']').value; 
		
		var combo_2 = document.getElementById('cmb_per2['+dia_inicio+']').value;
		var quant_2 = document.getElementById('txt_qtd2['+dia_inicio+']').value;
		var espec_2 = document.getElementById('cmb_espec2['+dia_inicio+']').value;
		var horas_2 = document.getElementById('txt_hora2['+dia_inicio+']').value;
		var inter_2 = document.getElementById('txt_inter2['+dia_inicio+']').value; 
		
		
		//alert('dia_inicio: '+dia_inicio+' - combo_1: '+combo_1+' - quant_1: '+quant_1+' - espec_1: '+espec_1+' - horas_1: '+horas_1+' - inter_1: '+inter_1);   
		//alert('dia_inicio: '+dia_inicio+' - combo_2: '+combo_2+' - quant_2: '+quant_2+' - espec_2: '+espec_2+' - horas_2: '+horas_2+' - inter_2: '+inter_2);   
		
		for(var i=1; i<=31; i++){
			//alert(i+': '+combo_1);
			//alert(document.getElementById('txt_hora['+i+']').value);
			if(document.getElementById('dia_'+i).style.display != "none"){
				document.getElementById('cmb_per['+i+']').value    = combo_1;
				document.getElementById('txt_qtd['+i+']').value    = quant_1;
				document.getElementById('cmb_espec['+i+']').value  = espec_1;
				document.getElementById('txt_hora['+i+']').value   = horas_1;
				document.getElementById('txt_inter['+i+']').value  = inter_1;
			}
			
			if(document.getElementById('dia_'+i+'_1').style.display != "none"){
				document.getElementById('cmb_per2['+i+']').value   = combo_2;
				document.getElementById('txt_qtd2['+i+']').value   = quant_2;
				document.getElementById('cmb_espec2['+i+']').value = espec_2;
				document.getElementById('txt_hora2['+i+']').value  = horas_2;
				document.getElementById('txt_inter2['+i+']').value = inter_2;
			}
		}
		
	}
}

</script>

<BODY link=black vlink=black alink=black onselectstart="return false">
<?php

//gera calendario
	echo calendario();

	function UltimoDia(){
		   $mes_alt = $_GET['mes'];
		   /*if(!isset($mes_alt)){
		   		$mes = date("m");
		   }else {
		   		$mes = $mes_alt;
		   }*/
		   $ano = date("Y");
	 
	   if (((fmod($ano,4)==0) and (fmod($ano,100)!=0)) or (fmod($ano,400)==0)) { 
		   $dias_fevereiro = 29; 
	   } else { 
		   $dias_fevereiro = 28; 
	   } 
	   switch($mes) { 
		   case 01: return 31; break; 
		   case 02: return $dias_fevereiro; break; 
		   case 03: return 31; break; 
		   case 04: return 30; break; 
		   case 05: return 31; break; 
		   case 06: return 30; break; 
		   case 07: return 31; break; 
		   case 08: return 31; break; 
		   case 09: return 30; break; 
		   case 10: return 31; break; 
		   case 11: return 30; break; 
		   case 12: return 31; break; 
	   } 
	}

	function dia_pascoa($a){
		//fabioissamu@yahoo.com Fabio Issamu Oshiro
		//retorna a p�scoa
		if ($a<1900){$a+=1900;}
		$c = floor($a/100);
		$n = $a - (19*floor($a/19));
		$k = floor(($c - 17)/25);
		$i = $c - $c/4 - floor(($c-$k)/3) +(19*$n) + 15;
		$i = $i - (30*floor($i/30));
		$i = $i - (floor($i/28)*(1-floor($i/28))*floor(29/($i+1))*floor((21-$n)/11));
		$j = $a + floor($a/4) + $i + 2 -c + floor($c/4);
		$j = $j - (7* floor($j/7));
		$l = $i - $j;
		$m = 3 + floor(($l+40)/44);
		$d = $l + 28 - (31*floor($m/4));
		$retorno=mktime(0, 0, 0, $m, $d-1, $a);
		return $retorno;
	}

function calendario(){

	//Vari�vel de retorno do c�digo em HTML
	$retorno="";

	//Primeira linha do calend�rio
	$arr_dias=Array("DOM","SEG","TER","QUA","QUI","SEX","S�B");

	//Deseja iniciar pelo s�bado?
	$ini_sabado=false;


	//Feriados comuns
	$feriados["1-1"]="Confraterniza��o Universal";
	$feriados["21-4"]="Tiradentes";
	$feriados["15-11"]="Proclama��o da Rep�blica";
	$feriados["2-11"]="Finados";
	$feriados["1-5"]="Dia do Trabalho";
	$feriados["7-9"]="Dia da Independ�ncia";
	$feriados["12-10"]="N.S. Aparecida";
	//$feriados["15-10"]="Dia dos Professores";
	$feriados["25-12"]="Natal";

	//mes e ano do calendario a ser montado
	If($_GET['mes'] and $_GET['ano'])
	{
	   $mes = $_GET['mes'];
	  
	   $ano = $_GET['ano'];
	}
	Else
	{
	   $mes = date("m");
	   $ano = date("Y");
	}

	//Feriados com data mutante
	$pascoa=dia_pascoa($ano);
	$feriados[date("j-n", $pascoa)]="P�scoa";
	$feriados[date("j-n", $pascoa-86400*2)]="Paix�o";
	$feriados[date("j-n", $pascoa-86400*46)]="Cinzas";
	$feriados[date("j-n", $pascoa-86400*47)]="Carnaval";
	$feriados[date("j-n", $pascoa+86400*60)]="Corpus Christi";

	$cont_mes = 1; 
	if ($ini_sabado){
		$dia_semana = converte_dia(date("w", mktime(0, 0, 0, $mes, 1, $ano))); //dia da semana do primeiro dia do mes
	}else{
		//Comum
		$dia_semana = date("w", mktime(0, 0, 0, $mes, 1, $ano)); 
	}
	$t_mes = date("t", mktime(0, 0, 0, $mes, 1, $ano)); //no. total de dias no mes

	//dados do mes passado
	$dia_semana_ant = ((date("d", mktime(0, 0, 0, $mes, 0, $ano))+1)-$dia_semana); 
	$mes_ant = date("m", mktime(0, 0, 0, $mes, 0, $ano));
	$ano_ant = date("Y", mktime(0, 0, 0, $mes, 0, $ano));

	//dados do mes seguinte
	$dia_semana_post = 1;
	$mes_post = date("m", mktime(0, 0, 0, $mes, $t_mes+1, $ano));  
	$ano_post = date("Y", mktime(0, 0, 0, $mes, $t_mes+1, $ano));
	$sql_p = "SELECT nmprof FROM tbprofissional WHERE cdprof = '$_GET[cdprof]'";
	$sql_p = mysqli_query($db,$sql_p) or die("erro sqlp");  
	$l1 = mysqli_fetch_array($sql_p);
	echo "<h1>Agenda Profissional: $l1[nmprof]</h1>";
	echo "<div id='msg'></div><br />";
	
	$retorno.="<center>";

	//titulo do calendario
	$retorno.= "<font style=\"font-family:verdana,arial,serif;font-size:16\"><b>Calend&#225;rio: ".converte_mes($mes)."/".$ano."</b></font><br>";

	//montagem do calendario
	$retorno.= "<table><tr><td>&nbsp;</td><td>";

	$retorno.= "<table border=1 width=580 cellpadding=5 cellspacing=5 style='border-collapse: collapse' id=AutoNumber1 bordercolor=#333333>";
	//primeira linha do calendario
	$retorno.= "<tr bgcolor=#B5B5B5 face=verdana,arial,serif>";
	for($i=0;$i<7;$i++){
		if ($i==0 || $i==6){
			//� domingo ou s�bado
			$retorno.= "<td bgcolor=#0066FF><font color=#EEEEEE face=verdana,arial,serif><strong>$arr_dias[$i]</strong></font></td>";
		}else{
			$retorno.= "<td><font color=#EEEEEE face=verdana,arial,serif><strong>$arr_dias[$i]</strong></font></td>";
		}
	}
	$cont_cor = 0;
	While ($t_mes >= $cont_mes)
	{
	   $cont_semana = 0;
	   $retorno.= "<tr>";
	   If ($dia_semana == 7)
	   {
		  $dia_semana = 0;
	   }
	   If(($cont_cor%2)!=0) //alterna cor das linhas
	   {
		  $cor = "#F0F0F0";
	   }
	   Else
	   {
		  $cor = "#F8F8F8";
	   }
	   
	   While ($dia_semana < 7)
	   {
		  If ($cont_mes <= $t_mes)
		  {
			 If ($dia_semana == $cont_semana) //celulas de dias do mes
			 {
				include("conecta.php");
				
				$dia_mes = str_pad($cont_mes, 2, '0', STR_PAD_LEFT);
				
				$sql = "SELECT op FROM tbdias_aten WHERE data = '$ano-$mes-$dia_mes' AND cdforn = '$_GET[cdforn]' AND cdprof = '$_GET[cdprof]'";
				//echo $sql."<br />";
				
				$cor = "#F8F8F8";
				
				//$mes_selecionado = array();
				
				$sql = mysqli_query($db,$sql) or die("erro");
				$l = mysqli_fetch_array($sql);
				if($l[op] == "0"){
					$cor = "#66ff33";
					//echo $cont_mes."-";
					$mes_selecionado[].=$cont_mes;
					//array_push($mes_selecionado, $cont_mes);
				}else if($l[op] == "1"){
					$cor = "#FF5353";
				}else $l[op] = 2;
					//$cor = "#F8F8F8";
					
					

					$retorno.= "<td valign='top' style='background-color:".$cor."' width=110 height=70 onclick=\"mudar_cor_over(this,$cont_mes,$mes,$ano,$l[op],$_GET[cdforn],$_GET[cdprof])\">";
					$retorno.= "<font face=verdana,arial,serif size=2><b>".$cont_mes."</b>";

				/************************************************************/
				/******** Conteudo do calendario, se tiver, aqui!!!! ********/ 
				/************************************************************/
				$nome_feriado=$feriados[$cont_mes."-".((int)$mes)];
				if ($nome_feriado!=""){
					$retorno.= "<br>" . $nome_feriado;
				}
				$retorno.= "</font></td>";
				$cont_mes++;
				$dia_semana++;
				$cont_semana++;
			 }
			 Else //celulas vazias no inicio (mes anterior)
			 {
				$cor = "#F8F8F8";
				$retorno.= "<td valign=top bgcolor=".$cor.">";
				$retorno.= "<font color=#AAAAAA face=verdana,arial,serif size=2>".$dia_semana_ant."</font>";
				$retorno.= "</td>";
				$cont_semana++;    
				$dia_semana_ant++;
			 }
		  }
		  Else
		  {
				While ($cont_semana < 7) //celulas vazias no fim (mes posterior)
				{
					$cor = "#F8F8F8";
					$retorno.= "<td valign=top bgcolor=".$cor.">";
					$retorno.= "<font color=#AAAAAA face=verdana,arial,serif size=2>".$dia_semana_post."</font>";
					$retorno.= "</td>";
					$cont_semana++;    
					$dia_semana_post++;
				}
		 break 2;   
		  }
	   }
	   $retorno.= "</tr>";
	   $cont_cor++;
	}

	$retorno.= "</table>";

	$retorno.= "</td></tr></table>";


	$retorno.= "<br>";
	
	$sqldia = mysqli_query($db,"SELECT capdia FROM tbmes WHERE cdmes = $mes") or die("Erro SqlDia");
	$ld = mysqli_fetch_array($sqldia);
	
	/*$retorno .= "<form action='' name='frm1' id='frm1' method='post'><input type='hidden' name='mes' value='$mes' /><input type='hidden' name='cd' value='$_GET[cdforn]-$_GET[cdprof]-$_GET[mes]-$_GET[ano]' />
				<label style='width:190px'><font style=\"font-family:verdana,arial,serif;font-size:12\">Quant. de atendimentos dia: </font></label><label class='pq'><input type='text' name='qtd_dia' value='$ld[capdia]' /></label>
				 <div id='btns'><input type='submit' value='Salvar' onClick=\"frm1.action='salva_dia.php'\" /></div>
				</form>";*/

	//links para mes anterior e mes posterior
	$retorno.= "<table width=100%><tr><td width=50% align=right>";
	$retorno.= "<font style=\"font-family:verdana,arial,serif;font-size:12\">M&#234;s anterior: <a href=".$_SERVER['PHP_SELF']."?i=5&s=pgforn&cdforn=$_GET[cdforn]&cdprof=$_GET[cdprof]&f=d&mes=".$mes_ant."&ano=".$ano_ant." class=estilo1>".converte_mes($mes_ant)."/".$ano_ant."</a></font></td>";
	$retorno.= "<td>  </td><td width=50%>";
	$retorno.= "<font style=\"font-family:verdana,arial,serif;font-size:12\">M&#234;s posterior: <a href=".$_SERVER['PHP_SELF']."?i=5&s=pgforn&cdforn=$_GET[cdforn]&cdprof=$_GET[cdprof]&f=d&mes=".$mes_post."&ano=".$ano_post." class=estilo1>".converte_mes($mes_post)."/".$ano_post."</a></font>";
	$retorno.= "</td></tr></table>";

	//formulario para escolha de uma data
	$retorno.= "<form method=get action=".$_SERVER['PHP_SELF'].">";
	$retorno.= "</form>";
	
	$retorno.= "<br /><br /><br /><br />";
	$retorno.="<form method='post' action='' name='frm1' id='frm1' >";
	$retorno.= "<input type='hidden' name='cdforn' value='$_GET[cdforn]' />";
	$retorno.= "<input type='hidden' name='cdprof' value='$_GET[cdprof]' />";
	
	$retorno.= "<table id='tb2' border='1' valign='middle' width='580' cellpadding='0' cellspacing='0' style='border-collapse: collapse'>";
    $retorno.= "<th colspan='2' align='center'>Dia C�pia Agendas: <input type='text' id='dia_inicio' name='dia_inicio' size='13'></th><th colspan='2' aling='left'><div id='btns'><a href='javascript: void(0);' onclick='javascript: replicar_todas();' ><input type='button' name='check_replicar' value='Replicar Agendas'></a></div>
				<th>
					<div id='btns'>
						<input type='submit' name='btn_salvar' value='Gravar Agendas' onClick=\"frm1.action='pg/gravar_agenda.php'\" />
						<a href='pg/liberar_agendas.php?mes=$mes&cdforn=$_GET[cdforn]&cdprof=$_GET[cdprof]'><input type='button' name='btn1' value='Liberar Agendas'></a>
						<a href='pg/termo_comp.php?mes=$mes&cdprof=$_GET[cdprof]&cdforn=$_GET[cdforn]&ano=$ano' target='_blank'><input type='button' name='btn' value='Termo'></a>	
					</div>
				</th>";
	$retorno.= "</table>";
	$retorno.= "<br>";
	$retorno.= "<table id='tb1' border='1' width='580' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#333333'>";
    $retorno.= "<th>Dia</th><th>Per�odo</th><th>Quantidade</th><th>Espec.</th><th>Hora inicial</th><th>Intervalo</th>";
	$ultimo_dia = UltimoDia();
    
	$retorno.= "<input type='hidden' id='t_ultimo_dia' name='t_ultimo_dia' value='$ultimo_dia' />";

	for ($j=1; $j<=$ultimo_dia; $j++){
		$ld = ""; $dis = "";
		if(count($mes_selecionado) <= 0){
			$retorno.= "<tr id='dia_".$j."' style='display: none;'>";
		}elseif(in_array($j, $mes_selecionado)){
		//if(in_array($j, $mes_selecionado)){
			$retorno.= "<tr id='dia_".$j."' style='display: table-row;'>";
			#Busca os dados da agenda m�dica
			$dados = "SELECT *
					  FROM tbdias_aten AS da
					  WHERE da.data = '$ano-$mes-$j' AND da.cdforn = '$_GET[cdforn]' AND da.cdprof='$_GET[cdprof]'";
			$dados = mysqli_query($db,$dados) or die("Erro: ".mysqli_error());
			if(mysqli_num_rows($dados) > 0)
				$dis =  "disabled";
			$ld = mysqli_fetch_array($dados);			
		}else {
			$retorno.= "<tr id='dia_".$j."' style='display: none;'>";
		}
		
		$retorno.= "<input type='hidden' name='data[$j]' value='$ano-$mes-$j' />";
		$retorno.= "<td width='7%' align='center' bgcolor='#66ff33' style='font-weight: bold;' rowspan='2'>".$j."</td>";
		$selectm = ""; $selectt = "";
	    if($ld[periodo1] == "M")
			$selectm = "selected='selected'";
		elseif($ld[periodo1] == "T")
			$selectt = "selected='selected'";		
		$retorno.= "<td width='23%'><select id='cmb_per[$j]' name='cmb_per[$j]' style='height:30px;' $dis><option value=''>Selecione...</option><option value='M' $selectm>Manh�</option><option value='T' $selectt>Tarde</option></select></td>";
		$retorno.= "<td width='23%'><label class='pq'><input type='text' id='txt_qtd[$j]' name='txt_qtd[$j]' value='$ld[qtdp1]' $dis/></label></td>";
		$sql_esp = "SELECT fespec.CdForn,fespec.CdEspec,fespec.cdprof,ep.NmEspecProc,tbespecproc.CdProcedimento
					FROM tbfornespec AS fespec
					Inner Join tbespecproc AS ep ON ep.CdEspecProc = fespec.CdEspec
					Inner Join tbespecproc ON tbespecproc.CdEspecProc = fespec.CdEspec
					WHERE fespec.CdForn = $_GET[cdforn]
					AND fespec.`Status` =  1";
		$sql_esp = mysqli_query($db,$sql_esp) or die("Erro ao selecionar especialidades!");
		$ret1 = "<select id='cmb_espec[$j]' name='cmb_espec[$j]' style='height:30px;' $dis>";
		while($lesp = mysqli_fetch_array($sql_esp)){
			if($lesp[CdEspec] == $ld[cdespec1])
				$ret1 .= "<option value='$lesp[CdEspec]' selected='selected'>$lesp[NmEspecProc]</option>";
			else
				$ret1 .= "<option value='$lesp[CdEspec]'>$lesp[NmEspecProc]</option>";
		}
		$ret1 .="</select>";
		$retorno.= "<td width='53%'>$ret1</td>";
		$retorno.= "<td width='33%'><input type='time' name='txt_hora[$j]' id='txt_hora[$j]' value='$ld[horainiciop1]' $dis/></td>";
		$retorno.= "<td width='33%' align='center'><label class='pq'><input type='text' name='txt_inter[$j]' id='txt_inter[$j]' value='$ld[intervalo1]' style=\"width:50px\" $dis/></label></td>";
		$retorno.= "</tr>";	
		
		#LINHA DEBAIXO
		if(count($mes_selecionado) <= 0){
			$retorno.= "<tr id='dia_".$j."_1' style='display: none;'>";
		}elseif(in_array($j, $mes_selecionado)){
		//if(in_array($j, $mes_selecionado)){
			$retorno.= "<tr id='dia_".$j."_1' style='display: table-row;'>";
		}else {
			$retorno.= "<tr id='dia_".$j."_1' style='display: none;'>";
		}
		$selectm = ""; $selectt = "";
	    if($ld[periodo2] == "M")
			$selectm = "selected='selected'";
		elseif($ld[periodo2] == "T")
			$selectt = "selected='selected'";			
		$retorno.= "<td width='23%'><select id='cmb_per2[$j]' name='cmb_per2[$j]' style='height:30px;' $dis><option value=''>Selecione...</option><option value='M' $selectm>Manh�</option><option value='T' $selectt>Tarde</option></select></td>";
		$retorno.= "<td width='23%'><label class='pq'><input type='text' id='txt_qtd2[$j]' name='txt_qtd2[$j]' value='$ld[qtdp2]' $dis/></label></td>";
		$sql_esp = "SELECT fespec.CdForn,fespec.CdEspec,fespec.cdprof,ep.NmEspecProc
					FROM tbfornespec AS fespec
					Inner Join tbespecproc AS ep ON ep.CdEspecProc = fespec.CdEspec
					WHERE fespec.CdForn =  $_GET[cdforn]
					AND fespec.`Status` =  1";
		$sql_esp = mysqli_query($db,$sql_esp) or die("Erro ao selecionar especialidades!");
		$ret1 = "<select id='cmb_espec2[$j]' name='cmb_espec2[$j]' style='height:30px;' $dis>";
		while($lesp = mysqli_fetch_array($sql_esp)){
			if($lesp[CdEspec] == $ld[cdespec2])
				$ret1 .= "<option value='$lesp[CdEspec]' selected='selected'>$lesp[NmEspecProc]</option>";
			else
				$ret1 .= "<option value='$lesp[CdEspec]'>$lesp[NmEspecProc]</option>";
		}
		echo "</select>";
		$retorno.= "<td width='53%'>$ret1</td>";
		$retorno.= "<td width='33%'><input type='time' name='txt_hora2[$j]' id='txt_hora2[$j]' value='$ld[horainiciop2]' $dis/></td>";
		$retorno.= "<td width='33%' align='center'><label class='pq'><input type='text' name='txt_inter2[$j]' id='txt_inter2[$j]' value='$ld[intervalo2]' style=\"width:50px\" $dis/></label></td>";
		$retorno.= "</tr>";			
		
			
	}
		
	
	$retorno.= "</table>";
	$retorno.= "</center>";	
	$retorno.= "<br />";	
	/*$retorno.= "<div id='btns'>
				<input type='submit' name='btn_salvar' value='Gravar Agendas' onClick=\"frm1.action='pg/gravar_agenda.php'\" />
				<a href='pg/liberar_agendas.php?mes=$mes&cdforn=$_GET[cdforn]&cdprof=$_GET[cdprof]'><input type='button' name='btn1' value='Liberar Agendas'></a>
				<!-- input type='button' name='btn_corrigir' value='Corrigir Agendas' onClick='window.location.reload();' / -->
				<a href='pg/termo_comp.php?mes=$mes&cdprof=$_GET[cdprof]&cdforn=$_GET[cdforn]' target='_blank'><input type='button' name='btn' value='Termo'></a>
				</div>";*/
	$retorno.= "</form>";
	return $retorno;
}

Function converte_dia($dia_semana) //funcao para comecar a montar o calendario pela quarta-feira
{
   If($dia_semana == 0)
   {
      $dia_semana = 1;
   }
   ElseIf ($dia_semana == 1)
   {
      $dia_semana = 2;
   }
   ElseIf ($dia_semana == 2)
   {
      $dia_semana = 3;
   }
   ElseIf ($dia_semana == 3)
   {
      $dia_semana = 4;
   }
   ElseIf ($dia_semana == 4)
   {
      $dia_semana = 5;
   }
   ElseIf ($dia_semana == 5)
   {
      $dia_semana = 6;
   }
   ElseIf ($dia_semana == 6)
   {
      $dia_semana = 0;
   }

   return $dia_semana; 

}

Function converte_mes($mes)
{
         If($mes == 1)
         {
          $mes = "Janeiro";
         }
         ElseIf($mes == 2)
         {
          $mes = "Fevereiro";
         }
         ElseIf($mes == 3)
         {
          $mes = "Mar�o";
         }
         ElseIf($mes == 4)
         {
          $mes = "Abril";
         }
         ElseIf($mes == 5)
         {
          $mes = "Maio";
         }
         ElseIf($mes == 6)
         {
          $mes = "Junho";
         }
         ElseIf($mes == 7)
         {
          $mes = "Julho";
         }
         ElseIf($mes == 8)
         {
          $mes = "Agosto";
         }
         ElseIf($mes == 9)
         {
          $mes = "Setembro";
         }
         ElseIf($mes == 10)
         {
          $mes = "Outubro";
         }
         ElseIf($mes == 11)
         {
          $mes = "Novembro";
         }
         ElseIf($mes == 12)
         {
          $mes = "Dezembro";
         }
         return $mes;
}

?>
<br />
	<!--form action='' name='frm1' id='frm1' method='post'>
    	<input type='hidden' name='mes' value='$mes' />
        <input type='hidden' name='cd' value='$_GET[cdforn]-$_GET[cdprof]-$_GET[mes]-$_GET[ano]' />
		<label style='width:190px'><font style=\"font-family:verdana,arial,serif;font-size:12\">Quant. de atendimentos dia: </font></label>
        <label class='pq'><input type='text' name='qtd_dia' value='$ld[capdia]' /></label>
		<div id='btns'><input type='submit' value='Salvar' onClick=\"frm1.action='salva_dia.php'\" /></div>
	</form-->
</BODY>

