<script type="text/javascript"> 
$(document).ready(function() {
	$("#commentForm").validate();
});

</script>

<?php

	define("DIRECT_ACCESS",  true);
	
	require_once("verifica.php");
	
	//funcao para tratar erro
	require("admin/function_trata_erro.php");
	
	//verifica se o usuario tem permiss�o para acessar a pagina
	if ((int)$_SESSION["CdTpUsuario"] != 1 && (int)$_SESSION["CdTpUsuario"] != 2)	
	{
		echo '<script language="JavaScript" type="text/javascript"> 
			window.location.href="index.php?p=inicial";				
		  </script>';	
	}	
	$destino = "admin/regn_especialidade.php";
	
	$Acao = $_GET["acao"];
	$CdEspec = $_GET["id"];
	//value do botao de submit do formulario
	$btnAcao = "Cadastrar";
	//carrega os dados
	if (is_numeric($CdEspec))
	{
			require("conecta.php");
			
			$sql = "SELECT CdEspec,NmEspec,Status
					FROM tbespecialidade
					WHERE CdEspec=".$CdEspec; 
			
			$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_espec','frm_cadespec:select dados'));
			if (mysqli_num_rows($qry) == 1){
				 $dados = mysqli_fetch_array($qry);
			}
			else{
				echo '<script language="JavaScript" type="text/javascript"> 
					alert("Especialidade n�o encontrada!");
					window.location.href="index.php?p=lista_espec";				
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
		      <h4>Cadastro de Especialidade</h4></td>
		  </tr>
          <tr>
		      <td width="80">C&oacute;digo:</td>
		      <td><input type="text" name="cd_espec" size="10" readonly="readonly" value="<?php if(isset($dados["CdEspec"])) echo $dados["CdEspec"]; else echo "Autom&aacute;tico"; ?>" /></td>
		  </tr>
		  <tr>
		      <td>Especialidade:</td>
	          <td><input type="text" name="nm_espec" size="50" class="required" value="<?php echo $dados["NmEspec"]; ?>" /></td>
		  </tr>
           <tr>
		      <td>Situa&ccedil;&atilde;o:</td>
	          <td>
              		<select name="status">
                    	<option value="1" <?php if($dados["Status"] == 1) echo 'selected="selected"'; ?> >Ativo</option>
                        <option value="2" <?php if($dados["Status"] == 2) echo 'selected="selected"'; ?> >Inativo</option>
                    </select>
              </td>
		  </tr>
		  <tr>
		      <td colspan="2" align="center" height="40">
		          <input type="submit" value="<?php echo $btnAcao; ?>" />&nbsp;&nbsp;
		          <?php if($Acao == "edit" || $Acao == "del"){ ?>
				 			<input type="button" value="Cancelar" onclick="window.location.href='index.php?p=lista_espec'" />
                  <?php }else{ ?>        
		          			<input type="reset" value="Limpar" />
                  <?php } ?>
                  <input type="hidden" name="acao" value="<?php echo $Acao; ?>" />
		      </td>
		  </tr> 
		</table>

</div>

</form>