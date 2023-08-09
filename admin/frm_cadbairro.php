<script type="text/javascript"> 
$(document).ready(function() {
	$("#commentForm").validate();
});

</script>

<?php

	define("DIRECT_ACCESS",  true);

	require_once("verifica.php");
	$destino = "admin/regn_bairro.php";
	
	//funcao para tratar erro
	require("admin/function_trata_erro.php");
	
	$Acao = $_GET["acao"];
	$CdBairro = $_GET["id"];
	
	//value do botao de submit do formulario
	$btnAcao = "Cadastrar";
	
	//carrega os dados do paciente
	if (is_numeric($CdBairro))
	{
			require("conecta.php");
			
			$sql = "SELECT CdBairro,NmBairro,CdPref
					FROM tbbairro
					WHERE CdBairro=".$CdBairro; 
								
			//exibe somente os pacientes de sua localidade					
			if ((int)$_SESSION["CdOrigem"]>0)
			{
				$sql .= " AND CdPref=".(int)$_SESSION["CdOrigem"];		
			}
			
			$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_bairro','frm_cadbairro:select dados'));
			if (mysqli_num_rows($qry) == 1){
				 $dados_bairro = mysqli_fetch_array($qry);
			}
			else{
				echo '<script language="JavaScript" type="text/javascript"> 
					alert("Bairro não encontrado!");
					window.location.href="index.php?p=lista_bairro";				
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
	
	//inclui pagina de destino
	if (isset($_GET["pg"])){
		$destino .= "?pg=1";
		unset($_SESSION["dados_pac"]);
		foreach($_GET as $campo => $valor){
			//echo $campo.' = '.$valor.'<br />';
			$_SESSION["dados_rec"][] = $valor;
		}
		//echo '<br />'.$_SESSION["dados_pac"][2];
	}
?>
<form method="POST" action="<?php echo $destino; ?>" id="commentForm">

<div id="frms">

		<table width="100%" border="0" bgcolor="#FFFFFF" cellspacing="5">
		  <tr>
		      <td height="40" colspan=2 align="center">
		      <h4>Cadastro de Bairro</h4></td>
		  </tr>
          <tr>
		      <td width="80">C&oacute;digo:</td>
		      <td><input type="text" name="cd_bairro" size="10" readonly="readonly" value="<?php if(isset($dados_bairro["CdBairro"])) echo $dados_bairro["CdBairro"]; else echo "Autom&aacute;tico"; ?>" /></td>
		  </tr>
		  <tr>
		      <td>Bairro:</td>
	          <td><input type="text" name="nm_bairro" size="50" class="required" value="<?php echo $dados_bairro["NmBairro"]; ?>" /></td>
		  </tr>
          <tr>
		      <td>Cidade:</td>
	          <td>
              		<select name="cd_pref">
              		<?php 
						require("conecta.php");
						$sql = "SELECT CdPref, NmCidade FROM tbprefeitura";
						if ((int)$_SESSION["CdOrigem"]>0)
						{
							$sql .= " WHERE CdPref=".(int)$_SESSION["CdOrigem"];		
						}
						$sql .=  " ORDER BY NmCidade";
						
						$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_bairro','frm_cadfor:select cidade'));
						if (mysqli_num_rows($qry) > 0){
							while ($dados = mysqli_fetch_array($qry)){
								if ($dados_bairro["CdPref"] == $dados["CdPref"])
									echo '<option value="'.$dados["CdPref"].'" selected="selected">'.$dados["NmCidade"].'</option>';	
								else
									echo '<option value="'.$dados["CdPref"].'">'.$dados["NmCidade"].'</option>';
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
		          <input type="submit" value="<?php echo $btnAcao; ?>" />&nbsp;&nbsp;
		          <?php if($Acao == "edit" || $Acao == "del"){ ?>
				 			<input type="button" value="Cancelar" onclick="window.location.href='index.php?p=lista_bairro'" />
                  <?php }else{ ?>        
		          			<input type="reset" value="Limpar" />
                  <?php } ?>
                  <input type="hidden" name="acao" value="<?php echo $Acao; ?>" />
		      </td>
		  </tr> 
		</table>

</div>

</form>