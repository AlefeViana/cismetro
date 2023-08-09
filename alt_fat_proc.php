<?php 
	session_start();
	include "funcoes.php"; 

	if($_GET[b]=="true")
	{
	   $busca    = $_REQUEST["pesq"];
	   $cbopor   = (int)$_REQUEST["cbopesq"];
		//echo $pesq;		
	}	
?>
<h1> Controle &raquo; Alterar Paciente </h1>

	<div id="pnl_pesq" style="clear:both; height:50px;" >
      <form action="index.php?i=51&b=true" method="post" id="frm1">
                        <input  type="text" name="pesq" value="Pesquisar..." onfocus="if(this.value=='Pesquisar...')this.value='';" 
                        onblur="if(this.value=='')this.value='Pesquisar...';" style=" float:left; padding:8px; border:#CCCCCC solid 1px; 
                        width:200px; font-style:italic; background:url(img/icon_lupa.jpg) no-repeat; padding-left:25px; " />
                        <select name="cbopesq" style="float:left; width:155px; height:35px; margin-left:5px; border:#999999 solid 1px; " >
                                                <option value="7" <?php if ($cbopor == 7) echo 'selected="selected"';?> >Cod</option>
                                                <option value="1" <?php if ($cbopor == 1) echo 'selected="selected"';?> >CIH</option>
                                                <option value="2" <?php if ($cbopor == 2 || $cbopor == "") echo 'selected="selected"';?> >Nome do Paciente</option>
                                                <option value="3" <?php if ($cbopor == 3) echo 'selected="selected"';?> >Nome do Fornecedor</option>
                                                <option value="4" <?php if ($cbopor == 4) echo 'selected="selected"';?> >Nome da Especificação </option>
                                                <option value="5" <?php if ($cbopor == 5) echo 'selected="selected"';?> >Nome do Procedimento </option> 
                                                <option value="6" <?php if ($cbopor == 6) echo 'selected="selected"';?> >Data Nascimento</option>
                                                <option value="8" <?php if ($cbopor == 8) echo 'selected="selected"';?> >Data	 </option>
                                             </select>	
                        
                                </select> 
        <input type="submit" value="Buscar" name="btnpesq" style="margin-left:5px; padding:8px; background:#FFFFFF; border:#CCCCCC solid 1px; cursor:pointer" />                 
      </form> 
    </div>
    
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
	$valormed   = $_POST['valormed'];
	$CdEspecProc = $_POST['CdEspecProc'];	
	$Obs = $_POST['Obs'];	
	$Obs1 = $_POST['Obs1'];
	
	$p= $_POST['p'];
	if (validafat_data($DtAgCons)) {
		if(validafat($CdSolCons[0],0,51))
		{
			for($j=0;$j<count($CdEspecProc);$j++)
			{
				$Data = FormataDataBD($DtAgCons[$j]);
				//$valor_sus[$j] = moeda($valor_sus[$j]);
				//$valor[$j] = moeda($valor[$j]);
				//$valormed[$j] = moeda($valormed[$j]);
				
				$sql_pactuacao = mysqli_query($db,"UPDATE `tbsolcons` SET CdEspecProc='$CdEspecProc[$j]',CdPaciente = '$CdPaciente[$j]', Obs='$Obs' , Obs1='$Obs1' WHERE (CdSolCons='$CdSolCons[$j]')") or die (mysqli_error());
			
				$sql_agenda  = mysqli_query($db,"UPDATE tbagendacons SET CdForn='$select_forne[$j]', DtAgCons='$Data', HoraAgCons='$HoraAgCons[$j]'
				WHERE (CdSolCons='$CdSolCons[$j]')") or die (mysqli_error()) ;
				
				$sqlusr = mysqli_query($db,"INSERT INTO tbusralt(cdusr,cdag,dtalt) VALUES('$_SESSION[CdUsuario]','$CdSolCons[$j]','".date("Y-m-j H:i:s")."')") or die(mysqli_error());

				//Tonometria
				if ($CdEspecProc[$j] == 44 ){
					$CdEspecProcVali = 21;
					$comboOftalmo = getComboOftalmo($CdSolCons[$j]);
					$CdSolConsTono = getCdSolConsComboOftalmo($comboOftalmo,$CdEspecProcVali);

					$sql_pactuacao = mysqli_query($db,"UPDATE `tbsolcons` SET CdPaciente = '$CdPaciente[$j]', Obs='$Obs' , Obs1='$Obs1' WHERE (CdSolCons='$CdSolConsTono')") or die (mysqli_error());
					$sql_agenda  = mysqli_query($db,"UPDATE tbagendacons SET CdForn='$select_forne[$j]', DtAgCons='$Data', HoraAgCons='$HoraAgCons[$j]'
					WHERE (CdSolCons='$CdSolConsTono')") or die (mysqli_error()) ;
					$sqlusr = mysqli_query($db,"INSERT INTO tbusralt(cdusr,cdag,dtalt) VALUES('$_SESSION[CdUsuario]','$CdSolConsTono','".date("Y-m-j H:i:s")."')") or die(mysqli_error());
				}
			}
			$CdSolCons = $_POST['CdSolCons'];
			$conf = $_POST['conf'];
			
			for($i=0;$i<=count($conf);$i++)
			{
				$sql_1 = mysqli_query($db,"UPDATE `tbsolcons` SET `Status`='1' WHERE (`CdSolCons`='$conf[$i]')") or die (mysqli_error());
				$sql_2 = mysqli_query($db,"UPDATE `tbagendacons` SET `Status`='2' WHERE (`CdSolCons`='$conf[$i]')") or die (mysqli_error());
				if ($CdEspecProc[$i] == 44 ){
					$comboOftalmo = getComboOftalmo($CdSolCons[$i]);
					$CdSolConsTono = getCdSolConsComboOftalmo($comboOftalmo,$CdEspecProcVali);
					$sql_1 = mysqli_query($db,"UPDATE `tbsolcons` SET `Status`='1' WHERE (`CdSolCons`='$CdSolConsTono')") or die (mysqli_error());
					$sql_2 = mysqli_query($db,"UPDATE `tbagendacons` SET `Status`='2' WHERE (`CdSolCons`='$CdSolConsTono')") or die (mysqli_error());
				}
			}
			$canc = $_POST['canc'];
			for($i=0;$i<=count($canc);$i++)
			{
				$sql_1 = mysqli_query($db,"UPDATE `tbsolcons` SET `Status`='2' WHERE (`CdSolCons`='$canc[$i]')") or die (mysqli_error());
				$sql_2 = mysqli_query($db,"UPDATE `tbagendacons` SET `Status`='1' WHERE (`CdSolCons`='$canc[$i]')") or die (mysqli_error());
				if ($CdEspecProc[$i] == 44 ){
					$comboOftalmo = getComboOftalmo($CdSolCons[$i]);
					$CdSolConsTono = getCdSolConsComboOftalmo($comboOftalmo,$CdEspecProcVali);
					$sql_1 = mysqli_query($db,"UPDATE `tbsolcons` SET `Status`='2' WHERE (`CdSolCons`='$CdSolConsTono')") or die (mysqli_error());
					$sql_2 = mysqli_query($db,"UPDATE `tbagendacons` SET `Status`='1' WHERE (`CdSolCons`='$CdSolConsTono')") or die (mysqli_error());
				}
			}
			echo "<div  style='padding:10px; color:#009933; font-size:20px;'> Dados alterados com sucesso! </div>";
			if($_POST[realizado] != "1"){
				echo "<div id='teste'> <a href=\"javascript:abrirpop('guia_pac.php?id=$CdSolCons[0]','','800','600','no'); \"  
				style=\" font-size:30px; color:#0C0;  margin:0 auto 0px;\" id='exibe'>
				<img src='img/btn_imprimir2.png' width='40' height='40' title='Imprimir Guia de Encaminhamento' />
				<strong style=\" font-size:20px; color:#0C0; margin:0 auto 0px;\">  Clique aqui para Imprirmir a Guia de Encaminhamento </strong> </a> </div>";	
			}
		}
	}
}
 
 ?>
    <?php include "conecta.php";  ?>
    
	<script type="text/javascript" src="js2/ui/minified/jquery.ui.datepicker.min.js"></script>
    <script type="text/javascript" src="js2/localization/jquery.ui.datepicker-pt-BR.js"></script>
    <link rel="stylesheet" href="css/themes/base/jquery.ui.datepicker.css">
    <link rel="stylesheet" href="css/themes/base/jquery.ui.theme.css">
    <link rel="stylesheet" href="css/themes/base/jquery.ui.all.css">	
        <script type="text/javascript"> 
            $(document).ready(function() {	
            $("#frm1").validate({});
			<?php if($_SESSION["cdgrusuario"] == 3 || $_SESSION["cdgrusuario"] == 1) { ?>
            		$( "#DtAgCons" ).datepicker( { showButtonPanel: true, nextText: '', prevText: '', changeMonth: true, changeYear: true, minDate: 0 } );
			<?php } ?>
            });
    </script>    
	<script type="text/javascript"> 
    jQuery(function($){
        $("input[id=DtAgCons]").mask("99/99/9999");
        //$("input[id=HoraAgCons]").mask("99:99:99");
        //$("input[id=valor]").maskMoney({symbol:"R$",decimal:",",thousands:"."});
        //$("input[id=valor_sus]").maskMoney({symbol:"R$",decimal:",",thousands:"."});
    });
    </script>
    
	    
    <style> table td { height:20px;  } </style>


<div id="fat" style="padding:15px;">

  <?php 
  if(isset($_GET[busca])){
  	$busca = $_GET[busca];
	$cbopor = $_GET[cbopor];
  }
  $pesq = "";
if ($busca != ""){
		switch ($cbopor){
			case 1: $pesq = " AND sc.CdPaciente = $busca";
					break;
			case 2: $pesq = " AND p.NmPaciente LIKE '$busca%'";
					break;
			case 3: $pesq = " AND f.NmForn LIKE '%$busca%'";
					break;	
			case 4: $pesq = " AND ep.NmEspecProc LIKE '$busca%'";
					break;								
			case 5: $pesq = " AND proc.NmProcedimento LIKE '$busca%'";
					break;							
			case 7: $pesq = " AND sc.CdSolCons = $busca";
					break;							
			case 6: 			
			$busca = explode("/",$busca);
					$dia = $busca[0];
					$mes = $busca[1];
					$ano =  $busca[2];
			$busca = $_REQUEST["pesq"];
				 	$pesq = "  AND YEAR(p.DtNasc)=$ano AND MONTH(p.DtNasc)=$mes AND DAY(p.DtNasc)=$dia";
					break;							
			case 8: 			
			$busca = explode("/",$busca);
					$dia = $busca[0];
					$mes = $busca[1];
					$ano =  $busca[2];
					
			$busca = $_REQUEST["pesq"];		
					 
				 	$pesq = "  AND YEAR(ac.DtAgCons) = $ano AND MONTH(ac.DtAgCons)=$mes AND DAY(ac.DtAgCons)=$dia";
					break;							
		}
    } 
	$a = ""; $b = ""; 

	$dias = getConfiguracao(5);//config('alt');
	if ($dias[valor] > 0) {
		$dt = " AND ac.DtAgCons >= '".date('Y-m-d', strtotime('today +'.$dias.' days'))."'";
	}else{
		$dt = " AND ac.DtAgCons >= '".date('Y-m-d')."'";
	}
   if($_SESSION["CdTpUsuario"] == "1"){
   		$a = "((sc.Status='1' AND ac.Status='1') or (sc.Status='1' AND ac.Status='2'))";   
   } else {
   		$a = "sc.Status='1' AND ac.Status='1'";
		$b = "AND pr.CdPref = $_SESSION[CdOrigem] AND ac.DtAgCons >= '".date('Y-m-d', strtotime('today +'.$dias.' days'))."' ";
   }
  
    $sql2 = "SELECT  ac.CdSolCons, ac.DtAgCons,HoraAgCons,f.CdForn, f.NmForn,f.NmReduzido, proc.CdProcedimento, ac.qts,
	proc.NmProcedimento, ep.CdEspecProc, ep.NmEspecProc, p.CdPaciente,p.NmPaciente,p.CdPaciente, ac.valor,ac.valor_sus,ac.valormed,
	sc.status as ssc, ac.status as sac , sc.Obs, sc.Obs1,p.DtNasc,pr.NmCidade,pr.CdPref
	FROM tbsolcons sc INNER JOIN tbpaciente p ON sc.CdPaciente=p.CdPaciente 
	INNER JOIN tbbairro b ON b.CdBairro=p.CdBairro
	INNER JOIN tbprefeitura pr ON b.CdPref=pr.CdPref
	INNER JOIN tbespecproc ep ON sc.CdEspecProc=ep.CdEspecProc
	INNER JOIN tbprocedimento proc ON ep.CdProcedimento=proc.CdProcedimento
	LEFT JOIN tbagendacons ac ON sc.CdSolCons=ac.CdSolCons
	LEFT JOIN tbfornecedor f ON ac.CdForn=f.CdForn
	WHERE $a
	$b
    $pesq
    ";
	
		
  	$sql1 = mysqli_query($db,$sql2);
	
	$n = mysqli_num_rows($sql1);	

    // Especifique quantos resultados você quer por página
	$lpp = 15;

   // Retorna o total de páginas
	$pags = ceil($n / $lpp);

   // Especifica uma valor para variavel pagina caso a mesma não esteja setada
	if(!isset($_GET["pag"])) {
		 $pag = 0;
	}else{
		 $pag = (int)$_GET["pag"];
	}
   // Retorna qual será a primeira linha a ser mostrada no MySQL
	$inicio = $pag * $lpp;

   // Executa a query no MySQL com o limite de linhas.
	$sql2.= " ORDER BY DtAgCons,NmPaciente LIMIT $inicio, $lpp ";
	//echo $sql2;
	$sql2 = mysqli_query($db,$sql2)or die(mysqli_errno());	
	
	//

if($n>0)
{
	
	echo "<form action=\"?i=51&dtinicio=$dtinicio&dttermino=$dttermino&cd_pref=$cd_pref&cd_forn=$cd_forn&ac=att\" name=\"frm_alterar\" id=\"frm_alterar\" method=\"post\">";

	if($_GET[ag] == "sim")
		echo "<h1 style='background-color:#FFF'><span><a href='index.php?i=51'>&laquo;VOLTAR</a></span></h1>";
	 if ($busca != "" and $_GET[ag] != "sim"){
	 echo "<div id=res_pesq> 
				<h1>Resultado de Busca <span> <a href='?i=$cdsubitem&m=l'>
				<img src='img/icon_fechar.png' title='retornar todos resultados' /> </a></span </h1>
				<li> Palavra Pesquisada:  $busca  </li>
				<li> Registros Encontrados:  $qtdreg  </li>
				
			</div>"; 
	 }
	 if(!isset($_GET[ag])) {
	    echo '<table  border="0" id="table" align="left">
        <tr bgcolor="#D6D9DE">
        <th>&nbsp; </th>							 
        <th>C&oacute;d.</th>
        <th>Data </th>
        <th>Hora </th>
        <th>Paciente</th>
        <th>Data Nascimento</th>
        <th>Cidade</th>
        <th>Fornecedor</th>
        <th>Especifica&ccedil;&atilde;o</th>
        ';
		}
	while($l = mysqli_fetch_array($sql2))
	{
		$CdFornac = $l[CdForn];
		$CdEspecProc = $l[CdEspecProc];
	 if(!isset($_GET[ag])) {

	 	$data = date('Y-m-d');
	 	//$horasbloque = "14:00:00";
	 	$dias 	= getConfiguracao(5);//config('alt');
	 	$dt 	= '+'.$dias[valor].' day';
	 	//echo $dias;
	 	//echo $dt;
	 	//$databloque = date('Y-m-d H:i:s',strtotime("-".$dias." day", strtotime($l[DtAgCons])));
	 	$databloque = date('Y-m-d',strtotime($dt));
	 	//echo $data.' >= '.$databloque;
	 		 	//echo $databloque;
	 			//echo $dt;
	 	if ($_SESSION["CdTpUsuario"] == 3) {
	 		if ($data > $databloque) {
	 			echo '<tr style="background-color: lightgray;">';
	 			$link1 = "#";
	 		}else{
				echo '<tr>';
				$link1 = "index.php?i=51&busca=$l[CdSolCons]&cbopor=7&ag=sim";
	 		}
	 	}else{
 			echo '<tr>';
			$link1 = "index.php?i=51&busca=$l[CdSolCons]&cbopor=7&ag=sim";
	 	}
					   
    ?>
    <!-- -->
    	
        	<td><a href="<?php echo $link1; ?>" ><img src="img/icon_agenda.png" width="20" height="20"/></td>
            <td><?php echo $l[CdSolCons]; ?></td> 
            <td><?php
                 if (isset($l["DtAgCons"])){
										$data = $l["DtAgCons"]; 
                                        $l["DtAgCons"] = explode("-",$l["DtAgCons"]);
                                        $l["DtAgCons"] = $l["DtAgCons"][2]."/".$l["DtAgCons"][1]."/".$l["DtAgCons"][0];
                                   }
				echo $l["DtAgCons"];            	
				?></td> 
            <td><?php echo $l[HoraAgCons]; ?> </td> 
            <td align="left" nowrap="nowrap"><?php echo $l[NmPaciente]; ?> </td> 
            <td><?php 
				   if (isset($l["DtNasc"])){
						$l["DtNasc"] = explode("-",$l["DtNasc"]);
						$l["DtNasc"] = $l["DtNasc"][2]."/".$l["DtNasc"][1]."/".$l["DtNasc"][0];
				   }
				   echo $l["DtNasc"];			
				?> </td> 
            <td><?php echo $l[NmCidade];   ?> </td> 
            <td><?php echo $l[NmReduzido]; ?> </td> 
            <td><?php echo $l[NmEspecProc]; ?> </td> 
        </tr>
    <!-- -->
    
  <?php } if($_GET[ag] == "sim") { ?>
  <table width="468" id='table'>
  <tr>
    <td width="232"> CONFIRMAR
    
     
    
     </td>
    <td width="220">    <?php if($_SESSION["CdTpUsuario"] == 1) {
								// marcado 
								if(($l[ssc]== 1) and ($l[sac] == 1)) { echo "<input type='checkbox' name='conf[]'  value='$l[CdSolCons]'>";
								}
								// realizado
								if(($l[ssc]== 1) and ($l[sac] == 2)) {
								
								 echo "Realizado";  
								 echo "<input type='hidden' name='realizado' value='1' />";
								}
							}
		
	?>
     </td>
  </tr>
  
  
  <tr>
  
  <tr>
    <td> Munícipio </td>
    <td> <?php
						$sql = mysqli_query($db,"SELECT tbpaciente.CdPaciente, tbprefeitura.NmCidade, tbprefeitura.CdPref
						FROM tbpaciente, tbbairro, tbprefeitura
						where tbpaciente.CdBairro = tbbairro.CdBairro
						AND tbbairro.CdPref = tbprefeitura.CdPref
						AND tbpaciente.CdPaciente='$l[CdPaciente]'") or die (mysqli_error());
						
						
						$ss = mysqli_fetch_array($sql);
						$city = $ss[NmCidade];
					echo "$city";
					 ?>
                     
                     </td>
  </tr>  
    <td> Paciente </td>
    <td> 
							<?php
							$sql = "SELECT p.NmPaciente,p.CPF,p.csus,p.CdPaciente
									FROM
									tbpaciente AS p
									Inner Join tbbairro AS b ON b.CdBairro = p.CdBairro
									WHERE b.CdPref = $ss[CdPref]
									ORDER BY p.NmPaciente";
							//echo $sql;
							$sql = mysqli_query($db,$sql) or die("");
							//$local = mysqli_fetch_array($sql);
									
                               echo "<select id=\"CdPaciente\" name=\"CdPaciente[]\" 
                               class='required' $disabled  style='text-transform:uppercase; height:30px; padding-left:5px; font-size:15px; width:400px;'>";
                               if(mysqli_num_rows($sql) > 0)				
                                   while($local = mysqli_fetch_array($sql)){
                                       if ($local["CdPaciente"] == $l[CdPaciente] )
                                            echo "<option value=\"$local[CdPaciente]\" title=\"$local[NmPaciente]\" selected=\"selected\"> &raquo; $local[NmPaciente]</option>";
                                       else
                                            echo "<option value=\"$local[CdPaciente]\" title=\"$local[NmPaciente]\">$local[NmPaciente] </option>";
                                   }					
                               echo "</select>";  
							?>
     </td>
  </tr>

               <input type="hidden" name="CdSolCons[]" id="CdSolCons"  value="<?php echo $l[CdSolCons] ?>"  
               style="text-transform:uppercase; height:30px; padding-left:5px; font-size:15px; width:50px;"  /> 
             
 
 <tr> 
 	<td> Procedimento </td>
    <td>  <?php  
               
                 $sql = "SELECT DISTINCT tbespecproc.CdEspecProc, tbespecproc.NmEspecProc
                          FROM tbespecproc  LEFT JOIN tbsolcons ON tbsolcons.CdEspecProc = tbespecproc.CdEspecProc";
				 if($_SESSION["cdgrusuario"] == 4)
				 		$sql .= " WHERE tbsolcons.CdEspecProc = $CdEspecProc";
						   $qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=inicial','lista_agendamento:consulta forn'));
						   //$local = mysqli_fetch_array($qry);
							   
                               echo "<select id=\"CdEspecProc\" name=\"CdEspecProc[]\" 
                               class='required' $disabled  style='text-transform:uppercase; height:30px; padding-left:5px; font-size:15px; width:200px;'>";
                               if(mysqli_num_rows($qry) > 0)				
                                   while($local = mysqli_fetch_array($qry)){
                                       if ($local["CdEspecProc"] == $CdEspecProc )
                                            echo "<option value=\"$local[CdEspecProc]\" title=\"$local[NmEspecProc]\" selected=\"selected\"> &raquo; $local[NmEspecProc]</option>";
                                       else
                                            echo "<option value=\"$local[CdEspecProc]\" title=\"$local[NmEspecProc]\">$local[NmEspecProc] </option>";
                                   }					
                               echo "</select>"; 
							   
				echo $local[NmEspecProc];	 
                  ?>
               </td>
 
 </tr>    
 
   
<tr> 
    <td> Data </td>
    <td>   <?php  $DtAgCons = FormataDataBR($l[DtAgCons]);
				  $readonly = "readonly";
	       		  if($_SESSION["cdgrusuario"] == 3 || $_SESSION["cdgrusuario"] == 1)
				  		$readonly = "";
		   ?> 
                  <input type='text' style="text-align:center; font-size:15px; width:80px;" name='DtAgCons[]' id='DtAgCons' value='<?php echo $DtAgCons."'".$readonly; ?>  />  </td>
</tr>    
<tr> 
    <td> Hora </td>
    <td>    
    	<?php $HoraAgCons = $l[HoraAgCons]; ?> <input type='time' name='HoraAgCons[]' style="text-align:center; font-size:15px; width:80px;" id='HoraAgCons'  value='<?php echo $HoraAgCons."'".$readonly; ?> required/>  </td>
</tr><td>Fornecedor</td><td>        
 <?php 
              $sql = "SELECT DISTINCT f.CdForn,f.NmForn,f.NmReduzido
                            FROM tbfornecedor f LEFT JOIN tbfornespec fe ON f.CdForn = fe.CdForn            
                            ";
				 if($_SESSION["cdgrusuario"] == 4)
				 		$sql .= " WHERE f.CdForn = $CdFornac";			
               				 
                               $qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=inicial','lista_agendamento:consulta forn'));
							   //$local = mysqli_fetch_array($qry);
							   
                               echo "<select id=\"select_forne\" name=\"select_forne[]\" class='required' $disabled  style='text-transform:uppercase; height:30px; padding-left:5px; font-size:15px; width:500px;' >";
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
            </tr>    
             <tr> 
            	<td>  Observação </td>
            	<td>    <input type="text" style="font-size:15px; width:400px;" name='Obs' id='Obs' value="<?php echo $l[Obs]  ?>"  readonly/>  </td>
            </tr>

            <?php 
        }
	}

	
	?>

</table>
<?php 
	  echo "<div id='pag2'>";	
	  $busca = rawurlencode((string)$busca);
	  $param = "&i=$_GET[i]&pesq=$busca&busca=$busca&cbopesq=$cbopor&op=$op#tb"; 
	  //echo $param;
	 if ($pags > 1)
	 if($pag > 0) {
		  $menos = $pag - 1;
		  // Vai para a página anterior
		  $url = "$PHP_SELF?pag=$menos".$param;
		  echo '<li> <a href='.$url.' title=Anterior>&laquo; Anterior </a> </li>'; // Vai para a página anterior
	 }
	 
	 if ($pags > 1)
		{ 
			
			$kb = $pag+1; echo  "<li> Página <strong>$kb</strong> de <strong>$pags</strong> páginas  </li> "; 
		}								 
	 
	 if($pag < ($pags - 1)) {
			$mais = $pag + 1;
			$url = "$PHP_SELF?pag=$mais".$param;	
			echo ' <li> <a href='.$url.' title=Próxima>Pr&oacute;xima &raquo;</a> </li> ';
			$ul = $pags-1;
			 $url2 = "$PHP_SELF?pag=$ul".$param;
	 }				
	echo "</div>"; 	
?>
<?php if($_GET[ag] == "sim") { ?>
   <div id="btns">
   	<input type="submit" value="SALVAR" >
   </div>

<?php }
echo "</form>";
   } else { 
   		echo "<h1 style='background-color:#FFF'><span><a href='index.php?i=51'>VOLTAR</a></span></h1>";
   		echo "<div id='alert'> Nenhum Procedimento Encontrado </div>"; 
	}?>

</div>

