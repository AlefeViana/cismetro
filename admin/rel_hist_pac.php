<?php

	define("DIRECT_ACCESS", true);

	require_once("verifica.php");
	//verifica se o usuario tem permiss�o para acessar a pagina
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
		 //verifica se o tamanho do c�digo informado � v�lido  
		 if ($tamanho != 9 && $tamanho != 12){  
			 return false;  
		 }      
		 if ($formatado){  
			 // seleciona a m�scara para cpf ou cnpj  
			 $mascara = ($tamanho == 9) ? '###.###.###-##' : '##.###.###/####-##';   
			 $indice = -1;  
			 for ($i=0; $i < strlen($mascara); $i++) {  
				 if ($mascara[$i]=='#') $mascara[$i] = $codigoLimpo[++$indice];  
			 }  
			 //retorna o campo formatado  
			 $retorno = $mascara;      
		 }else{  
			 //se n�o quer formatado, retorna o campo limpo  
			 $retorno = $codigoLimpo;  
		 }  
	   return $retorno;  
	} 
	
 	require("../conecta.php");
	$CdPaciente = (int)$_GET["id"];
	$sql = "SELECT CdPaciente, NmPaciente, Sexo, DtNasc, NmMae, CPF FROM tbpaciente WHERE CdPaciente=$CdPaciente"; 
	$qry = mysqli_query($db,$sql)
				or die ('Ocorreu um erro de numero: '.mysqli_errno().', rel_hist_pac:consulta dados pac. Tente executar novamente a tarefa e se o erro persistir, contate o administrador do sistema informando essa mensagem. Copie essa mensagem!');
				
	if (mysqli_num_rows($qry) == 1){
		$dados = mysqli_fetch_array($qry);
		$qry = mysqli_query($db,"SELECT Prescricao, Descricao, Data FROM tbhistoricopac WHERE CdPaciente=$CdPaciente ORDER BY CdHPac")
				or die('Ocorreu um erro de numero: '.mysqli_errno().', rel_hist_pac:consulta historico pac. Tente executar novamente a tarefa e se o erro persistir, contate o administrador do sistema informando essa mensagem. Copie essa mensagem!');
	}
?>
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
<div style="width:700px;height:30px;text-align:center">Prontu&aacute;rio do Paciente</div>
<div>
<div style="border:1px #000 dashed; width:700px">
<table width="100%" border="0">
          <tr>
		      <td width="13%">C&oacute;digo:</td>
		      <td width="27%"><?php echo $dados["CdPaciente"]; ?></td>
              <td width="15%">Nome:</td>
		      <td colspan="3"><?php echo $dados["NmPaciente"]; ?></td>
		  </tr>
		  <tr>
		      <td>Sexo:</td>
		      <td><?php if ($dados["Sexo"] == 'M') echo 'Masculino'; else echo 'Feminino'; ?></td>
              <td>Data de Nasc.:</td>
		      <td width="22%"><?php echo fdata($dados["DtNasc"]); ?></td>
              <td width="6%">Idade:</td>
		      <td width="17%"><?php echo CalcularIdade($dados["DtNasc"]); ?></td>
		  </tr>
          <tr>
		      <td>Nome da M&atilde;e:</td>
		      <td><?php echo $dados["NmMae"]; ?></td>
              <td>CPF:</td>
		      <td><?php echo formatarCPF_CNPJ($dados["CPF"]); ?></td>
		  </tr>
</table>
</div>
<br />       

<div style="width:700px; text-align:justify">
<?php
	
	if (mysqli_num_rows($qry) > 0){
		$cont = 1;
		while($dados = mysqli_fetch_array($qry)){
			echo '<h3><u>Consulta '.$cont.' - Data da Consulta: '.fdata($dados["Data"]).'</u></h3>';
			echo "<b>Descri&ccedil;&atilde;o:</b><br />".$dados["Descricao"]."<hr /><b>Prescri&ccedil;&atilde;o:</b><br />".$dados["Prescricao"]."<br /><br />";
			$cont++;
		}
	}
	@mysqli_close();
	@mysqli_free_result($qry);
?>

</div>

</div>