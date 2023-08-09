<?php

	define("DIRECT_ACCESS",  true);
	
	require_once("verifica.php");
	
	//funcao para tratar erro
	require("admin/function_trata_erro.php");
	
	//verifica se o usuario tem permiss�o para acessar a pagina
	if ((int)$_SESSION["CdTpUsuario"] != 7)	
	{
		echo '<script language="JavaScript" type="text/javascript"> 
			window.location.href="index.php?p=inicial";				
		  </script>';	
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
	
 	require("conecta.php");
	$CdPaciente = $_GET["id"];
	$sql = "SELECT CdPaciente, NmPaciente, Sexo, DtNasc, NmMae, CPF FROM tbpaciente WHERE CdPaciente=$CdPaciente"; 
	$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_pac','frm_prescricao:select dados pac'));
	if (mysqli_num_rows($qry) == 1)
		$dados = mysqli_fetch_array($qry);
?>
<!-- Load TinyMCE -->
<script type="text/javascript" src="./js/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript">
	$().ready(function() {
		$('textarea.tinymce').tinymce({
			// Location of TinyMCE script
			script_url : './js/tiny_mce/tiny_mce.js',

			// General options
			theme : "advanced",
			plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",

			// Theme options
			theme_advanced_buttons1 : "bold,italic,underline,strikethrough,",
			theme_advanced_buttons2 : "",
			//theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,|,print,|,ltr,rtl,|,fullscreen",
			//theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			//theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,

			// Example content CSS (should be your site CSS)
			//content_css : "css/style.css",

			// Drop lists for link/image/media/template dialogs
			//template_external_list_url : "lists/template_list.js",
			//external_link_list_url : "lists/link_list.js",
			//external_image_list_url : "lists/image_list.js",
			//media_external_list_url : "lists/media_list.js",

			// Replace values for the template plugin
			//template_replace_values : {
			//	username : "Some User",
			//	staffid : "991234"
			//}
		});
	});
</script>
<!-- /TinyMCE -->
<form action="admin/regn_prescricao.php" method="post">

<div style="width:100%;height:30px;text-align:center">Prontu&aacute;rio do Paciente</div>
<div>
<div style="border:1px #000 dashed">
<table width="100%" border="0">
          <tr>
		      <td width="13%">CIH:</td>
		      <td width="27%"><?php echo $dados["CdPaciente"]; ?></td>
              <td width="15%">Nome:</td>
		      <td colspan="3"><?php echo $dados["NmPaciente"]; ?></td>
		  </tr>
		  <tr>
		      <td>Sexo:</td>
		      <td><?php if ($dados["Sexo"] == 'M') echo 'Masculino'; else echo 'Feminino'; ?></td>
              <td>Data de Nasc.:</td>
              <?php 
			  		$data = explode("-",$dados["DtNasc"]);
					$data = $data[2]."/".$data[1]."/".$data[0];
			  ?>
		      <td width="22%"><?php echo $data; ?></td>
              <td width="6%">Idade:</td>
		      <td width="17%"><?php echo CalcularIdade($dados["DtNasc"]); ?></td>
		  </tr>
          <tr>
		      <td>Nome da M&atilde;e:</td>
		      <td><?php echo $dados["NmMae"]; ?></td>
              <td>CPF:</td>
		      <td><?php echo formatarCPF_CNPJ($dados["CPF"]); ?></td>
		  </tr>
          <tr>
          	  <td colspan="6" height="45" valign="bottom">
              <?php 
			  		$link = 'javascript:abrirpop("admin/rel_hist_pac.php?id='.$dados["CdPaciente"].'","","750","550","yes")';
			  
              		echo "<a style=\"background-color:#EAEAEA\" href='$link'>Acessar hist&oacute;rico do paciente</a>";
				?>	
                    &nbsp;-&nbsp;
                    <a style="background-color:#EAEAEA" href="index.php?p=lista_hist_pac&id=<?php echo $dados["CdPaciente"];?>">Imprimir Prescri&ccedil;&otilde;es Anteriores</a>
              </td>	
          </tr>
</table>
</div>
<br />          
<div>
<table width="100%" border="0" bgcolor="#FFFFFF" cellspacing="5">
          <tr>
          	  <td width="20%" valign="top">Descri&ccedil;&atilde;o da Consulta:</td>
              <td width="80%">
              <textarea id="descricao" name="descricao" rows="15" cols="80" style="width: 80%" class="tinymce"></textarea>
			  </td>
          </tr>
          <tr>
          	  <td valign="top">Prescri&ccedil;&atilde;o:</td>
              <td>
              <textarea id="prescricao" name="prescricao" rows="15" cols="80" style="width: 80%" class="tinymce"></textarea>
			  </td>
          </tr>
</table>          

</textarea>
<br />
<input type="hidden" name="cdpaciente" value="<?php echo $dados["CdPaciente"]; ?>" />
<input type="submit" value="Salvar" />
</div>
</form>
</div>