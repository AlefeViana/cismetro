<?php

    define("DIRECT_ACCESS",  true);

	require_once("verifica.php");
//conecta no banco
    require_once("conecta.php");
//funcao para tratar erro
	require("admin/function_trata_erro.php");	

//variavel do querystring
	$CdForn = $_GET["id_forn"];

//variavel do form de busca

        $busca    = $_REQUEST["pesq"];
		$cbopor   = (int)$_REQUEST["cbopesq"];
		
		if ($cbopor == 1){
			$busca = (int)$busca;	
		}
       
//consulta
        $sql = "SELECT CdContrato, Descricao, DtValidade, NmForn, NmResp, TelResp
                FROM tbcontrato c INNER JOIN tbfornecedor f ON c.CdForn=f.CdForn";
    if ($busca != ""){
		switch ($cbopor){
			case 1: $sql .= " WHERE CdContrato = $busca";
					break;
			case 2: $sql .= " WHERE Descricao LIKE '%$busca%'";
					break;
		}
    }

	if (is_numeric($CdForn))
	{
		$sql .= " AND c.CdForn=".$CdForn;		
	}

    //echo $sql;
//executa a consulta
    $query = mysqli_query($db,$sql)or die (TrataErro(mysqli_errno(),'','index.php?p=lista_for','lista_contrato:select qtd contratos'));

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
    $limsql = $sql." ORDER BY DtValidade DESC, NmForn LIMIT $inicio, $lpp ";
    $query = mysqli_query($db,$limsql)or die(TrataErro(mysqli_errno(),'','index.php?p=lista_for','lista_contrato:select dados'));
//inicio do form de pesquisa
	$link_pesq = 'index.php?p=lista_contrato';
	if($CdForn > 0)
		$link_pesq .= "&id_forn=$CdForn";
?>
	<div id="rotina" style="text-align:center">
    	Contratos<br /><br />
    </div>
	<div id="frm_pesq">
    	<form action="<?php echo $link_pesq; ?>" method="post">
        	Pesquisar&nbsp;<input type="text" name="pesq" value="<?php echo $busca;?>" />
            &nbsp; por &nbsp;<select name="cbopesq">
            					<option value="1" <?php if ($cbopor == 1) echo 'selected="selected"';?> >C&oacute;digo</option>
                                <option value="2" <?php if ($cbopor == 2 || $cbopor == "") echo 'selected="selected"';?> >Descri&ccedil;&atilde;o</option>
            				 </select>	
            <input type="submit" value="Pesquisar" name="btnpesq" />                 
            &nbsp;&nbsp;
 <?php
 	if((int)$CdForn > 0) $param = "&id_forn=$CdForn";
 	$link_cad = "javascript:window.location.href='index.php?p=frm_cadcontrato$param'";
 ?>           
			<input type="button" name="BtnCad" value="Cadastrar Contrato" onClick="<?php echo $link_cad; ?>">
        </form>
    </div>
<?php

   if (mysqli_num_rows($query) > 0){
//inicio tabela conteudo
      echo '<center><table width="98%" border="0" id="grid">';
         echo '<tr bgcolor="#D6D9DE">';
             echo "<th>C&oacute;digo</th>";
			 echo "<th>Descri&ccedil;&atilde;o</th>";
			 echo "<th>Dt. Validade</th>";
			 echo "<th>Fornecedor</th>";
             echo "<th>Respons&aacute;vel</th>";
			 echo "<th>Telefone</th>";
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
			   	   $link = 'index.php?p=frm_cadcontrato&id='.$l[CdContrato].'&acao=edit';
			   	   $link_del = 'index.php?p=frm_cadcontrato&id='.$l[CdContrato].'&acao=del';
                   echo "<tr class=".$cortb.">";
				   echo "<td>$l[CdContrato]</td>";
                   echo "<td align=\"left\">$l[Descricao]</td>";
				   $l["DtValidade"] = explode("-",$l["DtValidade"]);
				   $l["DtValidade"] = $l["DtValidade"][2]."/".$l["DtValidade"][1]."/".$l["DtValidade"][0];
				   echo "<td align=\"center\">$l[DtValidade]</td>";
				   echo "<td align=\"left\">$l[NmForn]</td>";
				   echo "<td align=\"left\">$l[NmResp]</td>";
				   if ($l["TelResp"] != '')
				   		$l["TelResp"] = '('.substr($l["TelResp"],0,2).')'.substr($l["TelResp"],2,4).'-'.substr($l["TelResp"],6,4);
				   echo "<td align=\"left\">$l[TelResp]</td>";
				   echo '<td align="center"><a href="'.$link.'">
				   				<img src="imagens/b_edit.png" border="0" title="Alterar Registro" alt="Alterar"></a>
						</td>';                 
                   echo "</tr>";
         }//fim enquanto
         echo "</table>";
         //fim da tabela

         $param = "&p=lista_contrato&pesq=$busca&cbopesq=$cbopor&id_forn=$CdForn";
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
          echo '<h3><center><font face="verdana","arial" color="FF6464">Nenhum contrato encontrado</font></center></h3>';
	  }
@mysqli_free_result($query);
@mysqli_close();
?>