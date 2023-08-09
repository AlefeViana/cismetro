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

 	require("../conecta.php");
	$CdHPac = (int)$_GET["id"];
	$sql = "SELECT NmPaciente, Prescricao, Data FROM tbhistoricopac hp 
				INNER JOIN tbpaciente p ON hp.CdPaciente=p.CdPaciente 
			WHERE CdHPac=$CdHPac"; 
			
	$qry = mysqli_query($db,$sql) 
			or die ('Ocorreu um erro de numero: '.mysqli_errno().', rel_imp_prescricao:consulta dados prescricao. Tente executar novamente a tarefa e se o erro persistir, contate o administrador do sistema informando essa mensagem. Copie essa mensagem!');
			
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
<div id="cabecalho"></div>
<div style="width:700px;height:30px;text-align:center">Receitu&aacute;rio</div>
<br />       
<div style="width:700px; text-align:justify">
<?php
	
	if (mysqli_num_rows($qry) == 1){
		
		while($dados = mysqli_fetch_array($qry)){
			echo '<b><u>Paciente: '.$dados["NmPaciente"].'</u></b><br />';
			echo '<b><u>Data: '.fdata($dados["Data"]).'</u></b>';
			echo "<br /><br />".$dados["Prescricao"];
		}
	}
	@mysqli_close();
	@mysqli_free_result($qry);
?>

</div>