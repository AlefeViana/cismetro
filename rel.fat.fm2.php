<script type="text/javascript" src="js2/ui/minified/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="js2/localization/jquery.ui.datepicker-pt-BR.js"></script>
<link rel="stylesheet" href="css/themes/base/jquery.ui.datepicker.css">
<link rel="stylesheet" href="css/themes/base/jquery.ui.theme.css">
<link rel="stylesheet" href="css/themes/base/jquery.ui.all.css">	
	<script type="text/javascript"> 
		$(document).ready(function() {	
		
		$("#frm1").validate({
			});	
		jQuery(function($){
			$("#dtinicio").mask("99/99/9999");
			$("#dttermino").mask("99/99/9999");
			$( "#dtinicio" ).datepicker( { showButtonPanel: true, nextText: '', prevText: '',changeMonth: true, changeYear: true } );
			$( "#dttermino" ).datepicker( { showButtonPanel: true, nextText: '', prevText: '',changeMonth: true, changeYear: true } );				
		});
		});
	</script>
		
    <h1>Relat&oacute;rios &raquo; Faturamento Fornecedor Por Munícipio </h1>
    <form action="" method="post" id="frm1" target="_blank" >
    
        <input type="hidden" name="cdrelfat" id="cdrelfat" class="required" value="1" />
            
        <label class="gr" > Fornecedor *
        <select name="cd_forn" id="cd_forn"  class="required" > 
            <?php
            echo "<option value=''> </option>";		
            if($_SESSION['CdTpUsuario']==1)
            {
                echo "<option value='0'> Todos </option>";		
                $sql = "";
            }
            if($_SESSION['CdTpUsuario']==5) { 
            $sql = ",tbusuario
            WHERE tbfornecedor.CdForn = tbusuario.cdfornecedor
            AND tbusuario.CdUsuario = $_SESSION[CdUsuario]
            ";
            }
			if($_SESSION['CdTpUsuario']==3) { 
			echo " <option value='0'>Todos</option> ";
			$sql = " ";
			}
            
            $re = mysqli_query($db,"SELECT *
            FROM tbfornecedor $sql
            ORDER BY NmForn") or die (mysqli_error());
            if (mysqli_num_rows($re) > 0)
                while($l = mysqli_fetch_array($re)) {
                    if ($dados["CdForn"] == $l["CdForn"])
                        $selecionado = 'selected=\"selected\"';
                    else
                        $selecionado = '';
                
                    echo "<option $selecionado value=\"$l[CdForn]\">$l[NmForn]</option>";						
                }
            ?>
        </select>
        </label>
    
                                
        <label class="gr"  >Munic&iacute;pio *
        
          <select name="cd_pref" id="cd_pref" class="required">
            <option value=""></option>
            
        <?php 
            //limpa variavel que mantem os dados digitados
            
            require("conecta.php");
            $sql = "SELECT CdPref, NmCidade FROM tbprefeitura WHERE tbprefeitura.consorciado = 'S'";
            if($_SESSION["CdTpUsuario"]=='1') // consórcio / adm
            {
            	echo " <option value='0'>Todos</option> ";
            }
			if($_SESSION["CdTpUsuario"]=='5') // consórcio / adm
            {
            	echo " <option value='0'>Todos</option> ";
            }
            if($_SESSION["CdTpUsuario"]=='3') // prefeitura  
            {
				 	
                $sql .= " AND CdPref=".(int)$_SESSION["CdOrigem"];	
            }
            
                $sql .=  " ORDER BY NmCidade ";
            
                $qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_pac','frm_cadpac:select cidade'));
                
                if (mysqli_num_rows($qry) > 0)
                {
                while ($dados = mysqli_fetch_array($qry)){
                    if ($dados_pac["CdPref"] == $dados["CdPref"])
                    {
                        echo '<option value="'.$dados["CdPref"].'" selected="selected">'.$dados["NmCidade"].'</option>';	
                    }
                    else
    
                    {	echo '<option value="'.$dados["CdPref"].'">'.$dados["NmCidade"].'</option>'; }
                
                }}
        ?>
        </select> 
    	</label>	
        <label class="gr" > Status *
            <select name="status" class="required">
            	<option value=''> </option>
                <option value="T"> Todos </option>			
                <option value="M"> Marcado </option>			
                <option value="R"> Realizado </option>			
            </select>
        </label>                            
        <label  style="clear:both"> Data de In&iacute;cio  *
          <input type="text" name="dtinicio" id="dtinicio" class="required" readonly/></label>    	
        <label> Data de T&eacute;rmino *
          <input type="text" name="dttermino" id="dttermino" class="required"  readonly/></label>
    

    
        <table border="0"  id='table'>
            <th colspan="3"> Selecione abaixo o formato desejado do relat&oacute;rio </strong> </th>
            <tr bgcolor="#F9FCFF">
                <th> PDF	</th>
                <th> Excel</th>
                <th> Word	</th>
            </tr>
            <div id="btns">
            <tr>
                <th > <input type="image" value="Gerar" onClick="frm1.action='relatorios/table/fat_fm_pdf2.php'" src="relatorios/table/pdf.png" width="50" height="50" title="Gerar o relatório em formato PDF" alt="PDF"></th>
                <th> <input type="image" value="Gerar" onClick="frm1.action='relatorios/table/wordexcel_rel_ag_canc.php?excel=S'" src="relatorios/table/excel.png" width="50" height="50" title="Gerar o relatório em formato do Excel" alt="Excel">   </th>
                <th> <input type="image" value="Gerar" onClick="frm1.action='relatorios/table/wordexcel_rel_ag_canc.php?word=S'" src="relatorios/table/word.png" width="50" height="50" title="Gerar o relatório em formato do Word" alt="Word">	</th>
            </tr>
            </div>
        </table>   
        </form>   
  
        
    
		