<?php
	require_once("verifica.php");
    require("../conecta.php");

    header("Content-Type: text/html; charset=ISO-8859-1", true);

    $count = $_POST["count"];

    echo $count;

	echo "<div class='solicitacoes_grupotfd'>
	     <div class='seltfd'>
		<table width='1260' id='table' class='itens'>
		<tr>	    
		  </tr>
			
		    <tr>
		
		  </tr>

		    <tr>	
		       <td> Procedimento </td>
		         <td>  
		            <select name='cd_proc[]' required id='cd_proctfd$count' class='procs' style='width: 150px; height: 30px;'>
		                <option value=''>Selecione um procedimento</option>   ";                      
		                      
		                        $sql = "SELECT p.CdProcedimento, p.NmProcedimento
										FROM tbprocedimento AS p
										INNER JOIN tbespecproc ON p.CdProcedimento = tbespecproc.CdProcedimento
										WHERE p.`Status` = '1'
										GROUP BY Nmprocedimento
										ORDER BY p.NmProcedimento ASC";
																
		                        //require("conecta.php");
		                        $qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_solagd','frm_solagd:select dados procedimento'));
		                        if (mysqli_num_rows($qry) > 0){
		                            while ($dados = mysqli_fetch_array($qry)){	
		                                echo '<option value="'.$dados["CdProcedimento"].'">'.$dados["NmProcedimento"].'</option>';
		                            }
		                        } 
		                        @mysqli_close();
		                        @mysqli_free_result($qry); 
								@mysqli_free_result($sql_foto); 
		                      	echo "</select>";
		              
		 echo" </td>
			        <td> Especifica&ccedil;&atilde;o do Procedimento </td>
			        <td >  
			            <select name='cd_espectfd[]' id='cd_especificacaotfd$count' class='especs' required style='width: 350px; height: 30px;'>
			                <option value=''>Selecione</option>
			            </select>     
			        </td>
		        </td>";
		echo '<tr>
			<td> Observa&ccedil;&otilde;es  (255 Caracteres)</td>
			<td>
				<textarea name="obstfd[]" id="obs$count" style="width:400px; height:80px"  onkeyup="blocTexto(this.value)"></textarea>
			</td>
            <td> Urgente </td> 
            <td>
            	<label>
            		<input type="checkbox" name="urgentetfd[]" id="urgente$count" value="1" />
            	</label>
			</td>
			<td style="width: 100px;"">
	        	<button class="removerSelect btn btn-default" type="button"> X </button>
	        </td>
    	</tr>
    	<tr class="hide-msg" style="display: none;">
	        <td colspan="5"><div class="msg" style="margin-top: 2px; color: red; margin-left: 2px; font-size: 15px; font-weight: bold;">fdfds dsfsd</div></td>
	    </tr>';
    	echo "
		</table>
		<br/>
		 </div>
	</div>  ";
?>
