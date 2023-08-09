<?php 
	require_once("verifica.php");
	
	//funcao para tratar erro
	require("admin/function_trata_erro.php");
	
	//verifica se o usuario tem permissão para acessar a pagina
	if ((int)$_SESSION["CdTpUsuario"] != 1 && (int)$_SESSION["CdTpUsuario"] != 2 && (int)$_SESSION["CdTpUsuario"] != 3 && (int)$_SESSION["CdTpUsuario"] != 4)	
	{
		echo '<script language="JavaScript" type="text/javascript"> 
			window.location.href="index.php?p=inicial";				
		  </script>';	
	}	
?>
<script type="text/javascript"> 
$(document).ready(function() {
	$("#commentForm").validate();

	$("#cd_paciente").change(function(){
		$("#cih").val($("#cd_paciente").val());
	});
	
	$("#cih").blur(function(){					 
		$("#cd_paciente").val($("#cih").val());
	});
	
	$("#linha_espec").hide();
	
	$("#cd_proc").change(function(){
		if($("#cd_proc").val() == 1 || $("#cd_proc").val() == ""){
			$("#linha_espec").hide();
			$("#cd_especificacao").removeClass("required");
		}else{	
			$("#linha_espec").show();
			$("#cd_especificacao").addClass("required");
		}
	});
	
	$('#cd_proc').change(function(){								  
		$('#cd_especificacao').attr("disabled","disabled");						  
		$('#cd_especificacao').load('admin/load_especif.php?cdproc='+$('#cd_proc').val() );
		$('#cd_especificacao').removeAttr("disabled");
	});
	
});
</script>
<form method="POST" action="admin/regn_solagd.php" id="commentForm">

<div id="frms">

		<table width="100%" border="0" bgcolor="#FFFFFF" cellspacing="5">
		  <tr>
		      <td height="40" colspan=2 align="center">
		      <h4>Solicita&ccedil;&atilde;o de Consultas e Exame Especializados</h4></td>
		  </tr>
          <tr>
		      <td width="309">Prot&oacute;colo:</td>
		      <td width="787"><input type="text" name="cd_agdcons" size="10" value="Automático" disabled="disabled" /></td>
		  </tr>
          <tr>
		      <td width="309">CIH:</td>
		      <td><input type="text" name="cih" id="cih" size="10" value="" disabled="disabled" /></td>
		  </tr>
		  <tr>
		      <td>Paciente - Data de Nascimento - Nome da M&atilde;e:</td>
	          <td>
              		<select name="cd_paciente" id="cd_paciente" class="required">
                    	<option value="">Selecione um paciente</option>
<?php                            
	
	$sql = "SELECT p.CdPaciente,p.NmPaciente,p.DtNasc,p.NmMae
                FROM tbpaciente p INNER JOIN tbbairro b ON p.CdBairro=b.CdBairro";
    
//filtra os pacientes de sua cidade
	if ((int)$_SESSION["CdOrigem"]>0)
	{
		$sql .= " WHERE p.Status='1' AND b.CdPref=".(int)$_SESSION["CdOrigem"];		
		require("conecta.php");
		$qry_saldo = mysqli_query($db,"SELECT p.CdPref,NmCidade,LimiteMax,SUM(Credito)-SUM(Debito) as Saldo 
								  FROM tbprefeitura p LEFT JOIN tbmovimentacao m ON p.CdPref=m.CdPref 
								  WHERE p.CdPref = $_SESSION[CdOrigem]
								  GROUP BY p.CdPref,NmCidade"
								 )or die (mysqli_error());
		if(mysqli_num_rows($qry_saldo) == 1){
			$saldo = mysqli_result($qry_saldo,0,'Saldo');
			$limite = mysqli_result($qry_saldo,0,'LimiteMax');
			$verif_saldo = '';
			if ($saldo <= 0 && ((0 - $saldo)  >= $limite) )
				$verif_saldo = 'disabled="disabled"';
		}
	}
	
	$sql .= " ORDER BY p.NmPaciente";
	
	require("conecta.php");
	$qry = mysqli_query($db,$sql) or die (mysqli_error());
	if (mysqli_num_rows($qry) > 0){
		while ($dados = mysqli_fetch_array($qry)){	
			$dados["DtNasc"] = explode("-",$dados["DtNasc"]);
			$dados["DtNasc"] = $dados["DtNasc"][2]."/".$dados["DtNasc"][1]."/".$dados["DtNasc"][0];
			echo '<option value="'.$dados["CdPaciente"].'">'.trim($dados["NmPaciente"]).' - '.$dados["DtNasc"].' - '.$dados["NmMae"].'</option>';
		}
	} 
	mysqli_close();
	mysqli_free_result($qry);
?>	
                    </select>
               &nbsp;<input name="btnCadPac" type="button" value="Cadastrar Paciente" onclick="javascript:window.location.href='index.php?p=frm_cadpac&pg=1'" />
              </td>
		  </tr>
          <tr>
		      <td width="309">Procedimento:</td>
		      <td>
              		<select name="cd_proc" class="required" id="cd_proc">
                    	<option value="">Selecione um procedimento</option>
<?php                            
	
	$sql = "SELECT CdProcedimento, NmProcedimento
            FROM tbprocedimento 
			WHERE Status = '1'
			ORDER BY NmProcedimento";
	
	require("conecta.php");
	$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_solagd','frm_solagd:select dados procedimento'));
	if (mysqli_num_rows($qry) > 0){
		while ($dados = mysqli_fetch_array($qry)){	
			echo '<option value="'.$dados["CdProcedimento"].'">'.$dados["NmProcedimento"].'</option>';
		}
	} 
	@mysqli_close();
	@mysqli_free_result($qry); 
?>	
                    </select>     
              </td>
		  </tr>         
          <tr id="linha_espec">
		      <td width="309">Especifica&ccedil;&atilde;o do Procedimento.:</td>
		      <td>
              		<select name="cd_especificacao" id="cd_especificacao" class="required">
                    	<option value="">Selecione</option>
                    </select>     
              </td>
		  </tr>
          <tr>
		      <td>Especialidade</td>
	          <td>
              		<select name="cd_espec" class="required">
                    	<option value="">Selecione uma especialidade</option>
<?php                            
	
	$sql = "SELECT e.CdEspec, e.NmEspec
            FROM tbespecialidade e 
			WHERE e.Status = '1'
			GROUP BY e.CdEspec, e.NmEspec
			ORDER BY NmEspec";
	
	require("conecta.php");
	$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_solagd','frm_solagd:select dados especialidade'));
	if (mysqli_num_rows($qry) > 0){
		while ($dados = mysqli_fetch_array($qry)){	
			echo '<option value="'.$dados["CdEspec"].'">'.$dados["NmEspec"].'</option>';
		}
	} 
	@mysqli_close();
	@mysqli_free_result($qry); 
?>	
                    </select>                    
              </td>
		  </tr>
          <tr>
          	   <td>Observa&ccedil;&atilde;o:</td>
          	   <td>
               		<textarea name="obs" id="obs" style="width:500px; height:80px"></textarea>
               </td>
          </tr>
          <tr>
          	   <td colspan="2"><input type="checkbox" name="urgente" id="urgente" value="1" />&nbsp;Urgente</td>
          </tr>
		  <tr>
		      <td colspan="2" align="center" height="40">
		          <input type="submit" value="Solicitar" <?php echo $verif_saldo;?> />
                  <?php 
				  		if ($verif_saldo != "") echo "&nbsp;<font color=\"#FF0000\">Sem saldo dispon&iacute;vel.</font>";
				  ?>
		      </td>
		  </tr> 
		</table>

</div>

</form>