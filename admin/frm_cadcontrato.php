<script type="text/javascript"> 

$(document).ready(function() {
	
	$("#commentForm").validate({
			rules: {
				"dtval": {
						dateBR: true
				}
			},
			messages: {		
				"dtval": {
						dateBR: "Data inv�lida."
				}
			},
	});						   
	
	$("#commentForm").validate();
});

jQuery(function($){
	$("#dtval").mask("99/99/9999");
});

function DeleteArquivo(arquivo,cdcontrato){
	if(confirm('Deseja realmente excluir o arquivo')){
		window.location.href="index.php?p=frm_cadcontrato&a="+arquivo+"&id="+cdcontrato+"&acao=edit";
	}
}

</script>

<?php

	define("DIRECT_ACCESS",  true);

	require_once("verifica.php");
	$destino = "admin/regn_contrato.php";
	
	//funcao para tratar erro
	require("admin/function_trata_erro.php");
	
	$Acao = $_GET["acao"];
	$CdContrato = (int)$_GET["id"];
	$CdForn = (int)$_GET["id_forn"];
	
	//apaga arquivo
	if ($_GET["a"] != "" && $CdContrato > 0){
		require("conecta.php");
		$qry = mysqli_query($db,"UPDATE tbcontrato SET Arquivo=NULL WHERE CdContrato=$CdContrato");
		$local = $_GET["a"];
		@unlink('admin/contratos/'.$local);
	}
	
	//value do botao de submit do formulario
	$btnAcao = "Cadastrar";
	
	//carrega os dados do contrato
	if ($CdContrato > 0)
	{
			require("conecta.php");
			
			$sql = "SELECT CdContrato,CdForn,Descricao,DtValidade,Arquivo
					FROM tbcontrato 
					WHERE CdContrato=".$CdContrato; 					
			
			$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_contrato&id_forn=$CdForn','frm_cadcontrato:select dados'));
			if (mysqli_num_rows($qry) == 1){
				 $dados_contrato = mysqli_fetch_array($qry);
				 $CdForn = $dados_contrato["CdForn"];
			}
			else{
				echo '<script language="JavaScript" type="text/javascript"> 
					alert("Contrato não encontrado!");
					window.location.href="index.php?p=lista_contrato";				
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
							 $btnAcao = "Cancelar";
							 break;
			}
			
	}
	
?>
<form method="POST" action="<?php echo $destino; ?>" id="commentForm" enctype="multipart/form-data">

<div id="frms">

		<table width="100%" border="0" bgcolor="#FFFFFF" cellspacing="5">
		  <tr>
		      <td height="40" colspan=2 align="center">
		      <h4>Cadastro de Contrato</h4></td>
		  </tr>
          <tr>
		      <td width="80">C&oacute;digo:</td>
		      <td><input type="text" name="cd_contrato" size="10" readonly="readonly" value="<?php if(isset($dados_contrato["CdContrato"])) echo $dados_contrato["CdContrato"]; else echo "Autom&aacute;tico"; ?>" /></td>
		  </tr>
		  <tr>
		      <td>Descri&ccedil;&atilde;o:</td>
	          <td><input type="text" name="descricao" size="60" class="required" value="<?php echo $dados_contrato["Descricao"]; ?>" /></td>
		  </tr>
           <tr>
		      <td>Validade do Contrato:</td>
              <?php 			  		
			  		$dados_contrato["DtValidade"] = explode('-',$dados_contrato["DtValidade"]); 
					$dados_contrato["DtValidade"] = $dados_contrato["DtValidade"][2].'/'.$dados_contrato["DtValidade"][1].'/'.$dados_contrato["DtValidade"][0];
			  ?>
	          <td><input type="text" name="dtval" id="dtval" size="7" value="<?php echo $dados_contrato["DtValidade"]; ?>" onblur="if(!valida_data(this.value)){alert('Data inv�lida.'); this.value = ''; }" /></td>
		  </tr>
          <tr>
		      <td>Arquivo:</td>
	          <td>
              		<?php
						  if($dados_contrato["Arquivo"] == '')	
              					echo '<input type="file" name="arquivo" size="40" value="" />';
						  else{
						  		echo "<a href=\"admin/contratos/$dados_contrato[Arquivo]\" target=\"_blank\" title=\"Clique para abrir\">Contrato</a>";
								echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<a href=\"#\" onclick=\"DeleteArquivo('$dados_contrato[Arquivo]',$CdContrato)\">
											<img src=\"imagens/b_drop.png\" title=\"Excluir Arquivo\" alt=\"Excluir\">
									</a>";
						  }
					?>	
              </td>
		  </tr>
          <tr>
		      <td>Fornecedor:</td>
	          <td>                              
              		<select name="cd_forn">
              		<?php 
						require("conecta.php");
						$sql = "SELECT CdForn, NmReduzido FROM tbfornecedor";
						if ((int)$CdForn > 0)
						{
							$sql .= " WHERE CdForn=$CdForn";		
						}
						$sql .=  " ORDER BY NmForn";
						
						$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_contrato','frm_cadcontrato:select forn'));
						if (mysqli_num_rows($qry) > 0){
							while ($dados = mysqli_fetch_array($qry)){
								if ($dados_contrato["CdForn"] == $dados["CdForn"])
									echo '<option value="'.$dados["CdForn"].'" selected="selected">'.$dados["NmReduzido"].'</option>';	
								else
									echo '<option value="'.$dados["CdForn"].'">'.$dados["NmReduzido"].'</option>';
							}
						} 
						@mysqli_close();
						@mysqli_free_result($qry);
					?>
                    </select>
              </td>
		  </tr>
		  <tr>
		      <td colspan="2" align="center" height="40">
              <?php 
			  		if($CdForn > 0) $param = "&id_forn=$CdForn";
			  		$link_btnCancel = "window.location.href='index.php?p=lista_contrato$param'";
			  ?>
		          <input type="submit" value="<?php echo $btnAcao; ?>" />&nbsp;&nbsp;
		          <?php if($Acao == "edit" || $Acao == "del"){ ?>
				 			<input type="button" value="Cancelar" onclick="<?php echo $link_btnCancel; ?>" />
                  <?php }else{ ?>        
		          			<input type="reset" value="Limpar" />
                  <?php } ?>
                  <input type="hidden" name="acao" value="<?php echo $Acao; ?>" />
		      </td>
		  </tr> 
		</table>

</div>

</form>