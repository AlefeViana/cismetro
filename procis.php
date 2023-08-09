<!-- @autor: Renato Ayres 
// criação 
#data: 08/07/2011 #hora 12:48 
-->



 <?php 
  // funções 
  include "funcoes.php";
  #### Verifica se vai cadastrar a procis geral ou por mes
  if($_GET[pag] == 2)
  	include("procis_frm.php");
  else{
  
  
  
  // controles inserção, alteração, exclusão
  $ac = $_GET['ac']; 
  switch($ac)
  {
    case 'i': // inserção 	      
	 $cdpref = $_POST['cdpref'];
	 $ano = $_POST[ano];
     $dtinicio = FormataDataBD($_POST['dtinicio']);
     $dttermino =  FormataDataBD($_POST['dttermino']);
     $vprocis = moeda($_POST['vprocis']);  
	
     $sql = mysqli_query($db,"INSERT INTO `tbprocis` (`vprocis`, `cdpref`, `ano`) 
	 VALUES ('$vprocis', '$cdpref', '$ano')") or die (mysqli_error()); 
	
	 if($sql)
		echo '<script language="JavaScript" type="text/javascript"> 
				alert("Cadastro realizado com sucesso!");
				window.location.href="index.php?i=71";				
			</script>';	 	
    break;  
	
	 case 'e': // exclusão      
	 $cdprocis = $_GET['cd'];
     $sql = mysqli_query($db,"DELETE FROM `tbprocis` WHERE (`cdprocis`='$cdprocis')") or die (mysqli_error()); 
	 if($sql)
		echo '<script language="JavaScript" type="text/javascript"> 
				alert("Exclusão realizada com sucesso!");
				window.location.href="index.php?i=71";				
			</script>';	 	
    break;  
  } 
  ?> 
  
  
    <!-- Formulário TETO PPI -->
	<script type="text/javascript"> 
	$(document).ready(function() {	
											   
				$("#form").validate({
									
									rules: {
										
										"dtinicio": {
												dateBR: true
										},
										"dttermino": {
												dateBR: true
										}
									},
									messages: {
										
										"dtinicio": {
												dateBR: "Data inválida."
										},
										"dttermino": {
												dateBR: "Data inválida."
										}
									},
									
									
									});
				
				
				
					$("#form").validate();
				
				
				
				$("input[id=vprocis]").maskMoney({symbol:"R$",decimal:",",thousands:"."}); 
				
				
			});
			jQuery(function($){
				$("#dtinicio").mask("99/99/9999");
				$("#dttermino").mask("99/99/9999");
				
				
			});
	</script>
    <h1> Controle &raquo; PROCIS</h1> 
    
    <div id="form">
    <div id="alert"> Os Campos com * devem ser preechidos obrigatóriamente </div>
			   
    <form action="?i=71&ac=i" id="form" method="post"> 
      <label class="pq"> Ano <input name='ano' type="text" class="data required" id="ano" value="<?php echo date("Y"); ?>" maxlength="4">  </label>
      <label class="md"> Valor* : <input type="text" name="vprocis"  id="vprocis" class="required"  >  </label>
      <label>Cidade* : 
        <select name="cdpref" id="cdpref" class="required">
        <option value="">Selecione</option>
        <?php 
            //limpa variavel que mantem os dados digitados
            unset($_SESSION["dados_pac"]);
            require("conecta.php");
            
            $sql = "SELECT CdPref, NmCidade FROM tbprefeitura 
            WHERE consorciado='S'
            ORDER BY NmCidade";
        
            $qry = mysqli_query($db,$sql) or die ((mysqli_errno()));
            if (mysqli_num_rows($qry) > 0){
                while ($dados = mysqli_fetch_array($qry)){
                    if ($dados_pac["CdPref"] == $dados["CdPref"])
                        echo '<option value="'.$dados["CdPref"].'" selected="selected">'.$dados["NmCidade"].'</option>';	
                    else
                        echo '<option value="'.$dados["CdPref"].'">'.$dados["NmCidade"].'</option>';
                }
            } 
            mysqli_close();
            mysqli_free_result($qry);
        ?>
        </select>   
      </label>	
     <div id='btns'> <input type="submit" value="Cadastrar" /> </div>
     </form>
     <!-- **************************************************************** -->

<?php 	
    
	  
     require('conecta.php');
     $sql = mysqli_query($db,"SELECT
							tbprefeitura.NmCidade,
							tbprefeitura.CdPref,
							tbprocis.vprocis,
							tbprocis.cdprocis,
							tbprocis.ano
						FROM
							tbprefeitura
							Inner Join tbprocis ON tbprefeitura.CdPref = tbprocis.cdpref

") or die (mysqli_error());
	
	if(mysqli_num_rows($sql)>0)
	{  
	
	echo "<table id='table'>
      <tr>
        <th> Código </th>
        <th> Municipio </th>
        <th> Ano </th>
        <th> Data Término  </th>
        <th> Valor  </th>
        <th> PROCIS/MÊS  </th>
        <th> Excluir  </th>
      </tr>";
	 while($lin = mysqli_fetch_array($sql))
	 {
	  $dtinicio = FormataDataBR($lin[dtinicio]);
	  $dttermino = FormataDataBR($lin[dttermino]);
	  $vprocis = number_format($lin["vprocis"],2,',','.');

	  echo"<tr>
        <td style='text-align:center'> $lin[cdprocis] </td>
        <td> $lin[NmCidade] </td>
        <td style='text-align:center'> $lin[ano]  </td>
        <td style='text-align:center'> $dttermino  </td>      
		<td style='text-align:center'	> $vprocis  </td>
		<td style='text-align:center'> <a href='?i=71&pag=2&cdpref=$lin[CdPref]' >  <img src='img/add.png' width='15' height='15' /> </a>   </td>
        <td style='text-align:center'> <a href='?i=71&ac=e&cd=$lin[cdtetoppi]'> <img src='img/icon_excluir.png' width='15' height='15' /> </a>  </td>
       </tr>";
	 }
	 echo "</table>";
	} else { echo "<div id='alert'> Nenhum resultado encontrado </div>";    }
    //<a href=?i=71&ac=1&CdPref=$lin[CdPref]&data1=$dtinicio&data2=$dttermino> </a>
}#### fecha o if da verificação
?>