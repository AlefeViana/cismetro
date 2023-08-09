<script type="text/javascript"> 
$(document).ready(function() {
	$("#commentForm").validate();
});
jQuery(function($){	
	$("#limite").maskMoney({symbol:"R$",decimal:",",thousands:"."})
});
</script>

<?php
	require_once("verifica.php");
	
	//funcao para tratar erro
	require("admin/function_trata_erro.php");
	
	//verifica se o usuario tem permissão para acessar a pagina
	if ((int)$_SESSION["CdTpUsuario"] != 1)	
	{
		echo '<script language="JavaScript" type="text/javascript"> 
			window.location.href="index.php?p=inicial";				
		  </script>';	
	}	
	
	$destino = "admin/regn_pref.php";
	
	$Acao = $_GET["acao"];
	$CdPref = $_GET["id"];
	
	//value do botao de submit do formulario
	$btnAcao = "Cadastrar";
	
	//carrega os dados do paciente
	if (is_numeric($CdPref))
	{
			require("conecta.php");
			
			$sql = "SELECT CdPref,CdEstado,Email,NmCidade,LimiteMax
					FROM tbprefeitura
					WHERE CdPref=".$CdPref; 
			
			$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_pref','frm_cadpref:select dados'));
			if (mysqli_num_rows($qry) == 1){
				 $dados = mysqli_fetch_array($qry);
			}
			else{
				echo '<script language="JavaScript" type="text/javascript"> 
					alert("Prefeitura não encontrada!");
					window.location.href="index.php?p=lista_pref";				
			  		</script>';	
			}
			@mysqli_free_result($qry);
			@mysqli_close();
			
			switch ($Acao)
			{
				case "edit": $destino .= "?acao=edit";
							 $btnAcao = "Salvar";
							 break;
				case "del" : $destino .= "?acao=del";
							 $btnAcao = "Excluir";
							 break;
			}
			
	}
	
?>
<form method="POST" action="<?php echo $destino; ?>" id="commentForm">

<div id="frms">

		<table width="100%" border="0" bgcolor="#FFFFFF" cellspacing="5">
		  <tr>
		      <td height="40" colspan=2 align="center">
		      <h4>Cadastro de Prefeitura</h4></td>
		  </tr>
          <tr>
		      <td width="80">C&oacute;digo:</td>
		      <td><input type="text" name="cd_pref" size="10" readonly="readonly" value="<?php if(isset($dados["CdPref"])) echo $dados["CdPref"]; else echo "Autom&aacute;tico"; ?>" /></td>
		  </tr>
		  <tr>
		      <td>Cidade:</td>
	          <td><input type="text" name="nm_cidade" size="40" class="required" value="<?php echo $dados["NmCidade"]; ?>" /></td>
		  </tr>
          <tr>
		      <td>E-mail:</td>
	          <td><input type="text" name="email" class="email" size="50" value="<?php echo $dados["Email"]; ?>" /></td>
		  </tr>
          <tr>
		      <td>Limite M&aacute;ximo:</td>
	          <td><input type="text" name="limite" id="limite" size="10" value="<?php echo number_format($dados["LimiteMax"],2,',','.'); ?>" /></td>
		  </tr>
          <tr>
		      <td>Estado:</td>
	          <td>
              		<select name="cd_estado">
              		<?php 
						require("conecta.php");
						$sql = "SELECT CdEstado, NmEstado FROM tbestado";
						$sql .=  " ORDER BY NmEstado";
						
						$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_pref','frm_cadpref:select estado'));
						if (mysqli_num_rows($qry) > 0){
							while ($l = mysqli_fetch_array($qry)){
								if ($dados["CdEstado"] == $l["CdEstado"])
									echo '<option value="'.$l["CdEstado"].'" selected="selected">'.$l["NmEstado"].'</option>';	
								else
									echo '<option value="'.$l["CdEstado"].'">'.$l["NmEstado"].'</option>';
							}
						} 
						mysqli_close();
						mysqli_free_result($qry);
					?>
                    </select>
       
              </td>
		  </tr>          
		  <tr>       
		      <td colspan="2" align="center" height="40">
		          <input type="submit" value="<?php echo $btnAcao; ?>" />&nbsp;&nbsp;
		          <?php if($Acao == "edit" || $Acao == "del"){ ?>
				 			<input type="button" value="Cancelar" onclick="window.location.href='index.php?p=lista_pref'" />
                  <?php }else{ ?>        
		          			<input type="reset" value="Limpar" />
                  <?php } ?>
                  <input type="hidden" name="acao" value="<?php echo $Acao; ?>" />
		      </td>
		  </tr> 
		</table>

</div>

</form>