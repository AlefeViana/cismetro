<?php session_start();?>
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
   <!-- <script type="text/javascript" src="ajax.js"></script>-->
    
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
<script type="text/javascript"> /*  
		$(document).ready(function() {	
			$('#select_forne').change(function(){								  
				$('#CdEspecProc').attr("disabled","disabled");						  
				$('#CdEspecProc').load('admin/load_especforn.php?cdforn='+$('#select_forne').val() );
				$('#CdEspecProc').removeAttr("disabled");
			});						
		}); */
	</script>
<?php 


//session_start();
 include "conecta.php";  
 include "funcoes.php";  
 
   
	/* Parametros */
	$data = date('d/m/Y');
	
	$cd_forn = $_GET['cd_forn'];
	$cd_pref = $_GET['cd_pref'];
	$cdproc	 = $_GET['proc'];
	$cdespec = $_GET['espec'];		
	
	if($cd_forn ==0 ) {$condf = ""; } else { $condf = "AND af.cdfornecedor ='$cd_forn'"; }
	if($cd_pref ==0 ) {$condc = " AND pr.consorciado='S' "; } else { $condc = "AND af.cdpref = '$cd_pref' AND pr.consorciado='S'";}
	if($cdproc == 0){
		$proc = "";
	}else{
		$proc = "AND ep.CdProcedimento = '$cdproc'";
	}
	if($cdespec == 0){
		$espec = "";
	}else{
		$espec = "AND af.cdespecificacao = '$cdespec'";
	}	
	
	
	$dtinicio = $_GET['dtinicio'];
	$dttermino = $_GET['dttermino'];
	
	$data1 = FormataDataBD($dtinicio);
	$data2 = FormataDataBD($dttermino);
		
		
	$cdrelfat = $_GET['cdrelfat'];
	

	
/* ==================================================== Atualiza Faturamento */

$ac = $_GET['ac'];

if($ac=="att")
{
	
	$CdPaciente 	= $_POST['CdPaciente'];
	$NmPaciente 	= $_POST['NmPaciente'];
	
	$CdSolCons 		= $_POST['CdSolCons'];
	$HoraAgCons		= $_POST['HoraAgCons'];
	$DtAgCons 		= $_POST['DtAgCons'];
	$DtAgCons 		= $_POST['DtAgCons'];
	$select_forne 	= $_POST['select_forne'];
	$select_prof 	= $_POST['select_prof'];
	$Valor 			= $_POST['Valor'];
	$valor_sus 		= $_POST['valor_sus'];
	$qts 			= $_POST['qts'];
	$obsc 			= $_POST['obsc'];
	$cdagforn 		= $_POST["agforn"];
	$valor 			= $_POST['valor'];
	$valor_sus 		= $_POST['valor_sus'];
	$CdEspecProc 	= $_POST['CdEspecProc'];	
	$check_mudar 	= $_POST["check-mudar"];
	$espec2 		= $_POST["espec2"];
	$forn2 			= $_POST["forn2"];
	$DtAgCons2 		= $_POST["DtAgCons2"];
	$UserAlt 		= $_SESSION["CdUsuario"];

	
	$p= $_POST['p'];
	
	$msg_erro = "";
	for($j=0;$j<=count($cdagforn);$j++)
	{
		$Data = FormataDataBD($DtAgCons[$j]);
		$valor_sus[$j] = moeda($valor_sus[$j]);
		$valor[$j] = moeda($valor[$j]);

		if(isset($check_mudar))
			if(in_array($CdSolCons[$j], $check_mudar))
			{			

					$sql_agenda  = mysqli_query($db,"UPDATE tbagendacons 
												SET CdForn 		= '$select_forne[$j]',
													cdprof 		= '$select_prof[$j]',
													DtAgCons 	= '$Data',
													HoraAgCons	= '$HoraAgCons[$j]',
													UserAlt 	= '$UserAlt',
													DtAlt 		=  NOW()
												WHERE (CdSolCons = '$CdSolCons[$j]')") or die (mysqli_error());

					$sql_agforn = mysqli_query($db," UPDATE tbagenda_fornecedor af
											   	SET cdfornecedor 	= '$select_forne[$j]',
												    cdprof 			= '$select_prof[$j]',
												    data  			= '$Data',
												    hora			= '$HoraAgCons[$j]'
											   	WHERE cdagenda_fornecedor = $cdagforn[$j] ") or die ('ATP '.mysqli_error()) ;
					// POSSUI TRIGGER DE LOG DE ALTERAÇÕES REALIZADAS
					$sqluseralt = mysqli_query($db,"INSERT INTO `tbusralt` (cdusr, cdag, dtalt) VALUES ('$UserAlt','$CdSolCons[$j]',NOW())") or die ('ATP '.mysqli_error()) ;

			}
	}

	 $CdSolCons = $_POST['CdSolCons'];
	 $conf = $_POST['conf'];
	 if($conf != ""){
		for($i=0;$i<count($conf);$i++)
		{ 
			$cd = $conf[$i];
			set_realizado($cd,1);
		}
	 } 
	  
	  
	  $canc = $_POST['canc'];
	  if($canc != ""){
		  for($i=0;$i<count($canc);$i++)
		  { 
			 $abc = set_canc($canc[$i],2,0,1);
		  }
	  }
	  
	  echo "<div  style='padding:10px; color:#009933; font-size:20px;'> Dados alterados com sucesso! </div>";

	  if($msg_erro != "")
	  	echo "<div  style='padding:10px; color:red; font-size:16px;'> Agendamentos ".substr($msg_erro,0,strlen($msg_erro)-1)." não possuem contrato ou chegaram ao fim, na data informada! </div>";
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
  
    <div id="fat" style="padding:15px;">
    
     <!-- <h1 style="margin-bottom:2px;"> <img src="imagens/realizado.gif" width="10" height="10" />  Controle &raquo; Faturamento <strong>(Procedimentos Realizados ) </strong></h1>  -->
    <h1 style="margin-bottom:1px;" >   Controle Faturamento <?php ?> </h1>
    <h1 style="margin-bottom:0px;">   Periodo: <?php  echo $_GET['dtinicio']." 	 ".$_GET['dttermino'];        ?> </h1>

  <?php 
  
    $status = $_GET['status'];
    if($status=="T")
	{
		$status = " AND (af.`status` = 'A' OR af.`status` = 'M')";
	}
    if($status=="A")
	{
		$status = " AND (af.`status` = 'A')";
	}
    if($status=="M")
	{
		$status = " AND (af.`status` = 'M')";
	}
  
  
  	$sql3 = "SELECT IFNULL(NmPaciente, '--') AS nomepac, prof.nmprof, prof.cdprof, f.NmForn, ep.NmEspecProc, 
  			af.`data`, af.hora, af.cdagenda_fornecedor, ac.CdSolCons,	af.`status`,af.cdpref,pr.NmCidade,f.CdForn,ep.CdEspecProc
			FROM tbagenda_fornecedor af
			INNER JOIN tbfornecedor f ON f.CdForn = af.cdfornecedor
			INNER JOIN tbprofissional prof ON prof.cdprof = af.cdprof
			INNER JOIN tbespecproc ep ON ep.CdEspecProc = af.cdespecificacao
			INNER JOIN tbprocedimento proc on proc.CdProcedimento = ep.CdProcedimento
			LEFT JOIN tbagendacons ac ON ac.cdagenda_fornecedor = af.cdagenda_fornecedor
			LEFT JOIN tbsolcons sc ON sc.CdSolCons = ac.CdSolCons
			LEFT JOIN tbpaciente p ON p.CdPaciente = sc.CdPaciente
			LEFT JOIN tbprefeitura pr on pr.CdPref = af.cdpref
			WHERE af.`data` BETWEEN '$data1' AND '$data2'
			$status
			$condf
			$condc
			$proc
			$espec	    
			ORDER BY `data`,af.hora";
/*
	$sql_pg = "SELECT count(sc.CdSolCons) as qnt from tbsolcons sc inner join tbagendacons ac on sc.CdSolCons = ac.CdSolCons inner join tbprefeitura pr on sc.CdPref = pr.CdPref ";
	$sql_pg .= ($cdproc > 0)? " inner join tbespecproc ep on sc.CdEspecProc = ep.CdEspecProc inner join tbprocedimento proc on ep.CdProcedimento = proc.CdProcedimento " : "";
	$sql_pg .= " WHERE ac.DtAgCons BETWEEN '$data1' AND '$data2' ".$status.$condf.$condc.$proc.$espec;
	//echo $sql_pg;

  	$query = mysqli_query($db,$sql_pg);

  	$qnt_pg = mysqli_fetch_array($query);
  	mysqli_free_result($query);*/
  	$query = mysqli_query($db,$sql3);
  	$qtdreg = mysqli_num_rows($query);
    //$qtdreg = mysqli_num_rows($query);
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
	$limsql = $sql3." LIMIT $inicio, $lpp ";
	$sql2 = mysqli_query($db,$limsql)or die();

	//echo "$limsql";

	$n = mysqli_num_rows($sql2);

if($n>0)
{
	
	echo "<form action=\"alt_fat_r_novo.php?dtinicio=$dtinicio&dttermino=$dttermino&cd_pref=$cd_pref&cd_forn=$cd_forn&cdrelfat=$cdrelfat&status=$_GET[status]&proc=$cdproc&espec=$cdespec&ac=att\" name=\"frm_alterar\" id=\"frm_alterar\" method=\"post\">";
	 echo "<table id='table'>
	 <tr>
		<th > STATUS </th>
		<th > AGENDA </th>
		<th > PACIENTE </th>
		<th > MUNICIPIO </th>
		<th > ESPECIFICACAO </th>";
		//<th >PACTUACAO </th>
		echo"
		<th > DATA      </th>
		<th > HORA      </th>
		<th  > FORNECEDOR </th>
		<th > PROFISSIONAL </th>
	  </tr>";

	
	
	while($l = mysqli_fetch_array($sql2))
	{
		$cdprof = $l[cdprof];
		$CdForn = $l[CdForn];
		$CdEspecProc = $l[CdEspecProc];
	  
    ?>
              <tr>
               <input type="hidden" name="espec2[]" value="<?php echo $l["CdEspecProc"]; ?>">
                <input type="hidden" name="agforn[]" value="<?php echo $l["cdagenda_fornecedor"]; ?>">
               <input type="hidden" name="cdforn2[]" value="<?php echo $l["CdForn"]; ?>">
               <input type="hidden" name="DtAgCons2[]" value="<?php echo $l["DtAgCons"]; ?>">
               <input type="hidden" name="CdSolCons[]" id="CdSolCons"  value="<?php echo $l[CdSolCons] ?>"  style="text-transform:uppercase; height:30px; padding-left:5px; font-size:15px; width:50px;"  /> 
                <input type="hidden" name="CdPaciente[]" id='CdPaciente'   value="<?php echo $l[CdPaciente] ?>"  style="text-transform:uppercase; height:30px; padding-left:5px; font-size:15px; width:70px;" /> 
               <td  style="text-align:center"  >
     				<input type="checkbox" name="check-mudar[]" class="check-mudar" style="display: none;" value="<?php echo "$l[CdSolCons]"; ?>"/>
    <?php 

			if($l[status] == 'A') 
				echo "AGUARDANDO";
			if($l[status] == 'M') 
				echo "MARCADO";

		
			?>
     </td>
    			<td style="padding-left: 10px;"> <input type="text" name="cdagenda_fornecedor[]" id='cdagenda_fornecedor[]' value="<?php echo $l[cdagenda_fornecedor] ?>"  class="gr"  disabled style="text-transform:uppercase; height:30px; padding-left:5px; font-size:12px; width:40px;" />   </td>

                <td> <input type="text" name="NmPaciente[]" id='NmPaciente[]' value="<?php echo $l[nomepac] ?>"  class="gr"  disabled style="text-transform:uppercase; height:30px; padding-left:5px; font-size:12px; width:220px;" />   </td>

                <td> <input type="text" name="cd_pref[]" id='cd_pref[]' value="<?php echo $l[NmCidade] ?>"  class="gr"  disabled style="text-transform:uppercase; height:30px; padding-left:5px; font-size:12px; width:150px;" />   </td>
       <!--          <td> <input type="text" name="NmPaciente[]" id='NmPaciente[]' value="<?php echo $l[NmForn] ?>"  class="gr"  disabled style="text-transform:uppercase; height:30px; padding-left:5px; font-size:12px; width:220px;" />   </td> -->
                <td> <input type="text" name="espec[]" id='espec[]' value="<?php echo $l[NmEspecProc] ?>"  class="gr"  disabled style="text-transform:uppercase; height:30px; padding-left:5px; font-size:12px; width:220px;" />   </td>
               	
            
                <?php  $DtAgCons = FormataDataBR($l[data]); ?> 
                 <td>  <input type='text' style="text-align:center; font-size:15px; width:80px;" name='DtAgCons[]' id='DtAgCons' class='mudar' value='<?php echo $DtAgCons ?>' onBlur="ajax.engine('texto',this.value,<?php echo $l[CdSolCons] ?>,'DtAgCons')"  /> </td>
               
               
                <td> <?php $HoraAgCons = $l[hora]; ?> <input type='text' name='HoraAgCons[]' style="text-align:center; font-size:15px; width:50px;" id='HoraAgCons' class='mudar'  
                value='<?php echo $HoraAgCons ?>' onBlur="ajax.engine('texto',this.value,<?php echo $l[CdSolCons] ?>,'HoraAgCons')"  /> </td>
                
                   <td >  
              <?php 

			        $sql = "SELECT f.CdForn, f.NmForn FROM tbfornespec fe
							INNER JOIN tbfornecedor f on f.CdForn = fe.CdForn
							WHERE fe.`Status` = 1 
							and fe.CdEspec = ".$CdEspecProc;
							//echo $sql;
					$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=inicial','lista_agendamento:consulta forn'));
					echo "<select id=\"select_forne\" name=\"select_forne[]\" class='required mudar' $disabled  style='text-transform:uppercase; height:30px; padding-left:5px; font-size:15px; width:200px;' onChange=\"ajax.engine('texto',this.value,$l[CdSolCons],'cdforn')\">";
					if(mysqli_num_rows($qry) > 0)				
					while($local = mysqli_fetch_array($qry)){
						echo "prof: ".$local[CdForn]." - Gprof: ".$cdprof;
					if ($local[CdForn] == $CdForn)
						echo "<option value=\"$local[CdForn]\" title=\"$local[NmForn]\" selected=\"selected\"> &raquo; $local[NmForn]</option>";
					else
						echo "<option value=\"$local[CdForn]\" title=\"$local[NmForn]\">$local[NmForn] </option>";
					}					
					echo "</select>
					";	 
                    ?>  
               </td>
               <td >  
              <?php 

			        $sql = "SELECT nmprof,cdprof FROM tbprofissional WHERE `status` = 'a' ORDER BY nmprof";
					$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=inicial','lista_agendamento:consulta forn'));
					echo "<select id=\"select_prof\" name=\"select_prof[]\" class='required mudar' $disabled  style='text-transform:uppercase; height:30px; padding-left:5px; font-size:15px; width:200px;' onChange=\"ajax.engine('texto',this.value,$l[CdSolCons],'cdforn')\">";
					if(mysqli_num_rows($qry) > 0)				
					while($local = mysqli_fetch_array($qry)){
						echo "prof: ".$local[cdprof]." - Gprof: ".$cdprof;
					if ($local[cdprof] == $cdprof)
						echo "<option value=\"$local[cdprof]\" title=\"$local[nmprof]\" selected=\"selected\"> &raquo; $local[nmprof]</option>";
					else
						echo "<option value=\"$local[cdprof]\" title=\"$local[nmprof]\">$local[nmprof] </option>";
					}					
					echo "</select>
					";	 
                    ?>  
               </td>
            
              </tr>
            <?php 
	}
	?>
  <tr>
    <td colspan="12">
   <div id="btns">
   	<input type="submit" id="salvar" value="SALVAR" >
   </div>
    </td>
  </tr>
</table>
</form>
</div>
	
</div>

<?php 
echo "<div id='paginacao'>";		
                //      pag=1&dtinicio=01/01/2016&dttermino=29/02/2016&cd_pref=0&cd_forn=260&status=T
			$param = "&dtinicio=$dtinicio&dttermino=$dttermino&cd_pref=$cd_pref&cd_forn=$cd_forn&cdrelfat=$cdrelfat&status=$_GET[status]&proc=$cdproc&espec=$cdespec";
				if ($pags > 1)
					// echo "<br><br>Ver p&aacute;gina:&nbsp;";
					if($pag > 0) {
						$menos = $pag - 1;
						$url = "$PHP_SELF?pag=$menos".$param;
						$url2 = "$PHP_SELF?pag=0".$param;
						echo '<a class="pag" href='.$url2.'>P&aacute;gina 1</a>&nbsp;<a href='.$url.'>&laquo; Anterior</a>'; // Vai para a página anterior
					}
					
					
					
					if ($pags > 1){						
					$i = $pag;	
					if($i > 10){
						$i = $pag-10;
						if($pag < ($pags-19))
							$cont = $pag+19;
						else $cont = $pags;
					}else
					if($pag < ($pags-19)){
						$i=0; $cont = $pag+19;
						if($pag < 10 && $pag < ($pags-30))	$cont = 30;	
					}						 
					else {$i = 0; $cont = $pags;}		 
					 
					 for($i;$i<$cont;$i++) { // Gera um loop com o link para as p?ginas
							$url = "$PHP_SELF?pag=$i".$param;
							$j = $i + 1;
							if ($pag == $i)
								echo " <span>$j</span>";
							else
								echo " <a class='pag' href=".$url.">$j</a>";
						}
					}
						if($pag < ($pags - 1)) {
							$mais = $pag + 1;
							$url = "$PHP_SELF?pag=$mais".$param;
							echo ' <a class="pag" href='.$url.'>Pr&oacute;xima &raquo;</a></center>';
						}
				echo "</div>";

}else 
{ echo "Nenhum Faturamento encontrado"; }?>

<style> 
	table td { height:20px;  }

	#paginacao{
		text-align: center;
	} 

	.loading{
		width: 30px;
	    text-align: center;
	    border-radius: 20px;
	    height: 30px;
	    box-sizing: border-box;
	    border: solid 5px #92e6ff;
	    border-top-color: #4c9ad2;
	    animation: spin 1s infinite cubic-bezier(0.68, 0.46, 0.37, 0.54);
	    display: inline-block;
	}

	@keyframes spin{
		100%{
			transform: rotate(360deg);
		}
	}
</style>

<script type="text/javascript">
	$(document).ready(function(){
		$(".mudar").change(function(){
			var linhaPai = $(this).closest("tr");
			console.log(linhaPai.find(".check-mudar"));
			linhaPai.find(".check-mudar").attr("checked",true);
		});

		$(".pag").click(function(event) {
			$("#paginacao").html("<div class='loading'></div>");
		});

		$("#salvar").click(function(event) {
			$(this).hide();
			$("#btns").append("<div class='loading' style='float:right'></div>");
		});
	});
</script>

<?php 
	mysqli_close($db);
?>