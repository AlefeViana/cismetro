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
	$CdPaciente = (int)$_GET["id"];	
				  
	$sql = "SELECT p.CdPaciente,p.NmPaciente,p.DtNasc,p.NmMae,p.CPF,If(p.Sexo = 'F','Feminino','Masculino') as Sexo
			FROM tbpaciente p
			WHERE p.CdPaciente=$CdPaciente";
			
	$qry = mysqli_query($db,$sql) 
			or die ('Ocorreu um erro de numero: '.mysqli_error().', rel_anamnese_oftalmo:consulta dados paciente. Tente executar novamente a tarefa e se o erro persistir, contate o administrador do sistema informando essa mensagem. Copie essa mensagem!');

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
	<div id="titulo" align="center" style="height:20px;"><strong>ANAMNESE - RETINOGRAFIA COLORIDA</strong></div>
	<div id="conteudo">
    <table border="0" width="100%">
    <tr>
    	<td>
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
<?php 
	$sql = "SELECT a.DtInc,a.UserInc, ao.*,u.NmUsuario,f.RegistroOrgaoR
			FROM tbanamnese a INNER JOIN tbanamneseoftalmo ao ON a.CdPaciente=ao.CdPaciente AND a.DtInc=ao.DtInc							  
							  INNER JOIN tbusuario u ON a.UserInc=u.CdUsuario
							  INNER JOIN tbfuncionario f ON u.CdUsuario=f.CdUsuario
			WHERE a.TpAnamnese='tbanamneseoftalmo' AND 
				  a.CdPaciente=$CdPaciente
			ORDER BY a.DtInc DESC";
			
	$qry = mysqli_query($db,$sql) 
			or die ('Ocorreu um erro de numero: '.mysqli_error().', rel_anamnese_oftalmo:consulta dados anamnese. Tente executar novamente a tarefa e se o erro persistir, contate o administrador do sistema informando essa mensagem. Copie essa mensagem!');
			
	while( $r = mysqli_fetch_array($qry) ){
		$Data = explode(' ',$r["DtInc"]);
		$Data = fdata($Data[0]).' - '.$Data[1];
?>
    <tr>
    	<td valign="top">
    			<fieldset style="margin-top:20px;">
                        <legend><?php echo 'Data de Inclus&atilde;o: '.$Data; ?></legend>
                        
                        <label id="titulo1">Baixa Visual:</label>
                        <?php echo ' '.strtoupper($r["BaixaVisual"]); ?>                                        
                       
                        <label id="titulo1" style="margin-left:25px;">Acuidade Visual:</label>                                          
                        <?php echo ' OD: '.$r["AcuidadeVisualOD"].' - OE: '.$r["AcuidadeVisualOE"]; ?> 
                        
                        <label id="titulo1" style="margin-left:25px;">Tempo de Evolu&ccedil;&atilde;o:</label>           
                         <?php echo ' '.(int)$r["TempoEvolucao"].' anos'; ?>     
                        <br /><br />
                        <label id="titulo1">Coment&aacute;rios:</label>    
                        <?php echo ' '.$r["Comentarios"]; ?>                            
                        
                        <?php 
							//doencas sistemicas
							$sql = "SELECT ad.CdDoenca, d.NmDoenca, ad.Tempo
									FROM tbanaoftaldoenca ad INNER JOIN tbdoenca d ON ad.CdDoenca=d.CdDoenca
									WHERE ad.CdPaciente=$r[CdPaciente] AND
										  ad.DtInc='$r[DtInc]'";
							$qry_d = mysqli_query($db,$sql) or die (mysqli_error());		
							
							if (mysqli_num_rows($qry_d) > 0){
								echo '<br /><br /><label id="titulo1">Doen&ccedil;as&nbsp;Sist&ecirc;micas:</label>';
								while($dados = mysqli_fetch_array($qry_d)){
									echo '<br />'.strtoupper($dados["NmDoenca"]).' - '.$dados["Tempo"].' anos';
								}									
							}					
                                 
                        ?>
                        
                        <?php 
							//medicamentos em uso
							$sql = "SELECT am.CdMedicamento, m.NmMedicacao
									FROM tbanaoftalmed am INNER JOIN tbmedicacao m ON am.CdMedicamento=m.CdMedicacao
									WHERE am.CdPaciente=$r[CdPaciente] AND
										  am.DtInc='$r[DtInc]'";
							$qry_d = mysqli_query($db,$sql) or die (mysqli_error());	
							
							if (mysqli_num_rows($qry_d) > 0){
								echo '<br /><br /><label id="titulo1">Medica&ccedil;&atilde;o&nbsp;em&nbsp;Uso:</label>';
								while($dados = mysqli_fetch_array($qry_d)){
									echo '<br />'.strtoupper($dados["NmMedicacao"]);
								}									
							}					
                                 
                        ?>
						
                        <?php 
							//tratamentos previos
							$sql = "SELECT at.CdTratamento, t.NmTratamento, at.Olho
									FROM tbanaoftaltrat at INNER JOIN tbtratamento t ON at.CdTratamento=t.CdTratamento
									WHERE at.CdPaciente=$r[CdPaciente] AND
										  at.DtInc='$r[DtInc]'";
							$qry_d = mysqli_query($db,$sql) or die (mysqli_error());	
							
							if (mysqli_num_rows($qry_d) > 0){
								echo '<br /><br /><label id="titulo1">Tratamentos Oculares Pr&eacute;vios:</label>';
								while($dados = mysqli_fetch_array($qry_d)){
									echo '<br />'.strtoupper($dados["NmTratamento"]).' - '.strtoupper($dados["Olho"]);
								}									
							}					
                                 
                        ?>                                                           
                        <br /><br />
                        <label id="titulo1">Coment&aacute;rios:</label>                         
                        <?php echo ' '.$r["ComentariosT"]; ?>  
                        
                        <fieldset style="margin-top:15px;">
                                <legend style="font-weight:bold;">Respons&aacute;vel</legend>                                                           
                                <?php echo $r["NmUsuario"].' - '. $r["RegistroOrgaoR"]; ?>
                                
                    
                        </fieldset>       
                </fieldset>    
        </td>                
    </tr>        
        	
<?php
	}//end while
?>
    
    </table>    
</div>
<?php	
	@mysqli_close();
	@mysqli_free_result($qry);
?>

</div>