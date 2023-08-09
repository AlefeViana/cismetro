<?php
	require('verifica.php');
?>
<script type="text/javascript">
	
	function validaForm(){
		if( $("#laudood").val() == '' ){
			alert('Favor preencher o campo laudo do olho direito.');
			$("#laudood").focus();
			return false;
		}
		if( $("#laudooe").val() == '' ){
			alert('Favor preencher o campo laudo do olho esquerdo.');
			$("#laudooe").focus();
			return false;
		}
		return true;
	}
	
</script>
<?php		
	
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
			or die ('Ocorreu um erro de numero: '.mysqli_error().', frm_laudo_oftalmo:consulta dados paciente. Tente executar novamente a tarefa e se o erro persistir, contate o administrador do sistema informando essa mensagem. Copie essa mensagem!');

	if (mysqli_num_rows($qry) == 1){
		$r = mysqli_fetch_array($qry);
	}		
	
?>
<form action="admin/regn_laudooftalmo.php" method="post" onsubmit="return validaForm();">
<input type="hidden" name="cdpaciente" value="<?php echo $CdPaciente; ?>" />
<div style="height:20px;"></div>
<div id="outside" style="width:750px;">
	<div id="titulo" align="center" style="height:20px;"><strong>LAUDAMENTO - RETINOGRAFIA - RETINOPATIA DIAB&Eacute;TICA</strong></div>
	<div id="conteudo">
    <table border="0" width="100%">
    <tr>
    	<td colspan="3">
        	<fieldset>
            	<legend>Dados do Paciente</legend>
                <label id="titulo1">CIH:</label>
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
        </td>
    </tr>
    <tr>
    	<td>
    	<fieldset>
        	<legend>Olho Direito</legend>
        	<label>Qualidade de Imagem Achados:</label><br />
            <textarea name="qiaod" rows="5" cols="44"></textarea><br />
            <label>Avalia&ccedil;&atilde;o de M&aacute;cula:</label><br />
            <textarea name="amod" rows="5" cols="44"></textarea><br />
            <label style="margin-right:5px;">Evid&ecirc;ncias de Fotocoagula&ccedil;&atilde;o:</label><input type="text" size="24" name="evidenciaod" /><br />
            <label style="margin-right:85px;">Outras Altera&ccedil;&otilde;es:</label><input type="text" size="24" name="outrasaltod" /><br />
            <label style="margin-right:159px;">Laudo:</label><input type="text" size="24" name="laudood" id="laudood" /><br />
            <label>Coment&aacute;rios:</label><br />
            <textarea name="comentariosod" rows="3" cols="44"></textarea><br />
        </fieldset>
        </td>
        <td width="2%">
        </td>
        <td>
        <fieldset>
        	<legend>Olho Esquerdo</legend>
        	<label>Qualidade de Imagem Achados:</label><br />
            <textarea name="qiaoe" rows="5" cols="44"></textarea><br />
            <label>Avalia&ccedil;&atilde;o de M&aacute;cula:</label><br />
            <textarea name="amoe" rows="5" cols="44"></textarea><br />
            <label style="margin-right:5px;">Evid&ecirc;ncias de Fotocoagula&ccedil;&atilde;o:</label><input type="text" size="24" name="evidenciaoe" /><br />
            <label style="margin-right:85px;">Outras Altera&ccedil;&otilde;es:</label><input type="text" size="24" name="outrasaltoe" /><br />
            <label style="margin-right:159px;">Laudo:</label><input type="text" size="24" name="laudooe" id="laudooe" /><br />
            <label>Coment&aacute;rios:</label><br />
            <textarea name="comentariosoe" rows="3" cols="44"></textarea><br />
        </fieldset>        
        </td>
    </tr>
    <tr>
    	<td colspan="3" align="center" height="40">
        	<fieldset style="height:40px;">
            	<input type="submit" value="Salvar" name="btnsalvar" style="margin-right:100px; margin-top:5px;" />
                <input type="button" value="Cancelar" name="btncancel" onclick="javascript:window.location.href='index.php?p=lista_pac'" />                
        	</fieldset>
        </td>
    </tr>
    </table>    
	</div>
</div>
</form>