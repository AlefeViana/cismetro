<?php
	require_once("verifica.php");
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
	
 	require("../conecta.php");
	$Campos = $_GET["id"];
	$Campos = explode('_',$Campos);
	$CdPaciente = (int)$Campos[0];
	$DtInc = $Campos[1];
	$sql = "SELECT l.CdPaciente,p.NmPaciente,p.DtNasc,p.NmMae,p.CPF,If(p.Sexo = 'F','Feminino','Masculino') as Sexo,
				   l.DtInc,l.UserInc,QIAOD,QIAOE,AMOD,AMOE,EFOD,EFOE,OAOD,OAOE,LOD,LOE,COD,COE,u.NmUsuario,RegistroOrgaoR
			FROM tblaudo l INNER JOIN tbpaciente p ON l.CdPaciente=p.CdPaciente
						   INNER JOIN tblaudooftalmo lo ON l.CdPaciente=lo.CdPaciente AND l.DtInc=lo.DtInc
						   INNER JOIN tbusuario u ON l.UserInc=u.CdUsuario
						   INNER JOIN tbfuncionario f ON u.CdUsuario=f.CdUsuario
			WHERE Tabela='tblaudooftalmo' AND 
			      l.CdPaciente=$CdPaciente AND 
				  l.DtInc='$DtInc'";
			
	$qry = mysqli_query($db,$sql) 
			or die ('Ocorreu um erro de numero: '.mysqli_error().', rel_laudo_oftalmo:consulta dados laudo. Tente executar novamente a tarefa e se o erro persistir, contate o administrador do sistema informando essa mensagem. Copie essa mensagem!');

	if (mysqli_num_rows($qry) == 1){
		$r = mysqli_fetch_array($qry);
	}		
?>
<style type="text/css">
	#titulo{
		margin-bottom:20px;
	}
	#titulo1{
		font-weight:bold;
	}
</style>
<script type="text/javascript" src="../js/jquery-1.4.2.min.js"></script>	
<script type="text/javascript" src="../js/jquery.maskedinput-1.2.2.min.js"></script>
<script type="text/javascript"> 
$(document).ready(function() {	
	$("#BtnImp").click(function(){
		$("#Imp").hide();
		window.print();
		$("#Imp").show();
	});
});			

</script>
<div id="Imp"><input type="button" name="BtnImp" id="BtnImp" value="Imprimir" /></div>
<div id="outside" style="width:750px;">
	<div id="logo" style="position:relative; vertical-align:top;"><img src="../imagens/consaude_online.png" border="0" alt="ConsaudeOnline" /></div>
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
    	<td width="50%" valign="top">
    	<fieldset>
        	<legend>Olho Direito</legend>
        	<label id="titulo1">Qualidade de Imagem Achados:</label><br />
            <?php echo ' '.$r["QIAOD"]; ?><br /><br />
            <label id="titulo1">Avalia&ccedil;&atilde;o de M&aacute;cula:</label><br />
            <?php echo ' '.$r["AMOD"]; ?><br /><br />
            <label id="titulo1">Evid&ecirc;ncias de Fotocoagula&ccedil;&atilde;o:</label>
			<?php echo ' '.$r["EFOD"]; ?><br /><br />
            <label id="titulo1">Outras Altera&ccedil;&otilde;es:</label>
			<?php echo ' '.$r["OAOD"]; ?><br /><br />
            <label id="titulo1">Laudo:</label>
			<?php echo ' '.$r["LOD"]; ?><br /><br />
            <label id="titulo1">Coment&aacute;rios:</label><br />
            <?php echo ' '.$r["COD"]; ?><br />
        </fieldset>
        </td>
        <td width="5px;">
        </td>
        <td width="50%" valign="top">
        <fieldset>
        	<legend>Olho Esquerdo</legend>
        	<label id="titulo1">Qualidade de Imagem Achados:</label><br />
            <?php echo ' '.$r["QIAOE"]; ?><br /><br />
            <label id="titulo1">Avalia&ccedil;&atilde;o de M&aacute;cula:</label><br />
            <?php echo ' '.$r["AMOE"]; ?><br /><br />
            <label id="titulo1">Evid&ecirc;ncias de Fotocoagula&ccedil;&atilde;o:</label>
			<?php echo ' '.$r["EFOE"]; ?><br /><br />
            <label id="titulo1">Outras Altera&ccedil;&otilde;es:</label>
			<?php echo ' '.$r["OAOE"]; ?><br /><br />
            <label id="titulo1">Laudo:</label>
			<?php echo ' '.$r["LOE"]; ?><br /><br />
            <label id="titulo1">Coment&aacute;rios:</label><br />
            <?php echo ' '.$r["COE"]; ?><br />
        </fieldset>        
        </td>
    </tr>
    
    <tr>
    	<td colspan="3">
        	<fieldset>
            	<legend>Respons&aacute;vel</legend>               
                <br />
                <center>___________________________________________</center><br />
                <center>
					<?php echo $r["NmUsuario"].' - '. $r["RegistroOrgaoR"]; ?><br />     
                </center>                 
                	<?php 
						$r["DtInc"] = explode(' ',$r["DtInc"]);
						echo '<sup>Data do Laudo: '.fdata($r["DtInc"][0]).' '.$r["DtInc"][1].'</sup>';
					?>                
            </fieldset>
        </td>
    </tr>
    
    </table>    
</div>
<?php	
	@mysqli_close();
	@mysqli_free_result($qry);
?>

</div>