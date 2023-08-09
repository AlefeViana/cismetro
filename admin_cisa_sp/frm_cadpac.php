<script type="text/javascript"> 
$(document).ready(function() {	

	$("#commentForm").validate({
		rules: {
			"cpf_paciente": {
					required: false,
					verificaCPF: true
			},
			"dtnasc_paciente": {
					dateBR: true
			}
		},
		messages: {
			"cpf_paciente": {
			required: "Digite seu cpf.",
			verificaCPF: "CPF inválido."
			},
			"dtnasc_paciente": {
					dateBR: "Data inválida."
			}
		},
	});
	
	$("#commentForm").validate();
	
	$('#cidade').change(function(){	
		$('#bairro').load('admin/load_bairro.php?cdpref='+$('#cidade').val() , function(response, status, xhr) {
																				  if (status == "error") {
																						var msg = "Sorry but there was an error: ";
																						$("#error").html(msg + xhr.status + " " + xhr.statusText);
																						alert(msg + xhr.status + " " + xhr.statusText);
																				  }
																				});
	});
	
	$('#btncadbairro').click(function(){
		var link='';
	
		link += '&nmpac='  + $("#nome").val();
		link += '&rg='    + $("#rg").val();
		link += '&cpf='   + $("#cpf").val();
		link += '&docs='  + $("#docs").val();
		link += '&sexo='  + $("#sexo").val();
		link += '&data='  + $("#data").val();
		link += '&mae='   + $("#mae").val();
		link += '&pai='   + $("#pai").val();
		link += '&tel='   + $("#tel").val();
		link += '&cel='   + $("#cel").val();
		link += '&email=' + $("#email").val();
		link += '&logr='  + $("#logradouro").val();
		link += '&num='   + $("#num").val();
		link += '&compl=' + $("#compl").val();
		link += '&cdpac=' + $("#cd").val();
		
		window.location.href='index.php?p=frm_cadbairro&pg=1'+link;
	});
	
	
	
});

jQuery(function($){
	$("#data").mask("99/99/9999");
	$("#tel").mask("(99)9999-9999");
	$("#cel").mask("(99)9999-9999");
	$("#cpf").mask("999.999.999-99");
	$("#cep").mask("99999-999");
});


</script>

<?php
	require_once("verifica.php");
	$destino = "admin/regn_pac.php";
	
	//funcao para tratar erro
	require("admin/function_trata_erro.php");
	
	$Acao = $_GET["acao"];
	$CdPac = $_GET["id"];
	
	//value do botao de submit do formulario
	$btnAcao = "Cadastrar";
	
	//identifica se é a primeira chamada quando o usuario entra no modo de edição, utilizado para limpar as variaveis de sessão
	$first_time = (int)$_GET["first"];
	if($first_time === 1){
		unset($_SESSION["dados_rec"]);
		unset($_SESSION["form"]);
	}
	//carrega os dados da sessao nas variaveis de controle do formulario
	if(isset($_SESSION["form"][0])){
		$destino = $_SESSION["form"][0];
		$btnAcao = $_SESSION["form"][1];
		$Acao 	 = $_SESSION["form"][2];
	}
	
	//carrega os dados do paciente
	if (is_numeric($CdPac))
	{
			require("conecta.php");
			
			$sql = "SELECT CdPaciente,p.CdBairro,NmPaciente,RG,CPF,
						   OutrosDocs,Sexo,DtNasc,Naturalidade,Nacionalidade,NmMae,NmPai,Telefone,Celular,
						   Email,Profissao,Logradouro,Numero,Compl,CEP,b.CdPref,p.Referencia
					FROM tbpaciente p INNER JOIN tbbairro b ON p.CdBairro=b.CdBairro
					WHERE CdPaciente=".$CdPac; 
								
			//exibe somente os pacientes da localidade do usuario logado					
			if ((int)$_SESSION["CdOrigem"]>0)
			{
				$sql .= " AND CdPref=".(int)$_SESSION["CdOrigem"];		
			}
			
			$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_pac','frm_cadpac:select dados'));
			if (mysqli_num_rows($qry) == 1){
				 $dados_pac = mysqli_fetch_array($qry);
			}
			else{
				echo '<script language="JavaScript" type="text/javascript"> 
					alert("Paciente não encontrado!");
					window.location.href="index.php?p=lista_pac";				
			  		</script>';	
			}
			@mysqli_free_result($qry);
			@mysqli_close();
			
			switch ($Acao)
			{
				case "edit": $destino .= "?acao=edit";
							 $btnAcao = "Salvar";
							 $_SESSION["form"][0] = $destino;
							 $_SESSION["form"][1] = $btnAcao;
							 $_SESSION["form"][2] = $Acao;
							 break;
				case "del" : $destino .= "?acao=del";
							 $btnAcao = "Excluir";
							 break;
			}
			
	}
	
	//recupera o codigo do paciente na sessao
	if ((int)$_SESSION["dados_rec"][16] > 0){
		$CdPac = $_SESSION["dados_rec"][16];
	}
	
	//Recupera dados salvo na sessão
	if(isset($_SESSION["dados_rec"])){
		$dados_pac["NmPaciente"] = $_SESSION["dados_rec"][2];
		$dados_pac["RG"] = $_SESSION["dados_rec"][3];
		$dados_pac["CPF"] = $_SESSION["dados_rec"][4];
		$dados_pac["OutrosDocs"] = $_SESSION["dados_rec"][5];
		$dados_pac["Sexo"] = $_SESSION["dados_rec"][6];
		//colocar a data no formato americano e testar o cadastro de bairro na edição do paciente
		$dados_pac["DtNasc"] = $_SESSION["dados_rec"][7];
		if (isset($dados_pac["DtNasc"])){
				$dados_pac["DtNasc"] = explode("/",$dados_pac["DtNasc"]);
				$dados_pac["DtNasc"] = $dados_pac["DtNasc"][2]."-".$dados_pac["DtNasc"][1]."-".$dados_pac["DtNasc"][0];
		}
		$dados_pac["NmMae"] = $_SESSION["dados_rec"][8];
		$dados_pac["NmPai"] = $_SESSION["dados_rec"][9];
		$dados_pac["Telefone"] = $_SESSION["dados_rec"][10];
		$dados_pac["Celular"] = $_SESSION["dados_rec"][11];
		$dados_pac["Email"] = $_SESSION["dados_rec"][12];
		$dados_pac["Logradouro"] = $_SESSION["dados_rec"][13];
		$dados_pac["Numero"] = $_SESSION["dados_rec"][14];
		$dados_pac["Compl"] = $_SESSION["dados_rec"][15];
		$dados_pac["CdPaciente"] = $_SESSION["dados_rec"][16];
	}
	
	//inclui pagina de destino
	if (isset($_GET["pg"]))
		$destino .= "?pg=1";
?>

<form method="POST" action="<?php echo $destino; ?>" id="commentForm">

<div id="frms">

		<table width="100%" border="0" bgcolor="#FFFFFF" cellspacing="5">
		  <tr>
		      <td height="40" colspan=2 align="center">
		      <h4>Cadastro de Paciente</h4></td>
		  </tr>
          <tr>
		      <td width="150">CIH:</td>
		      <td><input type="text" name="cd_paciente" id="cd" size="10" value="<?php if(isset($dados_pac["CdPaciente"])) echo $dados_pac["CdPaciente"]; else echo "Autom&aacute;tico"; ?>" readonly="readonly" /></td>
		  </tr>
		  <tr>
		      <td width="150">Nome*:</td>
		      <td><input type="text" class="required" name="nm_paciente" id="nome" size="50" value="<?php echo $dados_pac["NmPaciente"]; ?>" /></td>
		  </tr>
		  <tr>
		      <td width="150">RG:</td>
		      <td><input type="text" name="rg_paciente" id="rg" size="14" value="<?php echo $dados_pac["RG"]; ?>"/></td>
		  </tr>
          <tr>
		      <td width="150">CPF:</td>
		      <td><input type="text" name="cpf_paciente" id="cpf" size="14" value="<?php echo $dados_pac["CPF"]; ?>" /></td>
		  </tr>
          <tr>
		      <td width="150">Outros Ducumentos:</td>
		      <td><input type="text" name="docs_paciente" id="docs" size="25" value="<?php echo $dados_pac["OutrosDocs"]; ?>" /></td>
		  </tr>
          <tr>
		      <td width="150">Sexo*:</td>
		      <td>
              		<select name="sexo_paciente" id="sexo">
              				<option value="F" <?php  if($dados_pac["Sexo"] == "F") echo 'selected="selected"'; ?> >Feminino</option>
                            <option value="M" <?php  if($dados_pac["Sexo"] == "M") echo 'selected="selected"'; ?> >Masculino</option>
              		</select>
              </td>
		  </tr>
          <?php 
		  		if (isset($dados_pac["DtNasc"])){
					$dados_pac["DtNasc"] = explode("-",$dados_pac["DtNasc"]);
					$dados_pac["DtNasc"] = $dados_pac["DtNasc"][2]."/".$dados_pac["DtNasc"][1]."/".$dados_pac["DtNasc"][0];
				}
		  ?>
          <tr>
		      <td width="150">Data de Nascimento*:</td>
		      <td><input type="text" name="dtnasc_paciente" id="data" size="11" value="<?php echo $dados_pac["DtNasc"]; ?>" /></td>
		  </tr>
          <tr>
		      <td width="150">Naturalidade*:</td>
		      <td><input type="text" class="required" name="naturalidade" id="naturalidade" size="40" value="<?php echo $dados_pac["Naturalidade"]; ?>" /></td>
		  </tr>
          <tr>
		      <td width="150">Nacionalidade*:</td>
		      <td><input type="text" class="required" name="nacionalidade" id="nacionalidade" size="20" value="<?php if ($dados_pac["Nacionalidade"] != '') echo $dados_pac["Nacionalidade"]; else echo 'Brasileira'; ?>" /></td>
		  </tr>
          <tr>
		      <td width="150">Nome da M&atilde;e*:</td>
		      <td><input type="text" class="required" name="mae_paciente" id="mae" size="50" value="<?php echo $dados_pac["NmMae"]; ?>" /></td>
		  </tr>
          <tr>
		      <td width="150">Nome do Pai:</td>
		      <td><input type="text" name="pai_paciente" id="pai" size="50" value="<?php echo $dados_pac["NmPai"]; ?>" /></td>
		  </tr>
          <tr>
		      <td width="150">Telefone:</td>
		      <td><input type="text" name="tel_paciente" id="tel" size="13" value="<?php echo $dados_pac["Telefone"]; ?>" /></td>
		  </tr>
          <tr>
		      <td width="150">Celular:</td>
		      <td><input type="text" name="cel_paciente" size="13" id="cel" value="<?php echo $dados_pac["Celular"]; ?>" /></td>
		  </tr>
          <tr>
		      <td width="150">E-mail:</td>
		      <td><input type="text" name="email_paciente" id="email" class="email" size="35" value="<?php echo $dados_pac["Email"]; ?>" /></td>
		  </tr>
          <tr>
		      <td width="150">Profiss&atilde;o:</td>
		      <td><input type="text" name="profissao" id="profissao" size="45" value="<?php echo $dados_pac["Profissao"]; ?>" /></td>
		  </tr>
          <tr>
		      <td width="150">Logradouro*:</td>
		      <td><input type="text" name="log_paciente" id="logradouro" class="required" size="40" value="<?php echo $dados_pac["Logradouro"]; ?>" /></td>
		  </tr>
          <tr>
		      <td width="150">N&uacute;mero*:</td>
		      <td><input type="text" name="num_paciente" id="num" class="required digits" size="8" value="<?php echo $dados_pac["Numero"]; ?>" /></td>
		  </tr>
          <tr>
		      <td width="150">Complemento:</td>
		      <td><input type="text" name="compl_paciente" id="compl" size="18" value="<?php echo $dados_pac["Compl"]; ?>" /></td>
		  </tr>
          <tr>
          	  <td width="150">Cidade*:</td>
		      <td>
              		<select name="cidade" id="cidade" class="required">
                  		<option value="">Selecione</option>
                    <?php 
						//limpa variavel que mantem os dados digitados
						unset($_SESSION["dados_pac"]);
						require("conecta.php");
						$sql = "SELECT CdPref, NmCidade FROM tbprefeitura";
						if ((int)$_SESSION["CdOrigem"]>0)
						{
							$sql .= " WHERE CdPref=".(int)$_SESSION["CdOrigem"];		
						}
						else
						{
							$sql .= " WHERE CdPref in (SELECT CdOrigem
								 					   FROM tbusuario 
								 					   WHERE CdOrigem <> 'NULL' AND Status='1'
								 					   )";							
						}
						$sql .=  " ORDER BY NmCidade";
						
						$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_pac','frm_cadpac:select cidade'));
						if (mysqli_num_rows($qry) > 0){
							while ($dados = mysqli_fetch_array($qry)){
								if ($dados_pac["CdPref"] == $dados["CdPref"])
									echo '<option value="'.$dados["CdPref"].'" selected="selected">'.$dados["NmCidade"].'</option>';	
								else
									echo '<option value="'.$dados["CdPref"].'">'.$dados["NmCidade"].'</option>';
							}
						} 
						mysqli_close();
						mysqli_free_result($qry);
					?>
                            
                    </select>
              
              </td>	
          </tr>
          <tr>
          	  <td width="150">Bairro*:</td>
		      <td>
              		<select name="bairro" id="bairro" class="required">               
                            <option value="">Selecione uma cidade primeiro</option>
                    <?php 
							if ($dados_pac["CdPref"] != ""){
									require("conecta.php");
									$sql  = "SELECT CdBairro, NmBairro FROM tbbairro WHERE CdPref=".$dados_pac["CdPref"];
									$sql .= " ORDER BY NmBairro";
									
									$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_pac','frm_cadpac:select bairro'));
									if (mysqli_num_rows($qry) > 0){
										while ($dados = mysqli_fetch_array($qry)){
											if ($dados_pac["CdBairro"] == $dados["CdBairro"])
												echo '<option value="'.$dados["CdBairro"].'" selected="selected">'.$dados["NmBairro"].'</option>';	
											else
												echo '<option value="'.$dados["CdBairro"].'">'.$dados["NmBairro"].'</option>';
										}
									} 
									mysqli_close();
									mysqli_free_result($qry);
							}
					?>
                    </select>
                   &nbsp;&nbsp;
                   <?php 
						if($_SESSION["CdTpUsuario"] == 7)
							$habilitado = 'disabled="disabled"';
						else
							$habilitado = '';	
			  		?>
                   <?php if($Acao != "del"){ ?>
                   			<input name="btncadbairro" id="btncadbairro" type="button" value="Cadastrar Bairro"  <?php echo $habilitado; ?> />
                   <?php } ?>        
              
              </td>	
          </tr>
          <tr>
		      <td width="150">CEP*:</td>
		      <td><input type="text" name="cep_paciente" class="required" id="cep" size="18" value="<?php echo $dados_pac["CEP"]; ?>" /></td>
		  </tr>
          <tr>
		      <td width="150">Refer&ecirc;ncia Moradia:</td>
		      <td><input type="text" name="referencia" id="referencia" size="50" value="<?php echo $dados_pac["Referencia"]; ?>" /></td>
		  </tr>
		  <tr>
		      <td colspan="2" align="center" height="40">
		          <input type="submit" value="<?php echo $btnAcao; ?>" <?php echo $habilitado; ?> />&nbsp;&nbsp;
                 <?php if($Acao == "edit" || $Acao == "del"){ ?>
				 			<input type="button" value="Cancelar" onclick="window.location.href='index.php?p=lista_pac'" />
                 <?php }else{ ?>        
		          			<input type="reset" value="Limpar" />
                 <?php } ?>           
                  <input type="hidden" name="acao" value="<?php echo $Acao; ?>" />
		      </td>
		  </tr> 
		</table>

</div>

</form>