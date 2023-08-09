<?php
	require_once("verifica.php");
	
	//funcao para tratar erro
	require("admin/function_trata_erro.php");
	
   //verifica se o usuario tem permissão para acessar a pagina
   if ((int)$_SESSION["CdTpUsuario"] != 7)	
   {
		echo '<script language="JavaScript" type="text/javascript"> 
			window.location.href="index.php?p=inicial";				
		  </script>';	
   }
   
//formata data p/ data BR
	function fdata($data){
		$data = explode("-",$data);
		return $data[2]."/".$data[1]."/".$data[0];
	}
	
//recebe variavel com a id do paciente
	$CdPaciente = (int)$_GET["id"];

//conecta no banco
    require_once("conecta.php");
           
//consulta
        $sql = "SELECT l.CdPaciente,NmPaciente,l.DtInc,l.UserInc,u.NmUsuario
                FROM tblaudo l INNER JOIN tbpaciente p ON l.CdPaciente = p. CdPaciente
							   INNER JOIN tbusuario u ON l.UserInc = u.CdUsuario
				WHERE Tabela='tblaudooftalmo'";
	if ($CdPaciente > 0) 
		$sql .= " AND l.CdPaciente=$CdPaciente";

    //echo $sql;
//executa a consulta
    $query = mysqli_query($db,$sql)or die (TrataErro(mysqli_errno(),'','index.php?p=inicial','lista_laudo_oftalmo:qtd regs'));

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
    $limsql = $sql." ORDER BY DtInc DESC LIMIT $inicio, $lpp ";
    $query = mysqli_query($db,$limsql)or die(TrataErro(mysqli_errno(),'','index.php?p=inicial','lista_laudo_oftalmo:consulta dados'));
//inicio do form de pesquisa
?>
	<div id="rotina" style="text-align:center">
    	Lista de Laudos<br /><br />
    </div>
<?php

   if (mysqli_num_rows($query) > 0){
//inicio tabela conteudo
      echo '<center><table width="98%" border="0" id="grid">';
         echo '<tr bgcolor="#D6D9DE">';
             echo "<th>C&oacute;digo</th>";
			 echo "<th>Paciente</th>";
			 echo "<th>Data</th>";
			 echo "<th>M&eacute;dico</th>";
			 echo "<th>Exibir</th>";
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
			   //link----------------
			   	   
				   $link = 'javascript:abrirpop("admin/rel_laudo_oftalmo.php?id='.$l[CdPaciente].'_'.$l[DtInc].'","","790","550","yes")';
                   echo "<tr class=".$cortb.">";
				   echo "<td align=\"left\">$l[CdPaciente]</td>";
				   echo "<td align=\"left\">$l[NmPaciente]</td>";
				   echo "<td align=\"center\">".fdata(substr($l[DtInc],0,10))."</td>";
				   echo "<td align=\"left\">$l[NmUsuario]</td>";                   
				   echo "<td align=\"center\"><a href='$link'>
				   			<img src=\"imagens/Print.ico\" border=\"0\" title=\"Visualizar Laudo\" alt=\"Exibir\" width=\"13\" height=\"13\"></a></td>";
                   echo "</tr>";
         }//fim enquanto
         echo "</table>";
         //fim da tabela

         $param = "&p=lista_laudo_oftalmo&pesq=$busca&cbopesq=$cbopor&id=$CdPaciente";
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
          echo '<h3><center><font face="verdana","arial" color="FF6464">Nenhum laudo encontrado</font></center></h3>';
	  }
@mysqli_free_result($query);
@mysqli_close();
?>