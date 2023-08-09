<!-- @autor: Renato Ayres 
// criação 
#data: 08/07/2011 #hora 12:48 
-->


 <?php 
  // funções 
  //include "funcoes.php";
  require('conecta.php');
  
  // controles inserção, alteração, exclusão
  $ac = $_GET['ac']; 
  switch($ac)
  {
    case 'i': // inserção 	      
	 $cdpref = $_POST['cdpref'];
     $dtinicio = FormataDataBD($_POST['dtinicio']);
     $dttermino =  FormataDataBD($_POST['dttermino']);
     $vltetoppi = moeda($_POST['vltetoppi']);  
	
     $sql = mysqli_query($db,"INSERT INTO `tbtetoppi` (`cdpref`, `dtinicio`, `dttermino`, `vltetoppi`) 
	 VALUES ('$cdpref', '$dtinicio', '$dttermino', '$vltetoppi')") or die (mysqli_error()); 
	
	 if($sql)
		echo '<script language="JavaScript" type="text/javascript"> 
				alert("Cadastro realizado com sucesso!");
				window.location.href="index.php?i=30";				
			</script>';	 	
    break;  
	
	 case 'e': // inserção 	      
	 $cdtetoppi = $_GET['cd'];
     $sql = mysqli_query($db,"DELETE FROM `tbtetoppi` WHERE (`cdtetoppi`='$cdtetoppi')") or die (mysqli_error()); 
	 if($sql)
		echo '<script language="JavaScript" type="text/javascript"> 
				alert("Exclusão realizada com sucesso!");
				window.location.href="index.php?i=30";				
			</script>';	 	
    break;  
  }
  
  ?> 
  
  
    <!-- Formulário PROCIS MES -->
	<script type="text/javascript"> 
	$(document).ready(function() {	
											   
			$("#form").validate();
				$("input[id=jan]").maskMoney({symbol:"R$",decimal:",",thousands:"."}); 
				$("input[id=fev]").maskMoney({symbol:"R$",decimal:",",thousands:"."});
				$("input[id=marc]").maskMoney({symbol:"R$",decimal:",",thousands:"."}); 
				$("input[id=abr]").maskMoney({symbol:"R$",decimal:",",thousands:"."}); 
				$("input[id=mai]").maskMoney({symbol:"R$",decimal:",",thousands:"."}); 
				$("input[id=jun]").maskMoney({symbol:"R$",decimal:",",thousands:"."}); 
				$("input[id=jul]").maskMoney({symbol:"R$",decimal:",",thousands:"."}); 
				$("input[id=ago]").maskMoney({symbol:"R$",decimal:",",thousands:"."}); 
				$("input[id=set]").maskMoney({symbol:"R$",decimal:",",thousands:"."}); 
				$("input[id=out]").maskMoney({symbol:"R$",decimal:",",thousands:"."}); 
				$("input[id=nov]").maskMoney({symbol:"R$",decimal:",",thousands:"."}); 
				$("input[id=dez]").maskMoney({symbol:"R$",decimal:",",thousands:"."});
			});
			jQuery(function($){
				$("#dtinicio").mask("99/99/9999");
				$("#dttermino").mask("99/99/9999");
				
				
			});
 
	</script>
<?php
	require('conecta.php');  
	$sqlcid = mysqli_query($db,"SELECT
				tbprefeitura.NmCidade
				FROM
				tbprefeitura
				WHERE tbprefeitura.CdPref = $_GET[cdpref]");
	while($l = mysqli_fetch_array($sqlcid))
	{
		$nmcid = $l[NmCidade];
	}

?>  
    <h1> Controle &raquo; PROCIS &raquo;  <?php echo $nmcid ?><span> <a href="?i=71" title="Retornar ao Cadastro de Procis"> &laquo; <b>voltar</b> </a> </span> </h1>
    <form action="" id="form" method="post"> 
      <label class="md"> Janeiro <input name='jan' type="text" id="jan">  </label>
      <label class="md"> Fevereiro <input type="text"  name='fev' id="fev">  </label>
      <label class="md"> Março <input type="text" name="marc"  id="marc" >  </label>
      <label class="md"> Abril <input type="text" name="abr"  id="abr" >  </label>
      <label class="md" style="clear:both"> Maio <input type="text" name="mai"  id="mai"  >  </label>
      <label class="md"> Junho <input type="text" name="jun"  id="jun"  >  </label>
      <label class="md"> Julho <input type="text" name="jul"  id="jul"  >  </label>
      <label class="md"> Agosto <input type="text" name="ago"  id="ago"  >  </label>
      <label class="md" style="clear:both"> Setembro <input type="text" name="set"  id="set"  >  </label>
      <label class="md"> Outubro <input type="text" name="out"  id="out"  >  </label>
      <label class="md"> Novembro <input type="text" name="nov"  id="nov"  >  </label>
      <label class="md"> Dezembro <input type="text" name="dez"  id="dez"  >  </label>
      	
     <div id='btns'> <input type="submit" value="Cadastrar" /> </div>
     </form>
     <!-- **************************************************************** -->
