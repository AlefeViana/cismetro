<?php
	require_once("verifica.php");
	
//funcao para tratar erro
	require("admin/function_trata_erro.php");
	
//conecta no banco
    require_once("conecta.php");
    
//variavel do form de busca

        $busca    = mysqli_real_escape_string($_REQUEST["pesq"]);
		$cbopor   = (int)$_REQUEST["cbopesq"];
		
		if ($cbopor == 1){
			$busca = (int)$busca;	
		}
       
//consulta pacientes
        $sql = "SELECT p.CdPaciente,p.NmPaciente,p.NmMae,p.DtNasc,pr.NmCidade
                FROM tbpaciente p INNER JOIN tbbairro b ON p.CdBairro=b.CdBairro
								  INNER JOIN tbprefeitura pr ON b.CdPref=pr.CdPref";
    if ($busca != ""){
		switch ($cbopor){
			case 1: $sql .= " WHERE CdPaciente = $busca";
					break;
			case 2: $sql .= " WHERE NmPaciente LIKE '$busca%'";
					break;
		}
    }
//filtra os pacientes de uma cidade de acordo com o usuario logado
	if ((int)$_SESSION["CdOrigem"]>0)
	{
		$sql .= " AND b.CdPref=".(int)$_SESSION["CdOrigem"];		
	}

    //echo $sql;
//executa a consulta
    $query = mysqli_query($db,$sql)or die (TrataErro(mysqli_errno(),'','index.php?p=inicial','lista_pac:qtd regs'));

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
    $limsql = $sql." ORDER BY NmCidade,NmPaciente LIMIT $inicio, $lpp ";
    $query = mysqli_query($db,$limsql)or die(TrataErro(mysqli_errno(),'','index.php?p=inicial','lista_pac:consulta dados'));
	
	if($_SESSION["CdTpUsuario"] == 7){
		$habilitado = 'disabled="disabled"';
	}else{
		$habilitado = '';	
	}
//inicio do form de pesquisa
?>
	<div id="rotina" style="text-align:center">
    	Cadastro de Paciente<br /><br />
    </div>
	<div id="frm_pesq">
    	<form action="index.php?p=lista_pac" method="post">
        	Pesquisar&nbsp;<input type="text" name="pesq" value="<?php echo $busca;?>" />
            &nbsp; por &nbsp;<select name="cbopesq">
            					<option value="1" <?php if ($cbopor == 1) echo 'selected="selected"';?> >CIH</option>
                                <option value="2" <?php if ($cbopor == 2 || $cbopor == "") echo 'selected="selected"';?> >Nome do Paciente</option>
            				 </select>	
            <input type="submit" value="Pesquisar" name="btnpesq" />                 
            &nbsp;&nbsp;
			<input type="button" name="BtnCad" value="Cadastrar Paciente" <?php echo $habilitado; ?> onClick="javascript:window.location.href='index.php?p=frm_cadpac'">
        </form>
    </div>
<?php

   if (mysqli_num_rows($query) > 0){
//inicio tabela conteudo
      echo '<center><table width="98%" border="0" id="grid">';
         echo '<tr bgcolor="#D6D9DE">';
		     if($_SESSION["CdTpUsuario"] == 7){
			 	echo "<th>Laudo</th>";
				echo "<th>Anamnese</th>";
			 }
             echo "<th>CIH</th>";
			 echo "<th>Paciente</th>";
			 echo "<th>Nome da M&atilde;e</th>";
			 echo "<th>Data de Nascimento</th>";
			 echo "<th>Cidade</th>";
			 echo "<th>Alterar</th>";
             echo "<th>Excluir</th>";
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
			   	   $link = 'index.php?p=frm_cadpac&id='.$l[CdPaciente].'&acao=edit&first=1';
			   	   $link_del = 'index.php?p=frm_cadpac&id='.$l[CdPaciente].'&acao=del&first=1';
                   echo "<tr class=".$cortb.">";
				   if($_SESSION["CdTpUsuario"] == 7){
				   		/*echo "<td><a href='index.php?p=frm_prescricao&id=$l[CdPaciente]'>
				 <img src=\"imagens/document_add.png\" border=\"0\" title=\"Incluir Prescrição\" alt=\"Incluir Prescrição\" width=\"13\" height=\"13\" /></a></td>";*/
						 echo "<td align=\"center\"><a href='index.php?p=frm_laudo_oftalmo&id=$l[CdPaciente]'>
						 <img src=\"imagens/document_add.png\" border=\"0\" title=\"Laudar\" alt=\"Laudar\" width=\"13\" height=\"13\" /></a>
						 <a href='index.php?p=lista_laudo_oftalmo&id=$l[CdPaciente]'>
						 <img src=\"imagens/Print.ico\" border=\"0\" title=\"Imprimir Laudos\" alt=\"Imprimir Laudos\" width=\"13\" height=\"13\" /></a>
						 </td>";
				   }
				    if($_SESSION["CdTpUsuario"] == 7){
						 $ViewHistory = 'javascript:abrirpop("admin/rel_anamnese_oftalmo.php?id='.$l[CdPaciente].'","","790","550","yes")';
						 echo "<td align=\"center\">
						 			<a href='index.php?p=anamnese&id=$l[CdPaciente]'>
								 	<img src=\"imagens/document_add.png\" border=\"0\" title=\"Incluir\" alt=\"Incluir\" width=\"13\" height=\"13\" /></a>
						 			<a href='$ViewHistory'>
						 			<img src=\"imagens/Print.ico\" border=\"0\" title=\"Imprimir\" alt=\"Imprimir\" width=\"13\" height=\"13\" /></a>
						 </td>";
				   }
				   echo "<td>$l[CdPaciente]</td>";
                   echo "<td align=\"left\">$l[NmPaciente]</td>";
				   echo "<td align=\"left\">$l[NmMae]</td>";
				   $l["DtNasc"] = explode("-",$l["DtNasc"]);
				   $l["DtNasc"] = $l["DtNasc"][2]."/".$l["DtNasc"][1]."/".$l["DtNasc"][0];
				   echo "<td>$l[DtNasc]</td>";
				   echo "<td align=\"left\">$l[NmCidade]</td>";
				   echo '<td align="center"><a href="'.$link.'">
				   				<img src="imagens/b_edit.png" border="0" title="Alterar Registro" alt="Alterar"></a></td>';
                   echo '<td align="center"><a href="'.$link_del.'"><img src="imagens/b_drop.png" border="0" title="Excluir Registro" alt="Excluir"></a></td>';
                   echo "</a></tr>";
         }//fim enquanto
         echo "</table>";
         //fim da tabela

         $param = "&p=lista_pac&pesq=$busca&cbopesq=$cbopor";
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
          echo '<h3><center><font face="verdana","arial" color="FF6464">Nenhum paciente encontrado</font></center></h3>';
	  }
@mysqli_free_result($query);
@mysqli_close();
?>