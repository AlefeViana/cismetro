<?php

	define("DIRECT_ACCESS", true);

	require_once("verifica.php");
	
	//funcao para tratar erro
	require("function_trata_erro.php");
	
	//verifica se o usuario tem permiss�o para acessar a pagina
	if ( (int)$_SESSION["CdTpUsuario"] != 1 && (int)$_SESSION["CdTpUsuario"] != 2 && (int)$_SESSION["CdTpUsuario"] != 3 &&
		 (int)$_SESSION["CdTpUsuario"] != 4 )	
	{
		echo '<script language="JavaScript" type="text/javascript"> 
			window.location.href="../index.php?p=inicial";				
		  </script>';	
	}	
?>
<script type="text/javascript" src="../js/jquery-1.4.2.min.js"></script>	
<script type="text/javascript" src="../js/jquery.maskedinput-1.2.2.min.js"></script>
<script type="text/javascript"> 
$(document).ready(function() {	
	$("#BtnImp").click(function(){
		$("#frm_pesq").hide();
		window.print();
		$("#frm_pesq").show();
	});
	$("#BtnVoltar").click(function(){
		window.location.href="../index.php?p=inicial";
	});
});
</script>
<?php
//conecta no banco
    require_once("../conecta.php");
    
//consulta fornecedor
        $sql = "SELECT NmReduzido, Telefone, NmCidade, Logradouro, Numero, Compl, Bairro
                FROM tbfornecedor f INNER JOIN tbprefeitura p ON f.CdCidade=p.CdPref
				WHERE f.Status='1'
				ORDER BY NmCidade,NmReduzido";

//executa a consulta
    $query = mysqli_query($db,$sql)or die (TrataErro(mysqli_errno(),'','../index.php?p=inicial','rel_agendamento:consulta dados'));
?>
<div id="logo" style="position:relative; vertical-align:top;"><img src="../imagens/consaude_online.png" border="0" alt="ConsaudeOnline" /></div>
<div id="rotina" style="text-align:center">
    	Lista de Fornecedores<br /><br />
</div>
<div id="frm_pesq" style="height:40px; text-align:center">
  
			<input type="button" name="BtnImp" id="BtnImp" value="Imprimir" />
            &nbsp;
			<input type="button" name="BtnVoltar" id="BtnVoltar" value="Voltar" />
            
</div>
<?php
//inicio do form de pesquisa
   if (mysqli_num_rows($query) > 0){
	   
//inicio tabela conteudo
      echo '<center><table width="100%" border="1" cellspacing="0" cellpadding="0">';
         echo '<tr bgcolor="#D6D9DE">';
		 	 echo "<th>Nome</th>";
			 echo "<th>Telefone</th>";
             echo "<th>Endere&ccedil;o</th>";
			 echo "<th>Cidade</th>";			 
         echo "</tr>";
        //cor da tabela
         $cortb = "#D5F4F4";
		 
         while($l = mysqli_fetch_array($query)){
               if ($cortb == "#D5F4F4"){
                   $cortb = "#FFFFFF";
               }
               else{
                   $cortb = "#D5F4F4";
               }		
			   
                   echo "<tr bgcolor=".$cortb.">";
				   echo "<td>&nbsp;$l[NmReduzido]</td>";
				   echo "<td align=\"center\">&nbsp;(".substr($l[Telefone],0,2).")".substr($l[Telefone],2,4)."-".substr($l[Telefone],6,4)."</td>";				   				
				   echo "<td >&nbsp;$l[Logradouro], $l[Numero] - $l[Compl], $l[Bairro]</td>";
                   echo "<td align=\"left\">&nbsp;$l[NmCidade]</td>";				  
				   echo "</tr>";				   
         }//fim enquanto		 
         echo "</table>";
         //fim da tabela
         
      }//fim do if
      else{
          echo '<h3><center><font face="verdana","arial" color="FF6464">Nenhum fornecedor encontrado</font></center></h3>';
	  }
@mysqli_free_result($query);
@mysqli_close();
?>