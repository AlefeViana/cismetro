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
	
	function formatarCPF_CNPJ($campo, $formatado = true){  
		 //retira formato  
		 $codigoLimpo = ereg_replace("[' '-./ t]",'',$campo);  
		 // pega o tamanho da string menos os digitos verificadores  
		 $tamanho = (strlen($codigoLimpo) -2);  
		 //verifica se o tamanho do c�digo informado � v�lido  
		 if ($tamanho != 9 && $tamanho != 12){  
			 return false;  
		 }      
		 if ($formatado){  
			 // seleciona a m�scara para cpf ou cnpj  
			 $mascara = ($tamanho == 9) ? '###.###.###-##' : '##.###.###/####-##';   
			 $indice = -1;  
			 for ($i=0; $i < strlen($mascara); $i++) {  
				 if ($mascara[$i]=='#') $mascara[$i] = $codigoLimpo[++$indice];  
			 }  
			 //retorna o campo formatado  
			 $retorno = $mascara;      
		 }else{  
			 //se n�o quer formatado, retorna o campo limpo  
			 $retorno = $codigoLimpo;  
		 }  
	   return $retorno;  
	} 
	function formatarIE($campo, $formatado = true){  
		 //retira formato  
		 $codigoLimpo = ereg_replace("[' '-./ t]",'',$campo);  
		 // pega o tamanho da string menos os digitos verificadores  
		 $tamanho = (strlen($codigoLimpo) -4);  
		 //verifica se o tamanho do c�digo informado � v�lido  
		 if ($tamanho != 9){  
			 return false;  
		 }      
		 if ($formatado){  
			 // seleciona a m�scara para IE  
			 $mascara = ($tamanho == 9) ? '###.###.###-####' : '###.###.###-####';   
			 $indice = -1;  
			 for ($i=0; $i < strlen($mascara); $i++) {  
				 if ($mascara[$i]=='#') $mascara[$i] = $codigoLimpo[++$indice];  
			 }  
			 //retorna o campo formatado  
			 $retorno = $mascara;      
		 }else{  
			 //se n�o quer formatado, retorna o campo limpo  
			 $retorno = $codigoLimpo;  
		 }  
	   return $retorno;  
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
        $sql = "SELECT CdForn, NmForn, IE, CNPJ, Telefone
                FROM tbfornecedor";
    if ($busca != ""){
		switch ($cbopor){
			case 1: $sql .= " WHERE CdForn = $busca";
					break;
			case 2: $sql .= " WHERE NmForn LIKE '$busca%'";
					break;
		}
    }

//echo $sql;
//executa a consulta
    $query = mysqli_query($db,$sql)or die (TrataErro(mysqli_errno(),'','index.php?p=inicial','lista_for:qtd regs'));

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
    $limsql = $sql." ORDER BY NmForn LIMIT $inicio, $lpp ";
    $query = mysqli_query($db,$limsql)or die(TrataErro(mysqli_errno(),'','index.php?p=inicial','lista_for:consulta dados'));
//inicio do form de pesquisa
?>
	<div id="rotina" style="text-align:center">
    	Cadastro de Fornecedor<br /><br />
    </div>
	<div id="frm_pesq">
    	<form action="index.php?p=lista_for" method="post">
        	Pesquisar&nbsp;<input type="text" name="pesq" value="<?php echo $busca;?>" />
            &nbsp; por &nbsp;<select name="cbopesq">
            					<option value="1" <?php if ($cbopor == 1) echo 'selected="selected"';?> >C&oacute;digo</option>
                                <option value="2" <?php if ($cbopor == 2 || $cbopor == "") echo 'selected="selected"';?> >Nome do Fornecedor</option>
            				 </select>	
            <input type="submit" value="Pesquisar" name="btnpesq" />                 
            &nbsp;&nbsp;
			<input type="button" name="BtnCad" value="Cadastrar Fornecedor" onClick="javascript:window.location.href='index.php?p=frm_cadfor'">
        </form>
    </div>
<?php

   if (mysqli_num_rows($query) > 0){
//inicio tabela conteudo
      echo '<center><table width="98%" border="0" id="grid">';
         echo '<tr bgcolor="#D6D9DE">';
             echo "<th>C&oacute;digo</th>";
			 echo "<th>Fornecedor</th>";
			 echo "<th>CNPJ</th>";
			 echo "<th>IE</th>";
			 echo "<th>Contratos</th>";
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
			   	   $link = 'index.php?p=frm_cadfor&id='.$l[CdForn].'&acao=edit&first=1';
				   $link_con = 'index.php?p=lista_contrato&id_forn='.$l[CdForn];
			   	   $link_del = 'index.php?p=frm_cadfor&id='.$l[CdForn].'&acao=del&first=1';
                   echo "<tr class=".$cortb.">";

				   echo "<td>$l[CdForn]</td>";
                   echo "<td align=\"left\">$l[NmForn]</td>";
				   echo "<td align=\"left\">".formatarCPF_CNPJ($l["CNPJ"])."</td>";
				   $l["ValContrato"] = explode("-",$l["ValContrato"]);
				   $l["ValContrato"] = $l["ValContrato"][2]."/".$l["ValContrato"][1]."/".$l["ValContrato"][0];
				   echo "<td>".formatarIE($l[IE])."</td>";
				   echo "<td align=\"center\"><a href=\"$link_con\"><img src=\"imagens/Document.png\" width=\"25\" height=\"25\" title=\"Visualizar Contratos\" /></a></td>";
				   echo '<td align="center"><a href="'.$link.'">
				   				<img src="imagens/b_edit.png" border="0" title="Alterar Registro" alt="Alterar"></a></td>';
                   echo '<td align="center"><a href="'.$link_del.'"><img src="imagens/b_drop.png" border="0" title="Excluir Registro" alt="Excluir"></a></td>';
                   echo "</a></tr>";
         }//fim enquanto
         echo "</table>";
         //fim da tabela

         $param = "&p=lista_for&pesq=$busca&cbopesq=$cbopor";
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
                echo ' | <a href='.$url.'>Próxima</a></center>';
         }
      }//fim do if
      else{
          echo '<h3><center><font face="verdana","arial" color="FF6464">Nenhum fornecedor encontrado</font></center></h3>';
	  }
@mysqli_free_result($query);
@mysqli_close();
?>