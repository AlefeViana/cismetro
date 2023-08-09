<script type="text/javascript"> 
$(document).ready(function() {
	$("#commentForm").validate();
});
jQuery(function($){	
	$("#valor").maskMoney({symbol:"R$",decimal:",",thousands:"."})
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
	
	$destino = "admin/regn_addmoney.php";
	
	$CdPref = (int)$_GET["id"];
	
?>
<form method="POST" action="<?php echo $destino; ?>" id="commentForm">

<div id="frms">

		<table width="100%" border="0" bgcolor="#FFFFFF" cellspacing="5">
		  <tr>
		      <td height="40" colspan=2 align="center">
		      <h4>Movimenta&ccedil;&atilde;o Financeira</h4></td>
		  </tr>
          <tr>
		      <td width="9%">Prefeitura:</td>
	          <td width="91%">
              		<select name="cd_prefeitura">
              		<?php 
						require("conecta.php");
						$sql  = "SELECT CdPref, NmCidade 
								FROM tbprefeitura 
								WHERE Status='1' AND CdPref in (SELECT CdOrigem
																FROM tbusuario 
																WHERE CdOrigem <> 'NULL' AND Status='1'
								 								)";
						$sql .= " ORDER BY NmCidade";
						
						$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_pref','frm_addmoney:select pref'));
						if (mysqli_num_rows($qry) > 0){
							while ($l = mysqli_fetch_array($qry)){
								if ($l["CdPref"] == $CdPref)
									echo '<option value="'.$l["CdPref"].'" selected="selected">'.$l["NmCidade"].'</option>';	
								else
									echo '<option value="'.$l["CdPref"].'">'.$l["NmCidade"].'</option>';
							}
						} 
						mysqli_close();
						mysqli_free_result($qry);
					?>
                    </select>
       
              </td>
		  </tr>       
		  <tr>
		      <td>Valor:</td>
	          <td><input type="text" name="valor" id="valor" size="10" class="required" value="" /></td>
		  </tr>  
          <tr>
		      <td>Tipo:</td>
	          <td>
              		<select name="tpentrada">
                    	<option value="1">FPM</option>
                        <option value="2">Dep. Extra</option>  
                        <option value="5">Consaude Mental</option>                       
                    </select>
              </td>
		  </tr>          
		  <tr>
		      <td colspan="2" align="center" height="40">
		          <input type="submit" value="Salvar" style="margin-right:40px" />
		          <input type="button" value="Cancelar" onclick="window.location.href='index.php?p=lista_pref'" />                 		        
		      </td>
		  </tr> 
		</table>

</div>

</form>