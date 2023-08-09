<script type="text/javascript"> 

jQuery(function($){
	$("input[id=date]").mask("99/99/9999");
	$("input[id=hora]").mask("99:99");
	$("input[id=valcons]").maskMoney({symbol:"R$",decimal:",",thousands:"."});
});

</script>
<?php

	define("DIRECT_ACCESS",  true);

	require_once("verifica.php");
	
	//funcao para tratar erro
	require("admin/function_trata_erro.php");
	
   //verifica se o usuario tem permiss�o para acessar a pagina
   if ((int)$_SESSION["CdTpUsuario"] != 1 && (int)$_SESSION["CdTpUsuario"] != 2)	
   {
		echo '<script language="JavaScript" type="text/javascript"> 
			window.location.href="index.php?p=inicial";				
		  </script>';	
   }	
//conecta no banco
    require_once("conecta.php");
    
//variavel do form de busca

        $busca    = $_REQUEST["pesq"];
		$cbopor   = (int)$_REQUEST["cbopesq"];
		$cboshow  = (int)$_REQUEST["cboshow"];
		
		if ($cbopor == 1){
			$busca = (int)$busca;	
		}
       
//consulta solicitacao e agendamento de consultas
        $sql = "SELECT sc.CdSolCons,DtAgCons,HoraAgCons,p.CdPaciente,p.NmPaciente,p.DtNasc,sc.CdEspec,ac.Valor,
		               ac.CdForn,e.NmEspec,pr.NmCidade,sc.Protocolo,sc.DtInc,pr.CdPref,ep.CdEspecProc,NmEspecProc,
					   Obs,Urgente,sc.Status,ac.Status as StatusAg,
					   If(proc.NmProcedimento = 'Consulta','',Concat(proc.NmProcedimento,' - ')) as NmProcedimento
                FROM tbsolcons sc INNER JOIN tbpaciente p ON sc.CdPaciente=p.CdPaciente 
								  INNER JOIN tbbairro b ON b.CdBairro=p.CdBairro
								  INNER JOIN tbprefeitura pr ON b.CdPref=pr.CdPref
								  INNER JOIN tbespecialidade e ON sc.CdEspec=e.CdEspec
								  INNER JOIN tbespecproc ep ON sc.CdEspecProc=ep.CdEspecProc
								  INNER JOIN tbprocedimento proc ON ep.CdProcedimento=proc.CdProcedimento
								  LEFT JOIN tbagendacons ac ON sc.CdSolCons=ac.CdSolCons
								  LEFT JOIN tbfornecedor f ON ac.CdForn=f.CdForn";

//filtra as solicita��es 
		if($cboshow == "") $cboshow = 1;
		
		switch($cboshow){
			case 1: $sql .=	" WHERE sc.Status='1' AND ac.Status is NULL";
					break;
			case 2: $sql .=	" WHERE sc.Status='1' AND ac.Status='1'";
					break;	
			case 3: $sql .=	" WHERE sc.Status='1' AND ac.Status='2'";
					break;
			case 4: $sql .=	" WHERE sc.Status='2'";
					break;	
			default:$sql .=	" WHERE (sc.Status='1' OR sc.Status='2' OR ac.Status='1' OR ac.Status='2')";
					break;		
		}
			
//define busca
	if ($busca != ""){
		switch ($cbopor){
			case 1: $sql .= " AND sc.CdPaciente = $busca";
					break;
			case 2: $sql .= " AND p.NmPaciente LIKE '$busca%'";
					break;
			case 3: $sql .= " AND f.NmForn LIKE '$busca%'";
					break;	
			case 4: $sql .= " AND e.NmEspec LIKE '$busca%'";
					break;								
			case 5: $sql .= " AND sc.Protocolo LIKE '$busca%'";
					break;
			case 6: $sql .= " AND pr.NmCidade LIKE '$busca%'";
					break;		
		}
    }

//filtra os pacientes da prefeitura que o usuario estiver associado
	if ((int)$_SESSION["CdOrigem"]>0)
	{
		$sql .= " AND b.CdPref=".(int)$_SESSION["CdOrigem"];		
	}

    //echo $sql;
//executa a consulta
    $query = mysqli_query($db,$sql)or die (TrataErro(mysqli_errno(),'','index.php?p=inicial','lista_agendamento:qtd regs'));

//obtem o numero de linhas da consulta
    $qtdreg = mysqli_num_rows($query);
	//echo "Registros ".$qtdreg;

// Especifique quantos resultados voc� quer por p�gina
    $lpp = 15;

// Retorna o total de p�ginas
    $pags = ceil($qtdreg / $lpp);

// Especifica uma valor para variavel pagina caso a mesma n�o esteja setada
    if(!isset($_GET["pag"])) {
         $pag = 0;
    }else{
         $pag = (int)$_GET["pag"];
	}
// Retorna qual ser� a primeira linha a ser mostrada no MySQL
    $inicio = $pag * $lpp;

// Executa a query no MySQL com o limite de linhas.
    $limsql = $sql." ORDER BY NmEspec, CdSolCons, NmForn LIMIT $inicio, $lpp ";
    $query = mysqli_query($db,$limsql)or die(TrataErro(mysqli_errno(),'','index.php?p=inicial','lista_agendamento:consulta dados'));
//inicio do form de pesquisa
?>
<style type=text/css>
	#draggable { width: auto; position:absolute; border:1px solid; background-color:#FFF; height:auto; left:850px; }
</style>
    
<script type="text/javascript">
	$(function() {
		$("#draggable").draggable();
	});	
</script>
    

    <div id="draggable">
    	<?php 
				$sql = "SELECT p.CdPref,NmCidade,SUM(Credito)-SUM(Debito) as Saldo 
						FROM tbprefeitura p LEFT JOIN tbmovimentacao m ON p.CdPref=m.CdPref 
						WHERE p.CdPref in (SELECT CdOrigem
								 		   FROM tbusuario 
								 		   WHERE CdOrigem <> 'NULL' AND Status='1'
								 		  )";
				
				if ($busca != "" && $cbopor == 6){
					$sql .= " AND NmCidade LIKE '$busca%'";				
				}
				
				$sql .= " GROUP BY p.CdPref,NmCidade";
				$sql .= " ORDER BY NmCidade";
				$qry = mysqli_query($db,$sql)or die(TrataErro(mysqli_errno(),'','index.php?p=inicial','lista_agendamento:consulta saldo'));
				if ( mysqli_num_rows($qry) > 0 ){
					echo '<table>';
					
					while($saldo_pref = mysqli_fetch_array($qry))
						echo '<tr><td>'.$saldo_pref[NmCidade].'</td><td width="10">&nbsp;</td><td align="right">'.number_format($saldo_pref[Saldo],2,',','.').'</td></tr>';
					
					echo '</table>';
				}
		?>    
    </div>
    <div id="rotina" style="text-align:center">
    	Agendamento de Consultas e Exames Especializados<br /><br />
    </div>
	<div id="frm_pesq">
    	<form action="index.php?p=lista_agendamento" name="frm_pesq" method="post" id="frm_pesq">
        	Pesquisar&nbsp;<input type="text" name="pesq" value="<?php echo $busca;?>" />
            &nbsp; por &nbsp;<select name="cbopesq">
            					<option value="6" <?php if ($cbopor == 6 || $cbopor == "") echo 'selected="selected"';?> >Cidade</option>
            					<option value="1" <?php if ($cbopor == 1) echo 'selected="selected"';?> >CIH</option>                                
                                <option value="4" <?php if ($cbopor == 4) echo 'selected="selected"';?> >Nome da Especialidade</option>
                                <option value="3" <?php if ($cbopor == 3) echo 'selected="selected"';?> >Nome do Fornecedor</option>
                                <option value="2" <?php if ($cbopor == 2) echo 'selected="selected"';?> >Nome do Paciente</option>                                
                                <option value="5" <?php if ($cbopor == 5) echo 'selected="selected"';?> >Protocolo</option>                             
            				 </select>	
            &nbsp;Situa&ccedil;&atilde;o&nbsp;
                <select name="cboshow">
                    <option value="1" <?php if ($cboshow == 1 || $cboshow == "") echo 'selected="selected"';?> >Aguardando</option>
                    <option value="2" <?php if ($cboshow == 2) echo 'selected="selected"';?> >Marcado</option>
                    <option value="3" <?php if ($cboshow == 3) echo 'selected="selected"';?> >Realizado</option>
                    <option value="4" <?php if ($cboshow == 4) echo 'selected="selected"';?> >Cancelado</option> 
                    <option value="5" <?php if ($cboshow == 5) echo 'selected="selected"';?> >Todos</option> 
                </select>                 
            <input type="submit" value="Filtrar" name="btnpesq" />                      
            
            <input type="button" style="margin-left:90px;" name="BtnSalv" value="Salvar" 
            				onClick="javascript:window.document.getElementById('frm_agd').submit();">
            <br />
        </form>
    </div>   
<?php
//onclick="this.form.submit();"
//inicio do formulario de agendamento
			   echo "<form action=\"admin/regn_agd.php\" name=\"frm_agd\" id=\"frm_agd\" method=\"post\">";
								   
   if (mysqli_num_rows($query) > 0){
//inicio tabela conteudo
      echo '<center><table width="100%" border="0" id="grid">';
         echo '<tr bgcolor="#D6D9DE">';
  		 	 echo "<th></th>";
		 	 echo "<th>C&oacute;d.</th>";
			 echo "<th>Obs.</th>";
			 echo "<th>Protocolo</th>";
             echo "<th>Data</th>";
			 echo "<th>Hora</th>";
			 echo "<th>CIH</th>";
			 echo "<th>Paciente</th>";
			 echo "<th>Cidade</th>";
			 echo "<th>Local</th>";
			 echo "<th>Especialidade</th>";
			 echo "<th>Especifica&ccedil;&atilde;o</th>";
			 echo "<th>Valor</th>";
			 echo "<th></th>";
             echo "<th></th>";
         echo "</tr>";
        //cor da tabela
         $cortb = "linha2";
		 	 
         while($l = mysqli_fetch_array($query)){
               if ($cortb == "linha2"){
                   $cortb = "linha1";
               }
               else{
                   $cortb = "linha2";
               }		
			   
			   	   $link = 'javascript:abrirpop("admin/frm_addobs.php?id='.$l[CdSolCons].'&acao=edit","","640","550","no")';
			   	   $link_del = 'javascript:abrirpop("admin/frm_addobs.php?id='.$l[CdSolCons].'&acao=del","","640","550","no")';
                   echo "<tr class=".$cortb.">";
				   echo "<td>";
				   $disabled = '';
				   if($l["Status"] == 2)
				   {
				   		echo '<img src="imagens/cancelado.gif" width="13" height="13" title="Cancelado" />';
						$disabled = 'disabled="disabled"';
				   }
				   else
				   {	
				   		if($l["Status"] == 1 && $l["CdForn"] != ""){
							if ($l["StatusAg"] == 2){
								echo '<img src="imagens/realizado.gif" width="13" height="13" title="Realizado" />'; 
								$disabled = 'disabled="disabled"';
							}else{	
								echo '<img src="imagens/marcado.gif" width="13" height="13" title="Marcado" />'; 
								$readonly = 'readonly="readonly"';
							}
						}else
							echo '<img src="imagens/aguardando.gif" width="13" height="13" title="Aguardando" />'; 
				   }
							
				   echo "</td>";
				   echo "<td>$l[CdSolCons]</td>";
				   echo "<td>";
				   if ($l["Urgente"]){
					    echo "<a href='$link' title='Urgente' id='exibe'><img src='imagens/document_warning.png' width='13' height='13' /></a>&nbsp;";
				   }
				   if ($l["Obs"] != ""){
				   		echo "<a href='$link' title='$l[Obs]' id='exibe'><img src='imagens/message.png' width='13' height='13' /></a>";
				   }
				   echo "</td>";
				   echo "<td nowrap=\"nowrap\">$l[Protocolo]</td>";
				   
				   if (isset($l["DtAgCons"])){
						$l["DtAgCons"] = explode("-",$l["DtAgCons"]);
						$l["DtAgCons"] = $l["DtAgCons"][2]."/".$l["DtAgCons"][1]."/".$l["DtAgCons"][0];
				   }
				   echo "<td><input type=\"text\" name=\"dtag$l[CdSolCons]\" value=\"$l[DtAgCons]\" size=\"7\" id=\"date\" 
				   			onblur=\"if(!valida_data(this.value)){alert('Data inv�lida.'); this.value = ''; }\" $disabled /></td>";
				   
				   /*$sql = "SELECT HoraSaida
				   		   FROM tbhorasaida
						   WHERE CdPref=$l[CdPref]";
				   $qry = mysqli_query($db,$sql) or die (mysqli_error());	
				   $tiphor = '';
				   $tiphor .= "Hora de sa&iacute;da do onibus\n";
				   while ($hor = mysqli_fetch_array($qry)){
						   $tiphor .= $hor["HoraSaida"]."  ";
				   }
				   $tiphor = rtrim($tiphor);*/
                   echo "<td><input type=\"text\" name=\"hag$l[CdSolCons]\" value=\"$l[HoraAgCons]\" size=\"2\" id=\"hora\" title=\"$tiphor\" 
				   			onblur=\"if(!valida_horario(this.value)){alert('Hora inv�lida.'); this.value = ''; }\" $disabled /></td>";
				   echo "<td>$l[CdPaciente]</td>";
				   echo "<td align=\"left\" nowrap=\"nowrap\">$l[NmPaciente]</td>";
				   echo "<td align=\"left\">$l[NmCidade]</td>";
				   if ($l["CdEspecProc"] == 1)
				   		$sql = "SELECT f.CdForn,f.NmForn,f.NmReduzido
								FROM tbfornecedor f INNER JOIN tbfornespec fe ON f.CdForn=fe.CdForn
								WHERE fe.CdEspec = $l[CdEspec]";
				   else			
					   $sql = "SELECT f.CdForn,f.NmForn,f.NmReduzido
							   FROM tbfornecedor f INNER JOIN tbfornservicos fs ON f.CdForn=fs.CdForn
							   WHERE fs.CdEspecProc=$l[CdEspecProc]";
							   
				   $qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=inicial','lista_agendamento:consulta forn'));
				   
				   echo "<td align=\"left\">
				   				<select name=\"local$l[CdSolCons]\" style=\"width:125px;\" $disabled>
									<option value=\"0\">Selecione Local</option>";
				   if(mysqli_num_rows($qry) > 0)				
					   while($local = mysqli_fetch_array($qry)){
						   if ($local["CdForn"] == $l["CdForn"])
								echo "<option value=\"$local[CdForn]\" title=\"$local[NmForn]\" selected=\"selected\">$local[NmReduzido]</option>";
						   else
								echo "<option value=\"$local[CdForn]\" title=\"$local[NmForn]\">$local[NmReduzido]</option>";
					   }					
				   echo "</select></td>";
				   
				   echo "<td align=\"left\">$l[NmEspec]</td>";
				   echo "<td align=\"left\">$l[NmProcedimento]$l[NmEspecProc]</td>";
				   
				   $l["Valor"] = number_format($l["Valor"],2,",",".");
						
				   echo "<td align=\"left\"><input type=\"text\" name=\"valcons$l[CdSolCons]\" value=\"$l[Valor]\" size=\"6\" id=\"valcons\" $disabled $readonly /></td>";
				   echo "<td align=\"center\"><a href='$link'>
				 <img src=\"imagens/document_add.png\" border=\"0\" title=\"Incluir Obs.\" alt=\"Incluir Obs.\" width=\"13\" height=\"13\" /></a></td>";
				   echo "<td align=\"center\"><a href='$link_del'>
				  <img src=\"imagens/document_delete.png\" border=\"0\" title=\"Cancelar Solicita��o\" alt=\"Cancelar\" width=\"13\" height=\"13\" /></a></td>";				
                   echo "</tr>";
         }//fim enquanto
         echo "</table>";
		 //envia as variaveis do formulario de pesquisa para n�o perder o filtro
		 echo "<input type=\"hidden\" value=\"pesq=$busca&cbopesq=$cbopor&cboshow=$cboshow\" name=\"varspesq\" id=\"varspesq\" />";
         //fim da tabela
		 //fim do formulario de agendamento
		echo "</form>";	
         $param = "&p=lista_agendamento&pesq=$busca&cbopesq=$cbopor&cboshow=$cboshow";
		 if ($pags > 1)
		 echo "<br><br>Ver p&aacute;gina:&nbsp;";
         if($pag > 0) {
              $menos = $pag - 1;
              $url = "$PHP_SELF?pag=$menos".$param;
              echo '<a href='.$url.'>Anterior</a>'; // Vai para a p�gina anterior
         }
		 if ($pags > 1)
         for($i=0;$i<$pags;$i++) { // Gera um loop com o link para as p�ginas
                $url = "$PHP_SELF?pag=$i".$param;
				$j = $i + 1;
                if ($pag == $i)
                    echo " | <a href=".$url."><b>$j</b></a>";
                else
                    echo " | <a href=".$url.">$j</a>";
         }
         if($pag < ($pags - 1)) {
                $mais = $pag + 1;
                $url = "$PHP_SELF?pag=$mais".$param;
                echo ' | <a href='.$url.'>Pr�xima</a></center>';
         }
      }//fim do if
      else{
          echo '<h3><center><font face="verdana","arial" color="FF6464">Nenhum agendamento encontrado</font></center></h3>';
	  }
@mysqli_free_result($query);
@mysqli_close();
?>