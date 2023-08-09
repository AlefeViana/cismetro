<?php
	require('verifica.php');
	
	//formata data p/ data BR
	function fdata($data){
		$data = explode("-",$data);
		return $data[2]."/".$data[1]."/".$data[0];
	}
	
	function CalcularIdade($DtNasc){
		$DtNasc = explode("-",$DtNasc);
		$DtNow  = explode("-",date("Y-m-d"));
		
		$Idade = $DtNow[0] - $DtNasc[0];
		if ($DtNasc[1] > $DtNow[1]){
			$Idade--;
			return $Idade;
		}
		if ($DtNasc[1] == $DtNow[1] && $DtNasc[2] > $DtNow[2]){
			$Idade--;
			return $Idade;
		}
		return $Idade;
	}
	function formatarCPF_CNPJ($campo, $formatado = true){  
		 //retira formato  
		 $codigoLimpo = ereg_replace("[' '-./ t]",'',$campo);  
		 // pega o tamanho da string menos os digitos verificadores  
		 $tamanho = (strlen($codigoLimpo) -2);  
		 //verifica se o tamanho do código informado é válido  
		 if ($tamanho != 9 && $tamanho != 12){  
			 return false;  
		 }      
		 if ($formatado){  
			 // seleciona a máscara para cpf ou cnpj  
			 $mascara = ($tamanho == 9) ? '###.###.###-##' : '##.###.###/####-##';   
			 $indice = -1;  
			 for ($i=0; $i < strlen($mascara); $i++) {  
				 if ($mascara[$i]=='#') $mascara[$i] = $codigoLimpo[++$indice];  
			 }  
			 //retorna o campo formatado  
			 $retorno = $mascara;      
		 }else{  
			 //se não quer formatado, retorna o campo limpo  
			 $retorno = $codigoLimpo;  
		 }  
	   return $retorno;  
	} 
	
	//recebe codigo do paciente
	$CdPaciente = (int)$_GET["id"];
		
	$sql = "SELECT CdPaciente,NmPaciente,DtNasc,NmMae,CPF,If(Sexo = 'F','Feminino','Masculino') as Sexo				   
			FROM tbpaciente
			WHERE CdPaciente=$CdPaciente";
			
	require('conecta.php');		
	
	$qry = mysqli_query($db,$sql) 
			or die ('Ocorreu um erro de numero: '.mysqli_error().', anamnese:consulta dados paciente. Tente executar novamente a tarefa e se o erro persistir, contate o administrador do sistema informando essa mensagem. Copie essa mensagem!');

	if (mysqli_num_rows($qry) == 1){
		$r = mysqli_fetch_array($qry);
	}		
?>
<link href="../css/jsGrid.css" rel="stylesheet" type="text/css">
<script language="javascript" src="js/jsGrid.js"></script>
<script type="text/javascript" src="js/jquery.blockui.js"></script>
<script type="text/javascript">
$(document).ready(function() {
						   
    $("#btncadmed").click(function() {
        var medicacao = $("#frm_edtmed");
        var nomePost = medicacao.val(); 
		var resp    = '';
        //alert($("#frm_edtmed").val()); 
		$.ajax({
				type: "POST",
				url: "admin/regn_medicacao.php",
				data: "medicacao="+nomePost,
				cache: false,
				success: function(data){
					$("#resposta").html(data);
					carregaMed();
					//resp = data;
					//alert('Cadastro realizado com sucesso!')
				}
		});
        /*$.post("regn_medicacao.php", {medicacao: nomePost},
			function(data){
				$("#resposta").html(data);
			 }
			 , "html");
		*/
		$.blockUI({ css: { 
					border: 'none',			
					padding: '15px', 
					backgroundColor: '#000', 
					'-webkit-border-radius': '10px', 
					'-moz-border-radius': '10px', 
					opacity: .5, 
					color: '#fff' 
				}, message: $('#resposta') }); 		
		setTimeout($.unblockUI, 2000);		
 		//$.blockUI({ message: $('#frm_medicacao') });       
    });
	
	$("#btncaddoenca").click(function() {
        var doenca = $("#frm_edtdoenca");
        var nomePost = doenca.val(); 
		var resp    = '';     
		$.ajax({
				type: "POST",
				url: "admin/regn_doenca.php",
				data: "frm_edtdoenca="+nomePost,
				cache: false,
				success: function(data){
					$("#resposta").html(data);
					carregaDoenca();				
				}
		});        
		$.blockUI({ css: { 
					border: 'none',			
					padding: '15px', 
					backgroundColor: '#000', 
					'-webkit-border-radius': '10px', 
					'-moz-border-radius': '10px', 
					opacity: .5, 
					color: '#fff' 
				}, message: $('#resposta') }); 		
		setTimeout($.unblockUI, 2000);
		setTimeout($('#resposta').val(''), 4000);
    });
	
	$("#btncadtrat").click(function() {
        var trat = $("#frm_edttrat");
        var nomePost = trat.val(); 
		var resp    = '';     
		$.ajax({
				type: "POST",
				url: "admin/regn_tratamento.php",
				data: "frm_edttrat="+nomePost,
				cache: false,
				success: function(data){
					$("#resposta").html(data);
					carregaTrat();				
				}
		});        
		$.blockUI({ css: { 
					border: 'none',			
					padding: '15px', 
					backgroundColor: '#000', 
					'-webkit-border-radius': '10px', 
					'-moz-border-radius': '10px', 
					opacity: .5, 
					color: '#fff' 
				}, message: $('#resposta') }); 		
		setTimeout($.unblockUI, 2000);	 		 
    });
	
	$('#frm_medicacao').hide();
	$('#frm_doenca').hide();
	$('#frm_trat').hide();
	
	$('#btnAddMed').click(function() { 
        $.blockUI({ message: $('#frm_medicacao') }); 
        //setTimeout($.unblockUI, 2000); 
    });
	$('#btnAddDoenca').click(function() { 
        $.blockUI({ message: $('#frm_doenca') }); 
        //setTimeout($.unblockUI, 2000); 
    });
	$('#btnAddTrat').click(function() { 
        $.blockUI({ message: $('#frm_trat') }); 
        //setTimeout($.unblockUI, 2000); 
    });
	
	$('#btnCancel').click(function() { 
        window.location.href='index.php?p=lista_pac';
    });
	
	$('#btncancelmed').click(function() { 
        $.unblockUI(); 
        //setTimeout($.unblockUI, 2000); 
    });
	$('#btncanceldoenca').click(function() { 
        $.unblockUI(); 
    });
	$('#btncanceltrat').click(function() { 
        $.unblockUI(); 
    });
	
	function carregaMed(){
		//$('#cbomedicacao').html('<option value="">Carregando...</option>');
		$('#cbomedicacao').load('admin/load_medicacao.php');				
	}
	function carregaDoenca(){		
		$('#cbodoenca').load('admin/load_doenca.php');		
	}
	function carregaTrat(){		
		$('#cbotratamento').load('admin/load_tratamento.php');		
	}
	
});

	var grid,grid1,grid2;
	var Items = Array();
	var Items1 = Array();
	var Items2 = Array();
	
	function criaGrid(){
		grid = new jsGrid("divJGrid");
		grid.rows.addCol("380px");
		grid.rows.addCol("50px");
		grid.setTextMatrix(0,0,"Código");
		grid.setTextMatrix(0,1,"Medicação");
		grid.setTextMatrix(0,2,"");
	}
	function criaGrid1(){
		grid1 = new jsGrid("divJGrid1");
		grid1.rows.addCol("240px");
		grid1.rows.addCol("140px");
		grid1.rows.addCol("50px");
		grid1.setTextMatrix(0,0,"Código");
		grid1.setTextMatrix(0,1,"Doença");
		grid1.setTextMatrix(0,2,"Tempo");
		grid1.setTextMatrix(0,3,"");
	}
	function criaGrid2(){
		grid2 = new jsGrid("divJGrid2");
		grid2.rows.addCol("240px");
		grid2.rows.addCol("140px");
		grid2.rows.addCol("50px");
		grid2.setTextMatrix(0,0,"Código");
		grid2.setTextMatrix(0,1,"Tratamento");
		grid2.setTextMatrix(0,2,"Olho");
		grid2.setTextMatrix(0,3,"");
	}
	function AddItem(){
		var cod  = document.getElementById('cbomedicacao').value;
		var valor = document.getElementById('cbomedicacao').options[document.getElementById('cbomedicacao').selectedIndex].text;
		
		if (!VerificaValor(cod)){
			AdicionaLinha(grid);		
			grid.setTextMatrix(grid.rows.count()-1,0,cod);
			grid.setTextMatrix(grid.rows.count()-1,1,valor.substring(0,45));
			grid.setTextMatrix(grid.rows.count()-1,2,"<a href='#mantem' ondblclick='RemoveRowSelecionada("+cod+");'>Excluir</a>");
			document.getElementById('cbomedicacao').focus();
			Items.push(cod);			
			AddValForm();
		}
	}	
	function AddItem1(){
		var cod  = document.getElementById('cbodoenca').value;
		var valor = document.getElementById('cbodoenca').options[document.getElementById('cbodoenca').selectedIndex].text;
		var tempo = document.getElementById('edtempo').value;
		
		if (!VerificaValor1(cod) && tempo != '' ){
			AdicionaLinha(grid1);		
			grid1.setTextMatrix(grid1.rows.count()-1,0,cod);
			grid1.setTextMatrix(grid1.rows.count()-1,1,valor.substring(0,45));
			grid1.setTextMatrix(grid1.rows.count()-1,2,tempo);
			grid1.setTextMatrix(grid1.rows.count()-1,3,"<a href='#mantem' ondblclick='RemoveRowSelecionada1("+cod+");'>Excluir</a>");
			document.getElementById('cbodoenca').focus();
			Items1.push(cod+'-'+tempo);						
			AddValForm1();
		}
	}
	function getValue(radio) {
		for (i=0; i < radio.length; i++) 
		  	if (radio[i].checked == true) return radio[i].value;
				return "Nenhum radio foi checado";
	}

	function AddItem2(){
		var cod  = document.getElementById('cbotratamento').value;
		var valor = document.getElementById('cbotratamento').options[document.getElementById('cbotratamento').selectedIndex].text;
		var olho = getValue(document.form.olho);
		
		if (!VerificaValor2(cod)){
			AdicionaLinha(grid2);		
			grid2.setTextMatrix(grid2.rows.count()-1,0,cod);
			grid2.setTextMatrix(grid2.rows.count()-1,1,valor.substring(0,45));
			grid2.setTextMatrix(grid2.rows.count()-1,2,olho);
			grid2.setTextMatrix(grid2.rows.count()-1,3,"<a href='#mantem' ondblclick='RemoveRowSelecionada2("+cod+");'>Excluir</a>");
			document.getElementById('cbotratamento').focus();
			Items2.push(cod+'-'+olho);		
			AddValForm2();
		}
	}
	function VerificaValor(cod){
		for (var i=0;i<Items.length;i++){			
			if (Items[i] == cod)
				return true;
		}
		 return false;
	}
	function VerificaValor1(cod){
		var cd = cod.split('-');
		var a;
		for (var i=0;i<Items1.length;i++){			
			a = Items1[i].split('-');			
			if (a[0] == cd[0])
				return true;
		}
		 return false;
	}
	function VerificaValor2(cod){
		var cd = cod.split('-');
		var a;
		for (var i=0;i<Items2.length;i++){			
			a = Items2[i].split('-');
			if (a[0] == cod[0])
				return true;
		}
		 return false;
	}
	function RemoveItem(cod){
		var aux = Array();
		for (var i=0;i<Items.length;i++){
			if (Items[i] != cod)
				aux.push(Items[i]);
		}		
		return aux;
	}
	function RemoveItem1(cod){
		var aux = Array();
		var a;
		
		for (var i=0;i<Items1.length;i++){		
			a = Items1[i].split('-');
			if (a[0] != cod)
				aux.push(Items1[i]);							
		}
		
		return aux;
	}
	function RemoveItem2(cod){
		var aux = Array();
		var a;
		
		for (var i=0;i<Items2.length;i++){
			a =Items2[i].split('-');
			if (a[0] != cod)
				aux.push(Items2[i]);
		}		
		return aux;
	}
	
	function AddValForm(){
		document.getElementById('medicacaouso').value = Items;		
	}
	function AddValForm1(){
		document.getElementById('doenca').value = Items1;		
	}
	function AddValForm2(){
		document.getElementById('tratamento').value = Items2;		
	}	
	function RemoveRowSelecionada(cod){
		Items = RemoveItem(cod);
		AddValForm();
		grid.rows.selectedRow.remove();		
	}
	function RemoveRowSelecionada1(cod){	
		Items1 = RemoveItem1(cod);
		AddValForm1();
		grid1.rows.selectedRow.remove();		
	}
	function RemoveRowSelecionada2(cod){
		Items2 = RemoveItem2(cod);
		AddValForm2();
		grid2.rows.selectedRow.remove();		
	}
	function AdicionaLinha(grid){
		grid.rows.add();
	}
	
	
	function RemoveUltimaRow(){
		grid.rows.remove(grid.rows.count()-1);
	}
	function AdicionaColuna(){
		var largura = document.getElementById('txtLargura').value;
		grid.rows.addCol(largura);
	}
	function EditarTexto(){
		var row =  document.getElementById('txtLinha').value;
		var col  = document.getElementById('txtColuna').value;
		var valor  = document.getElementById('txtValor').value;
		grid.setTextMatrix(row,col,valor);
	}
	function ExibirValorCelula(){
		var row =  document.getElementById('txtLinha2').value;
		var col  = document.getElementById('txtColuna2').value;
		alert(grid.getTextMatrix(row,col));
	}	
	function MudarLarguraColuna(){
		var col =  document.getElementById('txtCol').value;
		var width =  document.getElementById('txtWidth').value;
		grid.rows.setColWidth(col,width);
	}
</script>
<style>
#titulo{
	width:auto;
	text-align:center;
}
#espaco{
	width:30px;	
}
#outside{
	margin:10px; 
	padding:15px;
	width:auto;
}

</style>
<form name="form" action="admin/regn_anamnese_oftalmo.php" method="post">
<div>
	<div id="titulo">ANAMNESE - RETINOGRAFIA COLORIDA</div>
    <div id="conteudo">
    <fieldset id="outside">
    		<!--Cabeçalho paciente -->
    	   <fieldset style="width:100%; margin-bottom:15px;">
            	<legend>Dados do Paciente</legend>
                <label id="titulo1">CIH:</label>
                <input type="hidden" name="cdpaciente" id="cdpaciente" value="<?php echo $r["CdPaciente"]; ?>" />
                <?php echo ' '.$r["CdPaciente"]; ?>
                <label id="titulo1" style="margin-left:20px;">Nome:</label>
                <?php echo ' '.$r["NmPaciente"]; ?>
                <label id="titulo1" style="margin-left:20px;">Sexo:</label>
                <?php echo ' '.$r["Sexo"]; ?><br />
                <label id="titulo1">Data de Nascimento:</label>
                <?php echo ' '.fdata($r["DtNasc"]); ?>
                <label id="titulo1" style="margin-left:20px;">Idade:</label>
                <?php echo ' '.CalcularIdade($r["DtNasc"]); ?>
                <?php
					if($r["CPF"] != ''){
						echo "<label id=\"titulo1\" style=\"margin-left:20px;\">CPF:</label>";
					 	echo ' '.formatarCPF_CNPJ($r["CPF"]); 
					}
				 ?>
                <br /><label id="titulo1">Nome da M&atilde;e:</label>
                <?php echo ' '.$r["NmMae"]; ?>                
           </fieldset>   
    	   <fieldset style="float:left; position:relative; margin-top:3px; width:100%;">      
               <fieldset id="baixavisualfield" style="float:left; position:relative;">
                 <legend>Baixa Visual</legend>                       
                    <input type="radio" name="baixavisual" id="baixavisual" value="od" checked="checked" /><label>OD</label>
                    <input type="radio" name="baixavisual" id="baixavisual" value="oe" /><label>OE</label>
                    <input type="radio" name="baixavisual" id="baixavisual" value="ao" /><label>AO</label>
               </fieldset>
               <span id="espaco"></span>     
               <fieldset id="acuidadevisual" style="float:right; margin-right:340px; position:relative;">
                    <legend>Acuidade Visual</legend>
                    
                        <label>OD&nbsp;</label><input type="text" name="acuidadevisualod" id="acuidadevisualod" size="5" />
                        <label>OE&nbsp;</label><input type="text" name="acuidadevisualoe" id="acuidadevisualoe" size="5" />
                    
               </fieldset>
        
       		
                <label style="position:relative; float:left; margin-top:5px;">Tempo de Evolu&ccedil;&atilde;o:
                <input type="text" name="tempoevolucao" id="tempoevolucao" size="5" />&nbsp;Anos</label>                           
                
                <fieldset style="position:relative; float:left;"><legend>Coment&aacute;rios:</legend>    
                	<textarea name="comentarios" id="comentarios" rows="7" cols="70"></textarea>     
                </fieldset>    
                
           </fieldset>
            
  	   <fieldset style="float:left; position:relative; margin-top:3px; width:100%; margin-top:15px;">
       		<legend>Doen&ccedil;as&nbsp;Sist&ecirc;micas</legend>
            <label>Doença</label>
            <select name="cbodoenca" id="cbodoenca">
            <?php
            	require("conecta.php");
				$qry = mysqli_query($db,"SELECT CdDoenca,NmDoenca FROM tbdoenca WHERE Status='1' ORDER BY NmDoenca") or die (mysqli_error());
				while($result = mysqli_fetch_row($qry))
					echo "<option value=\"$result[0]\" title=\"$result[1]\">".substr($result[1],0,20)."</option>";
			?>
            </select>&nbsp;
            <label>Tempo</label>
            <input type="text" name="edtempo" id="edtempo" size="10" />&nbsp;            
            <input type="button" value="Adicionar" onclick="AddItem1();" />
            <input type="button" value="Novo" id="btnAddDoenca" /><br />
            <div id="divJGrid1">    
				<script>
                    criaGrid1();
                </script>
            </div>                            
            <input type="hidden" name="doenca" id="doenca" />            
       </fieldset>
       
       <fieldset id="medicacao1" style="float:left; position:relative; width:100%; margin-top:15px;">
       		<legend>Medica&ccedil;&atilde;o&nbsp;em&nbsp;Uso</legend>
            <label>Medica&ccedil;&atilde;o</label>
            <select name="cbomedicacao" id="cbomedicacao" style="width:400px">
            <?php
            	//require("../conecta.php");
				$qry = mysqli_query($db,"SELECT CdMedicacao,NmMedicacao FROM tbmedicacao WHERE Status='1' ORDER BY NmMedicacao") or die (mysqli_error());
				while($result = mysqli_fetch_row($qry))
					echo "<option value=\"$result[0]\" title=\"$result[1]\">".substr($result[1],0,60)."</option>";
			?>
            </select>&nbsp;            
            <input type="button" value="Adicionar" onclick="AddItem();" />
            <input type="button" value="Novo" id="btnAddMed" /><br />
             <div id="divJGrid">    
				<script>
                    criaGrid();
                </script>
            </div>
            <input type="hidden" name="medicacaouso" id="medicacaouso" />
       </fieldset> 
       
       <fieldset id="tratoculares" style="float:left; position:relative; width:100%; margin-top:15px;">
       		<legend>Tratamentos Oculares Pr&eacute;vios</legend>
            <label>Tratamento</label>
            <select name="cbotratamento" id="cbotratamento" style="width:300px">            
            <?php
            	//require("../conecta.php");
				$qry = mysqli_query($db,"SELECT CdTratamento,NmTratamento FROM tbtratamento WHERE Status='1' ORDER BY NmTratamento") or die (mysqli_error());
				while($result = mysqli_fetch_row($qry))
					echo "<option value=\"$result[0]\" title=\"$result[1]\">".substr($result[1],0,60)."</option>";
			?>
            </select>&nbsp;
            
            <label>OD:</label><input type="radio" name="olho" id="olho" value="od" checked="checked" />
            <label>OE:</label><input type="radio" name="olho" id="olho" value="oe" />
            <label>AO:</label><input type="radio" name="olho" id="olho" value="ao" />
            &nbsp;            
            <input type="button" value="Adicionar" onclick="AddItem2();" />
            <input type="button" value="Novo" id="btnAddTrat" /><br />
             <div id="divJGrid2">    
				<script>
                    criaGrid2();
                </script>
            </div>
            <input type="hidden" name="tratamento" id="tratamento" />            
            <fieldset><legend>Coment&aacute;rios</legend>
            	<textarea name="comentariost" id="comentariost" rows="7" cols="70"></textarea>  
            </fieldset>    
       </fieldset>                    
            
       <fieldset style="position:relative; float:left; margin-top:20px; width:100%; text-align:center;">
       			<input type="submit" value="Salvar" name="btnSalvar" id="btnSalvar" />
                <input type="button" value="Cancelar" name="btnCancel" id="btnCancel" style="margin-left:40px;" />
       </fieldset>     
    </fieldset>   
    </div>
</div>  
</form>     
<?php
	require_once('frm_medicacao.php');
	require_once('frm_doenca.php');
	require_once('frm_tratamento.php');
?>
<div id="resposta" style="z-index:-1; position:relative;"></div>