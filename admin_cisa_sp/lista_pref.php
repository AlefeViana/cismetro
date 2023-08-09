<?php
	require_once("verifica.php");
	
	//funcao para tratar erro
	require("admin/function_trata_erro.php");
	
	//verifica se o usuario tem permissão para acessar a pagina
	if ((int)$_SESSION["CdTpUsuario"] != 1)	
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
/*
SELECT p.CdPref,NmCidade,SUM(Credito)-SUM(Debito) as Saldo 
FROM `tbprefeitura` p LEFT JOIN tbmovimentacao m ON p.CdPref=m.CdPref 
WHERE p.`CdPref` in (
  SELECT CdOrigem
  FROM tbusuario 
  WHERE CdOrigem <> 'NULL' AND Status='1'
)
GROUP BY p.CdPref,NmCidade

*/
	$sql = "SELECT p.CdPref,NmCidade,LimiteMax,SUM(Credito)-SUM(Debito) as Saldo 
			FROM tbprefeitura p LEFT JOIN tbmovimentacao m ON p.CdPref=m.CdPref 
			WHERE p.CdPref in (SELECT CdOrigem
							   FROM tbusuario 
							   WHERE CdOrigem <> 'NULL' AND Status='1'
							  )
			";
			
    if ($busca != ""){
		switch ($cbopor){
			case 1: $sql .= " AND p.CdPref = $busca";
					break;
			case 2: $sql .= " AND NmCidade LIKE '$busca%'";
					break;
		}
    }
	
	$sql .= " GROUP BY p.CdPref,NmCidade,LimiteMax";

    //echo $sql;
//executa a consulta
    $query = mysqli_query($db,$sql)or die (TrataErro(mysqli_errno(),'','index.php?p=inicial','lista_pref:qtd regs'));

//obtem o numero de linhas da consulta
    $qtdreg = mysqli_num_rows($query);
	//echo "Registros ".$qtdreg;

// Especifique quantos resultados você quer por página
    $lpp = 50;

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
    $limsql = $sql." ORDER BY NmCidade LIMIT $inicio, $lpp ";
    $query = mysqli_query($db,$limsql)or die(TrataErro(mysqli_errno(),'','index.php?p=inicial','lista_pref:consulta dados'));
//inicio do form de pesquisa
?>
	<div id="rotina" style="text-align:center">
    	Saldo das Prefeituras<br /><br />
    </div>
	<div id="frm_pesq">
    	<form action="index.php?p=lista_pref" method="post">
        	Pesquisar&nbsp;<input type="text" name="pesq" value="<?php echo $busca;?>" />
            &nbsp; por &nbsp;<select name="cbopesq">
            					<option value="1" <?php if ($cbopor == 1) echo 'selected="selected"';?> >C&oacute;digo</option>
                                <option value="2" <?php if ($cbopor == 2 || $cbopor == "") echo 'selected="selected"';?> >Cidade</option>
            				 </select>	
            <input type="submit" value="Pesquisar" name="btnpesq" />                         
        </form>
    </div>
<?php

   if (mysqli_num_rows($query) > 0){
//inicio tabela conteudo
      echo '<center><table width="98%" border="0" id="grid">';
         echo '<tr bgcolor="#D6D9DE">';
             echo "<th>C&oacute;digo</th>";
			 echo "<th>Cidade</th>";
			 echo "<th>Saldo</th>";
			 echo "<th>Limite Max.</th>";
			 echo "<th>Cr&eacute;dito</th>";
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
			   	   $link = 'index.php?p=frm_cadpref&id='.$l[CdPref].'&acao=edit';	
                   echo "<tr class=".$cortb.">";
					   echo "<td>$l[CdPref]</td>";
					   echo "<td align=\"left\">$l[NmCidade]</td>";
					   echo "<td align=\"center\">".number_format($l[Saldo],2,',','.')."</td>";
					   echo "<td align=\"center\">".number_format($l[LimiteMax],2,',','.')."</td>";
					   				
					   $Credito = $l[Saldo] + $l[LimiteMax];					 
							
					   echo "<td align=\"center\">".number_format($Credito,2,',','.')."</td>";
					   echo "<td align=\"center\">
					   		  <a href=\"index.php?p=frm_addmoney&id=$l[CdPref]\">
					   			<img src=\"imagens/dinheiro.png\" width=\"25\" height=\"25\" title=\"Movimenta&ccedil;&atilde;o Financeira\" alt=\"Adicionar dinheiro\" />
							  </a>	
							</td>";
					   echo '<td align="center"><a href="'.$link.'">
				   				<img src="imagens/b_edit.png" border="0" title="Alterar Registro" alt="Alterar"></a></td>';		
				   echo"</tr>";
         }//fim enquanto
         echo "</table>";
         //fim da tabela

         $param = "&p=lista_pref&pesq=$busca&cbopesq=$cbopor";
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
          echo '<h3><center><font face="verdana","arial" color="FF6464">Nenhuma prefeitura encontrada</font></center></h3>';
	  }
@mysqli_free_result($query);
@mysqli_close();
?>