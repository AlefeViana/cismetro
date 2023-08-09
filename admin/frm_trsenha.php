<script type="text/javascript"> 
$(document).ready(function() {	

	$("#commentForm").validate({
		rules: {
			senha: {
				required: true,
				minlength: 5
			},
			confirm_senha: {
				required: true,
				minlength: 5,
				equalTo: "#senha"
			}
		},
		messages: {
			senha: {
				required: "Este campo &eacute; requerido.",
				minlength: "Sua senha deve ter pelo menos 5 caracteres"
			},
			confirm_senha: {
				required: "Este campo &eacute; requerido.",
				minlength: "Sua senha deve ter pelo menos 5 caracteres",
				equalTo: "Digite uma senha igual a nova senha"
			}
		}
	});

	$("#commentForm").validate();

});
</script>	
<form method="POST" action="admin/regn_trsenha.php" id="commentForm">
<center>
<div id="frms">

		<table width="100%" height="100%" border="0" bgcolor="#FFFFFF" cellspacing="5">
		  <tr>
		      <td height="40" colspan=2 align="center">
		      	<font color="000000" face="Verdana,Arial" size="4">Trocar de senha</font>
              </td>
		  </tr>
		  <tr>
		      <td width="100" align="left">Senha Atual: </td>
		      <td align="left"><input type="password" name="senha_atual" class="required" /></td>
		  </tr>
		  <tr>
		      <td align="left">Nova Senha: </td>
		      <td align="left"><input type="password" name="senha" id="senha" /></td>
		  </tr>
          <tr>
		      <td align="left">Confirma Senha: </td>
		      <td align="left"><input type="password" name="confirm_senha" id="confirm_senha" /></td>
		  </tr>
		  <tr>
		      <td colspan="2" align="center" height="40">
		          <input type="submit" value="Trocar" />&nbsp;&nbsp;
		          <input type="reset" value="Limpar" />
		      </td>
		  </tr> 
		</table>

</div>
</center>
</form>