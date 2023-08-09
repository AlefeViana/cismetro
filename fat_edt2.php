	<?php 
	$i = $_GET['i'];
	
	switch($i)
	{
		case "28": // faturamento 
		?>		
			<script type="text/javascript"> 
			$(document).ready(function() {	
			
			$("#frm1").validate({
				
				});	
			jQuery(function($){
				$("#dtinicio").mask("99/99/9999");
				$("#dttermino").mask("99/99/9999");
			});
			
			});
			</script>
			
			<h1>Controle &raquo; Faturamento </h1>

				<form action="javascript:abrirpop('popfatedt2.php','','990','700','yes')" method="post" target="_blank" id="frm1" >
					
					
				   <label  >  Tipo
					<select name="cdrelfat" id="cdrelfat" class="required">
						<option value="" >   </option>
						<option value="1"> Fornecedor Por Municipio  </option>
						<option value="2"> Municipio Por Fornecedor  </option>
			<!--			<option value="3"> Municipio Por Fornecedor PPI  </option> -->
					 </select>
				   </label>
					
					
					<label >Fornecedor
					  <select name="cd_forn" class="required">
								<option value="">  </option>
								<option value="0"> Todos </option>
								<?php 
									require("conecta.php");
									$sql = "SELECT tbfornecedor.CdForn, tbfornecedor.NmForn
									FROM tbfornecedor ORDER BY NmForn";
									
									$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_bairro','frm_cadfor:select cidade'));
									if (mysqli_num_rows($qry) > 0){
										while ($dados = mysqli_fetch_array($qry)){
											if ($dados_bairro["CdForn"] == $dados["CdForn"])
												echo '<option value="'.$dados["CdForn"].'" selected="selected">'.$dados["NmForn"].'</option>';	
											else
												echo '<option value="'.$dados["CdForn"].'">'.$dados["NmForn"].'</option>';
										}
									} 
									@mysqli_close();
									@mysqli_free_result($qry);
								?>
								</select>
						 </label>
					
					
					
					
					<label  >Municipio
					  <select name="cd_pref" class="required">
								<option value="">  </option>
								<option value="0"> Todos </option>
								<?php 
									require("conecta.php");
									$sql = "SELECT CdPref, NmCidade FROM tbprefeitura";
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
						 </label>
				
					
					
					
					</label>    	
					<label style="clear:both"> Data de Inicio <input type="text" name="dtinicio" id="dtinicio" class="required" ></label>    	
					<label> Data de Termino <input type="text" name="dttermino" id="dttermino" class="required"  ></label>    	
			
					<div id="btns"> 
						<input type="submit" value="Alterar">

					
					</div>    		
				</form>
				

	<?php 
		break;	
		
	}
?>




















