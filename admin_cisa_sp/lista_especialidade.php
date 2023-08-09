<?php
	require_once("verifica.php");
	
	//funcao para tratar erro
	require("admin/function_trata_erro.php");
	
	//verifica se o usuario tem permissão para acessar a pagina
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
		
		if ($cbopor == 1){
			$busca = (int)$busca;	
		}
       
//consulta
        $sql = "SELECT CdEspec, NmEspec, Status
                FROM tbespecialidade";
    if ($busca != ""){
		switch ($cbopor){
			case 1: $sql .= " WHERE CdEspec = $busca";
					break;
			case 2: $sql .= " WHERE NmEspec LIKE '$busca%'";
					break;
		}
    }

    //echo $sql;
//executa a consulta
    $query = mysqli_query($db,$sql)or die (TrataErro(mysqli_errno(),'','index.php?p=inicial','lista_especialidade:qtd regs'));

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
    $limsql = $sql." ORDER BY NmEspec LIMIT $inicio, $lpp ";
    $query = mysqli_query($db,$limsql)or die(TrataErro(mysqli_errno(),'','index.php?p=inicial','lista_especialidade:consulta dados'));
//inicio do form de pesquisa
?>
	<div id="rotina" style="text-align:center">
    	Cadastro de Especialidade<br /><br />
    </div>
	<div id="frm_pesq">
    	<form action="index.php?p=lista_espec" method="post">
        	Pesquisar&nbsp;<input type="text" name="pesq" value="<?php echo $busca;?>" />
            &nbsp; por &nbsp;<select name="cbopesq">
            					<option value="1" <?php if ($cbopor == 1) echo 'selected="selected"';?> >C&oacute;digo</option>
                                <option value="2" <?php if ($cbopor == 2 || $cbopor == "") echo 'selected="selected"';?> >Nome da Especialidade</option>
            				 </select>	
            <input type="submit" value="Pesquisar" name="btnpesq" />                 
            &nbsp;&nbsp;
			<input type="button" name="BtnCad" value="Cadastrar Especialidade" onClick="javascript:window.location.href='index.php?p=frm_cadespec'">
        </form>
    </div>
<?php

   if (mysqli_num_rows($query) > 0){
//inicio tabela conteudo
      echo '<center><table width="98%" border="0" id="grid">';
         echo '<tr bgcolor="#D6D9DE">';
		 	 echo "<th></th>";
             echo "<th>C&oacute;digo</th>";
			 echo "<th>Especialidade</th>";
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
			   	   $link = 'index.php?p=frm_cadespec&id='.$l[CdEspec].'&acao=edit';
			   	   $link_del = 'index.php?p=frm_cadespec&id='.$l[CdEspec].'&acao=del';
                   echo "<tr class=".$cortb.">";
				   if($l["Status"] == 1)
				   		echo "<td><img src=\"imagens/aguardando.gif\" width=\"13\" height=\"13\" title=\"Ativo\" /></td>";
				   else
				   		echo "<td><img src=\"imagens/cancelado.gif\" width=\"13\" height=\"13\" title=\"Inativo\" /></td>";
				   		
				   echo "<td>$l[CdEspec]</td>";
                   echo "<td align=\"left\">$l[NmEspec]</td>";
				   echo '<td align="center"><a href="'.$link.'">
				   				<img src="imagens/b_edit.png" border="0" title="Alterar Registro" alt="Alterar"></a></td>';
                   echo '<td align="center"><a href="'.$link_del.'"><img src="imagens/b_drop.png" border="0" title="Excluir Registro" alt="Excluir"></a></td>';
                   echo "</a></tr>";
         }//fim enquanto
         echo "</table>";
         //fim da tabela

         $param = "&p=lista_espec&pesq=$busca&cbopesq=$cbopor";
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
          echo '<h3><center><font face="verdana","arial" color="FF6464">Nenhuma especialidade encontrada</font></center></h3>';
	  }
@mysqli_free_result($query);
@mysqli_close();
?>