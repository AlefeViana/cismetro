<script type="text/javascript"> 

<script type="text/javascript"> 
$(document).ready(function() {	

	$("#commentForm").validate(});

jQuery(function($){
	$("#data").mask("99/99/9999");
	$("#tel").mask("(99)9999-9999");
	$("#cel").mask("(99)9999-9999");
	$("#cpf").mask("999.999.999-99");
	$("#cep").mask("99999-999");
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
	$destino = "admin/regn_for.php";
	
	$Acao = $_GET["acao"];
	$CdForn = $_GET["id"];
	//value do botao de submit do formulario
	$btnAcao = "Cadastrar";
	//carrega os dados
	if (is_numeric($CdForn))
	{
			require("conecta.php");
			
			$sql = "SELECT CdForn,NmForn,NmReduzido,IE,CNPJ,CNES,
							Telefone,Fax,NmResp,TelResp,Email,Logradouro,Numero,Compl,Bairro,CEP,CdCidade
					FROM tbfornecedor
					WHERE CdForn=".$CdForn; 
			
			$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_for','frm_cadfor:select dados'));
			if (mysqli_num_rows($qry) == 1){
				 $dados = mysqli_fetch_array($qry);
			}
			else{
				echo '<script language="JavaScript" type="text/javascript"> 
					alert("Fornecedor n�o encontrado!");
					window.location.href="index.php?p=lista_for";				
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
<form method="POST" action="admin/regn_for.php" id="commentForm">

<div id="frms">

		<table width="100%" border="0" bgcolor="#FFFFFF" cellspacing="5">
		  <tr>
		      <td height="40" colspan="2" align="center">
		      <h4>Cadastro de Fornecedor</h4></td>
		  </tr>
          <tr>
		      <td width="221">C&oacute;digo:</td>
		      <td width="869"><input type="text" name="cd_forn" size="10" readonly="readonly" value="<?php if(isset($dados["CdForn"])) echo $dados["CdForn"]; else echo "Autom&aacute;tico"; ?>" /></td>
		  </tr>
		  <tr>
		      <td>Raz&atilde;o Social*:</td>
		      <td><input type="text" name="nm_forn" id="nm_forn" size="50" class="required" value="<?php echo $dados["NmForn"]; ?>" /></td>
		  </tr>
          <tr>
		      <td>Nome Reduzido*:</td>
		      <td><input type="text" name="nm_reduzido" id="nm_reduzido" size="30" class="required" value="<?php echo $dados["NmReduzido"]; ?>" /></td>
		  </tr>
		  <tr>
		      <td>IE:</td>
		      <td><input type="text" name="ie_forn" id="ie_forn" size="14" value="<?php echo $dados["IE"]; ?>" /></td>
		  </tr>
          <tr>
		      <td>CNPJ*:</td>
		      <td><input type="text" name="cnpj_forn" id="cnpj_forn" size="16" value="<?php echo $dados["CNPJ"]; ?>" /></td>
		  </tr>
          <tr>
		      <td>CNES*:</td>
		      <td><input type="text" name="cnes_forn" id="cnes_forn" class="required number" maxlength="7" size="10" value="<?php echo $dados["CNES"]; ?>" /></td>
		  </tr>         
          <tr>
		      <td>Telefone*:</td>
		      <td><input type="text" name="tel_forn" id="tel_forn" size="13" class="required" value="<?php echo $dados["Telefone"]; ?>" /></td>
		  </tr>
          <tr>
		      <td>Fax:</td>
		      <td><input type="text" name="fax_forn" id="fax_forn" size="13" value="<?php echo $dados["Fax"]; ?>" /></td>
		  </tr>
          <tr>
		      <td>Nome do Respons&aacute;vel*:</td>
		      <td><input type="text" name="nm_resp" id="nm_resp" size="50" class="required" value="<?php echo $dados["NmResp"]; ?>" /></td>
		  </tr>
          <tr>
		      <td>Tel. Respons&aacute;vel*:</td>
		      <td><input type="text" name="tel_resp" id="tel_resp" size="13" class="required" value="<?php echo $dados["TelResp"]; ?>" /></td>
		  </tr>
          <tr>
		      <td>Email:</td>
		      <td><input type="text" name="email_forn" id="email_forn" size="40" class="email" value="<?php echo $dados["Email"]; ?>" /></td>
		  </tr>
          <tr>
		      <td>Logradouro*:</td>
		      <td><input type="text" name="logr_forn" id="logr_forn" size="40" class="required" value="<?php echo $dados["Logradouro"]; ?>" /></td>
		  </tr>
          <tr>
		      <td>N&uacute;mero*:</td>
		      <td><input type="text" name="num_forn" id="num_forn" size="8" class="required number" value="<?php echo $dados["Numero"]; ?>" /></td>
		  </tr>
          <tr>
		      <td>Complemento:</td>
		      <td><input type="text" name="compl_forn" size="18" value="<?php echo $dados["Compl"]; ?>" /></td>
		  </tr>
          <tr>
		      <td>Bairro*:</td>
		      <td><input type="text" name="bairro_forn" size="18" class="required" value="<?php echo $dados["Bairro"]; ?>" /></td>
		  </tr>
          <tr>
		      <td>CEP*:</td>
		      <td><input type="text" name="cep" id="cep" size="18" class="required" value="<?php echo $dados["CEP"]; ?>" /></td>
		  </tr>
          <tr>
		      <td>Cidade*:</td>
		      <td>
              		<select name="cid_forn" class="required">
                    		<option value="">Selecione uma cidade</option>
                    <?php
						require("conecta.php");
						$sql = "SELECT CdPref, NmCidade FROM tbprefeitura WHERE Status='1' ORDER BY NmCidade";
						$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_for','frm_cadfor:select cidade'));
						if (mysqli_num_rows($qry) > 0)
							while($cidades = mysqli_fetch_array($qry)){
								if ($dados["CdCidade"] == $cidades[CdPref])
									$selecao = 'selected="selected"';
								else
									$selecao = '';
								echo "<option $selecao value=\"$cidades[CdPref]\">$cidades[NmCidade]</option>";	
							}
			  		?>        
                    </select>
              </td>
		  </tr>
          <tr height="45" valign="bottom">
		      <td align="center" colspan="2"><b>Servi&ccedil;os dispon&iacute;veis</b></td>
		  </tr>
          <tr>
		      <td align="left" colspan="2">
              <div><b>Consultas:</b></div>
          	  <div>	
              <?php
              		$sql = "SELECT CdEspec, NmEspec FROM tbespecialidade WHERE Status='1' ORDER BY NmEspec";
					$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_for','frm_cadfor:select especialidade'));
					$qtd = mysqli_num_rows($qry);
					if ($qtd > 0)
					{
						$servicos = array();
					//servicos do tipo consultas
						if (is_numeric($CdForn))
						{
							$qry1 = mysqli_query($db,"SELECT CdEspec,CdForn FROM tbfornespec WHERE CdForn=$CdForn") 
										or die (TrataErro(mysqli_errno(),'','index.php?p=lista_for','frm_cadfor:select fornespec'));
							
							//unset($servicos);
							if (mysqli_num_rows($qry1) > 0)
								while($dados1 = mysqli_fetch_array($qry1))
									$servicos[] = $dados1[CdEspec];  
							//foreach($servicos as $item)
							//	echo "-".$item;		
						}
							
						echo '<table width="820"><tr>';
						$i = 0;
						
						while($dados = mysqli_fetch_array($qry)){
							if (in_array($dados[CdEspec],$servicos))
								$check = 'checked="checked"';
							else
								$check = '';
							$i++;	

							if ($i % 4 == 0 ? $valor="</td></tr><tr>" : $valor="</td>")
								echo "<td><input type=\"checkbox\" name=\"serv_cons[]\" value=\"$dados[CdEspec]\" $check />$dados[NmEspec]".$valor;	
							
							unset($check);
						}
						echo '</table>';						
					}
			  ?>
              </div>
<?php 
			  		$sql = "SELECT CdProcedimento, NmProcedimento FROM tbprocedimento WHERE CdProcedimento > 1 AND Status='1' ORDER BY NmProcedimento";
			  		$qry_proc = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_for','frm_cadfor:select proc'));
					if (mysqli_num_rows($qry_proc) > 0)
					{
						while($dados_proc = mysqli_fetch_array($qry_proc))
						{					
							echo"<div><br /><b>$dados_proc[NmProcedimento]:</b></div>
          	  					 <div>";
              				
							$sql = "SELECT CdEspecProc, NmEspecProc 
									FROM tbespecproc 
									WHERE Status='1' AND CdProcedimento=$dados_proc[CdProcedimento] ORDER BY NmEspecProc";
							$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_for','frm_cadfor:select especproc'));
							$qtd = mysqli_num_rows($qry);
							if ($qtd > 0)
							{
								$servicos = array();
							//servicos do tipo exame
								if (is_numeric($CdForn))
								{
									$qry1 = mysqli_query($db,"SELECT CdEspecProc,CdForn FROM tbfornservicos WHERE CdForn=$CdForn") 
										or die (TrataErro(mysqli_errno(),'','index.php?p=lista_for','frm_cadfor:select fornservicos exames'));
									
									//unset($servicos);
									if (mysqli_num_rows($qry1) > 0)
										while($dados1 = mysqli_fetch_array($qry1))
											$servicos[] = $dados1[CdEspecProc]; 
									
								}
									
								echo '<table width="820"><tr>';
								$i = 0;
								
								while($dados = mysqli_fetch_array($qry)){
									if (in_array($dados[CdEspecProc],$servicos))
										$check = 'checked="checked"';
									else
										$check = '';
									  
									  $i++;	
									  if ($i % 3 == 0 ? $valor="</td></tr><tr>" : $valor="</td>")
										echo "<td><input type=\"checkbox\" name=\"forn_service[]\" value=\"$dados[CdEspecProc]\" $check />
													$dados[NmEspecProc]".$valor;	
								}
								echo '</table>';
							}
							echo "</div>";	
						}
					}
?>					           
              </td>
		  </tr>
		  <tr>
		      <td colspan="2" align="center" height="40">
		          <input type="submit" value="<?php echo $btnAcao; ?>" />&nbsp;&nbsp;
		          <?php if($Acao == "edit" || $Acao == "del"){ ?>
				 			<input type="button" value="Cancelar" onclick="window.location.href='index.php?p=lista_for'" />
                  <?php }else{ ?>        
		          			<input type="reset" value="Limpar" />
                  <?php } ?>
                  <input type="hidden" name="acao" value="<?php echo $Acao; ?>" />
		      </td>
		  </tr> 
		</table>

</div>

</form>
<?php 
@mysqli_free_result($qry);
@mysqli_free_result($qry1);
@mysqli_close();	
?>