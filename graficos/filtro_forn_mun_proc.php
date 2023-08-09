<script type="text/javascript" src="js2/ui/minified/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="js2/localization/jquery.ui.datepicker-pt-BR.js"></script>

<link rel="stylesheet" href="css/themes/base/jquery.ui.datepicker.css">
<link rel="stylesheet" href="css/themes/base/jquery.ui.theme.css">
<link rel="stylesheet" href="css/themes/base/jquery.ui.all.css">

<script type="text/javascript" language="javascript"> 
  $(document).ready(function() {
	$("#cd_proc").change(function(){
		if($("#cd_proc").val() == 1 || $("#cd_proc").val() == ""){
			$("#linha_espec").hide();
			$("#cd_especificacao").removeClass("required");
		}else{	
			$("#linha_espec").show();
			$("#cd_especificacao").addClass("required");
		}
	});
	    $( "#dtinicio" ).datepicker( { showButtonPanel: true, nextText: '', prevText: '', changeMonth: true, changeYear: true } );
		$( "#dttermino" ).datepicker( { showButtonPanel: true, nextText: '', prevText: '', changeMonth: true, changeYear: true } );
	$('#cd_proc').change(function(){								  
		$('#cd_especificacao').attr("disabled","disabled");						  
		$('#cd_especificacao').load('admin/load_especif.php?cdproc='+$('#cd_proc').val() );
		$('#cd_especificacao').removeAttr("disabled");
	});
	jQuery(function($){
		//$("#dtinicio").mask("99/99/9999");
		//$("#dttermino").mask("99/99/9999");
	});
});
</script>  
 
<label  class="gr">Munic&iacute;pio *
  <select name="cd_pref" id="cd_pref" class="required">
       
       <?php 
        //limpa variavel que mantem os dados digitados
        
        require("conecta.php");
        $sql = "SELECT CdPref, NmCidade FROM tbprefeitura WHERE tbprefeitura.consorciado = 'S' ";
        if($_SESSION["CdTpUsuario"]=='1') // consórcio / adm
        {
			echo "<option value='0'> Todos </option>";
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
  <label class="gr"> Fornecedor *
        <select name="cd_forn" id="cd_forn"  class="required" > 
            <?php
            
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
            	echo "<option value='0'> Todos </option>";		
				
				$sql = "
            ";
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


<label  class="gr"> Procedimento *
<select name="cd_proc" id="cd_proc">
<option value="0">  Todos </option>
    <?php                            
        require("conecta.php");
        $sql = "SELECT CdProcedimento, NmProcedimento
                FROM tbprocedimento 
                WHERE Status = '1'
                ORDER BY NmProcedimento";
        
       
        $qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_solagd','frm_solagd:select dados procedimento'));
        if (mysqli_num_rows($qry) > 0){
            while ($dados = mysqli_fetch_array($qry)){	
                echo '<option value="'.$dados["CdProcedimento"].'">'.$dados["NmProcedimento"].'</option>';
            }
        } 
        @mysqli_close();
        @mysqli_free_result($qry); 
      ?>	 
  </select>
</label>

<label class="gr"> Especifica&ccedil;&atilde;o *
    <select name="cd_especificacao" id="cd_especificacao"  >
        <option value="0"> Todos  </option>
    </select>     
</label>
<!--
<label style="clear:both"> Data de In&iacute;cio  *
    <input type="text" name="dtinicio" id="dtinicio" class="required"> </label>    	
<label> Data de T&eacute;rmino *
<input type="text" name="dttermino" id="dttermino" class="required"> </label> -->
    
   


