<script type="text/javascript"> 

$("document").ready(function(){
	$("#obs").hide();
	$("#exibe").click(function(){
		$("#obs").show();						   
	});

});

jQuery(function($){
	$("input[id=date]").mask("99/99/9999");
	$("input[id=hora]").mask("99:99");
});

</script>
<?php
	require_once("verifica.php");
	
	//funcao para tratar erro
	require("admin/function_trata_erro.php");
	
   //verifica se o usuario tem permissão para acessar a pagina
   if ((int)$_SESSION["CdTpUsuario"] != 1 && (int)$_SESSION["CdTpUsuario"] != 2 && (int)$_SESSION["CdTpUsuario"] != 3 && (int)$_SESSION["CdTpUsuario"] != 4)	
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
        $sql = "SELECT sc.CdSolCons,DtAgCons,HoraAgCons,p.CdPaciente,p.NmPaciente,sc.CdEspec,ac.Valor,
		               ac.CdForn,e.NmEspec,pr.NmCidade,sc.Protocolo,sc.DtInc,pr.CdPref,ep.CdEspecProc,
					   NmEspecProc,f.NmForn,Obs1,sc.Status,ac.Status as StatusAg,Urgente,NmReduzido
                FROM tbsolcons sc INNER JOIN tbpaciente p ON sc.CdPaciente=p.CdPaciente 
								  INNER JOIN tbbairro b ON b.CdBairro=p.CdBairro
								  INNER JOIN tbprefeitura pr ON b.CdPref=pr.CdPref
								  INNER JOIN tbespecialidade e ON sc.CdEspec=e.CdEspec
								  INNER JOIN tbespecproc ep ON sc.CdEspecProc=ep.CdEspecProc
								  LEFT JOIN tbagendacons ac ON sc.CdSolCons=ac.CdSolCons
								  LEFT JOIN tbfornecedor f ON ac.CdForn=f.CdForn";

//filtra as solicitações 	
		if($cboshow == "") $cboshow = 2;
		
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
		}
    }

//filtra os pacientes da prefeitura que o usuario estiver associado
	if ((int)$_SESSION["CdOrigem"]>0)
	{
		$sql .= " AND b.CdPref=".(int)$_SESSION["CdOrigem"];		
	}

    //echo $sql;
//executa a consulta
    $query = mysqli_query($db,$sql)or die (TrataErro(mysqli_errno(),'','index.php?p=inicial','lista_solagd:qtd regs'));

//obtem o numero de linhas da consulta
    $qtdreg = mysqli_num_rows($query);
	//echo "Registros ".$qtdreg;

// Especifique quantos resultados você quer por página
    $lpp = 15;

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
    $limsql = $sql." ORDER BY DtInc DESC, CdSolCons, NmForn LIMIT $inicio, $lpp ";
    $query = mysqli_query($db,$limsql)or die(TrataErro(mysqli_errno(),'','index.php?p=inicial','lista_solagd:consulta dados'));
//inicio do form de pesquisa
?>
	<div id="rotina" style="text-align:center">
    	Agendamento de Consultas e Exames Especializados<br /><br />
    </div>
	<div id="frm_pesq">
    	<form action="index.php?p=lista_solagd" name="frm_pesq" method="post" id="frm_pesq">
        	Pesquisar&nbsp;<input type="text" name="pesq" value="<?php echo $busca;?>" />
            &nbsp; por &nbsp;<select name="cbopesq">
            					<option value="1" <?php if ($cbopor == 1) echo 'selected="selected"';?> >CIH</option>
                                <option value="2" <?php if ($cbopor == 2 || $cbopor == "") echo 'selected="selected"';?> >Nome do Paciente</option>
                                <option value="3" <?php if ($cbopor == 3) echo 'selected="selected"';?> >Nome do Fornecedor</option>
                                <option value="4" <?php if ($cbopor == 4) echo 'selected="selected"';?> >Nome da Especialidade</option>
                                <option value="5" <?php if ($cbopor == 5) echo 'selected="selected"';?> >Protocolo</option>
            				 </select>	
            &nbsp;Situa&ccedil;&atilde;o&nbsp;
                <select name="cboshow">
                    <option value="1" <?php if ($cboshow == 1) echo 'selected="selected"';?> >Aguardando</option>
                    <option value="2" <?php if ($cboshow == 2) echo 'selected="selected"';?> >Marcado</option>
                    <option value="3" <?php if ($cboshow == 3) echo 'selected="selected"';?> >Realizado</option>
                    <option value="4" <?php if ($cboshow == 4) echo 'selected="selected"';?> >Cancelado</option> 
                    <option value="5" <?php if ($cboshow == 5) echo 'selected="selected"';?> >Todos</option> 
                </select>                       
            <input type="submit" value="Filtrar" name="btnpesq" />                 
            &nbsp;
			<input type="button" name="BtnCad" value="Solicitar Agendamento" onClick="javascript:window.location.href='index.php?p=frm_solagd'">
          
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
			 echo "<th>Obs.</th>";
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
			 echo "<th>Valor</th>";
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
			   
                   echo "<tr class=".$cortb.">";
				   echo "<td>";
					   if($l["Status"] == 2)
							echo '<img src="imagens/cancelado.gif" width="13" height="13" title="Cancelado" />';
					   else
					   {	
							if($l["Status"] == 1 && $l["CdForn"] != ""){
								if ($l["StatusAg"] == 2)
									echo '<img src="imagens/realizado.gif" width="13" height="13" title="Realizado" />'; 
								else	
									echo '<img src="imagens/marcado.gif" width="13" height="13" title="Marcado" />'; 
							}
							else
								echo '<img src="imagens/aguardando.gif" width="13" height="13" title="Aguardando" />'; 
					   }			
				   echo "</td>";
				   
				   echo "<td align=\"center\">";
				   $link = 'javascript:abrirpop("admin/frm_addobs.php?id='.$l[CdSolCons].'","","640","550","no")';
				   if ($l["Urgente"]){
					    echo "<a href='$link' title='Urgente' id='exibe'><img src='imagens/document_warning.png' width='13' height='13' /></a>&nbsp;";
				   }
				   if ($l["Obs1"] != "")	
				      echo "<a href='$link' title='$l[Obs1]' id='exibe'><img src='imagens/message.png' width='13' height='13' /></a>";
				   echo "</td>";
				   
				   echo "<td>$l[CdSolCons]</td>";
				   echo "<td nowrap=\"nowrap\">$l[Protocolo]</td>";
				   
				   if (isset($l["DtAgCons"])){
						$l["DtAgCons"] = explode("-",$l["DtAgCons"]);
						$l["DtAgCons"] = $l["DtAgCons"][2]."/".$l["DtAgCons"][1]."/".$l["DtAgCons"][0];
				   }
				   echo "<td>$l[DtAgCons]</td>";
                   echo "<td>$l[HoraAgCons]</td>";
				   echo "<td>$l[CdPaciente]</td>";
				   echo "<td align=\"left\" nowrap=\"nowrap\">$l[NmPaciente]</td>";
				   echo "<td align=\"left\" nowrap=\"nowrap\">$l[NmCidade]</td>";				   
				   echo "<td align=\"left\" nowrap=\"nowrap\" title=\"$l[NmForn]\">$l[NmReduzido]</td>";
				   echo "<td align=\"left\" nowrap=\"nowrap\">$l[NmEspec]</td>";
				   echo "<td align=\"left\" nowrap=\"nowrap\">$l[NmEspecProc]</td>";	
				   echo "<td align=\"right\" nowrap=\"nowrap\">".number_format($l[Valor],2,',','.')."</td>";
				   echo "</tr>";
         }//fim enquanto
         echo "</table>";
         //fim da tabela
		 //fim do formulario de agendamento
		echo "</form>";	
         $param = "&p=lista_solagd&pesq=$busca&cbopesq=$cbopor&cboshow=$cboshow";
		 if ($pags > 1)
		 echo "<br><br>Ver p&aacute;gina:&nbsp;";
         if($pag > 0) {
              $menos = $pag - 1;
              $url = "$PHP_SELF?pag=$menos".$param;
              echo '<a href='.$url.'>Anterior</a>'; // Vai para a página anterior
         }
		 if ($pags > 1)
         for($i=0;$i<$pags;$i++) { // Gera um loop com o link para as páginas
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
                echo ' | <a href='.$url.'>Próxima</a></center>';
         }
      }//fim do if
      else{
          echo '<h3><center><font face="verdana","arial" color="FF6464">Nenhum agendamento encontrado</font></center></h3>';
	  }
@mysqli_free_result($query);
@mysqli_close();
?>