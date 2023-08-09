<!-- @autor: Renato Ayres 
// criação 
#data: 08/07/2011 #hora 12:48 
-->

<script type="text/javascript" src="js2/ui/minified/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="js2/localization/jquery.ui.datepicker-pt-BR.js"></script>
<link rel="stylesheet" href="css/themes/base/jquery.ui.datepicker.css">
<link rel="stylesheet" href="css/themes/base/jquery.ui.theme.css">
<link rel="stylesheet" href="css/themes/base/jquery.ui.all.css">


 <?php 
  // funções 
  include "funcoes.php";

  
 // ATUALIZA A TABELA TBAGENDA CONS
if($_GET[ac]==1) 
{ 

 $CdPref = $_GET['CdPref'];
	$data1 = FormataDataBD($_GET[data1]);
	$data2 =  FormataDataBD($_GET[data2]);
 
 
	$sql_m = mysqli_query($db, "SELECT DISTINCT  pr.CdPref, pr.NmCidade			  
	FROM tbsolcons sc INNER JOIN tbpaciente p ON sc.CdPaciente=p.CdPaciente 
	INNER JOIN tbbairro b ON b.CdBairro=p.CdBairro
	INNER JOIN tbprefeitura pr ON b.CdPref=pr.CdPref
	INNER JOIN tbespecproc ep ON sc.CdEspecProc=ep.CdEspecProc
	INNER JOIN tbprocedimento proc ON ep.CdProcedimento=proc.CdProcedimento
	LEFT JOIN tbagendacons ac ON sc.CdSolCons=ac.CdSolCons
	LEFT JOIN tbfornecedor f ON ac.CdForn=f.CdForn
	WHERE (sc.Status = '1' AND ac.Status = '2')
	AND LEFT(DtAgCons,10) BETWEEN '$data1' AND '$data2'
	AND pr.CdPref = '$CdPref'
	AND ep.ppi = 'S'	
	ORDER BY  ac.DtAgCons ASC ") or die (mysqli_error());
	
	
    
    
    while($lin = mysqli_fetch_array($sql_m))
	{
		//echo "</br>";
		//echo "</br>";
	   $CdPref = $lin['CdPref'];
       $NmPref = $lin ['NmCidade'];
	   
	   //echo $NmPref;

    
        $sql_teto = mysqli_query($db,"SELECT *
	       from tbtetoppi
	       WHERE tbtetoppi.cdpref = '$CdPref'
	       AND tbtetoppi.dtinicio = '$data1'
	       AND tbtetoppi.dttermino = '$data2'
	       ORDER BY tbtetoppi.cdpref ") or die (mysqli_error());
		   
           
           $linteto = mysqli_fetch_array($sql_teto);
           $vltetoppi = $linteto['vltetoppi'];
		   //echo $vltetoppi;
		   //echo "</br>";
		   $limiteteto=0;
    
        // FORNECEDORES
        $sql_f = mysqli_query($db, "SELECT DISTINCT  f.CdForn, f.NmForn			  
		FROM tbsolcons sc INNER JOIN tbpaciente p ON sc.CdPaciente=p.CdPaciente 
		INNER JOIN tbbairro b ON b.CdBairro=p.CdBairro
		INNER JOIN tbprefeitura pr ON b.CdPref=pr.CdPref
		INNER JOIN tbespecproc ep ON sc.CdEspecProc=ep.CdEspecProc
		INNER JOIN tbprocedimento proc ON ep.CdProcedimento=proc.CdProcedimento
		LEFT JOIN tbagendacons ac ON sc.CdSolCons=ac.CdSolCons
		LEFT JOIN tbfornecedor f ON ac.CdForn=f.CdForn
		WHERE (sc.Status = '1' AND ac.Status = '2')
		AND pr.CdPref = '$CdPref'
		AND ep.ppi = 'S'
		AND LEFT(DtAgCons,10) BETWEEN '$data1' AND '$data2'
		ORDER BY  ac.DtAgCons ASC ") or die (mysqli_error());
		

        while($lin2 = mysqli_fetch_array($sql_f))
		{
			
			$CdForn = $lin2['CdForn'];
			$NmForn = $lin2['NmForn'];
			//echo "</br>";
			//echo $NmForn;           
            
            $sql_fd = mysqli_query($db, "SELECT ac.DtAgCons, ac.HoraAgCons,ac.cdsolcons, p.NmPaciente, ep.NmEspecProc, qts, ac.valor, ac.valor_sus  
			FROM tbsolcons sc INNER JOIN tbpaciente p ON sc.CdPaciente=p.CdPaciente 
			INNER JOIN tbbairro b ON b.CdBairro=p.CdBairro
			INNER JOIN tbprefeitura pr ON b.CdPref=pr.CdPref
			INNER JOIN tbespecproc ep ON sc.CdEspecProc=ep.CdEspecProc
			INNER JOIN tbprocedimento proc ON ep.CdProcedimento=proc.CdProcedimento
			LEFT JOIN tbagendacons ac ON sc.CdSolCons=ac.CdSolCons
			LEFT JOIN tbfornecedor f ON ac.CdForn=f.CdForn
			WHERE (sc.Status = '1' AND ac.Status = '2')
			AND f.CdForn = '$CdForn'
			AND pr.CdPref = '$CdPref'
			AND ep.ppi = 'S'
			AND LEFT(DtAgCons,10) BETWEEN '$data1' AND '$data2'
			ORDER BY  ac.DtAgCons ASC ") or die (mysqli_error()); 
            
			$qts = 0;
			$valor =0;
			$valor_sus=0;
			$dif =0;
			
            
            while($lin3 = mysqli_fetch_array($sql_fd))
			{	
                // RECEBE DADOS
				$DtAgCons =  $lin3['DtAgCons'];
                $CdSolCons = $lin3['cdsolcons'];
				$HoraAgCons =  $lin3['HoraAgCons'];
				$NmPaciente =  $lin3['NmPaciente'];
				$NmEspecProc =  $lin3['NmEspecProc'];
                $qts =  $lin3['qts'];
                $valor =  $lin3['valor'];
				$valor_sus =  $lin3['valor_sus'];
				$dif =  (($valor-$valor_sus)*$qts);
				
                //echo "Valor Teto : ".$vltetoppi;
                
				$limiteteto  = (($valor_sus*$qts) +  $limiteteto);
                
                if (($limiteteto <= $vltetoppi)and ($valor_sus > 0))
				{
                  //Validação
                  //echo "Valor do Teto PPI:  ". $limiteteto;
                  //echo "<br/>";
                $sql_insere = mysqli_query($db, "UPDATE `tbagendacons` SET `ppi`='S' WHERE (`CdSolCons`='$CdSolCons')") or die (mysqli_error());
                }
                if ($limiteteto > $vltetoppi) 
                {   
                  $sql_insere = mysqli_query($db, "UPDATE `tbagendacons` SET `ppi`='N' WHERE (`CdSolCons`='$CdSolCons')") or die (mysqli_error());
                    // RETIRO O VALOR DO ÚLTIMO, ASSIM O PPI VOLTA A TER ESPACO PARA OUTRO VALOR MENOR
                    $limiteteto = ($limiteteto - ($valor_sus*$qts));
                    $auxiliar = $auxiliar + 1;
                    
                } 
            }

        }
 
    }
	
	//INCLUSÃO DOS QUE NÃO SÃO PPI NA TABELA
	
	$sql_m = mysqli_query($db, "SELECT DISTINCT  pr.CdPref, pr.NmCidade			  
	FROM tbsolcons sc INNER JOIN tbpaciente p ON sc.CdPaciente=p.CdPaciente 
	INNER JOIN tbbairro b ON b.CdBairro=p.CdBairro
	INNER JOIN tbprefeitura pr ON b.CdPref=pr.CdPref
	INNER JOIN tbespecproc ep ON sc.CdEspecProc=ep.CdEspecProc
	INNER JOIN tbprocedimento proc ON ep.CdProcedimento=proc.CdProcedimento
	LEFT JOIN tbagendacons ac ON sc.CdSolCons=ac.CdSolCons
	LEFT JOIN tbfornecedor f ON ac.CdForn=f.CdForn
	WHERE (sc.Status = '1' AND ac.Status = '2')
	AND LEFT(DtAgCons,10) BETWEEN '$data1' AND '$data2'
	AND ep.ppi = 'N'	
	AND pr.CdPref = '$CdPref'
	ORDER BY  ac.DtAgCons ASC ") or die (mysqli_error());
	
	
	while($lin = mysqli_fetch_array($sql_m))
	{
		//echo "</br>";
		//echo "</br>";
	   $CdPref = $lin['CdPref'];
       $NmPref = $lin ['NmCidade'];
    
        // FORNECEDORES
        $sql_f = mysqli_query($db, "SELECT DISTINCT  f.CdForn, f.NmForn			  
		FROM tbsolcons sc INNER JOIN tbpaciente p ON sc.CdPaciente=p.CdPaciente 
		INNER JOIN tbbairro b ON b.CdBairro=p.CdBairro
		INNER JOIN tbprefeitura pr ON b.CdPref=pr.CdPref
		INNER JOIN tbespecproc ep ON sc.CdEspecProc=ep.CdEspecProc
		INNER JOIN tbprocedimento proc ON ep.CdProcedimento=proc.CdProcedimento
		LEFT JOIN tbagendacons ac ON sc.CdSolCons=ac.CdSolCons
		LEFT JOIN tbfornecedor f ON ac.CdForn=f.CdForn
		WHERE (sc.Status = '1' AND ac.Status = '2')
		AND pr.CdPref = '$CdPref'
		AND ep.ppi = 'N'
		AND LEFT(DtAgCons,10) BETWEEN '$data1' AND '$data2'
		ORDER BY  ac.DtAgCons ASC ") or die (mysqli_error());
		
		

        while($lin2 = mysqli_fetch_array($sql_f))
		{
			
			$CdForn = $lin2['CdForn'];
			$NmForn = $lin2['NmForn'];
			//echo "</br>";
			//echo $NmForn;           
            
            $sql_fd = mysqli_query($db, "SELECT ac.DtAgCons, ac.HoraAgCons,ac.cdsolcons, p.NmPaciente, ep.NmEspecProc, qts, ac.valor, ac.valor_sus  
			FROM tbsolcons sc INNER JOIN tbpaciente p ON sc.CdPaciente=p.CdPaciente 
			INNER JOIN tbbairro b ON b.CdBairro=p.CdBairro
			INNER JOIN tbprefeitura pr ON b.CdPref=pr.CdPref
			INNER JOIN tbespecproc ep ON sc.CdEspecProc=ep.CdEspecProc
			INNER JOIN tbprocedimento proc ON ep.CdProcedimento=proc.CdProcedimento
			LEFT JOIN tbagendacons ac ON sc.CdSolCons=ac.CdSolCons
			LEFT JOIN tbfornecedor f ON ac.CdForn=f.CdForn
			WHERE (sc.Status = '1' AND ac.Status = '2')
			AND f.CdForn = '$CdForn'
			AND pr.CdPref = '$CdPref'
			AND ep.ppi = 'N'
			AND LEFT(DtAgCons,10) BETWEEN '$data1' AND '$data2'
			ORDER BY  ac.DtAgCons ASC ") or die (mysqli_error()); 
            
            while($lin3 = mysqli_fetch_array($sql_fd))
			{	
                // RECEBE DADOS
                $CdSolCons = $lin3['cdsolcons'];
				
				   $sql_insere = mysqli_query($db, "UPDATE `tbagendacons` SET `ppi`='N' WHERE (`CdSolCons`='$CdSolCons')") or die (mysqli_error());
               
			} 
		}

	}
	
	echo '<script language="JavaScript" type="text/javascript"> 
				alert("Relátorio PPI e Não PPI gerado com sucesso!");
				window.location.href="index.php?i=30";				
			</script>';	 	
} 
  
  
  
  
  
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
				
				
				
				$("input[id=vltetoppi]").maskMoney({symbol:"R$",decimal:",",thousands:"."}); 
				
				
			});
			jQuery(function($){
				$("#dtinicio").mask("99/99/9999");
				$("#dttermino").mask("99/99/9999");
				$( "#dtinicio" ).datepicker( { showButtonPanel: true, nextText: '', prevText: '' } );
				$( "#dttermino" ).datepicker( { showButtonPanel: true, nextText: '', prevText: '' } );	
				
			});
	</script>
    <h1> Controle &raquo; Teto PPI </h1> 
    <form action="?i=30&ac=i" id="form" method="post"> 
      <label> Data Inicio <input type="text" class="data required" name='dtinicio' id="dtinicio">  </label>
      <label> Data Término <input type="text" class="data required"  name='dttermino' id="dttermino">  </label>
      <label> Valor <input type="text" name="vltetoppi"  id="vltetoppi" class="required"  >  </label>
      <label>Cidade: 
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
     $sql = mysqli_query($db,"SELECT t.cdtetoppi, p.CdPref, p.nmcidade, t.dtinicio, t.dttermino, t.vltetoppi 
	FROM tbtetoppi as t, tbprefeitura as p
	WHERE t.cdpref = p.cdpref ORDER BY cdtetoppi DESC ") or die (mysqli_error());
	
	if(mysqli_num_rows($sql)>0)
	{  
	
	echo "<table id='table'>
      <tr>
        <th> Código </th>
        <th> Municipio </th>
        <th> Data Inicio </th>
        <th> Data Término  </th>
        <th> Gerar PPI e NÃO PPI  </th>
        <th> Valor  </th>
        <th> Excluir  </th>
      </tr>";
	 while($lin = mysqli_fetch_array($sql))
	 {
	  $dtinicio = FormataDataBR($lin[dtinicio]);
	  $dttermino = FormataDataBR($lin[dttermino]);
	  $vltetoppi = number_format($lin["vltetoppi"],2,',','.');

	  echo"<tr>
        <td style='text-align:center'> $lin[cdtetoppi] </td>
        <td> $lin[nmcidade] </td>
        <td style='text-align:center'> $dtinicio  </td>
        <td style='text-align:center'> $dttermino  </td>      
		
		
		<td style='text-align:center'> <a href=?i=30&ac=1&CdPref=$lin[CdPref]&data1=$dtinicio&data2=$dttermino> <img src='img/icon.png' /> </a>   </td>
				<td style='text-align:center'	> $vltetoppi  </td>

        <td style='text-align:center'> <a href='?i=30&ac=e&cd=$lin[cdtetoppi]'> <img src='img/icon_excluir.png' width='15' height='15' /> </a>  </td>
       </tr>";
	 }
	 echo "</table>";
	} else { echo "<div id='alert'> Nenhum resultado encontrado </div>";    }
    
?>
	
    
    
    




















